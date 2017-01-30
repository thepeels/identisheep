<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    public function getIndex()
    {
        return view('contact')->with([
            'title'     => 'Contact Us'
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postSendMessage(Request $request)
    {
        if (NULL == $request->message){
            Session::flash('message','You sent a blank message, did you mean to?');
            return Redirect::back();
        }

        $title = 'Message from IdentiSheep user no. '.Auth::user()->id;
        $content = $request->message;
        Mail::send(['text'=>'emails.contact'],['title'=>$title,'content'=>$content], function ($message)
    {
        $message->from(Auth::user()->email,'me');
        $message->to('john@jjc.me');
        $message->subject('Identisheep Message from Owner No.'. Auth::user()->id);
    });
        Session::flash('message','Thank you for your message.');
        return Redirect::back();
    }

    public function postSendWelcome()
    {
        if (Auth::check()) {
            $title = 'Welcome to IdentiSheep your User id is ' . Auth::user()->id;
            //$content = $request->message;
            Mail::send(['text' => 'emails.welcome_email'], ['title' => $title], function ($message) {
                $message->to(Auth::user()->email, 'me');
                $message->from('admin@identisheep.com');
                $message->subject('Identisheep Welcome');
            });

            //return Redirect::back();
        }
    }
}
