@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Stock Purchase Data List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Stock Purchase Data List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('stockpurchaselist') }}">Stock Purchase List</a></li>
			<li class="breadcrumb-item">Stock Purchase Data List</li>
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
			
			<div class="col-md-9 payment-heading-box">
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;"  href="{{ route('stockpurchasecreate') }}" > <i class="icon md-plus" aria-hidden="true"></i> Add Stock Purchase Data </a> &nbsp;&nbsp;&nbsp; 
				
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
				@if(!empty($data))
				<p><strong>Packing Slip No.:</strong> POS/PPS/{{ get_financial_year() }}/{{ $packing_slip_no }}</p>
				<table class="table table-bordered">
					<thead>
					    <th class="bg-info" style="color:#fff !important;">Article No.</th>
					    <th class="bg-info" style="color:#fff !important;">Color</th>
						<th class="bg-info" style="color:#fff !important;">Po No</th>
						<th class="bg-info" style="color:#fff !important;">Lot No</th>
						<th class="bg-info" style="color:#fff !important;">Roll No</th>						
						<th class="bg-info" style="color:#fff !important;">QTY</th>
						<th class="bg-info" style="color:#fff !important;">Unit</th>
						<th class="bg-info" style="color:#fff !important;">RIC</th>
						<th class="bg-info" style="color:#fff !important;">Purchase Date</th>
					</thead>
					<tbody id="fb_table_body">
					
						
				    	@foreach($data as $row)
						<?php 
							
						?>
						<tr>
							<td>{{ $row['item_data']['i_value'] }}</td>
							<td>{{ $row['color'] }}</td>
							<td>{{ $row['po_no'] }}</td>
							<td>{{ $row['lot_no'] }}</td>
							<td>{{ $row['roll_no'] }}</td>
							<td>{{ $row['qty'] }}</td>
							<td>{{ $row['unit'] }}</td>
							<td>
								{{ $row['RIC'] }}
							</td>
							<td>{{ date('d M Y', strtotime($row['created_at'])) }}</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				<?php /*?>
				<table class="table table-bordered">
					<thead>
					    <th class="bg-info" style="color:#fff !important;">Article No.</th>
					    <th class="bg-info" style="color:#fff !important;">Color</th>
						
						
											
						<th class="bg-info" style="color:#fff !important;">QTY</th>
						<th class="bg-info" style="color:#fff !important;">Unit</th>
					
						
					</thead>
					<tbody id="fb_table_body">
					
						<?php 
							$itmArr =array();
						?>
				    	@foreach($data as $row)
						<?php 
							
							if(!in_array($row['item'],$itmArr) && !in_array($row['item'],$itmArr[$row['item']][$row['color']])){
								array_push($itmArr,$row['item']);
								?>
								<tr style="background:#E8E8E8">
									<td colspan="8">{{ getItemName($row['item']) }}</td>
									
								</tr>
								
						<?php	}
						?>
						<tr>
							<td>{{ getItemName($row['item']) }}</td>
							<td>{{ $row['color'] }}</td>
						
						
						
							<td>{{ $row['qty'] }}</td>
							<td>{{ $row['unit'] }}</td>
							
							
						</tr>
						@endforeach
						
					</tbody>
				</table>
				<?php */?>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
