@extends('Front.layout.template')
@section('middlecontent')

<?php echo "djhjkhjkk";exit;?>
<section class=""> 
<div class="loader"></div>
<div class="row" style="margin-bottom:60px;">
      <div class="col-md-10 col-md-offset-1">
        <div id="payment-page">
          <div class="container container-fluid p_155">
            <div class="payment-container">
              <img src="data:image/png;base64, {{$QRCode}}" /><br>
              
            </div>
          </div>
        </div>
      </div>
    </div>
</section>        

@endsection
