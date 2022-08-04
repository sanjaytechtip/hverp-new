@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Order to Dispatch')
@section('pagecontent')
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn order-to-dispatch-wrap">
        <div class="page-header">
          <h1 class="page-title">Order to Dispatch</h1>
          <ol class="breadcrumb"  style="white-space:nowrap;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Order to Dispatch</li>
          </ol>
        </div>
        <div class="inlineform-wrap">
          <form method="POST" action="{{url('admin/order-to-dispatch')}}" class="custom-inline-form">
            @csrf
            <div class="inlineform-wrap-row">
              <div class="form-box">
                <input class="form-control order-no-input" type="text" name="saleorder_no" value="{{$request->saleorder_no}}" autocomplete="off" placeholder="Order No">
              </div>
              <div class="form-box">
                <select class="required form-control customer-select" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Customer" data-minimum-input-length="1">
                  <option value="">-- Select --</option>
                </select>
              </div>
              <div class="form-box form-box-submit">
                <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
              </div>
              <div class="form-box form-box-submit"><a href="{{route('order_to_dispatch')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a></div>
            </div>
            <div class="inlineform-wrap-row">
              <div class="form-wrap form-wrap-submit"> <a class="btn btn-danger waves-effect waves-classic custom-red" style="float:right;" href="{{url('admin/saleordercreate')}}"> <i class="icon md-plus" aria-hidden="true"></i>From Quotation</a> </div>
              <div class="form-wrap form-wrap-submit"> <a href="{{url('admin/add-replacement-order')}}" class="btn btn-success waves-effect waves-classic custom-green" name="reload"><i class="icon md-plus" aria-hidden="true"></i>Add Replacement Order</a> </div>
              <div class="form-wrap form-wrap-submit"> <a href="{{url('admin/add-direct-order')}}" class="btn btn-primary waves-effect waves-classic custom-blue" name="reload"><i class="icon md-plus" aria-hidden="true"></i>Add Direct Order</a> </div>
            </div>
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
            <table class="table table-bordered t1 custom-lineheight-table show-hide-table">
              <thead>
                <tr>
                  <th class="bg-info" id="ticket_number" style="color:#fff !important; width:90px;">Order</th>
                  <th class="bg-info" id="location" style="color:#fff !important;">Customer</th>
                  <th class="bg-info" id="department" style="color:#fff !important; width:65px;">SO PDF</th>
                  <th class="bg-info" id="customer" style="color:#fff !important;width:95px">Email</th>
                  <th class="bg-info" id="date" style="color:#fff !important; width:95px">Date</th>
                  <th class="bg-info" id="GrandTotal" style="color:#fff !important; width:275px">T. Item / <span class="text-success">Dispatched</span> / <span class="text-danger">Pending</span> / <span class="text-primary">In Stock</span></th>
                  <th class="bg-info" id="create-challan" style="color:#fff !important;">Make OC</th>
                  <th class="bg-info" id="action" style="color:#fff !important;width:171px">Action</th>
                </tr>
              </thead>
              <tbody id="fb_table_body">
              
              @php
              $cus_data = getAllBuyerData();
              @endphp
              @if(!empty($saleorderlist))
              @foreach($saleorderlist as $list)
              @php
              $list = (array)$list;
              $items = getSalesOrderItems($list['id']);
              //pr($items);
              @endphp
                <tr @if($list['type']==1) class="cm-show-hide-row custom-table-danger" @elseif($list['type']==2) class="cm-show-hide-row custom-table-success" @else class="cm-show-hide-row custom-table-primary" @endif>
              
              
                <td>{{$list['saleorder_no']}} @if($list['single_order']=='yes') (SL) @endif</td>
                <td class="white-space-normal">{{ $cus_data[$list['customer_name']] }}</td>
                <td><a title="PDF Download" target="_blank" href="{{url('admin/saleorder-pdf/'.$list['id'])}}" class="btn btn-pure  icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-collection-pdf" aria-hidden="true"></i></a></td>
                <td class="white-space-normal"><a class="send_email" cus="{{$list['id']}}" id="{{$list['customer_name']}}" title="Send Email" href="javascript:void(0);" data-toggle="modal" data-target="#myModal-{{$list['id']}}"><i class="fas fa-envelope"></i></a> <br/>
                  <span id="email-date-{{$list['id']}}">@if($list['email_send_date']!='') {{date('d-m-Y H:i A',strtotime($list['email_send_date']))}} @endif</span></td>
                <td>{{ date('d-m-Y',strtotime($list['saleorder_date'])) }}</td>
                <td><span>{{getTotalQuantity($list['id'])}}</span> / <span class="text-success">{{ getDispatchOrderData($list['customer_name'],$list['id'],$list['saleorder_no']) }}</span> / <span class="text-danger">{{getTotalQuantity($list['id'])-getDispatchOrderData($list['customer_name'],$list['id'],$list['saleorder_no'])}}</span> / <span class="text-primary">{{getStockTotalQuantity($list['id'])}}</span></td>
                <td>
                    @php
                    $btn = makeChallanBtn(getTotalQuantity($list['id']),getDispatchOrderData($list['customer_name'],$list['id'],$list['saleorder_no']),getTotalQuantity($list['id'])-getDispatchOrderData($list['customer_name'],$list['id'],$list['saleorder_no']),getStockTotalQuantity($list['id']),$list['single_order']);
                    @endphp
                    @if($btn==1)
                    <!-- <a href="javascript:void(0);" data-toggle="modal" sale_order_id="{{$list['id']}}" type="@if($list['single_order']=='yes')1 @else 0 @endif" class="make-challan-pop" customer_name="{{ $cus_data[$list['customer_name']] }}" id="{{$list['customer_name']}}" data-target="#myModal-challan">Make OC</a> -->
                    <a target="_blank" href="{{url('/admin/order-to-make-oc/'.$list['id'])}}">Make OC</a>
                    @elseif(getStockTotalQuantity($list['id'])==0)
                    
                    @else
                    <i class="icon md-check" style="color:#4caf50; font-size:15px; font-weight:bold;"  aria-hidden="true"></i>
                    @endif
                </td>
                <td style="text-align:center;" class="action">
                    <div class="action-wrap"> 
                    @if(getChallanCount($list['saleorder_no'])<=0)
                    <a title="Edit" href="{{url('admin/saleorder-edit/'.$list['id'])}}" class="btn btn-pure btn-primary icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i></a> 
                    @endif
                    <a title="View" href="{{url('admin/saleorder-view/'.$list['id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i></a> <a title="Print" target="_blank" href="{{url('admin/saleorder-print/'.$list['id'])}}" class="btn btn-pure btn-info icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-print" aria-hidden="true"></i></a> 
                    <!--<a title="PDF Download" href="{{url('admin/saleorder-pdf/'.$list['id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-collection-pdf" aria-hidden="true"></i></a>--> 
                    @if(getChallanCount($list['saleorder_no'])<=0)
                    <span id="del_{{$list['id']}}"> <a title="Delete" onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/delete_saleorder/'.$list['id'])}}" class="btn btn-pure btn-danger icon waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i></a> </span> </div></td>
                    @endif
              </tr>
              <tr class="hide1 cm_hide_row">
                  <td colspan="8"><div class="fix-table-new">
                      <div class="cm_inner_main challan-list-inner">
                        <table class="table">
                          <thead>
                            <tr class="table-head">
                              <th width="5%" class="">Item Name</th>
                              <th width="5%" class="">Vendor SKU</th>
                              <th width="5%" class="">HSN Code</th>
                              <th width="5%" class="">Tax</th>
                              <th width="5%" class="">Packing Name</th>
                              <th width="5%" class="">Rate</th>
                              <th width="5%" class="">MRP</th>
                              <th width="5%" class="">Disc %</th>
                              <th width="5%" class="">Discount</th>
                              <th width="5%" class="">Qty</th>
                              <th width="5%" class="">Date</th>
                              <th width="5%" class="">Net Rate</th>
                              <th width="5%" class="">Tax Amount</th>
                              <th width="5%" class="">Amount</th>
                            </tr>
                          </thead>
                         <tbody>
                          @foreach($items as $items_data)
                          @php
                          $items_data = (array)$items_data;
                          //pr($items_data);
                          @endphp
                          <tr>
                            <td class="white-space-normal"><span class="arrow-icon"></span>{!!getAllitemsById($items_data['item_id'])!!}</td>
                            <td class="white-space-normal">{{$items_data['vendor_sku']}}</td>
                            <td class="white-space-normal">{{$items_data['hsn_code']}}</td>
                            <td class="white-space-normal">{{$items_data['tax_rate']}}</td>
                            <td class="white-space-normal">{{$items_data['packing_name']}}</td>
                            <td class="white-space-normal">{{$items_data['rate']}}</td>
                            <td class="white-space-normal">{{$items_data['mrp']}}</td>
                            <td class="white-space-normal">{{$items_data['discount_per']}}</td>
                            <td class="white-space-normal">{{$items_data['discount']}}</td>
                            <td class="white-space-normal">{{$items_data['quantity']}}</td>
                            <td class="white-space-normal" style="white-space:nowrap;">{{$items_data['scheduled_date']}}</td>
                            <td class="white-space-normal">{{$items_data['net_rate']}}</td>
                            <td class="white-space-normal">{{$items_data['tax_amount']}}</td>
                            <td class="white-space-normal">{{$items_data['amount']}}</td>
                          </tr>
                          @endforeach
                         </tbody>
                        </table>
                      </div>
                    </div></td>
                </tr>
              <div class="modal fade email-popup" id="myModal-{{$list['id']}}" role="dialog">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Email Send</h4>
                    </div>
                    <div class="modal-body search-data-items">
                      <form method="POST" id="reloadform" action="{{url('admin/order_to_dispatch/email_send')}}" class="custom-inline-form item-form-apply">
                        @csrf
                        <div class="row">
                          <div class="col-sm-3">
                            <div class="form-wrap">
                              <label>From</label>
                              <input class="form-control" type="text" id="email_from-{{$list['id']}}" name="email_from" readonly required value="info@hvtdoon.co.in" autocomplete="off" placeholder="">
                            </div>
                          </div>
						  <div class="col-sm-3">
                            <div class="form-wrap">
                              <label>Select Department</label>
                              <select required="" id="department-{{$list['customer_name']}}-{{$list['id']}}" class="form-control dept valid" aria-required="true" aria-invalid="false">
                              <option value="">Select</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-wrap">
                              <label>Select Staff</label>
                              <div class="dropdown bootstrap-select show-tick dropup">
                              <select data-plugin="selectpicker" multiple="" cus="{{$list['id']}}" id="select_staff-{{$list['customer_name']}}-{{$list['id']}}" required="" class="form-control valid staff" aria-required="true" aria-invalid="false">
                              <option value="">Select</option>
                              </select>
                              </div>
                            </div>
                          </div>
						   <div class="col-sm-3">
                            <div class="form-wrap">
                              <label>To</label>
                              <input class="form-control" required type="text" id="email_to-{{$list['id']}}" name="email_to" value="" autocomplete="off" placeholder="" />
                            </div>
                          </div>
						  
						  <div class="col-sm-6">
                            <div class="form-wrap">
                              <label>CC</label>
                              <input class="form-control" type="text" id="email_cc-{{$list['id']}}" name="email_cc" value="" autocomplete="off" placeholder="">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-wrap">
                              <label>BCC</label>
                              <input class="form-control" type="text" id="email_bcc-{{$list['id']}}" name="email_bcc" value="" autocomplete="off" placeholder="">
                            </div>
                          </div>
						  
                          <div class="col-sm-12">
                            <div class="form-wrap">
                              <label>Subject</label>
                              <input class="form-control" type="text" id="email_subject-{{$list['id']}}" name="email_subject" required value="Order to Dispatch" autocomplete="off" placeholder="">
                            </div>
                          </div>
                          <div class="col-sm-12">
                            <div class="form-wrap">
                              <label>Message</label>
                              <textarea class="form-control template_body" required id="email_body-{{$list['id']}}" name="email_body"><p>Dear Sir/ Madam </p>
                              
                                  <p>
                              
                              {{ $cus_data[$list['customer_name']] }}</p>
                              
                              <br/><p>
                              
                              We wish to thank you for your interest shown in our company. Please find attached herewith a copy of the SO, vide SO No. {{$list['saleorder_no']}}</p>
                              
                                  <p>
                              
                              Kindly confirm the same by a return mail, to make sure that everything is as per your request, and for us to proceed with the order.</p>
                              
                                  <p>
                              
                              For any doubts/ changes, please feel to contact me on the below given number or email id, and I shall be happy to assist.</p>
                              
                                  <p>
                              
                              Thank You,</p>
                              
                                  <p>
                              
                              Regards,<br/><strong>H.V.TECHNOLOGIES.</strong><br/>B-7, Phase II, Transport Nagar Dehradun Uttarakhand.<br/>Phone:7500461461 | 7500464464</p>
                              
                              
