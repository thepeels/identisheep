@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')
    <div style="width:60%;margin-left:20%;">
        <h4>{!! $title !!} - Movement of sheep onto holding</h4>
        <h5>(A continuous series of tags with no spaces.)</h5>
        <h5>Start a new batch after any spaces in the series.</h5>
        {!!Form::open(array('url' => '/sheep/batch','class'=>'form-inline'))!!}

        {!!Form::input('hidden','id',$id) !!}

        {!!Form::label('text','Entry Date') !!}
        {!!Form::input('int','day',NULL,['class'=>'newclass input-xs','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',NULL,['class'=>'newclass input-xs','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',NULL,['class'=>'newclass input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','UK 0 ')!!}
        {!!Form::input('text','flock_number',NULL,['class'=>'newclass input-xs','placeholder'=>'EID Flock Number'])!!}<br>
        {!!$errors->first('flock_number','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::input('int','start_tag',NULL,['class'=>'newclass input-xs','placeholder'=>'Start Tag Number'])!!}<br>
        {!!$errors->first('start_tag','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::input('int','end_tag',NULL,['class'=>'newclass input-xs','placeholder'=>'Finish Tag Number'])!!}<br>
        {!!$errors->first('end_tag','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::submit('Load batch',['class'=>'btn btn-info btn-xs'])!!}<br>
    <?if (null !=(Session::get('find_error'))){
        echo (Session::pull('find_error'));
        } ?>

        {!!Form::close()!!}<br>
    </div>
@stop