<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    public function getDetails()
    {
        $email = Auth::user()->getEmail();
    $now =Carbon::now()->addMinutes(5);
        return View::make('subs/card_details')->with([
            'title' => 'Card Details',
            'receipt_email'  => $email,
            'now' =>$now
        ]);
    }

    public function postStore(Request $request)
    {
        $user = Auth::user();
        $token = $request->stripeToken;
        $user->newSubscription('Annual','Annual')->create($token);
        $invoices = ($user->invoices());
        foreach ($invoices as $invoice)
        {
            dd('amount: £'.$invoice->subtotal.' tax: £'.$invoice->tax.' total: £'.$invoice->total);
        }


    }

    private function expiryYears()
    {
        $now = Carbon::now();
        $start  = $now->year;
        $end    = $now->addYears(10)->year;
        return [$start,$end];
    }

}
