@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'List '.$forms[0]['form_name'])
@section('pagecontent')
@php
//pr($datalist); die;
@endphp
<style>
.modal-open .select2-container {
    z-index: 9999999999;
}
</style>
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <?php //pr(getAllRoom()); ?>
      <div class="new-header-div-fot-btn  @if($forms[0]['id']==35) new-listdata-design  @endif">
        <div class="page-header">
          <h1 class="page-title">{{($forms[0]['form_name']) }} : List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">{{($forms[0]['form_name']) }} : List</li>
          </ol>
        </div>
        <div class="inlineform-wrap">
          <div class="custom-inline-form">
            <form method="POST" action="{{url('admin/listdata'.'/'.$forms[0]['id'])}}"
                            class="custom-inline-form">
              @csrf
              @if($forms[0]['id']==35)
              <div class="form-wrap form-wrap-submit"> <a
                                    class="btn btn-primary waves-effect waves-classic" style="float:right;"
                                    href="{{ url('admin/adddata/'.$forms[0]['id']) }}"> <i class="icon md-plus"
                                        aria-hidden="true"></i> Add </a> </div>
              @if($forms[0]['is_Import']==1)
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton" data-toggle="modal"
                                    data-target="#myModal" href="javascript:void(0);" title="Import Data"><i
                                        class="icon md-upload" aria-hidden="true"></i></a></div>
              <!-- <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton" data-toggle="modal"
                                    data-target="#myModal_update" href="javascript:void(0);" title="Update Data"><i
                                        class="icon md-upload" aria-hidden="true"></i></a></div> --> 
              @endif
              @if($forms[0]['is_export']==1)
              @if($forms[0]['id']==35)
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-info waves-effect waves-classic small-buton"
                                    href="{{url('admin/export_data_master_item'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/0')}}"
                                    title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
              @else
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-info waves-effect waves-classic small-buton"
                                    href="{{url('admin/export_data_master'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/0')}}"
                                    title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
              @endif
              @endif
            </form>
          </div>
        </div>
        <div class="new-form-design">
          <form method="POST" action="{{url('admin/listdata'.'/'.$forms[0]['id'])}}"
                        class="custom-inline-form">
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
                <input class="form-control" type="text" name="packing_name" value="{{@$packing_name}}" autocomplete="off" placeholder="">
              </div>
              <div class="form-wrap small-box">
                <label>HSN Code</label>
                <input class="form-control" type="text" name="hsn_code" value="{{@$hsn_code}}" autocomplete="off" placeholder="">
              </div>
            </div>
            <div class="new-form-box new-form-box-radio-box">
              <div class="new-form-radio-box">
                <input type="radio" id="html" name="is_verified" @if($is_verified=='TRUE' ) checked @endif value="TRUE">
                <label for="html">Verified</label>
              </div>
              <div class="new-form-radio-box">
                <input type="radio" id="css" @if($is_verified=='FALSE' ) checked @endif name="is_verified" value="FALSE">
                <label for="css">Unverified</label>
              </div>
              <div class="form-wrap form-wrap-submit">
                <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
              </div>
              <div class="form-wrap form-wrap-submit"> <a href="{{url('admin/listdata'.'/'.$forms[0]['id'])}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
              @else
              <div class="form-wrap">
                <select name="tbl_column" required class="form-control" id="tbl_column">
                  <option value="">-- Search By --</option>                  
                                    @foreach($finalArr as $key1=>$search_list)
                                    @if($search_list!='Action')                                    
                  <option @if($tbl_column==$key1) selected @endif value="{{$key1}}">{{$search_list}} </option>                   
                                    @endif
                                    @endforeach                                
                </select>
              </div>
              <div class="form-wrap">
                <input class="form-control" required type="text" name="i_name" value="{{@$i_name}}" autocomplete="off" placeholder="">
              </div>
              <div class="form-wrap form-wrap-submit"><button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button></div>
              <div class="form-wrap form-wrap-submit"> <a
                                    href="{{url('admin/listdata'.'/'.$forms[0]['id'])}}"
                                    class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i
                                        class="icon md-refresh" aria-hidden="true"></i></a> </div>
              @if(userMenuAccessRight(getUserAccess(),'adddata/'.$forms[0]['id']))
              <div class="form-wrap form-wrap-submit"> <a class="btn btn-primary waves-effect waves-classic"
                                    style="float:right;" href="{{ url('admin/adddata/'.$forms[0]['id']) }}"> <i
                                        class="icon md-plus" aria-hidden="true"></i> Add </a> </div>
              @endif
              
              @if($forms[0]['import']=='import')
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton" data-toggle="modal"
                                    data-target="#myModal" href="javascript:void(0);" title="Import Data"><i
                                        class="icon md-upload" aria-hidden="true"></i></a></div>
              @endif
              @if($forms[0]['export']=='export')
              @if($forms[0]['id']=='35')
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton"
                                    href="{{url('admin/export_data_master_item'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/0')}}"
                                    title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
              @elseif($forms[0]['id']=='6188f3acd049047e9b2f35d2')
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton"
                                    href="{{url('admin/export_data_master_customer'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/0')}}"
                                    title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
              @elseif($forms[0]['id']=='619b4690d0490447703e8b72')
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton"
                                    href="{{url('admin/export_data_master_vendor'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/0')}}"
                                    title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
              @else
              <div class="form-wrap form-wrap-submit"><a
                                    class="btn btn-success waves-effect waves-classic small-buton"
                                    href="{{url('admin/export_data_master'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/0')}}"
                                    title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
              @endif
              
              @endif
              @endif </div>
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
          @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model"
                        id="invoice_btn"></a> 
          <!-- Modal --> 
        </div>
        <div class="panel">
          <div class="panel-body">
            <table class="table table-bordered t1 list-data-table">
              <thead>
              
              @if($forms[0]['id']!=35)
              
                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Sr. No </th>
                @endif
                @foreach($finalArr as $key=>$list)
                <th class="bg-info @if($list!='Action') sort ta @endif"
                                    id="@if($list!='Action'){{$key}}@endif"
                                    style="@if($list=='Action')width: 10%;@endif color:#fff !important;text-align:center;"> {{$list}}</th>
                @endforeach
                  </thead>
              <tbody>
              
              @if(!empty($datalist))
              @php
              $i=($datalist->currentpage()-1)* $datalist->perpage();
              @endphp
              @foreach($datalist as $user)
              <tr> @if($forms[0]['id']!=35)
                <td style="text-align:center;" class="white-space-normal">{{$i+1}}</td>
                @endif
                @foreach($finalArr as $key=>$list)
                @if($key!='action')
                <td style="text-align:center;" class="white-space-normal"> @if($user->$key!='disable')
                  @if($key=='user_role_erp')
                  {{getUserRole($user->user_role_erp,$user->desig_multiple)}}
                  @elseif($key=='attendance_type')
                  @if($user->attendance_type=='enable' && !empty($user->emp_code)) <i class="icon md-check green-tick" aria-hidden="true"></i> @endif
                  @elseif($key=='overtime_pay')
                  @if($user->overtime_pay=='yes') <i class="icon md-check green-tick" aria-hidden="true"></i> @elseif($user->overtime_pay=='no' && !empty($user->emp_code)) <i class="icon md-close red-cross" aria-hidden="true"></i> @endif
                  @elseif($key=='updated_on') <span id="updated_on-{{$user->id}}">{{globalDateformatItem($user->updated_at)}}</span> @elseif($key=='mrp') <a title='Price Edit' class="price_edit price_edit-{{$user->id}}" gst="{{getGSThsnTax($user->hsn_code)}}" cus="{{$user->mrp}}" id="{{$user->id}}" data-toggle="modal" data-target="#myModal_mrp" href="javascript:void(0);">{{$user->mrp}}</a> @elseif($key=='list_price') <a title='List Price Edit' class="list_price_edit list_price_edit-{{$user->id}}" gst_price="{{getGSThsnTax($user->hsn_code)}}" cus="{{$user->list_price}}" id="{{$user->id}}" data-toggle="modal" data-target="#myModal_list_price" href="javascript:void(0);">{{$user->list_price}}</a> @elseif($key=='company_name') <a id="{{$user->id}}">{{$user->company_name}}</a> @elseif($key=='vendor_sku') <a id="{{$user->id}}" class="vendor-sku" data-toggle="modal" data-target="#myModal_vendor_sku" href="javascript:void(0);">{{$user->vendor_sku}}</a> @elseif($key=='register_type')
                  @if($user->register_type==1) Register @else Unregister @endif
                  @elseif($key=='block_unblock')
                  @if($user->block_unblock==1) Block @else Unblock @endif
                  @elseif($key=='unit' && $forms[0]['id']==28)
                  {{getUnitNameFromId($user->unit)}}
                  @elseif($key=='type_of_pack' && $forms[0]['id']==32)
                  {{getPackNameFromId($user->type_of_pack)}}
                  @elseif($key=='department' && $forms[0]['id']==14)
                  {{getDepartmentName($user->department)}}
                  @else
                  @php
                  $str = str_replace('_',' ',$user->$key);
                  if(strlen($str)>100){echo substr($str,0,100).'... ...';}else{echo $str;}
                  @endphp
                  @endif
                  @elseif($user->attendance_type=='disable' && !empty($user->emp_code)) <i class="icon md-close red-cross" aria-hidden="true"></i> @endif </td>
                @endif
                @endforeach
                <td class="center-assign"><div class="action-wrap"> @if($forms[0]['id']==11) <span class="btn btn-pure btn-success icon  waves-effect waves-classic"><i title=" @if($user->status==1) Unblock @else Block @endif" style="font-size:16px; cursor:pointer;" id="{{$user->id}}" cus="@if($user->status==1) 0 @else 1 @endif" class="fas fa-check-circle @if($user->status==1) bluetext @else blacktext @endif status-update" aria-hidden="true"></i></span> @endif <a title='Edit' href="{{url('admin/editdata'.'/'.$forms[0]['table_name'].'/'.$user->id.'/'.$forms[0]['id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i></a> <a title='Delete' onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/deletedata'.'/'.$forms[0]['table_name'].'/'.$user->id.'/'.$forms[0]['id'])}}" class="btn btn-pure btn-danger icon"><i class="icon md-delete" aria-hidden="true"></i></a> @if($forms[0]['id']==11) 
                  <!-- <a href="{{route('userassign',$user->id)}}" title="Assign Pages"><i class="site-menu-icon md-assignment" aria-hidden="true"></i></a> --> 
                  @endif
                  @if($forms[0]['id']==35) <a href="javascript:void(0);" title="Add Opening Stock" data-toggle="modal" data-target="#myModal_opening_stock-{{$user->id}}"><i class="site-menu-icon site-menu-icon fas far fa-box-open" aria-hidden="true"></i></a>
                  <div id="myModal_opening_stock-{{$user->id}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg"> 
                      <!-- Modal content-->
                      <form id="open-stock-{{$user->id}}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" value="{{$user->id}}">
                        <input type="hidden" name="item_name" value="{{$user->name}}">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">OPENING STOCK</h4>
                          </div>
                          <input type="hidden" name="id" value="{{$forms[0]['id']}}">
                          <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
                          <div class="modal-body col-md-12 custom-popup-form">
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="loc_central">Local/Central:</label>
                                <select class="form-control" name="loc_cen" id="loc-cen-{{$user->id}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                                  <option value="">-- Select --</option>
                                  <option value="LOCAL">LOCAL</option>
                                  <option value="CENTRAL">CENTRAL</option>
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Select Store:</label>
                                <select class="form-control store_room" id="store-room-{{$user->id}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="room"aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Rack No:</label>
                                <select class="form-control rack_no" data-plugin="select2" id="rack-no-{{$user->id}}" data-placeholder="Type name" data-minimum-input-length="1" name="rack_no" aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Batch No:</label>
                                <input class="form-control" name="batch_no" id="batch_no-{{$user->id}}">
                              </div>
                            </div>
                            
                            <div class="form-row">
                              <div class="form-group col-md-4">
                                <label for="store">Mfg Date:</label>
                                <input type="text" readonly autocomplete="off" id="mfg_date-{{$user->id}}" value="{{date('d/m/Y')}}" required class="form-control date_with_time small_field_custom datefield" name="mfg_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="store">Expiry Date:</label>
                                <input type="text" readonly autocomplete="off" id="expiry_date-{{$user->id}}" value="{{date('d/m/Y')}}" required class="form-control date_with_time small_field_custom datefield" name="expiry_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="store">Retest Date:</label>
                                <input type="text" readonly autocomplete="off" id="retest_date-{{$user->id}}" class="form-control date_with_time small_field_custom datefield" name="retest_date" aria-required="true" aria-invalid="false">
                              </div>
                            </div>
                           
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="quantity">Quantity:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$user->id}}" id="qty_pop-{{$user->id}}" required class="form-control qty-pop" name="quantity">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">Rate:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$user->id}}" id="rate_pop-{{$user->id}}" required class="form-control rate-pop" name="rate">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">MRP:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$user->id}}" id="mrp_pop-{{$user->id}}" value="{{$user->mrp}}" required class="form-control mrp-pop" name="mrp">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">Amount:</label>
                                <input type="number" autocomplete="off" min="0" id="amount_pop-{{$user->id}}" required class="form-control" name="amount">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="quantity">COA:</label>
                                <input type="text" autocomplete="off" cus="{{$user->id}}" id="coa_pop-{{$user->id}}" required class="form-control coa-pop" name="coa">
                              </div>
                              <div class="form-group col-md-6">
                                <label for="quantity">COA PDF Upload:</label>
                                <input type="file" cus="{{$user->id}}" id="coa_pop-{{$user->id}}" accept="application/pdf" class="form-control coa-pop-upload" name="coa_file">
                              </div>
                             
                            </div>
                            
                          </div>
                          <div class="modal-footer">
                            <button type="button" cus="{{$user->id}}" class="btn btn-success btn-save-open-stock">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <a title="Opening Stock" href="{{ url('admin/item_details/?i_id=')}}{{$user->id}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i></a> @endif </div></td>
              </tr>
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
          <div class="mt-10">{!! $datalist->onEachSide(0)->links('pagination::bootstrap-5') !!}</div>
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
        <input type="hidden" name="form_id" id="form_id" value="{{$forms[0]['id']}}">
        <input type="hidden" name="table_name" id="table_name" value="{{$forms[0]['table_name']}}">
        <input type="hidden" name="id" value="" id="mrp_id">
        <input type="hidden" name="gst_tax" value="" id="gst_tax">
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
<div id="myModal_list_price" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">List Price Update</h4>
      </div>
      <form method="post" action="{{url('admin/list_price_data_update')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="form_id" id="form_id" value="{{$forms[0]['id']}}">
        <input type="hidden" name="table_name" id="table_name" value="{{$forms[0]['table_name']}}">
        <input type="hidden" name="id" value="" id="list_price_id">
        <input type="hidden" name="gst_tax_list" value="" id="gst_tax_list">
        <div class="modal-body col-md-12">
          <input type="number" required name="list_price_update" id="list_price_update" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" name="importSubmit" class="btn btn-success update-list-price">Update</button>
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
      @if($forms[0]['id']==35)
      <form method="post" action="{{url('admin/import_data_master_items')}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{url('admin/import_data_master')}}" enctype="multipart/form-data">
        @endif
        @csrf
        <input type="hidden" name="id" value="{{$forms[0]['id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12">
          <input type="file" required accept=".csv" name="result_file" class="form-control">
          @if($forms[0]['id']=='35') <small style="float: right;"><a
                                href="{{url('admin/export_data_master_item'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/2')}}">Download
          Sample File</a></small> @else <small style="float: right;"><a
                                href="{{url('admin/export_data_master'.'/'.$forms[0]['id'].'/'.$forms[0]['table_name'].'/2')}}">Download
          Sample File</a></small> @endif </div>
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
        <input type="hidden" name="id" value="{{$forms[0]['id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12">
          <input type="file" required accept=".csv" name="result_file" class="form-control">
          <small style="float: right;"><a href="{{url('admin/export_data_master_update')}}">Download Sample
          File</a></small> </div>
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
      <input type="hidden" name="id" value="{{$forms[0]['id']}}">
      <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
      <div class="modal-body col-md-12" id="results1"> </div>
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
      <input type="hidden" name="id" value="{{$forms[0]['id']}}">
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
$(function() {
    var table = $('.t1');

    @php

    $myArray = array();

    foreach($finalArr as $key => $list) {

        if ($key != "action") {

            $myArray[] = '#'.$key;

        }

    }

    @endphp

    $('{{@implode( ', ', $myArray )}}')

        .wrapInner('<span title=""/>')

        .each(function() {



            var th = $(this),

                thIndex = th.index(),

                inverse = false;



            th.click(function() {



                table.find('td').filter(function() {



                    return $(this).index() === thIndex;



                }).sortElements(function(a, b) {



                    return $.text([a]) > $.text([b]) ?

                        inverse ? -1 : 1

                        :
                        inverse ? 1 : -1;



                }, function() {



                    // parentNode is the element we want to move

                    return this.parentNode;



                });



                inverse = !inverse;

                if (inverse == true) {

                    $('.ta').addClass('sort');

                    $('.ta').removeClass('dsc');

                    $('.ta').removeClass('asc');

                    $(this).addClass('asc');

                    $(this).removeClass('sort');

                    $(this).removeClass('dsc');

                } else if (inverse == false) {

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
$(function() {
    $(document).on('click', '.price_edit', function() {
        var price = $(this).attr('cus');
        var mrp_id = $(this).attr('id');
        $('#mrp_update').val(price);
        $('#mrp_id').val(mrp_id);
        $('#gst_tax').val($(this).attr('gst'));
    });

    $(document).on('click', '.list_price_edit', function() {
        var price = $(this).attr('cus');
        var list_price_id = $(this).attr('id');
        $('#list_price_update').val(price);
        $('#list_price_id').val(list_price_id);
        $('#gst_tax_list').val($(this).attr('gst_price'));
    });

    $(document).on('click', '.update-list-price', function() {
        var list_price_update = $('#list_price_update').val();
        var list_price_id = $('#list_price_id').val();
        var price_edit = $('.price_edit-'+list_price_id).html();
        var date_update = "{{globalDateformatItem(date('Y-m-d H:i:s'))}}";
        var gst_tax_list = $('#gst_tax_list').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(list_price_update!='' && price_edit!='' && list_price_id!='' )
        {
          var mrp_price_gst = (price_edit*gst_tax_list)/100;
          var final_check_price = eval(price_edit)-eval(mrp_price_gst);
          //alert(final_check_price+"-"+list_price_update);
          if(final_check_price<list_price_update)
          {
            alert("Maximum Net Rate should be "+eval(final_check_price));
            return false;
          }else{
            $.ajax({
                url: "{{ url('admin/list_price_data_update') }}",
                type: "POST",
                data: {
                  list_price_update: list_price_update,
                  list_price_id: list_price_id
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
                        $('.list_price_edit-' + list_price_id).attr('cus', list_price_update);
                        $('.list_price_edit-' + list_price_id).html(list_price_update);
                        $('.close-data').trigger('click');
                        $('#updated_on-' + list_price_id).html(date_update);
                    }
                }
            }); 
          }

        }else if (list_price_update != '' && list_price_id != '' && (price_edit='' || price_edit==0)) {
            $.ajax({
                url: "{{ url('admin/list_price_data_update') }}",
                type: "POST",
                data: {
                  list_price_update: list_price_update,
                  list_price_id: list_price_id
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
                        $('.list_price_edit-' + list_price_id).attr('cus', list_price_update);
                        $('.list_price_edit-' + list_price_id).html(list_price_update);
                        $('.close-data').trigger('click');
                        $('#updated_on-' + list_price_id).html(date_update);
                    }
                }
            });
        }


    });

    $(document).on('click', '.update-mrp', function() {
        var mrp_update = $('#mrp_update').val();
        var mrp_id = $('#mrp_id').val();
        var gst = $('#gst_tax').val();
        var list_price_edit = $('.list_price_edit-'+mrp_id).html();
        var date_update = "{{globalDateformatItem(date('Y-m-d H:i:s'))}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if(list_price_edit!='' && mrp_update!='' && mrp_id!='' )
        {
          var list_price_gst = (list_price_edit*gst)/100;
          var final_check = eval(list_price_gst)+eval(list_price_edit);
          if(final_check>mrp_update){
            alert("Minimum MRP should be "+eval(final_check));
            return false;
          }else{
            $.ajax({
                url: "{{ url('admin/mrp_data_update') }}",
                type: "POST",
                data: {
                    mrp_update: mrp_update,
                    mrp_id: mrp_id
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
                        $('.price_edit-' + mrp_id).attr('cus', mrp_update);
                        $('.price_edit-' + mrp_id).html(mrp_update);
                        $('.close-data').trigger('click');
                        $('#updated_on-' + mrp_id).html(date_update);
                    }
                }
            });
          }
        }else if (mrp_update != '' && mrp_id != '' && (list_price_edit=='' || list_price_edit==0)) {
            $.ajax({
                url: "{{ url('admin/mrp_data_update') }}",
                type: "POST",
                data: {
                    mrp_update: mrp_update,
                    mrp_id: mrp_id
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
                        $('.price_edit-' + mrp_id).attr('cus', mrp_update);
                        $('.price_edit-' + mrp_id).html(mrp_update);
                        $('.close-data').trigger('click');
                        $('#updated_on-' + mrp_id).html(date_update);
                    }
                }
            });
        }


    });
});
</script> 
<script>
$(function() {
    $(document).on('click', '.vendor-sku', function() {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (id != '') {
            $('#loader').show();
            $.ajax({
                url: "{{ url('admin/ajax_item_details') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
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
$(function() {
    $(document).on('click', '.company-dept', function() {
        var id = $(this).attr('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (id != '') {
            $('#loader').show();
            $.ajax({
                url: "{{ url('admin/ajax_department_details') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
                        $('#results').html(response);
                        $('#loader').hide();
                    }
                }
            });
        }
    });
});
</script> 
@if($forms[0]['id']=='35') 
<script>
$(function() {
    var roomArr = '<?php print_r(getAllRoom()); ?>';
    roomArr = JSON.parse(roomArr);
    var roomData = $.map(roomArr, function(value, key) {
        return {
            value: value,
            data: key
        };
    });
    //console.log(buyerData);
    var roomHtml = '<option value="">--Select--</option>';
    for (var i = 0; i < roomData.length; i++) {
        roomHtml += '<option value="' + roomData[i].data + '">' + roomData[i].value + '</option>';
    }
    $('.store_room').html(roomHtml);
    //console.log(buyerHtml);
});
</script> 
<script>
$(function() {
    var rackArr = '<?php print_r(getAllRack()); ?>';
    rackArr = JSON.parse(rackArr);
    //console.log(buyerArr);
    var rackData = $.map(rackArr, function(value, key) {
        return {
            value: value,
            data: key
        };
    });
    //console.log(buyerData);
    var rackHtml = '<option value="">--Select--</option>';
    for (var i = 0; i < rackData.length; i++) {
        rackHtml += '<option value="' + rackData[i].data + '">' + rackData[i].value + '</option>';
    }
    $('.rack_no').html(rackHtml);
    //console.log(buyerHtml);
});
</script> 
 
<script>
$(function() {
    $(document).on('click', '.btn-save-open-stock', function() {
        var id = $(this).attr('cus');
        var myform = document.getElementById("open-stock-" + id);
        var fd = new FormData(myform);
        //$('.btn-save-open-stock').prop('disabled', true);
        var loc_cen = $('#loc-cen-'+id).val();
        var store_room = $('#store-room-'+id).val();
        var batch_no = $('#batch_no-'+id).val();
        var rack_no = $('#rack-no-'+id).val();
        var qty_pop = $('#qty_pop-'+id).val();
        var mrp_pop = $('#mrp_pop-'+id).val();
        var rate_pop = $('#rate_pop-'+id).val();
        var amount_pop = $('#amount_pop-'+id).val();

        if(loc_cen=='')
        {
          alert('Please select the Local/Central.');
          return false;
        }
        else if(store_room=='')
        {
          alert('Please select the Store Room.');
          return false;
        }
        else if(batch_no=='')
        {
          alert('Please enter the batch no.');
          return false;
        }
        else if(rack_no=='')
        {
          alert('Please select the rack no.');
          return false;
        }
        else if(qty_pop=='')
        {
          alert('Please enter the quantity.');
          return false;
        }
        else if(mrp_pop=='')
        {
          alert('Please enter the MRP.');
          return false;
        }
        else if(rate_pop=='')
        {
          alert('Please enter the Rate.');
          return false;
        }
        else if(amount_pop=='')
        {
          alert('Please enter the Amount.');
          return false;
        }else{
        $.ajax({
            url: "{{url('admin/submit_opening_balance')}}",
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(msg) {
                //alert(msg); return false;
                if (msg == 1) {
                    $('#open-stock-' + id)[0].reset();
                    $('#store-room-' + id).html("").trigger("change");
                    $('#rack-no-' + id).html("").trigger("change");
                    $('#loc-cen-' + id).html("").trigger("change");
                    alert('Opening Stock Successfully add.');
                    //$('.btn-save-open-stock').prop('disabled', false);
					window.location.reload(true);
                }
            }
        });
      }
    });
});
</script> 
<script>
$(function() {
    $(document).on('keyup change', '.qty-pop', function() {
        var id = $(this).attr('cus');
        var qty = $('#qty_pop-' + id).val();
        var rate = $('#rate_pop-' + id).val();
        if (qty != '' && rate != '') {
            $('#amount_pop-' + id).val(eval(qty * rate));
        } else {
            $('#amount_pop-' + id).val('');
        }
    });
    $(document).on('keyup change', '.rate-pop', function() {
        var id = $(this).attr('cus');
        var qty = $('#qty_pop-' + id).val();
        var rate = $('#rate_pop-' + id).val();
        if (qty != '' && rate != '') {
            $('#amount_pop-' + id).val(eval(qty * rate));
        } else {
            $('#amount_pop-' + id).val('');
        }
    });
});
</script> 
@endif
@if($forms[0]['id']=='11') 
<script>
    $(function(){
                $(document).on('click','.status-update',function(){
                  var id = $(this).attr('id');
                  var status = $(this).attr('cus');
                  if(status==1){var type="Unblock";}else{var type = "Block";}
                  if(id!='' && status!='')
                  {
                    $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    var verify = confirm("Are you sure you want to "+type+" this user?");
                    if(verify)
                    {
                        $.ajax({
                        url: "{{ url('admin/ajax_user_status_update') }}",
                        type: "POST",
                        data: {id:id,status:status},
                        success: function(response) {
                        //alert(response); return false;
                        if (response != '') {
                        window.location.reload(true);
                        }
                        }
                        });
                    }
                  }
                });
    });
</script> 
@endif
<link href="{{url('/public/css/jquery.datetimepicker.css')}}" rel="stylesheet">
<script type="text/javascript" src="{{url('/public/js/jquery.datetimepicker.full.js')}}"></script>
<script>
jQuery(document).ready(function() {

    jQuery('.date_with_time').datetimepicker({

        timepicker: false,

        format: 'd/m/Y',

        autoclose: true,

        minView: 2

    });

});
</script>

<script>
$(function(){
  $(document).on('blur change','.rate-pop',function(){
  var mrp_id = $(this).attr('cus');
  var mrp_val = $('#mrp_pop-'+mrp_id).val();
  var rate_val = $('#rate_pop-'+mrp_id).val();
  if(mrp_id!='')
  {
  $.ajaxSetup({
  headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  }); 

  $.ajax({
  url: "{{ url('admin/ajax_get_item_tax_rate') }}",
  type: "POST",
  data: {mrp_id:mrp_id},
  success: function(response) {
  //alert(response); return false;
  if (response != '') {
    if(mrp_val!='' && rate_val!='')
  {
    var tax_rate_val = eval((response*mrp_val)/100);
    //alert(mrp_val+"-"+eval(mrp_val-tax_rate_val)+"-"+rate_val);
    if(eval(mrp_val-tax_rate_val)<=eval(rate_val))
    {
      alert("Maximum Net Rate should be "+eval(mrp_val-tax_rate_val));
      $('#rate_pop-'+mrp_id).val('');
      return false;
    }
  }
  }
  }
  });

  }
  
  
});
});
</script>
<script>
$(function(){
  $(document).on('blur change','.mrp-pop',function(){
  var mrp_id = $(this).attr('cus');
  var mrp_val = $('#mrp_pop-'+mrp_id).val();
  var rate_val = $('#rate_pop-'+mrp_id).val();
  if(mrp_id!='')
  {
  $.ajaxSetup({
  headers: {
  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  }); 

  $.ajax({
  url: "{{ url('admin/ajax_get_item_tax_rate') }}",
  type: "POST",
  data: {mrp_id:mrp_id},
  success: function(response) {
  //alert(response); return false;
  if (response != '') {
    if(mrp_val!='' && rate_val!='')
  {
    //debugger;
    var tax_rate_val = eval((response*rate_val)/100);
    var rate_plus_tax = eval(rate_val)+tax_rate_val;
    //alert(mrp_val+"-"+rate_plus_tax+"-"+rate_val);
    if(eval(mrp_val-tax_rate_val)<=eval(rate_val))
    {
      alert("Minimum MRP should be "+eval(eval(rate_val)+tax_rate_val));
      $('#mrp_pop-'+mrp_id).val('');
      return false;
    }
  }
  }
  }
  });

  }
  
  
});
});
</script>
@endsection