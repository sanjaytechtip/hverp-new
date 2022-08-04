@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'From Create Page')



@section('pagecontent')



<div class="page creat-fms-box">

  <div class="page-header">

    <h1 class="page-title">{{ __('Add Form') }}</h1>

    <ol class="breadcrumb">

      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>

      <li class="breadcrumb-item">{{ __('Add Form') }}</li>

    </ol>

    <div class="page-header-actions custom-page-heading">

      <div class="row no-space w-400">

          <div class="col-sm-6">

          <!--<div class="counter">-->

          <!--<span class="counter-number font-weight-medium">DATE</span>-->

          <!--  <div class="counter-label">{{ DateFormate(date('d-M-Y')) }}</div>-->

          <!--   </div>-->

        </div>

      </div>

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

            <form class="" action="{{ url('admin/formadd') }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();">

              @csrf

              <div class="card">

                <div class="card-body">

                  <div class="row" style="margin:0;">

                    

                    <div class="col-lg-6 col-md-6 col-sm-12">

                    <div class="row">

						<div class="col-lg-4 col-md-4 col-sm-12">

							<div class="contact-form bs-example auto-complete-div" style="margin-top:10px;">

							<label for="contact">Form Name<span class="required" style="color: #e3342f;">*</span></label>						

								<input type="text" name="form_name" id="form_name" class="form-control required" />						

							</div>

							</div>

							<div class="col-lg-4 col-md-4 col-sm-12">

							<div class="contact-form bs-example auto-complete-div" style="margin-top:10px;">

							<label for="contact">Module Name<span class="required" style="color: #e3342f;">*</span></label>						

							<input class="form-control required" id="module_name" name="module_name">

							</div>

							</div>
							<div class="col-lg-4 col-md-4 col-sm-12">

							<div class="contact-form bs-example auto-complete-div" style="margin-top:10px;">

							<label for="contact">Table Name<span class="required" style="color: #e3342f;">*</span></label>						

							<input class="form-control required" id="table_name" name="table_name">

							</div>

							</div>

							

						</div>

						

						

                      </div>

                      </div>

                      <div class="purchaseorder-box">

                    <hr />

                    <div class="table-wrap big-table">

                      <table class="editable-table table table-striped" id="editableTable1">

                        <thead>

                          <tr>
                            <th>Field Type<span class="required" style="color: #e3342f;">*</span></th> 
                            
                            <th width="50">Label Name</th>

                            <th>Field Name<span class="required" style="color: #e3342f;">*</span><br/><small>Single word,no spaces.Underscores and dashes allowed</small></th>
                            
                            <th>Is Array</th>
                            
                            <th>Is Unique</th>
                            
                            <th>Min Length</th>

                            <th>Max Length</th>

                            <th>Layout<span class="required" style="color: #e3342f;">*</span></th>

                            <th>ID</th>

                            <th width="80">Class</th>

                            <th width="80">Placeholder Text</th>

                            <th width="110">Field Instructions</th>

                            <th width="120">Is Required</th>

                            <th width="110">Required Message</th>
                            
                            <th width="80">Restrictions</th>

                            <th width="50">Show in List</th>

                            <th width="90">Is Disable</th>

                            <th width="40">&nbsp;</th>

                          </tr>

                        </thead>

                        <tbody id="results">

                        <tr id="data-row-0">
                            
                            <td data-text="Field Type">

							<?php echo field_type_dropdown('row[0][field_type]',0,'');?>

							<textarea name="row[0][field_type_value]" id="field_type_value-0" style="display:none; height:40px;" placeholder="red:Red" class="form-control required"></textarea>
                            <span style="display:none; float:right;" class="add-repeat" id="add-repeat-0" cus="0"><a href="javascript:void(0);">Add Row</a></span>
						  </td>

                          

                          <td data-text="Label Name">

							<input type="text" name="row[0][label_name]" id="label_name" class="form-control" />

                            </td>
                            <td data-text="Field Name">

						  <input type="text" name="row[0][field_name]" id="field_name" class="form-control field_name required" />

                            </td>
                            
                            <td data-text="Is Array">

						  <input type="checkbox" value="1" name="row[0][is_array]" id="is_array-0" />

                            </td>
                            
                            <td data-text="Is Unique">

						  <input type="checkbox" value="1" name="row[0][is_unique]" id="is_unique-0" />

                            </td>
                            
                            <td data-text="Min Length">

						  <input type="text" name="row[0][min_length]" id="min_length-0" class="form-control" />

                            </td>

						  <td data-text="Max Length">

						  <input type="text" name="row[0][character_limit]" id="character_limit-0" class="form-control" />

                            </td>

						  <td data-text="Layout">

						      <select class="form-control required" id="layout-0" name="row[0][layout]">

							<option value="">-- Select --</option>

							@foreach(getLayout() as $key=>$layout_val)

							<option value="{{$key}}">{{$layout_val}}</option>

							@endforeach

							</select>

						  </td>

                          <td data-text="ID">

						  <input type="text" name="row[0][field_id]" id="field_id" class="form-control" />

						  </td>

                          <td data-text="Class">

						  <input type="text" name="row[0][field_class]" id="field_class" class="form-control" />

						  </td>

                          <td data-text="Placeholder Text">

						  <input type="text" name="row[0][placeholder_text]" id="placeholder_text-0" class="form-control" />

						  </td>

						  <td data-text="Field Instructions">

						  <input type="text" name="row[0][field_instructions]" id="field_instructions-0" class="form-control" />

						  </td>

                          <td data-text="Is Required">

						  <input type="checkbox" value="1" name="row[0][is_required]" id="is_required-0" />

						   </td>

                          <td data-text="Required Message">

						  <input type="text" name="row[0][required_message]" id="required_message-0" class="form-control" />

                            </td>
                            
                          <td data-text="Restrictions">

						  <?php echo getRestrictions('row[0][restrictions]',0,'');?>

                          </td>

                          <td data-text="Show in List">

						  <input type="checkbox" value="1" name="row[0][show_list]" id="show_list-0" />

						  </td>

                          <td data-text="Is Disable">

						  <input type="checkbox" value="1" name="row[0][is_disable]" id="is_disable" />

						  </td>

                          

                          <td><a href="javascript:void(0);" onclick="return removeRow(0);" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>

                        </tr>

                          </tbody>

                        

                      </table>

                    </div>

                    <div class="add-row add-new-line-btn">

                      <div class="add-left">

                        <div class="dropdown two-button">

                          <button type="button" class="btn btn-primary add-new-line" cus="1">+ Add a new line</button>

                          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> </button>

                          <div class="dropdown-menu"> <a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> <a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> <a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> </div>

                        </div>

                      </div>

					 

                    </div>

                    <div class="save-row">

                      <div class="save-right float-right">

                        <button type="submit" name="approve" value="approve" class="btn btn-success">Submit</button>

                        <a href="{{ url('admin/create-form') }}"  class="btn btn-secondary">Cancel</a> </div>

                    </div>

                  </div>

                  </div>

                  

                </div>

              </div>

            </form>

 

      </div>

    </div>

  </div>

