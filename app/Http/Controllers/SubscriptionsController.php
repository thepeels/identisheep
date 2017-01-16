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

class SubscriptionsController extends Controller
{
    public function getDetails()
    {
        $email = $this->owner()->getEmail();

        return View::make('subs/card_details')->with([
            'title'         => 'Subscribe',
            'receipt_email' => $email
        ]);
    }

    public function postStore(Request $request)
    {
        $owner = $this->owner();
        $token = $request->stripeToken;
        $owner->newSubscription('Annual','Annual')->create($token);
        $invoices = ($owner->invoices());
        foreach ($invoices as $invoice)
        {
            dd('amount: £'.$invoice->subtotal/100  .' tax: £'.$invoice->tax/100 .' total: £'.$invoice->total/100);
        }


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
