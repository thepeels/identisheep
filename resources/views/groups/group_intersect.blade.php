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
        {!! Form::open(array('url' => '/group/intersect','class'=>'form-inline', 'files' => true)) !!}
        {!! Form::label('text','Select a group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::select('group1',$group_names,['class'=>'new_class input-xs','size' => '20']) !!}<br>
        {!! Form::label('text','Select a second group',['style'=>'margin-top:15px;']) !!}<br>
        {!! Form::select('group2',$group_names,['class'=>'new_class input-xs','size' => '20']) !!}<br><br>
        {!!Form::submit('Show Sheep',['class'=>'btn btn-info btn-xs'])!!}
    </div>
@stop