@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Purchase Discount Form')
@section('pagecontent')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
	<div class="page-header">
        <h1 class="page-title">Add Purchase Discount Form</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('purchase_discount_net_rate_list') }}">Purchase Discount Form : List</a></li>
			<li class="breadcrumb-item">Add Purchase Discount Form</li>
        </ol>
	</div>
	
	<div class="page-content container-fluid">
		<div class="row justify-content-center">
			@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div><br />
			@endif
			<div class="col-md-12">
				<div class="card">					
					<div class="card-body_rename card-body">					
						<h2 class="form-box form-box-heading">Purchase Discount</h2>
						<div class="custom-form-box">						
						<form class="custom-form" action="{{url('admin/purchase_add_discount_form')}}" id="task_form" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                @csrf
                <div class="form-box rows  line_repeat_1">
				<div class="form-box col2">
                    <label for="customer">Brand<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="brand" name="brand" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2 customer">
                    <label for="customer">Vendor</label>
                    <select class="form-control" id="vendor_name" name="vendor_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Valid Till<span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control filter-date" autocomplete="off" required name="valid_till" placeholder="Valid Till">
                  </div>
				  <div class="form-box col2">
                    <label for="discription">Discount On<span class="required" aria-required="true">*</span></label>
                    <div class="inline-radio-box">
                    <div class="radio-box">
                    <input type="radio" id="list-price" checked="checked" value="1" name="discount_by" class="custom-control-input">
                    <label class="custom-control-label" for="list-price">List Price</label>
                    </div>
                    <div class="radio-box">
                    <input type="radio" id="mrp-price" value="2" name="discount_by" class="custom-control-input">
                    <label class="custom-control-label" for="mrp-price">MRP</label>
                    </div>
                    </div>
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Discount<span class="required" aria-required="true">*</span></label>
                    <input type="number" class="form-control" autocomplete="off" required name="discount" placeholder="Discount">
                  </div>
                </div>
                <div class="form-box submit-reset"></div>
                <div class="form-box col2">
                  <input type="submit" name="submit" class="form-control submit " id="" value="Submit">
                </div>
                <div class="form-box col2">
                  <input type="reset" name="reset" class="form-control reset " id="" value="Reset">
                </div>
              </form>
					</div>					
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>



@endsection
@section('custom_validation_script') 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
<script>
	$(function () { 
		var brandArr = '<?php print_r(getAllBrand()); ?>';
		brandArr = JSON.parse(brandArr);
		var brandData = $.map(brandArr, function (value, key) { return { value: value, data: key }; });
		var brandHtml='<option value="">--Select--</option>';
		for(var i=0; i<brandData.length; i++){
			brandHtml+='<option value="'+brandData[i].data+'">'+brandData[i].value+'</option>';
		}
		$('#brand').html(brandHtml);
	});
</script> 
<script>
	$(function () { 
		var custArr = '<?php print_r(getAllVendor()); ?>';
		custArr = JSON.parse(custArr);
		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
		var custHtml='<option value="">--Select--</option>';
		for(var i=0; i<custData.length; i++){
			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
		}
		$('#vendor_name').html(custHtml);
	});
</script> 

<script>
$(document).ready(function() {
    $("#task_form").validate({});
});
</script>
<link href="{{ url('public/css/jquery.datetimepicker.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ url('public/js/jquery.datetimepicker.full.js')}}"></script> 
<script>
	jQuery(document).ready(function () {
	jQuery('.filter-date').datetimepicker({
	format: 'd-m-Y',
	timepicker:false,
	autoclose: true,
	minView: 2
	});
	});
	</script>
<style>
    .ui-tooltip.ui-widget { display:none; }
</style>
@endsection