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
        <table class="table table-striped table-bordered table-sm table-condensed narrow">
            <thead>
                <th>Invoice date</th>
                <th>Amount</th>
            </thead>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                    <td>{{ $invoice->total() }}</td>
                    <td><a href="/subs/download-invoice/{{ $invoice->id }}" class="btn btn-info btn-inverse btn-xs">Download</a></td>
                </tr>
            @endforeach
        </table><br>
        <button onclick="goBack()" class="btn btn-info  btn-inverse col-sm-offset-2" >Finished</button>
    </div>
    <script>
        function goBack() {
            window.history.back()
        }
    </script>
@stop
