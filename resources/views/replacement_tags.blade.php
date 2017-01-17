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
                - (number of records = {{$ewes->total()}})
            @endif
        </h4>
        @if(Request::path() !== ('sheep'))
            <div class="no-print" style="margin:0 0 10px;">
                @if($elements[sizeof($elements)-1]!='print')
                    {!! $ewes->render() !!}<br>
                    <a href="../../{{$print}}"
                       class="btn btn-default btn-xs"
                       style="margin-bottom:-1px;"
                       title="printable version of whole list">Printable Version
                    </a>
                @endif
            </div>
        @endif
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
                        {{date('d-M-Y',strtotime($ewe->updated_at))}}
                    </td>
                    <td class="no-print">
                        <a href="../edit/{{$ewe->id}}"
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
