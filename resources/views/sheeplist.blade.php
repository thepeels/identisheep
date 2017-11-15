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
        <h4><p class="no-screen"> {{Auth::user()->getBusiness()}}, {{Auth::user()->getAddress()}}</p><br>
            <p class="no-screen">Holding Number {{Auth::user()->getHolding()}}</p><br>
            {{$title}}
            @if(in_array($elements[sizeof($elements)-2],$filtered_pages))
                - <span class="pages">(number of records =
                    @if($elements[sizeof($elements)-1]=='print'){{$ewes->count()}})
                    @else{{$ewes->count()}} of {{$ewes->total()}})
                    @endif</span>
            @endif
            @if(in_array($elements[sizeof($elements)-2],$second_filter))
                <span class="date-range">dates between {{date('d M Y',strtotime(Session::get('date_from')?:config('dates.from')))}}
                    and {{date('d M Y',strtotime(Session::get('date_to')?:config('dates.to')))}}
                </span>
            @endif
        </h4>
        @if(Request::path() !== ('sheep'))
            <div class="no-print" style="margin:0 0 10px;">
                @if($elements[sizeof($elements)-1]!='print')
                    {!! $ewes->render() !!}<br>
                    <a href="../../{{$print}}"
                       class="btn btn-info btn-inverse btn-xs"
                       style="margin-bottom:-1px;"
                       title="printable version of whole list">Printable Version - All rows on one page
                    </a>
                @endif
            </div>
        @endif
        {{date('d M Y',strtotime('today'))}}
        <table class="table table-striped table-bordered table-sm table-condensed print" >
            <thead>
            <tr>
                <th class="no-print">Ref</th>
                <th>Tag Number</th>
                <th>Original Tags</th>
                <th class="no-print">Old Tags</th>
                <th class="no-print">Older Tags</th>
                <th>Move on</th>
                <th>Sourced from</th>
                @if(in_array($elements[sizeof($elements)-2],$second_filter))
                    <th>Move Off</th>
                    <th>How moved off</th>
                    <th>Disposal</th>
                    <th>Sex</th>
                @else
                    <th>Date of last changes</th>
                @endif
                {{--<th class="no-screen">Row No.</th>--}}
            </tr>
            </thead>
            <tbody>
            <?$row_no = 0;?>
            @foreach ($ewes as $ewe)
                <?$date_on = (date('Y',strtotime($ewe->move_on))==config('app.base_date')?"":date('d-M-Y',strtotime($ewe->move_on)));
                $date_off = (date('Y',strtotime($ewe->move_off))==config('app.base_date')?"":date('d-M-Y',strtotime($ewe->move_off)));
                $row_no++;?>
                <tr> {{-- style="color:{{($ewe->tag_colour=='White')?'black':$ewe->tag_colour}}" --}}
                    <td class="no-print">
                        {{$ewe->local_id}}
                    </td>
                    <td>
                        {{$ewe->country_code}} {{$ewe->flock_number . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->serial_number)}}
                    </td>
                    <td>
                        {{$ewe->original_flock_number . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->original_serial_number) .' - - ' . $ewe->tag_colour}}
                    </td>
                    <td class="no-print">
                        {{sprintf('%05d',$ewe->old_serial_number)}}
                    </td>
                    <td class="no-print">
                        {{sprintf('%05d',$ewe->older_serial_number)}}
                    </td>
                    <td>
                        {{$date_on}}
                    </td>
                    <td>
                        {{$ewe->source}}
                    </td>
                    @if(in_array($elements[sizeof($elements)-2],$second_filter))
                        <td class="move-off">
                            {{$date_off}}
                        </td>
                        <td>
                            {{$ewe->destination}}
                        </td>
                        <td>
                            {{$ewe->disposal}}
                        </td>
                        <td>
                            {{$ewe->sex}}
                        </td>
                    @else
                        <td>
                            {{date('d-M-Y',strtotime($ewe->updated_at))}}
                        </td>
                        <td class="no-print">
                            <a href="../edit/{{$ewe->id}}"
                               class="btn btn-default btn-xs"
                               style="margin-bottom:-1px;"
                               title="Edit this Sheep">Edit Sheep
                            </a>
                        </td>
                    
                    @endif
                    {{--<td class="no-screen">{{$row_no}}</td>--}}
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