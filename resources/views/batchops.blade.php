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
        <h4>{{$title}} {{$subtitle}}</h4>
        <fieldset>
        {!!Form::open(array('url' => "batch/csvload",'class' => '', 'files' => true))!!}
        <div class="form-group">
            {!! Form::label('name','Select .csv file') !!}
            {!! Form::file('file_raw') !!}<br>
            {!! Form::label('text','Destination Holding') !!}
            {!! Form::input('text','destination',NULL,['class'=>'newclass input-xs','placeholder'=>' Number or Name']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('Check',['name'=>'check','class' => 'btn btn-primary']) !!}
            {!! Form::submit('Load',['name'=>'load','class' => 'btn btn-primary']) !!}
        </div>
        {!!Form::close()!!}
        </fieldset>
    </div>
@stop