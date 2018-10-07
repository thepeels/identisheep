@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')

    <div style="width:60%;margin-left:20%;">
        <h4>{!! $title !!}</h4>
        <?php $flock = isset($e_flock)?$e_flock:NULL ;?>
        {!!Form::open(array('url' => '/sheep/seek','class'=>'form-inline'))!!}

        {!!Form::label('text','UK 0')!!}
        {!!Form::input('text','e_flock',$flock,['class'=>'new_class input-xs','placeholder'=>' Flock Number'])!!}
        {!!Form::input('int','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>' Tag Number'])!!}<br>
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}<br>
        
        @if (null != Session::get('find_error'))
            {!! Session::pull('find_error')!!}
        @endif<br>
        
        {!!Form::submit('Find Sheep to Edit tags',['name'=>'find','class'=>'btn btn-info btn-xs'])!!}<br><br>
        {!!Form::submit('View all details and/or Record Death',['name'=>'view','class'=>'btn btn-danger btn-xs'])!!}<br>

        {!!Form::close()!!}<br>
    </div>

@stop