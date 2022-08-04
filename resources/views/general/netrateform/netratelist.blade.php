@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Sale Net Rate List')
@section('pagecontent')

<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn">
        <div class="page-header">
          <h1 class="page-title">Sale Net Rate List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Sale Net Rate List</li>
          </ol>
        </div>
        <div class="payment-heading-box1">
        <form method="POST" action="{{url('admin/netratelist')}}" class="custom-inline-form">
          @csrf
          <div class="form-box"><select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type Customer" data-minimum-input-length="1">
            <option value="">-- Select --</option>
            </select></div>
            <div class="form-box"><input type="text" class="form-control" autocomplete="off" placeholder="Item Name" name="product_name" value="{{$product_name}}"></div>
            <div class="form-box form-box-submit"><button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button></div>
          <div class="form-box form-box-submit"><a href="{{url('admin/netratelist')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a></div><div class="form-box  form-box-submit">
              @if(userMenuAccessRight(getUserAccess(),'netrate_form_create'))
              <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('netrate_form_create') }}"> <i class="icon md-plus" aria-hidden="true"></i> Add</a>
              @endif
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
          <div class="panel-body"> @if(!empty($datas))
            <table class="table table-bordered t1">
              <thead>
              <th class="bg-info" style="color:#fff !important;">Sr. No</th>
                <th class="bg-info sort ta" id="ticket_number" style="color:#fff !important;">Item Name</th>
                <th class="bg-info sort ta" id="location" style="color:#fff !important;">Customer</th>
                <th class="bg-info sort ta" id="department" style="color:#fff !important;">Till Date</th>
                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Net Rate</th>
                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Updated On</th>
                <th class="bg-info" style="color:#fff !important;">Action</th>
                  </thead>
              <tbody id="fb_table_body">
              @php
              $i=($datas->currentpage()-1)* $datas->perpage(); 
              @endphp
              @foreach($datas as $data)
              <tr>
                <td>{{ $i+1 }}</td>
                <td class="white-space-normal" style="line-height:normal">{{$data->product_name}}</td>
                <td>@if($data->customer_id!='' && $data->customer_id!=0) {{$data->customer_name}} @endif</td>
                <td id="date-rate-{{$data->id}}"> {{ globalDateformatNet($data->date_rate)}} </td>
                <td id="net-rate-{{$data->id}}"> {{ $data->net_rate}} </td>
                <td id="updated-on-{{$data->id}}"> {{ globalDateformatNet($data->updated_at)}} </td>
                <td style="text-align:center;" class="action">
                    <div class="action-wrap">
                    <!-- <label class="radio-btn-action"><input type="radio" value="{{$data->id}}" class="action_data" name="action_data" aria-required="true"></label> -->
                    <span id="action-{{$data->id}}" class="action-data-show">
                    <a title="Edit" href="javascript:void(0);" data-toggle="modal" data-target="#myModal-{{$data->id}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i></a> 
                    <div id="myModal-{{$data->id}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm"> 
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sale Net Rate Update</h4>
                    </div>
                    <form method="post" action="{{url('admin/netrate_data_update')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body col-md-12">
                    <div class="form-group">
                    <input type="text" class="form-control filter-date date_rate-{{$data->id}}" value="{{ globalDateformatNet($data->date_rate)}}" autocomplete="off" required name="date_rate" placeholder="Till Date">
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control net_rate-{{$data->id}}" autocomplete="off" value="{{$data->net_rate}}" required name="net_rate" placeholder="Net Rate">
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" name="importSubmit" id="{{$data->id}}" class="btn btn-success update-net-rate">Update</button>
                    <button type="button" class="btn btn-danger close-data" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                    </div>
                    </div>
                    </div>
                    <span id="del_{{$data->id}}">
                        <a title="Delete" onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/delete_netrate/'.$data->id)}}" class="btn btn-pure btn-danger icon waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i></a>
                        </span>
                    </span>
                    </div>
                    </td>
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
          <div class="pagination-wrap">{{ $datas->links('pagination::bootstrap-5') }}</div>
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
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script> 
<script>

	jQuery(document).ready(function () {

	jQuery('.filter-date').datetimepicker({

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
                    $('#updated-on-'+id).html(date_update);
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
		$('#customer_name').val('<?php echo $customer_name; ?>');
	});
</script> 
@endsection