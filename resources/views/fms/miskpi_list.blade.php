@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'KPI List')
@section('pagecontent')
<div class="page">
	<div class="page-content"> 
	@if(session()->has('success'))
		<div class="alert alert-success text-center">
		  <p>{{ session()->get('success') }}</p>
		</div>
		<br />
    @endif    
    @if(session()->has('error'))
    <div class="alert alert-danger text-center">
       <p>{{ session()->get('error') }}</p>
    </div>
    <br />
    @endif     
		<!-- Panel Table Add Row -->
		<div class="panel">
			<header class="panel-heading pull-right"><br/>
				<h1 class="panel-title text-center">{{ __('KPI List') }}</h1>
				<br/>
			</header>
			<div class="panel-body">
				<div class="responsive-table">
					
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="bg-info" style="color:#fff !important;">Sr. No</th>
								<th class="bg-info" style="color:#fff !important;">KPI Name</th>
								<th class="bg-info" style="color:#fff !important;">FMS</th>
								<th class="bg-info" style="color:#fff !important;">User Name</th>
								<th class="bg-info" style="color:#fff !important;">Action</th>
							</tr>
						</thead>
						<tbody id="fb_table_body">	
							<?php 
								//pr($allkpi);
								$i=1;
							?>
							@foreach($allkpi as $kpi)
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $kpi['kpi_name'] }}</td>
								<td>{{ getFmsNameById($kpi['fms']) }}</td>
								<td>{{ getAgentDetails($kpi['user']) }}</td>
								<td>
									 
									<a href="{{ route('viewkpi', $kpi['_id']) }}" title="View" class="btn btn-pure waves-effect waves-classic waves-effect waves-classic">
									<i class="icon md-eye" aria-hidden="true"></i> </a>
									
									<a href="{{ route('editkpi', $kpi['_id']) }}" title="Edit" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i> </a> 
									
								</td>
							</tr>
							<?php $i++; ?>
							@endforeach
						</tbody>
					</table>
					<div class="col-md-2"><a href="{{ route('miskpi') }}" class="btn btn-block btn-primary waves-effect waves-classic waves-effect waves-classic">Create KPI</a></div>
				</div>			
			</div>
		</div>
	</div>
</div>
@endsection