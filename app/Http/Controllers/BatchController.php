<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sheep;
use Auth,View,Input,Redirect,Validator,Session,DB;

class BatchController extends Controller {

    /**
     * BatchController constructor.
     *
     * Filtered by Auth() before usage.
     */
    public function __construct(){
        $this->middleware('auth');
    }
    public static function user(){
        $user = Auth::user()->id;
        return $user;
    }
    public function getBatchops()
    {
        return View::make('batchops')->with([
            'title' => 'Batch Operations',
            'subtitle' => '- Sheep Off Holding'
        ]);
    }
    public function  postCsvload()
    {
        $rules1 = Sheep::$rules['dates'];
        $rules2 = Sheep::$rules['where_to'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $file_raw       = Input::file('file_raw');
        $destination    = Input::get('destination');
        $user           = Auth::user()->id;
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_off       = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        //$no_spaces = preg_replace('/\s+/', '', $file_raw);
        $rawlist = str_replace(array(",\r\n", ",\n\r", ",\n", ",\r", ", ","¶"), "", $file_raw);
        $rawlist = str_replace(array("l"), '1', $rawlist);
        $rawlist = str_replace(array("O"), '0', $rawlist);
        $ewelist = array_map('str_getcsv', file($rawlist));

        if(Input::get('check')) {
            $i = 0;
            //dd($ewelist);
            foreach ($ewelist[2] as $ewe) {
                $i++;
                $e_flock = substr($ewe, -11, 6);
                $e_tag = substr($ewe,-5);
                echo($i . ' ' . $e_flock . ' ' . $e_tag . "<br>");
            }
            exit();
        }
        if(Input::get('load')) {
            foreach ($ewelist[2] as $ewe) {
                $e_flock = substr($ewe, -11, 6);
                $e_tag = substr($ewe,-5);
                $ewe = Sheep::firstOrNew([
                    'e_flock' => $e_flock,
                    'e_tag' => $e_tag]);
                $ewe->user_id           = $user;
                $ewe->e_flock           = $e_flock;
                $ewe->e_tag             = $e_tag;
                $ewe->move_off          = $move_off;
                $ewe->off_how           = $destination;
                $ewe->save();
                $ewe->delete();
            }
        }
        return Redirect::to('list');
    }
    public function getBatchopson()
    {
        return View::make('batchopson')->with([
            'title' => 'Batch Operations',
            'subtitle' => '- Sheep Onto Holding'
        ]);
    }
    public function  postCsvloadon()
    {
        $rules = Sheep::$rules['dates'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $file_raw       = Input::file('file_raw');
        //$destination    = Input::get('destination');
        $user           = Auth::user()->id;
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_on       = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        //$no_spaces = preg_replace('/\s+/', '', $file_raw);
        $rawlist = str_replace(array(",\r\n", ",\n\r", ",\n", ",\r", ", ","¶"), "", $file_raw);
        $rawlist = str_replace(array("l"), '1', $rawlist);
        $rawlist = str_replace(array("O"), '0', $rawlist);
        $ewelist = array_map('str_getcsv', file($rawlist));

        if(Input::get('check')) {
            $i = 0;
            //dd($ewelist);
            foreach ($ewelist[2] as $ewe) {
                $i++;
                $e_flock = substr($ewe, -11, 6);
                $e_tag = substr($ewe,-5);
                echo($i . ' ' . $e_flock . ' ' . $e_tag . "<br>");
            }
            exit();
        }
        if(Input::get('load')) {
            foreach ($ewelist[2] as $ewe) {
                $e_flock = substr($ewe, -11, 6);
                $e_tag = substr($ewe,-5);
                $ewe = Sheep::firstOrNew([
                    'e_flock' => $e_flock,
                    'e_tag' => $e_tag]);
                $ewe->user_id           = $user;
                $ewe->e_flock           = $e_flock;
                $ewe->e_tag             = $e_tag;
                $ewe->move_on         = $move_on;
                //$ewe->off_how           = $destination;
                $ewe->save();
                //$ewe->delete();
            }
        }
        return Redirect::to('list');
    }
    /**
     * Load Batch entry form
     *
     * @param none
     *
     * @return view
     */
    public function getBatch()
    {
        return View::make('sheepbatch')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of tags'
        ]);
    }

    /**
     * Post batch entry
     *
     *
     */
    public function postBatch()
    {
        $rules = Sheep::$rules['batch'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $id             = Input::get('id');
        $flock_number   = Input::get('flock_number');
        $start_tag      = Input::get('start_tag');
        $end_tag        = Input::get('end_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $colour_of_tag  = Input::get('colour_of_tag');
        $move_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        if ($start_tag <= $end_tag){
            $i = $start_tag;
            while ($i <= $end_tag){
                $ewe = Sheep::firstOrNew([
                    'e_flock'           =>  $flock_number,
                    'e_tag'             =>  $i,
                    'user_id'           =>  $id
                ]);
                $ewe->original_e_flock  = $flock_number;
                $ewe->colour_flock      = $flock_number;
                $ewe->original_e_tag    = $i;
                $ewe->colour_tag        = $i;
                $ewe->move_on           = $move_on;
                $ewe->colour_of_tag     = $colour_of_tag;

                $ewe->save();
                $i++;

            }
        }
        return Redirect::back()->withInput(
            [
                'day'           =>$d,
                'month'         =>$m,
                'year'          =>$y,
                'flock_number'  =>$flock_number,
                'colour_of_tag' =>$colour_of_tag
            ]);
    }
    /**
     * Load Batch entry form
     *
     * @param none
     *
     * @return view
     */
    public function getBatchoff()
    {
        return View::make('sheepbatchoff')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of tags'
        ]);
    }
    /**
     * Post batch entry
     *
     *
     */
    public function postBatchoff()
    {
        $rules1 = Sheep::$rules['batch'];
        $rules2 = Sheep::$rules['where_to'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $id             = Input::get('id');
        $flock_number   = Input::get('flock_number');
        $start_tag      = Input::get('start_tag');
        $end_tag        = Input::get('end_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $colour_of_tag  = Input::get('colour_of_tag');
        $off_how        = Input::get('destination');
        $move_off        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        if ($start_tag <= $end_tag){
            $i = $start_tag;
            while ($i <= $end_tag){
                $ewe = Sheep::firstOrNew([
                    'e_flock'           =>  $flock_number,
                    'e_tag'             =>  $i,
                    'user_id'           =>  $id
                ]);
                $ewe->original_e_flock  = $flock_number;
                $ewe->colour_flock      = $flock_number;
                $ewe->original_e_tag    = $i;
                $ewe->colour_tag        = $i;
                $ewe->move_off           = $move_off;
                $ewe->colour_of_tag     = $colour_of_tag;
                $ewe->off_how           = $off_how;
                $ewe->save();
                $ewe->delete();
                $i++;

            }
        }
        return Redirect::back()->withInput(
            [
                'day'           =>$d,
                'month'         =>$m,
                'year'          =>$y,
                'flock_number'  =>$flock_number,
                'colour_of_tag' =>$colour_of_tag
            ]);
    }
}
