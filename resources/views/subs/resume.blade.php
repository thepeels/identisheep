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
        I wish to resume my {!! $subscribed_to !!}<br>
        I will remain a member until the end of the Billing period<br><br>
        
        <a href="resumehere"
           class="btn btn-info btn-sm "
           style="margin-bottom:-1px;"
           title="Re-activate cancelled subscription">Re-activate
        </a>
    </div>
@stop