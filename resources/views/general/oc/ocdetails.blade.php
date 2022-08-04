@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'OC Items Details')
@section('pagecontent')
<div class="page">
<div class="new-width-design new-new-width-design">
  <div class="new-design-inner">
    <div class="new-header-div-fot-btn search-design-data">
      <div class="page-header">
        <h1 class="page-title">OC Items Details</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('outwardchallanlist') }}">Outward Challan Listing</a></li>
          <li class="breadcrumb-item">OC Items Details</li>
        </ol>
      </div>
      <div class="payment-heading-box1 inlineform-wrap">
 
        
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
              <tbody id="fb_table_body">
            	<tr>
            	  <td><b>OC No: </b>{{$oc_data->oc_no}}</td>    
            	  <td><b>City: </b>{{$oc_data->h_city}}</td> 
            	  <td><b>Customer: </b>{{$oc_data->company_name}}</td> 
            	  <td><b>Date: </b>{{date('d-m-Y H:i:s',strtotime($oc_data->dispatch_date))}}</td> 
                <input type="hidden" name="customer_id" id="customer_id" value="{{$oc_data->customer_id}}">
            	</tr>
            	</tbody>
            </table>
            <table class="table table-bordered t1 custom-lineheight-table">
              <thead>
                <tr>
                  <th class="bg-info" id="sr" style="color:#fff !important;">SR</th>
                  <th class="bg-info" id="item_name" style="color:#fff !important;">Item SKU</th>
                  <th class="bg-info" id="batch_no" style="color:#fff !important;">Batch No.</th>
                  <th class="bg-info" id="store" style="color:#fff !important;">Store</th>
                  <th class="bg-info" id="room" style="color:#fff !important;">Room</th>
                  <th class="bg-info" id="room" style="color:#fff !important;">Rack No</th>
                  <th class="bg-info" id="Mfg_Date" style="color:#fff !important;">Mfg Date</th>
                  <th class="bg-info" id="Expire_Date" style="color:#fff !important;">Expire Date</th>
                  <th class="bg-info" id="qty" style="color:#fff !important;">Quantity</th>
                  <th class="bg-info" id="dispatch_qty" style="color:#fff !important;">Disp Planning Status</th>
                  <th class="bg-info" id="barcode" style="color:#fff !important;">Barcode</th>
                  <th class="bg-info" id="action" style="color:#fff !important;">Action</th>
                </tr>
              </thead>
              <tbody id="fb_table_body">
                <?php 
               //pr($oc_data_item);pr($oc_data);die;
                if(!empty($oc_data_item)){
                $i=1;
                $k=0;
                foreach($oc_data_item as $list){
                $list = (array) $list;?>
                <tr>
                  <td>{{$i}}</td>    
                  <td>{{$list['vendor_sku']}}</td>
                  <td>{{$list['batch_no']}}</td>
                  <td>{{$list['loc_cen']}}</td>
                  <td>{{$list['room_no']}}</td>
                  <td>{{$list['rack_name']}}</td>
                  <td>{{globalDateformatItem($list['mfg_date'])}}</td>
				          <td>{{globalDateformatItem($list['expiry_date'])}}</td>
                  <td>{{$list['dispatch_qty']}}</td>
                  <td>
                    @if($list['dispatch_planning_status']==1)
                    <i class="icon md-check" style="color:#4caf50; font-size:15px; font-weight:bold;" aria-hidden="true"></i>
                    @endif
                  </td>
                  <td>BARCODE Scan</td>
                  <td>
                    @if($list['status']==0 && $list['challan_id']=='')
                    <input type="checkbox" name="oc_id[]" value="{{$list['id']}}"/></a>
                    @php
                    $k++;
                    @endphp
                    @else
                    <i class="icon md-check" style="color:#4caf50; font-size:15px; font-weight:bold;" aria-hidden="true"></i>
                    @endif
                  <!--<a onclick="javascript:confirm('Are you sure to delete the data?');" href="{{url('admin/challan-delete/'.$list['_id'])}}">Delete</a>-->
                  </td>
                </tr>
                <?php
                $i++;
                }
                }else{?>
                <tr><td colspan="12">No record found.</td></tr>
                <?php }?>
              </tbody>
            </table>
          </div>
        </div>
        @if($k>=1)
        <div class="disp-plann-btn">
        <button class="btn btn-primary waves-effect waves-classic make-challan-invoice">Make Challan</button>
		    <button class="btn btn-primary waves-effect waves-classic make-challan-invoice">Invoice</button>
        </div>
        @endif
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

<style>
.email-popup .row .form-wrap label { display: block; text-align: left; }

.email-popup .note-editable{ text-align:left;}
    
</style>
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script> 

<script>
$(function(){
    $(document).on('click','.make-challan-invoice',function(event){
      event.preventDefault();
      var numberOfChecked = $('input:checkbox:checked').length;
      if(numberOfChecked>0)
      {
      var searchIDs = $("input:checkbox:checked").map(function(){
      return $(this).val();
      }).get();
      var customer_id = $('#customer_id').val();
      if(searchIDs!=''){
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });

          $.ajax({
          url: "{{ url('admin/order_to_dispatch/make_challan') }}" ,
          type: "POST",
          data: {id:searchIDs,customer_id:customer_id},
          success: function( response ) {
			  //alert(response); return false;
			  if(response!='')
			  {
			   window.location.reload(true);
			  }
          }
          });
        }

      }else{
      alert("Please check atleast one checkbox.");
      return false;
      }
    });
});
</script>
	
@endsection