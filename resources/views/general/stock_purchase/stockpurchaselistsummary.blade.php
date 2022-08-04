@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Stock Purchase Summary')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Stock Purchase Summary</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('stockpurchaselist') }}">Stock Purchase List</a></li>
			<li class="breadcrumb-item">Stock Purchase Summary</li>
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
			
			<div class="col-md-12 payment-heading-box">
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('stockpurchasecreate') }}" > <i class="icon md-plus" aria-hidden="true"></i> Add Stock Purchase Data </a>
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
								{{ getItemName($row['item']) }} {{ $row['color'] }} {{ $row['po_no'] }} {{ $row['lot_no'] }} {{ $row['roll_no'] }} {{ $row['qty'] }}
							</td>
							<td>{{ date('d M Y', strtotime($row['created_at'])) }}</td>
						</tr>
						@endforeach
						
					</tbody>
				</table>
				<hr/>
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
							
							if(!in_array($row['item_data']['i_value'],$itmArr)){
								array_push($itmArr,$row['item_data']['i_value']);
								?>
								<tr style="background:#E8E8E8;text-align: center;">
									<td colspan="4">
										<b>{{ $row['item_data']['i_value'] }}</b>
									</td>
									
								</tr>
								
						<?php	}
						?>
						<tr>
							<td>{{ $row['item_data']['i_value'] }}</td>
							<td>{{ $row['color'] }}</td>
							<td>{{ $row['qty'] }}</td>
							<td>{{ $row['unit'] }}</td>	
						</tr>
						@endforeach
						
					</tbody>
				</table>
				@endif
				<?php pr($itmArr);?>
			</div>
		</div>
	</div>
</div>
@endsection
