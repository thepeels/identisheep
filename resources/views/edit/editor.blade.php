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
        <h4>{!! $title.' for sheep number '. $ewe->flock_number . ' ' . sprintf('%05d',$ewe->serial_number) !!}</h4>
    
    
        
            {!!Form::open(array('url' => '/edit/full','class'=>'form-horizontal'))!!}
            {!!Form::input('hidden','id',$ewe->id)!!}
            <div class="form-group">
                {!!Form::label('text','Country code',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','country_code',$ewe->country_code,['class'=>'form-control','placeholder'=>$ewe->country_code])!!}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('text','Flock Number',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','e_flock',$ewe->flock_number,['class'=>'form-control','placeholder'=>$ewe->flock_number,'autofocus'=>"autofocus"])!!}
                    {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('text','Serial Number',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','e_tag',$ewe->serial_number,['class'=>'form-control','placeholder'=>sprintf('%05d',$ewe->serial_number)])!!}
                    {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('text','Colour Tag Flock Number',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','supp_flock',$ewe->supplementary_tag_flock_number,['class'=>'form-control','placeholder'=>$ewe->supplementary_tag_flock_number])!!}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('text','Colour Tag Serial Number',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','supp_serial',$ewe->serial_number,['class'=>'form-control','placeholder'=>$ewe->serial_number])!!}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('text','Sex',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','sex',$ewe->sex,['class'=>'form-control','placeholder'=>$ewe->sex])!!}
                </div>
            </div>
            <div class="form-group">
                {!!Form::label('text','Source',['class' => 'col-sm-3 control-label'])!!}
                <div class="col-sm-2">
                    {!!Form::input('text','source',$ewe->source,['class'=>'form-control','placeholder'=>$ewe->source])!!}
                </div>
            </div>
            
            
            <br>
            {!!Form::submit('Load Edits',['class'=>'btn btn-info btn-sm '])!!}
            {!!Form::close()!!}<br>
        
    
    </div>

@stop