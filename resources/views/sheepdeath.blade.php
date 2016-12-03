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
        {!!Form::open(array('url' => '/sheep/death','class'=>'form-inline'))!!}
        {!!Form::label('text','Entry Date') !!}
        {!!Form::input('int','day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',date('m'),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::input('hidden','id',$id)!!}
        {!!Form::label('text','UK 0')!!}
        {!!Form::input('text','e_flock',NULL,['class'=>'new_class','placeholder'=>' Flock Number','size' => '12'])!!}
        {!!Form::input('text','e_tag',NULL,['class'=>'new_class','placeholder'=>'Tag Number','size' => '10'])!!}<br>
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}<br>
        {!!Form::label('Female') !!}
        {!!Form::radio('sex','Female','true')!!}<br>
        {!!Form::label('Male') !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        {!!Form::radio('sex','Male')!!}<br>
        {!!Form::label('text','How died?') !!}
        {!!Form::input('text','how_died',NULL,['class'=>'new_class input-xs','placeholder'=>'Optional','size' => '10'])!!}<br>
        <br>
        {!!Form::submit($title,['class'=>'btn btn-info btn-xs'])!!}
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::close()!!}<br>
    </div>
@stop