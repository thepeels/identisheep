<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="/css/app.css" rel="stylesheet">
	@yield('title')
	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href="/css/override.css" rel="stylesheet">
	<link href="/css/print.css" rel="stylesheet" media="print" type="text/css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<?
$url = Request::path();
$elements = explode('/', $url);
$help_page = $elements[sizeof($elements)-1];
?>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Flock Records</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/">Home</a></li>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Sheep Lists<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/sheep/list/{print}">Ewes</a></li>
							<li><a href="/sheep/tups">Tups</a></li>
							<li><a href="/sheep/noneid">Non-EID</a></li>
							<li><a href="/sheep/replaced">Replaced Tags</a></li>
							<li><a href="/batch/singlelist">Batch Tags Used</a></li>
						</ul>
					</li>
					<li><a href="/sheep/seek">Find a Sheep</a></li>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Single Sheep Entry<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/sheep/addewe">Add a Ewe</a></li>
							<li><a href="/sheep/addtup">Add a Tup</a></li>
						</ul>
					</li>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Batch On<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/batch/batch">Batch On</a></li>
							<li><a href="/batch/batchopson">.csv Batch On</a></li>
						</ul>
					</li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Batch Off<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/batch/batchops">.csv Batch Off</a></li>
							<li><a href="/batch/batchoff">Breeding Batch Off</a></li>
							<li><a href="/batch/singleoff">Single Tag Batch Off</a></li>
						</ul>
					</li>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Single Exit<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/sheep/eweoff">Ewe Off</a></li>
							<li><a href="/sheep/tupoff">Tup Off</a></li>
						</ul>
					</li>
					<li><a href="/sheep/death">Record Death</a></li>
					<li><a href="/sheep/offlist">Off List</a></li>
					<li><a href="/sheep/deadlist">Dead List</a></li>
					<li><a href="../help/{{$help_page}}">Help</a></li>
					@if (Auth::guest())
						<li><a href="/auth/login">Login</a></li>
						<li><a href="/auth/register">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/auth/logout">Logout</a></li>
								<li><a href="/sheep/delete">Delete old Records</a></li>
								<li><a href="/sheep/search">Search</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>
