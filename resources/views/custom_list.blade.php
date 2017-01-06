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
    <h4>{{$title}} between {{date_format($date_start,'d-M-Y')}} and {{date_format($date_end,'d-M-Y')}}</h4>
    {{ date('d M Y',strtotime('today'))}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Number of records = {!! $list->total() !!}&nbsp;&nbsp;&nbsp;&nbsp;{!! $list->render() !!}

    <table class="table table-striped table-bordered table-sm table-condensed print" >
        <thead>
        <tr>
            <th class="no-print">ref</th>
            <th>Tag Number</th>
            <th>Original Tags</th>
            <th>Move on</th>
            <th>Move Off</th>
            <th>How moved off</th>
            <th>Sex</th>
            <th>Date of last changes</th>
        <tr>
        </thead>
        <tbody>
        @foreach ($list as $ewe)
            <?$date_on = (date('Y',strtotime($ewe->move_on))=="1970"?"":date('d-M-Y',strtotime($ewe->move_on)));
            $date_off = (date('Y',strtotime($ewe->move_off))=="1970"?"":date('d-M-Y',strtotime($ewe->move_off)));?>
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
                    {{$date_on}}
                </td>
                    <td class="move-off">
                        {{$date_off}}
                    </td>
                    <td>
                        {{$ewe->destination}}
                    </td>
                    <td>
                        {{$ewe->sex}}
                    </td>
                    <td>
                        {{date('d-M-Y',strtotime($ewe->updated_at))}}
                    </td>

            </tr>

        @endforeach
        </tbody>
    </table>
</div>
@stop