</textarea>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="{{$list['id']}}" class="btn btn-info btn-email-send">Send Now</button>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
              @else
              <tr>
                <td>No record found.</td>
              </tr>
              @endif
                </tbody>
              
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal-challan" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Items for <span id="customer-name-details"></span></h4>
      </div>
      <div class="modal-body search-data-items">
        <form method="post" id="make_oc">
          <div id="data-table-result">
            <table class="table table-bordered" id="data_table1">
              <thead>
                <tr>
                  <th class="bg-info">Item</th>
                  <th class="bg-info">Qty</th>
                  <th class="bg-info">SO NO.</th>
                  <th class="bg-info">Dispatch</th>
                  <th class="bg-info">Balance</th>
                  <th class="bg-info">Stock</th>
                  <th class="bg-info">In Process</th>
                  <th class="bg-info">Dispatch Qty</th>
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
        @if(getStockTotalQuantity($list['id'])>0)
        <button type="button" class="btn btn-info btn-apply waves-effect waves-classic" id="1">Make OC</button>
        @endif 
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
            var email_cc        = $('#email_cc-'+id).val();
            var email_bcc        = $('#email_bcc-'+id).val();
            var email_subject   = $('#email_subject-'+id).val();
            var email_body      = $('#email_body-'+id).val();
            var pattern         = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
            //$('#'+id).prop('disabled',true);
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            if(id!='')
            {
				if(email_from==''){
					alert('From Email id is required');
					$('#email_from-'+id).focus();
					return false;
				}
				else if(email_from !='' && (!pattern.test(email_from))){
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
				else if(email_to ==''){
					alert('Please valid to Email id is required');
					$('#email_to-'+id).focus();
					return false;
				}
				else if(email_cc !='' && (!pattern.test(email_cc))){
					alert('Please valid to Email id is required');
					$('#email_cc-'+id).focus();
					return false;
				}
				else if(email_bcc !='' && (!pattern.test(email_bcc))){
					alert('Please valid to Email id is required');
					$('#email_bcc-'+id).focus();
					return false;
				}
				else if(email_subject==''){
				alert('Subject is required');
				$('#email_subject-'+id).focus();
				return false;
				}
				else if(email_body==''){
				alert('Message body is required');
				$('#email_body-'+id).focus();
				return false;
				}
				else
				{
					$.ajax({
					url: "{{ url('admin/order_to_dispatch/email_send') }}" ,
					type: "POST",
					data: {id:id,email_from:email_from,email_to:email_to,email_cc:email_cc,email_bcc:email_bcc,email_subject:email_subject,email_body:email_body},
					success: function( response ) {
					//alert(response); return false;
					if(response!='')
					{
						//console.log(response); return false;
					alert('Mail was Sent successfully!');
					$('#email-date-'+id).html(response);
					$('.close').trigger('click');
					//$('#'+id).prop('disabled',false);
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
          // $('#loader').show();
          var id                = $(this).attr('id');
          var customer_name     = $(this).attr('customer_name');
          $('#customer-name-details').html(customer_name);
          var single_order_type = $(this).attr('type');
          var sale_order_id     = $(this).attr('sale_order_id');
         
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
                    data: {id:id,single_order_type:single_order_type,sale_order_id:sale_order_id},
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
		$('#customer_name').html(custHtml);
		$('#customer_name').val('<?php echo $request->customer_name; ?>');
	});
</script>
<style>
.email-popup .row .form-wrap label { display: block; text-align: left; }

.email-popup .note-editable{ text-align:left;}
    
</style>
<script>
    $(function(){
       $(document).on('keyup change','.dispatch_qty',function(){
          var id = $(this).attr('id'); 
          var dispatch_qty_val  = $('.dispatch_qty-'+id).val();
          var balance_qty       = $('.balance-qty-'+id).html();
          if(dispatch_qty_val>balance_qty && dispatch_qty_val!='')
          {
          alert('Dispatch Quantity less than or equal to Balance Quantity.');
          $('.dispatch_qty-'+id).val('');
          $('.dispatch_qty_chk-'+id).prop('disabled',true);
          return false;
          }else if(dispatch_qty_val==''){
          $('.dispatch_qty_chk-'+id).prop('disabled',true);    
          }else{
          $('.dispatch_qty_chk-'+id).prop('disabled',false);
          //alert('success...');   
          }
       }); 
    });
</script> 
<script>
    $(function(){
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                $(document).on('click','.btn-apply',function(e){
                    
                var chk_length_count = $('.dispatch_qty').filter(function() {
                return this.value.trim() != '';
                }).length;
                
                e.preventDefault();
                
                 var single_type    = $('.single_order_type').val();
                 var rowCount = $('#results-pop tr').length;
                if(single_type==1 && rowCount!=chk_length_count){
                alert("Please enter the quantity all input. This is a single lot order.");
                return false;    
                }else{
                if(chk_length_count>0)
                {
                $.ajax({
                type: 'POST',
                url: "{{ url('admin/order_to_dispatch/make_oc') }}",
                data: $('#make_oc').serialize(),
                success: function (response) {
                //alert(response); return false;
                if(response==1)
                {
                window.location.reload(true);
                }
                }
                });
                }else{
                alert("Please enter the quantity.");
                return false;
                }
                }
                }); 
    });
</script> 
<script>
  $(function(){
        $(document).on('click','.send_email',function(){
           var id = $(this).attr('id');
           var order_id = $(this).attr('cus');
           if(id!='')
           {
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

              $.ajax({
              type: 'POST',
              url: "{{ url('admin/order_to_dispatch/get_department') }}",
              data: {id:id},
              success: function (response) {
              //alert(response); return false;
              if(response!='')
              {
              $('#department-'+id+'-'+order_id).html(response);
              }
              }
              });

           }
        });
  });
</script>
<script>
  $(function(){
      $(document).on('change','.dept',function(){
         var res_id = $(this).attr('id');
         var data = res_id.split('-');
         var customer_id = data[1];
         var order_id = data[2];
         var dept_id = $(this).val();
         if(dept_id)
         {
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

            $.ajax({
              type: 'POST',
              url: "{{ url('admin/order_to_dispatch/get_staff') }}",
              data: {designation:dept_id,c_id:customer_id},
              success: function (response) {
              //alert(response); return false;
              if(response!='')
              {
              $('#select_staff-'+customer_id+'-'+order_id).html(response).selectpicker('refresh');
              }
              }
              });
         }
         //alert(dept_id);
      });
  });
</script>
<script>
  $(function(){
      $(document).on('change','.staff',function(){
         var order_id = $(this).attr('cus');
         var id = $(this).val();
         if(id!='')
         {
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
              type: 'POST',
              url: "{{ url('admin/order_to_dispatch/get_staff_email') }}",
              data: {id:id},
              success: function (response) {
              //alert(response); return false;
              if(response!='')
              {
              $('#email_to-'+order_id).val(response);
              }else{
              $('#email_to-'+order_id).val('');  
              }
              }
              });
         }else{
          $('#email_to-'+order_id).val('');  
         }
      });
  });
</script>
<script>
  $(function(){
	  $(".show-hide-table .cm-show-hide-row td:first-child").click(function() {
		  $(this).parent().toggleClass("show-table-below");
		  $(this).parent().next(".hide1").toggleClass("cm_hide_row");        
    });
  });
</script>
	  
@endsection