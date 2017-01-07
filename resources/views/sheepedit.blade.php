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
    <title>{!! $title !!}'Here'</title>
@stop
@section('content')
<div style="width:60%;margin-left:20%;">
    <h4>{!! $title.' for sheep number '. $e_flock . ' ' . sprintf('%05d',$e_tag) !!}</h4>
    {!!Form::open(array('url' => '/sheep/changetags','class'=>'form-inline'))!!}

    {!!Form::input('hidden','id',$id)!!}
    {!!Form::label('text','UK 0 '.$e_flock.' '.sprintf('%05d',$e_tag),['class' =>'bolder'])!!}
    {!!Form::input('hidden','old_e_flock',$e_flock,['class'=>'new_class input-xs','placeholder'=>' Old Flock Number'])!!}
    {!!Form::input('hidden','old_e_tag',sprintf('%05d',$e_tag),['class'=>'new_class input-xs','placeholder'=>' Old Number'])!!}
    <br><br>
    {!! Form::label('text','New Numbers') !!}<br>
    {!!Form::label('text','UK 0')!!}
    {!!Form::input('text','e_flock',old('e_flock'),['class'=>'new_class input-xs','placeholder'=>' New Flock Number','autofocus'=>"autofocus"])!!}
    {!!Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>' New Number'])!!}<br>
    {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}
    {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}
    <small style="color:#f00">{!!$errors->first('e_tag')!!}</small>

    <br>
    {!!Form::submit('Add/Change',['class'=>'btn btn-info btn-xs'])!!}

    {!!Form::close()!!}<br>
</div>

@stop