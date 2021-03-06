@extends('layouts.adminapp')
@section('admin_content')  
      <!-- content wrpper -->
			<div class="content_wrapper">
				<!--middle content wrapper-->
				<div class="middle_content_wrapper">
					
				<section class="page_area">
					<div class="panel">
						<div class="panel_header">
							<div class="row">
								<div class="col-md-6">
									<div class="panel_title"><span class="panel_icon"><i class="fas fa-plus-square"></i></span><span>Add Affiliate Product</span></div>
								</div>
								<div class="col-md-6 text-right">
									<button type="button"  style="margin: 5px;" class="btn btn-success" ><i class="fas fa-award"></i> <a href="{{route('admin.product.all')}}" style="color: #fff;">All Affiliate Product</a></button>
								</div>
							</div>
							
						</div>
						<div class="panel_body">
							<form action="{{route('admin.product.insert')}}" method="POST" id="choice_form" enctype="multipart/form-data">
								@csrf
								<div class="form-group row">
									<input type="hidden" name="product_type" value="4">
								    <label for="" class="col-sm-3 col-form-label text-right">Product Name:</label>
								    <div class="col-sm-6">
								      <input type="text" name="product_name" class="form-control" onchange="update_sku()">
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product Sku:</label>
								    <div class="col-sm-6">
								      <input type="text" class="form-control" name="product_sku">
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product Quantity:</label>
								    <div class="col-sm-6">
								      <input type="number" class="form-control" name="product_qty">
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product 	Affiliate Link:</label>
								    <div class="col-sm-6">
								      <input type="text" class="form-control" name="affiliate_link">
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product Category</label>
								    <div class="col-sm-6">
								    	@php
											$category=App\Category::where('is_deleted',0)->where('cate_status',1)->get();
								    	@endphp
								      <select class="form-control" name="cate_id" id="cate_id">
								      	<option >Select</option>
								      	@foreach($category as $cate)
								      	<option  value="{{$cate->id}}">{{$cate->cate_name}}</option>
								      	@endforeach
								      </select>
								    </div>
								  </div>
								   <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product SubCategory</label>
								    <div class="col-sm-6">
								      <select class="form-control" name="subcate_id" id="subcate_id">
								      	<option>Select</option>
								      </select>
								    </div>
								  </div>
								   <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product ReSubCategory</label>
								    <div class="col-sm-6">
								      <select class="form-control" name="resubcate_id" id="resubcate_id">
								      	<option>Select</option>
								      </select>
								    </div>
								  </div>
								   <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product Current Price:</label>
								    <div class="col-sm-6">
								      <input min="0" value="0" step="0.01" name="unit_price" class="form-control">
								    </div>
								  </div>
								 <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Select Upload Type:</label>
								    <div class="col-sm-6">
								     <select class="form-control" name="upload_type" id="upload_type">
								     	<option>Select</option>
								     	<option value="1">File</option>
								     	<option value="2">Link</option>
								     </select>
								    </div>
								 </div>

								 <div class="form-group row" id="selectfile" style="display: none">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Select file</label>
								    <div class="col-sm-6">
								     <input type="file" name="pdf">
								    </div>
								 </div>
								 <div class="form-group row" id="selectlink" style="display: none">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Link</label>
								    <div class="col-sm-6">
								     <input type="text" name="upload_link" class="form-control">
								    </div>
								 </div>
								 <div class="row">
		                          	<div class="col-md-3"></div>
		                          	<div class="col-md-8">
		                             	<label class="chech_container mb-4">
											<input type="checkbox"  id="allow_flash_deal"  name="allow_flash_deal" value="1">
											<span class="checkmark"></span>
											Flash Deal
										</label>
		                          	</div>
		                        </div>

		                         <div id="flash_deal_section" style="display: none;">
		                              <div  class="row">
		                                <div class="col-md-3"></div>
		                                <div class="col-md-6 row">
		                                   <div class="col-md-3">
		                                      	<label>Start Date:</label>
		                                        <input type="date" name="flash_deal_start_date" class="form-control">
		                                    </div>
		                                    <div class="col-md-3">
		                                      	<label>End Date:</label>
		                                        <input type="date" name="flash_deal_end_date" class="form-control">
		                                    </div>
		                                    <div class="col-md-3">
		                                      	<label>% / Amount</label>
		                                        <select class="form-control" name="flash_deal_type">
		                                        	<option>--Select--</option>
		                                        	<option value="1">Amount</option>
		                                        	<option value="2">%</option>
		                                        </select>
		                                    </div>
		                                    <div class="col-md-3">
		                                      	<label>Price</label>
		                                        <input type="number" name="flash_deal_price" class="form-control">
		                                    </div>
		                                </div>
		                             </div>
                          		</div>
                          		<div style="margin-top: 15px">
	                          		<div class="row">
	                          			<label for="" class="col-sm-3 col-form-label text-right">Product Description:</label>
									    <div class="col-sm-6">
									      <textarea name="product_description" id="editor1" rows="10" cols="80"> </textarea>
									    </div>
									</div>
                          	   </div>
                          	   <div style="margin: 15px 0px">
	                          		<div class="row">
	                          			<label for="" class="col-sm-3 col-form-label text-right">Product Buy and Return Policy:</label>
									    <div class="col-sm-6">
									      <textarea class="editor2" name="buy_and_return_policy" id="editor2" rows="10" cols="80"> </textarea>
									    </div>
									</div>
                          	   </div>
		                         <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Product Estimated Shipping Time</label>
								    <div class="col-sm-6">
								      <input type="text" class="form-control" name="shipping_time">
								    </div>
								  </div>

								  <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Meta Tag</label>
								    <div class="col-sm-6">
								      <input type="text" class="form-control" data-role="tagsinput" name="m_tag">
								    </div>
								  </div>
								  <div class="form-group row">
								    <label for="inputPassword" class="col-sm-3 col-form-label text-right">Meta Description</label>
								    <div class="col-sm-6">
								      <input type="text" class="form-control" name="meta_description">
								    </div>
								  </div>
								 
























								<div class="form-group row">
									<label for="" class="col-sm-3 col-form-label text-right">Main Image</label>
									 <div class="col-sm-6">
										<div id="photos" class="row"></div>
									</div>
								</div>

								<div class="form-group row">
								  <label for="" class="col-sm-3 col-form-label text-right">Thumbnail Image</label>
									<div class="col-sm-6">
									<div id="thumbnail_img" class="row">
										
									</div>
									</div>
								</div>
							
								<div class="form-group row">
							
									<div class="col-md-12 text-center">
										<button type="submit" class="btn btn-primary">Add Product</button>
									</div>
								</div>
							</form>	
						</div>	
					</div>
				</section>
			</div>
		</div>
