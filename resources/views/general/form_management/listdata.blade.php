@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'List Data')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">List Data</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item">List Data</li>
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
				<form method="POST" action="{{ route('searchstaff') }}">
					@csrf
					<select name="staff" id="staff_id" class="form-control" required>
						<option>--Select--</option>
						<option value="name" {{ @$search=='name'?'selected': '' }}>Name</option>
						<option value="mobile" {{ @$search=='mobile'?'selected': '' }}>Mobile</option>
						<option value="email" {{ @$search=='email'?'selected': '' }}>Email</option>
					</select>
					<input type="text" class="form-control" name="staff_code" id="order_val" value="{{ @$search_by }}" required>
					<button class="btn btn-primary" type="submit" name="order_search">Search</button>
					<a href="{{ route('stafflist') }}" class="btn btn-primary" name="reload">Reload</a>
				</form>
				<a class="btn btn-primary waves-effect waves-classic" style="float:right; margin-left:10px;" href="{{ route('exportstaff') }}"> <i class="icon md-plus" aria-hidden="true"></i>  Export All Staff</a>&nbsp;&nbsp;&nbsp;
				
				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('add_staff') }}"> <i class="icon md-plus" aria-hidden="true"></i> Create New Staff </a>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body">
				@if(!empty($staffs))
				<?php //pr($staffs); die; ?>
				<table class="table table-bordered">
					<thead>
						<th class="bg-info" style="color:#fff !important;">Sr. No</th>
						<th class="bg-info" style="color:#fff !important;">Name</th>
						<th class="bg-info" style="color:#fff !important;">Mobile</th>
						<th class="bg-info" style="color:#fff !important;">Email</th>
						<th class="bg-info" style="color:#fff !important;">Designation</th>
						<th class="bg-info" style="color:#fff !important;">Created At</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
						@php 
						$i = ($staffs->currentpage()-1)* $staffs->perpage(); 
						@endphp
						
						@if($staffs->count())
						@foreach($staffs as $staff)
						<?php //pr($staff); die; ?>
						<tr>
							<td>{{ $i+1 }}</td>
							<td>{{ $staff['name'] }}</td>
							<td>{{ $staff['mobile'] }}</td>
							<td>{{ $staff['email'] }}</td>
							<td>{{ implode(',',$staff['designation']) }}</td>
							<td>{{ DateFormate($staff['created_at']) }}</td>
							<td>
								<a href="{{ route('staffview', $staff['_id']) }}" title='View' class="btn btn-pure waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i> </a>
								
								<a href="{{ route('staffrole', $staff['_id']) }}" title='User Role' class="btn btn-pure waves-effect waves-classic"><i class="icon md-accounts" aria-hidden="true"></i> </a>
								
								<a href="{{ route('staffedit', $staff['_id']) }}"  title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>  
								
								<?php /* ?>
								<a onclick="return confirm('Are you sure you want to delete?');" title='Delete' href="{{ route('staffdelete', $staff['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a>
								<?php */ ?>
								
								
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
				
			</div>
			<span>{{ $staffs->links() }}</span>
		</div>
	</div>  
</div>
@endsection