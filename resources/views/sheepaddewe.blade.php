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
        {{date('d-m-Y',strtotime('second friday september 2015'))}}
@stop
@section('content')
    <div style="width:60%;margin-left:20%;">
        <h4>{!! $title !!}</h4>
        {!!Form::open(array('url' => '/sheep/addewe','class'=>'form-inline'))!!}
        {!!Form::label('text','Entry Date') !!}
        {!!Form::input('int','day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',date('m'),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::input('hidden','id',$id)!!}
        {!!Form::input('hidden','sex',$sex)!!}
        {!!Form::label('text','UK 0')!!}
        {!!Form::input('text','e_flock',NULL,['class'=>'new_class input-xs','placeholder'=>'Flock Number'])!!}
        {!!Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>$sex])!!}<br>
        {!!Form::label('text','Tag Colour')!!}
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}<br>
        {!!Form::input('text','colour_of_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Colour'])!!}

        {!!Form::submit($title,['class'=>'btn btn-info btn-xs'])!!}

        {!!Form::close()!!}<br>
    </div>

@stop