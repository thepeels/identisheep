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
        {!! Form::open(array('url' => '/inventory/clear-inventory','class'=>'form-inline','onsubmit' => 'return ConfirmDelete()')) !!}
        {!! Form::submit($title,['class'=>'btn btn-danger btn-xs']) !!}<br>
        {!! $errors->first('year','<small style="color:#f00">:message</small>') !!}
        {!! Form::close() !!}
    </div>
    <script>
        function ConfirmDelete()
        {
            var x = confirm("Are you sure you wish to clear all inventory records?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@stop