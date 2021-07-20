<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>{{$siteDetails->site_title}}</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <link rel="apple-touch-icon" href="{{url('/')}}/assets/front/apple-touch-icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/fontawesome.min.css" integrity="sha512-OdEXQYCOldjqUEsuMKsZRj93Ht23QRlhIb8E/X0sbwZhme8eUw6g8q7AdxGJKakcBbv7+/PX0Gc2btf7Ru8cZA==" crossorigin="anonymous" />
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/bootstrap.min.css">

  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/main.css">
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/jquery-confirm.min.css">
  <!-- added custom css for custom chnages -->
  <link rel="stylesheet" href="{{url('/')}}/assets/front/css/custom.css">
  <style>
   .loader{
    position: absolute;
    top:0px;
    right:0px;
    width:100%;
    height:100%;
    background-color:#eceaea;
    background-image:url('{{url('/')}}/assets/front/img/ajax-loader.gif');
    background-size: 50px;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40);
    display:none;
}
</style>
   <!-- end custom css for custom chnages -->
  <script src="{{url('/')}}/assets/front/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
  <script type="text/javascript"> 
    var siteUrl="{{url('/')}}";
    var product_add_success     = "{{trans('messages.product_add_success')}}";
    var product_update_success  = "{{trans('messages.product_update_success')}}";
    var product_remove_success  = "{{trans('messages.product_remove_success')}}";
    var product_remove_confirm  = "{{trans('lang.shopping_cart_confirm_remove')}}";
    var product_qty_error       = "{{trans('messages.product_qty_error')}}";
    var product_variant_error   = "{{trans('messages.product_variant_error')}}";
    var wishlist_add_success    = "{{trans('messages.wishlist_add_success')}}";
    var wishlist_product_remove = "{{trans('messages.wishlist_product_remove')}}";
    var wishlist_product_remove_success = "{{trans('messages.wishlist_product_remove_success')}}";
  </script>
  <script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
  <script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script>
  <script src="{{url('/')}}/assets/front/js/jquery-confirm.min.js"></script>

</head>
<body>
  <header>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <hr class="top_line"/>

      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <a class="navbar-brand" href="{{url('/')}}"><img class="logo" src="{{url('/')}}/uploads/Images/{{$siteDetails->header_logo}}" height="70"/></a>
          </div>

          <div class="col-md-8">
            <div class="top_login">
              <div class="pull-right">
                @if(Auth::guard('user')->id())
                <a href="javascript:void(0)"  class="dropdown-toggle"  type="button" data-toggle="dropdown"><h3 class="de_col"><i class="fa fa-user"></i>{{ __('lang.my_account_title')}}</h3></a>
                <ul class="dropdown-menu">

                  <li><a href="{{route('frontUserProfile')}}">{{ __('users.profile_label')}}</a></li>
                  
                  
                  <li><a href="{{route('frontAllOrders')}}">@if(session('role_id')==1) {{ __('lang.manage_orders_menu')}} @else {{ __('lang.txt_seller_order')}} @endif</a></li>

                  <li><a href="{{route('frontAllServiceRequest')}}">@if(session('role_id')==1) {{ __('lang.my_service_request')}} @else {{ __('lang.all_service_request')}} @endif</a></li>

                  <li><a href="{{route('manageFrontProducts')}}">{{ __('lang.manage_products_menu')}}</a></li>
                  <li><a href="{{route('frontProductAttributes')}}">{{ __('lang.manage_attributes_menu')}}</a></li>
                  @if(session('role_id')==2)
                    <li><a href="{{route('manageFrontServices')}}">{{ __('lang.manage_services_menu')}}</a></li>
                    <li><a href="{{route('frontSellerPersonalPage')}}">{{ __('users.seller_personal_page_menu')}}</a></li>
                    <li><a href="{{route('frontSellerPackages')}}">{{ __('lang.packages_menu')}}</a></li>

                  @endif
                  <li><a href="{{route('frontChangePassword')}}">{{ __('lang.change_password_menu')}}</a></li>
                  <li><a href="{{route('frontLogout')}}">{{ __('lang.logout_label')}}</a></li>
                </ul>

                @else
                <h3 class="de_col"><a  href="{{route('frontLogin')}}"  title="{{ __('users.login_label')}}"> {{ __('users.login_label')}} <i class="fas fa-user-check de_col"></i></a></h3>
                @endif

              </div>





              <div class="clearfix"></div>
                <form method="POST" action="{{route('AllproductListing')}}" class="navbar-form navbar-right header_left_container" role="form">
                  @csrf
                <div class=" form-group search_now_input_box">
                  <!-- <input type="text" placeholder="Email" class="form-control search_now_input"> -->
                  <input type="text" class="form-control search_now_input" placeholder="{{ __('lang.search_placeholder')}}" name="search" id="search_string">
                  <button class="search_icon_btn" type="submit"><i class="fa fa-search"></i></button>
                </div>
                  <div class="form-group cart_details">
                    <a @if(Auth::guard('user')->id() && session('role_id')==1) href="{{route('frontShowCart')}}" @else href="javascript:alert('{{trans('errors.login_buyer_required')}}')" @endif>
                      <i class="glyphicon glyphicon-shopping-cart cart_icon"></i>
                    </a>
                    @php
                      $productCnt = getOrderProducts(Auth::guard('user')->id());
                    @endphp
                    @if($productCnt > 0)
                    <div class="cart_count">
                      <span>{{$productCnt}}</span>
                    </div>
                    @endif
                  </div>

                  <div class="form-group cart_details" style="padding-left:0px;">
                    <a @if(Auth::guard('user')->id() && session('role_id')==1) href="{{route('frontShowWishlist')}}" @else href="javascript:alert('{{trans('errors.login_buyer_required')}}')" @endif>
                        <i class="glyphicon glyphicon-heart" style="font-size:20px;"></i>
                      </a>
                      @php
                        $productWishlistCnt = getWishlistProducts(Auth::guard('user')->id());
                      @endphp
                      @if($productWishlistCnt > 0)
                      <div class="cart_count">
                        <span>{{$productWishlistCnt}}</span>
                      </div>
                      @endif
                  </div> 
                <button type="button" class=" btn buy_now_btn debg_color" onclick="location.href='{{route('AllproductListing')}}';">{{ __('lang.buy_now_btn')}}</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <div class="clearfix"></div>
