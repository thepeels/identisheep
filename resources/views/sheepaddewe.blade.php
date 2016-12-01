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
        {!!Form::open(array('url' => '/sheep/addewe','class'=>'form-inline'))!!}
        {!!Form::label('text','Entry Date') !!}
        {!!Form::input('int','day',NULL,['class'=>'newclass input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',NULL,['class'=>'newclass input-xs','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',NULL,['class'=>'newclass input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::input('hidden','id',$id)!!}
        {!!Form::input('hidden','sex',$sex)!!}
        {!!Form::label('text','UK 0')!!}
        {!!Form::input('text','e_flock',NULL,['class'=>'newclass input-xs','placeholder'=>' New Flock Number'])!!}
        {!!Form::input('text','e_tag',NULL,['class'=>'newclass input-xs','placeholder'=>' New Number'])!!}<br>
        {!!Form::label('text','Tag Colour')!!}
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}<br>
        {!!Form::input('text','colour_of_tag',NULL,['class'=>'newclass input-xs','placeholder'=>' Colour'])!!}

        {!!Form::submit($title,['class'=>'btn btn-info btn-xs'])!!}

        {!!Form::close()!!}<br>
    </div>

@stop