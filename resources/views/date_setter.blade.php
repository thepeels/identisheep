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
        <h4>{{$title}}</h4><br>
        <h4>Either accept the default 'Last 10 years' date range</h4>
        <h4>or change this range to your needs</h4>

        {!!Form::open(array('url' => '/sheep/datesetter','class'=>'form-inline'))!!}

        {!!Form::label('text','Start Date') !!}
        {!!Form::input('int','day',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',date('m'),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',date('Y')-10,['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','End Date') !!}
        {!!Form::input('int','day_to',date('d'),['class'=>'new_class input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month_to',date('m'),['class'=>'new_class','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year_to',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day_to','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month_to','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year_to','<small style="color:#f00">:message</small>')!!}<br>
        <h4>or one of the options below</h4>
        {!! Form::input('checkbox','thisyear') !!}
        {!! Form::label('text','Select one year prior to 1st December THIS year') !!}<br>
        {!! Form::input('checkbox','lastyear') !!}
        {!! Form::label('text','Select one year prior to 1st December LAST year') !!}<br><br>
        {!!Form::submit($title,['onclick'=>'self.close()','class'=>'btn btn-info btn-xs'])!!}<br>
        {!!$errors->first('date_set') !!}<br>
    {{--{!!Form::submit('Finished',['onclick'=>'self.close()','class'=>'btn btn-info btn-xs'])!!}<br>--}}
    </div>
<!--<script>
            window.onunload = refreshParent;
            function refreshParent() {
            window.opener.location.reload();
            }
</script>-->
@stop
