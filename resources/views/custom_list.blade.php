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
    Records {!! $list->firstItem() !!} to {!! $list->lastItem() !!} of {!! $list->total() !!}
    &nbsp;&nbsp;&nbsp;&nbsp;<span class="no-print"> {!! $list->render() !!}</span>

    <table class="table table-striped table-bordered table-sm table-condensed print" >
        <thead>
        <tr>
            <th class="no-print">Ref</th>
            <th>Tag Number</th>
            <th>Original Tags</th>
            <th>Move on</th>
            <th>Source</th>
            <th>Move Off</th>
            <th>How moved off</th>
            <th>Sex</th>
            <th>Date of last changes</th>
        <tr>
        </thead>
        <tbody>
        
        @foreach ($list as $ewe)
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
                    {{$ewe->date_on}}
                </td>
                <td>
                    {{$ewe->source}}
                </td>
    
                <td class="move-off">
                    {{$ewe->date_off}}
                </td>
                <td>
                    {{$ewe->destination}}
                </td>
                <td>
                    {{$ewe->sex}}
                </td>
                <td>
                    {{$ewe->updated}}
                </td>

            </tr>

        @endforeach
        </tbody>
    </table>
    @if(!Auth::guest())
        @if(Auth::user()->superuser)
            <a href="{!! Request::getRequestUri().'&make_group=true' !!}"
               class="btn btn-default btn-xs no-print"
               style="margin-bottom:-1px;"
               title="Edit this Sheep">Convert list to Group
            </a>
        @endif
    @endif
</div>
@stop