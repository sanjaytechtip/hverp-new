@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Group of Company List')
@section('pagecontent')
<?php //pr($elements);die;?>
<div class="page">
  <div class="new-width-design">
    <div class="new-design-inner">
      <div class="page-header">
        <h1 class="page-title">Group of Company Management</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"> <a href="{{route('dashboard')}}">Home</a> </li>
          <li class="breadcrumb-item">Group of Company Management</li>
        </ol>
      </div>
      <div class="page-content">
        <div class="row"> @if (\Session::has('success'))
          <div class="col-md-12">
            <div class="alert alert-success">
              <p>{{ \Session::get('success') }}</p>
            </div>
          </div>
          @endif </div>
        <div class="modal fade" id="invoice_model" role="dialog">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order Search</h4>
              </div>
              <div class="modal-body">
                <div id="order_found"></div>
              </div>
            </div>
          </div>
        </div>
		
        <div class="inlineform-wrap">
          <form method="POST" action="{{ route('companylist') }}" class="custom-inline-form">
            @csrf
            <div class="form-wrap">
              <input type="text" class="form-control" placeholder="Enter Group Name" value="{{ $search_by }}" name="name" id="name" >
            </div>
			<div class="form-wrap">
			  <select class="form-control" data-plugin="select2" id="company_name" name="company_name" placeholder="Assign FMS" style="width:200px"></select>
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary" type="submit"  name="order_search">Search</button>
            </div>
            <div class="form-wrap form-wrap-submit"> <a href="{{ route('companylist') }}" class="btn btn-primary" name="reload">Reload</a> </div>
            <div class="form-wrap form-wrap-submit"> @if(userMenuAccessRight(getUserAccess(),'addcompany')) <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('addcompany') }}"> <i class="icon md-plus" aria-hidden="true"></i>Create New Group of Company </a> @endif </div>
          </form>
        </div>
      </div>
      <?php //echo "<pre>"; print_r($elements); die; ?>
      <div class="panel itemlist-wrap companylist-wrap">
        <div class="panel-body">
        @if(!empty($elements))
          <div class="fix-table-new">
            <table class="table table-bordered companylist-table" cellspacing="0">
              <thead>
                <tr class="table-head">
                  <th style="text-align:center;" class="name-th" align="center" id="heder_name">Sr. No</th>
                  <th style="text-align:center;" class="name-th" align="center" id="heder_name">Group Name</th>
                  <th style="text-align:center;" class="name-th" align="center" id="heder_name">Company Name</th>
                  <th style="text-align:center;" class="action-th" align="center" id="heder_action">Action</th>
                </tr>
              </thead>
			  <?php //pr($elements);die;?>
              <tbody>              
              <?php               
              $i = 0;
              foreach($elements as $element){				  
			  $allBuyer = json_decode(getAllBuyer(),true);
			 // pr($allBuyer);die;
			  $element = (array)$element; ?>
              <tr class="cm_main_row">
                <td class="js_dynamic_estock_child" data-th="Sr. No">{{ $i+1 }}</td>
                <td class="js_dynamic_estock_child" data-th="Group Name">{{ $element['group_name'] }}</td>
                <td class="js_dynamic_estock_child white-space-normal" data-th="Company Name"> 
				<?php
				$selectedBuyer = '';
				if(!empty($element['g_company_name'])){
					$g_company_name = explode(',', $element['g_company_name']);
					foreach($g_company_name as $row){
						$selectedBuyer .=  $allBuyer[$row].', ';
					}
					echo rtrim($selectedBuyer, ',');	
				}
				?>
				</td>
                <td data-th="Action" data-th="Action"><div class="order-action"> <a href="{{route('companyedit',$element['id'])}}" title="Edit"><i class="site-menu-icon md-edit" aria-hidden="true"></i></a>                    
                    <a href="{{ route('companydelete',$element['id'] ) }}" onclick="return confirm('Are you sure you want to delete?');" title="Remove"><i class="site-menu-icon md-delete" aria-hidden="true"></i></a></div></td>
              </tr>
              <?php $i++; }?>
              
                </tbody>
            </table>
          </div>
          @endif          
        </div>
		<div class="mt-10">{!! $elements->links('pagination::bootstrap-5') !!} </div>
      </div>
      <!-- End Panel Table Tools -->
      </div>
  </div>
</div>
@endsection

@section('custom_validation_script')
<script>
	$(function () { 
		var custArr = '<?php print_r(getAllBuyer()); ?>';
		custArr = JSON.parse(custArr);
		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
		var custHtml='<option value="" data-select2-id="">-Select Company-</option>';
		for(var i=0; i<custData.length; i++){
			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
		}
		$('#company_name').html(custHtml);
	});
</script>

@endsection