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
        </h4>
        @if(Request::path() !== ('sheep'))

        @endif
        <table class="table table-striped table-bordered table-sm table-condensed print" >
            <thead>
            <tr>
                <th class="no-print">local</th>
                <th>Tag Number</th>
                <th>Original Tags</th>
                <th>Old Tags</th>
                <th>Older Tags</th>
                <th>Secondary Tag</th>
                <th>Move on</th>
                <th>Move Off</th>
                <th>Sex</th>
                <th>Date of last changes</th>
            </tr>
            </thead>
            <tbody>


                <?$date_on = (date('Y',strtotime($ewe->move_on))==config('app.base_date')?"":date('Y-m',strtotime($ewe->move_on)));
                $date_off = (date('Y',strtotime($ewe->move_off))==config('app.base_date')?"":date('Y-m-d',strtotime($ewe->move_off)));?>
                <tr>
                    <td class="no-print">
                        {{$ewe->local_id}}
                    </td>
                    <td>
                        UK0 {{$ewe->flock_number . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->serial_number)}}
                    </td>
                    <td>
                        {{$ewe->original_flock_number . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->original_serial_number) .' - - ' . $ewe->tag_colour}}
                    </td>
                    <td>
                        {{sprintf('%05d',$ewe->old_serial_number)}}
                    </td>
                    <td>
                        {{sprintf('%05d',$ewe->older_serial_number)}}
                    </td>
                    <td>
                        {{$ewe->supplementary_tag_flock_number.' '.sprintf('%05d',$ewe->supplementary_serial_number)}}
                    </td>
                    <td>
                        {{$date_on}}
                    </td>
                    <td class="move-off">
                        Not Yet Moved
                    </td>
                    <td>
                        {{$ewe->sex}}
                    </td>
                    <td>
                        {{date('Y-m-d',strtotime($ewe->updated_at))}}
                    </td>
                </tr>

            </tbody>
        </table>
        <a href="search"
           class="btn btn-info btn-xs"
           style="margin-bottom:-1px;"
           title="Search fo another Sheep">Search Again
        </a>
        @if($date_off = (date('Y',strtotime($ewe->move_off))==config('app.base_date')))
            <a href="../edit/{{$ewe->id}}"
               class="btn btn-info btn-xs"
               style="margin-bottom:-1px;"
               title="Edit this Sheep">Edit Sheep
            </a>
            <a href="../deathsearch/{{$ewe->flock_number}}/{{$ewe->serial_number}}/{{$ewe->sex}}"
               class="btn btn-default btn-xs btn-inverse"
               style="margin-bottom:-1px;"
               title="Edit this Sheep">Enter Death of this Sheep
            </a>
        @endif
    </div>
@stop

{{--
@section('footer')
<h3>@include('includes.footer')</h3>
    <p><a href="user/addusers">Add a User</a></p>
    <p><a href="/icon/icons">Go to Icons</a></p>
@stop
    --}}
