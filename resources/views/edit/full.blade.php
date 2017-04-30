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
        <h4>{!! $title !!}</h4>
        {!!Form::open(array('url' => '/edit/select','class'=>'form-inline'))!!}
        
        {!!Form::label('text','UK 0')!!}
        {!!Form::input('text','e_flock',old('e_flock'),['class'=>'new_class input-xs','placeholder'=>' Flock Number','tabindex'=>'1'])!!}
        {!!Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>' Serial Number','autofocus'=>'autofocus','tabindex'=>'2'])!!}<br>
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}
        
        <br>
        {!!Form::submit(' Select ',['class'=>'btn btn-info btn-xs'])!!}
        
        {!!Form::close()!!}<br>
    </div>
@stop