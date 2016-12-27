		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
		@extends('app')
		@section('content')
		<!--<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

		</style>-->


		<div class="container" style="text-align: center;margin-top:200px;opacity:0.5;">
			<div class="content" style="font-size: 80px;margin-bottom: 40px;">
				<div class="title" style="font-size:96px;margin-bottom: 40px;">Welcome to <span class="red">Identi</span><span class="blue">Sheep</span> </div>
				<div class="quote" style="font-size: 24px;">{{ Inspiring::quote() }}</div>
			</div>
		</div>
	@stop


