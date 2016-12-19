@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')
    <div style="width:60%;margin-left:20%;">
        @if($home_bred == 'false')
            <h4>{!! $title !!}</h4>
        @else
            <h4>{!! $alt_title !!}</h4>
        @endif
            <h5>(A continuous series of tags with no spaces.)</h5>
        <h5>Start a new batch after any spaces in the series.</h5>
        {!!Form::open(array('url' => '/batch/batch','class'=>'form-inline'))!!}

        {!!Form::input('hidden','id',$id) !!}
        {!!Form::input('hidden','home_bred',$home_bred)!!}<br>

        {!!Form::label('text','Entry Date') !!}
        {!!Form::input('int','day',date('d'),['class'=>'new_class','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',date('m'),['class'=>'new_class input-xs','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        @if($home_bred == 'false')
            {!!Form::label('text','UK 0 ')!!}
            {!!Form::input('text','flock_number',NULL,['class'=>'new_class input-xs','placeholder'=>'EID Flock Number'])!!}<br>
            {!!$errors->first('flock_number','<small style="color:#f00">:message</small>')!!}<br>
        @else
            {!!Form::label('text','UK 0 '.$home_bred)!!}
            {!!Form::input('hidden','flock_number',$home_bred,['class'=>'new_class input-xs','placeholder'=>'EID Flock Number'])!!}<br>
            {!!$errors->first('flock_number','<small style="color:#f00">:message</small>')!!}<br>
        @endif
        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::input('int','start_tag',NULL,['class'=>'new_class','placeholder'=>'Start Tag Number'])!!}<br>
        {!!$errors->first('start_tag','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::input('int','end_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Finish Tag Number'])!!}<br>
        {!!$errors->first('end_tag','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','Tag Colour')!!}
        {!!Form::input('text','colour_of_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Colour'])!!}<br>
        {!!$errors->first('colour_of_tag','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::submit('Load batch',['class'=>'btn btn-info btn-xs'])!!}<br>
    <?if (null !=(Session::get('find_error'))){
        echo (Session::pull('find_error'));
        } ?>

        {!!Form::close()!!}<br>
    </div>
@stop