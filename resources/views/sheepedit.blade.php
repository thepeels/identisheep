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
    <h4>{!! $title.' for sheep number '. $id !!}</h4>
    {!!Form::open(array('url' => '/sheep/changetags','class'=>'form-inline'))!!}

    {!!Form::input('hidden','id',$id)!!}
    {!!Form::input('text','e_flock',$e_flock,['class'=>'newclass input-xs','placeholder'=>' New Flock Number'])!!}
    {!!Form::input('text','e_tag',sprintf('%05d',$e_tag),['class'=>'newclass input-xs','placeholder'=>' New Number'])!!}

    {!!Form::submit('Add/Change',['class'=>'btn btn-info btn-xs'])!!}
    {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}<br>

    {!!Form::close()!!}<br>
</div>

@stop