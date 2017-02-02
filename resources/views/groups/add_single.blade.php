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
        {!! Form::open(array('url' => '/group/single-to-group','class'=>'form-inline', 'files' => true)) !!}
        {!! Form::label('text','Select a group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::select('group',$group_names,['class'=>'new_class input-xs','size' => '20']) !!}<br><br>
        {!! Form::label('text','Flock and Individual Number')!!}<br>
        {!! Form::label('text','UK 0')!!}
        {!! Form::input('text','e_flock',old('e_flock'),['class'=>'new_class input-xs','placeholder'=>'Flock Number']) !!}
        {!! Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Tag Number']) !!}<br>
        {!! $errors->first('e_flock','<small style="color:#f00">:message</small>') !!}{!! $errors->first('e_tag','<small style="color:#f00">:message</small>') !!}<br>
        {!! Form::input('text','info',NULL,['class'=>'new_class input-xs','placeholder'=>'info','size' => '20']) !!}<br><br>
        {!! Form::submit($title,['class'=>'btn btn-info btn-xs']) !!}
    </div>
@stop