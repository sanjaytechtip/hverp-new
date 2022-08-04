@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Challan Listing')
@section('pagecontent')
<div class="page">
<div class="new-width-design new-new-width-design">
  <div class="new-design-inner">
    <div class="new-header-div-fot-btn search-design-data">
      <div class="page-header">
        <h1 class="page-title">Challan Listing</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item">Challan Listing</li>
        </ol>
      </div>
      <div class="payment-heading-box1 inlineform-wrap">
 
        <form method="POST" action="{{url('admin/challan-list')}}" class="custom-inline-form">
          @csrf
        <div class="form-box">
        <input class="form-control" type="text" name="challan_no" value="{{$request->challan_no}}" autocomplete="off" placeholder="Challan No">
        </div>
        <div class="form-box">
        <input class="form-control" type="text" name="city" value="{{$request->city}}" autocomplete="off" placeholder="City">
        </div>
        <div class="form-box">
        <input class="form-control" type="text" id="dispatch_date" name="dispatch_date" value="{{$request->dispatch_date}}" autocomplete="off" placeholder="Outward Date">
        </div>
        
        <div class="form-box">
        <select class="required form-control" id="customer_id" name="customer_id" data-plugin="select2" data-placeholder="Type Customer" data-minimum-input-length="1">
        <option value="">-- Select --</option>
        </select>
        </div>
        <div class="form-box">
        <input class="form-control" type="text" name="saleorder_ref_no" value="{{$request->saleorder_ref_no}}" autocomplete="off" placeholder="Ref No.">
        </div>
        <div class="form-box">
        <input class="form-control" type="text" id="created_date" name="created_date" value="{{$request->created_date}}" autocomplete="off" placeholder="Dated">
        </div>
        <div class="form-box">
        <input class="form-control" type="text" id="item_name" name="item_name" value="{{$request->item_name}}" autocomplete="off" placeholder="Item Name">
        </div>
        <div class="form-box">
        <select class="required form-control" id="user_id" name="user_id" data-plugin="select2" data-placeholder="Type User" data-minimum-input-length="1">
        <option value="">-- Select --</option>
        </select>
        </div>
        <div class="form-box form-box-submit"><button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button></div>
          <div class="form-box form-box-submit"><a href="{{route('challan_list')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a></div>  
   </form>
</div>
      </div>
     
      <div class="page-content">
        <div class="row"> @if (\Session::has('success'))
          <div class="col-md-12">
            <div class="alert alert-success">
              <p>{{ \Session::get('success') }}</p>
            </div>
          </div>
          @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model" id="invoice_btn"></a> </div>
        <div class="panel">
          <div class="panel-body">
            <table class="table table-bordered t1 custom-lineheight-table">
              <thead>
                <tr>
                  <th class="bg-info" id="ticket_number" style="color:#fff !important;">Challan No</th>
                  <th class="bg-info" id="city" style="color:#fff !important;">City</th>
                  <th class="bg-info" id="location" style="color:#fff !important;">Outward Date</th>
                  <th class="bg-info" id="no_of_items" style="color:#fff !important;">Customer/Order No.</th>
                  <th class="bg-info" id="department" style="color:#fff !important;">Ref No.</th>
                  <th class="bg-info" id="action" style="color:#fff !important;">Dated</th>
                  <th class="bg-info" id="item_name" style="color:#fff !important;">Item Name</th>
                  <th class="bg-info" id="user" style="color:#fff !important;">User</th>
                  <th class="bg-info" id="qty" style="color:#fff !important;">Qty</th>
                </tr>
              </thead>
              <tbody id="fb_table_body">
                @php
                $cus_data = getAllBuyerData();
                $cus_data_city = getAllBuyerDataCity();
                @endphp
                @if(!empty($challanlist))
                @php
                $i=1;
                @endphp
                @foreach($challanlist as $list)
                @php
                $items = getChallanDetails($list['challan_id']);
                @endphp
                <tr id="{{$list['challan_id']}}" cus="{{$items['challan_no']}}" class="row-challan">
                  <td>{{$items['challan_no']}}</td>
                  <td>@if($cus_data_city[$list['customer_id']]['city']!='NULL') {{ $cus_data_city[$list['customer_id']]['city'] }} @endif</td>
                  <td>{{date('d-m-Y H:i:s',strtotime($items['dispatch_date']))}}</td>
                  <td class="white-space-normal">{{ $cus_data[$list['customer_id']] }}/{{ $list['sale_order_no'] }}</td>
                  <td>{{ getSaleOrderDetails($list['sale_order_no'])['saleorder_ref_no'] }}</td>
                  <td>{{ date('d-m-Y H:i:s',strtotime(getSaleOrderDetails($list['sale_order_no'])['created_date'])) }}</td>
                  <td class="white-space-normal">{{ getAllitemsById($list['item_id']) }} </td>
                  <td>{{ getUserNameByID($list['user_id']) }}</td>
                  <td>{{ $list['dispatch_qty'] }}</td>
                </tr>
                @php
                $i++;
                @endphp
                @endforeach
                @else
                <tr><td colspan="7">No record found.</td></tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<button type="button" style="display:none;" class="btn btn-info my-challan" data-toggle="modal" data-target="#myModal-challan">Open Modal</button>
  <div class="modal fade" id="myModal-challan" role="dialog">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"><span id="item-name-details"></span></h4>
                    </div>
                    <div class="modal-body search-data-items">
                    <form method="post" id="make_challan">
                    <div id="data-table-result">
                    <table class="table table-bordered" id="data_table1">
                    <thead>
                    <tr>
                    <th class="bg-info">Item Name</th>
                    <th class="bg-info">City</th>
                    <th class="bg-info">Store Name/Rack No/Stock Type</th>
                    <th class="bg-info">Batch No (Expiry Date)</th>
                    <th class="bg-info">Rate</th>
                    <th class="bg-info">Quantity</th>
                    <!--<th class="bg-info">Out</th>-->
                    </tr>
                    </thead>
                    <tbody id="results-pop">
                   
                    </tbody>
                    </table>
                    </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect waves-classic" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-info btn-apply waves-effect waves-classic" id="1">Save</button>-->
                    </div>
                    </div>
                    </div>
                    </div>      

