@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'HSN Code List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">HSN List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">HSN List</li>
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
				<!--<form onsubmit="event.preventDefault(); findPaymentOrder();" method="POST" id="findpayment">-->
				<form method="POST" action="{{ url('admin/search') }}">
					@csrf
					<input type="text" class="form-control" placeholder="Enter Hsn Code" value="{{ $search_by }}" name="hsn_code" id="order_val" required> 					
					<button class="btn btn-primary" type="submit"  name="order_search">Search</button>
					<a href="{{ route('hsnlist') }}" class="btn btn-primary" name="reload">Reload</a>
				</form>
				
				<a class="btn btn-primary waves-effect waves-classic" style="float:right; margin-left:10px;" href="{{ route('export_hsn') }}"> Export All Hsn </a>&nbsp;&nbsp;&nbsp;
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('add_hsn') }}"> <i class="icon md-plus" aria-hidden="true"></i> Create New Hsn </a>
				
				
			
			</div>
			
		</div>
		<div class="panel">
			<div class="panel-body">
				@if(!empty($hsncode))
				<?php //pr($hsncode); die; ?>
				<table class="table table-bordered">
					<thead>
						<th class="bg-info" style="color:#fff !important;">Sr. No</th>
						<th class="bg-info" style="color:#fff !important;">HSN Code</th>
						<th class="bg-info" style="color:#fff !important;">GSTN %</th>
						<th class="bg-info" style="color:#fff !important;">Created At</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
					
						@php 
						$i = ($hsncode->currentpage()-1)* $hsncode->perpage() 
						@endphp
						@if($hsncode->count())
						@foreach($hsncode as $row)
						<?php //pr($row); die; ?>
						<tr>
							<td>{{ $i+1 }}</td>
							<td>{{ $row['hsn_code'] }}</td>
							<td>{{ $row['gstn'] }}</td>
							<td>{{ DateFormate($row['created_at']) }}</td>
							<td>
								<!--<a href="" class="btn btn-info waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i> View</a> -->
								<a href="{{ route('hsnedit', $row['_id']) }}" title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>  
								
								<a onclick="return confirm('Are you sure you want to delete?');" style="display:none;" title='Delete' href="{{ route('hsndelete', $row['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a>
								
							</td>
						</tr>
						
						@php $i++ @endphp
						@endforeach
						@else
						<tr>
							<td colspan="5">Result not found.</td>
						</tr>
						@endif
					</tbody>
				</table>
				@endif
				<span>{{ $hsncode->links() }}</span>
			</div>
		</div>
	</div>  
</div>
@endsection