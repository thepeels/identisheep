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
        {!! Form::open(array('url' => 'list/re-name','class'=>'form-inline no-print', 'files' => true)) !!}
        {!! Form::label('text','New Group Name')!!}<br>
        {!! Form::input('text','group_name',old('group_name'),['class'=>'new_class input-xs','placeholder'=>'Group Name?']) !!}<br><br>
        {!! Form::label('text','Group Description - optional')!!}<br>
        {!! Form::input('text','description',old('description'),['class'=>'new_class input-xs','placeholder'=>'Description?']) !!}<br><br>
        {!! Form::label('text','Additional Information - optional')!!}<br>
        {!! Form::input('text','info',old('info'),['class'=>'new_class input-xs','placeholder'=>'Info?']) !!}<br><br>
        {!! Form::input('hidden','set_group',TRUE) !!}
        {!! $errors->first('e_flock','<small style="color:#f00">:message</small>') !!}{!! $errors->first('e_tag','<small style="color:#f00">:message</small>') !!}<br>
        {!!Form::submit('Submit',['class'=>'btn btn-default btn-xs no-print'])!!}
    </div>
@stop