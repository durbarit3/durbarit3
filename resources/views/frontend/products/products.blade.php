@extends('layouts.websiteapp')
@section('main_content')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Main Container  -->
  <div class="breadcrumbs">
    <div class="container">
        <div class="title-breadcrumb">
            {{$category->cate_name}}
        </div>
        <ul class="breadcrumb-cate">
            <li><a href="{{url('/')}}"><i class="fa fa-home"></i></a></li>
            <li><a href="">{{$category->cate_name}}</a></li>
        </ul>
    </div>
</div>

<div class="container product-detail">
    <div class="row">
       @include('frontend.products.sidenav')

        <div id="content" class="col-md-9 col-sm-12 col-xs-12">
            <div class="module banners-effect-9 form-group">
                <div class="banners">
                    @php
                        $maincate_id=$category->id;
                        $newban_image=App\CategoryBanner::where('section',2)->where('category_id',$maincate_id)->orderBy('id','DESC')->limit(1)->first();
                    @endphp
                  @if($newban_image)
                    @php
                      $sitebanmain=$newban_image->siteban_id;
                      $main_image=App\SiteBanner::where('id',$sitebanmain)->first();
                    @endphp
                    @if($main_image)
                    <div><a href=""><img src="{{asset('public/uploads/sitebanner/'.$main_image->image)}}"></a></div>
                    @else
                      <div><a href="#"><img src="{{asset('public/frontend/')}}/image/catalog/demo/category/men-cat.jpg"></a></div>
                    @endif
                @else


                @endif

                </div>
            </div>
            <a href="javascript:void(0)" class="open-sidebar hidden-lg hidden-md"><i class="fa fa-bars"></i>Sidebar</a>

            <div class="products-category">
                <div class="form-group clearfix">
                    <h3 class="title-category ">{{$category->cate_name}}</h3>
                </div>
                <div class="products-category">
                    <div class="product-filter filters-panel">
                        <div class="row">
                            <div class="col-sm-2 view-mode hidden-sm hidden-xs">
                                <div class="list-view">
                                    <button class="btn btn-default grid active" data-view="grid" data-toggle="tooltip" data-original-title="Grid"><i class="fa fa-th"></i></button>
                                    <button class="btn btn-default list" data-view="list" data-toggle="tooltip" data-original-title="List"><i class="fa fa-th-list"></i></button>
                                </div>
                            </div>

                            <div class="short-by-show form-inline text-right col-md-10 col-sm-12">
                                <div class="form-group short-by">
                                    <label class="control-label" for="input-sort">Sort By:</label>
                                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                                        <option value="" selected="selected">Default</option>
                                        <option value="">Name (A - Z)</option>
                                        <option value="">Name (Z - A)</option>
                                        <option value="">Price (Low &gt; High)</option>
                                        <option value="">Price (High &gt; Low)</option>
                                        <option value="">Rating (Highest)</option>
                                        <option value="">Rating (Lowest)</option>
                                        <option value="">Model (A - Z)</option>
                                        <option value="">Model (Z - A)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="input-limit">Show:</label>
                                    <select id="input-limit" class="form-control" onchange="location = this.value;">
                                        <option value="" selected="selected">12</option>
                                        <option value="">25</option>
                                        <option value="">50</option>
                                        <option value="">75</option>
                                        <option value="">100</option>
                                    </select>
                                </div>
                                <div class="form-group product-compare"><a id="compare-total" class="btn btn-default">Product Compare (0)</a></div>
                            </div>

                        </div>
                    </div>

                    <div class="products-list grid row number-col-3 so-filter-gird search_main_top" id="search_main_top">
                        <!-- category product -->
                        @php
                        $products=App\Product::where('is_deleted',0)->where('cate_id',$category->id)->orderBy('id','DESC')->limit(9)->get();
                        @endphp
                        @foreach($products as $product)
                        <div class="product-layout col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="product-item-container">
                                <div class="left-block">
                                    <div class="product-image-container  second_img  ">
                                        <a href="#" title="Lorem Ipsum dolor at vero eos et iusto odi  with Premium ">
                                            <img src="{{asset('public/uploads/products/thumbnail/'.$product->thumbnail_img)}}" alt="Lorem Ipsum dolor at vero eos et iusto odi  with Premium " title="Lorem Ipsum dolor at vero eos et iusto odi  with Premium " class="img-1 img-responsive">
                                            <img src="{{asset('public/uploads/products/thumbnail/'.$product->thumbnail_img)}}" alt="Lorem Ipsum dolor at vero eos et iusto odi  with Premium " title="Lorem Ipsum dolor at vero eos et iusto odi  with Premium " class="img-2 img-responsive">
                                        </a>
                                    </div>
                                    <!-- <div class="countdown_box">
                                        <div class="countdown_inner">
                                        </div>
                                    </div> -->
                                    <div class="box-label">
                                        <span class="label-product label-sale">
                                            Sale
                                        </span>
                                    </div>
                                </div>

                                <div class="right-block">
                                    <div class="caption">
                                        <h4><a href="product.html">{{Str::limit($product->product_name,40)}}</a></h4>
                                        <div class="total-price">
                                            <div class="price price-left">
                                                <span class="price-new">$98.00 </span> <span class="price-old">$122.00 </span>
                                            </div>
                                            <div class="price-sale price-right">
                                                <span class="discount">-20%
                                                    <strong>OFF</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="description item-desc hidden">
                                            <p>The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the.. </p>
                                        </div>
                                        <div class="list-block hidden">
                                            <button class="addToCart" type="button" data-toggle="tooltip" title="" onclick="cart.add('30 ', '1 ');" data-original-title="Add to Cart "><span>Add to Cart </span></button>
                                            <button class="wishlist btn-button" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('30 ');" data-original-title="Add to Wish List "><i class="fa fa-heart-o"></i></button>
                                            <button class="compare btn-button" type="button" data-toggle="tooltip" title="" onclick="compare.add('30 ');" data-original-title="Compare this Product "><i class="fa fa-retweet"></i></button>
                                        </div>
                                    </div>
                                    <div class="button-group">
                                        <a class="quickview iframe-link visible-lg btn-button" data-fancybox-type="iframe" href="quickview.html"> <i class="fa fa-search"></i> </a>
                                        <button class="wishlist btn-button" type="button" data-toggle="tooltip" title="" onclick="wishlist.add('105');" data-original-title="Add to Wish List"><i class="fa fa-heart-o"></i></button>
                                        <button class="compare btn-button" type="button" data-toggle="tooltip" title="" onclick="compare.add('105');" data-original-title="Compare this Product"><i class="fa fa-retweet"></i></button>
                                        <button class="addToCart btn-button" type="button" data-toggle="tooltip" title="" onclick="cart.add('105', '2');" data-original-title="Add to Cart"><span class="hidden">Add to Cart </span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- endcategory product -->
                    </div>



                    <div class="product-filter product-filter-bottom filters-panel">
                        <div class="col-sm-6 text-left">
                            <ul class="pagination">
                                <li class="active"><span>1</span>
                                </li>
                                <li><a href="#">2</a>
                                </li>
                                <li><a href="#">&gt;</a>
                                </li>
                                <li><a href="#">&gt;|</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-6 text-right">Showing 1 to 9 of 9 (1 Pages)</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- //Main Container -->

<script>
    $(document).ready(function(){
      $("#so-filter-heading").click(function(){
            alert('succes');
      });
});


</script>
<!-- <script>
   $('input[name="searchmain"]').on('keyup', function() {
        search();
    });

    function search(){

        $.ajax({
            type: "GET",
            url: '{{ route('products.search.cate')}}',
            dataType:'json',
            data: $('#search-item').serializeArray(),
            success: function(data) {
                 $('#search-main-top').html(data);

            }
        });

    }
</script> -->
<script>
    $(document).on('keyup','#searchmain',function(){
            var search_content=$(this).val();
             $.ajax({
                    type: "GET",
                    url: '{{ route('products.search.cate')}}',
                    data: {search_content},
                    dataType:'json',
                    success: function(data) {
                         $('#neew #search_main_top').html(data);

                    }
                });
    });

</script>

@endsection
