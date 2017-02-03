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
        {!! $group->description !!} {!! $group->info !!}
        <? $counter = 0;?>
        <table class="table table-striped table-bordered table-sm table-condensed print medium-to-wide" >
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
                    <td><a href = "detach/{{$member->id}}/{{$group->id}}" class="btn btn-default btn-xs">Remove fom Group</a></td>
                </tr>
            @endforeach
        </table>
        Total {!! $counter !!} sheep.
        {!! Form::open(array('url' => '/group/add-on-the-fly','class'=>'form-inline', 'files' => true)) !!}
        {!! Form::hidden('group',$group->id) !!}<br>
        {!! Form::label('text','Additional sheep - Flock and Individual Number')!!}<br>
        {!! Form::label('text','UK 0')!!}
        {!! Form::input('text','e_flock',old('e_flock'),['class'=>'new_class input-xs','placeholder'=>'Flock Number']) !!}
        {!! Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Tag Number']) !!}<br>
        {!! $errors->first('e_flock','<small style="color:#f00">:message</small>') !!}{!! $errors->first('e_tag','<small style="color:#f00">:message</small>') !!}<br>
        {!!Form::submit('Add to group',['class'=>'btn btn-info btn-xs'])!!}
    </div>
@stop