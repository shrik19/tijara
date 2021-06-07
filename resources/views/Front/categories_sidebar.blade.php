<div class="category_list_box"  id="accordion">
        <h2 class="de_col">{{ __('lang.categories_head')}}</h2>
        <ul class="category_list">
        @php $i=0; @endphp
        @foreach($Categories as $CategoryId=>$Category) 
        @php $i++; @endphp 
                <li class="expandCollapseSubcategory <?php if($i==1) echo'activemaincategory';?>" data-toggle="collapse" data-parent="#accordion" href="#subcategories<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne"><a href="#">{{$Category['category_name']}}</a></li> 
                
                <ul id="subcategories<?php echo $i; ?>" class="subcategories_list  panel-collapse collapse <?php if($i==1) echo'in activesubcategories'; ?>"  role="tabpanel" aria-labelledby="headingOne" style="">
                @foreach($Categories[$CategoryId]['subcategory'] as $subcategory) 
                <li style="list-style: none;" ><a href="{{url('/')}}/products/{{$Category['category_slug']}}/{{$subcategory['subcategory_slug']}}">{{$subcategory['subcategory_name']}}</a></li> 
                @endforeach
                </ul>
            @endforeach       
        </ul>
</div>