@endsection

@section('custom_validation_script')

<script>
    $(function(){
       $(document).on('click','.btn-email-send',function(){
            var id              = $(this).attr('id'); 
            var email_from      = $('#email_from-'+id).val();
            var email_to        = $('#email_to-'+id).val();
            var email_subject   = $('#email_subject-'+id).val();
            var email_body      = $('#email_body-'+id).val();
            var pattern         = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            $('#id').prop('disabled',true);
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            if(id!='')
            {
            if(email_from=='')
            {
            alert('From Email id is required');
            $('#email_from-'+id).focus();
            return false;
            }
            else if(email_from !='' && (!pattern.test(email_from)))
            {
            alert('Please valid from Email id is required');
            $('#email_from-'+id).focus();
            return false;    
            }
            else if(email_to=='')
            {
            alert('To Email id is required');
            $('#email_to-'+id).focus();
            return false;
            }
            else if(email_to !='' && (!pattern.test(email_to)))
            {
            alert('Please valid to Email id is required');
            $('#email_to-'+id).focus();
            return false;
            }
            else if(email_subject=='')
            {
            alert('Subject is required');
            $('#email_subject-'+id).focus();
            return false;
            }
            else if(email_body=='')
            {
            alert('Message body is required');
            $('#email_body-'+id).focus();
            return false;
            }
            else
            {
                $.ajax({
                url: "{{ url('admin/order_to_dispatch/email_send') }}" ,
                type: "POST",
                data: {id:id,email_from:email_from,email_to:email_to,email_subject:email_subject,email_body:email_body},
                success: function( response ) {
                //alert(response); return false;
                if(response!='')
                {
                alert('Mail was Sent successfully!');
                $('#email-date-'+id).html(response);
                $('.close').trigger('click');
                $('#id').prop('disabled',false);
                }
                }
                });
            
            }
            }
       }); 
    });
</script>
<script>
    $(function(){
       $(document).on('click','.make-challan-pop',function(){
           $('#loader').show();
          var id = $(this).attr('id');
          var customer_name = $(this).attr('customer_name');
          $('#customer-name-details').html(customer_name);
          if(id!='')
          {
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });   
            
                    $.ajax({
                    url: "{{ url('admin/order_to_dispatch/item_display') }}" ,
                    type: "POST",
                    data: {id:id},
                    success: function( response ) {
                    //alert(response); return false;
                    if(response!='')
                    {
                    $('#results-pop').html(response);
                    $('#loader').hide();
                    }
                    }
                    });
          }
       }); 
    });
</script>
<script>	
$(document).ready(function() {		
$('.template_body').summernote({		  
height:300,		
});	
});
</script>
<script>
	$(function () { 
		var custArr = '<?php print_r(getAllBuyer()); ?>';
		custArr = JSON.parse(custArr);
		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
		var custHtml='<option value="">--Select--</option>';
		for(var i=0; i<custData.length; i++){
			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
		}
		$('#customer_id').html(custHtml);
		$('#customer_id').val('<?php echo $request->customer_id; ?>');
	});
</script> 
<script>
	$(function () { 
		var custArr = '<?php print_r(getAllusers()); ?>';
		custArr = JSON.parse(custArr);
		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
		var custHtml='<option value="">--Select--</option>';
		for(var i=0; i<custData.length; i++){
			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
		}
		$('#user_id').html(custHtml);
		$('#user_id').val('<?php echo $request->user_id; ?>');
	});
</script> 
<style>
.email-popup .row .form-wrap label { display: block; text-align: left; }

.email-popup .note-editable{ text-align:left;}
    
</style>
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script> 
<script>

	jQuery(document).ready(function () {

	jQuery('#dispatch_date,#created_date').datetimepicker({

	timepicker:false,

	format: 'd-m-Y',

	autoclose: true, 

	minView: 2

	});

	});

	</script> 
	<script>
	    $(function(){
	       $(document).on('click','.row-challan',function(){
	          var id = $(this).attr('id');
	          var item_name = $(this).attr('cus');
	          var qty = $(this).attr('qty');
	          //alert(id);
	          $('#item-name-details').html(item_name);
	          $('#item-qty').html(qty);
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
	          if(id!='' && item_name!='' && qty!='')
	          {
                $.ajax({
                url: "{{ url('admin/ajax_item_details_challan') }}" ,
                type: "POST",
                data: {id:id,item_name:item_name,qty:qty},
                success: function( response ) {
                //alert(response); return false;
                if(response!='')
                {
                $('#results-pop').html(response);
                //$('#loader').hide();
                }
                }
                });   
	          }
	          
	          $('.my-challan').trigger('click');
	       }); 
	    });
	</script>
	
@endsection