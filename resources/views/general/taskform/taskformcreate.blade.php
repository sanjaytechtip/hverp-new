@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Task Form')
@section('pagecontent')
<?php //pr(getAllusers()); die;?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
  <div class="page-header">
    <h1 class="page-title">Add Task Form</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('taskfmsdata') }}">Task FMS : List</a></li>
      <li class="breadcrumb-item">Add Task Form</li>
    </ol>
  </div>
  <div class="page-content container-fluid">
    <div class="row justify-content-center"> @if (\Session::has('success'))
      <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
      </div>
      <br />
      @endif
      <div class="col-md-12">
        <div class="card">
          <div class="card-body_rename card-body">
            <h2 class="form-box form-box-heading task-form-list">Task Form <small><a href="{{ route('taskfmsdata') }}">Task FMS List</a></small></h2>
            <div class="custom-form-box">
              <form class="custom-form" action="{{url('admin/add_task_form')}}" id="task_form" method="POST" enctype="multipart/form-data" novalidate="novalidate">
                @csrf
                <div class="form-box rows  line_repeat_1">
                <div class="form-box col2 radio-buttons-wrap">
                    <label><input type="radio" value="1" checked name="task" /> New Enquiry</label>
                    <label><input type="radio" value="2" name="task" /> Old Enquiry</label>
                  </div>
                  
                   <div class="form-box col2 customer new-task">
                    <label for="customer">Customer Name<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                    <div class="list-add-new"><a href="{{url('admin/add-customer')}}" target="_blank" style="text-decoration:underline;">Add New</a> <a href="{{url('admin/customer-list')}}" target="_blank" style="text-decoration:underline;">List</a></div>
                  </div>
                
                  <div class="form-box col2">
                    <label for="department">Department<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="department_name" name="department_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                    <div class="list-add-new"><a href="{{url('admin/adddata/23')}}" target="_blank" style="text-decoration:underline;">Add New</a> <a href="{{url('admin/listdata/23')}}" target="_blank" style="text-decoration:underline;">List</a></div>
                  </div>
                  <div class="form-box col2 customer old-task" style="display:none;">
                    <label for="customer">Ticket Number<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="ticket_number" name="ticket_number" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                  </div>
                 
                  <div class="form-box col2 location new-task">
                    <label for="location">Task Category<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="category_name" name="category_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                    <div class="list-add-new"><a href="{{url('admin/adddata/14')}}" target="_blank" style="text-decoration:underline;">Add New</a> <a href="{{url('admin/listdata/14')}}" target="_blank" style="text-decoration:underline;">List</a></div>
                  </div>
                  
                  <div class="form-box col2 new-task">
                    <label for="discription">Email Subject<span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control" autocomplete="off" required name="subject_name" placeholder="Email Subject">
                  </div>
                  <div class="form-box col2 user new-task">
                    <label for="user">User<span class="required" aria-required="true">*</span></label>
                    <select class="required form-control" id="user_name" name="user_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">-- Select --</option>
                    </select>
                    <div class="list-add-new"><a href="{{url('admin/adddata/11')}}" target="_blank" style="text-decoration:underline;">Add New</a> <a href="{{url('admin/listdata/11')}}" target="_blank" style="text-decoration:underline;">List</a></div>
                  </div>
                  <div class="form-box col2">
                    <label for="discription">Date & Time<span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control filter-date" autocomplete="off" required name="date_time" placeholder="Date & Time">
                  </div>
                  <div class="form-box col2 new-task">
                    <label for="discription">Notes</label>
                    <textarea name="task_notes" maxlength="200" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-box submit-reset"></div>
                <div class="form-box col2">
                  <input type="submit" name="submit" class="form-control submit " id="" value="Submit">
                </div>
                <div class="form-box col2">
                  <input type="reset" name="reset" class="form-control reset " id="" value="Reset">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_validation_script') 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
