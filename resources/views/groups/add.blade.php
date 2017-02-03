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
        {!! Form::open(array('url' => '/group/add','class'=>'form-inline', 'files' => true)) !!}
        {!! Form::label('text','Select a group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::select('group',$group_names,['class'=>'new_class input-xs','size' => '20']) !!}<br>
        {!! Form::label('text','Select a .csv File',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::file('csv_file') !!}<br>
        {!!Form::submit($title,['class'=>'btn btn-info btn-xs'])!!}
    </div>
@stop