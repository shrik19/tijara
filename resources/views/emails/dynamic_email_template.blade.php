<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{{ __('lang.welcome')}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	border: 0;
}
body {
	font-family: 'Poppins', sans-serif;
	color: #202f3c;
	font-weight: bold;
	font-size: 16px;
	line-height: 25px;
}
.white-on-hover:hover {
	background-color: #fff!important;
	color: #73064a!important;
}
.pink-on-hover:hover {
	background-color: #fd6964!important;
	color: #fff!important;
}
.text-pink-on-hover:hover {
	color: #fd6964!important;
}
.text-purple-on-hover:hover {
	color: #73064a!important;
}

@media(max-width:629px) {
h1 {
	font-size: 30px!important;
	line-height: 40px!important;
}
td {
	display: block;
	clear: both;
	width: 100%;
	text-align: center!important;
}
.two-col td {
	margin: 10px 0;
}
}
</style>
</head>

<body>
{!! $email_body !!}
</body>
</html>
