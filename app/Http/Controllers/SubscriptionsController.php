<?php

namespace App\Http\Controllers;

use App\Domain\Sheep\EmailService;
use App\Models\Subscriptions;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\InvoiceItem;
use Stripe\Subscription as Sub;


class SubscriptionsController extends Controller
/**ToDo: make an un-subscribe method and also put it into menu probably under an account drop down */
{
    public function getDetails()
    {
        $email = $this->owner()->getEmail();

        return View::make('subs/card_details')->with([
            'title'         => 'Subscribe',
            'receipt_email' => $email,
            'price'         => config('app.price'),
            'vat_rate'      => config('app.vat_rate')
        ]);
    }

    public function getPremium()
    {
        //object(Stripe_Customer);
        $owner  = $this->owner();
        $email  = $this->owner()->getEmail();
        $now    = Carbon::now();


        switch (true){
            case ($owner->onGenericTrial()):

                return view('subs/card_details_premium')->with([
                    'title'         => 'Subscribe',
                    'receipt_email' => $email,
                    'price'         => config('app.price')*2,
                    'vat_rate'      => config('app.vat_rate')
                ]);

            case ($owner->subscribed('Annual','Annual')):
                $row = DB::table('subscriptions')->where('user_id',$owner->id)->get();
                foreach ($row as $subscription) {
                    $stripe_id = $subscription->stripe_id;
                }
                Stripe::setApiKey(config('services.stripe.secret'));
                $customer = Customer::retrieve($owner->stripe_id); // the stripe customer to create invoice below
                //dd($customer);
                $sub    = Sub::retrieve($stripe_id);//Sub ~ Stripe/Subscription
                $end    = (Carbon::createFromTimestamp($sub->current_period_end));
                $start  = (Carbon::createFromTimestamp($sub->current_period_start));
                $difference = $end->diff($now)->days;
                //dd($difference);
                $price = round((config('app.price')*$difference/364),2);
                //dd($price);
                InvoiceItem::create([ //because stripe does not apply vat to prorated subscription
                    'customer'      => $customer,
                    'amount'        => round($price*(config('app.vat_rate')-1)*100,0,PHP_ROUND_HALF_DOWN),
                    'currency'      => 'gbp',
                    'description'   => 'V.A.T. @ '. (config('app.vat_rate')-1)*100 .'%'
                ]);
                break;

            case ($owner->subscribed('Annual','Premium')):
                Session::flash('message','You are already subscribed to the Premium service');

                return view('home');
        }

        return View::make('subs/card_details_premium')->with([
            'title'         => 'Subscribe',
            'receipt_email' => $email,
            'price'         => $price,
            'vat_rate'      => config('app.vat_rate')
        ]);
    }

    public function postStore(Request $request)
    {
        $owner = $this->owner();
        $token = $request->stripeToken;
        if(!$owner->subscribed('Annual')) {
            $owner->newSubscription('Annual', 'Annual')->create($token);
        }
        Session::flash('message','You are now subscribed, you will be able to download '.PHP_EOL .
                        'your Invoice under your User Name -> Invoice History.');
        /**ToDo: make new email for with invoice download link (subs/single-invoice) */
        //$email = new EmailService($this->owner()->email);
        //$email->sendInvoiceByEmail();
        return view('home');
    }
    public function postStorePremium(Request $request)
    {
        $owner = $this->owner();
        $token = $request->stripeToken;
        if(!$owner->subscribed('Annual','Premium')) {
            if ($owner->subscribed('Annual')) {
                $owner->subscription('Annual')->swap('Premium','Premium');
            }
            if (!$owner->subscribed('Annual')) {
                $owner->newSubscription('Premium', 'Premium')->create($token);
            }
            $owner->setSuperuser(1);
            $owner->save();
            Session::flash('message', 'You are now subscribed to the premium service, you will be able to download ' . PHP_EOL .
                'your Invoice under your User Name -> Invoice History.');
            /**ToDo: make new email for with invoice download link (subs/single-invoice) */
            //$email = new EmailService($this->owner()->email);
            //$email->sendInvoiceByEmail();
            return view('home');
        }
        if($owner->subscribed('Premium')){
            Session::flash('message','You are already subscribed to the Premium service');
            return view('home');
        }
        Session::flash('message','Error, unable to subscribe you to the Premium service');
        return Redirect::back();
    }

    public function getCancel()
    {
        $owner = $this->owner();
        switch (true){
            case $owner->subscribed('Annual','Annual'):
                $until = Carbon::createFromFormat('Y-m-d H:i:s',$owner->subscription('Annual')->created_at)->addYears(1)->toFormattedDateString('d M Y');
                //dd(!$owner->subscribed('Annual'));
                return View::make('subs/cancel')->with([
                    'title'         => 'Cancel subscription',
                    'subscribed_to' => 'Annual Subscription',
                    'until'         => $until
                ]);
            case $owner->subscribed('Annual','Premium'):
                $until = Carbon::createFromFormat('Y-m-d H:i:s',$owner->subscription('Annual')->created_at)->addYears(1)->toFormattedDateString('d M Y');
                return View::make('subs/cancel')->with([
                    'title'         => 'Cancel subscription',
                    'subscribed_to' => 'Premium Subscription',
                    'until'         => $until
                ]);
            case !$owner->subscribed('Annual','Annual')&&!$owner->subscribed('Annual','Premium'):
            //if(!$subscribed){
            /**ToDo: flash a message no subscription and/or you have already cancelled. perhaps this route is not possible
            * unless browser open through expiry time
             */
            Session::flash('message','subscription in free trial period or else ended already');
                return Redirect::back();
        }

        }
    public function getCancelhere()
    {
        $owner = $this->owner();

        if($owner->subscribed('Annual','Annual')){
            $owner->subscription('Annual')->cancel();
            Session::flash('message','We have cancelled your Annual subscription,
                but you remain a member until '.date_format($owner->subscription('Annual')->ends_at,'d M Y'));
        }
        if($owner->subscribed('Annual','Premium')){
            $owner->subscription('Annual')->cancel();
            Session::flash('message','We have cancelled your Premium subscription,
                but you remain a Premium member until '.date_format($owner->subscription('Annual')->ends_at,'d M Y'));
        }

        return Redirect::to('home');
    }
    public function getResume()
    {
        $owner = $this->owner();
        $subscribed = $owner->subscribed('Annual');
        if(!$owner->subscription('Annual')->onGracePeriod()){
            Session::flash('alert-class','alert-danger');
            Session::flash('message','You are not currently un-subscribed, so no action was taken.');
            return Redirect::to('home');
            /**ToDo: flash a message you are subscribed. */
        }
        if($owner->subscription('Annual','Annual')->onGracePeriod())
        return View::make('subs/resume')->with([
            'title'     => 'Resume subscription',
            'subscribed_to'=> 'Annual Subscription',
            'subscribed_until'=> date_format($owner->subscription('Annual')->ends_at,'d M Y')
        ]);
        if($owner->subscription('Annual','Premium')->onGracePeriod())
        return View::make('subs/resume')->with([
            'title'     => 'Resume subscription',
            'subscribed_to'=> 'Premium Subscription',
            'subscribed_until'=> date_format($owner->subscription('Annual')->ends_at,'d M Y')
        ]);
    }
    public function getResumehere()
    {
        $owner = $this->owner();

        Session::flash('message','We are re-activating your subscription,
                and you will remain a member, with your next payment will be taken automatically on '.date_format($owner->subscription('Annual')->ends_at,'d M Y'));
        $owner->subscription('Annual')->resume();

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

    public function getInvoice(Request $request)
    {
        $owner = $this->owner();
        //dd($owner);
        //$test = new EmailService($owner->email); //don't know how to do this
        //$test->sendInvoiceByEmail();
        if(Carbon::now()<=$owner->getTrialEndsAt()){
            Session::flash('alert-class','alert-danger');
            Session::flash('message','You are currently on your free trial, so no invoices are available.');
            return Redirect::back();
        }
        $invoices = $owner->invoicesIncludingPending();
        //$invoiceId = $invoice[0]->id;
        //return $owner->downloadInvoice($invoiceId ,[
        return View::make('receipt_selector')->with([
            'title'     => 'Select Invoice from List',
            'vendor'    => 'IdentiSheep',
            'product'   => $owner->subscription('Annual')->id,
            'vat'       => 'Vat Number - UK 499 7886 39',
            'number'    => $owner->subscription('Annual')->id,
            'invoices'  => $invoices,
    ]);

    }
    public function getSingleInvoice(Request $request)
    {
        $owner = $this->owner();
        $invoice = $owner->invoicesIncludingPending();
        $invoiceId = $invoice[0]->id;
        return $owner->downloadInvoice($invoiceId ,[
        //return View::make('cashier/receipt')->with([
            'vendor'    => 'IdentiSheep',
            'product'   => 'Annual Membership',
            'vat'       => 'Vat Number - UK 499 7886 39',
            'number'    => $owner->subscription('Annual')->id
    ]);

    }

    public function getDownloadInvoice($invoiceId)
    {
        $owner = $this->owner();
        return $owner->downloadInvoice($invoiceId ,[
            //return View::make('cashier/receipt')->with([
            'vendor'    => 'IdentiSheep',
            'product'   => $owner->subscription('Annual')->stripe_plan . ' Membership',
            'vat'       => 'Vat Number - UK 499 7886 39',
            'number'    => $owner->subscription('Annual')->id
        ]);
    }
}
