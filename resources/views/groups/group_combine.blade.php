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
        {!! Form::open(array('url' => '/group/combine','class'=>'form-inline', 'files' => true)) !!}
        {!! Form::label('text','Select a group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::select('group1',$group_names,['class'=>'new_class input-xs','size' => '20']) !!}<br>
        {!! Form::label('text','Select a second group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::select('group2',$group_names,['class'=>'new_class input-xs','size' => '20']) !!}<br>
        {!! Form::label('text','Choose a New Group Name',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::input('text','name',old('name'),['class'=>'new_class input-xs','size' => '20','placeholder'=>'New Group Name']) !!}<br>
        {!! Form::label('text','Optional description',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::input('text','description',NULL,['class'=>'new_class input-xs','placeholder'=>'description','size' => '20']) !!}<br>
        {!! Form::label('text','Optional additional info',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::input('text','info',NULL,['class'=>'new_class input-xs','placeholder'=>'info','size' => '20']) !!}<br><br>
        {!! Form::submit('Make New Group',['class'=>'btn btn-info btn-xs'])!!}
    </div>
@stop