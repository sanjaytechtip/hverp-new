@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Balance Stock List')
@section('pagecontent')
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn search-design-data">
        <div class="page-header">
          <h1 class="page-title">Balance Stock List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Balance Stock List</li>
          </ol>
        </div>
        <div class="payment-heading-box1">
        <form method="POST" action="{{url('admin/balance-stock-list')}}" class="custom-inline-form">
          @csrf
          <div class="form-box">
            <input class="form-control" type="text" name="brand_name" value="{{$request->brand_name}}" autocomplete="off" placeholder="Brand Name">
          </div>
          <div class="form-box">
            <input class="form-control" type="text" name="item_name" value="{{$request->item_name}}" autocomplete="off" placeholder="Item Name">
          </div>
          <div class="form-box">
            <input class="form-control" type="text" id="product_code" name="product_code" value="{{$request->product_code}}" autocomplete="off" placeholder="Product Code">
          </div>
          <div class="form-box form-box-submit">
            <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
          </div>
          <div class="form-box form-box-submit"><a href="{{route('balance_stock_list')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a></div>
          
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
          <div class="panel-body"> @if(!empty($balancestocklist))
            <table class="table table-bordered t1 custom-lineheight-table">
              <thead>
              <th class="bg-info" id="brand_name" style="color:#fff !important;">Brand Name</th>
                <th class="bg-info" id="item_name" style="color:#fff !important;">Item Name</th>
                <th class="bg-info" id="product_code" style="color:#fff !important;">Product Code</th>
                <th class="bg-info" id="rate" style="color:#fff !important;">Rate</th>
                <th class="bg-info" id="balance" style="color:#fff !important;">Balance</th>
                  </thead>
              <tbody id="fb_table_body">
              
              @php
              
              $i=($balancestocklist->currentpage()-1)* $balancestocklist->perpage(); 
              
				$item_data = getAllitemsDataList();
               $item_data = (array) $item_data;
			   
              $brand_name = getAllbrandDataList();
              $brand_name = (array) $brand_name;
              
              $product_code = getAllproductsDataList();
               $product_code = (array) $product_code;
              //pr($cus_data); die;
              
              @endphp
              
              @foreach($balancestocklist as $data)
			  <?php  $data = (array) $data; ?>
              <tr>
                <td class="white-space-normal">{{ $brand_name[$data['item_id']] }}</td>
                <td class="rack-details" id="{{$data['item_id']}}" style="cursor:default;" data-toggle="modal" data-target="#myModal_balance_stock">{{ $item_data[$data['item_id']] }}</td>
                <td>{{ $product_code[$data['item_id']] }}</td>
                <td>{{ rateAverage($data['item_id']) }}</td>
                <td>{{ rateQuantity($data['item_id']) }}</td>
              </tr>
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
          <div class="mt-10">{{ $balancestocklist->links('pagination::bootstrap-5') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="myModal_balance_stock" class="modal fade myModal-balance-stock-popup" role="dialog">
  <div class="modal-dialog modal-xlg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">BALANCE STOCK RACK WISE</h4>
      </div>
      <div class="modal-body col-md-12">
        <form method="POST" id="reloadform" action="" class="custom-inline-form item-form-apply">
          @csrf
          <input type="hidden" name="id" id="rank_id">
          <div class="new-form-box">
            <div class="form-wrap small-box">
              <label>Brand</label>
              <input class="form-control" type="text" id="brand" name="brand" value="{{@$brand}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap">
              <label>Item Name</label>
              <input class="form-control" type="text" id="item_name" name="item_name" value="{{@$item_name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Rack No.</label>
              <input class="form-control" type="text" id="rack_no" name="rack_no" value="{{@$rack_no}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Type</label>
              <input class="form-control" type="text" id="type" name="type" value="{{@$type}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Batch No.</label>
              <input class="form-control" type="text" id="batch_no" name="batch_no" value="{{@$batch_no}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Mfg Date</label>
              <input class="form-control" type="text" id="mfg_date" name="mfg_date" value="{{@$mfg_date}}" autocomplete="off" placeholder="">
            </div>
          </div>
          <div class="new-form-box new-form-box-radio-box">
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic order_search" type="button" name="order_search">Search</button>
            </div>
            <div class="form-wrap form-wrap-submit"> <a id="reload" href="javascript:void(0);" class="btn btn-primary reload waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
          </div>
        </form>
        <table class="table table-bordered t1" id="data_table">
          <thead>
          	<tr>
          <th class="bg-info">Brand</th>
            <th class="bg-info">Item Name</th>
            <th class="bg-info">Rack No.</th>
            <th class="bg-info">Type</th>
            <th class="bg-info">Batch No.</th>
            <th class="bg-info">Mfg Date</th>
            <th class="bg-info">Expiry Date</th>
            <th class="bg-info">Rate</th>
            <th class="bg-info">Balance</th>
            </tr>
          </thead>
          <tbody id="results-pop">
          </tbody>
        </table>
        
        <ul id="example-2" class="pagination" ></ul>
        <div class="show"></div>
      </div>
      
          
         
       
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script> 
<script>



	jQuery(document).ready(function () {



	jQuery('#mfg_date').datetimepicker({



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
<script src="{{ url('js/pagination.js')}}"></script> 
<script>
    $(function(){
        $(document).on('click','.rack-details',function(){
              var id = $(this).attr('id');
              $('#rank_id').val(id);
              if(id!='')
              {
                    $('#loader').show();

                    $.ajaxSetup({

                    headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                    });

                    

                    $.ajax({

                    url: "{{ url('admin/ajax-rack-details') }}" ,

                    type: "POST",

                    data: {id:id},

                    success: function( response ) {

                    //alert(response); return false;

                    if(response!='')

                    {

                    var returnedData = JSON.parse(response);

                    $('#results-pop').html(returnedData.html);

                    $('#example-2').pagination({

                    total: returnedData.record_count, 

                    current: 1,

                    length: 100,

                    size: 2,

                    prev: 'Previous',

                    next: 'Next',

                    click: function(options, refresh, $target){

                    $.ajaxSetup({

                    headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                    });

                    

                    $.ajax({

                    url: "{{ url('admin/ajax-rack-details') }}" ,

                    type: "POST",

                    data: {page:options.current,id:id},

                    success: function( response ) {

                    //alert(response); return false;

                    if(response!='')

                    {

                    var returnedData = JSON.parse(response);

                    $('#results-pop').html(returnedData.html);  

                    }

                    }

                    }); 

                    }

                    });

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
        $(document).on('click','#reload',function(){
            //alert();
           $('#reloadform')[0].reset();
           $('.order_search').trigger('click');
        });
    });

</script> 
<script>
    $(function(){
        $(document).on('click','.order_search',function(){

                $('#loader').show();

                $.ajaxSetup({

                headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

                });

                var formulario =  $(".item-form-apply");

                    $.ajax({

                    url: "{{ url('admin/ajax-rack-details') }}" ,

                    type: "POST",

                    data: formulario.serialize(),

                    success: function( response ) {

                    //alert(response); return false;



                    if(response!='')

                    {

                    $('#loader').hide();

                    var returnedData = JSON.parse(response);

                   $('#results-pop').html(returnedData.html);

                    

                    //alert(returnedData.record_count);

                    $('#example-2').pagination({

                    total: returnedData.record_count, 

                    current: 1,

                    length: 100,

                    size: 2,

                    prev: 'Previous',

                    next: 'Next',

                    click: function(options, refresh, $target){

                    $.ajaxSetup({

                    headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                    });

                    var brand = $("#brand").val();

                    var item_name = $("#item_name").val();

                    var rack_no = $("#rack_no").val();

                    var type = $("#type").val();

                    var batch_no = $("#batch_no").val();

                    var mfg_date = $("#mfg_date").val();

                    var id = $("#rank_id").val();

                    $.ajax({

                    url: "{{ url('admin/ajax-rack-details') }}" ,

                    type: "POST",

                    data: {page:options.current,brand:brand,item_name:item_name,rack_no:rack_no,type:type,batch_no:batch_no,mfg_date:mfg_date,id:id},

                    success: function( response ) {

                    //alert(response); return false;

                    if(response!='')

                    {

                    var returnedData = JSON.parse(response);

                    $('#results-pop').html(returnedData.html);  

                    }

                    }

                    }); 

                    }

                    });   

                    }else{

                    }

                    }

                    }); 

        });

    });

</script> 
@endsection