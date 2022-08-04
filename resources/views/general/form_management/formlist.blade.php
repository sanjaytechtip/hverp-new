@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'Form List')

@section('pagecontent')

<div class="page">
<div class="new-width-design new-new-width-design">
    <div class="new-design-inner">    
    
    <div class="new-header-div-fot-btn">
	<div class="page-header">

		<h1 class="page-title">Form List</h1>

		<ol class="breadcrumb">

			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>

			<li class="breadcrumb-item">Form List</li>

		</ol>

	</div>
<div class="payment-heading-box"><a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('createform') }}"> <i class="icon md-plus" aria-hidden="true"></i> Create Form </a></div>
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

			<!--<div class="col-md-12 payment-heading-box">

				<a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('createform') }}"> <i class="icon md-plus" aria-hidden="true"></i> Create Form </a>

			</div>-->

		</div>

		<div class="panel">

			<div class="panel-body">

				@if(!empty($forms))

				<table class="table table-bordered">

					<thead>

						<th class="bg-info" style="color:#fff !important;">Sr. No</th>

						<th class="bg-info" style="color:#fff !important;">Form Name</th>

						<th class="bg-info" style="color:#fff !important;">Module Name</th>
						
						<th class="bg-info" style="color:#fff !important;">Table</th>
						
						<th class="bg-info" style="color:#fff !important;">Form Action</th>
						
						<th class="bg-info" style="color:#fff !important;">Data Action</th>

					</thead>

					<tbody id="fb_table_body">

					    @php

						$i=1;

						@endphp

						@foreach($forms as $form)

						<tr>

							<td>{{ $i }}</td>

							<td>{{ $form['form_name'] }}</td>

							<td>{{ $form['module_name'] }}</td>
							
							<td>{{ $form['table_name'] }}</td>

							<td>

							    <a href="{{ route('formedit', $form['_id']) }}"  title='Form Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a>

								<!--<a href="{{ route('formeditlist', $form['_id']) }}"  title='List' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-file" aria-hidden="true"></i> </a>-->

								<a href="{{ route('viewform',$form['_id'])}}" title='Form View' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i> </a>

								<a href="{{ route('formpreview',$form['_id'])}}"  title='Form Preview' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-assignment" aria-hidden="true"></i> </a>

								

								<!--<a onclick="return confirm('Are you sure you want to delete?');" title='Form Delete' href="{{ route('formdelete', $form['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a>-->

							</td>
							
							<td>
								
								<a href="{{ route('adddata',$form['_id'])}}"  title='Add Data' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-plus" aria-hidden="true"></i> </a>
								
							    								
								<a href="{{ route('listdata',$form['_id'])}}"  title='List Data' class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-file" aria-hidden="true"></i> </a>

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

			</div>

		</div>

	</div>  
</div></div>
</div>

@endsection