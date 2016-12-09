<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 24/11/2016
 * Time: 12:10
 */ ?>
@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')
    <div style="width:75%;margin-left:12.5%;">
    <h4 class="print">
        {{$title}}
    </h4>
        <div class="no-print">
            {!! $batches->render() !!}
        </div>
        <table class="table table-striped table-bordered table-sm table-condensed print medium-width" >
        <thead>
        <tr>
            <th>Date</th>
            <th>Number Used</th>
            <th>Flock Number</th>
            <th>Destination</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($batches as $batch)
            <?$date = (date('Y',strtotime($batch->date_applied))=="1970"?"":date('d - m - Y',strtotime($batch->date_applied)))?>
            <tr>
                <td>
                    {{$date}}
                </td>
                <td>
                    {{$batch->count}}
                </td>
                <td>
                    UK0 {{$batch->flock_number}}
                </td>
                <td>
                    {{$batch->destination}}
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>

    </div>
@stop

{{--
@section('footer')
<h3>@include('includes.footer')</h3>
    <p><a href="user/addusers">Add a User</a></p>
    <p><a href="/icon/icons">Go to Icons</a></p>
@stop
    --}}
