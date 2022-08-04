@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Opening Stock List')
@section('pagecontent') 
<script src="{{url('public/theme/global/vendor/jquery/jquery.js')}}"></script> 
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn search-design-data">
        <div class="page-header">
          <h1 class="page-title">Opening Stock List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Opening Stock List</li>
          </ol>
        </div>
        <div class="payment-heading-box1">
        <form method="POST" action="{{url('admin/opening-stock-list')}}" class="custom-inline-form">
          @csrf
          <div class="form-box">
            <input class="form-control" type="text" name="room_no" value="{{$request->room_no}}" autocomplete="off" placeholder="Room No.">
          </div>
          <div class="form-box">
            <input class="form-control" type="text" name="item_name" value="{{$request->item_name}}" autocomplete="off" placeholder="Item Name">
          </div>
          <div class="form-box">
            <input class="form-control" type="text" id="batch_no" name="batch_no" value="{{$request->batch_no}}" autocomplete="off" placeholder="Batch No.">
          </div>
          <div class="form-box form-box-submit">
            <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
          </div>
          <div class="form-box form-box-submit"><a href="{{route('opening_stock_list')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a></div>
          <div class="form-box  form-box-submit">
              <a class="btn btn-success waves-effect waves-classic small-buton waves-effect waves-classic" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" title="Import Data"><i class="icon md-upload" aria-hidden="true"></i></a>
               </div>
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
          <div class="panel-body"> @if(!empty($openingstocklist))
            <table class="table table-bordered t1 custom-lineheight-table">
              <thead>
              <th class="bg-info" id="item_name" style="color:#fff !important;">Item Name</th>
                <th class="bg-info" id="room_no" style="color:#fff !important;">Room No.</th>
                <th class="bg-info" id="rack_no" style="color:#fff !important;">Rack No.</th>
                <th class="bg-info" id="batch_no" style="color:#fff !important;">Batch No.</th>
                <th class="bg-info" id="qty" style="color:#fff !important;">Qty</th>
                <th class="bg-info" id="mfg_date" style="color:#fff !important;">Mfg Date</th>
                <th class="bg-info" id="expire_date" style="color:#fff !important;">Expire Date</th>
                <th class="bg-info" id="stock_type" style="color:#fff !important;">Stock Type</th>
                <th class="bg-info" id="rate" style="color:#fff !important;">Rate</th>
                <th class="bg-info" id="mrp" style="color:#fff !important;">MRP</th>
                <th class="bg-info" id="amount" style="color:#fff !important;">Amount</th>
                <th class="bg-info" style="color:#fff !important;">Action</th>
                  </thead>
              <tbody id="fb_table_body">
              
              @php
              
              $i=($openingstocklist->currentpage()-1)* $openingstocklist->perpage(); 
              
              $item_data = getAllitemsDataList();
              
              $room_data = getAllroomsDataList();
              
              $rack_data = getAllracksDataList();
              
              //pr($cus_data); die;
              
              @endphp
              
              @foreach($openingstocklist as $data)
              <tr>
                <td class="white-space-normal">{{ $item_data[$data['item_id']] }}</td>
                <td> {{$room_data[$data['room_no']] }}</td>
                <td> {{$rack_data[$data['rack_no']] }} </td>
                <td> {{$data['batch_no']}} </td>
                <td> {{$data['quantity']}} </td>
                <td> {{date('d-M-Y',strtotime($data['mfg_date']))}} </td>
                <td> {{date('d-M-Y',strtotime($data['expiry_date']))}} </td>
                <td> {{$data['loc_cen']}} </td>
                <td> {{$data['rate']}} </td>
                <td> {{$data['mrp']}} </td>
                <td> {{$data['amount']}} </td>
                <td style="text-align:center;" class="action"><a title="Edit" href="javascipt:void(0);" data-toggle="modal" data-target="#myModal_opening_stock-{{$data['_id']}}"><i class="icon md-edit" aria-hidden="true"></i></a>
                  <div id="myModal_opening_stock-{{$data['_id']}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg"> 
                      
                      <!-- Modal content-->
                      
                      <form id="open-stock-{{$data['_id']}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$data['_id']}}">
                        <input type="hidden" name="item_id" value="{{$data['item_id']}}">
                        <input type="hidden" name="item_name" value="{{$item_data[$data['item_id']]}}">
                        <div class="modal-content custom-modal-content-wrap">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">OPENING STOCK</h4>
                          </div>
                          <div class="modal-body col-md-12 custom-popup-form">
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="store">Select Store:</label>
                                <select class="form-control store_room" id="store-room-{{$data['_id']}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="room"aria-hidden="true">
                                </select>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="store">Rack No:</label>
                                <select class="form-control rack_no" data-plugin="select2" id="rack-no-{{$data['_id']}}" data-placeholder="Type name" data-minimum-input-length="1" name="rack_no"aria-hidden="true">
                                </select>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="store">Batch No:</label>
                                <input class="form-control" name="batch_no" value="{{$data['batch_no']}}" id="batch_no">
                              </div>
                              <div class="form-group col-md-6">
                                <label for="store">Mfg Date:</label>
                                <input type="text" readonly autocomplete="off" id="mfg_date" value="{{date('m/d/Y',strtotime($data['mfg_date']))}}" required class="form-control date_with_time small_field_custom datefield hasDatepicker valid" name="mfg_date" aria-required="true" aria-invalid="false">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="store">Expiry Date:</label>
                                <input type="text" readonly autocomplete="off" id="expiry_date" value="{{date('m/d/Y',strtotime($data['expiry_date']))}}" required class="form-control date_with_time small_field_custom datefield hasDatepicker valid" name="expiry_date" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group col-md-6">
                                <label for="quantity">Quantity:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$data['_id']}}" id="qty_pop-{{$data['_id']}}" value="{{$data['quantity']}}" required class="form-control qty-pop" name="quantity">
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="quantity">MRP:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$data['_id']}}" value="{{$data['mrp']}}" id="mrp_pop-{{$data['_id']}}" value="{{$data['mrp']}}" required class="form-control mrp-pop" name="mrp">
                              </div>
                              <div class="form-group col-md-6">
                                <label for="loc_central">Local/Central:</label>
                                <select class="form-control" name="loc_cen" id="loc-cen-{{$data['_id']}}" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                                  <option value="">-- Select --</option>
                                  <option @if($data['loc_cen']=="LOCAL") selected @endif value="LOCAL">LOCAL</option>
                                  <option @if($data['loc_cen']=="CENTRAL") selected @endif value="CENTRAL">CENTRAL</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="quantity">Rate:</label>
                                <input type="number" autocomplete="off" min="0" cus="{{$data['_id']}}" value="{{$data['rate']}}" id="rate_pop-{{$data['_id']}}" required class="form-control rate-pop" name="rate">
                              </div>
                              <div class="form-group col-md-6">
                                <label for="quantity">Amount:</label>
                                <input type="number" autocomplete="off" min="0" id="amount_pop-{{$data['_id']}}" value="{{$data['amount']}}" required class="form-control" name="amount">
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" cus="{{$data['_id']}}" class="btn btn-success btn-save-open-stock">Update</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div></td>
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

		$('#store-room-{{$data['_id']}}').html(roomHtml);

		$('#store-room-{{$data['_id']}}').val('<?php echo $data['room_no'];?>');

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

		$('#rack-no-{{$data['_id']}}').html(rackHtml);

		$('#rack-no-{{$data['_id']}}').val('<?php echo $data['rack_no'];?>');

		//console.log(buyerHtml);

	});

