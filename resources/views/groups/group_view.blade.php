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
        <h4>{{$group_name}}</h4>
        <h5>{{$title}}</h5>
        <? $counter = 0;?>
        <table class="table table-striped table-bordered table-sm table-condensed print medium-width" >
            <thead>
            <th>Ref</th>
            <th>Number</th>
            <th>Colour</th>
            <th>Moved On</th>
            </thead>
            @foreach($group->sheep as $member)
                <?$counter++;?>
                <tr>
                    <td>{!! $member->getLocalId() !!}</td>
                    <td>UK0 {!! $member->getFlockNumber() !!} - {!! sprintf('%05d',$member->getSerialNumber()) !!}</td>
                    <td>{!! $member->getTagColour() !!}</td>
                    <td>{!! date('d M Y',strtotime($member->getMoveOn())) !!}</td>
                </tr>
            @endforeach
        </table>
        Total {!! $counter !!} sheep.
    </div>
@stop