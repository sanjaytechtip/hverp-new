@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'View Article')
@section('pagecontent')
<div class="page">
	<div class="page-content container-fluid">
		@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div>
		@endif
        <!-- Panel Full Example -->
		<?php //pr($viewArticle); die; ?>
        <div class="panel">
			<div class="panel-body">
				<form autocomplete="off" enctype="multipart/form-data" class="form-horizontal">
					@csrf
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Article No:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewArticle['article_no'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Description:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewArticle['description'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">HSN Code:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewArticle['hsn_code'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">GSM:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewArticle['gsm'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Width:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewArticle['width'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Unit:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewArticle['unit'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Other:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewArticle['other'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Color:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewArticle['color'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Created At:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewArticle['created_at'] }}</p>
						</div>
					</div>
				</form>
			</div>
        </div><!-- End Panel Full Example -->
	</div>
</div><!-- End Page -->	
@endsection