</div>



<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script>

$(function(){
       $(document).on('click','.add-new-line',function(){
          
	      var count_of_row = $(this).attr('cus');

		  var rowCount = $('#editableTable1 >tbody >tr').length;

		  for(var i = 1; i <= count_of_row; i++) {

	     rowCount++;

	    var html = '<tr id="data-row-'+rowCount+'"> <td data-text="Field Type">{!!field_type_dropdown("row['+rowCount+'][field_type]","'+rowCount+'")!!}<textarea name="row['+rowCount+'][field_type_value]" id="field_type_value-'+rowCount+'" placeholder="red:Red" style="display:none; height:40px;" class="form-control required"></textarea><span style="display:none; float:right;" class="add-repeat" id="add-repeat-'+rowCount+'" cus="'+rowCount+'"><a href="javascript:void(0);">Add Row</a></span></td> <td data-text="Label Name"><input type="text" name="row['+rowCount+'][label_name]" id="label_name" class="form-control" /> </td><td data-text="Field Name"> <input type="text" name="row['+rowCount+'][field_name]" id="field_name" class="form-control field_name required" required /> </td><td data-text="Is Array"> <input type="checkbox" value="1" name="row['+rowCount+'][is_array]" id="is_array-'+rowCount+'" /> </td> <td data-text="Is Unique"> <input type="checkbox" value="1" name="row['+rowCount+'][is_unique]" id="is_unique-'+rowCount+'" /> </td><td data-text="Min Length"> <input type="text" name="row['+rowCount+'][min_length]" id="min_length-'+rowCount+'" class="form-control" /> </td> <td data-text="Max Length"> <input type="text" name="row['+rowCount+'][character_limit]" id="character_limit-'+rowCount+'" class="form-control" /> </td> <td data-text="Layout"> <select class="form-control required" id="layout-'+rowCount+'" name="row['+rowCount+'][layout]"><option value="">-- Select --</option>@foreach(getLayout() as $key=>$layout_val)<option value="{{$key}}">{{$layout_val}}</option>@endforeach</select> </td> <td data-text="ID"> <input type="text" name="row['+rowCount+'][field_id]" id="field_id" class="form-control" /> </td> <td data-text="Class"> <input type="text" name="row['+rowCount+'][field_class]" id="field_class" class="form-control" /> </td> <td data-text="Placeholder Text"> <input type="text" name="row['+rowCount+'][placeholder_text]" id="placeholder_text-'+rowCount+'" class="form-control" /> </td> <td data-text="Field Instructions"> <input type="text" name="row['+rowCount+'][field_instructions]" id="field_instructions-'+rowCount+'" class="form-control" /> </td> <td data-text="Is Required"> <input type="checkbox" value="1" name="row['+rowCount+'][is_required]" id="is_required-'+rowCount+'" /> </td> <td data-text="Required Message"> <input type="text" name="row['+rowCount+'][required_message]" id="required_message-'+rowCount+'" class="form-control"/> </td> <td data-text="Restrictions">{!!getRestrictions("row['+rowCount+'][restrictions]","'+rowCount+'","")!!}</td> <td data-text="Show in List"> <input type="checkbox" value="1" name="row['+rowCount+'][show_list]" id="show_list-'+rowCount+'" /> </td> <td data-text="Is Disable"> <input type="checkbox" value="1" name="row['+rowCount+'][is_disable]" id="is_disable" /> </td> <td><a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';

	     $('#results').append(html);

		 }

	   });

});

