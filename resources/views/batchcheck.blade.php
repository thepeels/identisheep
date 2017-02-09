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
        <h5>{{$heading}}</h5>
        <table class="table table-striped table-bordered table-sm table-condensed print narrow" >
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tag Number</th>
                </tr>
            </thead>
            <tbody>
            @foreach($tag_list as $tag)
                <tr>
                    <td>{{$tag[0]}}</td>
                    <td>{{$tag[1]}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        Total {{count($tag_list)}} tags<br> <br>
        
        If these appear correct, use your browser "Back <- " button, and select the load option on the previous page.
        
        
    </div>
<script>
    function goBack() {
            window.history.back()
    }
</script>
@stop
