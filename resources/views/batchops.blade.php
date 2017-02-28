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
        {!! Form::open(array('url' => "batch/csvload",'class' => '', 'files' => true)) !!}
        <div class="form-group">
            {!! Form::label('text','Movement Date') !!}
            {!! Form::input('int','day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
            {!! Form::input('int','month',date('m'),['class'=>'new_class input-xs','placeholder'=>'MM','size' => '1']) !!}
            {!! Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
            {!! $errors->first('day','<small style="color:#f00">:message</small>') !!}
            {!! $errors->first('month','<small style="color:#f00">:message</small>') !!}
            {!! $errors->first('year','<small style="color:#f00">:message</small>') !!}<br>

            {!! Form::label('text','Destination Holding') !!}
            {!! Form::input('text','destination',old('destination'),['class'=>'new_class','placeholder'=>'Number or Name']) !!}<br>
            {!! $errors->first('destination','<small style="color:#f00">:message</small>') !!}<br>
            
            {!! Form::label('name','Select .csv file') !!}
            {!! Form::file('file_raw',old('file_raw')) !!}
            {!! $errors->first('file_raw','<small style="color:#f00">:message</small>') !!}<br>
        </div>
        <div class="form-group">
            {!! Form::submit('Check',['name'=>'check','class' => 'btn btn-primary']) !!}
            {!! Form::submit('Load',['name'=>'load','class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
        </fieldset>
    </div>
@stop