</script>
<script>
    $(function(){
        var j=0;
       $(document).on('click','.add-repeat',function(){
           j++;
          var id = $(this).attr('cus');
          var html = '<tr class="row-repeat" id="data-row-repeat'+j+'"> <td data-text="Field Type">{!!field_type_dropdown("row['+id+'][repeat]['+j+'][field_type]","'+j+'")!!}<textarea name="row['+id+'][repeat]['+j+'][field_type_value]" id="field_type_value-'+j+'" placeholder="red:Red" style="display:none; height:40px;" class="form-control required"></textarea></td> <td data-text="Label Name"><input type="text" name="row['+id+'][repeat]['+j+'][label_name]" id="label_name" class="form-control required" required /> </td><td data-text="Field Name"> <input type="text" name="row['+id+'][repeat]['+j+'][field_name]" id="field_name" class="form-control field_name required" required /> </td><td data-text="Is Array"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_array]" id="is_array-'+j+'" /> </td> <td data-text="Is Unique"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_unique]" id="is_unique-'+j+'" /> </td> <td data-text="Min Length"> <input type="text" name="row['+id+'][repeat]['+j+'][min_length]" id="min_length-'+j+'" class="form-control" /> </td><td data-text="Max Length"> <input type="text" name="row['+id+'][repeat]['+j+'][character_limit]" id="character_limit-'+j+'" class="form-control" /> </td> <td data-text="Layout"> <select class="form-control required" id="layout-'+j+'" name="row['+id+'][repeat]['+j+'][layout]"><option value="">-- Select --</option>@foreach(getLayout() as $key=>$layout_val)<option value="{{$key}}">{{$layout_val}}</option>@endforeach</select> </td><td data-text="ID"> <input type="text" name="row['+id+'][repeat]['+j+'][field_id]" id="field_id" class="form-control" /> </td> <td data-text="Class"> <input type="text" name="row['+id+'][repeat]['+j+'][field_class]" id="field_class" class="form-control" /> </td> <td data-text="Placeholder Text"> <input type="text" name="row['+id+'][repeat]['+j+'][placeholder_text]" id="placeholder_text-'+j+'" class="form-control" /> </td> <td data-text="Field Instructions"> <input type="text" name="row['+id+'][repeat]['+j+'][field_instructions]" id="field_instructions-'+j+'" class="form-control" /> </td> <td data-text="Is Required"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_required]" id="is_required-'+j+'" /> </td><td data-text="Required Message"> <input type="text" name="row['+id+'][repeat]['+j+'][required_message]" id="required_message-'+j+'" class="form-control"/> </td> <td data-text="Restrictions">{!!getRestrictions("row['+id+'][repeat]['+j+'][restrictions]","'+j+'","")!!}</td> <td data-text="Show in List"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][show_list]" id="show_list-'+j+'" /> </td> <td data-text="Is Disable"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_disable]" id="is_disable" /> </td> <td><a href="javascript:void(0);" onclick="return removeRowRepeat('+j+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row-repeat waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
          //alert(html);
          $('#data-row-'+id).after(html);
       }); 
    });
