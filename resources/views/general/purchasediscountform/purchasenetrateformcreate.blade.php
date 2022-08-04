@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Purchase Net Rate Form')
@section('pagecontent')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .modal {z-index: 9999;} 
</style>
<div class="page formbuilder">
  <div class="page-header">
    <h1 class="page-title">Add Purchase Net Rate Form</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('purchase_net_rate_list') }}">Net Purchase Rate Form : List</a></li>
      <li class="breadcrumb-item">Add Purchase Net Rate Form</li>
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
            <h2 class="form-box form-box-heading">Purchase Net Rate Form</h2>
            <div class="custom-form-box">
              <form class="custom-form" action="{{url('admin/add-purchase-netrate-form')}}" id="task_form" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                @csrf
                <div class="form-box rows  line_repeat_1">
                  <div class="form-box col2">
                    <label for="department">Option<span class="required" aria-required="true">*</span></label>
                    <div class="inline-radio-box inline-radio single-label">
                      <label>
                        <input type="radio" value="1" checked class=" block_unblock " id="" required="" name="option" aria-required="true"> Product Wise</label>
                      <label>
                        <input type="radio" value="2" class=" block_unblock " id="" required="" name="option" aria-required="true">Party Product Wise</label>
                      <label id="block_unblock-error" class="error" for="block_unblock"></label>
                    </div>
                  </div>
                  <div class="form-box col2 customer" style="display:none;">
                    <label for="customer">Vendor<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="vendor_name" name="vendor_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2 customer">
                    <label for="customer">Customer<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                  <div class="form-box col2">
                    <label for="customer">Product<span class="required" aria-required="true">*</span></label>
                    <input type="hidden" name="product_id" id="product_id"  class="required form-control">
                    <input type="text" name="product" id="product"  class="required form-control" readonly>
                    <a data-toggle="modal" data-target="#myModal" href="javascript:void(0);">Search</a>
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Till Date<span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control filter-date" autocomplete="off" required name="date_rate" placeholder="Till Date">
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Net Rate<span class="required" aria-required="true">*</span></label>
                    <input type="number" class="form-control" min="0" autocomplete="off" required name="net_rate" placeholder="Net Rate">
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Minimum Order Quantity<span class="required" aria-required="true">*</span></label>
                    <input type="number" class="form-control" min="0" autocomplete="off" required name="min_order_qty" placeholder="Minimum Order Quantity">
                  </div>
                  <div class="form-box col2">
                    <label for="contact_name">Contact Name<span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control" autocomplete="off" required name="contact_name" placeholder="Contact Name">
                  </div>
                  <div class="form-box col2">
                    <label for="email">Email<span class="required" aria-required="true">*</span></label>
                    <input type="email" class="form-control" autocomplete="off" required name="email" placeholder="Email">
                  </div>
                  <div class="form-box col2">
                    <label for="phone">Phone<span class="required" aria-required="true">*</span></label>
                    <input type="number" class="form-control" maxlength="12" min="10" autocomplete="off" required name="phone" placeholder="Phone">
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
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Search Items</h4>
        </div>
        <div class="modal-body search-data-items">
          <form method="POST" id="reloadform" action="" class="custom-inline-form item-form-apply">
            @csrf
            <div class="new-form-box">
            <div class="form-wrap small-box"> 
            <label>Vendor SKU</label>
            <input class="form-control" type="text" id="vendor_sku" name="vendor_sku" value="{{@$vendor_sku}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap"> 
            <label>Name</label>
            <input class="form-control" type="text" id="name" name="name" value="{{@$name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Synonyms</label>
            <input class="form-control" type="text" id="synonyms" name="synonyms" value="{{@$synonyms}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Grade</label>
            <input class="form-control" type="text" id="grade" name="grade" value="{{@$grade}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Brand</label>
            <input class="form-control" type="text" id="brand" name="brand" value="{{@$brand}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Packing</label>
            <input class="form-control" type="text" id="packing_name" name="packing_name" value="{{@$packing_name}}" autocomplete="off" placeholder=""></div>
            <div class="form-wrap small-box"> 
            <label>HSN Code</label>
            <input class="form-control" type="text" id="hsn_code" name="hsn_code" value="{{@$hsn_code}}" autocomplete="off" placeholder=""></div>
            </div>
            <div class="new-form-box new-form-box-radio-box">
            <div class="new-form-radio-box">
            <input type="radio" id="html" name="is_verified" @if($is_verified=='TRUE') checked @endif value="TRUE"><label for="html">Verified</label>
            </div>
            <div class="new-form-radio-box">
            <input type="radio" id="css" @if($is_verified=='FALSE') checked @endif name="is_verified" value="FALSE"><label for="css">Unverified</label>
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic order_search" type="button" name="order_search">Search</button>
            </div>
            
            <div class="form-wrap form-wrap-submit"> <a id="reload" href="javascript:void(0);" class="btn btn-primary reload waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
            
            </div>
            
          </form>
          <div id="data-table-result">
          <table class="table table-bordered t1">
              <thead>
                <th class="bg-info">Apply</th>
                <th class="bg-info">Vendor SKU</th>
                <th class="bg-info">Group Name</th>
                <th class="bg-info">HSN Code</th>
                <th class="bg-info">Grade</th>
                <th class="bg-info">Brand</th>
                <th class="bg-info">Packing Name</th>
                <th class="bg-info">List Price</th>
                <th class="bg-info">MRP</th>
                <!--<th class="bg-info">Net Rate</th>-->
                <th class="bg-info">Stock</th>
                  </thead>
              <tbody id="results">              
              @if(!empty($datalist))
              @foreach($datalist as $user)
              @php
              $user = (array)$user;
              @endphp
              <tr>
                <td style="text-align:center;" class="white-space-normal"><input id="{{$user['id']}}" value="{{$user['vendor_sku']}}-{{$user['name']}}-{{$user['hsn_code']}}-{{$user['grade']}}-{{$user['brand']}}-{{$user['packing_name']}}-{{$user['list_price']}}-{{$user['mrp']}}-{{$user['net_rate']}}-{{$user['stock']}}" name="apply-radio" type="radio"></td>
                <td style="text-align:center;" class="white-space-normal">{{$user['vendor_sku']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['name']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['hsn_code']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['grade']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['brand']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['packing_name']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['list_price']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['mrp']}}</td>
                <!--<td style="text-align:center;" class="white-space-normal">{{$user['net_rate']}}</td>-->
                <td style="text-align:center;" class="white-space-normal">{{$user['stock']}}</td>
               </tr>
             
              @endforeach
              @else
              <tr>
                <td colspan="8">No record found.</td>
              </tr>
              @endif
                </tbody>
            </table>
            </div>
            <div class="container" style="margin-top:10px;">
	
	<div class="box">
	    <ul id="example-2" class="pagination"></ul>
	    <div class="show"></div>
	</div>
	
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-apply" data-dismiss="modal">Apply</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('custom_validation_script') 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
<script>
	$(function () { 
		var itemArr = '<?php print_r(getAllItems()); ?>';
		itemArr = JSON.parse(itemArr);
		var itemData = $.map(itemArr, function (value, key) { return { value: value, data: key }; });
		var itemHtml='<option value="">--Select--</option>';
		for(var i=0; i<itemData.length; i++){
			itemHtml+='<option value="'+itemData[i].data+'">'+itemData[i].value+'</option>';
		}
		$('#product').html(itemHtml);
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
    $(document).ready(function(){
        $("input[type='radio']").click(function(){
            var radioValue = $("input[name='option']:checked").val();
            if(radioValue==2){
                $('.customer').show();
            }else{
                $('.customer').hide();
            }
        });
    });
</script> 
<script>
    $(document).ready(function(){
        $(".btn-apply").click(function(){
            var radioValue = $("input[name='apply-radio']:checked").val();
           var id = $("input[name='apply-radio']:checked").attr('id');
           $('#product').val(radioValue);
           $('#product_id').val(id);
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
        
        $(document).on('click','.order_search',function(){
                $('#loader').show();
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                var formulario =  $(".item-form-apply");
                    $.ajax({
                    url: "{{ url('admin/net_rate_search') }}" ,
                    type: "POST",
                    data: formulario.serialize(),
                    success: function( response ) {
                    //alert(response); return false;

                    if(response!='')
                    {
                    $('#loader').hide();
                    var returnedData = JSON.parse(response);
                   $('#data-table-result').html(returnedData.html);
                    
                    //alert(returnedData.record_count);
                    $('#example-2').pagination({
                    total: returnedData.record_count, 
                    current: 1,
                    length: 10,
                    size: 2,
                    prev: 'Previous',
                    next: 'Next',
                    click: function(options, refresh, $target){
                    $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    var vendor_sku = $("#vendor_sku").val();
                    var name = $("#name").val();
                    var synonyms = $("#synonyms").val();
                    var grade = $("#grade").val();
                    var brand = $("#brand").val();
                    var packing_name = $("#packing_name").val();
                    var hsn_code = $("#hsn_code").val();
                    if($('input[name="is_verified"]').is(':checked'))
                    {
                    var is_verified = $('input[name="is_verified"]').val();
                    }else
                    {
                    var is_verified = '';
                    }
                    
                    $.ajax({
                    url: "{{ url('admin/net_rate_search') }}" ,
                    type: "POST",
                    data: {page:options.current,brand:brand,vendor_sku:vendor_sku,name:name,synonyms:synonyms,grade:grade,packing_name:packing_name,hsn_code:hsn_code,is_verified:is_verified},
                    success: function( response ) {
                    //alert(response); return false;
                    if(response!='')
                    {
                    var returnedData = JSON.parse(response);
                    $('#data-table-result').html(returnedData.html);  
                    }
                    }
                    }); 
                    }
                    });   
                   //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                   $('.btn-apply').show();
                    }else{
                   $('#data-table-result').html(''); 
                   //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                   $('#loader').hide();
                   $('.btn-apply').hide();
                    }
                    }
                    }); 
        });
    });
</script>
<script>
    $(function(){
        $(document).on('click','#reload',function(){
            //alert();
           $('#reloadform')[0].reset();
           $('#example-2').pagination({
    total: {{$itemlist_count}}, 
    current: 1,
    length: 10,
    size: 2,
    prev: 'Previous',
    next: 'Next',
    click: function(options, refresh, $target){
        $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
            url: "{{ url('admin/net_rate_search') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              var returnedData = JSON.parse(response);
              $('#data-table-result').html(returnedData.html);  
            }
            }
            }); 
    }
});
           $('.order_search').trigger('click');
        });
    });
</script>
<link rel="stylesheet" type="text/css" href="{{ url('css/pagination.min.css')}}">
  
<script src="{{ url('js/pagination.js')}}"></script>
<script>
    $('#example-2').pagination({
    total: {{$itemlist_count}}, 
    current: 1,
    length: 10,
    size: 2,
    prev: 'Previous',
    next: 'Next',
    click: function(options, refresh, $target){
        $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
            url: "{{ url('admin/net_rate_search') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              var returnedData = JSON.parse(response);
              $('#data-table-result').html(returnedData.html);  
            }
            }
            }); 
    }
});
</script>
@endsection