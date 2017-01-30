<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 28/01/2017
 * Time: 12:53
 */

namespace App\Domain\Sheep;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * @var email
    */
    public $email;

    /**
     * EmailService constructor.
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function owner()
    {
        return Auth::user();
    }

    /**
     *
     */
    public function sendWelcome()
    {
        $title = 'Welcome to IdentiSheep';

        Mail::send(['html' => 'emails.welcome_email','text' => 'emails.welcome_email_text'], ['title' => $title], function ($message) {
            $message->to($this->email);
            //$message->from('john@jjc.me'); //must match data in app/config/mail or be blank line
            $message->subject('Identisheep Welcome');
        });

    }

    public function sendInvoiceByEmail()
    {
        $owner = $this->owner();
        $invoices = $owner->invoicesIncludingPending();
        $invoiceId = $invoices[0]->id;
        dd($invoiceId);
        $title = 'IdentiSheep Invoice';
        Mail::send(['html' => 'emails.invoice_email','text' => 'emails.invoice_email_text'], ['title' => $title], function ($message) {
            $message->to($this->email);
            //$message->from('john@jjc.me'); //must match data in app/config/mail or be blank line
            $message->subject('Identisheep Welcome');
            $message->attach($invoiceId);
        });
    }
}