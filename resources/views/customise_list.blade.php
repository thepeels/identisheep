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
        {!! Form::input('int','day',date('d',strtotime($date_start)),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!! Form::input('int','month',date('m',strtotime($date_start)),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!! Form::input('int','year',date('Y',strtotime($date_start)),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}
        {!! Form::label('text','End Date') !!}
        {!! Form::input('int','end_day',date('d',strtotime($date_end)),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!! Form::input('int','end_month',date('m',strtotime($date_end)),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!! Form::input('int','end_year',date('Y',strtotime($date_end)),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}
        {!!$errors->first('end_day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('end_month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('end_year','<small style="color:#f00">:message</small>')!!}<br><br>
        {!! Form::label('text','Use these dates for all custom lists until I change them?') !!}&nbsp;&nbsp;
        {!! Form::checkbox('keep_date',old($keep_date),$keep_date) !!}&nbsp;&nbsp;&nbsp;(click)<br><br>
        Which Sheep to List?'<br>
        <div style="margin-left: 40px;">
            {!! Form::radio('sex','female',TRUE,array('id'=>'sex-0')) !!}
            {!! Form::label('sex-0','Female &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('sex','male',FALSE,array('id'=>'sex-1')) !!}
            {!! Form::label('sex-1','Male &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('sex','both',FALSE,array('id'=>'sex-2')) !!}
            {!! Form::label('sex-2','Both &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
        </div>
        <br> Movement On or Off?
        <div style="margin-left: 40px;">
            {!! Form::radio('move','on',FALSE,array('id'=>'move-0')) !!}
            {!! Form::label('move-0','ON &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('move','off',TRUE,array('id'=>'move-1')) !!}
            {!! Form::label('move-1','OFF &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
        </div>
        <br> Include moved Off and Dead Sheep?
        <div style="margin-left: 40px;">
            {!! Form::radio('include_dead',TRUE,TRUE,array('id'=>'include_dead-0')) !!}
            {!! Form::label('include_dead-0','Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
            {!! Form::radio('include_dead',FALSE,FALSE,array('id'=>'include_dead-1')) !!}
            {!! Form::label('include_dead-1','No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') !!}<br>
        </div><br>
        {!! Form::input('hidden','make_group',FALSE) !!}
        {!! Form::submit('Generate List',['class'=>'btn btn-info btn-xs']) !!}

        {!! Form::close() !!}<br>
    </div>
@stop