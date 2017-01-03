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
        <h4>{!! $title. $e_flock . '&nbsp;&nbsp;&nbsp;' . sprintf('%05d',$e_tag) !!}</h4>
        <table class="table table-striped table-bordered table-sm table-condensed" >
            <thead>
            <tr>
                <th>Tag Number</th>
                <th>Colour</th>
                <th>Original Tags</th>
                <th>Old Tags</th>
                <th>Older Tags</th>
                <th>Move on</th>
            </tr>
            </thead>
            <tbody>
                <?$date_on = (date('Y',strtotime($move_on))=="1970"?"":date('Y-m',strtotime($move_on)));?>
                <tr>
                    <td>
                        UK0 {{$e_flock}} {{sprintf('%05d',$e_tag)}}
                    </td>
                    <td>
                        {{$colour_of_tag}}
                    </td>
                    <td>
                        {{$original_e_flock}} {{sprintf('%05d',$original_e_tag)}}
                    </td>
                    <td>
                        {{sprintf('%05d',$e_tag_1)}}
                    </td>
                    <td>
                        {{sprintf('%05d',$e_tag_2)}}
                    </td>
                    <td>
                        {{$date_on}}
                    </td>
                    @if(Request::url() === 'http://flock/sheep/seek')
                        <td>
                            <a href="edit/{{$id}}"
                               class="btn btn-default btn-xs"
                               style="margin-bottom:-1px;"
                               title="Edit this Sheep">Edit Sheep
                            </a>
                        </td>
                        <td>
                            <a href="enterdeath/{{$id}}/{{$e_flock}}/{{$e_tag}}/{{$sex}}"
                               class="btn btn-default btn-xs btn-inverse"
                               style="margin-bottom:-1px;"
                               title="Edit this Sheep">Enter Death of this Sheep
                            </a>
                        </td>
                    @endif
                </tr>

            </tbody>
        </table>
    </div>
@stop