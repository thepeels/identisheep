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
        {!! Form::open(array('url' => '/sheep/delete','class'=>'form-inline','onsubmit' => 'return ConfirmDelete()')) !!}
        {!! Form::label('text','Delete Records of sheep entered up to 1st of December ...') !!}
        {!! Form::input('int','year',$year,['class'=>'new_class','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!! Form::label('text','Change this year to suit your needs') !!}<br><br>
        {!! Form::submit($title,['class'=>'btn btn-danger btn-xs']) !!}<br>
        {!! $errors->first('year','<small style="color:#f00">:message</small>') !!}
        {!! Form::close() !!}
    </div>
    <script>
        function ConfirmDelete()
        {
            var x = confirm("Are you sure you want to delete all records up to the end of the year?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@stop