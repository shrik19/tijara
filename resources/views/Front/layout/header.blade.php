<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>@if(isset($meta_title)) {{$meta_title}} @else {{$siteDetails->site_title}} @endif</title>
  <meta name="description" content=" @if(isset($meta_description)) {{$meta_description}} @endif ">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{url('/')}}/assets/img/favicon.png" type="image/x-icon">
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
  
    var wishlist_service_remove_success = "{{trans('messages.wishlist_service_remove_success')}}";
    var txt_your_comments = "{{ __('lang.txt_your_comments')}}";
    var txt_your_review = "{{ __('lang.txt_your_review')}}";
    var txt_comments = "{{ __('lang.txt_comments')}}";
    var txt_comments_err = "{{ __('lang.txt_comments_err')}}";
    var login_buyer_required = "{{ __('errors.login_buyer_required')}}";
    var review_add_success = "{{ __('messages.review_add_success')}}";
    var err_msg_okay = "{{ __('users.err_msg_okay')}}";
    var please_add_your_message = "{{ __('users.please_add_your_message')}}";
    var please_add_service_time = "{{ __('users.please_add_service_time')}}";
    var select_placeholder = "{{ __('lang.select_label')}}";
    var close_store_confirm_msg = "{{trans('messages.close_store_confirm_msg')}}";
    var cancel_js_btn = "{{ __('users.cancel_btn')}}";
    var js_confirm_msg = "{{ __('users.confirm_btn')}}";
    var delete_card_details_confirm = "{{ __('messages.delete_card_details_confirm')}}";
    var image_upload_height_width = "{{ __('errors.image_upload_height_width')}}";
    var oops_heading = "{{ __('users.oops_heading')}}";
    var success_heading = "{{ __('users.success_heading')}}";
    var cancel_btn = "{{ __('lang.cancel_btn')}}";
    var select_what_to_search = "{{ __('messages.select_what_to_search')}}";
    var del_variant_confirm_box = "{{ __('messages.del_variant_confirm_box')}}";
    var select_shopping_method_err = "{{ __('errors.select_shopping_method_err')}}";
    var not_found_payment_info = "{{ __('lang.not_found_payment_info')}}";
    var not_valid_credit_card_no = "{{ __('lang.not_valid_credit_card_no')}}";
    var card_declined_err = "{{ __('lang.card_declined_err')}}";
    var invalid_exp_month_err = "{{ __('lang.invalid_exp_month_err')}}";
    var invalid_exp_year_err = "{{ __('lang.invalid_exp_year_err')}}";
    var incorrect_card_number_err = "{{ __('lang.incorrect_card_number_err')}}";
    var rate_limit_err = "{{ __('lang.rate_limit_err')}}";
    var processing_error = "{{ __('lang.processing_error')}}";
    var missing_err =  "{{ __('lang.missing_err')}}";
    var incorrect_zip_err =  "{{ __('lang.incorrect_zip_err')}}";  
    var incorrect_cvc_err =  "{{ __('lang.incorrect_cvc_err')}}";
    var expired_card_err =  "{{ __('lang.expired_card_err')}}";    
    var invalid_cvc_err =  "{{ __('lang.invalid_cvc_err')}}";
    var enter_all_fields_err =  "{{ __('errors.enter_all_fields_err')}}";




    var is_login = 0;
    @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1)
    is_login = 1;
    @elseif(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==2)
    is_login = 2;
    @endif

  </script>
  <script src="{{url('/')}}/assets/front/js/vendor/jquery-1.11.2.min.js"></script>
  <script src="{{url('/')}}/assets/front/js/vendor/bootstrap.min.js"></script>
  <script src="{{url('/')}}/assets/front/js/jquery-confirm.min.js"></script>

</head>
<body>
  <header class="product_view">
    <nav class="navbar navbar-default navbar-fixed-top tj-navbar" role="navigation">
      <hr class="top_line"/>
<div class="product_view tijara_top_menu">
      <div class="container-fluid p-d-0">
        <div>
          <div class="tj-topmenu">
          <div class="wid-20 col-sm-12">
            <a class="navbar-brand tj-logo" href="{{url('/')}}"><img class="logo" src="{{url('/')}}/uploads/Images/{{$siteDetails->header_logo}}"/></a>
          </div>

          <div class="wid-85 col-xm-12">
            
            <div class="top_login">
            <div>
              @php
              $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
              if (strpos($url,'service') !== false) 
                $formUrl = route('AllserviceListing');
              else
                $formUrl  = route('AllproductListing');
              @endphp
                <form method="POST" action="{{$formUrl}}" class="navbar-form navbar-left header_left_container" role="form" id="product_service_search_from" onSubmit="return false" >
                  @csrf
                  <input type="hidden" name="search_type" id="product_service_search_type"> 
                  <div class="col-md-10">
                <div class=" form-group search_now_input_box">
                  <!-- <input type="text" placeholder="Email" class="form-control search_now_input"> -->
                  <input type="text" class="form-control search_now_input current_search_string" placeholder="{{ __('lang.search_placeholder')}}" name="search" id="search_string" autocomplete="off">
                   <div id="sellerListFilter"></div>
                  <button class="search_icon_btn" type="submit"><i class="fa fa-search"></i></button>
                </div>
                </div>
                <div class="col-md-2 p-0">
                  <ul class="right-icons">
                  <li>

