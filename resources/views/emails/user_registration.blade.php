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
              <td style="text-align:center;"><h2 style="color:#73064a; font-size:21px; line-height:31px; margin: 20px 0;">{{ __('lang.hi')}} {{$name}}, {{ __('messages.thank_you_joined_us_msg')}} <span style="color:#fd6964;">{{ __('messages.how_to_shopping_msg')}}</span></h2></td>
            </tr>
            <tr>
              <td style="text-align:center;"><p style="margin:17px 0 20px 0; font-weight:600; color:#73064a;"><span style="color:#fd6964; text-transform:uppercase;">{{ __('messages.SHOP')}}</span> {{ __('messages.browse_product_add_to_cart_msg')}}</p>
                <p style="margin:17px 0 20px 0; font-weight:600; color:#73064a;"><span style="color:#fd6964; text-transform:uppercase;">{{ __('messages.SHARE')}}</span> {{ __('messages.spread_with_friends_family_msg')}}</p>
                <p style="margin:17px 0 20px 0; font-weight:600; color:#73064a;"><span style="color:#fd6964; text-transform:uppercase;">{{ __('messages.PAY')}}</span> {{ __('messages.secure_payment_msg')}}</p>
                <p style="margin:17px 0 20px 0; font-weight:600; color:#73064a;"><span style="color:#fd6964; text-transform:uppercase;">{{ __('messages.DELIVERED')}}</span> {{ __('messages.deliver_directly_to_your_door_msg')}}</p>
                <p style="font-size:21px; line-height:31px; font-weight:bold; color:#73064a; text-align:center; margin:20px 0; text-transform:uppercase;">{{ __('messages.bag_our_most_popular_products')}}</p></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="two-col" style="margin-bottom:20px;">
                  <tbody>
					@if(!empty($baskets))
                    <tr>
					  @foreach($baskets as $basket)	
                      <td style="text-align:center; position:relative; vertical-align: middle;">
					  <a href="{{url('/')}}">
					  @if(!empty($basket['get_images']))
						  <img src="{{url('/')}}/uploads/basket/resized/{{$basket['get_images'][0]['image']}}" alt="{{$basket['title']}}" style="margin:0 auto; border-radius:7px; overflow:hidden; vertical-align: middle; width: 100%; height: 100%; max-width: 286px; max-height: 205px;" /> 
					  @else
						<img class="img-fluid" src="{{url('/')}}/assets/front/images/no-image-basket.jpg" alt="{{$basket['title']}}" style="margin:0 auto; border-radius:7px; overflow:hidden; vertical-align: middle; width: 100%; height: 100%; max-width: 286px; max-height: 205px;" /> 
						@endif
                        <div style="background-image: url({{url('/')}}/assets/img/overlay-img.png); background-repeat: repeat-x;
    background-position: top left; width: 100%; height: 100%; max-width: 286px; max-height: 205px; display: block; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); overflow: hidden; border-radius: 7px;"></div>
                        <div style="position:absolute; bottom:15px; left:50%; transform:translateX(-50%); margin:0 auto; width:100%; color:#fff;">
                          <p>{{$basket['title']}}</p>
                          @if($basket['basket_discount'])
                          <small style="font-weight: 600; font-size: 12px;">{{$basket['basket_discount']}}% Discount per case</small>
						  @endif
						  </div>
						  </a>
						</td>
						@endforeach
                    </tr>
					@endif
                  </tbody>
                </table></td>
            </tr>

            <tr> </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td style="background-color:#fd6964; padding:15px 0;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width:630px; margin:0 auto; padding:0 15px;" class="two-col">
          <tbody>
            <tr>
              <td style="text-align:left;"><img src="https://tijara.techbeeconsulting.com/uploads/img/1620728258_187920862.png" alt="{{ __('lang.tijara_logo_alt')}}" style="vertical-align:middle;"></td>
            <td style="text-align:right;"><a href="#" target="_blank" style="display:inline-block; margin-right:10px;"><img src="{{url('/')}}/assets/img/instagram-icon.png" alt="Instagram" style="vertical-align:middle;"></a> <a href="#" target="_blank" style="display:inline-block; margin-right:10px;"><img src="{{url('/')}}/assets/img/facebook-icon.png" alt="Facebook" style="vertical-align:middle;"></a> <a href="#" target="_blank" style="display:inline-block;"><img src="{{url('/')}}/assets/img/twitter-icon.png" alt="Twitter" style="vertical-align:middle;"></a></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>
