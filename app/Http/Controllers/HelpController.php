<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Help,View;
use App\Domain\DomainException;
use Exception;


class HelpController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($page)
	{
        try{
            $help = Help::$help_text[$page];
        }
        catch (Exception $e){
            //throw new DomainException('help page not published yet');
            return View::make('help')->with([
                'return'=> 'sheep/'.$page,
                'title' => 'Error',
                'text'=> 'help page not published yet'
            ]);
        }
        return View::make('help')->with([
            'return'=>$help[0],
            'title'=> $help[1],
            'text' => $help[2]
        ]);
	}


	public static $help_text= [
	    'addewe'=>['Help for Add a ewe page','This is the help text <br>
                    this is after the line break']
    ];


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
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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

}