<div>
@if(Auth::guard('user')->id())
<!-- <a href="/profile"   type="button" ><h3 class="de_col"><i class="fa fa-user"></i><span class="pro-text">{{ __('lang.my_account_title')}}</span></h3></a> -->
@if(Auth::guard('user')->getUser()->role_id == 1)
  <?php echo "here";exit;?>
<div class="pull-right">
           <!-- y -->
            <a href="javascript:void(0)"  class="dropdown-toggle"  type="button" data-toggle="dropdown"><h3 class="de_col"><img src="{{url('/')}}/assets/img/imgpsh_fullsize.png"height="28" /></h3></a>
            <ul class="dropdown-menu">

              <li style="width:100%"><a href="{{route('frontUserProfile')}}">{{ __('users.buyer_profile_update_title')}}</a></li>

              <li  style="width:100%"><a href="{{route('frontAllBuyerOrders')}}">{{__('lang.manage_orders_menu')}} </a></li>

              <li style="width:100%"><a href="{{route('frontAllServiceRequest')}}">{{ __('users.my_booking_title')}}</a></li>

              <li style="width:100%"><a href="{{route('manageFrontProducts')}}">{{ __('users.buyer_product_list_title')}}</a></li>

              <!-- <li><a href="{{route('frontProductAttributes')}}">{{ __('lang.manage_attributes_menu')}}</a></li> -->
             
              <li style="width:100%"><a href="{{route('frontChangePassword')}}">{{ __('lang.change_password_menu')}}</a></li>
              <li style="width:100%"><a href="{{route('frontLogout')}}">{{ __('lang.logout_label')}}</a></li>
            </ul>

          <!--   @else
            <h3 class="de_col"><a  href="{{route('frontLogin')}}"  title="{{ __('users.login_label')}}"> {{ __('users.login_label')}} <i class="fas fa-user-check de_col"></i></a></h3>
            @endif -->

          </div>
@else

<div class="cart_details">
     @php
        $getTotalOrders = getNewOrders(Auth::guard('user')->id());
        $getTotalBookings = getNewBookings(Auth::guard('user')->id());
        $totalNotifications = $getTotalOrders + $getTotalBookings;

    @endphp
                 <a class="top_icon_css"  href="/profile">

                <img class="m-7" src="{{url('/')}}/assets/img/imgpsh_fullsize.png" height="30" />
                  </a>
          
                  @if($totalNotifications > 0)
                  <div class="cart_count notification_count">
                     
                    <span class="notification_count dropdown-toggle"  type="button" data-toggle="dropdown" id="notification_count" style="cursor: pointer">{{$totalNotifications}}</span>
        <ul class="dropdown-menu">
    <li style="width:100%"><a href="{{route('frontAllbookingRequest')}}">{{ __('users.my_booking_title')}} <p class='badge badge-pill debg_color' id="allSellerBookings">{{ $getTotalBookings}}</p></a></li>

    <li  style="width:100%"><a href="{{route('frontAllOrders')}}">{{__('lang.manage_orders_menu')}} <p class='badge badge-pill debg_color' id="allSellerOrders">{{ $getTotalOrders}}</p></a></li>
  </ul>
                  </div>
        
                  @endif
              </div>
<!--   <a href="/profile"   type="button" ><h3 class="de_col"><img src="{{url('/')}}/assets/img/imgpsh_fullsize.png" height="30" /></h3></a>  -->

@endif              
@else

<h3 class="de_col"><a  href="{{route('frontLogin')}}"  title="{{ __('users.login_label')}}"><img src="{{url('/')}}/assets/img/imgpsh_fullsize.png"height="28"/></a></h3>

@endif

</div>
</li>
                    <li>
                    <div class="cart_details">

                   <!--  <a class="top_icon_css" @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1) href="{{route('frontShowWishlist')}}" @elseif(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==2) onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @else onclick="showErrorMessage('{{trans('errors.login_buyer_required')}}','{{ route('frontLogin') }}');" @endif> -->
                     <a class="top_icon_css" @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1) href="{{route('frontShowWishlist')}}" @elseif(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==2) href="{{ route('frontLogin')}}" @else href="{{ route('frontLogin') }}" @endif>

                    <img class="m-7 mt-2" src="{{url('/')}}/assets/img/imgpsh_fullsize_wishlist.png"height="28" />
                      </a>
                      @php
                        $productWishlistCnt = getWishlistProducts(Auth::guard('user')->id());
                      @endphp
                      @if($productWishlistCnt > 0)
                      <div class="cart_count count_wishlist">
                        <span class="wishlist_count">{{$productWishlistCnt}}</span>
                      </div>
                      @endif
                  </div>
                    </li>

                    <li>
                    <div class="cart_details"  style="padding-left:0px;padding-right:0">
                    <a class="top_icon_css" @if(Auth::guard('user')->id() && Auth::guard('user')->getUser()->role_id==1) href="{{route('frontShowCart')}}" @else href="{{ route('frontLogin') }}" @endif>

                    <img class="mt-2" src="{{url('/')}}/assets/img/imgpsh_fullsize_cart.png" height="28" />

                    </a>

                    @php
                      $productCnt = getOrderProducts(Auth::guard('user')->id());
                    @endphp
   
                    <div class="cart_count bag_cart_count" @if($productCnt <= 0) style="display: none;" @endif>
                      <span class="add_to_cart_count">{{$productCnt}}</span>
                    </div>
      
                  </div>
                    </li>
                    
                  
                  </ul>
                

                  

