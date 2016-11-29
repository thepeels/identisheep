<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sheep;
use Auth,View,Input,Redirect,Validator,Session;

class BatchController extends Controller {

    /**
     * BatchController constructor.
     *
     * Filtered by Auth() before usage.
     */
    public function __construct(){
        $this->middleware('auth');
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
        $file_raw       = Input::file('file_raw');
        $destination    = Input::get('destination');
        $user           = Auth::user()->id;
        $no_spaces = preg_replace('/\s+/', '', $file_raw);
        $rawlist = str_replace(array(",\r\n", ",\n\r", ",\n", ",\r", ", ","¶"), "", $no_spaces);
        $rawlist = str_replace(array("l"), '1', $rawlist);
        $rawlist = str_replace(array("O"), '0', $rawlist);
        $ewelist = array_map('str_getcsv', file($rawlist));

        if(Input::get('check')) {
            $i = 0;
            foreach ($ewelist[1] as $ewe) {
                $i++;
                $e_flock = substr($ewe, -11, 6);
                $e_tag = substr($ewe,-5);
                echo($i . ' ' . $e_flock . ' ' . $e_tag . "<br>");
            }
        }
        if(Input::get('load')) {
            foreach ($ewelist[1] as $ewe) {
                $e_flock = 'UK0'.substr($ewe, -11, 6);
                $e_tag = substr($ewe,-5);
                $ewe = Sheep::firstOrNew([
                    'e_flock' => $e_flock,
                    'e_tag' => $e_tag]);
                $ewe->user_id           = $user;
                $ewe->e_flock           = $e_flock;
                $ewe->e_tag             = $e_tag;
                $ewe->move_off          = '2016-11-20 00:00:00';
                $ewe->off_how           = $destination;
                $ewe->save();
                $ewe->delete();
            }
        }
        return Redirect::to('list');
    }

}
