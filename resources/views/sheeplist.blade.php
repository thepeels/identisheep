<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 24/11/2016
 * Time: 12:10
 */ ?>
@extends('app')
@section('title')
    <title>{{$title}}</title>
@stop
@section('content')
    <div style="width:60%;margin-left:20%;">
    <h4>{{$title}}</h4>
    <table class="table table-striped table-bordered" >
        <thead>
        <tr>
            <th>ID</th>
            <th>Owner</th>
            <th>Tag Number</th>
            <th>Original Tags</th>
            <th>Old Tags</th>
            <th>Older Tags</th>
            <th>Move on</th>
            <th>How moved off</th>
        </tr>
        </thead>

        @foreach ($sheep as $ewe)

            <tr>
                <td>
                    {{$ewe->id}}
                </td>
                <td>
                    {{$ewe->user_id}}
                </td>
                <td>
                    {{$ewe->e_flock}} {{sprintf('%05d',$ewe->e_tag)}}
                </td>
                <td>
                    {{$ewe->original_e_flock}} {{sprintf('%05d',$ewe->original_e_tag)}}
                </td>
                <td>
                    {{sprintf('%05d',$ewe->e_tag_1)}}
                </td>
                <td>
                    {{sprintf('%05d',$ewe->e_tag_2)}}
                </td>
                <td>
                {{date('Y-m-d',strtotime($ewe->move_on))}}
                </td>
                <td>
                    {{$ewe->off_how}}
                </td>
                <td>
                    <a href="sheep/edit/{{$ewe->id}}"
                       class="btn btn-default btn-xs"
                       style="margin-bottom:-1px;"
                       title="Edit this Sheep">Edit Sheep
                    </a>
                </td>

            </tr>

        @endforeach

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
