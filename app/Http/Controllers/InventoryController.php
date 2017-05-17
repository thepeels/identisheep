<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 17/04/2017
 * Time: 09:26
 */

namespace app\Http\Controllers;

use App\Models\Sheep;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Domain\FileHandling\ExcelHandler;
use App\Domain\FileHandling\FileHandler;
use App\Domain\Sheep\TagNumber;
use App\Http\Controllers\Controller;


class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        /*if (Auth::guest()) {
            return Redirect::to('../login');
        }*/
        $this->middleware('subscribed');
    }

    private static function owner()
    {
        return Auth::user()->id;
    }
    public function getClearInventory()
    {
        return View::make('inventory/clear')->with([
            'title' => 'Clear the Sheep Inventory',
        ]);
    }

    public function postClearInventory()
    {
        $list = Sheep::where('owner',$this->owner())->get();
        foreach ($list as $list){
            $list->setInventory(false);
            $list->save();
        }

        Session::flash('message','Inventory Cleared');
        return View::make('inventory/clear')->with([
            'title' => 'Clear the Sheep Inventory',
        ]);
    }

    public function getAddSingle()
    {
        return View::make('inventory/single')->with([
            'title'     => 'Add a Sheep to the Inventory'
        ]);
    }

    /**
     * @param Request $request
     */
    public function postAddSingle(Request $request)
    {
        $country_code   = $request->country_code?:'UK0';
        $tag = new TagNumber($country_code.$request->e_flock.sprintf('%05d',$request->e_tag));
        $owner = $this->owner();
        $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
        if ($sheep_exists) {
            $ewe = Sheep::where([
                'flock_number' => $tag->getFlockNumber(),
                'serial_number' => $tag->getSerialNumber(),
                'owner' => $owner
            ])->first();
            $ewe->setInventory(true);
            $ewe->save();

            Session::flash('message','Sheep '.$tag->__toString().' added to inventory.');
        } else {
            Session::flash('message','Tag Numbers do not match any of your sheep, nothing added');
        }

        return Redirect::back()->withInput([
            'e_flock'   => $request->e_flock
        ])->with([
            'title'     => 'Add a Sheep to the Inventory'
        ]);

    }

    public function getAddList()
    {
        $not_added = [];
        return View::make('inventory/list')->with([
            'title' => 'Select a file to add sheep to inventory',
            'not_added' => $not_added
        ]);
    }

    public function postAddList(Request $request)
    {
        $type =($request->file_raw->getMimeType());
        $owner = $this->owner();

        if(stripos($type,'text' )!==False){
            $process_file = new FileHandler(file(Input::file('file_raw')),$request->file_raw->getClientOriginalName());

            $ewelist = $process_file->mappedFile();

        }

        if(stripos($type,'corrupt' )!==False || stripos($type,'excel' )!==False ||
            stripos($type,'vnd.ms-office' )!==False || stripos($type,'vnd.openxml')!==False
            || stripos($type,'vnd.openxmlformats-officedocument.spreadsheetml')!==False){//dd($type);
            $process_file = new ExcelHandler((Input::file('file_raw')),$request->file_raw->getClientOriginalName());
            $ewelist = $process_file->returnTagNumbers();
            //$some_thing = $process_file->excelFile();
            //dd($ewelist);

        };
        $request->flash();
        ($type);
        $processed = 0;
        $added = 0;
        $not_added = [];
        foreach ($ewelist as $number) {
            $tag = new TagNumber($number);
            //dd($tag->getCountryCode());
            $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
            if ($tag->getSerialNumber() != 0) {
                if ($sheep_exists) {
                    $ewe = Sheep::where([
                        'flock_number' => $tag->getFlockNumber(),
                        'serial_number' => $tag->getSerialNumber()
                    ])->first();
                    $added +=  (1 - $ewe->getInventory());
                    $ewe->setInventory(true);
                    $ewe->save();
                    $processed++;
                }else{
                    array_push($not_added,$tag->getFlockNumber().' - '.$tag->getSerialNumber());
                    //dd($not_added);
                    $processed++;
                }
            }
        }
        Session::flash('message', $processed .' Tags processed, ' . $added . ' sheep Added to Inventory.');
        return View::make('inventory/list')->with([
            'not_added' => $not_added,
            'title'     => 'Select a file to add sheep to inventory'
        ]);
    }

    public function getView()
    {
        $females = Sheep::where([
            'owner'     => $this->owner(),
            'sex'       => 'female',
            'inventory' => true,
            'alive'     => true
        ])->count();
        $males = Sheep::where([
            'owner'     => $this->owner(),
            'sex'       => 'male',
            'inventory' => true,
            'alive'     => true
        ])->count();
        return View::make('inventory/view')->with([
            'title'         => 'Inventory',
            'females'       => $females,
            'males'         => $males
        ]);
    }

    public function getViewNotIn()
    {
        $females = Sheep::where([
            'owner'     => $this->owner(),
            'sex'       => 'female',
            'inventory' => false,
            'alive'     => true
        ])->get();
        $males = Sheep::where([
            'owner'     => $this->owner(),
            'sex'       => 'male',
            'inventory' => false,
            'alive'     => true
        ])->get();

        return View::make('inventory/list-not-in')->with([
            'title'         => 'Sheep existing but not in the Inventory',
            'females'       => $females,
            'males'         => $males
        ]);
    }

    /**
     * @param $id
     */
    public function getAddGroup($id)
    {
        $added = 0;
        $processed = 0;
        $group = Group::where('id',$id)->first();
        foreach($group->sheep as $member){
            if($member->getAlive()==true){
                $added += (1 - $member->getInventory());
                $member->setInventory(true);
                $member->save();
                $processed++;
            }
        }
        Session::flash('message',$processed . ' sheep processed, ' . $added . ' added to inventory');
        return Redirect::back();
    }
    /*ToDo add 'add a group to inventory and custom list?*/
}