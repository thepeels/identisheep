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
        <?$flock = isset($e_flock)?$e_flock:NULL ;?>
        <?$old_flock = isset($old_e_flock)?$old_e_flock:NULL ;?>
        {!! Form::open(array('url' => '/sheep/replace-a-tag','class'=>'form-inline')) !!}
    <br>
        {!! Form::label('text', 'Date of Replacement') !!}
        {!! Form::input('int','day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!! Form::input('int','month',date('m'),['class'=>'new_class input-xs','placeholder'=>'MM','size' => '1']) !!}
        {!! Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!! $errors->first('day','<small style="color:#f00">:message</small>') !!}
        {!! $errors->first('month','<small style="color:#f00">:message</small>') !!}
        {!! $errors->first('year','<small style="color:#f00">:message</small>') !!}<br>
        
        {!! Form::label('text','Numbers on Replacement Tags') !!}<br>
        {!! Form::label('text','UK 0') !!}
        {!! Form::input('text','e_flock',$flock,['class'=>'new_class input-xs','placeholder'=>' Flock Number']) !!}
        {!! Form::input('int','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>' Tag Number']) !!}<br>
        {!! $errors->first('e_flock','<small style="color:#f00">:message</small>') !!}
        {!! $errors->first('e_tag','<small style="color:#f00">:message</small>') !!}<br>
    <h4>Optional </h4>
        {!! Form::label('text','Original Numbers') !!}<br>
        {!! Form::label('text','UK 0') !!}
        {!! Form::input('text','original_flock',NULL,['class'=>'new_class input-xs','placeholder'=>' Flock Number']) !!}
        {!! Form::input('int','original_tag',NULL,['class'=>'new_class input-xs','placeholder'=>' Tag Number']) !!}<br>
        {!! $errors->first('original_flock','<small style="color:#f00">:message</small>') !!}
        {!! $errors->first('original_tag','<small style="color:#f00">:message</small>') !!}<br>
    
    <?if (null !=(Session::get('find_error'))){
            echo (Session::pull('find_error'));
        }?><br>
        {!! Form::submit('Enter Tag Details',['class'=>'btn btn-info btn-xs']) !!}<br><br>
        {!! Form::submit('Find Sheep to Edit tags',['name'=>'find','class'=>'btn btn-info btn-xs']) !!}<br><br>
        {!! Form::submit('View all details and/or Record Death',['name'=>'view','class'=>'btn btn-danger btn-xs']) !!}<br>
        
        {!! Form::close()!!}<br>
</div>
@stop