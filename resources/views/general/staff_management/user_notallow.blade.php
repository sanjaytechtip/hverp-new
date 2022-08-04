@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'View Staff')
@section('pagecontent')
<div class="page">
	<div class="page-content container-fluid">
		@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div>
		@endif
        <!-- Panel Full Example -->
		<?php //pr($viewStaff); die; ?>
        <div class="panel">
			<div class="panel-body">
				<h1>Permission denied</h1>
				</p>You have not permission to access this module, please contact to administrator.</p>
			</div>
        </div><!-- End Panel Full Example -->
	</div>
</div><!-- End Page -->	
@endsection