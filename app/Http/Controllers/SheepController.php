<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sheep;
use Auth,View,Input,Redirect,Validator,Session;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SheepController extends Controller {
    /**
     * SheepController constructor.
     *
     * cause to be Auth filtered before use
     */
    public function __construct(){
        $this->middleware('auth');
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ewes = Sheep::where('user_id',$this->user())
            ->where('sex','female')
            ->orderBy('e_flock')
            ->get();
        return view('sheeplist')->with([
            'sheep'=>$ewes,
            'title'=>'All Female Sheep'
            ]);
	}
	public static function user(){
        $user = Auth::user()->id;
        return $user;
    }
	public function getTups()
	{
		$ewes = Sheep::where('user_id',$this->user())
            ->where('sex','male')
            ->orderBy('e_flock')
            ->get();
        return view('sheeplist')->with([
            'sheep'=>$ewes,
            'title'=>'Male Sheep'
            ]);
	}
    public function getOfflist()
    {
        $ewes = Sheep::onlyTrashed()
            ->where('user_id',$this->user())
            ->orderBy('e_flock')
            ->get();
        return view('sheeplist')->with([
            'sheep'=>$ewes,
            'title'=>'Sheep Moved Off'
        ]);
    }
    public function getDeadlist()
    {
        $ewes = Sheep::onlyTrashed()
            ->where('user_id',$this->user())
            ->where('off_how','like','died'.'%')
            ->orderBy('e_flock')
            ->get();
        return view('sheeplist')->with([
            'sheep'=>$ewes,
            'title'=>'Dead List'
        ]);
    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

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
	 * @param  none
	 * @return Response
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
            'id'            =>$ewe->id,
            'title'         => 'Edit Sheep Tag Data',
            'e_flock'       =>$ewe->e_flock,
            'e_tag'         =>$ewe->e_tag,
            'original_e_flock'=>$ewe->original_e_flock,
            'colour_flock'  => $ewe->colour_flock,
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
        $rules = [
            'day'       => 'digits:2|required|min:1|max:31',
            'month'     => 'digits:2|required|min:1|max:12',
            'year'      => 'integer|required|min:2009|max:2025',
            'flock_number' => 'digits:6|required',
            'start_tag' => 'digits_between:1,5|required',
            'end_tag'   => 'digits_between:1,5|required'
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
        $move_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        if ($start_tag < $end_tag){
            $i = $start_tag;
            while ($i <= $end_tag){
                $ewe = Sheep::firstOrNew([
                    'e_flock'           =>  'UK0'.$flock_number,
                    'e_tag'             =>  $i,
                    'user_id'           =>  $id
                ]);
                $ewe->original_e_flock  = 'UK0'.$flock_number;
                $ewe->colour_flock      = 'UK0'.$flock_number;
                $ewe->original_e_tag    = $i;
                $ewe->colour_tag        = $i;
                $ewe->move_on           = $move_on;

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
    public function getAddewe()
    {
        return View::make('sheepaddewe')
            ->with([
                'title'     => 'Add a Ewe',
                'id'        =>  $this->user(),
                'sex'       => 'female'
            ]);
    }
    public function getAddtup()
    {
        return View::make('sheepaddewe')
            ->with([
                'title'     => 'Add a Tup',
                'id'        =>  $this->user(),
                'sex'       => 'male'
            ]);
    }
    public function postAddewe()
    {
        $rules = [
            'day'       => 'digits:2|required|min:1|max:31',
            'month'     => 'digits:2|required|min:1|max:12',
            'year'      => 'integer|required|min:2009|max:2025',
            'e_flock'   => 'digits:6|required',
            'e_tag'     => 'numeric|required|between:1,99999',
        ];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $e_flock        = Input::get('e_flock');
        $e_flock_number = 'UK0'.$e_flock;
        $e_tag          = Input::get('e_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $ewe = Sheep::firstOrNew([
            'e_flock'           =>  $e_flock_number,
            'e_tag'             =>  $e_tag,
            'user_id'           =>  $this->user()
        ]);
        $ewe->original_e_flock  =  $e_flock_number;
        $ewe->colour_flock      =  $e_flock_number;
        $ewe->e_tag             =  $e_tag;
        $ewe->move_on           = $move_on;
        $ewe->sex               = Input::get('sex');
        $ewe->save();

        return Redirect::back()->withInput(['e_flock'=>$e_flock,'sex'=>$ewe->sex]);
    }


    public function getDeath()
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $this->user(),

        ]);
    }
    public function postDeath()
    {
        $rules = [
            'day'       => 'digits:2|required|min:1|max:31',
            'month'     => 'digits:2|required|min:1|max:12',
            'year'      => 'integer|required|min:2009|max:2025',
            'e_flock'   => 'digits:6|required',
            'e_tag'     => 'numeric|required|between:1,99999',
        ];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $e_flock        = Input::get('e_flock');
        $e_flock_number = 'UK0'.$e_flock;
        $e_tag          = Input::get('e_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_off       = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $how_died       = ' - '.Input::get('how_died');

        $ewe = Sheep::firstOrNew([
            'e_flock'           =>  $e_flock_number,
            'e_tag'             =>  $e_tag,
            'user_id'           =>  $this->user()
        ]);
        $ewe->original_e_flock  =  $e_flock_number;
        $ewe->colour_flock      =  $e_flock_number;
        $ewe->e_tag             =  $e_tag;
        $ewe->move_off          = $move_off;
        $ewe->off_how           = 'died'.$how_died;
        $ewe->sex               = Input::get('sex');
        $ewe->save();
        $ewe->delete();

        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $this->user(),

        ]);
    }
}
