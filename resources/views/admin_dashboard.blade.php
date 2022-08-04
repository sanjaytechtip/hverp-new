@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Dashboard')
@section('pagecontent')

<div class="page creat-fms-box">
	<div class="page-content container-fluid">
		<h2>{{ __('Welcome to ERP-') }} {{ Auth::user()->full_name }}</h2>
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card"></div>
			</div>
		</div>
	</div>
</div>

@endsection
