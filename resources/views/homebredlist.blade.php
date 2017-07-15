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
    <div style="width:100%;margin-left:20%;">
        <h4><p class="no-screen"> {{Auth::user()->getBusiness()}}, {{Auth::user()->getAddress()}}</p><br>
            <p class="no-screen">Holding Number {{Auth::user()->getHolding()}}</p><br>
            {{$title}}
        </h4>
        <table class="table table-striped table-bordered table-sm table-condensed print narrow" >
            <thead>
            <tr>
                <th>Date of Application</th>
                <th>Number Deployed</th>
                <th>Start Number</th>
                <th>End Number</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td>{{date('d - m - Y',strtotime($tag->date_applied))}}</td>
                    <td>{{$tag->count}}</td>
                    <td>{{$tag->start}}</td>
                    <td>{{$tag->finish}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop