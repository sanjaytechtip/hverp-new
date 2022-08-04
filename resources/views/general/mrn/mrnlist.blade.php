@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Material Receipt Note List')
@section('pagecontent')
<?php //pr($mrnlist); die;?>
<?php //pr($request); die;?>
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn search-design-data">
        <div class="page-header">
          <h1 class="page-title">Material Receipt Note List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Material Receipt Note List</li>
          </ol>
        </div>
        <div class="payment-heading-box1">
        <form method="POST" action="{{url('admin/mrn-list')}}" class="custom-inline-form">
          @csrf
          <div class="form-box">
            <input class="form-control" type="text" name="mrn_receipt_no" value="{{$request->mrn_receipt_no}}" autocomplete="off" placeholder="Receipt No">
          </div>
          <div class="form-box">
            <input class="form-control" type="text" name="mrn_bill_no" value="{{$request->mrn_bill_no}}" autocomplete="off" placeholder="Bill No">
          </div>
          <div class="form-box">
            <input class="form-control" type="text" name="mrn_challan_no" value="{{$request->mrn_challan_no}}" autocomplete="off" placeholder="Challan No">
          </div>
          <div class="form-box">
            <input class="form-control date_with_time small_field_custom datefield hasDatepicker valid" type="text" id="quotation_date" name="mrn_bill_date" value="{{$request->mrn_bill_date}}" autocomplete="off" placeholder="Bill Date">
          </div>
          <div class="form-box">
            <select class="required form-control" id="created_by" name="created_by" data-plugin="select2" data-placeholder="Created By" data-minimum-input-length="1">
              <option value="">-- Select --</option>
            </select>
          </div>
          <div class="form-box">
            <select class="required form-control" id="supplier_name" name="supplier_name" data-plugin="select2" data-placeholder="Type Supplier" data-minimum-input-length="1">
              <option value="">-- Select --</option>
            </select>
          </div>
          <div class="form-box form-box-submit">
            <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
          </div>
          <div class="form-box form-box-submit"><a href="{{route('mrn_list')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a></div>
          <div class="form-box  form-box-submit"> <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('mrncreate') }}"> <i class="icon md-plus" aria-hidden="true"></i> Add</a> </div>
          </div>
        </form>
      </div>
      <div class="page-content">
        <div class="row"> @if (\Session::has('success'))
          <div class="col-md-12">
            <div class="alert alert-success">
              <p>{{ \Session::get('success') }}</p>
            </div>
          </div>
          @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model" id="invoice_btn"></a> </div>
        <div class="panel">
          <div class="panel-body"> @if(!empty($mrnlist))
            <table class="table table-bordered t1 custom-lineheight-table">
              <thead>
              <th class="bg-info sort ta" id="ticket_number" style="color:#fff !important;">Receipt No</th>
                <th class="bg-info sort ta" id="department" style="color:#fff !important;">Supplier</th>
                <th class="bg-info sort ta" id="department" style="color:#fff !important;">Item Name</th>
                <th class="bg-info sort ta" id="bill_no" style="color:#fff !important;">Bill No</th>
                <th class="bg-info sort ta" id="challan_no" style="color:#fff !important;">Challan No</th>
                <!--<th class="bg-info sort ta" id="customer" style="color:#fff !important;">Date</th>-->
                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Bill Date</th>
                <th class="bg-info sort ta" id="GrandTotal" style="color:#fff !important;">Grand Total</th>
                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Created By</th>
                <th class="bg-info sort ta" id="qty" style="color:#fff !important;">Qty</th>
                <th class="bg-info sort ta" id="r_qty" style="color:#fff !important;">Remaining Qty</th>
                <th class="bg-info sort ta" id="approvedBy" style="color:#fff !important;">Remarks</th>
                <th class="bg-info" style="color:#fff !important;">Action</th>
                  </thead>
              <tbody id="fb_table_body">
              
              @php
              $i=($mrnlist->currentpage()-1)* $mrnlist->perpage(); 
              $user_data = (array) getAllusersDataList();
              $cus_data = (array) getAllVendorData();
              //pr($cus_data); die;
              @endphp
              @foreach($mrnlist as $data)
              @php
              $data = (array) $data;
              //pr($data); 
              $in_qty = $data['quantity']-$data['inward_qty'];
              
              @endphp
              <tr>
                <td>{{ mrnDetails($data['mrn_id'])['mrn_receipt_no'] }}</td>
                <td class="white-space-normal custom-white-space-normal"> {{ $cus_data[$data['supplier_name']] }} </td>
                <td class="white-space-normal custom-white-space-normal"> {{ getAllitemsById($data['item_id']) }} </td>
                <td> {{ mrnDetails($data['mrn_id'])['mrn_bill_no'] }}</td>
                <td> {{ mrnDetails($data['mrn_id'])['mrn_challan_no'] }}</td>
                <!--<td> {{ date('d-m-Y',strtotime(mrnDetails($data['mrn_id'])['mrn_date'])) }}</td>-->
                <td> {{ date('d-m-Y',strtotime(mrnDetails($data['mrn_id'])['mrn_bill_date'])) }}</td>
                <td> {{ mrnDetails($data['mrn_id'])['mrn_grand_total'] }}</td>
                <td> {{ $user_data[mrnDetails($data['mrn_id'])['created_by']] }}</td>
                <td> {{ $data['quantity'] }}</td>
                <td> {{ $in_qty }}</td>
                <td class="white-space-normal custom-white-space-normal"> {{ mrnDetails($data['mrn_id'])['mrn_remarks'] }}</td>
                <td style="text-align:center;" class="action"><div class="action-wrap"> <a title="View" href="{{url('admin/view_mrn/'.$data['mrn_id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i></a> <a title="Edit" href="{{url('admin/mrn-edit/'.$data['mrn_id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i></a> <span id="del_{{$data['id']}}"> <a title="Delete" onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/delete_mrn/'.$data['mrn_id'])}}" class="btn btn-pure btn-danger icon waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i></a> </span> @if($in_qty>0) <a href="javascript:void(0);" title="Stock Inward" data-toggle="modal" data-target="#myModal_stock_inward-{{$data['id']}}"><i class="fas far fa-box-open" aria-hidden="true"></i></a> @endif <a title="Opening Stock" href="{{ url('admin/item_details/?i_id=')}}{{$data['item_id']}}&type=2" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic waves-effect waves-classic"><i class="icon md-eye" aria-hidden="true"></i></a> </div>
                  <?php //pr($data); ?>
                  <div id="myModal_stock_inward-{{$data['id']}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg"> 
                      <!-- Modal content-->
                      <form id="open-stock-{{$data['id']}}" method="post">
                        @csrf
                        <input type="hidden" name="item_id" value="{{$data['item_id']}}">
                        <input type="hidden" name="mrn_id" value="{{$data['mrn_id']}}">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">STOCK INWARD</h4>
                          </div>
                          <input type="hidden" name="id" value="{{$forms[0]['id']}}">
                          <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
                          <div class="modal-body col-md-12 custom-popup-form mrn-custom-popup-form">
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="loc_central">Local/Central:</label>
                                <select class="form-control" name="loc_cen" id="loc-cen-{{$data['id']}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                                  <option value="">-- Select --</option>
                                  <option value="LOCAL">LOCAL</option>
                                  <option value="CENTRAL">CENTRAL</option>
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Select Store:</label>
                                <select class="form-control store_room" id="store-room-{{$data['id']}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="room"aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Rack No:</label>
                                <select class="form-control rack_no" data-plugin="select2" id="rack-no-{{$data['id']}}" data-placeholder="Type name" data-minimum-input-length="1" name="rack_no"aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="store">Batch No:</label>
                                <input class="form-control" name="batch_no" id="batch_no-{{$data['id']}}">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-4">
                                <label for="store">Mfg Date:</label>
                                <input type="text" readonly autocomplete="off" id="mfg_date" value="{{date('d/m/Y')}}" required class="form-control date_with_time small_field_custom datefield valid" name="mfg_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="store">Expiry Date:</label>
                                <input type="text" readonly autocomplete="off" id="expiry_date" value="{{date('d/m/Y')}}" required class="form-control date_with_time small_field_custom datefield valid" name="expiry_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="store">Retest Date:</label>
                                <input type="text" readonly autocomplete="off" id="retest_date-{{$data['id']}}" class="form-control date_with_time small_field_custom datefield" name="retest_date" aria-required="true" aria-invalid="false">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="quantity">Quantity:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$data['id']}}" id="qty_pop-{{$data['id']}}" required class="form-control qty-pop" name="quantity">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">Rate:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$data['id']}}" id="rate_pop-{{$data['id']}}" gst="{{$data['tax_rate']}}" required class="form-control rate-pop" name="rate">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">MRP:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$data['id']}}" id="mrp_pop-{{$data['id']}}" gst_mrp="{{$data['tax_rate']}}" value="" required class="form-control mrp-pop" name="mrp">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="quantity">Amount:</label>
                                <input type="number" autocomplete="off" min="0" id="amount_pop-{{$data['id']}}" required class="form-control" name="amount">
                              </div>
                              <div class="form-row">
                              <div class="form-group col">
                                <label for="quantity">COA:</label>
                                <input type="text" autocomplete="off" cus="{{$data['id']}}" id="coa_pop-{{$data['id']}}" required class="form-control coa-pop" name="coa">
                              </div>
                              <div class="form-group col">
                                <label for="quantity">COA PDF Upload:</label>
                                <input type="file" cus="{{$data['id']}}" id="coa_pop-{{$data['id']}}" accept="application/pdf" class="form-control coa-pop-upload" name="coa_file">
                              </div>
                              <div class="form-group col">
                                <label for="quantity">Rejected Qty:</label>
                                <input type="number" autocomplete="off" cus="{{$data['id']}}" id="reject_qty-{{$data['id']}}" class="form-control" name="reject_qty">
                              </div>
                              <div class="form-group col">
                                <label for="quantity">Breakage Qty:</label>
                                <input type="number" autocomplete="off" cus="{{$data['id']}}" id="breakage_qty-{{$data['id']}}" class="form-control" name="breakage_qty">
                              </div>
                              <div class="form-group col">
                                <label for="quantity">Shortage Qty:</label>
                                <input type="number" autocomplete="off" cus="{{$data['id']}}" id="shortage_qty-{{$data['id']}}" class="form-control" name="shortage_qty">
                              </div>
                            </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" cus="{{$data['id']}}" class="btn btn-success btn-save-open-stock">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div></td>
              </tr>
              @php $i++ @endphp
              
              
              
              @endforeach
              
              
              
              @else
              <tr>
                <td colspan="8">Result not found.</td>
              </tr>
              @endif
                </tbody>
              
            </table>
          </div>
          <div class="pagination-wrap mrn-pagination-wrap">{{ $mrnlist->links('pagination::bootstrap-5') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

 @section('custom_validation_script') 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
<script src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script> 
<script>
    $(document).ready(function(){
        $("input[type='radio']").click(function(){
            var radioValue = $("input[name='action_data']:checked").val();
            if(radioValue!='')
            {
            $('.action-data-show').hide();
            $('#action-'+radioValue).show();
            }else{
            $('.action-data-show').hide();  
            }
        });
    });
</script> 
<script>

$(function(){

    var table = $('.t1');

    $('#ticket_number,#department,#customer,#subject,#date_time,#location')

        .wrapInner('<span title=""/>')

        .each(function(){

            var th = $(this),

                thIndex = th.index(),

                inverse = false;

            

            th.click(function(){

                //alert();

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
	$(function () { 
		var buyerArr = '<?php print_r(getAllVendor()); ?>';
		buyerArr = JSON.parse(buyerArr);
		//console.log(buyerArr);
		var buyerData = $.map(buyerArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			buyerHtml+='<option value="'+buyerData[i].data+'">'+buyerData[i].value+'</option>';
		}
		$('#supplier_name').html(buyerHtml);
		$('#supplier_name').val('<?php echo $request->supplier_name; ?>');
		//$('#buyer_name').html(buyerHtml);
		//console.log(buyerHtml);
	});
</script> 
<script>
	$(function () { 
		var userArr = '<?php print_r(getAllusersData()); ?>';
		userArr = JSON.parse(userArr);
		var userData = $.map(userArr, function (value, key) { return { value: value, data: key }; });
		var userHtml='<option value="">--Select--</option>';
		for(var i=0; i<userData.length; i++){
			userHtml+='<option value="'+userData[i].data+'">'+userData[i].value+'</option>';
		}
		$('#created_by').html(userHtml);
		$('#created_by').val('<?php echo $request->created_by; ?>');
		$('#approved_by').html(userHtml);
		$('#approved_by').val('<?php echo $request->approved_by; ?>');
	});
</script> 
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
         $(document).on('click','.btn-save-open-stock',function(){
             var id = $(this).attr('cus');
             var myform = document.getElementById("open-stock-"+id);
             var fd = new FormData(myform );
                // $('.btn-save-open-stock').prop('disabled', true);
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
                $.ajax({
                url: "{{url('admin/submit_inward_balance')}}",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (msg) {
                //console.log(msg); return false;
                if(msg==1){
                $('#open-stock-'+id)[0].reset();
                $('#store-room-'+id).html("").trigger("change");
                $('#rack-no-'+id).html("").trigger("change");
                alert('Stock Inward Successfully add.');
                //$('.btn-save-open-stock').prop('disabled', false);
                window.location.reload(true);
                }
                }
                });
              }
         }); 
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
@endsection