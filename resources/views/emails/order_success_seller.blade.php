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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td style="height:173px; background-color:#73054a;"></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width:630px; margin:0 auto; padding:0 15px;">
          <tbody>
            <tr>
              <td style="text-align:center;"><img src="{{url('/')}}/assets/img/logo.png" alt="{{ __('lang.welcome')}}" style="margin:-64px auto 0 auto; vertical-align:bottom"></td>
            </tr>
           <tr>
                <td style="text-align:center;"><h2 style="color:#000; font-size:26px; line-height:36px; margin-top: 40px;">{{ __('lang.hi')}} {{ $name }}!</h2></td>
              </tr>
              <tr>
                <td style="text-align:center;"><p style="margin:17px 0 35px 0;font-weight:600;color:#03989e;">Ny beställning gjord. Nedan är länken för att kontrollera orderinformation. </p></td>
              </tr>
              <tr>
                <td><a href="{{ $order_details_link }}">{{ $order_details_link }}</a></td>
              </tr>
            <tr> </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td style="background-color:#fd6964; padding:15px 0;">&nbsp;
				<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width:630px; margin:0 auto; padding:0 15px;" class="two-col">
          <tbody>
            <tr>
              <td style="text-align:left;"><img src="https://tijara.techbeeconsulting.com/uploads/img/1620728258_187920862.png" alt="{{ __('lang.tijara_logo_alt')}}" style="vertical-align:middle;"></td>
            <td style="text-align:right;"><a href="#" target="_blank" style="display:inline-block; margin-right:10px;"><img src="{{url('/')}}/assets/img/instagram-icon.png" alt="Instagram" style="vertical-align:middle;"></a> <a href="#" target="_blank" style="display:inline-block; margin-right:10px;"><img src="{{url('/')}}/assets/img/facebook-icon.png" alt="Facebook" style="vertical-align:middle;"></a> <a href="#" target="_blank" style="display:inline-block;"><img src="{{url('/')}}/assets/img/twitter-icon.png" alt="Twitter" style="vertical-align:middle;"></a></td>
            </tr>
          </tbody>
        </table> -->
			</td>
    </tr>
  </tbody>
</table>
</body>
</html>
