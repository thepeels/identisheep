<?php

namespace App\Http\Controllers;

use App\Domain\FileHandling\ExcelHandler;
use App\Domain\FileHandling\FileHandler;
use App\Domain\Sheep\TagNumber;
use App\Models\Group;
use App\Models\Sheep;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        /*if (Auth::guest()) {
            return Redirect::to('../login');
        }*/
        $this->middleware('subscribed');
    }
    /**
     * @return mixed
     */
    public function getCreate()
    {
        return View::make('groups/create')->with([
            'title'     =>'Create a new Group'
            ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postCreate(Request $request)
    {
        $group = $this->firstOrNewGroup($request);
        $group->save();
        Session::flash('message','Group \''.$request->name .'\' created');

        return Redirect::to('group/create');
    }

    /**
     * @return mixed
     */
    public function getSingleToGroup()
    {
        return View::make('groups/add_single')->with([
            'title'     =>'Add to Group',
            'group_names'=>$this->groupNames()
        ]);
    }
    /**
     * @return mixed
     */
    public function getAddToGroup()
    {
        return View::make('groups/add')->with([
            'title'     =>'Add to Group',
            'group_names'=>$this->groupNames()
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postAdd(Request $request)
    {
        $group = $this->loadGroup($request);

        $csv_file = $request->csv_file;

        $type =($request->csv_file->getMimeType());//$request->file_raw->getClientOriginalName().' '.
        if(stripos($type,'text' )!==False){
            $process_file = new FileHandler(file(Input::file('csv_file')),$request->csv_file->getClientOriginalName());

            $ewe_list = $process_file->mappedFile();
        }

        if(stripos($type,'corrupt' )!==False || stripos($type,'excel' )!==False || stripos($type,'vnd' )!==False){
            $process_file = new ExcelHandler((Input::file('csv_file')),$request->csv_file->getClientOriginalName());
            $ewe_list = $process_file->returnTagNumbers();
        }

        $this->addIntoGroup($ewe_list,$group->getId()); //array of tag numbers, group number

        return $this->loadGroupView($group);
    }
    /**
     * @param array $ewe_list
     * @param int $group_id
     * @return array
     */
    private function addIntoGroup($ewe_list,$group_id)
    {
        $group = Group::find($group_id);
        $i = 0;
        foreach ($ewe_list as $ewe) {
            $tag = new TagNumber($ewe);
            if ($tag->getSerialNumber() != 0) {
                $ewe = Sheep::where([
                    'flock_number' => $tag->getFlockNumber(),
                    'serial_number'=> $tag->getSerialNumber()])->first();
                if($ewe == NULL){
                    Session::flash('alert-class','alert-danger');
                    Session::flash('message','You can not add sheep to a group that you have not added to the flock previously.');
                    return Redirect::back();
                }
                if(!$ewe->groups->contains($group->getId())) {
                    $ewe->groups()->attach($group->getId(), ['owner_id' => $this->owner()]);
                    $i++;
                }
            }
            Session::flash('message',$i.' Sheep added to ' . $group->getName() .'.');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postSingleToGroup(Request $request)
    {
        $request->flashExcept('e_tag');
        $group = $this->loadGroup($request);
        $rules1 = Sheep::$rules['death'];
        $validation = Validator::make($request->all(), $rules1);
        if ($validation->fails()) {
            return $this->getSingleToGroup()->withErrors($validation->messages());
        }
        $tag = new TagNumber('UK)' . $request->e_flock . sprintf('%05d',$request->e_tag));
        try {
            $ewe = Sheep::where([
                'flock_number' => $tag->getFlockNumber(),
                'serial_number' => $tag->getSerialNumber()])->firstOrFail();
        } catch(ModelNotFoundException $e) {
            Session::flash('alert-class', 'alert-danger');
            Session::flash('message', 'Sheep Not Found - not added');
            return Redirect::back()->withInput();//redirect()->back(); also
        }
        if(!$ewe->groups->contains($group->getId())) {
            $ewe->groups()->attach($group->getId(), ['owner_id' => $this->owner()]);
        }
        Session::flash('message','Sheep ' .$tag->getShortTagNumber() . ' added to ' . $group->getName() .'.');

        return $this->getSingleToGroup();

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postAddOnTheFly(Request $request)
    {
        $request->flashExcept('e_tag');
        $group_id = $request->group;
        $group = Group::where('id', $group_id)->first();

        $rules1 = Sheep::$rules['death'];
        $validation = Validator::make($request->all(), $rules1);
        if ($validation->fails()) {
            return $this->loadGroupView($group)->withErrors($validation->messages());
        }

        $tag = new TagNumber('UK0' . $request->e_flock . sprintf('%05d',$request->e_tag));
            try {
                $ewe = Sheep::where([
                    'flock_number' => $tag->getFlockNumber(),
                    'serial_number' => $tag->getSerialNumber()])->firstOrFail();
            } catch(ModelNotFoundException $e) {
                Session::flash('alert-class', 'alert-danger');
                Session::flash('message', 'Sheep Not Found - not added');
                return $this->loadGroupView($group);
            }
        if(!$ewe->groups->contains($group->getId())) {
            $ewe->groups()->attach($group->getId(), ['owner_id' => $this->owner()]);
        }
        Session::flash('message','Sheep ' .$tag->getFlockNumber()
            .' '.sprintf('%05d',$tag->getSerialNumber()). ' added to '
            . $group->getName() .'.');

        return $this->loadGroupView($group);
    }

    /**
     * @param int $sheep_id
     * @param int $group_id
     * @return mixed
     */
    public function getDetach($sheep_id,$group_id)
    {
        $group = Group::find($group_id);
        $group->sheep()->detach($sheep_id);

        return $this->loadGroupView($group);
    }

    /**
     * @return mixed
     */
    public function getIntersect()
    {
        return View::make('groups/group_intersect')->with([
            'title'         =>'Show Sheep common to 2 Groups',
            'group_names'   =>$this->groupNames()
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function postIntersect(Request $request)
    {
        list($group1, $group2) = $this->setUpTwoGroups($request);

        $sheep_group2 = [];
        $sheep_group1 = [];
        foreach($group2->sheep as $sheep) array_push($sheep_group2,$sheep->getId());
        foreach($group1->sheep as $sheep) array_push($sheep_group1,$sheep->getId());
        $combined = array_intersect($sheep_group2,$sheep_group1);

        $intersected = [];
        foreach($combined as $id) array_push($intersected,$sheep = Sheep::find($id));
        $collection = collect($intersected);

        return View::make('groups/group_intersect_view')->with([
            'group'     => $collection,
            'title'     => 'Group Members',
            'group_name'=> 'Group Comparison',
            'group1'    => $group1->getName(),
            'group2'    => $group2->getName(),
        ]);
    }
    /**
     * @return mixed
     */
    public function getCombine()
    {
        return View::make('groups/group_combine')->with([
            'title'         =>'Amalgamate 2 Groups to form a New Group',
            'group_names'   =>$this->groupNames()
        ]);
    }

    public function postCombine(Request $request)
    {
        list($group1, $group2) = $this->setUpTwoGroups($request);

        $group = $this->firstOrNewGroup($request);
        $group->save();

        $this->attachGroup($group1, $group);
        $this->attachGroup($group2, $group);
        $group->save();

        return $this->loadGroupView($group);

    }
    /**
     * @return mixed
     */
    public function getViewGroup()
    {
        return View::make('groups/group_selector')->with([
            'title'         =>  'Select Group',
            'group_names'   =>  $this->groupNames()
        ]);
    }

    public function postViewGroup(Request $request)
    {
        $group = $this->loadGroup($request);

        return $this->loadGroupView($group);
    }

    /**
     * @return mixed
     */
    private function owner()
    {
        return Auth::user()->id;
    }

    /**
     * @return mixed
     */
    public function groupNames()
    {
        $group_names = Group::where('owner', $this->owner())->lists('name');
        return $group_names;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function loadGroup(Request $request)
    {
        $group_list = Group::where('owner', $this->owner())->lists('id');//lists() returns array not collection
        $group_id = $group_list[$request->group];
        $group = Group::where('id', $group_id)->first();
        return $group;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function setUpTwoGroups(Request $request)
    {
        $group_list = Group::where('owner', $this->owner())->lists('id');//lists() returns array not collection
        $group_id_1 = $group_list[$request->group1];
        $group_id_2 = $group_list[$request->group2];
        $group1 = Group::where('id', $group_id_1)->first();
        $group2 = Group::where('id', $group_id_2)->first();
        return array($group1, $group2);
    }

    /**
     * @param Request $request
     * @return static
     */
    public function firstOrNewGroup(Request $request)
    {
        $group = Group::firstOrCreate([
            'name' => $request->name,
            'description' => $request->description,
            'info' => $request->info,
            'owner' => $this->owner()
        ]);
        return $group;
    }

    /**
     * @param $group1
     * @param $group
     */
    public function attachGroup($group1, $group)
    {
        $id_array = [];
        foreach ($group1->sheep as $sheep)
        {
            if (!$group->sheep->has($sheep->getId()))
            {
                array_push($id_array,$sheep->getId());
            }
            $group->sheep()->sync($id_array,false);
        }
    }

    /**
     * @param $group
     * @return mixed
     */
    public function loadGroupView($group)
    {
        return View::make('groups/group_view')->with([
            'group' => $group, //collection dismantled in view with foreach
            'title' => 'Group Members',
            'group_name' => $group->getName()
        ]);
    }

    /**
     * @return mixed
     */
    public function getDelete()
    {
        return View::make('groups/group_delete')->with([
            'title'         =>  'Select Group for Deletion',
            'group_names'   =>  $this->groupNames()
        ]);
    }

    public function postDelete(Request $request)
    {
        $group = $this->loadGroup($request);
        $group->delete();

        return $this->getDelete();
    }
}
