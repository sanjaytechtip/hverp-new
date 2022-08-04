@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Sale Discount Form')
@section('pagecontent')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
  <div class="page-header">
    <h1 class="page-title">Add Sale Discount Form</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('discountlist') }}">Sale Discount Form : List</a></li>
      <li class="breadcrumb-item">Add Sale Discount Form</li>
    </ol>
  </div>
  <div class="page-content container-fluid">
    <div class="row justify-content-center"> @if (\Session::has('success'))
      <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
      </div>
      <br />
      @endif
      <div class="col-md-12">
        <div class="card">
          <div class="card-body_rename card-body">
            <h2 class="form-box form-box-heading">Sale Discount Form</h2>
            <div class="custom-form-box">
              <form class="custom-form" onSubmit="return checkDiscountPurchase();" action="{{url('admin/add_discount_form')}}" id="task_form" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                @csrf
                <div class="form-box rows  line_repeat_1">
                  <div class="form-box col2">
                    <label for="department">Option<span class="required" aria-required="true">*</span></label>
                    <div class="inline-radio-box inline-radio single-label">
                      <label>
                        <input type="radio" value="1" checked class=" block_unblock " id="" required="" name="option" aria-required="true">
                        Brand Wise</label>
                      <label>
                        <input type="radio" value="2" class=" block_unblock " id="" required="" name="option" aria-required="true">
                        Customer Brand Wise</label>
                      <label>
                        <input type="radio" value="3" class=" block_unblock " id="" required="" name="option" aria-required="true">
                        Group of Company</label>
                      <label id="block_unblock-error" class="error" for="block_unblock"></label>
                    </div>
                  </div>
                  <div class="form-box col2 group-customer" style="display:none;">
                    <label for="customer">Group Name<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="group_name" name="group_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2 customer" style="display:none;">
                    <label for="customer">Customer<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2">
                    <label for="customer">Brand<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="brand" name="brand" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Valid Till<span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control filter-date valid_till" autocomplete="off" required name="valid_till" placeholder="Valid Till">
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
                    <label for="discription" class="custom-dis-box"><span>Discount<span class="required" aria-required="true">*</span></span><span id="purchase-discount"></span></label>
                    <input type="number" class="form-control discount" autocomplete="off" required name="discount" placeholder="Discount">
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
<input type="hidden" id="pur_dis">
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
		var custArr = '<?php print_r(getAllCustomer()); ?>';
		custArr = JSON.parse(custArr);
		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
		var custHtml='<option value="">--Select--</option>';
		for(var i=0; i<custData.length; i++){
			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
		}
		$('#customer_name').html(custHtml);
	});
</script> 
<script>
	$(function () { 
		var gropArr = '<?php print_r(getAllGroup()); ?>';
		gropArr = JSON.parse(gropArr);
		var groupData = $.map(gropArr, function (value, key) { return { value: value, data: key }; });
		var groupHtml='<option value="">--Select--</option>';
		for(var i=0; i<groupData.length; i++){
			groupHtml+='<option value="'+groupData[i].data+'">'+groupData[i].value+'</option>';
		}
		$('#group_name').html(groupHtml);
	});
</script> 
<script>
    $(document).ready(function(){
        $("input[type='radio']").click(function(){
            var radioValue = $("input[name='option']:checked").val();
            if(radioValue==2){
                $('.customer').show();
                $('.group-customer').hide();
            }else if(radioValue==3){
                $('.customer').hide();
                $('.group-customer').show();
            }else{
                $('.customer').hide();
                $('.group-customer').hide();
            }
        });
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
<script>
  $(function(){
      $(document).on('change','#brand,.valid_till',function(){
             var brand_id           = $('#brand').val();
             var option_value       = $('input[name=option]:checked').val();
             var valid              = $('input[name=valid_till]').val();
             var discount_by        = $('input[name=discount_by]:checked').val();
             if(brand_id!='')
             {
              $.ajaxSetup({
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
              });
              
              $.ajax({
                    url: "{{ url('admin/get_purchase_discount_data') }}" ,
                    type: "POST",
                    data: {brand_id:brand_id,option_value:option_value,valid:valid,discount_by:discount_by},
                    success: function( response ) {
                    //console.log(response); return false;
                    if(response!='')
                    {
                    $('#purchase-discount').html('Purchase Discount(%) : '+ response);
                    $('#pur_dis').val(response);
                    }else{
                    $('#purchase-discount').html(''); 
                    $('#pur_dis').val('');
                    }
                    }
                    }); 
                   
               
             }
      });
  });
</script>
<script>
  function checkDiscountPurchase(){
    var pur_dis = parseInt($('#pur_dis').val());
    var discount = parseInt($('.discount').val());
    if(pur_dis!='' && discount!='' && discount>pur_dis)
    {
     alert("Discount should be apply less or equal to purchase discount.");
     return false;
    }
    else if(pur_dis=='' || isNaN(pur_dis) && (discount=='' || discount!='' || isNaN(discount)))
    {
    alert("No purchase discount found. Please choose anther one.");
     return false;
    }else{
     return true;
    }
  }
</script>
@endsection