</script>

<script>

  function removeRow(row){

    if(row!=0){

    $('#data-row-'+row).remove();	

	}

  }

  </script> 
  <script>

  function removeRowRepeat(row){

    if(row!=''){

    $('#data-row-repeat'+row).remove();	

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

<script>

    $('#module_name,.field_name').keypress(function( e ) {    

    if(!/[0-9a-zA-Z-_]/.test(String.fromCharCode(e.which)))

        return false;

});

</script>

<script>

    $(function(){

        $(document).on('change','.field_type',function(){

           var field_type_val = $(this).val();

           var id = $(this).attr('cus');

           //alert(field_type_val+'-'+id);

           if(field_type_val=='dropdown' || field_type_val=='multiselect' || field_type_val=='checkbox' || field_type_val=='radio')

           {

               $('#field_type_value-'+id).show();
               $('#character_limit-'+id).prop('readonly', false);
               $('#layout-'+id).prop('disabled', false);
               $('#layout-'+id).addClass('required');
               $('#layout-'+id).attr('required');
               $('#placeholder_text-'+id).prop('readonly', false);
               $('#field_instructions-'+id).prop('readonly', false);
               $('#is_required-'+id).prop('disabled', false);
               $('#required_message-'+id).prop('readonly', false);
               $('#show_list-'+id).prop('disabled', false);
               $('#add-repeat-'+id).hide();

           }else if(field_type_val=='function')

           {

               $('#field_type_value-'+id).show();
               $('#field_type_value-'+id).prop('placeholder','Enter custom function');
               $('#character_limit-'+id).prop('readonly', false);
               $('#layout-'+id).prop('disabled', false);
               $('#layout-'+id).addClass('required');
               $('#layout-'+id).attr('required');
               $('#placeholder_text-'+id).prop('readonly', false);
               $('#field_instructions-'+id).prop('readonly', false);
               $('#is_required-'+id).prop('disabled', false);
               $('#required_message-'+id).prop('readonly', false);
               $('#show_list-'+id).prop('disabled', false);
               $('#add-repeat-'+id).hide();

           }else
           
           if(field_type_val=='content')

           {
               //alert(field_type_val+'-'+id);
               $('#character_limit-'+id).prop('readonly', true);
               $('#layout-'+id).prop('disabled', true);
               $('#layout-'+id).removeClass('required');
               $('#layout-'+id).removeAttr('required');
               $('#placeholder_text-'+id).prop('readonly', true);
               $('#field_instructions-'+id).prop('readonly', true);
               $('#is_required-'+id).prop('disabled', true);
               $('#required_message-'+id).prop('readonly', true);
               $('#show_list-'+id).prop('disabled', true);
               $('#field_type_value-'+id).hide();
               $('#add-repeat-'+id).hide();
           }else if(field_type_val=='repeat'){
               $('#add-repeat-'+id).show();
           }else{
               $('#character_limit-'+id).prop('readonly', false);
               $('#layout-'+id).prop('disabled', false);
               $('#layout-'+id).addClass('required');
               $('#layout-'+id).attr('required');
               $('#placeholder_text-'+id).prop('readonly', false);
               $('#field_instructions-'+id).prop('readonly', false);
               $('#is_required-'+id).prop('disabled', false);
               $('#required_message-'+id).prop('readonly', false);
               $('#show_list-'+id).prop('disabled', false);
               $('#field_type_value-'+id).prop('placeholder','red:Red');
               $('#field_type_value-'+id).hide();
               $('#character_limit-'+id).prop('readonly', false);
               $('#add-repeat-'+id).hide();
           }

           

        });

    });

</script>

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>

  jQuery( function() {
    jQuery( "#results" ).sortable({
      revert: true
    });
    
   
  } );
</script>

@endsection