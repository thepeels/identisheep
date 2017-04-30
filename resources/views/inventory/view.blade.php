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
        <table class="table table-striped table-bordered table-sm table-condensed print narrow" >
            <tbody>
            <tr>
                <td>total females</td>
                <td> {{ $females }}</td>
            </tr>
            <tr>
                <td>total males</td>
                <td> {{ $males }}</td>
            </tr>
            <tr>
                <td></td>
                <td> {{ $males + $females }}</td>
            </tr>
           </tbody>
        </table>
        
        
    </div>
@stop