<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Homebred;
use App\Models\Sheep;
use Auth,View,Input,Redirect,Validator,Session,Carbon\Carbon,DB;
use Illuminate\Pagination\Paginator;

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
	public function getIndex()
	{
		$ewes = Sheep::where('user_id',$this->user())
            ->where('sex','female')
            ->get();
        /*$count = Homebred::count(1)->get();
        foreach($count as $count){$thecount = $count->count;}
        'count'=>$thecount,*/
        return view('sheeplist')->with([
            'ewes'=>$ewes,
            'title'=>'All Female Sheep',
            'count'=>Sheep::where('user_id',$this->user())
                ->where('sex','female')
                ->count()
            ]);
	}
    public static function user(){
        $user = Auth::user()->id;
        return $user;
    }
    public function getList()
    {
        $ewes = Sheep::where('user_id',$this->user())
            ->where('sex','female')
            ->paginate(20);
        return view('sheeplist')->with([
            'ewes'=>$ewes,
            'title'=>'All Female Sheep',
            'count'=>Sheep::where('user_id',$this->user())
                ->where('sex','female')
                ->count()
        ]);
    }
    public function getTups()
	{
		$ewes = Sheep::where('user_id',$this->user())
            ->where('sex','male')
            ->orderBy('e_flock')
            ->paginate(20);
        return view('sheeplist')->with([
            'ewes'=>$ewes,
            'title'=>'Male Sheep',
            'count'=>Sheep::where('user_id',$this->user())
                ->where('sex','male')
                ->count()
            ]);
	}
    public function getOfflist()
    {
        $ewes = Sheep::onlyTrashed()
            ->where('user_id',$this->user())
            ->where('off_how','not like','died'.'%')
            ->orderBy('move_off','DESC')
            ->paginate(20);
        return view('sheeplist')->with([
            'ewes'=>$ewes,
            'title'=>'Sheep Moved Off'
        ]);
    }
    public function getDeadlist()
    {
        $ewes = Sheep::onlyTrashed()
            ->where('user_id',$this->user())
            ->where('off_how','like','died'.'%')
            ->orderBy('e_flock')
            ->paginate(20);
        return view('sheeplist')->with([
            'ewes'=>$ewes,
            'title'=>'Dead List'
        ]);
    }
	/**
	 * Show the form for deleting records.
	 *
	 * @return Response
	 */
	public function getDelete()
	{
        $year = date('Y', strtotime('-10 years'));
        return View::make('delete_old')->with([
            'title'         => 'Delete old Records',
            'year'          => $year

        ]);
	}
	public function postDelete()
    {
        $rules = Sheep::$rules['old_dates'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $year = Input::get('year');
        $date = Carbon::create($year,12,31,0);
        $date = $date->toDateTimeString();
        //dd($date);
        Sheep::withTrashed()->where('move_on','<=',$date)->forceDelete();

        return Redirect::to('sheep/list');

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
        //foreach($ewe as $ewes);
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
        $old_e_flock = Input::get('old_e_flock');
		$ewe = Sheep::where('id',$id)->first();
        if ($ewe->e_tag != Input::get('e_tag')) {
            $ewe->e_tag_2 = $ewe->e_tag_1;
            $ewe->e_tag_1 = $ewe->e_tag;
            $ewe->e_tag = Input::get('e_tag');
        }
        $ewe->e_flock = Input::get('e_flock');
        $ewe->save();
        return View::make('sheepfinder')->with([
            'title'     => 'Find another sheep',
            'e_flock'   => $old_e_flock]);
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
            'title'     => 'Change Tags',
            'e_flock'   =>$ewe->e_flock,
            'e_tag'     =>$ewe->e_tag
        ]);
    }
    /**
     * Update Tag numbers
     *
     * @param none
     *
     * @return view
     */
    public function getEditmore($flock_old,$flock_new)
    {
        //$ewe = Sheep::getById($id);
        //return $ewe->id;
        return View::make('sheepeditmore')->with([
            'id'        =>$id,
            'title'     => 'Change more Tags',
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

        $e_flock = Input::get('flock');
        //dd($e_flock);
        $e_tag    =Input::get('tag');
        $ewe = Sheep::getByTag($e_flock, $e_tag);
            if ($ewe == NULL){Session::put('find_error','Sheep not found, check numbers and re-try.');
                return Redirect::to('sheep/seek')->withInput();
            }
        if (Input::get('find')){
            return View::make('sheepedit')->with([
                'id'            =>$ewe->id,
                'title'         => 'Change Tags',
                'e_flock'       =>$e_flock,
                'e_tag'         =>$ewe->e_tag,
                'original_e_flock'=>$ewe->original_e_flock,
                'colour_flock'  => $ewe->colour_flock,
            ]);
        }
        if (Input::get('view')){
            return View::make('sheepview')->with([
                //'ewe'           =>$ewe,
                'id'            =>$ewe->id,
                'title'         =>'Details for Sheep number ',
                'e_flock'       =>$e_flock,
                'e_tag'         =>$ewe->e_tag,
                'e_tag_1'        =>$ewe->e_tag_1,
                'e_tag_2'        =>$ewe->e_tag_2,
                'original_e_flock'=>$ewe->original_e_flock,
                'original_e_tag'=>$ewe->original_e_tag,
                'colour_of_tag'  =>$ewe->colour_of_tag,
                'move_on'       =>$ewe->move_on,
                'sex'           =>$ewe->sex
            ]);
        }
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
        $l              = DB::table('sheep')->max('id');
        if ($start_tag < $end_tag){
            $i = $start_tag;
            while ($i <= $end_tag){
                $l++;
                $ewe = Sheep::firstOrNew([
                    'e_flock'           =>  $flock_number,
                    'e_tag'             =>  $i,
                    'user_id'           =>  $id,
                ]);
                if(!Sheep::where('e_flock',$flock_number)
                    ->where('e_tag',$i)
                    ->where('user_id',$id)){
                    $ewe->local_id      = $l;
                }
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
        $rules = Sheep::$rules['dates_and_tags'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $user_id             = $this->user();
        $e_flock        = Input::get('e_flock');
        $e_flock_number = $e_flock;
        $e_tag          = Input::get('e_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $colour_of_tag  = Input::get('colour_of_tag');
        $move_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $l              = DB::table('sheep')->where('user_id',$user_id)->max('local_id');
        $sheep_exists   = Sheep::check($e_flock_number,$e_tag,$user_id);
        if (NULL === $sheep_exists) {
            $l++;
            $ewe = new Sheep();
            $ewe->user_id = $user_id;
            $ewe->local_id = $l;
            $ewe->e_flock = $e_flock_number;
            $ewe->original_e_flock = $e_flock_number;
            $ewe->colour_flock = $e_flock_number;
            $ewe->e_tag = $e_tag;
            $ewe->original_e_tag = $e_tag;
            $ewe->colour_tag = $e_tag;
            $ewe->move_on = $move_on;
            $ewe->colour_of_tag = $colour_of_tag;
            $ewe->sex = Input::get('sex');
            $ewe->save();

            $tag = new Homebred();
            $tag->e_flock       = $e_flock_number;
            $tag->date_applied  = $move_on;
            $tag->user_id       = $user_id;
            $tag->count         = 1;
            $tag->save();
        }

        return Redirect::back()->withInput([Input::except('e_tag')]);/*[
            'e_flock'   =>$e_flock,
            'sex'       =>$ewe->sex,
            'day'       =>$d,
            'month'     =>$m,
            'year'      =>$y,
            'colour_of_tag'=>$colour_of_tag

        ]);*/
    }


    public function getDeath()
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $this->user(),
            'sex'       =>'female',
            'e_flock'   => NULL,
            'e_tag'     => NULL
        ]);
    }
    public function postDeath()
    {
        $rules = Sheep::$rules['dates_and_tags'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $e_flock        = Input::get('e_flock');
        $e_flock_number = $e_flock;
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
            'e_flock'   => NULL,
            'e_tag'     => NULL,
            'sex'       => $ewe->sex

        ]);
    }
    public function getEnterdeath($id,$e_flock,$e_tag,$sex)
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $id,
            'e_flock'   => $e_flock,
            'e_tag'     => $e_tag,
            'sex'       => $sex
        ]);
    }
    public function getSearch()
    {
         return View::make('search')->with([
             'title'=>'Search for a Tag'
         ]);
    }
    public function getReplaced()
    {
        $ewes = Sheep::replaced('ewes');

        return View::make('replacement_tags')->with([
            'ewes'=>$ewes[0],
            'count'=>$ewes[1],
            'title'=>'Tag Replacements List'
        ]);
    }
    public function postSearch()
    {
        $tag = Input::get('tag');

        $ewes = Sheep::searchByTag($tag)->paginate(20);
        return View::make('searchresult')->with([
            'ewes'=>$ewes,
            'title'=>'Search Results for Tag '.$tag
        ]);
    }
}
