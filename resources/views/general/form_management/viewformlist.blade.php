@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'List '.$forms[0]['form_name'])
@section('pagecontent')
<style>
    .modal-open .select2-container {
  z-index: 9999999999;
}
</style>
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn @if($forms[0]['_id']=='619c7679d0490455f258a8f2') new-listdata-design  @endif">
        <div class="page-header">
          <h1 class="page-title">{{($forms[0]['form_name']) }} : List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">{{($forms[0]['form_name']) }} : List</li>
          </ol>
        </div>
        <div class="inlineform-wrap">
        <div class="custom-inline-form">
            <form method="POST" action="{{url('admin/listdata'.'/'.$forms[0]['_id'])}}" class="custom-inline-form">
            @csrf
            @if($forms[0]['_id']=='619c7679d0490455f258a8f2')
            @if(userMenuAccessRight(getUserAccess(),'adddata/'.$forms[0]['_id']))
            <div class="form-wrap form-wrap-submit"> <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ url('admin/adddata/'.$forms[0]['_id']) }}"> <i class="icon md-plus" aria-hidden="true"></i> Add </a> </div>
            @endif
            @if($forms[0]['import']=='import')
            <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" title="Import Data"><i class="icon md-upload" aria-hidden="true"></i></a></div>
            <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" data-toggle="modal" data-target="#myModal_update" href="javascript:void(0);" title="Update Data"><i class="icon md-upload" aria-hidden="true"></i></a></div>
            @endif
            @if($forms[0]['export']=='export')
            @if($forms[0]['_id']=='619c7679d0490455f258a8f2')
            <div class="form-wrap form-wrap-submit"><a class="btn btn-info waves-effect waves-classic small-buton" href="{{url('admin/export_data_master_item'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
            @else
            <div class="form-wrap form-wrap-submit"><a class="btn btn-info waves-effect waves-classic small-buton" href="{{url('admin/export_data_master'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
            @endif
            @endif
            </form>
            </div>
        </div>
          <div class="new-form-design">
            <form method="POST" action="{{url('admin/listdata'.'/'.$forms[0]['_id'])}}" class="custom-inline-form">
            @csrf
            <div class="new-form-box">
            <div class="form-wrap small-box"> 
            <label>Vendor SKU</label>
            <input class="form-control" type="text" name="vendor_sku" value="{{@$vendor_sku}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap"> 
            <label>Name</label>
            <input class="form-control" type="text" name="name" value="{{@$name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Synonyms</label>
            <input class="form-control" type="text" name="synonyms" value="{{@$synonyms}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Grade</label>
            <input class="form-control" type="text" name="grade" value="{{@$grade}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Brand</label>
            <input class="form-control" type="text" name="brand" value="{{@$brand}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Packing</label>
            <input class="form-control" type="text" name="packing_name" value="{{@$packing_name}}" autocomplete="off" placeholder=""></div>
            <div class="form-wrap small-box"> 
            <label>HSN Code</label>
            <input class="form-control" type="text" name="hsn_code" value="{{@$hsn_code}}" autocomplete="off" placeholder=""></div>
            </div>
            <div class="new-form-box new-form-box-radio-box">
            <div class="new-form-radio-box">
            <input type="radio" id="html" name="is_verified" @if($is_verified=='TRUE') checked @endif value="TRUE"><label for="html">Verified</label>
            </div>
            <div class="new-form-radio-box">
            <input type="radio" id="css" @if($is_verified=='FALSE') checked @endif name="is_verified" value="FALSE"><label for="css">Unverified</label>
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
            </div>
            
            <div class="form-wrap form-wrap-submit"> <a href="{{url('admin/listdata'.'/'.$forms[0]['_id'])}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
            @else
            <div class="form-wrap">
              <select name="tbl_column" required class="form-control" id="tbl_column">
                <option value="">-- Search By --</option>
            @foreach($finalArr as $key1=>$search_list)
            @if($search_list!='Action')
                <option @if($tbl_column==$key1) selected @endif value="{{$key1}}">{{$search_list}}</option>
            @endif
            @endforeach
              </select>
            </div>
            <div class="form-wrap">
              <input class="form-control" required type="text" name="i_name" value="{{@$i_name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
            </div>
            <div class="form-wrap form-wrap-submit"> <a href="{{url('admin/listdata'.'/'.$forms[0]['_id'])}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
            @if(userMenuAccessRight(getUserAccess(),'adddata/'.$forms[0]['_id']))
            <div class="payment-heading-box"> <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ url('admin/adddata/'.$forms[0]['_id']) }}"> <i class="icon md-plus" aria-hidden="true"></i> Add </a> </div>
            @endif
            
             @if($forms[0]['import']=='import')
            <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" title="Import Data"><i class="icon md-upload" aria-hidden="true"></i></a></div>
            @endif
            @if($forms[0]['export']=='export')
            @if($forms[0]['_id']=='619c7679d0490455f258a8f2')
          <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" href="{{url('admin/export_data_master_item'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
          @elseif($forms[0]['_id']=='6188f3acd049047e9b2f35d2')
          <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" href="{{url('admin/export_data_master_customer'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
          @elseif($forms[0]['_id']=='619b4690d0490447703e8b72')
          <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" href="{{url('admin/export_data_master_vendor'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
          @else
          <div class="form-wrap form-wrap-submit"><a class="btn btn-success waves-effect waves-classic small-buton" href="{{url('admin/export_data_master'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
          @endif
            
            @endif
            @endif
            </div>
            
          </form>
        </div>
      </div>
      <div class="page-content">
        <div class="row"> @if (\Session::has('success'))
          <div class="col-md-12">
            <div class="alert alert-success">
              <p>{{ \Session::get('success') }}</p>
            </div>
          </div>
          @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model" id="invoice_btn"></a> 
          <!-- Modal --> 
        </div>
        <div class="panel">
          <div class="panel-body">
            <table class="table table-bordered t1 list-data-table">
              <thead>
                @if($forms[0]['_id']!='619c7679d0490455f258a8f2')
                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Sr. No</th>
                @endif
                @foreach($finalArr as $key=>$list)
                <th class="bg-info @if($list!='Action') sort ta @endif" id="@if($list!='Action'){{$key}}@endif" style="@if($list=='Action')width: 10%;@endif color:#fff !important;text-align:center;">{{$list}}</th>
                @endforeach
                  </thead>
              <tbody>              
              @if(!empty($datalist->items()))
              @php 
              $i = $datalist->perPage() * ($datalist->currentPage() - 1)+ 1;
              @endphp
              @foreach($datalist as $user)
              <tr>
                @if($forms[0]['_id']!='619c7679d0490455f258a8f2')
                <td style="text-align:center;" class="white-space-normal">{{$i}}</td>
                @endif
                @foreach($finalArr as $key=>$list)
                @if($key!='action')
                <td style="text-align:center;" class="white-space-normal"> @if($user[$key]!='disable')
                  @if($key=='user_role_erp')
                  {{getUserRole($user['user_role_erp'],$user['desig_multiple'])}}
                  @elseif($key=='attendance_type')
                  @if($user['attendance_type']=='enable' && !empty($user['emp_code'])) <i class="icon md-check green-tick" aria-hidden="true"></i> @endif
                  @elseif($key=='overtime_pay')
                  @if($user['overtime_pay']=='yes') <i class="icon md-check green-tick" aria-hidden="true"></i> @elseif($user['overtime_pay']=='no' && !empty($user['emp_code'])) <i class="icon md-close red-cross" aria-hidden="true"></i> @endif            
                  @elseif($key=='updated_on')
                  <span id="updated_on-{{$user['_id']}}">{{globalDateformatItem($user['updated_on'])}}</span>
                  @elseif($key=='mrp')
                  <a title='Price Edit' class="price_edit price_edit-{{$user['_id']}}" cus="{{$user['mrp']}}" id="{{$user['_id']}}" data-toggle="modal" data-target="#myModal_mrp" href="javascript:void(0);">{{$user['mrp']}}</a>
                  @elseif($key=='company_name')
                  <a id="{{$user['_id']}}">{{$user['company_name']}}</a>
                  @elseif($key=='vendor_sku')
                  <a id="{{$user['_id']}}" class="vendor-sku" data-toggle="modal" data-target="#myModal_vendor_sku" href="javascript:void(0);">{{$user['vendor_sku']}}</a>
                  @elseif($key=='register_type')
                  @if($user['register_type']==1) Register @else Unregister @endif
                  @elseif($key=='block_unblock')
                  @if($user['block_unblock']==1) Block @else Unblock @endif
                  @else
                  @php
                  $str = str_replace('_',' ',$user[$key]);
                  if(strlen($str)>100){echo substr($str,0,100).'... ...';}else{echo $str;}
                  @endphp
                  @endif
                  @elseif($user['attendance_type']=='disable' && !empty($user['emp_code'])) <i class="icon md-close red-cross" aria-hidden="true"></i> @endif </td>
                @endif
                @if($key=='action')
                <td class="center-assign">
                @if(userMenuAccessRight(getUserAccess(),'editdata/'.$forms[0]['table_name']))  
                <a title='Edit' href="{{url('admin/editdata'.'/'.$forms[0]['table_name'].'/'.$user['_id'].'/'.$forms[0]['_id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i></a> 
                @endif
                
                @if(userMenuAccessRight(getUserAccess(),'deletedata/'.$forms[0]['table_name']))
                <a title='Delete' onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/deletedata'.'/'.$forms[0]['table_name'].'/'.$user['_id'].'/'.$forms[0]['_id'])}}" class="btn btn-pure btn-danger icon"><i class="icon md-delete" aria-hidden="true"></i></a>
                @endif
                @if($forms[0]['_id']=='6180d37bd04904276c0bf042' && $user['fms_role']!='admin')
                <a href="{{route('userassign',$user['_id'])}}" title="Assign Pages"><i class="site-menu-icon md-assignment" aria-hidden="true"></i></a>
                @endif
                @if($forms[0]['_id']=='619c7679d0490455f258a8f2')
                <a href="javascript:void(0);" title="Opening Stock" data-toggle="modal" data-target="#myModal_opening_stock-{{$user['_id']}}"><i class="site-menu-icon site-menu-icon fas far fa-box-open" aria-hidden="true"></i></a>
                    <div id="myModal_opening_stock-{{$user['_id']}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg"> 
  <!-- Modal content-->
  <form id="open-stock-{{$user['_id']}}" method="post">
    @csrf
    <input type="hidden" name="item_id" value="{{$user['_id']}}">
    <input type="hidden" name="item_name" value="{{$user['name']}}">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">OPENING STOCK</h4>
      </div>
      <input type="hidden" name="id" value="{{$forms[0]['_id']}}">
      <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
      
      
      <div class="modal-body col-md-12 custom-popup-form">
      <div class="form-row">
        <div class="form-group col-md-6">
        <label for="store">Select Store:</label>
        <select class="form-control store_room" id="store-room-{{$user['_id']}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="room"aria-hidden="true">
        </select>
        </div>
        <div class="form-group col-md-6">
        <label for="store">Rack No:</label>
        <select class="form-control rack_no" data-plugin="select2" id="rack-no-{{$user['_id']}}" data-placeholder="Type name" data-minimum-input-length="1" name="rack_no"aria-hidden="true">
        </select>
        </div>
       </div>
        
        <div class="form-row">
         <div class="form-group col-md-6">
        <label for="store">Batch No:</label>
        <input class="form-control" name="batch_no" id="batch_no">
        </div>
         <div class="form-group col-md-6">        
        <label for="store">Mfg Date:</label>
        <input type="text" readonly autocomplete="off" id="mfg_date" value="{{date('m/d/Y')}}" required class="form-control date_with_time small_field_custom datefield hasDatepicker valid" name="mfg_date" aria-required="true" aria-invalid="false">
        </div>
        </div>
        
        <div class="form-row">
         <div class="form-group col-md-6">
        <label for="store">Expiry Date:</label>
        <input type="text" readonly autocomplete="off" id="expiry_date" value="{{date('m/d/Y')}}" required class="form-control date_with_time small_field_custom datefield hasDatepicker valid" name="expiry_date" aria-required="true" aria-invalid="false">
        </div>
         <div class="form-group col-md-6">
        <label for="quantity">Quantity:</label>
        <input type="number" autocomplete="off" min="0" cus="{{$user['_id']}}" id="qty_pop-{{$user['_id']}}" required class="form-control qty-pop" name="quantity">        
        </div>
        </div>
        
        
        <div class="form-row">
         <div class="form-group col-md-6">
        <label for="quantity">MRP:</label>
        <input type="number" autocomplete="off" min="0" cus="{{$user['_id']}}" id="mrp_pop-{{$user['_id']}}" value="{{$user['mrp']}}" required class="form-control mrp-pop" name="mrp"></div>
         <div class="form-group col-md-6">
        <label for="loc_central">Local/Central:</label>
        <select class="form-control" name="loc_cen" id="loc-cen-{{$user['_id']}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
          <option value="">-- Select --</option>
          <option value="LOCAL">LOCAL</option>
          <option value="CENTRAL">CENTRAL</option>
        </select>
        </div>
        </div>
        
        
        <div class="form-row">
         <div class="form-group col-md-6">
        <label for="quantity">Rate:</label>
        <input type="number" autocomplete="off" min="0" cus="{{$user['_id']}}" id="rate_pop-{{$user['_id']}}" required class="form-control rate-pop" name="rate">
        </div>
         <div class="form-group col-md-6">
        <label for="quantity">Amount:</label>
        <input type="number" autocomplete="off" min="0" id="amount_pop-{{$user['_id']}}" required class="form-control" name="amount">
      </div>
        </div>
      </div>
      
      
      <div class="modal-footer">
        <button type="button" cus="{{$user['_id']}}" class="btn btn-success btn-save-open-stock">Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </form>
</div>

                    </div>
                @endif
                </td>
                @endif
                @endforeach </tr>
              @php 
              $i = $i+1; 
              @endphp
              @endforeach
              @else
              <tr>
                <td colspan="8">No record found.</td>
              </tr>
              @endif
                </tbody>
            </table>
          </div>
          <div class="pagination-wrap"> {!! $datalist->links() !!} </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="myModal_mrp" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">MRP Update</h4>
      </div>
      <form method="post" action="{{url('admin/mrp_data_update')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="form_id" id="form_id" value="{{$forms[0]['_id']}}">
        <input type="hidden" name="table_name" id="table_name" value="{{$forms[0]['table_name']}}">
        <input type="hidden" name="id" value="" id="mrp_id">
        <div class="modal-body col-md-12">
          <input type="number" required name="mrp_update" id="mrp_update" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" name="importSubmit" class="btn btn-success update-mrp">Update</button>
          <button type="button" class="btn btn-danger close-data" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Data</h4>
      </div>
      <form method="post" action="{{url('admin/import_data_master')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$forms[0]['_id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12">
          <input type="file" required accept=".csv" name="result_file" class="form-control">
          @if($forms[0]['_id']=='619c7679d0490455f258a8f2')
          <small style="float: right;"><a href="{{url('admin/export_data_master_item'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/2')}}">Download Sample File</a></small>
          @else
          <small style="float: right;"><a href="{{url('admin/export_data_master'.'/'.$forms[0]['_id'].'/'.$forms[0]['table_name'].'/2')}}">Download Sample File</a></small>
          @endif
        </div>
        <div class="modal-footer">
          <button type="submit" name="importSubmit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="myModal_update" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Data</h4>
      </div>
      <form method="post" action="{{url('admin/import_data_master_update')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$forms[0]['_id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12">
          <input type="file" required accept=".csv" name="result_file" class="form-control">
          <small style="float: right;"><a href="{{url('admin/export_data_master_update')}}">Download Sample File</a></small>
        </div>
        <div class="modal-footer">
          <button type="submit" name="importSubmit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>




<div id="myModal_company_dept" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Company Department Details</h4>
      </div>
        <input type="hidden" name="id" value="{{$forms[0]['_id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12" id="results1">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
<div id="myModal_vendor_sku" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Item Details</h4>
      </div>
        <input type="hidden" name="id" value="{{$forms[0]['_id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12">
         <table class="table table-bordered">
             <tbody id="results">
                 
             </tbody>
         </table> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 

@endsection















 @section('custom_validation_script') 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
<script src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script> 
<script>

$(function(){

    var table = $('.t1');

    @php

    $myArray = array();

    foreach ($finalArr as $key=>$list){ 

        if ($key!="action"){

         $myArray[] = '#'.$key;

        }

    }

    @endphp

    $('{{@implode( ', ', $myArray )}}')

        .wrapInner('<span title=""/>')

        .each(function(){

            

            var th = $(this),

                thIndex = th.index(),

                inverse = false;

            

            th.click(function(){

                

                table.find('td').filter(function(){

                    

                    return $(this).index() === thIndex;

                    

                }).sortElements(function(a, b){

                    

                    return $.text([a]) > $.text([b]) ?

                        inverse ? -1 : 1

                        : inverse ? 1 : -1;

                    

                }, function(){

                    

                    // parentNode is the element we want to move

                    return this.parentNode; 

                    

                });

                

                inverse = !inverse;

                if(inverse==true){

				$('.ta').addClass('sort');

				$('.ta').removeClass('dsc');

				$('.ta').removeClass('asc');

				$(this).addClass('asc');

				$(this).removeClass('sort');

				$(this).removeClass('dsc');

				}else if(inverse==false){

				$('.ta').addClass('sort');

				$('.ta').removeClass('dsc');

				$('.ta').removeClass('asc');

				$(this).addClass('dsc');

				$(this).removeClass('sort');

				$(this).removeClass('asc');

				}

                    

            });

                

        });

});

</script> 
<script>
    $(function(){
        $(document).on('click','.price_edit',function(){
            var price = $(this).attr('cus');
            var mrp_id = $(this).attr('id');
            $('#mrp_update').val(price);
            $('#mrp_id').val(mrp_id);
        });
        
        $(document).on('click','.update-mrp',function(){
            var mrp_update = $('#mrp_update').val();
            var mrp_id = $('#mrp_id').val();
            var date_update = "{{globalDateformatItem(date('Y-m-d H:i:s'))}}";
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                if(mrp_update!='' && mrp_id!=''){
                    $.ajax({
                    url: "{{ url('admin/mrp_data_update') }}" ,
                    type: "POST",
                    data: {mrp_update:mrp_update,mrp_id:mrp_id},
                    success: function( response ) {
                    //alert(response); return false;
                    if(response!='')
                    {
                    $('.price_edit-'+mrp_id).attr('cus',mrp_update);
                    $('.price_edit-'+mrp_id).html(mrp_update);
                    $('.close-data').trigger('click');
                    $('#updated_on-'+mrp_id).html(date_update);
                    }
                    }
                    }); 
                }
        
        
        });
    });
</script>

<script>
    $(function(){
        $(document).on('click','.vendor-sku',function(){
           var id = $(this).attr('id');
           $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
           if(id!='')
           {
            $('#loader').show();
            $.ajax({
            url: "{{ url('admin/ajax_item_details') }}" ,
            type: "POST",
            data: {id:id},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            $('#results').html(response);
            $('#loader').hide();
            }
            }
            });   
           }
        });
    });
</script>

<script>
    $(function(){
        $(document).on('click','.company-dept',function(){
           var id = $(this).attr('id');
           $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
           if(id!='')
           {
            $('#loader').show();
            $.ajax({
            url: "{{ url('admin/ajax_department_details') }}" ,
            type: "POST",
            data: {id:id},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            $('#results').html(response);
            $('#loader').hide();
            }
            }
            });   
           }
        });
    });
</script>
@if($forms[0]['_id']=='619c7679d0490455f258a8f2')
<script>
	$(function () { 
		var roomArr = '<?php print_r(getAllRoom()); ?>';
		roomArr = JSON.parse(roomArr);
		//console.log(buyerArr);
		var roomData = $.map(roomArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var roomHtml='<option value="">--Select--</option>';
		for(var i=0; i<roomData.length; i++){
			roomHtml+='<option value="'+roomData[i].data+'">'+roomData[i].value+'</option>';
		}
		$('.store_room').html(roomHtml);
		//console.log(buyerHtml);
	});
</script>
<script>
	$(function () { 
		var rackArr = '<?php print_r(getAllRack()); ?>';
		rackArr = JSON.parse(rackArr);
		//console.log(buyerArr);
		var rackData = $.map(rackArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var rackHtml='<option value="">--Select--</option>';
		for(var i=0; i<rackData.length; i++){
			rackHtml+='<option value="'+rackData[i].data+'">'+rackData[i].value+'</option>';
		}
		$('.rack_no').html(rackHtml);
		//console.log(buyerHtml);
	});
</script>
<script>
$('.date_with_time').datepicker({
dateFormat: 'dd-mm-yy',
changeMonth: true,
changeYear: true,
});
</script> 
<script>
    $(function(){
         $(document).on('click','.btn-save-open-stock',function(){
             var id = $(this).attr('cus');
             var myform = document.getElementById("open-stock-"+id);
             var fd = new FormData(myform );
                 $('.btn-save-open-stock').prop('disabled', true);
                $.ajax({
                url: "{{url('admin/submit_opening_balance')}}",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (msg) {
                //alert(msg); return false;
                if(msg==1){
                $('#open-stock-'+id)[0].reset();
                $('#store-room-'+id).html("").trigger("change");
                $('#rack-no-'+id).html("").trigger("change");
                $('#loc-cen-'+id).html("").trigger("change");
                alert('Opening Stock Successfully add.');
                $('.btn-save-open-stock').prop('disabled', false);
                }
                }
                });
         }); 
    });
</script>
<script>
$(function(){
   $(document).on('keyup change','.qty-pop',function(){
     var id = $(this).attr('cus');
     var qty = $('#qty_pop-'+id).val();
     var rate = $('#rate_pop-'+id).val();
     if(qty!='' && rate!='')
        {
        $('#amount_pop-'+id).val(eval(qty*rate));
        }else{
        $('#amount_pop-'+id).val('');    
        }
   });
   $(document).on('keyup change','.rate-pop',function(){
     var id = $(this).attr('cus');
     var qty = $('#qty_pop-'+id).val();
     var rate = $('#rate_pop-'+id).val();
     if(qty!='' && rate!='')
        {
        $('#amount_pop-'+id).val(eval(qty*rate));
        }else{
        $('#amount_pop-'+id).val('');    
        }
   });
});
</script>
@endif
@endsection