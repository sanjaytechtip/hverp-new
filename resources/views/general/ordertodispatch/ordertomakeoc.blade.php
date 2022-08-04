@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Make OC')
@section('pagecontent')
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn order-to-dispatch-wrap">
        <div class="page-header">
          <h1 class="page-title new-page-title">Make OC <span>({{$customer_name}})</span></h1>
          <ol class="breadcrumb"  style="white-space:nowrap;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('order_to_dispatch') }}">Order to Dispatch : List</a></li>
            <li class="breadcrumb-item">Make OC</li>
          </ol>
        </div>
        <div class="inlineform-wrap">
          
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
		  <form method="post" id="make_oc">
		  <input type="hidden" value="{{$customer_id}}" name="customer_id">
			<table class="table table-bordered t1 custom-lineheight-table">
			<thead>
			<tr>
			<th class="bg-info" id="sr" style="color:#fff !important;">SR</th>
			<th class="bg-info" id="item_name" style="color:#fff !important;">Item Name</th>
			<th class="bg-info" id="date" style="color:#fff !important;">Date</th>
			<th class="bg-info" id="qty" style="color:#fff !important;">Quantity</th>
			<th class="bg-info" id="dispatch" style="color:#fff !important;">Dispatch</th>
			<th class="bg-info" id="balance" style="color:#fff !important;">Balance</th>
			<th class="bg-info" id="stock" style="color:#fff !important;">Stock</th>
			<th class="bg-info" id="process" style="color:#fff !important;">Process</th>
			<th class="bg-info" id="action" style="color:#fff !important;">Action</th>
			</tr>
			</thead>
			<tbody id="results-pop">
			@if(!empty($saleorderlist))
			@php
			$i=1;
			@endphp
			@foreach($saleorderlist as $list)
			@php
			$item_list = getOrderItemList($list['id']);
			@endphp
			<tr>
			<td colspan="8" align="left" class="heading-td"><h4>{{$list['saleorder_no']}}</h4></td>
			</tr>
			@if(!empty($item_list))
			@php
			$sum_qty = 0;
			$dispatch_qty = 0;
			$btn_flag = 0;
			@endphp
			@foreach($item_list as $list_item)
			<tr>
			<td>{{$i}}</td> 
			<td class="title-box">
			{!!getAllitemsById($list_item['item_id'])!!}
			@php
            $get_bal = getBalanceQuantity($list_item['item_id']);
			@endphp
			@if(!empty($get_bal))
			@php
            $btn_flag =1;
			@endphp
            <table class="inner-tbl">
				<tr>
				<th>Batch No.</th>
					<th>Store</th>
					<th>Room</th>
					<th>Rack No</th>
					<th>Mfg Date</th>
					<th>Expire Date</th>
					<th>Qty</th>
					<th>Action</th>
			   </tr>
				@php
				$sel = 0;
				@endphp
				@foreach($get_bal as $key=>$bal_get)
				@php
				$today = date('Y-m-d');
				@endphp
				<tr class="it">
					<td>{{$bal_get['batch_no']}}</td>
					<td>{{$bal_get['loc_cen']}}</td>
					<td>{{$bal_get['room_no']}}</td>
					<td>{{$bal_get['rack_name']}}</td>
					<td>{{globalDateformatItem($bal_get['mfg_date'])}}</td>
					<td>{{globalDateformatItem($bal_get['expiry_date'])}}</td>
					<td>{{$bal_get['quantity']}}</td>
					<td>
						@if($today<=$bal_get['expiry_date'])
						<input value="{{$bal_get['id']}}" type="radio" @if($sel==0) checked="checked" @endif name="row[{{$i}}][item_stock_id]" class="@if($sel!=0) error-checked @endif">
					    @php
                        $sel=1;
						@endphp
						@else
						<span style="color:#F00;">Expired</span>
						@endif
					</td>
			</tr>
			@endforeach
			</table>
			@endif
			</td>    
			<td>{{$list_item['scheduled_date']}}</td>
			<td class="qty">{{$list_item['quantity']}}</td>
			<td>{{getDispatchOrder($list_item['customer_id'],$list_item['item_id'],getSaleOrderNo($list_item['sale_order_id']))}}</td>    
			<td class="balance-qty-{{$list_item['item_id']}}">{{($list_item['quantity']-getDispatchOrder($list_item['customer_id'],$list_item['item_id'],getSaleOrderNo($list_item['sale_order_id'])))}}</td>
			<td>{{getStockItem($list_item['item_id'])}}</td>
			<td></td>
			<td>
			<?php if(empty($get_bal) || $sel==0){$read = "disabled='disabled'";}else{$read = '';}?>
			<?php if(($list_item['quantity']-getDispatchOrder($list_item['customer_id'],$list_item['item_id'],getSaleOrderNo($list_item['sale_order_id'])))==0 || getStockItem($list_item['item_id'])==0){$readonly = "disabled='disabled'";}else{$readonly = '';}?>
			<input min="1" {{$readonly}} {{$read}} class="dispatch_qty dispatch_qty-{{$list_item['item_id']}}" id="{{$list_item['item_id']}}" name="row[{{$i}}][dispatch_qty]" style="width:70px;" type="number"></td>
			</tr>
			
			<input type="hidden" value="{{$list['saleorder_no']}}" name="row[{{$i}}][sale_order_no]">
			<input type="hidden" value="{{$list_item['item_id']}}" name="row[{{$i}}][item_id]">
			<input type="hidden" value="{{$list_item['id']}}" name="row[{{$i}}][o_item_id]">
			@php
			$i++;
			$sum_qty = $sum_qty + $list_item['quantity'];
			$dispatch_qty = $dispatch_qty + getDispatchOrder($list_item['customer_id'],$list_item['item_id'],getSaleOrderNo($list_item['sale_order_id']));
			@endphp
			@endforeach
			@endif
			@if($list['single_order']=='no' || $list['single_order']=='')
			<input type="hidden" class="single_order_type" value="0">
			@else
			<input type="hidden" class="single_order_type" value="1">
			@endif
			@endforeach
			@endif
			</tbody>
			</table>
            <div class="make-oc-btn">
			@if($sum_qty!=$dispatch_qty)
			@if($btn_flag==1)
			<button type="button" class="btn btn-info btn-apply waves-effect waves-classic" id="1">Make OC</button></div>
			@endif
			@endif
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

				var a = 0;
				var dispatch_qty = 0;
				$(".qty").each(function() {
				a += parseInt($(this).html());
				});

				$(".dispatch_qty").each(function() {
					dispatch_qty += isNaN(parseInt($(this).val())) ? 0 : parseInt($(this).val());
				});
				
                 
				 //alert(a+'-'+dispatch_qty); return false;
                if(single_type==1 && a!=dispatch_qty){
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
                if(response!='')
                {
				var url = '{{url("/admin/dispatch-planning")}}';
                window.location.href= url+'/'+response;
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
       $(document).on('keyup change','.dispatch_qty',function(){
          var id = $(this).attr('id'); 
          var dispatch_qty_val  = parseInt($('.dispatch_qty-'+id).val());
          var balance_qty       = parseInt($('.balance-qty-'+id).html());
		  //alert(dispatch_qty_val+"-"+balance_qty);
          if(dispatch_qty_val>balance_qty && dispatch_qty_val!='')
          {
          alert('Dispatch Quantity less than or equal to Balance Quantity.');
          $('.dispatch_qty-'+id).val('');
          return false;
          }else if(dispatch_qty_val==''){    
          }else{
          //alert('success...');   
          }
       }); 
    });
</script> 
<script>
$(function(){
   $(document).on('click','.error-checked',function(){
     var verify  = confirm('Are you sure avoid the near expiry date?');
	 if(verify){

	 }else{
	 return false;
	 }
   });
});
</script>
@endsection