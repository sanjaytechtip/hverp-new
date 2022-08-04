@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Dropdown List')
@section('pagecontent')
<div class="page dropdown-box">
<div class="page-header">
        <h1 class="page-title">{{ __('Dropdown List') }}</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item">{{ __('Dropdown List') }}</li>
        </ol>
      </div>
      
  <div class="page-content"> 
    <div class="payment-heading-box">
            <form method="POST" action="{{ url('admin/dropdown_search') }}">
              @csrf
              <input type="text" class="form-control" placeholder="Enter Dropdown Name" value="{{ @$search_by }}" name="dropdown_name" id="order_val" required>
              <button class="btn btn-primary" type="submit"  name="order_search">Search</button>
              <a href="{{ route('dropdownlisting') }}" class="btn btn-primary" name="reload">Reload</a>
            </form>
            <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('dropdowncreate') }}"> <i class="icon md-plus" aria-hidden="true"></i>Create New Dropdown List </a> </div>
    <!-- Panel Table Add Row -->
    
    <div class="panel">
      
      <div class="panel-body">
        <div class="row "> @if (\Session::has('success'))
          <div class="col-md-12">
            <div class="alert alert-success">
              <p>{{ \Session::get('success') }}</p>
            </div>
          </div>
          @endif
          
        </div>
        @if(!empty($dropdownlists))
        <table class="table table-bordered table-hover table-striped" cellspacing=	"0" id="exampleAddRow1">
          <thead>
            <tr>
              <th class="bg-info" style="color:#fff !important;">Sr. No</th>
              <th class="bg-info" style="color:#fff !important;">Dropdown Name</th>
              <th class="bg-info" style="color:#fff !important;">Key</th>
              <th class="bg-info" style="color:#fff !important;">Created at</th>
              <th class="bg-info" style="color:#fff !important;">Actions</th>
            </tr>
          </thead>
          <tbody>
          
          @php 
          
          $i = ($dropdownlists->currentpage()-1)* $dropdownlists->perpage() 
          
          @endphp	   
          
          @foreach($dropdownlists as $dropdownlist)
          <?php //echo "<pre>"; print_r($dropdownlist); die; ?>
          <tr class="gradeA">
            <td>{{ $i+1 }}</td>
            <td><a href="javascript:void(0)" data-toggle="modal" data-target="#list_{{ $dropdownlist['_id'] }}" title="Click to see list details">{{ $dropdownlist['dropdown_name'] }}</a>
              <div class="modal fade modal-info show dropdown-popup" id="list_{{ $dropdownlist['_id'] }}" aria-labelledby="exampleModalInfo" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                      <h4 class="modal-title">{{ $dropdownlist['dropdown_name'] }}</h4>
                    </div>
                    <div class="modal-body"> {!! getDropdownListingById($dropdownlist['_id']) !!} </div>
                    
                    <!--<div class="modal-footer">

												<button type="button" class="btn btn-default btn-pure waves-effect waves-classic" data-dismiss="modal">Close</button>

											</div> --> 
                    
                  </div>
                </div>
              </div></td>
            <td>{{ $dropdownlist['key'] }}</td>
            <td>{{ DateFormate($dropdownlist['created_at']) }}</td>
            <td class="actions"><a href="{{ route('dropdownedit', $dropdownlist['_id']) }}" title='Edit' class="btn btn-pure btn-success icon  waves-effect waves-classic" ><i class="icon md-edit" aria-hidden="true"></i> </a> <a onclick="return confirm('Are you sure you want to delete?');" style="display:none;" title='Delete' href="{{ route('dropdowndelete', $dropdownlist['_id']) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a></td>
          </tr>
          @php $i++ @endphp
          
          @endforeach
            </tbody>
          
        </table>
        @endif <span>{{ $dropdownlists->links() }}</span> </div>
    </div>
    
    <!-- End Panel Table Add Row --> 
    
  </div>
</div>
@endsection