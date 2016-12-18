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
        <h4>{{$title}}
            @if(Request::path() === 'sheep/replaced')
                - (number of records = {{$count}})
            @endif
        </h4>
        {!! $ewes->render() !!}
        <table class="table table-striped table-bordered table-sm table-condensed" >
            <thead>
            <tr>
                <th>local</th>
                <th>Tag Number</th>
                <th>Original Tags</th>
                <th>Date of Changes</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($ewes as $ewe)
                <?$date_on = (date('Y',strtotime($ewe->move_on))=="1970"?"":date('Y-m',strtotime($ewe->move_on)));
                $date_off = (date('Y',strtotime($ewe->move_off))=="1970"?"":date('Y-m-d',strtotime($ewe->move_off)));?>
                <tr>
                    <td>
                        {{$ewe->local_id}}
                    </td>
                    <td>
                        UK0 {{$ewe->flock_number . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->serial_number)}}
                    </td>
                    <td>
                        {{$ewe->original_flock_number . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->original_serial_number) .' - - ' . $ewe->tag_colour}}
                    </td>
                    <td>
                        {{date('Y-m-d',strtotime($ewe->updated_at))}}
                    </td>
                    <td>
                        <a href="edit/{{$ewe->id}}"
                           class="btn btn-default btn-xs"
                           style="margin-bottom:-1px;"
                           title="Edit this Sheep">Edit Sheep
                        </a>
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
