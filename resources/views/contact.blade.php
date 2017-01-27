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
        <h5>Use this form to send us questions, comments, or observations about the website.</h5>
        
        {!! Form::open(['url' => '/contact/send-message','class'=>'form-inline']) !!}
        {!! Form::textarea('message',NULL,['placeholder'=>'Your message here','style'=>'padding:10px;','size'=>'67x7']) !!} <br><br>
        {!! Form::submit('Send Message',['class'=>'btn btn-info btn-xs btn-inverse']) !!}
    </div>
@stop