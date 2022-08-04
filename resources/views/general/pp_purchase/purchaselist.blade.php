@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Purchase List')
@section('pagecontent')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Purchase Order Management</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">Purchase Order Management</li>
		</ol>
	</div>
    <div class="page-content">
		<div class="row">
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
			<div class="col-md-12 payment-heading-box">
				<form method="POST" action="{{ route('searchpurchase') }}" onsubmit="return checkInputVal();">
					@csrf
					<select name="purchase" id="purchase_id" class="form-control" required onchange="getSearch(this.value);">
						<option>--Select--</option>
						<option value="po_serial_number" {{ @$search=='po_serial_number'?'selected': '' }}>PO Number</option>
						<option value="order_type" {{ @$search=='order_type'?'selected': '' }}>Order Type</option>
						<option value="supplier_name" {{ @$search=='supplier_name'?'selected': '' }}>Supplier Name</option>
						<option value="item_number" {{ @$search=='item_number'?'selected': '' }}>Item Number</option>
					</select>
					
					
					<?php 
						$normal='';
						$item_search='';
						if(@$search=='item_number'){ 
							$normal=' disabled style="display:none;"';
							$item_search='';
						}else{
							$normal='';
							$item_search='disabled style="display:none;"'; 
						}

						?>
					<input type="text" <?php echo $normal; ?> class="form-control" name="purchase_code" id="order_val" value="{{ @$search_by }}" required>

					<input type="text" name="item_drop" <?php echo $item_search; ?> class="autocomplete-dynamic form-control" placeholder="Type Item No." value="{{ @$item_drop }}" id="item_id" required />
					<input type="hidden" name="item_id" class="form-control" value="{{ @$item_id }}" id="hidden_item_id" />
					
					<button class="btn btn-primary" type="submit" name="order_search">Search</button>
					<a href="{{ route('purchaselisting') }}" class="btn btn-primary" name="reload">Reload</a>
				</form>
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('purchase') }}" > <i class="icon md-plus" aria-hidden="true"></i> Add New Purchase Order </a>
			</div>
		</div>
		<?php 
			$uData = getCurrentUserDetails(); 
			$u_name =''; $u_email = ''; $u_phone = '';
			if(!empty($uData)){
				$u_name = $uData['name'];
				$u_email = $uData['email'];
				$u_phone = $uData['phone_no'];
			}
			//pr($uData); die;
		?>
		<div class="panel">
			<div class="panel-body">
					@if(!empty($purchaseinvoicelist))
				<?php //pr($purchaseinvoicelist); die; ?>
				<table class="table table-bordered">
					<thead>
						<th class="bg-info" style="color:#fff !important;">PO Date</th>
						<th class="bg-info" style="color:#fff !important;">PO Number</th>
						<th class="bg-info" style="color:#fff !important;">Supplier Name</th>
						
						<th class="bg-info" style="color:#fff !important;">Item No.</th>
						<th class="bg-info" style="color:#fff !important;">Total Order Qty</th>
						<th class="bg-info" style="color:#fff !important;">ETD</th>
						
						<th class="bg-info" style="color:#fff !important;">Order Type</th>
						
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
					
					    @php 
						$i = ($purchaseinvoicelist->currentpage()-1)* $purchaseinvoicelist->perpage()
						@endphp	
						@if($purchaseinvoicelist->count())
				    	@foreach($purchaseinvoicelist as $invoice)
						<?php
							$supplierdata= getSupplierData($invoice['brand_name']);
							if(!empty($supplierdata[0])){
								$brand_name = $supplierdata[0]['company_name'];
							}else{
								$brand_name = '';
							}
							
						 
							//pr($invoice); Pcs
							$oid = (array) $invoice['_id'];
							$p_id = $oid['oid'];
							$Mtr=0; $Kg =0; $Yd =0;	$Pcs=0; $item=''; $etd='';	
							
							foreach(getPoRowDataByPoId($p_id) as $rowdata){
								
								if($rowdata['item']!=''){
									$item.=getItemName($rowdata['item']).', ';
								}
								
								if($rowdata['item']!=''){
									$etd.=date('d.m.Y',strtotime($rowdata['etd'])).', ';
								}
								
								
								if($rowdata['unit']=='Mtr'){
									$Mtr +=$rowdata['order_qty'];
					
								}elseif($rowdata['unit']=='Kg'){
									$Kg +=$rowdata['order_qty'];
								}elseif($rowdata['unit']=='Yd'){
									$Yd +=$rowdata['order_qty'];
								}elseif($rowdata['unit']=='Pcs'){
									$Pcs +=$rowdata['order_qty'];
								}
								
							}
							
							$data_status='';
							if(array_key_exists('data_status', $invoice)){
								if($invoice['data_status']==1){
									$data_status = $invoice['data_status'];
								}
								
							}
							
							/* $rev='';
							$pi_v_data = getPuInvVersionsById($invoice['_id']);
							
							if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
								$pi_v_data = $pi_v_data['pi_version'];
								$pi_cnt = count($pi_v_data);
								$rev_cnt = $pi_cnt-1;
							
								if($rev_cnt==0){
									$rev='';
								}else{
									$rev = '-R'.($rev_cnt);
								}
							} */
							
							$rev='R1';
							$pi_v_data = getPuInvVersionsById($invoice['_id']);
							
							$partshipped_query='';
							$rev_p='';
							if(array_key_exists('partshipped_no', $invoice)){
								$rev_p = '-P'.$invoice['partshipped_no'];
								$partshipped_query='?partshipped='.$invoice['partshipped_no'];
								$rev = '';
								if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
									$pi_v_data_res = $pi_v_data['pi_version'];
									$pi_cnt = count($pi_v_data_res);									
									$rev_cnt = $pi_cnt-1;							
									if($rev_cnt==0){
										$rev='';
									}else{
										$rev = '-R'.($rev_cnt);
									}
								}
							}elseif(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
								$pi_v_data_res = $pi_v_data['pi_version'];
								$pi_cnt = count($pi_v_data_res);								
								//$rev = 'R'.$pi_cnt;
								$rev_cnt = $pi_cnt-1;							
								if($rev_cnt==0){
									$rev='';
								}else{
									$rev = '-R'.($rev_cnt);
								}
							}
							
						?>
						
						<tr>
							 <td>{{ date('d-m-Y', strtotime($invoice['po_date'])) }}</td>
							 <td><a href="{{ route('purchaseview', $invoice['_id']) }}{{ $partshipped_query }}" title='View' target="_blank">{{ $invoice['po_serial_number'] }}{{ $rev_p }}{{$rev}}</a></td>
							<td>{{$brand_name}}</td>
							<td>{{ substr($item,0,10) }} @if(strlen($item)>10) ... @endif</td>
							
							<td>
								<?php 
									$allQty = $Mtr>0? number_format($Mtr,2).'Mtr.,': '';
									$allQty .=$Kg>0? number_format($Kg,2).'Kg.,': '';
									$allQty .=$Yd>0? number_format($Yd,2).'Yd.,': '';
									$allQty .=$Pcs>0? number_format($Pcs,2).'Pcs.,': '';
									echo substr($allQty, 0,-1);
								?>
							</td>
							
							<td>{{ substr($etd,0,10) }} @if(strlen($etd)>15) ... @endif</td>
							
							<td>
								<?php 
									$o_type = $invoice['order_type'];
									if($o_type=='job_work_order'){
										echo 'Job Work Order';
									}else{
										echo $o_type;
									}
									
									$email_send='';
									if(array_key_exists('email_send', $invoice) && $invoice['email_send']==1){
										$email_send=1;
									}
								?>
							</td>
							
							<td>
								
								<a href="{{ route('purchaseview', $invoice['_id']) }}{{ $partshipped_query }}" title='View' class=""><i class="icon md-eye" aria-hidden="true"></i></a>
								
								
									<?php 
										if($email_send==''){
											$btn='btn-danger';
										}else{
											$btn='btn-success';
										}
									?>
									<span id="email_{{ $invoice['_id'] }}"><a href="javascript:void(0)" title="Send Mail" onclick="getinvoiceemaildata('{{ $invoice['_id'] }}');" class="btn btn-pure <?php echo $btn;?>"><i class="icon md-email" aria-hidden="true"></i></a></span>
									
								
								
								<?php 
									if(!array_key_exists('partshipped_no', $invoice)){
								?>
								<a href="{{ route('create_po_part_shipped', $invoice['_id']) }}" title='Create PO Part Shipped' class=""><i class="icon md-parking" aria-hidden="true"></i></a>
								<?php 
									}
								?>
								
								<a href="{{ route('purchaseedit', $invoice['_id']) }}{{ $partshipped_query }}" title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>  
								@if($data_status!=1)
								<a onclick="return confirm('Are you sure you want to delete?');" title='Delete' id="del_{{$invoice['_id']}}" href="{{ route('purchasedelete', $invoice['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a> 
								@endif
								<span id="pi_{{ $invoice['_id'] }}">
									@if(array_key_exists('data_status', $invoice))
										@if($invoice['data_status']==1)
											<a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>
										@else
											<a href="javascript:void(0)" onclick="return insertIntoFms('{{ $invoice['_id'] }}');" title="Insert into FMS"><i class="icon md-plus-circle-o" aria-hidden="true"></i></a>
										@endif
									@else
										<a href="javascript:void(0)" onclick="return insertIntoFms('{{ $invoice['_id'] }}');" title="Insert into FMS"><i class="icon md-plus-circle-o" aria-hidden="true"></i></a>
									@endif
								</span>
								
							</td>
						</tr>
						@endforeach
						@else
							<tr>
								<td colspan="9">Result not found.</td>
							</tr>
						@endif
					</tbody>
				</table>
				@endif
				<span>{{ $purchaseinvoicelist->links() }}</span>
			</div>
		</div>
	</div>  
