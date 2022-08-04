@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Employee Payment List')
@section('pagecontent')
<div class="page">
    <div class="page-content container-fluid">
		<div class="row justify-content-center">
					@if (\Session::has('success'))
					<div class="col-md-12">
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div>
					</div>
					@endif

				<a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model" id="invoice_btn"></a>
				<!-- Modal -->
				<div class="modal fade" id="invoice_model" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
							  <button type="button" class="close" data-dismiss="modal">&times;</button>
							  <h4 class="modal-title">Order Search</h4>
							</div>
							<div class="modal-body">
							  <div id="order_found"></div>
							</div>
						</div>
					</div>
				</div>
					
				<div class="col-md-3 pull-left payment-heading-box"">
					<h3 class="panel-title new-pagetitle" >Payment List</h3>
				</div>
				<div class="col-md-6 text-center-form payment-heading-box">
					<form onsubmit="event.preventDefault(); findPaymentOrder();" method="POST" id="findpayment">
						@csrf
						<input type="text" class="form-control" placeholder="Enter order Number" name="order_no" style="width:200px; float:left;" id="order_val"> 
						<button class="btn btn-primary" name="order_search" style="float:left; margin-left:10px;">Search</button>
					</form>
				</div>
				<div class="col-md-3 pull-right payment-heading-box">
					<a class="btn btn-primary waves-effect waves-classic" href="{{ route('employeereport') }}" style="float:right;">
						<i class="icon md-plus" aria-hidden="true"></i> Create New Payment
					</a>
				</div>

			<div class="col-md-12">
				<div class="card">
					@if(!empty($data))
						<?php //pr($data['data']); ?>
							<table class="table table-bordered">
							
								<thead>
									<th class="bg-info" style="color:#fff !important;">Sr. No</th>
									<th class="bg-info" style="color:#fff !important;">Invoice No.</th>
									<th class="bg-info" style="color:#fff !important;">Role</th>
									<th class="bg-info" style="color:#fff !important;">Name</th>
									<th class="bg-info" style="color:#fff !important;">Payment</th>
									<th class="bg-info" style="color:#fff !important;">Date</th>
									<th class="bg-info" style="color:#fff !important;">Action</th>
								</thead>
								
								<tbody id="fb_table_body">
										<?php
										$emp_arr = get_emp_details_api();
										$emp_role_arr = empReportArr();
										$sr=1; 
										?>
										@foreach($data['data'] as $row)
											<?php 
											//pr($row); die;
											//pr($row);
											?>
											<tr>
												<td>{{ $sr }}</td>
												<td>{{ $row['last_inv'] }}</td>
												<td>{{ $emp_role_arr[$row['search_type']] }}</td>
												<td>{{ $emp_arr[$row['s_emp_id']] }}</td>
												<td>{{ $row['total_price'] }}</td>
												<td>{{ changeDateToDmy($row['created_at']) }}</td>
												<td><a href="{{ route('payment_view', $row['last_inv']) }}" class="btn btn-info waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i> View</a>  <a href="{{ route('payment_edit', $row['last_inv']) }}" class="btn btn-warning waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> Edit</a>  <a href="{{ route('payment_print', $row['last_inv']) }}" target="_blank" class="btn btn-primary waves-effect waves-classic"><i class="icon md-print" aria-hidden="true"></i> Print</a> </td>
											</tr>
											<?php $sr++; ?>
										@endforeach
								</tbody>
								
							</table>
					@endif
					
				</div>
				
			</div>
			
		</div>
	</div>  
</div>
@endsection

@section('custom_script')
<script>
	function findPaymentOrder(){
		var formData = $('#findpayment').serialize();
		var order_no = $('#order_val').val();
		var ajax_url = "<?php echo route('findpaymentorder'); ?>";
		$('#loader').show();
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					/// console.log(res);					
					if(res!=''){
						var jsonData = JSON.parse(res);
						console.log(jsonData);
						$('#order_found').html('');
						
						var htmlData = '<p><strong>Order Number: </strong>'+order_no+'<br/>';	
						
						var invNo = 0;
						if(jsonData.hasOwnProperty("fabricator_list")){
							invNo = invNo+1;
							var paymentViewUrl = '{{ route("payment_view") }}/'+jsonData.fabricator_list.inv_no+'?order_no='+order_no;
							
							htmlData +='<strong>'+invNo+') Fabricator Invoice No: </strong><a href="'+paymentViewUrl+'" target="_blank">'+jsonData.fabricator_list.inv_no+'</a><br/>';
						}
						
						if(jsonData.hasOwnProperty("patchers_list")){
							invNo = invNo+1;
							var paymentViewUrl = '{{ route("payment_view") }}/'+jsonData.patchers_list.inv_no+'?order_no='+order_no;
							htmlData +='<strong>'+invNo+') Patchers Invoice No: </strong><a href="'+paymentViewUrl+'" target="_blank">'+jsonData.patchers_list.inv_no+'</a><br/>';
						}
						
						if(jsonData.hasOwnProperty("tailor_list")){
							invNo = invNo+1;
							var paymentViewUrl = '{{ route("payment_view") }}/'+jsonData.tailor_list.inv_no+'?order_no='+order_no;
							htmlData +='<strong>'+invNo+') Tailor Invoice No: </strong><a href="'+paymentViewUrl+'" target="_blank">'+jsonData.tailor_list.inv_no+'</a><br/>';
						}
						
						if(jsonData.hasOwnProperty("h_embroider_list")){
							invNo = invNo+1;
							var paymentViewUrl = '{{ route("payment_view") }}/'+jsonData.h_embroider_list.inv_no+'?order_no='+order_no;
							htmlData +='<strong>'+invNo+') H.EMBR Invoice No: </strong><a href="'+paymentViewUrl+'" target="_blank">'+jsonData.h_embroider_list.inv_no+'</a><br/>';
						}
						
						if(jsonData.hasOwnProperty("m_embroider_list")){
							invNo = invNo+1;
							var paymentViewUrl = '{{ route("payment_view") }}/'+jsonData.m_embroider_list.inv_no+'?order_no='+order_no;
							htmlData +='<strong>'+invNo+') M.EMBR Invoice No: </strong><a href="'+paymentViewUrl+'" target="_blank">'+jsonData.m_embroider_list.inv_no+'</a><br/>';
						}
						
						htmlData +='</p>';
						
						$('#order_found').html(htmlData);
						$('#loader').hide();
					}else{
						$('#order_found').html('');
						$('#order_found').html('<p><strong>Order Number: </strong>'+order_no+' <br/> Not Found</p>');
						$('#loader').hide();
					}
					 
					$('#invoice_btn').trigger('click');
					
				}
		  });
	}
</script>
@endsection