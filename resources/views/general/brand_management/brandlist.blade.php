@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Brand List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Brand List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">Brand List</li>
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
				<form method="POST" action="{{ route('search_brand') }}">
					@csrf
					<input type="text" class="form-control" placeholder="Enter Brand Name" name="brand_name" id="order_val" value="{{ $search_by }}" required>
					<button class="btn btn-primary" type="submit" name="order_search">Search</button>
					<a href="{{ route('brandlist') }}" class="btn btn-primary" name="reload">Reload</a>
				</form>
				<a class="btn btn-primary waves-effect waves-classic" style="float:right; margin-left:10px;" href="{{ route('exportbrand') }}"> <i class="icon md-plus" aria-hidden="true"></i>  Export All Brand</a>&nbsp;&nbsp;&nbsp;
				
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('add_brand') }}"> <i class="icon md-plus" aria-hidden="true"></i> Create New Brand </a>
			</div>
			<!--<div class="col-md-12 payment-heading-box"> 
				<button style="float:right;  margin-left:10px;" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary transition-3d-hover btn-rounded btn-margin">Import</button>
			</div>-->
		</div>
		<div class="panel">
			<div class="panel-body">
				@if(!empty($brands))
				<?php //pr($brands); die; ?>
				<table class="table table-bordered">
					<thead>
						<th class="bg-info" style="color:#fff !important;">Sr. No</th>
						<th class="bg-info" style="color:#fff !important;">Brand Name</th>
						<th class="bg-info" style="color:#fff !important;">Agent/Sales Person</th>
						<th class="bg-info" style="color:#fff !important;">Merchant Name</th>
						<th class="bg-info" style="color:#fff !important;">Created At</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
						@php 
						$i = ($brands->currentpage()-1)* $brands->perpage() 
						@endphp
						
						@if($brands->count())
						@foreach($brands as $brand)
						<?php //pr($brand); die;  ?>
						<tr>
							<td>{{ $i+1 }}</td>
							<td>{{ $brand['brand_name'] }}</td>
							<td>{{ getAgentMerchant($brand['sales_agent']) }}</td>
							<td>{{ getAgentMerchant(@$brand['merchant']) }}</td>
							<td>{{ DateFormate($brand['created_at']) }}</td>
							<td>
								<!--<a href="{{ route('articleview', $brand['_id']) }}" title='View' class="" target="_blank"><i class="icon md-eye" aria-hidden="true"></i></a>-->
								<a href="{{ route('brandedit', $brand['_id']) }}" title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>  
								<a onclick="return confirm('Are you sure you want to delete?');" title='Delete' style="display:none;" href="{{ route('branddelete', $brand['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a> 
							</td>
						</tr>
						 @php $i++ @endphp
						@endforeach
						@else
							<tr>
								<td colspan="8">Result not found.</td>
							</tr>
						@endif
					</tbody>
				</table>
				@endif
				 <span>{{ $brands->links() }}</span>
			</div>
		</div>
	</div>  
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<form method="post" action="{{ url('admin/BrandImport')}}" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Import New Brand</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</div>
				<div class="modal-body">
					<p>Import brand csv file only : <input type="file" required class="form-control" name="file" accept=".csv"></p>
				</div>
				<div class="modal-footer">
					<input type="submit" name="submit" value="Submit" class="btn btn-success">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<a href="https://themesofwp.com/ppfms/public/uploads/brand.csv" download>Download Sample File</a>
				</div>
			</div>
		</form>
		<!--<a href="{{ url('admin/downloadFile')}}" class="btn btn-danger" data-dismiss="modal">Download</a>-->
    </div>
</div>
@endsection