@extends('layouts.fms_admin_layouts')
@if($_GET['type']==2)
@section('pageTitle', 'Stock Inward : List')
@elseif($_GET['type']==1)
@section('pageTitle', 'Opening Stock : List')
@else
@section('pageTitle', 'Opening Stock : List')
@endif
@section('pagecontent')
@php
//pr($datalist); die;
@endphp
<script src="{{url('public/theme/global/vendor/jquery/jquery.js')}}"></script>
<style>
.modal-open .select2-container {
    z-index: 9999999999;
}
</style>

<div class="page">
    <div class="new-width-design new-new-width-design">
        <div class="new-design-inner">
		<?php //pr(getAllRoom()); ?>
            <div class="new-header-div-fot-btn">
                <div class="page-header">
                    @if($_GET['type']==2)
                    <h1 class="page-title">Stock Inward : List</h1>
                    @elseif($_GET['type']==1)
                    <h1 class="page-title">Opening Stock : List</h1>
                    @else
                    <h1 class="page-title">Opening Stock : List</h1>
                    @endif
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        @if($_GET['type']==2)
                        <li class="breadcrumb-item">Stock Inward: List</li>
                        @elseif($_GET['type']==1)
                        <li class="breadcrumb-item">Opening Stock : List</li>
                        @else
                        <li class="breadcrumb-item">Opening Stock : List</li>
                        @endif
                    </ol>
                </div>
				<div class="inlineform-wrap">
  <div class="custom-inline-form">
    <?php
						$idata = (array) json_decode(getAllItems());
						//pr($idata); die;
						$i_id='';
						if(!empty($_GET) && array_key_exists('i_id',$_GET) && $_GET['i_id']!=''){
							$i_id=$_GET['i_id'];
						}
						$itemArr = array();
					?>
    <form method="GET" action="" class="custom-inline-form">
        @if(!empty($_GET) && array_key_exists('type',$_GET) && $_GET['type']!='')
		@php
        $type=$_GET['type'];
        @endphp
        @else
        @php
        $type=1;
        @endphp
        @endif
        <input type="hidden" value="{{$type}}" name="type">
      <div class="form-wrap"> 
        <!--<label>Select Item</label>-->
        <select style="width:200px;" id="item_data" class="form-control" data-plugin="select2" data-placeholder="Type Item name" data-minimum-input-length="1" name="i_id"aria-hidden="true">
        <option value="">--Select--</option>
        @foreach($item_data as $id=>$row)
        <?php $itemArr[$row->id] = $row->name;?>
        <option @if($i_id==$row->id) selected @endif value="{{ $row->id }}">{{ $row->name }}</option>
        @endforeach
        </select>
      </div>
      <div class="form-wrap"> 
        <select style="width:200px;" id="item_location" class="form-control" data-plugin="select2" data-placeholder="Type Location" data-minimum-input-length="1" name="loc_cen"aria-hidden="true">
        <option value="">--Select--</option>
        <option @if($_GET['loc_cen']==1) selected @endif value="1">LOCAL</option>
        <option @if($_GET['loc_cen']==2) selected @endif value="2">CENTRAL</option>
        </select>
      </div>
      <div class="form-wrap"> 
        <select style="width:200px;" id="room" class="form-control" data-plugin="select2" data-placeholder="Type Room" data-minimum-input-length="1" name="room"aria-hidden="true">
        <option value="">--Select--</option>
        @foreach($room_data as $room)
        <option @if($_GET['room']==$room['id']) selected @endif value="{{$room['id']}}">{{$room['room_no']}}</option>
        @endforeach
        </select>
      </div>
      <div class="form-wrap"> 
        <select style="width:200px;" id="rack" class="form-control" data-plugin="select2" data-placeholder="Type Rack" data-minimum-input-length="1" name="rack" aria-hidden="true">
        <option value="">--Select--</option>
        @foreach($rack_data as $rack)
        <option @if($_GET['rack']==$rack['id']) selected @endif value="{{$rack['id']}}">{{$rack['rack_name']}}</option>
        @endforeach
        </select>
      </div>
      <div class="form-wrap"> 
        <input type="text" class="form-control" value="{{@$_GET['batch']}}" placeholder="Batch. No" name="batch">
      </div>
    <div class="form-box">
    <input class="form-control mfg_date" type="text" id="mfg_date" name="mfg_date" value="{{@$_GET['mfg_date']}}" autocomplete="off" placeholder="Mfg Date">
    </div>
    <div class="form-box">
    <input class="form-control expiry_date" type="text" id="expiry_date" name="expiry_date" value="{{@$_GET['expiry_date']}}" autocomplete="off" placeholder="Expiry Date">
    </div>      
        <div class="form-wrap form-wrap-submit">
          <button class="btn btn-primary waves-effect waves-classic waves-effect waves-classic" type="submit">Search</button>
        </div>
        <div class="form-wrap form-wrap-submit"> <a href="{{ route('item_details') }}" class="btn btn-primary waves-effect waves-classic waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>        
        <div class="form-wrap  form-wrap-submit">
        <button type="button" class="btn btn-primary waves-effect waves-classic waves-effect waves-classic print_bar_code">Print Barcode</button>
      </div>
    </form>
  </div>
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
                    <form method="post" id="print_bar_code_form" action="{{url('admin/item_details_barcode')}}">
                        <table class="table table-bordered t1 list-data-table item-details-table">
                            <thead>
                                <th class="bg-info" style="color:#fff !important;text-align:center;"><input type="checkbox" id="select_all" /></th>
                                <th class="bg-info" style="color:#fff !important;text-align:center;">Sr. No</th>
								<th class="bg-info" style="color:#fff !important;text-align:center;">Item</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Location</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Room No.</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Rack No.</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Batch. No</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Mfg Date</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Expiry Date</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Quantity</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">MRP</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Rate</th>
                                <th class="bg-info" style="width: 5%;color:#fff !important;text-align:center;">Amount</th>
                                <th class="bg-info" style="color:#fff !important;text-align:center;">Action</th>
                            
                               
                            </thead>
                            <tbody>
                                @if($datalist->total()>0)
                                @php
                                $i=($datalist->currentpage()-1)* $datalist->perpage();
                                @endphp
                                @foreach($datalist as $item)
                                <tr>
								<?php //pr($item); die; ?>
                                <td style="text-align:center;" class="white-space-normal">
                               
                                @csrf
                                <input type="checkbox" name="barcode[]" class="checkbox" value="{{$item->id}}"/>
                                
                                </td>
								<td style="text-align:center;" class="white-space-normal">{{$i+1}}</td>
								<td style="text-align:center;" class="white-space-normal">{!! $itemArr[$item->item_id] !!}</td>
								<td style="text-align:center;" class="white-space-normal">{{$item->loc_cen}}</td>
								<td style="text-align:center;" class="white-space-normal">
									<?php 
										$room_data = getAllRoom($item->room_no); 
										if(!empty($room_data)){ echo $room_data->room_no; }
									?>
								</td>
								<td style="text-align:center;" class="white-space-normal">{{getRackName($item->rack_no)}}</td>
								<td style="text-align:center;" class="white-space-normal">{{$item->batch_no}}</td>
								<td style="text-align:center;">{{globalDateformatItem($item->mfg_date)}}</td>
								<td style="text-align:center;">{{globalDateformatItem($item->expiry_date)}}</td>
								<td style="text-align:center;" class="white-space-normal">{{$item->quantity}}</td>
								<td style="text-align:center;" class="white-space-normal">{{$item->mrp}}</td>
								<td style="text-align:center;" class="white-space-normal">{{$item->rate}}</td>
								<td style="text-align:center;" class="white-space-normal">{{$item->amount}}</td>
								<td style="text-align:center;" class="center-assign">
                                <div class="action-wrap" style="width:auto;">
                                <a href="{{url('admin/item_view_details/'.$item->id)}}" class="btn btn-pure btn-success icon waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i></a>
                                <a title="Edit" href="javascipt:void(0);" data-toggle="modal" data-target="#myModal_stock_inward-{{$item->id}}"><i class="icon md-edit" aria-hidden="true"></i></a>
                  <div id="myModal_stock_inward-{{$item->id}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                      <!-- Modal content-->
                      <form id="open-stock-{{$item->id}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$item->id}}">
                        <input type="hidden" name="item_id" value="{{$item->item_id}}">
                        <input type="hidden" name="item_name" value="{!! $itemArr[$item->item_id] !!}">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">STOCK INWARD</h4>
                          </div>
                          <div class="modal-body col-md-12 custom-popup-form mrn-custom-popup-form">
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="loc_central">Local/Central:</label>
                                <select class="form-control" name="loc_cen" id="loc-cen-{{$item->item_id}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                                  <option value="">-- Select --</option>
                                  <option value="LOCAL" @if($item->loc_cen=='LOCAL') selected @endif>LOCAL</option>
                                  <option value="CENTRAL" @if($item->loc_cen=='CENTRAL') selected @endif>CENTRAL</option>
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Select Store:</label>
                                <select class="form-control store_room" id="store-room-{{$item->id}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="room"aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Rack No:</label>
                                <select class="form-control rack_no" data-plugin="select2" id="rack-no-{{$item->id}}" data-placeholder="Type name" data-minimum-input-length="1" name="rack_no"aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Batch No:</label>
                                <input class="form-control" name="batch_no" value="{{$item->batch_no}}" id="batch_no-{{$item->id}}">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-4">
                                <label for="store">Mfg Date:</label>
                                <input type="text" readonly autocomplete="off" id="mfg_date" value="{{date('d/m/Y',strtotime($item->mfg_date))}}" required class="form-control date_with_time small_field_custom datefield hasDatepicker valid" name="mfg_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="store">Expiry Date:</label>
                                <input type="text" readonly autocomplete="off" id="expiry_date" value="{{date('d/m/Y',strtotime($item->expiry_date))}}" required class="form-control date_with_time small_field_custom datefield hasDatepicker valid" name="expiry_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="store">Retest Date:</label>
                                <input type="text" readonly autocomplete="off" id="retest_date-{{$item->id}}" value="@if($item->retest_date!=''){{date('d/m/Y',strtotime($item->retest_date))}}@endif" class="form-control date_with_time small_field_custom datefield" name="retest_date" aria-required="true" aria-invalid="false">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="quantity">Quantity:</label>
                                <input type="number" autocomplete="off" min="0" id="quantity_pop-{{$item->id}}" value="{{$item->quantity}}" cus="{{$item->id}}" required class="form-control qty-pop" name="quantity">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">Rate:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$item->id}}" gst="{{getGSTItemTax($item->id)}}" id="rate_pop-{{$item->id}}" required class="form-control rate-pop" name="rate" value="{{$item->rate}}">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">MRP:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$item->id}}" gst_mrp="{{getGSTItemTax($item->id)}}" id="mrp_pop-{{$item->id}}" value="{{$item->mrp}}" required class="form-control mrp-pop" name="mrp">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">Amount:</label>
                                <input type="number" autocomplete="off" min="0" id="amount_pop-{{$item->id}}" required class="form-control" name="amount" value="{{$item->amount}}">
                              </div>
							  
							 </div>
                              <div class="form-row">
                                <div class="form-group col">
                                  <label for="quantity">COA:</label>
                                  <input type="text" autocomplete="off" cus="{{$item->id}}" value="{{$item->coa}}" id="coa_pop-{{$item->id}}" required class="form-control coa-pop" name="coa">
                                </div>
                                <div class="form-group col">
                                  <label for="quantity">COA PDF Upload:</label>
                                  <input type="file" cus="{{$item->id}}" id="coa_pop-{{$item->id}}" accept="application/pdf" class="form-control coa-pop-upload" name="coa_file">
                                  <div class="form-box delete-img" id="upload_picture-{{$item->id}}"> 
                                    @if(!empty($item->coa_file))
                                    <input type="hidden" value="{{$item->coa_file}}" name="coa_file_hidden">
                                    <div class="remove_row"> <a href="javascript:void(0)" title="Delete Image" field_name="upload_picture" cus="{{$item->id}}" class="delete-image"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>
                                    <a target="_blank" href="{{url('public/uploads/stock/'.$item->coa_file)}}"> <img src="{{url('public/files/icons-pdf.png')}}" height="50" width="50"> </a> 
                                    @endif 
                                  </div>
                                </div>
                                <div class="form-group col">
                                <label for="quantity">Rejected Qty:</label>
                                <input type="number" autocomplete="off" cus="{{$item->id}}" value="{{$item->reject_qty}}" id="reject_qty-{{$item->id}}" class="form-control" name="reject_qty">
                              </div>
                              <div class="form-group col">
                                <label for="quantity">Breakage Qty:</label>
                                <input type="number" autocomplete="off" cus="{{$item->id}}" value="{{$item->breakage_qty}}" id="breakage_qty-{{$item->id}}" class="form-control" name="breakage_qty">
                              </div>
                              <div class="form-group col">
                                <label for="quantity">Shortage Qty:</label>
                                <input type="number" autocomplete="off" cus="{{$item->id}}" value="{{$item->shortage_qty}}" id="shortage_qty-{{$item->id}}" class="form-control" name="shortage_qty">
                              </div>
                              </div>
                            </div>
                          <div class="modal-footer">
                            <button type="button" cus="{{$item->id}}" class="btn btn-success btn-save-open-stock">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>    
                            </div>
								</td>
                                </tr>
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

		$('#store-room-{{$item->id}}').html(roomHtml);

		$('#store-room-{{$item->id}}').val('<?php echo $item->room_no;?>');

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

		$('#rack-no-{{$item->id}}').html(rackHtml);

		$('#rack-no-{{$item->id}}').val('<?php echo $item->rack_no;?>');

		//console.log(buyerHtml);

	});