</div>

<a href="javascript:void(0)" data-toggle="modal" data-target="#exampleModal" id="mail_form"></a>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send PI Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		<div id="msg-update"></div>
      </div>
		<form action="" method="post" onsubmit="event.preventDefault(); return senInvoiceMail();" id="invoice-form">
			<input type="hidden" name="pi_id" id="pi_id" value="" />
			<input type="hidden" name="inv_type" id="inv_type" value="po_invoice" />
			<div class="modal-body">
			  <div class="form-group">
				<label for="recipient-name" class="col-form-label">Recipient:</label>
				<input type="text" name="customer" class="form-control" required id="recipient-name" value="" />
			  </div>
			  <div class="form-group">
				<label for="message-text" class="col-form-label">Message:</label>
				<textarea class="form-control note-codable" name="message" role="textbox" aria-multiline="true" id="message-text"></textarea>
			  </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" name="submit">Send Email</button>
			</div>
		</form>
    </div>
  </div>
</div>
<script>
	function insertIntoFms(profarmaId){
		//debugger;
		var cf = confirm('Are you sure you want to insert this PO into FMS?');
		if(!cf){
			return false;
		}else{
			$('#loader').show();
			var formData = 'po_id='+profarmaId;
			var ajax_url = "<?php echo route('ajax_insert_po_into_fms'); ?>";
			
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;
					
					/* check if data alredy inserted */
					if(res=='yes'){
						alert('Data alredy inserted please check.');
						location.reload();
						return false;
						
					}
					
					var data = JSON.parse(res);
					$('#loader').hide();
					$('#del_'+profarmaId).remove();
					if(data.status){
						$('#pi_'+profarmaId).html('');
						$('#pi_'+profarmaId).html('<a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>');
						
					}
					console.log(res);
				}
			});
		}
		
	}
	
	
	function checkInputVal(){
		var purchase_id = $('#purchase_id').val();
		var hidden_item_id = $('#hidden_item_id').val();
		if(purchase_id=='item_number' && hidden_item_id==''){
			alert('Please select correct Item from dropdown options.');
			return false;
		}else{
			return true;
		}
		
	}
	function getSearch(thisVal){
		$('#order_val').val('');
		if(thisVal=='item_number'){
			$('#order_val').hide();
			$('#order_val').prop('disabled', true);
			$('.autocomplete-dynamic').prop('disabled', false);
			$('#item_id').show();
		}else{
			$('#order_val').show();
			$('#order_val').prop('disabled', false);
			$('.autocomplete-dynamic').prop('disabled', true);
			$('#item_id').hide();
		}
		
	}
	
	
	
