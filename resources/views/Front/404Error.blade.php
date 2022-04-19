@extends('Front.layout.template')
@section('middlecontent')
<style type="text/css">
  *{
    transition: all 0.6s;
}

html {
    height: 100%;
}

body{
    font-family: 'Lato', sans-serif;
    color: #888;
    margin: 0;
}

#main{
    display: table;
    width: 100%;
    text-align: center;
}

.fof{
    display: table-cell;
    vertical-align: middle;
}

.fof h1{
    font-size: 50px;
    display: inline-block;
    padding-right: 12px;
}

@keyframes type{
    from{box-shadow: inset -3px 0px 0px #888;}
    to{box-shadow: inset -3px 0px 0px transparent;}
}
</style>
<div class="container containerfluid p_155" style="min-height: 650px;">
  <!-- Example row of columns -->
  <div class="row register-success-page">
    <div class="">
      <div class="col-md-3"></div> 
      <div class="col-md-6">
          <div id="main">
            <div class="fof">
                <h1>Error 404</h1>
                <p>{{ __('lang.err_404_msg')}} <a href="{{route('AllproductListing')}}">Här</a></p>
            </div>
          </div>        
      </div>
    </div>
  </div>
</div> <!-- /container -->

@endsection