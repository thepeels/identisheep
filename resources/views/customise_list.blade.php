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
        {!! Form::open(array('url' => '/list/customised','method' =>'get','class'=>'form-inline'))!!}
        {!! Form::label('text','Enter a Date Range&nbsp;  ->  &nbsp;Start Date') !!}
        {!! Form::input('int','day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!! Form::input('int','month',date('m'),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!! Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}
        {!! Form::label('text','End Date') !!}
        {!! Form::input('int','end_day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!! Form::input('int','end_month',date('m'),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!! Form::input('int','end_year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br><br>
        {!! Form::label('text','Use this date for all custom lists until changed?') !!}&nbsp;&nbsp;
        {!! Form::checkbox('keep_date',TRUE,FALSE) !!}&nbsp;&nbsp;&nbsp;(click)<br><br>
        Which Sheep to List?'<br>
        <div style="margin-left: 40px;">
            {!! Form::radio('sex','female',TRUE,array('id'=>'sex-0')) !!}
            {!! Form::label('sex-0','Female&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('sex','male',FALSE,array('id'=>'sex-1')) !!}
            {!! Form::label('sex-1','Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('sex','both',FALSE,array('id'=>'sex-2')) !!}
            {!! Form::label('sex-2','Both&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
        </div>
        <br> Movement On or Off?
        <div style="margin-left: 40px;">
            {!! Form::radio('move','on',FALSE,array('id'=>'move-0')) !!}
            {!! Form::label('move-0','ON&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('move','off',TRUE,array('id'=>'move-1')) !!}
            {!! Form::label('move-1','OFF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
        </div><br>
        {!!Form::submit('Generate List',['class'=>'btn btn-info btn-xs'])!!}

        {!!Form::close()!!}<br>
    </div>
@stop