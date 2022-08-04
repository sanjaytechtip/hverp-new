@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Stock Inward List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Stock Inward List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">Stock Inward List</li>
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
			<?php 
				$ptype='';
				if(isset($_GET) && $_GET['ptype']!=''){
					$ptype=$_GET['ptype'];
				}
				
				$ptype;
			?>
			<div class="col-md-2 payment-heading-box">
				<select class="form-control" style="float:right;" onchange="seletctType(this.value);">
					<option value="all" @if($ptype=='all' || $ptype=='') selected @endif>All</option>
					<option value="sales_return" @if($ptype=='sales_return') selected @endif>Sales Return</option>
					<option value="purchase" @if($ptype=='purchase') selected @endif>Purchase</option>
				</select>
			</div>
			<div class="col-md-7 payment-heading-box">
				
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;"  href="{{ route('stockpurchasecreate') }}" > <i class="icon md-plus" aria-hidden="true"></i> Add Stock Purchase Data </a>
				
			</div>
			<div class="col-md-3 payment-heading-box">
				
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('stockpurchasecreate') }}?type=salesreturn" > <i class="icon md-plus" aria-hidden="true"></i> Add Sales Return Data </a>
			</div>
			
		</div>
		<?php 
			//pr($data); die;
		?>
		<div class="panel">
			<div class="panel-body">
				@if(!empty($data_draft) || !empty($data))
				<table class="table table-bordered">
					<thead>
					    <th class="bg-info" style="color:#fff !important;">Packing Slip No.</th>
						<th class="bg-info" style="color:#fff !important;">Supplier</th>
						<th class="bg-info" style="color:#fff !important;">Total Qty(Mtr)</th>
						<th class="bg-info" style="color:#fff !important;">Total Qty(Kg)</th>
						<th class="bg-info" style="color:#fff !important;">Total Qty(Yd)</th>
						<th class="bg-info" style="color:#fff !important;">Total Qty(Pcs)</th>
						<th class="bg-info" style="color:#fff !important;">No. Of Rolls</th>
						<th class="bg-info" style="color:#fff !important;">Inward Type</th>
						<th class="bg-info" style="color:#fff !important;">Date</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
						<?php 
							//pr( $data_draft); die;
						?>
						@foreach($data_draft as $row)
						<tr>
							<td>{{ $row['packing_slip_no_full'] }}</td>
							<td> {{ getSupplierData($row['supplier_id'])[0]['company_name'] }}</td>
							<td>{{ $row['total_mtr'] }}</td>
							<td>{{ $row['total_kg'] }}</td>
							<td>{{ $row['total_yd'] }}</td>
							<td>{{ $row['total_pcs'] }}</td>
							<td>{{ $row['no_of_rolls'] }}</td>
							<td>{{ $row['p_type'] }}</td>
							<td>{{ date('d M Y', strtotime($row['created_at'])) }}</td>
							<td>
								<a href="{{ route('stockpurchaselistdata', $row['_id']) }}?status=draft" target="_blank">Detail</a>
								<a href="{{ route('stockpurchaselistsummary', $row['_id']) }}?status=draft" target="_blank">Summary</a>
								<a href="{{ route('stockpurchaselistdata_draft', $row['_id']) }}" target="_blank" title="Approve and Check RIC Duplicate">App./Check RIC</a>
								<a onclick="return confirm('Are you sure you want to delete?');" title="Delete" href="{{ route('stockpurchaselist_draft_delete', array( $row['_id'])) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a>
							</td>
						</tr>
						@endforeach
						
				    	@foreach($data as $row)
						<tr>
							<td>{{ $row['packing_slip_no_full'] }}</td>
							<td> {{ getSupplierData($row['supplier_id'])[0]['company_name'] }}</td>
							<td>{{ $row['total_mtr'] }}</td>
							<td>{{ $row['total_kg'] }}</td>
							<td>{{ $row['total_yd'] }}</td>
							<td>{{ $row['total_pcs'] }}</td>
							<td>{{ $row['no_of_rolls'] }}</td>
							<td>{{ $row['p_type'] }}</td>
							<td>{{ date('d M Y', strtotime($row['created_at'])) }}</td>
							<td>
								<a href="{{ route('stockpurchaselistdata', $row['_id']) }}" target="_blank">Detail</a>
								<a href="{{ route('stockpurchaselistsummary', $row['_id']) }}" target="_blank">Summary</a>
							</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				@endif
			</div>
		</div>
	</div>
</div>

<script>
   function seletctType(thisVal){
	   var query ='';
	   if(thisVal=='purchase'){
		    query ='?ptype=purchase';
	   }else if(thisVal=='sales_return'){
		   query ='?ptype=sales_return';
	   }else if(thisVal=='all'){
		   query ='?ptype=all';
	   }
	   location.href="{{ route('stockpurchaselist') }}"+query;
   }
</script> 

@endsection

