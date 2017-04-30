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
        <h5>Total {!! $females->count() + $males->count() !!}</h5>
            @foreach($females as $sheep)
                {{ $sheep->flock_number }} - {{ $sheep->serial_number }} <br>
            @endforeach
            @foreach($males as $sheep)
                {{ $sheep->flock_number }} - {{ $sheep->serial_number }} <br>
            @endforeach
    </div>
    



@stop