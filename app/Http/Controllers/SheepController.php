<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sheep;
use Auth,View,Input,Redirect,Validator,Session;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SheepController extends Controller {

    public function __construct(){
        $this->middleware('auth');
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{   $user = Auth::user()->id;
        //dd($user);
		$ewes = Sheep::where('user_id','=',$user)
            ->orderBy('e_flock')
            ->get();
        return view('sheeplist')->with([
            'sheep'=>$ewes,
            'title'=>'All Sheep'
            ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response

	public function getEdit($id)
	{
		$ewe = Sheep::where('id',$id)->first();
        //foreach($ewe as $sheep);
        $number = $ewe->e_flock.' '.sprintf('%05d',$ewe->e_tag);
        return 'edit sheep number '.$number;
	}
*/
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
     * @param  string $e_flock
	 * @return Redirect
	 */
	public function postChangetags()
	{
        $id = Input::get('id');
		$ewe = Sheep::where('id',$id)->first();
        if ($ewe->e_tag != Input::get('e_tag')) {
            $ewe->e_tag_2 = $ewe->e_tag_1;
            $ewe->e_tag_1 = $ewe->e_tag;
            $ewe->e_tag = Input::get('e_tag');
        }
        $ewe->e_flock = Input::get('e_flock');
        $ewe->save();
        return Redirect::to('list');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}
    /**
     * Update Tag numbers
     *
     * @param int $id
     *
     * @return view
     */
    public function getEdit($id)
    {
        $ewe = Sheep::getById($id);
        //return $ewe->id;
        return View::make('sheepedit')->with([
            'id'        =>$id,
            'title'     => 'Edit Sheep Tag Data',
            'e_flock'   =>$ewe->e_flock,
            'e_tag'     =>$ewe->e_tag
        ]);
    }
    /**
     * Update finding by tag number
     *
     * @param int $tag
     *
     * @param string $flock_number
     *
     * @return view
     */
    public function postSeek()
    {
        $flock = Input::get('flock');
        $tag    =Input::get('tag');
        $flock = 'UK0'.$flock;
        $ewe = Sheep::getByTag($flock, $tag);
            if ($ewe == NULL){Session::put('find_error','Sheep not found, check numbers and re-try.');
                return Redirect::to('sheep/seek')->withInput();
            }
        return View::make('sheepedit')->with([
            'id'        =>$ewe->id,
            'title'     => 'Edit Sheep Tag Data',
            'e_flock'   =>$ewe->e_flock,
            'e_tag'     =>$ewe->e_tag,
            'original_e_flock'=>$ewe->original_e_flock,
            'colour_flock' => $ewe->colour_flock,
        ]);
    }
    /**
     * Find sheep page
     *
     * @param none
     * @return view
     */
    public function getSeek()
    {
        return View::make('sheepfinder')->with([
            'title'     => 'Find a sheep'
        ]);
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
        $id = Auth::user()->id;
        return View::make('sheepbatch')->with([
            'id'=>$id,
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
        $rules = [
            'flock_number' => 'integer|required|min:100000|max:999999',
            'start_tag' => 'integer|required',
            'end_tag'   => 'integer|required',
            'day'       => 'integer|required|min:1|max:31',
            'month'     => 'integer|required|min:1|max:12',
            'year'      => 'integer|required|min:2009|max:2025',
        ];
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
        $date_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        if ($start_tag < $end_tag){
            $i = $start_tag;
            while ($i <= $end_tag){
                $ewe = new Sheep();
                $ewe->user_id           = $id;
                $ewe->e_flock           = 'UK0'.$flock_number;
                $ewe->original_e_flock  = 'UK0'.$flock_number;
                $ewe->colour_flock      = 'UK0'.$flock_number;
                $ewe->e_tag             = $i;
                $ewe->original_e_tag    = $i;
                $ewe->colour_tag        = $i;
                $ewe->move_on           = $date_on;

                $ewe->save();
            $i++;

            }
        }
        return Redirect::back()->withInput(
            [
                'day'           =>$d,
                'month'         =>$m,
                'year'          =>$y,
                'flock_number'  =>$flock_number
            ]);
    }
}