<script type="text/javascript">

  $(document).ready(function() {
     $('select[name="cate_id"]').on('change', function(){
         var cate_id = $(this).val();
        
         if(cate_id) {
             $.ajax({
                 url: "{{  url('/get/subcategory/all/') }}/"+cate_id,
                 type:"GET",
                 dataType:"json",
                 success:function(data) {
                     
                        $('#subcate_id').empty();
                        $('#subcate_id').append(' <option value="0">--Select--</option>');
                        $.each(data,function(index,districtObj){
                         $('#subcate_id').append('<option value="' + districtObj.id + '">'+districtObj.subcate_name+'</option>');
                       });
                     } 
             });
         } else {
             alert('danger');
         }

     });
 });
</script>
<script type="text/javascript">

  $(document).ready(function() {
     $('select[name="subcate_id"]').on('change', function(){
         var subcate_id = $(this).val();
        //alert(subcate_id);
         if(subcate_id) {
             $.ajax({
                 url: "{{  url('/get/resubcategory/all/') }}/"+subcate_id,
                 type:"GET",
                 dataType:"json",
                 success:function(data) {
                     
                        $('#resubcate_id').empty();
                        $('#resubcate_id').append(' <option value="0">--Select--</option>');
                        $.each(data,function(index,districtObj){
                         $('#resubcate_id').append('<option value="' + districtObj.id + '">'+districtObj.resubcate_name+'</option>');
                       });
                     } 
             });
         } else {
             alert('danger');
         }

     });
 });
</script>
 <script>
 $(document).ready(function() {
	$("#allow_product_condition").click(function() {
		if ($(this).is(":checked")) {
			$("#product_condition").show();
		}
		else {
			$("#product_condition").hide();
			}
	});
	$("#allow_product_measurement").click(function() {
		if ($(this).is(":checked")) {
			$("#product_measurement").show();
		}
		else {
			$("#product_measurement").hide();
			}
	});
	$("#allow_flash_deal").click(function() {
		if ($(this).is(":checked")) {
			$("#flash_deal_section").show();
		}
		else {
			$("#flash_deal_section").hide();
			}
	});
});
</script>
<script>
	 $(document).ready(function() {
	 	$('select[name="upload_type"]').on('change', function(){
	 		var uptype = $(this).val();
			if(uptype==1){
				$("#selectfile").show();
				$("#selectlink").hide();
			}
			else if(uptype==2)
			{
				$("#selectfile").hide();
				$("#selectlink").show();
			}else{
				$("#selectfile").hide();
				$("#selectlink").hide();
			}
	});
	
});

</script>
@endsection