</script>
                                @php
                                $i = $i+1;
                                @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="14">No record found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        </form>
                    </div>
                    <div class="mt-10">{!! $datalist->links('pagination::bootstrap-5') !!}</div>
                </div>
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
<link href="{{url('public/css/jquery.datetimepicker.css')}}" rel="stylesheet">
<script type="text/javascript" src="{{url('public/js/jquery.datetimepicker.full.js')}}"></script>
<script>
jQuery(document).ready(function() {

    jQuery('.mfg_date,.expiry_date').datetimepicker({

        timepicker: false,

        format: 'd-m-Y',

        autoclose: true,

        minView: 2

    });

    jQuery('.date_with_time').datetimepicker({

timepicker: false,

format: 'd/m/Y',

autoclose: true,

minView: 2

});

});
</script>
<script>
	
	function getSelectItem(i_id){
		var url = window.location.href.split('?')[0]+'?i_id='+i_id;
		window.location.href = url;
	}
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
<script>
$(function(){
   $(document).on('click','.print_bar_code',function(){
    var numberOfChecked = $('input:checkbox:checked').length;
    if(numberOfChecked>0){
      $('#print_bar_code_form').submit();
    }else{
        alert('Please check at least one checkbox.');
        return false;
    }
   });
});
</script>
<script>
    $(function(){
         $(document).on('click','.btn-save-open-stock',function(){
             var id = $(this).attr('cus');
             //alert(id); return false;
             var myform = document.getElementById("open-stock-"+id);
             var fd = new FormData(myform );
			 var loc_cen = $('#loc-cen-'+id).val();
        var store_room = $('#store-room-'+id).val();
        var batch_no = $('#batch_no-'+id).val();
        var rack_no = $('#rack-no-'+id).val();
        var qty_pop = $('#qty_pop-'+id).val();
        var mrp_pop = $('#mrp_pop-'+id).val();
        var rate_pop = $('#rate_pop-'+id).val();
        var amount_pop = $('#amount_pop-'+id).val();
        //alert(batch_no);
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
                 //$('.btn-save-open-stock').prop('disabled', true);
                $.ajax({
                url: "{{url('admin/update_stock_inward')}}",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (msg) {
                //console.log(msg); return false;
                if(msg==1){
                alert('Inward Stock Successfully update.');
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
  $(function(){
  $(document).on('blur change','.mrp-pop',function(){
  var mrp_id = $(this).attr('cus');
  var mrp_val = $('#mrp_pop-'+mrp_id).val();
  var rate_val = $('#rate_pop-'+mrp_id).val();
  var gst_mrp = $(this).attr('gst_mrp');
  if(mrp_id!='')
  {
    if (gst_mrp != '') {
  if(mrp_val!='' && rate_val!='')
  {
  //debugger;
  var tax_rate_val = eval((gst_mrp*rate_val)/100);
  var rate_plus_tax = eval(rate_val)+tax_rate_val;
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
  });
  </script>

  <script>
  $(function(){
  $(document).on('blur change','.rate-pop',function(){
  var mrp_id = $(this).attr('cus');
  var mrp_val = $('#mrp_pop-'+mrp_id).val();
  var rate_val = $('#rate_pop-'+mrp_id).val();
  var gst = $(this).attr('gst');
  if(mrp_id!='')
  {
  if (gst != '') {
  if(mrp_val!='' && rate_val!='')
  {
  var tax_rate_val = eval((gst*mrp_val)/100);
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
  });
  </script>


<script>
  $(function(){
      $(document).on('click','.delete-image',function(){
            var id = $(this).attr('cus');
            if(id!='')
            {
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
              var verify = confirm("Are you sure to delete the PDF File?");
              if(verify)
              {
              $.ajax({
              url: "{{ url('admin/stock_inward_image_remove') }}" ,
              type: "POST",
              data: {id:id},
              success: function( response ) {
              // alert(response); return false;
              if(response!='')
              {
              $('#upload_picture-'+id).remove();
              }
              }
              }); 
            }
            }
      });
  });
</script>

<style>.new-header-div-fot-btn{ z-index: 9; position: relative; }
.modal {  z-index: 999999999999999 !important; }
.modal-backdrop { z-index: 99999999 !important; }</style>
@endsection

