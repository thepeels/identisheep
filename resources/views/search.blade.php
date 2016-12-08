@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')

    <div style="width:60%;margin-left:20%;">
        <h4>{!! $title !!}</h4>
        {!!Form::open(array('url' => '/sheep/search','class'=>'form-inline'))!!}

        {!!Form::label('text','Tag Serial Number')!!}
        {!!Form::input('text','tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Number'])!!}
        {!!$errors->first('tag','<small style="color:#f00">:message</small>')!!}<br>

        <?if (null !=(Session::get('find_error'))){
            echo (Session::pull('find_error'));
        }?><br>
        {!!Form::submit('Search',['name'=>'find','class'=>'btn btn-info btn-xs'])!!}<br><br>

        {!!Form::close()!!}<br>
    </div>

@stop