<script>

	$(function () { 

		var deptArr = '<?php print_r(getAllDepart()); ?>';

		deptArr = JSON.parse(deptArr);

		var deptData = $.map(deptArr, function (value, key) { return { value: value, data: key }; });

		var deptHtml='<option value="">--Select--</option>';

		for(var i=0; i<deptData.length; i++){

			deptHtml+='<option value="'+deptData[i].data+'">'+deptData[i].value+'</option>';

		}

		$('#department_name').html(deptHtml);

	});

</script> 
<script>

	$(function () { 

		var catArr = '<?php print_r(getAllCat()); ?>';

		catArr = JSON.parse(catArr);

		var catData = $.map(catArr, function (value, key) { return { value: value, data: key }; });

		var catHtml='<option value="">--Select--</option>';

		for(var i=0; i<catData.length; i++){

			catHtml+='<option value="'+catData[i].data+'">'+catData[i].value+'</option>';

		}

		$('#category_name').html(catHtml);

	});

</script> 
<script>

	$(function () { 

		var custArr = '<?php print_r(getAllCustomer()); ?>';

		custArr = JSON.parse(custArr);

		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });

		var custHtml='<option value="">--Select--</option>';

		for(var i=0; i<custData.length; i++){

			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';

		}

		$('#customer_name').html(custHtml);

	});

</script> 
<script>

	$(function () { 

		var userArr = '<?php print_r(getAllusers()); ?>';

		userArr = JSON.parse(userArr);

		var userData = $.map(userArr, function (value, key) { return { value: value, data: key }; });

		var userHtml='<option value="">--Select--</option>';

		for(var i=0; i<userData.length; i++){

			userHtml+='<option value="'+userData[i].data+'">'+userData[i].value+'</option>';

		}

		$('#user_name').html(userHtml);

	});

</script> 
<script>
$(document).ready(function() {
    $("#task_form").validate({});
});
</script>
<link href="{{ url('public/css/jquery.datetimepicker.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ url('public/js/jquery.datetimepicker.full.js')}}"></script> 
<script>

	jQuery(document).ready(function () {

	jQuery('.filter-date').datetimepicker({

	format: 'd-m-Y H:i:s',

	autoclose: true, 

	minView: 2

	});

	});

	</script>
<style>
    .ui-tooltip.ui-widget { display:none; }
</style>
<script>
    $(document).ready(function(){
        $("input[type='radio']").click(function(){
            var radioValue = $("input[name='task']:checked").val();
            if(radioValue==1)
            {
            $('.new-task').show();
            $('.old-task').hide();
            }else{
            $('.new-task').hide();
            $('.old-task').show();
            }
        });
    });
</script> 
<script>
    $(function(){
        $(document).on('change','#department_name',function(){
            var dept_id = $(this).val();
            if(dept_id!='')
            {
                
                $.ajax({
                type:'POST',
                url:"{{ url('admin/ajax_search_ticket_number')}}",
                data:{dept_id:dept_id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(res){
                var ticketArr = JSON.parse(res);
                var ticketData = $.map(ticketArr, function (value, key) { return { value: value, data: key }; });
                var ticketHtml='<option value="">--Select--</option>';
                for(var i=0; i<ticketData.length; i++){
                ticketHtml+='<option value="'+ticketData[i].data+'">'+ticketData[i].value+'</option>';
                }
                $('#ticket_number').html(ticketHtml);
                }
                });
                
                
                $.ajax({
                type:'POST',
                url:"{{ url('admin/ajax_search_task_category')}}",
                data:{dept_id:dept_id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(res){
                var ticketArr = JSON.parse(res);
                var ticketData = $.map(ticketArr, function (value, key) { return { value: value, data: key }; });
                var ticketHtml='<option value="">--Select--</option>';
                for(var i=0; i<ticketData.length; i++){
                ticketHtml+='<option value="'+ticketData[i].data+'">'+ticketData[i].value+'</option>';
                }
                $('#category_name').html(ticketHtml);
                }
                });
            }
        });
    });
</script>
@endsection