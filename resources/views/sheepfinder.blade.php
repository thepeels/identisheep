@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')

    <div style="width:60%;margin-left:20%;">
        <h4>{!! $title !!}</h4>
        <?$flock = isset($e_flock)?$e_flock:NULL ;?>
        {!!Form::open(array('url' => '/sheep/seek','class'=>'form-inline'))!!}

        {!!Form::label('text','UK 0')!!}
        {!!Form::input('text','flock',$flock,['class'=>'newclass input-xs','placeholder'=>' Flock Number'])!!}
        {!!Form::input('int','tag',NULL,['class'=>'newclass input-xs','placeholder'=>' Tag Number'])!!}<br>
        {!!$errors->first('flock','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}<br>

        <?if (null !=(Session::get('find_error'))){
        echo (Session::pull('find_error'));
        }?><br>
        {!!Form::submit('Find Sheep to Edit tags',['name'=>'find','class'=>'btn btn-info btn-xs'])!!}
        {!!Form::submit('View all details',['name'=>'view','class'=>'btn btn-info btn-xs'])!!}<br>

        {!!Form::close()!!}<br>
    </div>

@stop