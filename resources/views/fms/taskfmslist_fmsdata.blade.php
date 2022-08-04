@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Task FMS')
@section('pagecontent')
@section('pagecontent')

<link rel="stylesheet" href="{{ URL::asset('public/multiselect/bootstrap-multiselect.css') }}" type="text/css"/>
<style type="text/css">
#fms_data input.form-control{ min-width:100px; padding-left:5px; padding-right:5px; }
#fms_data input[type="date"]{ width:100px; -webkit-appearance:none; appearance:none; }
#fms_data select{ width:100px; }
.date_with_dmy, .date_with_time{ background-color: #fffbfb !important; opacity: 1;}
#ui-datepicker-div {  z-index: 9999999 !important;}
.multiselect-group input {
  display: none;
}
.multiselect-item.multiselect-group {
  background-color: #e8e8e8;
}
</style>

<div class="page no-border taskfmsdata-page fms-page-task fms-page-new" id="fms_data">
  <div class="page-content"> 
    <!-- Panel Table Add Row -->
	<?php //pr($req); die('==='); ?>
    <div class="panel">
      <div class="panel-body">
        <div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>
        <form method="get">
          <table class="table table-bordered table-hover table-striped tbale_header fixed_table taskfmsdata-table" cellspacing="0" id="fmsdataTable">
            <thead>
              <tr class="tbale_header task_top_fix top-row">
                <th colspan="11" class="text-center font-weight-bold task_left_fix purple-bg-top">FMS : LEAD TO QUOTATION</th>
                <th colspan="23" class="text-left font-weight-bold  black-bg same-border"></th>
              </tr>
              <tr class="tbale_header task_top_fix top-row text-transform-none">
                <th class="text-center task_left_fix black-bg ticket-number-th ">Ticket No.
                  <div class="table-input">
                    <input autocomplete="off" value="{{$req->ticket_number}}" type="text" name="ticket_number" class="form-control ui-autocomplete-input" >
                  </div></th>
                <th class="text-center task_left_fix black-bg customer-th">Customer
                  <div class="table-input multy-select-box">
                    <select id="item-select" multiple="multiple" name="customer_name[]" >                      
						@foreach($companylist as $company)
						<option @if(@in_array($company['id'],[])) selected @endif value="{{$company['id']}}" >{{$company['company_name']}}</option>                  
						@endforeach    
                    </select>
					
                  </div>
                  <input type="hidden" name="" id="item_hidden" value="">
                </th>
                <th class="text-center task_left_fix black-bg task-category-th">Department
                  <div class="table-input multy-select-box">
                    <select id="item-select-department" multiple="multiple" name="department[]" >                      
                    @foreach($departmentlist as $dep)    
                    <option @if(@in_array($dep['id'],[])) selected @endif value="{{$dep['id']}}" >{{$dep['department_name']}}</option>                      
                    @endforeach
                    </select>
                  </div>
                </th>
				
                <th class="text-center task_left_fix black-bg task-category-th">Task Category
                  <div class="table-input multy-select-box">
                      <select id="item-select-category" multiple="multiple" name="item_category[]" >
                   
					<?php
					
                    $depArr = array();
                    foreach($item_categories as $category)
                    {
                    if(!in_array( $category['department'], $depArr)){
                    $depArr[] = $category['department'];
                    ?>
                    <optgroup label="{{$category['department']}}">
                    <?php }?>
                    <option @if(@in_array($category['id'],[])) selected @endif value="{{$category['id']}}" >{{$category['task_category']}}</option>
                    <?php }
                    ?>
                    </optgroup>
                    </select>
                  </div>
                </th>
                <th class="text-center task_left_fix black-bg date-th">Email Date
                  <div class="table-input ">                    
                    <input id="from_date" value="{{$req->from_date}}" placeholder="From" name="from_date" autocomplete="off" type="text" >
                  </div>
                  <div class="table-input">
                    <input value="{{$req->to_date}}" name="to_date" placeholder="To" autocomplete="off" id="to_date" type="text" >
                  </div></th>
                <th class="text-center task_left_fix black-bg email-subject-th">Email Subject
                  <div class="table-input">
                    <input type="text" value="{{$req->subject_name}}" autocomplete="off" name="subject_name" class="form-control ui-autocomplete-input" >
                  </div></th>
                  <th class="text-center task_left_fix black-bg note-th">Notes
                  <div class="table-input">
                    <input type="text" value="{{$req->task_notes}}" autocomplete="off" name="task_notes" class="form-control ui-autocomplete-input" >
                  </div></th>
                  <th class="text-center task_left_fix black-bg assigned-by-th">Assigned By
                  <div class="table-input multy-select-box">
                    <select id="item-select-user" multiple="multiple" name="user_id[]" >
                      
    @foreach($assigned_by_user as $user)
    
                      <option @if(@in_array($user['id'],[])) selected @endif value="{{$user['id']}}" >{{$user['full_name']}}</option>
                      
    @endforeach
    
                    </select>
                  </div>
                  </th>
                <th class="text-center task_left_fix black-bg assigned-to-th">Assigned To
                  @if(getUserFMSrole()==1)
                  <div class="table-input multy-select-box">
                    <select id="item-select-user" multiple="multiple" name="user_name[]" >
                      
    @foreach($userlist as $user)
    
                      <option @if(@in_array($user['id'],[])) selected @endif value="{{$user['id']}}" >{{$user['full_name']}}</option>
                      
    @endforeach
    
                    </select>
                  </div>
                  @endif </th>
                  
                <th class="text-center task_left_fix black-bg date-th">Created On
                  <div class="table-input ">
                    <input id="from_date_create" placeholder="from" value="{{$req->from_date_create}}" name="from_date_create" autocomplete="off" type="text" >
                  </div>
                  <div class="table-input">
                    <input value="{{$req->to_date_create}}" placeholder="To" name="to_date_create" autocomplete="off" id="to_date_create" type="text" >
                  </div></th>
                <th class="text-center task_left_fix black-bg status-th">Status <br/>
                  <select name="status" id="status">
                    <option value="">--Select--</option>
                    <option @if($req->status==1) selected @endif value="1">Win</option>
                    <option @if($req->status==2) selected @endif value="2">Lost</option>
                    <option @if($req->status==3) selected @endif value="3">On hold</option>
                    <option @if($req->status==99 || empty($req->status)) selected @endif value="99">Pending</option>
                  </select>
                </th>
                <th colspan="1" class="black-bg replies-th">Replies<br/>
                <select name="replies_status" id="replies_status">
                    <option value="">--All--</option>
                    <option @if($req->replies_status==999) selected @endif value="999">Pending</option>
                    <option @if($req->replies_status==1) selected @endif value="1">Completed</option>
                  </select>
                </th>
                <th colspan="22" class="black-bg same-border"></th>
              </tr>
            </thead>
            <tbody id="fmsTable">
            
            @php
            $i=0;
            @endphp
			
			<?php //pr($data); die;  ?>
            @foreach($data as $list)
			<?php 
					$list = (array) $list;
				//pr($list); die;  ?>
			<?php //echo getUserFMSrole(); pr($list); die;  ?>
            <tr class="tbale_header text-center ">
              <td class="text-center task_left_fix">{{$list['ticket_number']}}</td>
              <td class="text-center task_left_fix">
				
					<?php 
						if(array_key_exists($list['customer_name'],$cuslist_arr)){
							echo $cuslist_arr[$list['customer_name']];
						}
					?>
			</td>
              <td class="text-center task_left_fix">{{$resArr_dept[$list['department_name']]}}</td>
              <td class="text-center task_left_fix">
					
						{{@$item_cat_arr[$list['category_name']]}}
					
				</td>
              <td class="text-center task_left_fix">{{globalDateformatFMSDate($list['date_time'])}}</td>
              <td class="text-center task_left_fix">{{$list['subject_name']}}</td>
              <td class="text-center task_left_fix" id="notes-{{$list['id']}}">
              @if(@$list['task_notes']!='')
              <a data-toggle="modal" data-target="#myModal-notes" id="{{@$list['id']}}" class="select-notes" href="javascript:void(0)">Note</a>
              @else
              <i data-toggle="modal" data-target="#myModal-notes" style="font-size:12px; cursor:pointer;" id="{{$list['id']}}" class="fas fa-edit select-notes" aria-hidden="true"></i>
              @endif
              </td>
              <td class="text-center task_left_fix">{{@$assigned_by_arr[$list['user_id']]}}</td>
				<td class="text-center task_left_fix">
					<?php 
						if(array_key_exists($list['user_name'],$userlist_arr)){
							echo $userlist_arr[$list['user_name']];
						}
					?>
				</td>
              
              <td class="text-center task_left_fix">{{globalDateformatFMSDate($list['created_at'])}}</td>
              <td class="text-center task_left_fix" id="status-{{$list['id']}}"> 
                @if(getUserFMSrole()==1)
					@if($list['status']==1)
						<i style="font-size:16px; cursor:pointer;" id="status-{{$list['id']}}" class="fas fa-check-circle @if($list['status']==1)greentext @endif status-update" aria-hidden="true"></i>
					@elseif($list['status']==0) 
						<i style="font-size:16px; cursor:pointer;" id="status-{{$list['id']}}" class="fas fa-check-circle status-update" aria-hidden="true"></i>
					@elseif($list['status']==2) 
						<i style="font-size:16px; cursor:pointer;" id="status-{{$list['id']}}" class="fas fa-check-circle redtext status-update" aria-hidden="true"></i>
					@elseif($list['status']==3) 
						<i style="font-size:16px; cursor:pointer;" id="status-{{$list['id']}}" class="fas fa-check-circle bluetext status-update" aria-hidden="true"></i>
					@endif
				@else 
						<i style="font-size:16px;" class="fas fa-check-circle @if($list['status']==1)greentext @elseif($list['status']==2) redtext @elseif($list['status']==3) bluetext @endif" aria-hidden="true"></i>
				@endif
			</td>
				<?php 
					$j=0; 
					$repl = getFmsreplyDataByFmsId($list['id']);
					
				?>
				@for($i=0;$i<=19;$i++)
					<td class="text-center time-stamp-td" id="{{@$list['id']}}-{{$i}}"> 
						<?php 
							//pr($repl);
							if(array_key_exists($i, $repl)){
								//echo strtotime($repl[$i]->actual); die;
								$pl = strtotime($repl[$i]->planned);
								$acl = strtotime($repl[$i]->actual);
								//var_dump($acl);
								//pr($repl[$i]);
						?>
							@if(@$pl>0 && ($acl<0 || $acl==false) ) 
								<a data-toggle="modal" title="{{globalDateformatFMSDate(@$repl[$i]->planned)}}" id="{{@$list['id']}}-{{$i}}" class="replies_data" data-target="#myModal" href="javascript:void(0);"><i style="font-size:16px;" class="icon md-time" aria-hidden="true"></i></a>
								@if(getUserFMSrole()==1)
								<a data-toggle="modal" cus="{{globalDateformatFMS(@$repl[$i]->planned)}}" title="Date Update" id="{{@$list['id']}}-{{$i}}" class="date-update-data" data-target="#myModal-date-update" href="javascript:void(0);" style="float:right;"><i style="font-size:12px;" class="icon md-edit" aria-hidden="true"></i>
								</a>
								@endif 
							@elseif($acl>0 || $acl==true)
								<?php  
										$text_cls='';
										if(@$repl[$i]->difference<2 && @$repl[$i]->type==1){
											$text_cls='greentext';
										}elseif(@$repl[$i]->difference<2 && @$repl[$i]->type==2){
											$text_cls='greentab';
										}elseif(@$repl[$i]->difference>=2 && @$repl[$i]->type==1){
											$text_cls='redtext';
										}elseif(@$repl[$i]->difference>=2 && @$repl[$i]->type==2){
											$text_cls='redtab';
										}
								?>
								
								<span class="{{ $text_cls }}">{{globalDateformatFMSDate(@$repl[$i]->actual)}}</span>
							@endif
						<?php }?>
					</td>
				@endfor 
			  </tr>
            @php
            $i++;
            @endphp
            @endforeach
              </tbody>
            
          </table>
          <div class="from-filter custom-from-filter">
            
            <div class="blankdiv">&nbsp;</div>
            
            <div class="submit-btn-wrap">
            <input type="submit" value="Search" class="filter btn-info  waves-effect waves-classic">
            <a href="{{url('admin/taskfmsdata/')}}" class="btn btn-info waves-effect waves-classic waves-effect waves-classic" role="button">Reset</a></div>
            
            
            <div class="nav-box fms-pagination">{!! $data->appends(Request::all())->links('pagination::bootstrap-5') !!}</div>
            
          </div>
        </form>
      </div>
    </div>
    <!-- End Panel Table Add Row --> 
  </div>
</div>
<div class="modal fade " id="myModal" role="dialog">
  <div class="modal-dialog modal-sm"> 
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">REPLIES</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="department_id">
        <div class="button-box">
          <button class="btn btn-success general-reply">General Reply</button>
          <button class="btn btn-success quotation-sent">Quotation Sent</button>
        </div>
      </div>
      <!--<div class="modal-footer">--> 
      <!--  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>--> 
      <!--</div>--> 
    </div>
  </div>
</div>
<div class="modal fade " id="myModal-notes" role="dialog">
  <div class="modal-dialog modal-sm"> 
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">NOTES</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="department_id_notes">
        <div class="button-box">
        <div class="form-group" style="width:100%;"><textarea class="form-control" id="notes_value_update"></textarea></div>
          <button class="btn btn-success notes-update">Update</button>
        </div>
      </div>
    </div>
  </div>
</div>
<button id="status-click-btn" data-toggle="modal" data-target="#myModal-status" style="display:none;"></button>
<div class="modal fade " id="myModal-status" role="dialog">
  <div class="modal-dialog modal-sm"> 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">STATUS</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="department_id_status">
        <input type="hidden" id="table_id" value="23">
        <div class="button-box">
        <div class="form-group" style="width:100%;">
            <select class="form-control" id="status_value_update">
                <option value="">-- Select --</opion>
                <option value="1">Win</option>
                <option value="2">Lost</option>
                <option value="3">On hold</option>
                <option value="0">Pending</option>
            </select>
            </div>
          <button class="btn btn-success status-update-btn">Update</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="myModal-date-update" role="dialog">
  <div class="modal-dialog modal-sm"> 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">DATE UPDATE</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="department_id_date_update">
        <div class="button-box">
        <div class="form-group" style="width:100%;">
            <input type="text" class="form-control date-update-db">
            </div>
          <button class="btn btn-success date-update-btn">Update</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_script')
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.js"></script> 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon-i18n.min.js"></script> 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-sliderAccess.js"></script> 
<script type="text/javascript" src="{{ URL::asset('public/multiselect/bootstrap-multiselect.js') }}"></script> 
@endsection

@section('custom_validation_script') 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script> 
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script>
$.noConflict();
</script> 
<script>
$(document).ready(function() {
$("table.fixed_table tr .task_left_fix").each(function() {
 var x = $(this).offset();
 /*alert("Top position: " + x.top + " Left position: " + x.left);
   console.log(x.left + "px" + " " + x.top + "px");*/
 $(this).css({ "left": x.left - 0 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});

$("table.fixed_table tr.task_top_fix th").each(function() {
 var y = $(this).offset();
 /*alert("Top position: " + x.top + " Left position: " + x.left);
   console.log(x.left + "px" + " " + x.top + "px");*/
 //$(this).css({ "top": y.top - 123 + "px"});
 $(this).css({ "top": y.top - 60 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});

});
</script> 
<script>
    $(function(){
        $(document).on('click','.replies_data',function(){
            var id = $(this).attr('id');
            $('#department_id').val(id);
        });
    });
</script> 
<script>
    $(function(){
        $(document).on('click','.general-reply',function(){
            var department_id = $('#department_id').val();
            var table_id = '23';
            var type = "1";
            if(department_id!='')
            {
                $.ajax({

				type:'POST',

				url:"{{ url('admin/ajax_insert_department_data')}}",

				data:{department_id:department_id,type:type,table_id:table_id},

				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

				success:function(res){
                    //console.log(res); return false;
                    var returnedData = JSON.parse(res);
					$('#'+department_id).html('<span class='+returnedData.diff+'>'+returnedData.actual_date_time+'</span>');
					$('.close').trigger('click');
					
				}

			});
            }
        });
    });
</script> 
<script>
    $(function(){
        $(document).on('click','.quotation-sent',function(){
            var department_id = $('#department_id').val();
            var type = "2";
            var table_id = '23';
            if(department_id!='')
            {
                $.ajax({

				type:'POST',

				url:"{{ url('admin/ajax_insert_department_data')}}",

				data:{department_id:department_id,type:type,table_id:table_id},

				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

				success:function(res){
                    //alert(res); return false;
                    var returnedData = JSON.parse(res);
                    //$('#status-'+result[0]).html('<i style="font-size:16px;" class="fas fa-check-circle greentext" aria-hidden="true"></i>');
					$('#'+department_id).html('<span class='+returnedData.diff+'>'+returnedData.actual_date_time+'</span>');
					$('.close').trigger('click');
					
				}

			});
            }
        });
    });
</script> 
<script>
    $(function(){
       $(document).on('click','.status-update-undo',function(){
          var id = $(this).attr('id');
          var result = id.split('-');
          var table_id = '23';
          var verify = confirm("Do you want to change the status?"); 
          if(verify)
          {
              //alert('hello'); return false;
          $.ajax({
            type:'POST',
            url:"{{ url('admin/ajax_status_update_reopen')}}",
            data:{department_id:result[1],table_id:table_id},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(res){
            //alert(res); return false;
            if(res==1)
            {
            $('#status-'+result[1]).html('<i id='+id+' style="font-size:16px;cursor:pointer;" class="fas fa-check-circle status-update" aria-hidden="true"></i>');
            }
            }
            });    
          }
       }); 
    });
</script>
<script>
    $(function(){
       $(document).on('click','.status-update',function(){
          var id = $(this).attr('id');
          var result = id.split('-');
          var table_id = '23';
          //var verify = confirm("Do you want to change the status?");
          //if(verify)
          //{
              //alert(); return false;
            $('#status-click-btn').trigger('click');
            $('#department_id_status').val(result[1]);
          //}
       }); 
    });
</script> 
<script type="text/javascript">
	$(document).ready(function() {
			
		$('#item-select,#item-select-user,#item-select-category,#item-select-department').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			onChange: function(option, checked) {
				var item_hidden = $('#item_hidden').val();
				var arr = '';
				var item_hidden_arr=[];
				if(item_hidden.length>0){
					item_hidden_arr = item_hidden.split(',');
				}
				if (checked === true) {					
					item_hidden_arr.push(option.val());
					$('#item_hidden').val(item_hidden_arr.toString());
				}else{
					var arr = item_hidden_arr;
					arr = arr.filter(function(item) {
						return item !== option.val()
					})
					$('#item_hidden').val(arr.toString());					
				}
				
				//console.log(item_hidden_arr);
				//console.log(arr);
				
            }
		});
				
	});
</script>
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script> 
<script>
	jQuery(document).ready(function () {
	jQuery('#from_date,#to_date,#from_date_create,#to_date_create').datetimepicker({
	format: 'd-m-Y',
	timepicker: false,
	autoclose: true, 
	minView: 2
	});
	});
</script>
<script>
	jQuery(document).ready(function () {
	jQuery('.date-update-db').datetimepicker({
	format: 'd-m-Y H:i:s',
	autoclose: true, 
	minView: 2
	});
	});
</script>
<script>
    $(function(){
       $(document).on('click','.select-notes',function(){
          var id = $(this).attr('id');
          $('#department_id_notes').val(id);
          var table_id = '23';
          if(id!='')
          {
            $.ajax({
            type:'POST',
            url:"{{ url('admin/ajax_select_notes_data')}}",
            data:{id:id,table_id:table_id},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(res){
            //console.log(res); return false;
            if(res!='')
            {
            $('#notes_value_update').val(res);
            }else{
            $('#notes_value_update').val('');    
            }
            }
            });
          }
       }); 
    });
</script>
<script>
    $(function(){
       $(document).on('click','.notes-update',function(){
         var id = $('#department_id_notes').val();
         var notes_value_update = $('#notes_value_update').val();
         var table_id = '23';
          $.ajax({
            type:'POST',
            url:"{{ url('admin/ajax_update_notes_data')}}",
            data:{id:id,table_id:table_id,notes_value_update:notes_value_update},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(res){
            //console.log(res); return false;
            if(notes_value_update!='')
            {
            $('.close').trigger('click');
            $('#notes-'+id).html('<a data-toggle="modal" data-target="#myModal-notes" id='+id+' class="select-notes" href="javascript:void(0)">Note</a>');
            }else{
            $('.close').trigger('click');    
            $('#notes-'+id).html('<i data-toggle="modal" data-target="#myModal-notes" style="font-size:12px; cursor:pointer;" id='+id+' class="fas fa-edit select-notes" aria-hidden="true"></i>');
            }
            }
            });   
       });
    });
</script>
<script>
    $(function(){
        $(document).on('click','.status-update-btn',function(){
            var department_id_status = $('#department_id_status').val();
            var table_id = '23';
            var status_value_update = $('#status_value_update').val();
            if(department_id_status!='' && table_id!='' && status_value_update!='')
            {
            $.ajax({
            type:'POST',
            url:"{{ url('admin/ajax_status_update')}}",
            data:{department_id_status:department_id_status,table_id:table_id,status_value_update:status_value_update},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(res){
            //alert(res); return false;
            if(res!='')
            {
            if(res==1){
                var status_col = 'greentext';
            }
            else if(res==0)
            {
               var status_col = ''; 
            }else if(res==2)
            {
               var status_col = 'redtext'; 
            }else if(res==3)
            {
               var status_col = 'bluetext'; 
            }
            $('#status-'+department_id_status).html('<i style="font-size:16px;cursor:pointer;" id=status-'+department_id_status+' class="fas fa-check-circle '+status_col+' status-update" aria-hidden="true"></i>');
            $('.close').trigger('click');
            }
            }
            });
            }
        });
    });
</script>
<script>
    $(function(){
        $(document).on('click','.date-update-data',function(){
           var id = $(this).attr('id');
           var date_update = $(this).attr('cus');
           $('#department_id_date_update').val(id);
           $('.date-update-db').val(date_update);
        });
		
		$("th.task_left_fix").click(function(e) {
			$("th.task_left_fix").removeClass("active");
            $(this).addClass("active");			
        });
		
    });
</script>
<script>
    $(function(){
        $(document).on('click','.date-update-btn',function(){
           var id_array = $('#department_id_date_update').val();
           var date_update_db = $('.date-update-db').val();
           var table_id = '23';
           if(id_array!='' && date_update_db!='' && table_id!='')
           {
            $.ajax({
            type:'POST',
            url:"{{ url('admin/ajax_date_update_data')}}",
            
            data:{id_array:id_array,date_update_db:date_update_db,table_id:table_id},
            
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            
            success:function(res){
            //console.log(res); return false;
            //var returnedData = JSON.parse(res);
            //$('#'+department_id).html('<span class='+returnedData.diff+'>'+returnedData.actual_date_time+'</span>');
            alert('Planned date updated successfully.');
            $('.close').trigger('click');
            }
            });   
           }
        });
    });
</script>
<style>
.xdsoft_datetimepicker { z-index:9999999999999999; }
</style>

@endsection