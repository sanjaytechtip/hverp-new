@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Edit Discount Form')
@section('pagecontent')


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
	<div class="page-header">
        <h1 class="page-title">Edit Discount Form</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('discountlist') }}">Discount Form : List</a></li>
			<li class="breadcrumb-item">Edit Discount Form</li>
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
						<h2 class="form-box form-box-heading">Discount Form</h2>
						<div class="custom-form-box">						
						<form class="custom-form" action="{{url('admin/edit_discount/'.$data['_id'])}}" id="task_form" method="POST" enctype="multipart/form-data" novalidate="novalidate">
						@csrf
						<input type="hidden" value="{{$data['_id']}}" name="id">
						<div class="form-box rows  line_repeat_1">
						<div class="form-box col2">
						<label for="department">Department<span class="required" aria-required="true">*</span></label>
						@if($data['data_status']=='1')
						<select class="required form-control" readonly="readonly" name="department_name">
                        <option value="{{$data['department_name']}}">{{getDepartmentName($data['department_name'])}}</option>
                        </select>
						@else
                        <select class="required form-control" id="department_name" name="department_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                        </select>
                        @endif
                        </div>
						<div class="form-box col2 customer"><label for="customer">Customer<span class="required" aria-required="true">*</span></label>
						@if($data['data_status']=='1')
						<select class="required form-control" readonly="readonly" name="customer_name">
                        <option value="{{$data['customer_name']}}">{{getCustomerName($data['customer_name'])}}</option>
                        </select>
						@else
						<select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                        </select>
						@endif
						
                        </div>
                        <div class="form-box col2 location"><label for="location">Item Category<span class="required" aria-required="true">*</span></label>
						@if($data['data_status']=='1')
						<select class="required form-control" readonly="readonly" name="category_name">
                        <option value="{{$data['category_name']}}">{{getCategoryName($data['category_name'])}}</option>
                        </select>
                        @else
                        <select class="required form-control" id="category_name" name="category_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                        </select>
                        @endif
                        </div>
						<div class="form-box col2 user"><label for="user">User<span class="required" aria-required="true">*</span></label>
						<select class="required form-control" id="user_name" name="user_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">--Select--</option>
                        </select>
                        </div>
						<div class="form-box col2">
						<label for="discription">Subject<span class="required" aria-required="true">*</span></label>
						<input type="text" class="form-control" value="{{$data['subject_name']}}" autocomplete="off" required name="subject_name" placeholder="Subject">
						</div>
						<div class="form-box col2">
						<label for="discription">Date & Time<span class="required" aria-required="true">*</span></label>
						<input type="text" value="{{globalDateformatFMS($data['date_time'])}}" @if($data['data_status']=='1') readonly="" @endif class="form-control @if($data['data_status']=='') filter-date @endif" autocomplete="off" required name="date_time" placeholder="Date & Time">
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
		var deptArr = '<?php print_r(getAllDepart()); ?>';
		deptArr = JSON.parse(deptArr);
		var deptData = $.map(deptArr, function (value, key) { return { value: value, data: key }; });
		var deptHtml='<option value="">--Select--</option>';
		for(var i=0; i<deptData.length; i++){
			deptHtml+='<option value="'+deptData[i].data+'">'+deptData[i].value+'</option>';
		}
		$('#department_name').html(deptHtml);
		$('#department_name').val('<?php echo $data['department_name']; ?>');
	});
</script>

<script>
	$(function () { 
		var catArr = '<?php print_r(getAllCat()); ?>';
		catArr = JSON.parse(catArr);
		var catData = $.map(catArr, function (value, key) { return { value: value, data: key }; });
		var catHtml='<option value="">--Select--</option>';
		for(var i=0; i<catData.length; i++){
			catHtml+='<option value="'+catData[i].data+'">'+catData[i].value+'</option>';
		}
		$('#category_name').html(catHtml);
		$('#category_name').val('<?php echo $data['category_name']; ?>');
	});
</script>

<script>
	$(function () { 
		var custArr = '<?php print_r(getAllCustomer()); ?>';
		custArr = JSON.parse(custArr);
		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
		var custHtml='<option value="">--Select--</option>';
		for(var i=0; i<custData.length; i++){
			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
		}
		$('#customer_name').html(custHtml);
		$('#customer_name').val('<?php echo $data['customer_name']; ?>');
	});
</script>

<script>
	$(function () { 
		var userArr = '<?php print_r(getAllusers()); ?>';
		userArr = JSON.parse(userArr);
		var userData = $.map(userArr, function (value, key) { return { value: value, data: key }; });
		var userHtml='<option value="">--Select--</option>';
		for(var i=0; i<userData.length; i++){
			userHtml+='<option value="'+userData[i].data+'">'+userData[i].value+'</option>';
		}
		$('#user_name').html(userHtml);
		$('#user_name').val('<?php echo $data['user_name']; ?>');
	});
</script>

<script>
$(document).ready(function() {
    $("#inquiry_form").validate({});
});
</script>
<link href="{{ url('public/css/jquery.datetimepicker.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ url('public/js/jquery.datetimepicker.full.js')}}"></script>
	<script>
	jQuery(document).ready(function () {
	jQuery('.filter-date').datetimepicker({
	format: 'd-m-Y H:i:s',
	autoclose: true, 
	minView: 2
	});
	});
	</script>

<style>
    .ui-tooltip.ui-widget { display:none; }
</style>
@endsection