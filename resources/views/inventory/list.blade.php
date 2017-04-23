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
    <div style="width:60%;margin-left:20%;" class="no-print">
        <h4>{{$title}}</h4>
        <fieldset>
            {!! Form::open(array('url' => "inventory/add-list",'class' => '', 'files' => true)) !!}
            <div class="form-group">
    
                {!! Form::label('name','Select .csv file') !!}
                {!! Form::file('file_raw',old('file_raw')) !!}
                {!! $errors->first('file_raw','<small style="color:#f00">:message</small>') !!}<br>
            </div>
            <div class="form-group">
                {!! Form::submit('Load',['name'=>'load','class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </fieldset>
    </div>
    @if(count($not_added)>=1)
        <div style="width:60%;margin-left:20%;">
            <h5>Sheep not added as not in database yet:</h5>
        @foreach($not_added as $sheep)
                {{ $sheep }} <br>
        @endforeach
        </div>
    @endif
    
    
    
@stop