<?php

namespace App\Http\Controllers;

use App\Domain\FileHandling\FileHandler;
use App\Domain\Sheep\TagNumber;
use App\Models\Group;
use App\Models\Sheep;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class GroupController extends Controller
{
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
        $group = Group::firstOrNew([
            'name'          => $request->name,
            'description'   => $request->description,
            'info'          => $request->info,
            'owner'         =>  $this->owner()
        ]);
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
     */
    public function postAdd(Request $request)
    {
        $group = $this->loadGroup($request);

        $csv_file = $request->csv_file;
        $process_file = new FileHandler(file($csv_file));
        $ewe_list = $process_file->mappedFile();
        $this->addIntoGroup($ewe_list[2],$group->getId()); //array of tag numbers, group number

        return View::make('groups/group_view')->with([
            'group'     => $group, //collection dismantled in view with foreach
            'title'     => 'Group Members',
            'group_name'=> $group->getName()
        ]);
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
        $tag = new TagNumber('UK)' . $request->e_flock . $request->e_tag);
        try {
            $ewe = Sheep::where([
                'flock_number' => $tag->getFlockNumber(),
                'serial_number' => $tag->getSerialNumber()])->firstOrFail();
        } catch(ModelNotFoundException $e) {
            Session::flash('alert-class', 'alert-danger');
            Session::flash('message', 'Sheep Not Found - not added');
            return Redirect::back()->withInput();
        }
        if(!$ewe->groups->contains($group->getId())) {
            $ewe->groups()->attach($group->getId(), ['owner_id' => $this->owner()]);
        }
        Session::flash('message','Sheep ' .$tag->getFlockNumber()
                    .' '.sprintf('%05d',$tag->getSerialNumber()). ' added to '
                    . $group->getName() .'.');

        return $this->getSingleToGroup();

    }

    public function postAddOnTheFly(Request $request)
    {
        $request->flashExcept('e_tag');
        $group_id = $request->group;
        $group = Group::where('id', $group_id)->first();
        $rules1 = Sheep::$rules['death'];
        $validation = Validator::make($request->all(), $rules1);
        if ($validation->fails()) {
            return View::make('groups/group_view')->with([
                'group'     => $group, //collection dismantled in view with foreach
                'title'     => 'Group Members',
                'group_name'=> $group->getName()
            ])->withErrors($validation->messages());
        }
        //dd($group);
        $tag = new TagNumber('UK)' . $request->e_flock . $request->e_tag);
        try {
            $ewe = Sheep::where([
                'flock_number' => $tag->getFlockNumber(),
                'serial_number' => $tag->getSerialNumber()])->firstOrFail();
        } catch(ModelNotFoundException $e) {
            Session::flash('alert-class', 'alert-danger');
            Session::flash('message', 'Sheep Not Found - not added');
            return Redirect::back()->withInput();
        }
        if(!$ewe->groups->contains($group->getId())) {
            $ewe->groups()->attach($group->getId(), ['owner_id' => $this->owner()]);
        }
        Session::flash('message','Sheep ' .$tag->getFlockNumber()
            .' '.sprintf('%05d',$tag->getSerialNumber()). ' added to '
            . $group->getName() .'.');

        return View::make('groups/group_view')->with([
            'group'     => $group, //collection dismantled in view with foreach
            'title'     => 'Group Members',
            'group_name'=> $group->getName()
        ]);
    }
    public function getDetach($sheep_id,$group_id)
    {
        $group = Group::find($group_id);
        $group->sheep()->detach($sheep_id);
        //dd('got to here');
        //return Redirect::back();
        //return $this->postViewGroup($group_id);
        return View::make('groups/group_view')->with([
            'group'     => $group, //collection dismantled in view with foreach
            'title'     => 'Group Members',
            'group_name'=> $group->getName()
        ]);
    }
    public function getCombine()
    {
        return View::make('groups/group_combine')->with([
            'title'         =>'Show Sheep common to 2 Groups',
            'group_names'   =>$this->groupNames()
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function postCombine(Request $request)
    {
        $group_list = Group::where('owner',$this->owner())->lists('id');//lists() returns array not collection
        $group_id_1 = $group_list[$request->group1];
        $group_id_2 = $group_list[$request->group2];
        $group1 = Group::where('id',$group_id_1)->first();
        $group2 = Group::where('id',$group_id_2)->first();

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
            'group_name'=> 'Combined Groups',
            'group1'    => $group1->getName(),
            'group2'    => $group2->getName(),
        ]);
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

        return View::make('groups/group_view')->with([
            'group'     => $group, //collection dismantled in view with foreach
            'title'     => 'Group Members',
            'group_name'=> $group->getName()
        ]);
    }
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
}