</script> 
              @php $i++ @endphp
              
              
              
              
              
              
              
              @endforeach
              
              
              
              
              
              
              
              @else
              <tr>
                <td colspan="9">Result not found.</td>
              </tr>
              @endif
                </tbody>
              
            </table>
          </div>
          <div class="pagination-wrap">{{ $openingstocklist->links() }}</div>
        </div>
      </div>
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
      <form method="post" action="{{url('admin/import_opening_stock')}}" enctype="multipart/form-data">
        @csrf        
        <div class="modal-body col-md-12">
          <input type="file" required accept=".csv" name="result_file" class="form-control">
                    <small style="float: right;"><a href="{{url('admin/export_opening_stock')}}">Download Sample File</a></small>
                  </div>
        <div class="modal-footer">
          <button type="submit" name="importSubmit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
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
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script> 
<script>



	jQuery(document).ready(function () {



	jQuery('#quotation_date').datetimepicker({



	timepicker:false,



	format: 'd-m-Y',



	autoclose: true, 



	minView: 2



	});



	});



	</script> 
<script>

	function insertIntoFms(Id){

	    var cf = confirm('Are you sure you want to insert this TASK into FMS?');

	    if(!cf){

        return false;

		}else{

		$('#loader').show();

		var formData = 'id='+Id;

		var ajax_url = "{{ url('admin/ajax_insert_task_into_fms')}}";

		

		$.ajax({



				type:'POST',



				url:ajax_url,



				data:formData,



				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},



				success:function(res){

                    //alert(res); return false;

					console.log(res); //return false;

					

					/* check if data alredy inserted */

					if(res=='yes'){

						alert('Data already inserted please check.');

						location.reload();

						return false;

					}

					var data = JSON.parse(res);

					$('#loader').hide();

					if(data.status){

						$('#del_'+Id).html('');



						$('#pi_'+Id).html('');



						$('#pi_'+Id).html('<a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>');

					}



					console.log(res);



				}



			});

		

		}

	}

	</script> 
<script>

    $(function(){

        $(document).on('click','.update-net-rate',function(){

            var id = $(this).attr('id');

            var date_rate = $('.date_rate-'+id).val();

            var net_rate = $('.net_rate-'+id).val();

            var date_update = "{{date('d-m-Y')}}";

                $.ajaxSetup({

                headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

                });

                

                if(id!='' && date_rate!='' && net_rate!=''){

                    $.ajax({

                    url: "{{ url('admin/netrate_data_update') }}" ,

                    type: "POST",

                    data: {id:id,date_rate:date_rate,net_rate:net_rate},

                    success: function( response ) {

                   // alert(response); return false;

                    if(response!='')

                    {

                    $('#date-rate-'+id).html(date_rate);

                    $('#net-rate-'+id).html(net_rate);

                    $('.close-data').trigger('click');

                    $('#updated_on-'+id).html(date_update);

                    }

                    }

                    }); 

                }

        

        

        });

    });

</script> 
<script>

	$(function () { 

		var custArr = '<?php print_r(getAllBuyer()); ?>';

		custArr = JSON.parse(custArr);

		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });

		var custHtml='<option value="">--Select--</option>';

		for(var i=0; i<custData.length; i++){

			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';

		}

		$('#customer_name').html(custHtml);

		$('#customer_name').val('<?php echo $request->customer_name; ?>');

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

<style type="text/css">
.modal-open .select2-container { z-index:999999999; }
</style>

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
             //alert(id); return false;
             var myform = document.getElementById("open-stock-"+id);
             var fd = new FormData(myform );
                 $('.btn-save-open-stock').prop('disabled', true);
                $.ajax({
                url: "{{url('admin/update_opening_balance')}}",
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (msg) {
                //alert(msg); return false;
                if(msg==1){
                //alert('Opening Stock Successfully update.');
                //$('.btn-save-open-stock').prop('disabled', false);
                window.location.reload(true);
                }
                }
                });
         }); 
    });
</script>
@endsection