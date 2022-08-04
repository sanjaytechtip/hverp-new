@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Task Form')
@section('pagecontent')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style type="text/css">
.table-wrap table{ border:1px solid #dddddd; font-size:14px }
.table-wrap th, .table-wrap td{ padding:10px 10px; border-bottom:1px solid #ddd; border-right:1px solid #ddd; }
.table-wrap th{ background:#fafbfb; white-space:nowrap; color:#999; font-weight:bold; padding:7px 10px; }
.purchaseorder-box{ clear:both; }
.purchaseorder-box .search-form{ margin-bottom:5px; }
.purchaseorder-box .search-form .search-form-box{ width:100%; clear:both; float:left; }
.purchaseorder-box .search-form .search-form-box > div{ float:left; margin:0 10px 10px 0; }
.purchaseorder-box .search-form hr{ clear:both; width:100%; }
.purchaseorder-box .search-form .search-form-box .contact-form{ width:250px; }
.purchaseorder-box .search-form .search-form-box .date-form{ width:180px; }
.purchaseorder-box .search-form .search-form-box > div label{ margin:0 0 2px; display:block; }
.purchaseorder-box .search-form .search-form-box > div input,
.purchaseorder-box .search-form .search-form-box > div select{ padding:8px 10px; border:1px solid #dddddd; }
.purchaseorder-box .search-form .search-form-box > div select{ padding-right:30px; display:inline-block; }
.purchaseorder-box .search-form .search-form-box .contact-form.right-amounts{ float:right; margin-right:0; }
.purchaseorder-box .search-form .search-form-box .contact-form.right-amounts label{ width:auto; display:inline-block; margin:0 5px 0 0; font-weight:bold; }
.purchaseorder-box .search-form .search-form-box .contact-form.right-amounts select{ width:calc(100% - 100px); }
.purchaseorder-box .table-wrap{ margin-bottom:15px; }
.purchaseorder-box .add-row{ width: 100%; clear: both; padding: 0 0 15px; overflow:hidden; border-bottom:1px solid #e0e0e0; }
.purchaseorder-box .add-left{ float: left; }
.purchaseorder-box .total-right{ float: right; margin-bottom:30px; }
.purchaseorder-box .total-right .subtotal-box{ border-bottom:1px solid #ddd; width: 300px; box-sizing: border-box; padding:8px 0 8px 30px; overflow: hidden; }
.purchaseorder-box .total-right .subtotal-box label{ margin:0; float:left; }
.purchaseorder-box .total-right .subtotal-box span{ float:right; }
.purchaseorder-box .total-right .subtotal-box.grand-total{ font-weight: bold; font-size: 150%;}
.purchaseorder-box .address-wrap{ clear:both; border-top:1px solid #e0e0e0; padding-top:20px; }
.purchaseorder-box .address-wrap label{ margin:0 0 2px; display:block; font-weight:bold; }
.purchaseorder-box .form-box{ margin-bottom:15px; }
.purchaseorder-box textarea{ height:110px; }
.purchaseorder-box .save-row{ padding:15px 0 0; clear: both; border-top:1px solid #e0e0e0; }
.delivery-address{ position:relative; z-index:99; display:inline-table; }
.delivery-address .dropdown-box{ position:absolute; background:#fff; top:99.99%; left:0; display:none; width:300px; }
.delivery-address.clicked .dropdown-box{ display:block; }
.delivery-address > a{ display:inline-table; padding:5px 20px 5px 0; }
.delivery-address > a:after { display: inline-block; width: 0; height: 0; margin-left: .2431rem; vertical-align: .2431rem; content: ""; border-top: .286rem solid; border-right: .286rem solid transparent;  border-bottom: 0; border-left: .286rem solid transparent; }
.delivery-address ul{ margin:0; padding:0; list-style:none; border:1px solid #e0e0e0; max-height:160px; overflow:auto; }
/* width */
.delivery-address ul::-webkit-scrollbar { width:10px; }
/* Track */
.delivery-address ul::-webkit-scrollbar-track { background:#f1f1f1; }
/* Handle */
.delivery-address ul::-webkit-scrollbar-thumb { background:#888; border-radius: 10px; }
/* Handle on hover */
.delivery-address ul::-webkit-scrollbar-thumb:hover { background:#555; }
.delivery-address ul li{ padding:10px; border-bottom:1px solid #e0e0e0; cursor:pointer; }
.delivery-address ul li.link-tab{ padding:0; }
.delivery-address ul li.link-tab a{ padding:10px; display:block; }
.delivery-address ul li.link-tab a:hover{ text-decoration:underline; }
.delivery-address ul li:hover{ background-color:#fafafa; }
.delivery-address ul li h2{ margin:0 0 8px; font-size:15px; }
.delivery-address ul li p:last-child{ margin-bottom:0; }
.table-wrap input { min-height:32px; color:#999; }
.table-wrap td{ height:32px; }
.typeahead { background-color: #FFFFFF; }
.tt-query { box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset; }
.tt-hint { color: #999999; }
.tt-menu { background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.2); border-radius: 8px; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); margin-top: 12px; padding: 8px 0; width: 190px; }
.tt-suggestion { font-size: 15px;  /* Set suggestion dropdown font size */ padding: 3px 10px; }
.tt-suggestion:hover { cursor: pointer; background-color: #0097CF; color: #FFFFFF; }
.tt-suggestion p { margin: 0; }
.dashboard .card { height: calc(100% - 90px); background:none;}
.table td, .table th { padding: 5px; vertical-align: top; border-top: 1px solid #e0e0e0; }
.two-button button{ float:left; border-radius:5px 0 0 5px; border-right:1px solid rgba(0, 0, 0, 0.1); padding:5px 15px !important; }
.two-button button + button{ border-radius:0 5px 5px 0; border-right:1px solid rgba(0, 0, 0, 0); outline:none; padding:5px 10px !important; }
.card-body{ background:#fff; -ms-flex: 1 1 auto; flex: 1 1 auto; padding: 1.429rem; }
.history-box{ margin-top:30px;  background:#fff; -ms-flex: 1 1 auto; flex: 1 1 auto; padding:0; }
.history-box h2{ font-size:15px; margin:0; padding:0 0 10px; border-bottom:1px solid #e0e0e0; }
.history-form{ padding:1.429rem; background:#fafbfb; display:none; }
.history-box.clicked .history-form{ display:block; }
.history-box.clicked .note-box{ display:none; }
.textarea-box{ padding:0 0 15px 0; }
.textarea-box textarea{ height:100px; }
.note-box{ padding:15px 0; }
label.error{color:#FF0000;}
/* for auto fill inputs */
.auto-fill-field{ background-color:#d1d1d1;}
.big-table table td input[type="number"] {  width:117px !important; }
/* for auto select option */
.autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-no-suggestion { padding: 2px 5px;}
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: bold; color: #000; }
.autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
.numbers-only{ width:80px !important; }
.color-field{ width:300px !important; }
</style>
<?php //pr(getAllCat()); die; ?>
<div class="page creat-fms-box">
  <div class="page-header">
    <h1 class="page-title">{{ __('Add Task Form') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item">{{ __('Add Task Form') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400"> </div>
    </div>
  </div>
  <div class="page-content container-fluid">
    <div class="row justify-content-center"> @if (\Session::has('success'))
      <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
      </div>
      <br />
      @endif
      <div class="col-md-12">
        <form class="" action="{{ url('admin/add_task_form_multiple') }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();">
          @csrf
          <div class="card">
          <div class="card-body task-form-create-multiple">
            <div class="row" style="margin:0;"> </div>
            <div class="purchaseorder-box">
              <div class="table-wrap big-table">
                <table class="editable-table table table-striped" id="editableTable1">
                  <thead>
                    <tr>
                      <th width="50">Enquiry</th>
                      <th>Customer Name</th>
                      <th>Department</th>
                      <th>Ticket Number</th>
                      <th>Task Category</th>
                      <th width="80">Email Subject</th>
                      <th width="80">User</th>
                      <th width="110">Date & Time</th>
                      <th width="120">Notes</th>
                      <th width="40">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody id="results">
                
                  @for($i=1;$i<=5;$i++)
                  <tr id="data-row-{{ $i }}">
                    <td data-text="Enquiry" class="enquiry-td"><div class="enquiry-task">
                        <label>
                          <input type="radio" value="1" cus="{{$i}}" checked name="row[{{$i}}][task]" />
                          New Enquiry</label>
                        <label>
                          <input type="radio" value="2" cus="{{$i}}" name="row[{{$i}}][task]" />
                          Old Enquiry</label>
                      </div></td>
                    <td data-text="Customer" class="customer-name-td"><select class="required form-control customer_name new-task-{{$i}}" name="row[{{$i}}][customer_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                      </select></td>
                    <td data-text="Department" class="department-td"><select class="required form-control department_name" cus="{{$i}}" name="row[{{$i}}][department_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                      </select></td>
                    <td data-text="Ticket Number" class="ticket-number-td"><select disabled class="required form-control ticket_number ticket-number-{{$i}} old-task-{{$i}}" name="row[{{$i}}][ticket_number]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                      </select></td>
                    <td data-text="Task Category" class="task-category-td"><select class="required form-control new-task-{{$i}} category_name category_name-{{$i}}" name="row[{{$i}}][category_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                      </select></td>
                    <td data-text="Subject Name" class="subject-name-td"><input type="text" class="form-control new-task-{{$i}}" autocomplete="off" required name="row[{{$i}}][subject_name]" placeholder="Email Subject"></td>
                    <td data-text="User" class="user-td"><select class="required form-control user_name new-task-{{$i}}" name="row[{{$i}}][user_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                        <option value="">-- Select --</option>
                      </select></td>
                    <td data-text="Date & Time" class="date-time-td"><input type="text" class="form-control filter-date" autocomplete="off" required name="row[{{$i}}][date_time]" placeholder="Date & Time"></td>
                    <td data-text="Notes" class="notes-td"><textarea style="height:40px;" class="new-task-{{$i}}" name="row[{{$i}}][task_notes]" maxlength="200"></textarea></td>
                    <td class="delete-td"><a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                  </tr>
                  @endfor
                    </tbody>
                
                </table>
              </div>
              <div class="add-row">
                <div class="add-left">
                  <div class="dropdown two-button">
                    <button type="button" class="btn btn-primary add-new-line" cus="1">+ Add a new line</button>
                  
                    <!--<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> </button>-->
                  
                    <!--<div class="dropdown-menu"> <a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> <a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> <a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> </div>-->
                  
                  </div>
                </div>
              </div>
              <div class="save-row">
                <div class="save-right float-right">
                  <button type="submit" name="approve" value="approve" class="btn btn-success">Submit</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  function removeRow(row){
    if(row!=1){
    $('#data-row-'+row).remove();
	}
  }
  </script>
@endsection

@section('custom_validation_script')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $("#invoiceform").validate({});
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

	/* $(function () {
		var buyerArr = '<?php //print_r(getAllBuyer()); ?>';
		buyerArr = JSON.parse(buyerArr);
		var buyerData = $.map(buyerArr, function (value, key) { return { value: value, data: key }; });
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			buyerHtml+='<option value="'+buyerData[i].data+'">'+buyerData[i].value+'</option>';
		}
		$('#customer_name').html(buyerHtml);
		$('#buyer_name').html(buyerHtml);
	}); */

</script>
<script>
	$(function () {
		var deptArr = '<?php print_r(getAllDepart()); ?>';
		deptArr = JSON.parse(deptArr);
		var deptData = $.map(deptArr, function (value, key) { return { value: value, data: key }; });
		var deptHtml='<option value="">--Select--</option>';
		for(var i=0; i<deptData.length; i++){
			deptHtml+='<option value="'+deptData[i].data+'">'+deptData[i].value+'</option>';
		}
		$('.department_name').html(deptHtml);
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
		$('.category_name').html(catHtml);
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
		$('.customer_name').html(custHtml);
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
		$('.user_name').html(userHtml);
	});
</script>
<script>
    $(function(){
        $(document).on('change','.department_name',function(){
            var de_id = $(this).attr('cus');
            var dept_id = $(this).val();
            if(dept_id!='')
            {
                $.ajax({
					type:'POST',
					url:"{{ url('admin/ajax_search_ticket_number')}}",
					data:{dept_id:dept_id},
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					success:function(res){
											//console.log(res); return false;
											var ticketArr = JSON.parse(res);
											var ticketData = $.map(ticketArr, function (value, key) { return { value: value, data: key }; });
											var ticketHtml='<option value="">--Select--</option>';
											for(var i=0; i<ticketData.length; i++){
											ticketHtml+='<option value="'+ticketData[i].data+'">'+ticketData[i].value+'</option>';
											}
											$('.ticket-number-'+de_id).html(ticketHtml);
										}
                });

              
                $.ajax({
					type:'POST',
					url:"{{ url('admin/ajax_search_task_category')}}",
					data:{dept_id:dept_id},
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					success:function(res){
											//console.log(res); return false;
											var ticketArr = JSON.parse(res);
											var ticketData = $.map(ticketArr, function (value, key) { return { value: value, data: key }; });
											var ticketHtml='<option value="">--Select--</option>';
											for(var i=0; i<ticketData.length; i++){
											ticketHtml+='<option value="'+ticketData[i].data+'">'+ticketData[i].value+'</option>';
											}
											$('.category_name-'+de_id).html(ticketHtml);
										}
                });
            }
        });
    });
</script>
<script>
    $(function(){
        var rowCount = $('#editableTable1 >tbody >tr').length;
		$(document).on('click','.add-new-line',function(){
	       rowCount++;
	       var html = '<tr id="data-row-'+rowCount+'"> <td data-text="Enquiry" class="enquiry-td"><div class="enquiry-task"> <label> <input type="radio" value="1" cus="'+rowCount+'" checked name="row['+rowCount+'][task]" /> New Enquiry</label> <label> <input type="radio" value="2" cus="'+rowCount+'" name="row['+rowCount+'][task]" /> Old Enquiry</label> </div></td> <td data-text="Customer" class="customer-name-td"><select class="required form-control customer_name customer-name-'+rowCount+' new-task-'+rowCount+'" name="row['+rowCount+'][customer_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1"> <option value="">-- Select --</option> </select></td> <td data-text="Department" class="department-td"><select class="required form-control department_name department-name-'+rowCount+'" cus="'+rowCount+'" name="row['+rowCount+'][department_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1"> <option value="">-- Select --</option> </select></td> <td data-text="Ticket Number" class="ticket-number-td"> <select disabled class="required form-control ticket_number ticket-number-'+rowCount+' old-task-'+rowCount+'" name="row['+rowCount+'][ticket_number]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1"> <option value="">-- Select --</option> </select> </td> <td data-text="Task Category" class="task-category-td"><select class="required form-control new-task-'+rowCount+' category_name category_name-'+rowCount+'" name="row['+rowCount+'][category_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1"> <option value="">-- Select --</option> </select></td> <td data-text="Subject Name" class="subject-name-td"><input type="text" class="form-control new-task-'+rowCount+'" autocomplete="off" required name="row['+rowCount+'][subject_name]" placeholder="Email Subject"></td> <td data-text="User" class="user-td"><select class="required form-control user_name user-name-'+rowCount+' new-task-'+rowCount+'" name="row['+rowCount+'][user_name]" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1"> <option value="">-- Select --</option> </select></td> <td data-text="Date & Time" class="date-time-td"><input type="text" class="form-control filter-date" autocomplete="off" required name="row['+rowCount+'][date_time]" placeholder="Date & Time"></td> <td data-text="Notes" class="notes-td"><textarea class="new-task-'+rowCount+'" style="height:40px;" name="row['+rowCount+'][task_notes]" maxlength="200"></textarea></td> <td class="delete-td"><a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';

	        $('#results').append(html);

	        $(".customer-name-"+rowCount).select2({
	            placeholder:'Type name',
	            minimumInputLength: 1
	        });
	      
	      
	        $(".department-name-"+rowCount).select2({
	            placeholder:'Type name',
	            minimumInputLength: 1
	        });
	      
	        $(".category_name-"+rowCount).select2({
	            placeholder:'Type name',
	            minimumInputLength: 1
	        });
	      
	        $(".user-name-"+rowCount).select2({
	            placeholder:'Type name',
	            minimumInputLength: 1
	        });
	        $(".ticket-number-"+rowCount).select2({
	            placeholder:'Type name',
	            minimumInputLength: 1
	        });
	      
	      
            var custArr = '<?php print_r(getAllCustomer()); ?>';
            custArr = JSON.parse(custArr);
          
            var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });
          
            var custHtml='<option value="">--Select--</option>';
          
            for(var i=0; i<custData.length; i++){
            custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';
            }

	       $(".customer-name-"+rowCount).html(custHtml);
            jQuery('.filter-date').datetimepicker({
            format: 'd-m-Y H:i:s',
            autoclose: true,
            minView: 2
            });
          
            var deptArr = '<?php print_r(getAllDepart()); ?>';
            deptArr = JSON.parse(deptArr);
            var deptData = $.map(deptArr, function (value, key) { return { value: value, data: key }; });
            var deptHtml='<option value="">--Select--</option>';
            for(var i=0; i<deptData.length; i++){
            deptHtml+='<option value="'+deptData[i].data+'">'+deptData[i].value+'</option>';
            }
            $('.department-name-'+rowCount).html(deptHtml);
          
            var userArr = '<?php print_r(getAllusers()); ?>';
            userArr = JSON.parse(userArr);
            var userData = $.map(userArr, function (value, key) { return { value: value, data: key }; });
            var userHtml='<option value="">--Select--</option>';
            for(var i=0; i<userData.length; i++){
            userHtml+='<option value="'+userData[i].data+'">'+userData[i].value+'</option>';
            }
            $('.user-name-'+rowCount).html(userHtml);

       });

    });

</script>
<script>
    $(document).ready(function(){
        $(document).on('click',"input[type='radio']",function(){
            var id = $(this).attr('cus');
            var radioValue = $("input[name='row["+id+"][task]']:checked").val();
            if(radioValue==1)
            {
            $('.new-task-'+id).attr('disabled',false);
            $('.old-task-'+id).attr('disabled',true);
            }else{
            $('.new-task-'+id).attr('disabled',true);
            $('.old-task-'+id).attr('disabled',false);
            }
        });
    });
</script>
@endsection 