</div>
                <!-- <button type="button" class=" btn buy_now_btn debg_color" onclick="location.href='{{route('AllproductListing')}}';">{{ __('lang.buy_now_btn')}}</button> -->
              </form>
            </div>
          </div>
        </div>
      </div>
</div>
      </div>
      </div>
      <?php 
    $Categories = getCategorySubcategoryList();
    $category_slug    = '';
    $subcategory_slug = '';
    $is_seller = '';
    if(!empty(Auth::guard('user')->id())){
      if(Auth::guard('user')->getUser()->role_id==2){
       $seller_id = Auth::guard('user')->id();
       $link_seller_name = get_link_seller_name($seller_id);
       $is_seller=1;
     }
    }
   

  ?>

@if(!empty($Categories))
<div class="clearfix"></div>

<nav class="navbar sticky-top navbar-expand-lg bg-dark product_view mo_view_menu">
    <div class="container-fluid">    
      <div class="row">
        <div >
        <div class="product_view">
      <div class="">
      <button class="navbar-toggler toggle_btn navbar-fixed-top" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
   <i class="fas fa-bars"></i>
  </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="nav mainMenu pull-left"> 
    <!-- <span>( @php count($Categories) @endphp)</span> -->
        @php $i=0; @endphp
        @foreach($Categories as $CategoryId=>$Category)
        <?php $i++;
       /* $cls='';
        if($category_slug==$Category['category_slug'])
        $cls  =       'activemainmenu';
        else if($category_slug=='' && $i==1) $cls  =       'activemainmenu';
        <a href="{{url('/')}}/products/{{ $Category['category_slug'] }}" class="<?php if(in_array($Category['category_slug'], $current_path)) echo'activemainmenu';?>">{{$Category['category_name']}}</a>
        */
        ?>
                @if(!empty($Categories[$CategoryId]['subcategory']))
				<?php $current_path = explode("/",request()->path()); ?>
                <li class="main-menu-item-<?php echo $i; ?> "><a href="{{url('/')}}/products/{{ $Category['category_slug'] }}" class="">{{$Category['category_name']}}</a>
                  <?php /*
                <ul id="menu-<?php echo $i; ?>-sub-item" class="submenu_list" >
                  @foreach($Categories[$CategoryId]['subcategory'] as $subcategory)
                  <li class="<?php if(in_array($subcategory['subcategory_slug'], $current_path)) echo'activesubmenu';?>" ><a @if(empty($is_seller)) href="{{url('/')}}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @else href="{{url('/')}}/seller/{{ $link_seller_name }}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}" @endif>{{ $subcategory['subcategory_name'] }}</a></li>
                  @endforeach
                </ul> */?>
                 <ul id="menu-<?php echo $i; ?>-sub-item" class="submenu_list" >
                  @foreach($Categories[$CategoryId]['subcategory'] as $subcategory)
                  <li class="<?php if(in_array($subcategory['subcategory_slug'], $current_path)) echo'activesubmenu';?>" ><a  href="{{url('/')}}/products/{{ $Category['category_slug'] }}/{{ $subcategory['subcategory_slug'] }}">{{ $subcategory['subcategory_name'] }}</a></li>
                  @endforeach
                </ul> 
                </li>
                @endif
            @endforeach

        </ul>
        <a href="{{url('/')}}/annonser" title="{{ __('users.go_to_announse_page')}}" class="btn btn-black btn-sm  login_btn go_to_tijara_ads_btn_top pull-right">{{ __('users.go_to_announse_page')}}</a>

      </div>

      </div>
    </div>
  </div>
</div>
  <!-- <div class="col-md-2">
    <a href="{{url('/')}}/annonser" title="{{ __('users.go_to_announse_page')}}" class="btn btn-black btn-sm  login_btn go_to_tijara_ads_btn">{{ __('users.go_to_announse_page')}}</a>
  </div> -->
</div>
  </nav>
  <hr class="categoryGrayLine">
  <?php /* @if(Request::segment(1) =='products' || Request::segment(1) =='services' || Request::segment(1) =='annonser' || Request::segment(1) =='product')
  <hr class="categoryGrayLine">
  @endif
*/?>
@endif 
    </nav>
  </header>

  <script type="text/javascript">
     function saveValue(e){
            var id = e.id;  // get the sender's id to save it . 
            var val = e.value; // get the value. 
            localStorage.setItem(id, val);// Every time user writing something, the localStorage's value will override . 
        }
  </script>

 
