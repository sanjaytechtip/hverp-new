@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Create Left Menu')
@section('pagecontent')
<div class="page">
      <div class="page-content container-fluid">
		<h2>{{ __('Create Left Menu') }}</h2>
		<div class="row justify-content-center">
					@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div><br />
					@endif
        <div class="col-md-12">
            <div class="card">
                
                <div class="card-body">
                    <form method="POST" action="{{ route('storemenuname') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="menu_name" class="col-md-4 col-form-label text-md-right">{{ __('Menu Name') }}</label>

                            <div class="col-md-4">
                                <input id="menu_name" type="text" class="form-control{{ $errors->has('menu_name') ? ' is-invalid' : '' }}" name="menu_name" value="{{ old('menu_name') }}">

                                @if ($errors->has('menu_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('menu_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
						
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  
</div>

@endsection