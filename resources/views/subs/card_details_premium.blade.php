<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 24/11/2016
 * Time: 19:53
 */

?>
@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop

@section("content")
    <div style ="width:60%;margin:50px 0 0 20%;">
        <h4>Subscribe to <span class="red">Identi</span><span class="blue">Sheep</span> Premium for £{!! $price !!} + VAT / year</h4><br> <br>
        
        {!! Form::open(array('url'=>'/subs/store-premium','style'=>'margin-left:15%;')) !!}
            <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-label="Pay £<?=number_format($price*config('app.vat_rate'),2)?> by Card"
                    data-key="<?=config('services.stripe.key')?>"
                    data-amount="<?=$price*config('app.vat_rate')*100?>"
                    data-name="IdentiSheep"
                    data-description="Premium"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-receipt_email="<?=$receipt_email;?>"
                    data-zip-code="false"
                    data-currency="gbp"
                    data-business-vat-id="UK499788639">
            
            </script>
        </form>
    </div>


@stop