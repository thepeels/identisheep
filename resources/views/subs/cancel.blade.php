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
@section('content')
    <div style="width:60%;margin-left:20%;">
        <h4>{{$title}}</h4>
        Cancel My {!! $subscribed_to !!}<br>
        I understand that I will remain a member, and have use of the service,<br>
        until the end of the current Billing period - {!! $until !!}.<br><br>
    
        <a href="cancelhere"
           class="btn btn-default btn-sm btn-inverse"
           style="margin-bottom:-1px;"
           title="cancel this subscription">Un-subscribe
        </a>
    </div>
@stop