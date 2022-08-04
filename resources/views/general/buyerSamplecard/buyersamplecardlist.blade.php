@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Buyer Sample Card List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Buyer Sample Card List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">Buyer Sample Card List</li>
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
				<form method="POST" action="{{ route('searchsamplecard') }}">
					@csrf
					<select name="sample_card" id="sample_card_id" class="form-control" required>
						<option>--Select--</option>
						<option value="customer_name" {{ @$search=='customer_name'?'selected': '' }}>Customer Name</option>
					</select>
					<input type="text" class="form-control" name="sample_card_code" id="order_val" value="{{ @$search_by }}" required>
					<button class="btn btn-primary" type="submit" name="order_search">Search</button>
					<a href="{{ route('buyersamplecardlisting') }}" class="btn btn-primary" name="reload">Reload</a>
				</form>
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('buyersamplecardcreate') }}" > <i class="icon md-plus" aria-hidden="true"></i> Add Buyer Sample Card </a>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body">
					@if(!empty($buyersamplecardlist))
				<?php //pr($articles); die; ?>
				<table class="table table-bordered">
					<thead>
					    <th class="bg-info" style="color:#fff !important;">Timestamp</th>
					    
						<th class="bg-info" style="color:#fff !important;">Customer Name</th>
						
						<th class="bg-info" style="color:#fff !important;">Enquiry Type</th>						
						
						<th class="bg-info" style="color:#fff !important;">Total Amount</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
					
					    @php 
						$i = ($buyersamplecardlist->currentpage()-1)* $buyersamplecardlist->perpage()
						@endphp	
						@if($buyersamplecardlist->count())
				    	@foreach($buyersamplecardlist as $invoice)
						<?php 
							//pr($invoice); die;
							$oid = (array) $invoice['_id'];
							$sa_id = $oid['oid'];
							$unit_price =0;
							/* $kk = getSampleCardRowDataById($sa_id);
							pr($kk); die; */
							foreach(getSampleCardRowDataById($sa_id) as $rowdata){
								$unit_price +=$rowdata['unit_price'];
							}

							$data_status='';
							if(array_key_exists('data_status', $invoice)){
								if($invoice['data_status']==1){
									$data_status = $invoice['data_status'];
								}
								
							}
							
						?>
						<tr>
						    <td>{{ date('d-M-Y', strtotime($invoice['created_at'])) }}</td>
						  
							<td>{{ $invoice['customer_name'] }}</td>
						   
							<td>Sample Card</td>
							
							
							<td>{{ $invoice['total'] }}</td>
							<td>
								<a href="{{ route('samplecardshow', $invoice['_id']) }}" title='View' class=""><i class="icon md-eye" aria-hidden="true"></i></a>
								<a href="{{ route('samplecardedit', $invoice['_id']) }}" title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>
								
								
								@if($data_status!=1)
								<span id="del_{{ $invoice['_id'] }}">
								<a onclick="return confirm('Are you sure you want to delete?');" title='Delete' href="{{ route('samplecarddelete', $invoice['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a> 
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
				<?php /*<span>{{ $purchaseinvoicelist->links() }}</span>*/?>
			</div>
		</div>
	</div>  
</div>

<script>
	function insertIntoFms(profarmaId){
		//debugger;
		var cf = confirm('Are you sure you want to insert this Sample Card into FMS?');
		if(!cf){
			return false;
		}else{
			$('#loader').show();
			var formData = 'pi_id='+profarmaId;
			var ajax_url = "<?php echo route('ajax_insert_samplecard_into_fms'); ?>";
			
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