</script>
@endsection


@section('custom_validation_script')
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(function() {
		
		function split( val ) {
		return val.split( /,\s*/ );
		}
		function extractLast( term ) {
		return split( term ).pop();
		}
		
		jQuery(".autocomplete-dynamic").autocomplete({
			minLength: 2,
			source: "{{ route('getmatcharticle') }}",
			select: function( event, ui ) {
					event.preventDefault();
					//console.log(ui);					
					//console.log(ui.item.id);
					//console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
					jQuery('#hidden_item_id').val(ui.item.id);
					jQuery('.autocomplete-dynamic').val(ui.item.value);
					
					
				
					
			}
		});
	});
</script>
<script>
	function getinvoiceemaildata(profarmaId){
			$('#loader').show();
			$('#msg-update').html('');
			var formData = 'pi_id='+profarmaId;
			var ajax_url = "<?php echo route('getpoinvoiceemaildata'); ?>";
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;
				
					var data = JSON.parse(res);
					$('#loader').hide();
					if(data!=''){
						$('#recipient-name').val('');
						$('#recipient-name').val(data.email_id_md);
						$('#pi_id').val('');
						$('#pi_id').val(profarmaId);
						 
						var sales_p ='';
						if(data.sales_p_name!=''){
							sales_p= data.sales_p_name;
						}else if(data.merchant_name!=''){
							sales_p= data.merchant_name;
						}
			
						var company_name= data.company_name;
						var inv_no= data.inv_no;
						
						var u_name= '{{ $u_name }}';
						var u_email= '{{ $u_email }}';
						var u_phone= '{{ $u_phone }}';
						
						
						$('#message-text').summernote('code','');
						var html = '<p>Dear Sir/ Madam</p><p>'+company_name+'</p><p>We are glad to place with you our order, as per the attached Purchase Order vide PO No. <span id="inv_no">'+inv_no+'</span></p><p>Kindly confirm the same by a return mail, to make sure that you have clear understanding of the order. Also please provide a copy of your Proforma Invoice/ Sales Contract.</p><p>We have also attached herewith the following documents :</p><p>1. Testing Parameters - https://tinyurl.com/POS-Testingstandards</p><p>2. Packing Instructions - https://tinyurl.com/POS-PackingInstructions </p><p>Please make sure that you follow all instructions, to ensure that together we are able to supply "Quality and Quantity, on Time, Every Time" to our customers.</p><p>For any clarifications, please feel to contact me on the below given number or email id, and I shall be happy to assist.</p><p></p><p>Thank You,</p><p>Regards,<br/>'+u_name+'<br/>Positex Pvt. Ltd. <br/>406-407, P.P. Towers, Netaji Subhash Place, Pitampura, Delhi-110034 <br/>Phone:01142470202|+91-'+u_phone+', '+u_email+'</p>';
						 $('#message-text').summernote('code',html);
						$('#mail_form').trigger('click');
						
					}
				}
			});
		
	}
	
	function senInvoiceMail(){
		var formData = $('#invoice-form').serialize();
		var pi_id = $('#pi_id').val();
		var customer = $('#recipient-name').val();
		var message = $('#message-text').val();
			message = escape(message);
		var inv_no = $('#inv_no').text();
		var name_of_md = $('#name_of_md').text();
		var company_name = $('#company_name').text();
			company_name = escape(company_name);
			var ajax_url = "<?php echo route('senpoinvoicemail'); ?>";
			$('#loader').show();
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					$('#loader').hide();
					console.log(res); //return false;
					if(res=='1'){
						alert('Mail was Sent successfully!')
						$('#msg-update').html('');
						$('#msg-update').html('<h3 class="text-success">Mail was Sent successfully!</h3>');
						
						/* $('#email_'+pi_id).html('');
						$('#email_'+pi_id).html('<a href="javascript:void(0)" title="Mail was Sent" class="btn btn-pure btn-success"><i class="icon md-email" aria-hidden="true"></i></a>'); */
						
						$('#email_'+pi_id+' a').removeClass('btn-danger');
						$('#email_'+pi_id+' a').addClass('btn-success');
						
						return false;
					}
				}
			});
		
		
		
	}
	</script>
<script>
$.noConflict();
</script>
@endsection