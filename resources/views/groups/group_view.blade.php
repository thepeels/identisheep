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
        <div class="no-screen">
            <h4>{{$business}} &nbsp;&nbsp;&nbsp;&nbsp;{{$address}}<br> Holding No:{{$holding}}</h4>
        </div>
        <h4>{{$group_name}}</h4>
        <h5>{{$title}}</h5>
        <? $counter = 0;?>
        {!! $group->description !!} {!! $group->info !!}
        <table class="table table-striped table-bordered table-sm table-condensed print medium-to-wide" >
            <thead>
            <th>Ref</th>
            <th>Number</th>
            <th>Colour</th>
            <th>Source Holding</th>
            <th>Moved On</th>
            <th>Moved Off</th>
            </thead>
            @foreach($group->sheep as $member)
                <tr>
                    <td>{!! $member->getLocalId() !!}</td>
                    <td>{!! $member->getCountryCode() !!} {!! $member->getFlockNumber() !!} - {!! sprintf('%05d',$member->getSerialNumber()) !!}</td>
                    <td>{!! $member->getTagColour() !!}</td>
                    <td>{!! $member->getSource() !!}</td>
                    <td>{!! $member->date_on !!}</td>
                    <td>{!! $member->date_off !!}</td>
                    <td class="no-print"><a href = "../../../group/detach/{{$member->id}}/{{$group->id}}" class="btn btn-default btn-xs">Remove from Group</a></td>
                </tr>
            @endforeach
        </table>
        Total {!! $member->number !!} sheep.
        {!! Form::open(array('url' => '../group/add-on-the-fly','class'=>'form-inline no-print', 'files' => true)) !!}
        {!! Form::hidden('group',$group->id) !!}<br>
        {!! Form::label('text','Additional sheep - Flock and Individual Number')!!}<br>
        {!! Form::label('text','UK 0')!!}
        {!! Form::input('text','e_flock',old('e_flock'),['class'=>'new_class input-xs','placeholder'=>'Flock Number']) !!}
        {!! Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Tag Number']) !!}<br>
        {!! $errors->first('e_flock','<small style="color:#f00">:message</small>') !!}{!! $errors->first('e_tag','<small style="color:#f00">:message</small>') !!}<br>
        {!!Form::submit('Add to group',['class'=>'btn btn-info btn-xs'])!!}
    </div>
    @if(Auth::user()->id == 1)
    <div style="width:60%;margin-left:20%;margin-top:30px;" class="no-print">
        <a href="../inventory/add-group/{{ $group->id }}" class="btn btn-default btn-xs">Add Live Group Members to Inventory</a>
    </div>
    @endif
    
@stop