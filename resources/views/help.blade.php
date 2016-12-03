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
        {!! $text !!}
        <a href="../{{$return}}"
           class="btn btn-info "
           style="margin-top:50px;margin-left:50px;"
        >Close
        </a>
    </div>
@stop