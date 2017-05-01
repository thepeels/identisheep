<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Sheep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Domain\FileHandling\ExcelHandler;
use App\Domain\FileHandling\FileHandler;
use App\Domain\Sheep\TagNumber;
use App\Http\Controllers\Controller;

class EditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('subscribed');
    }

    private static function owner()
    {
        return Auth::user()->id;
    }

    public function getFull()
    {
        return View::make('edit/full')->with([
            'title'     => 'Select sheep for comprehensive edit'
        ]);
    }

    public function postSelect(Request $request)
    {
        $country_code = $request->country_code ?: 'UK0';
        $tag = new TagNumber($country_code . $request->e_flock . sprintf('%05d', $request->e_tag));
        $owner = $this->owner();
        $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
        if ($sheep_exists) {
            $ewe = Sheep::where([
                'flock_number' => $tag->getFlockNumber(),
                'serial_number' => $tag->getSerialNumber(),
                'owner' => $owner
            ])->first();
            //$inventory = $ewe->getInventory();
            return View::make('edit/editor')->with([
                'ewe'   => $ewe,
                'inv'   => $ewe->getInventory(),
                'title' => 'Edit field values below'
            ]);
        } else {
            Session::flash('message','Tag Numbers do not match any of your sheep, nothing added');
            return Redirect::back()->withInput([
                'e_flock'   => $request->e_flock
            ]);
        }
    }

    /**
     * @param int $id
     * @return mixed
     */

    public function getPassThrough($id)
    {
        $ewe = Sheep::getById($id);
        return View::make('edit/editor')->with([
            'ewe'   => $ewe,
            'inv'   => $ewe->getInventory(),
            'title' => 'Edit field values below'
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function postFull(Request $request)
    {
        $rules = Sheep::$rules['death'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        //dd($request->inventory);
        $inventory = true;
        if ($request->inventory == null )$inventory = false;
        //dd($inventory);
        $ewe = Sheep::findOrFail($request->id);
        //$ewe = new Sheep;
        $ewe->setCountryCode($request->country_code);
        $ewe->setSource($request->source);
        $ewe->setSex($request->sex);
        $ewe->setInventory($inventory);
        $ewe->setSupplementaryTagFlockNumber($request->supp_flock);
        if ($ewe->getSupplementarySerialNumber()!=$request->supp_serial){
            $ewe->setColourTag2($ewe->getColourTag1());
            $ewe->setColourTag1($ewe->getSupplementarySerialNumber());
            $ewe->setSupplementarySerialNumber($request->supp_serial);
        };
        /*if ($request->inventory == true)*/$ewe->setInventory($inventory);
        if ($ewe->getFlockNumber() != $request->e_flock){
            $ewe->setFlockNumber($request->e_flock);
        }
        if ($ewe->getSerialNumber() != $request->e_tag){
            $ewe->setOlderSerialNumber($ewe->getOldSerialNumber());
            $ewe->setOldSerialNumber($ewe->getSerialNumber());
            $ewe->setSerialNumber($request->e_tag);
        }
        $ewe->save();

        return Redirect::to('edit/full')->withInput([
            'e_flock'   => $request->e_flock
        ]);
    }


}
