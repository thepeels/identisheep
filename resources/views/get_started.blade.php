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
        <h4>{{$title}}</h4>
        <ul>
            <li>IdentiSheep is intended to help with keeping the minimum records required under the Sheep and Goat
                Identification regulations;<br> and in doing this help to prevent breaches of Cross Compliance.</li>
            <li>First you need to Register; <br> the details asked for are needed in order to be able to print lists
                for the Annual Inventory which are customised with your flock details.</li>
            <li>The website will hold a list of your EID sheep tags, and if you are meticulous about recording movements on
                and off, and also deaths, then lists of ear tags of sheep on your holding are readily available.</li>
            <li>Replacement tags can be recorded, and details of the original tags are retained with the new ones, and searching <br>
                for either the new or original tags will bring up the 'sheep' that has the tags.</li>
            <li>The tags are loaded into the website, in any of four ways.
                <ol><li>A batch of tags with the same flock number and consecutive serial numbers, is entered in a form.</li>
                    <li>A batch of tags from stick reader or similar is loaded as a file.</li>
                    <li>A single tag can be entered, (useful for bought-in tups and for hand recorded tags where the
                        RFID has failed for example).</li>
                    <li>Deaths or Movements Off (individually or as batches as above) will be entered into the database if
                        they do not already exist.</li>
                </ol></li>
            <li>Tags will not be added into the database if they are already present, so if you enter a list of sheep
                twice over by mistake, or you have two lists from different days with some sheep present in both lists,
                the sheep already in will not be added again. This last point is very useful
                in the start up period, because gradually the correct sheep will get entered, even if you have
                overlapping lists.</li>
            <li>With some effort to be methodical, a list of all your sheep will get compiled, and will at least be
                close enough to avoid a Cross Compliance Breach.</li>
            <li>Use of the website is free for 6 months, and thereafter an Annual fee of £{{config('app.price')}} (a nice pub lunch) is charged.
                Good value to keep your payments safe and much much less than the extravagant software associated with
                some tag reading equipment.</li>
            <li style="list-style-type: none"><br> So go on give it a try, beg or borrow a stick reader, or dig out your
                movement-on documents if you buy in replacements. Your tag purchase invoices too, can be a good starting point
                for home-bred flocks.<br>
                If you are lucky enough to have a mobile signal, you can even use it on your smart phone.</li>
        </ul>
        <a href="auth/register"
           class="btn btn-default "
           style="margin:30px 0 0 35%;"
           title="Get Registering">Go to Registration
        </a>

            
    </div>
@stop