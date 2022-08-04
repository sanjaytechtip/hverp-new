@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Invoice List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Proforma Invoice List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">Proforma Invoice List</li>
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
				<form method="POST" action="{{ route('searchinvoice') }}">
					@csrf
					<select name="invoice" id="invoice_id" class="form-control" required>
						<option>--Select--</option>
						<option value="po_serial_number" {{ @$search=='po_serial_number'?'selected': '' }}>PI No.</option>
						<option value="order_type" {{ @$search=='order_type'?'selected': '' }}>Order Type</option>
						<option value="company_name" {{ @$search=='company_name'?'selected': '' }}>Buyer Name</option>
					</select>
					<input type="text" class="form-control" name="invoice_code" id="order_val" value="{{ @$search_by }}" required>
					<button class="btn btn-primary" type="submit" name="order_search">Search</button>
					<a href="{{ route('invoicelisting') }}" class="btn btn-primary" name="reload">Reload</a>
					<a class="btn btn-primary waves-effect waves-classic" style="float:right; margin-left:10px;" href="{{ route('PurchaseExport') }}"> Export All Performa</a>&nbsp;&nbsp;&nbsp;
				</form>
				
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('invoicecreate') }}" > <i class="icon md-plus" aria-hidden="true"></i> Add New Proforma Invoice </a>
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
				<table class="table table-bordered">
					<thead>
					    <th class="bg-info" style="color:#fff !important;">PI Date</th>
					    <th class="bg-info" style="color:#fff !important;">PI No.</th>
						<th class="bg-info" style="color:#fff !important;">Company Name</th>
						<th class="bg-info" style="color:#fff !important;">Agent /Sales Person</th>
						<th class="bg-info" style="color:#fff !important;">Order Type</th>						
						<th class="bg-info" style="color:#fff !important;">Status</th>
						<th class="bg-info" style="color:#fff !important;">Total Qty</th>
						
						<th class="bg-info" style="color:#fff !important;">Total Amount</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
					
					    @php 
						$i = ($purchaseinvoicelist->currentpage()-1)* $purchaseinvoicelist->perpage()
						@endphp	
						@if($purchaseinvoicelist->count())
				    	@foreach($purchaseinvoicelist as $invoice)
						<?php 
							//pr($invoice); die;
							$oid = (array) $invoice['_id'];
							$p_id = $oid['oid'];
							$Mtr=0; $Kg =0; $Yd =0;	 $Pcs =0;						
							foreach(getPiRowDataByPiId($p_id) as $rowdata){
									//pr($rowdata);
								if($rowdata['unit']=='Mtr'){
									$Mtr +=$rowdata['bulkqty'];
					
								}elseif($rowdata['unit']=='Kg'){
									$Kg +=$rowdata['bulkqty'];
								}elseif($rowdata['unit']=='Yd'){
									$Yd +=$rowdata['bulkqty'];
								}elseif($rowdata['unit']=='Pcs'){
									$Pcs +=$rowdata['bulkqty'];
								}
								
								
							}
							
							$data_status='';
							if(array_key_exists('data_status', $invoice)){
								if($invoice['data_status']==1){
									$data_status = $invoice['data_status'];
								}
								
							}
							
							$rev='R1';
							$pi_v_data = getPiVersionsById($invoice['_id']);
							
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
						    <td>{{ date('d-M-Y', strtotime($invoice['proforma_invoice_date'])) }}</td>
						    <td><a href="{{ route('invoiceshow', $invoice['_id']) }}{{ $partshipped_query }}" title='View' target=="_blank">{{ $invoice['po_serial_number'] }}{{ $rev_p }}{{ $rev }}</a></td>
							<td>{{ GetBuyerName($invoice['customer_name']) }}</td>
						    <td>{{ getSalesPerson($invoice['sales_agent']) }}</td>
							<td>{{ $invoice['order_type'] }}</td>
							<td>{{ $invoice['status'] }}</td>
							<td><?php 
									$allQty = $Mtr>0? $Mtr.'Mtr.,': '';
									$allQty .=$Kg>0? $Kg.'Kg.,': '';
									$allQty .=$Yd>0? $Yd.'Yd.,': '';
									$allQty .=$Pcs>0? $Pcs.'Pcs.,': '';
									echo substr($allQty, 0,-1);
									
									$email_send='';
									if(array_key_exists('email_send', $invoice) && $invoice['email_send']==1){
										$email_send=1;
									}
								?>
							</td>
							
							<td>{{ $invoice['total'] }}</td>
							<td>
								<a href="{{ route('invoiceshow', $invoice['_id']) }}{{ $partshipped_query }}" title='View' class=""><i class="icon md-eye" aria-hidden="true"></i></a>
								
								
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
								<a href="{{ route('create_part_shipped', $invoice['_id']) }}" title='Create Part Shipped' class=""><i class="icon md-parking" aria-hidden="true"></i></a>
								<?php 
									}
								?>
								
								<a href="{{ route('invoiceedit', $invoice['_id']) }}{{ $partshipped_query }}" title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>
								@if($data_status!=1)
								<span id="del_{{ $invoice['_id'] }}">
								<a onclick="return confirm('Are you sure you want to delete?');" title='Delete' href="{{ route('invoicedelete', $invoice['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a> 
								</span>
								
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
			<input type="hidden" name="inv_type" id="inv_type" value="pi_invoice" />
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
		var cf = confirm('Are you sure you want to insert this PI into FMS?');
		if(!cf){
			return false;
		}else{
			$('#loader').show();
			var formData = 'pi_id='+profarmaId;
			var ajax_url = "<?php echo route('ajax_insert_pi_into_fms'); ?>";
			
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;
					
					/* check if data alredy inserted */
					if(res=='yes'){
						alert('Data alredy inserted please check.');
						location.reload();
						return false;
						
					}
					
					var data = JSON.parse(res);
					$('#loader').hide();
					if(data.status){
						$('#del_'+profarmaId).html('');
						$('#pi_'+profarmaId).html('');
						$('#pi_'+profarmaId).html('<a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>');
						
					}
					console.log(res);
				}
			});
		}
		
	}
