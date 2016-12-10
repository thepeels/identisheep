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
        @if($home_bred == 'false')
        <?$home_bred = FALSE;?>
            <h4>{!! $title !!}</h4>
        @else
            <h4>{!! $alt_title !!}</h4>
        @endif
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
        {!!Form::input('hidden','home_bred',$home_bred)!!}<br>
        @if($home_bred == FALSE)
            {!!Form::label('text','UK 0')!!}
            {!!Form::input('text','e_flock',old('e_flock'),['class'=>'new_class input-xs','placeholder'=>'Flock Number'])!!}
        @else
            {!!Form::label('text','UK 0 '.$home_bred)!!}
            {!!Form::input('hidden','e_flock',$home_bred,['class'=>'new_class input-xs','placeholder'=>'Flock Number'])!!}
        @endif
        {!!Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Tag Number'])!!}<br>
        {!!Form::label('text','Tag Colour')!!}
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}<br>
        {!!Form::input('text','colour_of_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Colour'])!!}

        {!!Form::submit($title,['class'=>'btn btn-info btn-xs'])!!}

        {!!Form::close()!!}<br>
    </div>

@stop