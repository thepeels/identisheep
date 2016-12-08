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
            <div class="no-print">
                {!! $ewes->render() !!}
            </div>
        @endif
        <table class="table table-striped table-bordered table-sm table-condensed print" >
        <thead>
        <tr>
            <th class="no-print">local</th>
            <th>Tag Number</th>
            <th>Original Tags</th>
            <th>Old Tags</th>
            <th>Older Tags</th>
            <th>Move on</th>
            <th>Move Off</th>
            <th>Sex</th>
                <th>Date of last changes</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($ewes as $ewe)
            <?$date_on = (date('Y',strtotime($ewe->move_on))=="1970"?"":date('Y-m',strtotime($ewe->move_on)));
              $date_off = (date('Y',strtotime($ewe->move_off))=="1970"?"":date('Y-m-d',strtotime($ewe->move_off)));?>
            <tr>
                <td class="no-print">
                    {{$ewe->local_id}}
                </td>
                <td>
                    UK0 {{$ewe->e_flock . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->e_tag)}}
                </td>
                <td>
                    {{$ewe->original_e_flock . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$ewe->original_e_tag) .' - - ' . $ewe->colour_of_tag}}
                </td>
                <td>
                    {{sprintf('%05d',$ewe->e_tag_1)}}
                </td>
                <td>
                    {{sprintf('%05d',$ewe->e_tag_2)}}
                </td>
                <td>
                    {{$date_on}}
                </td>
                    <td>
                        {{$date_off}}
                    </td>
                    <td>
                        {{$ewe->sex}}
                    </td>
                    <td>
                        {{date('Y-m-d',strtotime($ewe->updated_at))}}
                    </td>
                    <td class="no-print">
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
