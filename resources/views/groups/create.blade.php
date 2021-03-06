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
        {!! Form::open(array('url' => '/group/create','class'=>'form-inline')) !!}
        {!! Form::label('text','Name of Group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::input('text','name',NULL,['class'=>'new_class input-xs','placeholder'=>'name','size' => '20']) !!}<br>
        {!! Form::label('text','Optional description',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::input('text','description',NULL,['class'=>'new_class input-xs','placeholder'=>'description','size' => '20']) !!}<br>
        {!! Form::label('text','Optional additional info',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::input('text','info',NULL,['class'=>'new_class input-xs','placeholder'=>'info','size' => '20']) !!}<br>
        {!!Form::submit($title,['class'=>'btn btn-info btn-xs','style'=>'margin-top:15px;'])!!}
    </div>
@stop