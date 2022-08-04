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
        <div class="panel">
          <header class="panel-heading">
            <h3 class="panel-title">User</h3>
          </header>
          <div class="panel-body">
            <table class="table table-hover dataTable table-striped w-full" id="exampleTableTools">
              <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last name</th>
                  <th>Username</th>
                  <th>Email</th>
				  <th>Phone No.</th>
				  <th>User Role</th>
				  <th>Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>First Name</th>
                  <th>Last name</th>
                  <th>Username</th>
                  <th>Email</th>
				  <th>Phone No.</th>
				  <th>User Role</th>
				  <th>Action</th>
                </tr>
              </tfoot>
              <tbody>
			  @foreach($users as $user)
                <tr>
                  <td>{{$user->first_name}}</td>
                  <td>{{$user->last_name}}</td>
                  <td>{{$user->username}}</td>
                  <td>{{$user->email}}</td>
				  <td>{{$user->phone}}</td>
                  <td>{{$user->user_role}}</td>
				  <td>{{$user->image}}</td>
                  <td>
                   <!--<a href="{{route('useredit',$user->id)}}" class="" title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>-->
				  
				  <a href="{{route('useredit',$user->id)}}" class="btn btn-warning btn-block btn-xs">Edit</a>
					<br>
				  <form action="{{route('userdelete',$user->id)}}" method="post">
							@csrf
							<input name="_method" type="hidden" value="DELETE">
							<button class="btn btn-danger btn-block  btn-xs" type="submit" onclick="return confirm('Are you sure to delete this item?');">Delete</button>
				  </form></td>
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
