<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class SubscriptionsController extends Controller
/**ToDo: make an un-subscribe method and also put it into menu probably under an account drop down */
{
    public function getDetails()
    {
        $email = $this->owner()->getEmail();

        return View::make('subs/card_details')->with([
            'title'         => 'Subscribe',
            'receipt_email' => $email,
            'price'         => 10,
            'vat_rate'      => 1.2
        ]);
    }

    public function postStore(Request $request)
    {
        $owner = $this->owner();
        $token = $request->stripeToken;
        if(!$owner->subscribed('Test')) {
            $owner->newSubscription('Test', 'Test')->create($token);
        }
        $invoices = ($owner->invoices());
        foreach ($invoices as $invoice)
        {
            //$this->sendInvoice($invoice);
            dd('amount: £'.$invoice->subtotal/100  .' tax: £'.$invoice->tax/100 .' total: £'.$invoice->total/100);
        }
        /**ToDo: Send Invoice and flash a message*/
        //$this->sendInvoice($owner->invoices());
    }

    public function getCancel()
    {
        $owner = $this->owner();
        $subscribed = $owner->subscribed('Test');
        $until = Carbon::createFromFormat('Y-m-d H:i:s',$owner->subscription('Test')->created_at)->addDays(1)->toFormattedDateString('d M Y');
        if(!$subscribed){
            /**ToDo: flash a message no subscription and/or you have already cancelled. perhaps this route is not possible
            * unless browser open through expiry time
             */
            Session::flash('message','subscription ended already');
        }

        return View::make('subs/cancel')->with([
            'title'         => 'Cancel subscription',
            'subscribed_to' => 'Annual Subscription',
            'until'         => $until
        ]);
    }
    public function getCancelhere()
    {
        $owner = $this->owner();
        $owner->subscription('Test')->cancel();

        Session::flash('message','We have cancelled your subscription,
                but you remain a member until '.date_format($owner->subscription('Test')->ends_at,'d M Y'));

        return Redirect::to('home');
    }
    public function getResume()
    {
        $owner = $this->owner();
        $subscribed = $owner->subscribed('Test');
        if(!$owner->subscription('Test')->onGracePeriod()){
            Session::flash('alert-class','alert-danger');
            Session::flash('message','You are not currently un-subscribed, so no action was taken.');
            return Redirect::to('home');
            /**ToDo: flash a message you are subscribed. */
        }
        if($owner->subscription('Test')->onGracePeriod())
        return View::make('subs/resume')->with([
            'title'     => 'Resume subscription',
            'subscribed_to'=> 'Annual Subscription',
            'subscribed_until'=> date_format($owner->subscription('Test')->ends_at,'d M Y')
        ]);
    }
    public function getResumehere()
    {
        $owner = $this->owner();

        Session::flash('message','We are re-activating your subscription,
                and you will remain a member, with your next payment will be taken automatically on '.date_format($owner->subscription('Test')->ends_at,'d M Y'));
        $owner->subscription('Test')->resume();

        return Redirect::to('home');
    }
    private function expiryYears()
    {
        $now = Carbon::now();
        $start  = $now->year;
        $end    = $now->addYears(10)->year;
        return [$start,$end];
    }


    private function owner()
    {
        return Auth::user();
    }
    
}
