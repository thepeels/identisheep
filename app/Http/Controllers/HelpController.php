<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Help,View;
use App\Domain\DomainException;
use Exception;


class HelpController extends Controller {

    /**
     * @param $page
     * @return mixed
     * @throws DomainException
     */
	public function index($page)
	{
        try{
            $help = Help::$help_text[$page];
        }
        catch (Exception $e){

            //throw new DomainException('This help page not published yet');
            return View::make('help')->with([
                'title' => 'Please be patient...',
                'text'=> 'This help page not published yet<br><br>'
            ]);
        }
        return View::make('help')->with([
            'title'=> $help[0],
            'text' => $help[1]
        ]);
	}

    /**
     * @var array
     */
	public static $help_text= [
	    'addewe'=>["Help for Add a ewe page","This is the help text <br>this is after the line break"]
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
