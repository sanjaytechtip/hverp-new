@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Users List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">User Management</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item">User Management</li>
        </ol>
      </div>
	  
	  <div class="page-content">
				@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div>
					@endif
                <!-- Panel Table Tools -->
		<div class="row">
			<div class="col-md-3">
				<a class="btn btn-primary" href="{{ route('adduser')}}">Create New User</a>
				<br><br>
			</div>
		</div>
						
        <div class="panel">
          <div class="panel-body">
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="exampleAddRow">
              <thead>
                <tr>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Phone</th>
				 
				  <th>Created</th>
				  <th>Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Full Name</th>
                  <th>Email</th>
				  <th>phone</th>
				  <th>Created</th>
				  <th>Action</th>
                </tr>
              </tfoot>
              <tbody>
				@foreach($users as $user)
					<tr>
					  <td>{{$user->name}}</td>
					  <td>{{$user->email}}</td>
					  <td>{{$user->phone}}</td>
					  <td>{{$user->created_at}}</td>
					  <td>
						<a href="{{route('useredit',$user->id)}}" class="btn btn-warning btn-xs">Edit</a>
						<a href="{{route('userdelete',$user->id)}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to delete this item?');">Delete</a>
					  </td>
					</tr>
				@endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- End Panel Table Tools -->
      </div>
    </div>
    <!-- End Page -->
@endsection
