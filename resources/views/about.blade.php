<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 24/11/2016
 * Time: 19:53
 */

?>
@extends('app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Information about <span class="red">Identi</span><span class="blue">Sheep.com</span></div>
                    
                    <div class="panel-body">
                        <p>You will be using the site to keep track of individual ear tags and so be able to produce tagging and EID evidence <br>
                        up to DEFRA Cross Compliance standards.</p>
                        <p>Name and Address details are only collected to use as headings on Printed Lists.</p>
                        <p>Use of the site is free for six months, after that you will be asked to enter credit or debit card details<br>
                        You will then be charged £10 plus V.A.T. annually until you cancel your subscription.</p>
                        <p>Card details are never handled by the site, all payments are securely handled by <a href="https://stripe.com/gb" target="_blank">Stripe.com</a></p>
                        <p>When you subscribe you will receive a VAT invoice by email.</p>
                        <p>IdentiSheep.com is created and owned by Messrs J & J Corley, The Peels, Morpeth, NE65 7DL U.K.</p>
                        <p>The business is VAT registered in the U.K. No 499-7886-39.</p>
                        <p>Contact details <a id="email"href="click:the.address.will.be.decrypted.by.javascript" title="click to Email"
                        onclick='openMailer(this);'><img src="/scripts/image.png" style="height:16px"/></a></p>
                        <p>This site uses cookies to present your data in a usable format.</p>
                        <p><span class="red">Identi</span><span class="blue">Sheep.com</span> © <?php echo(date('Y'));?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<?php
include_once('scripts/rot13_script.php');
?>