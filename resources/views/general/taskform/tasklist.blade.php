@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Task List')
@section('pagecontent')
<div class="page">
  <div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
      <div class="new-header-div-fot-btn">
        <div class="page-header">
          <h1 class="page-title">Task List</h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Task List</li>
          </ol>
        </div>
        <div class="payment-heading-box">
        <form method="POST" action="{{url('admin/tasklist')}}" class="custom-inline-form">
          @csrf
          <select name="tbl_column" required class="form-control" id="tbl_column">
            <option value="">-- Search By --</option>
            <option @if($tbl_column=='ticket_number') selected @endif value="ticket_number">Ticket Number</option>
            <option @if($tbl_column=='subject_name') selected @endif value="subject_name">Subject</option>
            
            <!--<option value="date_time">Date</option>-->
            
          </select>
          <input class="form-control" required type="text" name="i_name" value="{{@$i_name}}" autocomplete="off" placeholder="">
          <button class="btn btn-primary waves-effect waves-classic" type="submit" name="order_search">Search</button>
          <a href="{{url('admin/tasklist')}}" class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> <a class="btn btn-primary waves-effect waves-classic" style="float:right;" href="{{ route('task_form_create') }}"> <i class="icon md-plus" aria-hidden="true"></i> Add</a>
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
                <th class="bg-info sort ta" id="ticket_number" style="color:#fff !important;">Ticket Number</th>
                <th class="bg-info sort ta" id="department" style="color:#fff !important;">Department</th>
                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Customer</th>
                <th class="bg-info sort ta" id="subject" style="color:#fff !important;">Subject</th>
                <th class="bg-info sort ta" id="date_time" style="color:#fff !important;">Date & Time</th>
                <th class="bg-info sort ta" id="location" style="color:#fff !important;">Category</th>
                <th class="bg-info" style="color:#fff !important;">Action</th>
                  </thead>
              <tbody id="fb_table_body">
              
              @php
              
              
              
              $i=($datas->currentpage()-1)* $datas->perpage(); 
              
              
              
              @endphp
              
              
              
              @foreach($datas as $data)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{$data['ticket_number']}}</td>
                <td>{{ getDepartmentName($data['department_name'])}}</td>
                <td>{{ getCustomerName($data['customer_name'])}}</td>
                <td>{{ $data['subject_name']}}</td>
                <td> {{ globalDateformat($data['date_time'])}} </td>
                <td> {{ getCategoryName($data['category_name'])}} </td>
                <td style="text-align:center;">
                    <a title="Edit" href="{{url('admin/edit_task/'.$data['_id'])}}" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-edit" aria-hidden="true"></i></a> 
                    @if($data['data_status']!=1)
                    <span id="del_{{$data['_id']}}"><a title="Delete" onclick="return confirm('Are you sure you want to delete?');" href="{{url('admin/delete_task/'.$data['_id'])}}" class="btn btn-pure btn-danger icon waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i></a></span>
                    <span id="pi_{{$data['_id']}}"><a href="javascript:void(0)" onclick="return insertIntoFms('{{$data['_id']}}');" title="Insert into FMS"><i class="icon md-plus-circle-o" aria-hidden="true"></i></a></span>
                    @else
                    <a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>
                    @endif
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
          <div class="pagination-wrap">{{ $datas->links() }}</div>
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
@endsection