</script>
@endsection


@section('custom_validation_script')
<script>
	function getinvoiceemaildata(profarmaId){
			$('#loader').show();
			$('#msg-update').html('');
			var formData = 'pi_id='+profarmaId;
			var ajax_url = "<?php echo route('getinvoiceemaildata'); ?>";
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;
					/* check if data alredy inserted */					
					var data = JSON.parse(res);
					$('#loader').hide();
					if(data!=''){
						$('#recipient-name').val('');
						//$('#recipient-name').val(data.recipient);
						var all_recipient = data.recipient;
						var recipient = all_recipient.replace(/,(\s+)?$/, '');  
						
						$('#recipient-name').val(recipient);
						$('#pi_id').val('');
						$('#pi_id').val(profarmaId);
						 
						var sales_p ='';
						if(data.sales_p_name!=''){
							sales_p= data.sales_p_name;
						}else if(data.merchant_name!=''){
							sales_p= data.merchant_name;
						}
						var name_of_md= data.name_of_md;
						var company_name= data.company_name;
						var inv_no= data.inv_no;
						
						var u_name= '{{ $u_name }}';
						var u_email= '{{ $u_email }}';
						var u_phone= '{{ $u_phone }}';
						
						$('#message-text').summernote('code','');
						var html = '<p>Dear Sir/ Madam</p><p>'+company_name+'</p><p>We wish to thank you for your interest shown in our fabrics. Please find attached herewith a copy of the Proforma Invoice, vide PI No. <span id="inv_no">'+inv_no+'</span></p><p>Kindly confirm the same by a return mail, to make sure that everything is as per your request, and for us to proceed with the order.</p><p>In cases of PI’s against ready stocks, PI’s are valid for a period of 3 days, after which the goods might be released to other buyers.</p><p>For any doubts/ changes, please feel to contact me on the below given number or email id, and I shall be happy to assist.</p><p></p><p>Thank You,</p><p>Regards,<br/>'+u_name+'<br/>Positex Pvt. Ltd. <br/>406-407, P.P. Towers, Netaji Subhash Place, Pitampura, Delhi-110034 <br/>Phone:01142470202|+91-'+u_phone+', '+u_email+'</p>';
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
			var ajax_url = "<?php echo route('seninvoicemail'); ?>";
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
						
						//$('#email_'+pi_id).html('');
						/* $('#email_'+pi_id).html('<a href="javascript:void(0)" title="Mail was Sent" class="btn btn-pure btn-success"><i class="icon md-email" aria-hidden="true"></i></a>'); */
						
						$('#email_'+pi_id+' a').removeClass('btn-danger');
						$('#email_'+pi_id+' a').addClass('btn-success');
						
						return false;
					}
				}
			});
	}
	</script>
@endsection