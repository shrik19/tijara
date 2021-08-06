<div class="pull-left sidebar_menu">
                @if(Auth::guard('user')->id())
                <ul style="margin-top: 50px;">

                  <li><a href="{{route('frontUserProfile')}}">{{ __('users.profile_label')}}</a></li>
                  
                  
                  <li><a href="{{route('frontAllOrders')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.manage_orders_menu')}} @else {{ __('lang.txt_seller_order')}} @endif</a></li>

                  <li><a href="{{route('frontAllServiceRequest')}}">@if(Auth::guard('user')->getUser()->role_id==1) {{ __('lang.my_service_request')}} @else {{ __('lang.all_service_request')}} @endif</a></li>

                  @php
                      $isPackagesubcribed = checkPackageSubscribe(Auth::guard('user')->id());
                  @endphp

                  @if($isPackagesubcribed !=0)
                      <li><a href="{{route('manageFrontProducts')}}">{{ __('lang.manage_products_menu')}}</a></li>
                      <li><a href="{{route('frontProductAttributes')}}">{{ __('lang.manage_attributes_menu')}}</a></li>
                  @endif

                  
                  @if(Auth::guard('user')->getUser()->role_id==2)
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