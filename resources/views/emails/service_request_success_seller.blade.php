<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{{ __('lang.newRequestReceived')}}</title>
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
              <td style="text-align:center;"><img src="{{url('/')}}/assets/img/logo.png" style="margin:-64px auto 0 auto; vertical-align:bottom"></td>
            </tr>
           <tr>
                <td style="text-align:center;"><h2 style="color:#000; font-size:26px; line-height:36px; margin-top: 40px;">{{ __('lang.hi')}} {{ $seller }}!</h2></td>
              </tr>
              <tr>
                <td style="text-align:center;"><p style="margin:17px 0 35px 0;font-weight:600;color:#03989e;">{{ __('lang.newRequestReceived')}} </p></td>
              </tr>
              <tr>
                <td style="text-align:center;">{{ __('lang.customer')}}: {{ $customername }} / {{ $phone_number }}</td>
                
              </tr>
              <tr>
                <td style="text-align:center;">{{ __('lang.service')}}: {{ $service }}</td>
                
              </tr>
              <tr>
                <td style="text-align:center;">{{ __('lang.service_time')}}: {{ $service_time }}</td>
                
              </tr>
              <tr>
                
                <td style="text-align:center;">{{ __('lang.message')}}: {{ $servicemessage }}</td>
              </tr>
              <tr>
                <td style="text-align:center;">&nbsp;</td>
              </tr>
            <tr> </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td style="background-color:#fd6964; padding:15px 0;">&nbsp;
				
			</td>
    </tr>
  </tbody>
</table>
</body>
</html>
