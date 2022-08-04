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
		<?php //pr($viewStaff); pr($roleArr); die; 
			//pr($viewStaff['_id']); die;
			$oid = (array) $viewStaff['_id'];
			$staff_id = $oid['oid'];
			if(array_key_exists('role', $viewStaff)){
				$roleArr = $viewStaff['role'];
			}else{
				$roleArr = array();
			}
			
		 ?>
        <div class="panel">
			<div class="panel-body">
				<form autocomplete="off" enctype="multipart/form-data" class="form-horizontal" action="{{ route('addstaffrole') }}" method="POST">
					@csrf
					<input type="hidden" name="staff_id" value="{{ $staff_id }}">
					<table class="table table-bordered">
					<thead>
						<th class="bg-info" style="color:#fff !important; width:50px;">Sr. No</th>
						<th class="bg-info" style="color:#fff !important;">Module Name</th>
						<th class="bg-info" style="color:#fff !important;">User Permission</th>
					</thead>
					<tbody id="fb_table_body">
						<tr>
							<td>1</td>
							<td>Staff Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_staff',$roleArr)) checked @endif value="add_staff"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_staff',$roleArr)) checked @endif value="view_staff"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_staff',$roleArr)) checked @endif value="edit_staff"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_staff',$roleArr)) checked @endif value="delete_staff"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>2</td>
							<td>HSN Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_hsn',$roleArr)) checked @endif  value="add_hsn"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_hsn',$roleArr)) checked @endif  value="view_hsn"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_hsn',$roleArr)) checked @endif  value="edit_hsn"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_hsn',$roleArr)) checked @endif  value="delete_hsn"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>3</td>
							<td>Purchase Code Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_purchasecode',$roleArr)) checked @endif  value="add_purchasecode"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_purchasecode',$roleArr)) checked @endif  value="view_purchasecode"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_purchasecode',$roleArr)) checked @endif  value="edit_purchasecode"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_purchasecode',$roleArr)) checked @endif  value="delete_purchasecode"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>4</td>
							<td>Buyer Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_buyer',$roleArr)) checked @endif  value="add_buyer"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_buyer',$roleArr)) checked @endif  value="view_buyer"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_buyer',$roleArr)) checked @endif  value="edit_buyer"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_buyer',$roleArr)) checked @endif  value="delete_buyer"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>5</td>
							<td>Option Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_option',$roleArr)) checked @endif  value="add_option"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_option',$roleArr)) checked @endif  value="view_option"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_option',$roleArr)) checked @endif  value="edit_option"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_option',$roleArr)) checked @endif  value="delete_option"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>6</td>
							<td>PI Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_pi',$roleArr)) checked @endif  value="add_pi"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_pi',$roleArr)) checked @endif  value="view_pi"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_pi',$roleArr)) checked @endif  value="edit_pi"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_pi',$roleArr)) checked @endif  value="delete_pi"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>7</td>
							<td>PO Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_po',$roleArr)) checked @endif  value="add_po"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_po',$roleArr)) checked @endif  value="view_po"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_po',$roleArr)) checked @endif  value="edit_po"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_po',$roleArr)) checked @endif  value="delete_po"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>8</td>
							<td>Template Link Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_templatelink',$roleArr)) checked @endif  value="add_templatelink"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_templatelink',$roleArr)) checked @endif  value="view_templatelink"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_templatelink',$roleArr)) checked @endif  value="edit_templatelink"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_templatelink',$roleArr)) checked @endif  value="delete_templatelink"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>9</td>
							<td>Email Template Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_emailtemplate',$roleArr)) checked @endif  value="add_emailtemplate"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_emailtemplate',$roleArr)) checked @endif  value="view_emailtemplate"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_emailtemplate',$roleArr)) checked @endif  value="edit_emailtemplate"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_emailtemplate',$roleArr)) checked @endif  value="delete_emailtemplate"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>10</td>
							<td>Brand Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_brand',$roleArr)) checked @endif  value="add_brand"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_brand',$roleArr)) checked @endif  value="view_brand"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_brand',$roleArr)) checked @endif  value="edit_brand"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_brand',$roleArr)) checked @endif  value="delete_brand"> Delete 
							</td>
						</tr>
						
						<tr>
							<td>11</td>
							<td>Supplier Management</td>
							<td>
								<input type="checkbox" name="role[]" @if(in_array('add_supplier',$roleArr)) checked @endif  value="add_supplier"> Add &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('view_supplier',$roleArr)) checked @endif  value="view_supplier"> View  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('edit_supplier',$roleArr)) checked @endif  value="edit_supplier"> Edit  &nbsp;&nbsp;
								<input type="checkbox" name="role[]" @if(in_array('delete_supplier',$roleArr)) checked @endif  value="delete_supplier"> Delete 
							</td>
						</tr>
						
						<tr>
							<td colspan="2"></td>
							<td><button type="submit" name="submit" class="btn btn-success">Submit</button></td>
						</tr>
					</tbody>
				</table>
					
				</form>
			</div>
        </div><!-- End Panel Full Example -->
	</div>
</div><!-- End Page -->	

<script>
var roleArr = '<?php echo implode(',',$roleArr); ?>';
	console.log(roleArr)
	
	jQuery(document).ready( function () { 
		jQuery("input[name='add_purchasecode']").props('checked', true);
	})
	

setTimeout(function(){ 


}, 5000);
</script>

@endsection