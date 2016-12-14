@extends('app')
@section('title')
    <title>{!! $title !!}</title>
@stop
@section('content')
    <div style="width:60%;margin-left:20%;">
        <h4>{!! $title !!} - Movement of a Double Tagged Sheep Off Holding</h4>
        {!!Form::open(array('url' => 'sheep/sheepoff','class'=>'form-inline'))!!}

        {!!Form::input('hidden','id',$id) !!}
        {!!Form::input('hidden','sex',$sex) !!}

        {!!Form::label('text','Date') !!}
        {!!Form::input('int','day',date('d'),['class'=>'new_class','placeholder'=>'DD','size' => '1']) !!}
        {!!Form::input('int','month',date('m'),['class'=>'new_class input-xs','placeholder'=>'MM','size' => '1']) !!}
        {!!Form::input('int','year',date('Y'),['class'=>'new_class input-xs','placeholder'=>'YYYY','size' => '3']) !!}<br>
        {!!$errors->first('day','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('month','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('year','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','UK 0 ')!!}
        {!!Form::input('text','e_flock',NULL,['class'=>'new_class input-xs','placeholder'=>'EID Flock Number'])!!}
        {!!Form::input('text','e_tag',NULL,['class'=>'new_class input-xs','placeholder'=>'Tag Number'])!!}<br>
        {!!$errors->first('e_flock','<small style="color:#f00">:message</small>')!!}
        {!!$errors->first('e_tag','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','Destination Holding')!!}
        {!!Form::input('text','destination',NULL,['class'=>'new_class input-xs','placeholder'=>'Number or Name'])!!}<br>
        {!!$errors->first('destination','<small style="color:#f00">:message</small>')!!}<br>

        {!!Form::label('text','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')!!}
        {!!Form::submit('Load Movement',['class'=>'btn btn-info btn-xs'])!!}<br>
        <?if (null !=(Session::get('find_error'))){
            echo (Session::pull('find_error'));
        } ?>

        {!!Form::close()!!}<br>
    </div>
@stop