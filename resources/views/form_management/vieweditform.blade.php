@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'From Edit Page')
@section('pagecontent')
<?php $readonlyField = (!empty($tbldataCount))?' readonly=""':'';?>
<div class="page creat-fms-box">
  <div class="page-header">
    <h1 class="page-title">{{ __('Edit Form') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item">{{ __('Edit Form') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400">
        <div class="col-sm-6"> </div>
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
        <form class="" action="{{ url('admin/formupdate_data/'.$forms_data[0]['id']) }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();">
          @csrf
          <div class="card">
            <div class="card-body">
              <div class="row" style="margin:0;">
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="row custom-form-filter">
                  <div class="col">
                      <div class="contact-form bs-example auto-complete-div">
                        <label for="contact">Form Name<span class="required" style="color: #e3342f;">*</span></label>
                        <input type="text" name="form_name" value="{{$forms_data[0]['form_name']}}" id="form_name" class="form-control required" />
                      </div>
                    </div>
                    <div class="col">
                      <div class="contact-form bs-example auto-complete-div">
                        <label for="contact">Module Name<span class="required" style="color: #e3342f;">*</span></label>
                        <input class="form-control required" id="module_name" {{ $readonlyField }} value="{{$forms_data[0]['module_name']}}" name="module_name">
                      </div>
                    </div>
                    <div class="col">
                      <div class="contact-form bs-example auto-complete-div">
                        <label for="contact">Table Name<span class="required" style="color: #e3342f;">*</span></label>
                        <input class="form-control required" id="table_name" {{ $readonlyField }} value="{{$forms_data[0]['table_name']}}" name="table_name">
                      </div>
                    </div>
                    <div class="col checkbox-wrap">
                      <div class="contact-form bs-example auto-complete-div">
                    <label>&nbsp;</label>                        <label>
                          <input type="checkbox" @if($forms_data[0]['import']=='import') checked @endif value="import" name="import"  /> Import </label>
                      </div>
                    </div>
                    <div class="col checkbox-wrap">
                      <div class="contact-form bs-example auto-complete-div">
                    <label>&nbsp;</label>
                        <label>
                          <input type="checkbox" @if($forms_data[0]['export']=='export') checked @endif value="export" name="export_data"  /> Export </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="purchaseorder-box">
                  <hr />
                  <div class="table-wrap big-table">
                    <table class="editable-table table table-striped small-table" id="editableTable1">
                      <thead>
                        <tr>
                          <th>Field Type<span class="required" style="color: #e3342f;">*</span></th>
                          <th width="50">Label Name</th>
                          <th>Field Name<span class="required" style="color: #e3342f;">*</span><br/>
                            <small>Single word,no spaces.Underscores and dashes allowed</small></th>
                            <th>Is Database Field</th>
                            <th>Field Length</th>
                            <th>Is Null</th>
                          <th>Is Array</th>
                          <th>Is Unique</th>
                          <th>Is Readonly</th>
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
                          <th>Conditional</th>
                          <th width="40">&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody id="results">
                      
                      @foreach($forms as $key=>$rows)
                      <tr id="data-row-{{$key}}">
                        <td data-text="Field Type"><?php echo field_type_dropdown('row['.$key.'][field_type]',$key,$rows['field_type']);?>
                          <textarea name="row[{{$key}}][field_type_value]" id="field_type_value-{{$key}}" style="@if($rows['field_type']=='dropdown' || $rows['field_type']=='radio' || $rows['field_type']=='multiselect' || $rows['field_type']=='checkbox' || $rows['field_type']=='function') @else display:none; @endif height:40px;" placeholder="red:Red" class="form-control">{!!$rows['field_type_value']!!}</textarea>
                          <span style=" @if($rows['field_type']!='repeat') display:none; @endif float:right;" class="add-repeat" id="add-repeat-{{$key}}" cus="{{$key}}"><a href="javascript:void(0);">Add Row</a></span></td>
                        <td data-text="Label Name"><input type="text" value="{{$rows['label_name']}}" name="row[{{$key}}][label_name]" id="label_name" class="form-control" /></td>
                        <td data-text="Field Name"><input type="text" {{ $readonlyField }} value="{{$rows['field_name']}}" name="row[{{$key}}][field_name]" id="field_name" class="form-control field_name required" /></td>
                        <td data-text="Is Database Field"><input type="checkbox" value="1" name="row[0][is_database_field]" @if($rows['is_database_field']==1) checked @endif value="1" id="is_database_field-0" /></td>
                        <td data-text="Field Length"><input type="number" min="1" max="255" name="row[0][field_length]" value="{{$rows['field_length']}}" id="field_length" class="form-control field_length" /></td>
                        <td data-text="Is Null"><input type="checkbox" value="1" value="1" @if($rows['is_nullable']==1) checked @endif name="row[0][is_nullable]" id="is_nullable-0" /></td>
                        <td data-text="Is Array"><input type="checkbox" @if($rows['is_array']==1) checked @endif  value="1" name="row[{{$key}}][is_array]" id="is_array-{{$key}}" /></td>
                        <td data-text="Is Unique"><input type="checkbox" @if($rows['is_unique']==1) checked @endif  value="1" name="row[{{$key}}][is_unique]" id="is_unique-{{$key}}" /></td>
                        <td data-text="Is Readonly"><input type="checkbox" @if($rows['is_readonly']==1) checked @endif  value="1" name="row[{{$key}}][is_readonly]" id="is_readonly-{{$key}}" /></td>
                        <td data-text="Min Length"><input type="text" @if($rows['field_type']=='content') readonly @endif value="{{$rows['min_length']}}" name="row[{{$key}}][min_length]" id="min_length-{{$key}}" class="form-control small-text-box" /></td>
                        <td data-text="Max Length"><input type="text" @if($rows['field_type']=='content') readonly @endif value="{{$rows['character_limit']}}" name="row[{{$key}}][character_limit]" id="character_limit-{{$key}}" class="form-control small-text-box" /></td>
                        <td data-text="Layout"><select class="form-control @if($rows['field_type']!='content') required @endif" id="layout-{{$key}}" @if($rows['field_type']=='content') disabled @endif name="row[{{$key}}][layout]">
                            <option value="">-- Select --</option>
                            

							@foreach(getLayout() as $key1=>$layout_val)

							
                            <option @if($key1==$rows['layout']) selected @endif value="{{$key1}}">{{$layout_val}}</option>
                            

							@endforeach

							
                          </select></td>
                        <td data-text="ID"><input type="text" value="{{$rows['field_id']}}" name="row[{{$key}}][field_id]" id="field_id" class="form-control" /></td>
                        <td data-text="Class"><input type="text" value="{{$rows['field_class']}}" name="row[{{$key}}][field_class]" id="field_class" class="form-control" /></td>
                        <td data-text="Placeholder Text"><input type="text" value="{{$rows['placeholder_text']}}" @if($rows['field_type']=='content') readonly @endif name="row[{{$key}}][placeholder_text]" id="placeholder_text-{{$key}}" class="form-control" /></td>
                        <td data-text="Field Instructions"><input type="text" value="{{$rows['field_instructions']}}" @if($rows['field_type']=='content') readonly @endif name="row[{{$key}}][field_instructions]" id="field_instructions-{{$key}}" class="form-control" /></td>
                        <td data-text="Is Required"><input type="checkbox" @if($rows['field_type']=='content') disabled @endif value="1" @if($rows['is_required']==1) checked @endif name="row[{{$key}}][is_required]" id="is_required-{{$key}}" /></td>
                        <td data-text="Required Message"><input type="text" value="{{$rows['required_message']}}" @if($rows['field_type']=='content') readonly @endif name="row[{{$key}}][required_message]" id="required_message-{{$key}}" class="form-control" /></td>
                        <td data-text="Restrictions"><?php echo getRestrictions('row['.$key.'][restrictions]',$key,$rows['restrictions']);?></td>
                        <td data-text="Show in List"><input type="checkbox" @if($rows['field_type']=='content') disabled @endif value="1" @if($rows['show_list']==1) checked @endif name="row[{{$key}}][show_list]" id="show_list-{{$key}}" /></td>
                        <td data-text="Is Disable"><input type="checkbox" @if($rows['is_disable']==1) checked @endif value="1" name="row[{{$key}}][is_disable]" id="is_disable" /></td>
                        <td data-text="Conditional" class="con-{{$key}}"><?php echo getConditional($forms[0]['module_name'],'row['.$key.'][conditional]',$key,$rows['conditional']);?>
                          <?php 
                           if($rows['sub_cond']!=''){
                           echo getSubConditional($forms[0]['module_name'],$rows['conditional'],$key,$rows['sub_cond']);
                           }
                           ?></td>
                        <td><a href="javascript:void(0);" onclick="return removeRow({{$key}});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                      </tr>
                      @if($rows['field_type']=='repeat' && !empty($rows['repeat']))   
                      @foreach($rows['repeat'] as $key1=>$rep)
                      <tr class="row-repeat" id="data-row-repeat<?php echo $key1;?>">
                        <td data-text="Field Type"><?php echo field_type_dropdown('row['.$key.'][repeat]['.$key1.'][field_type]',$key1,$rep["field_type"]);?>
                          <textarea name="row[{{$key}}][repeat][{{$key1}}][field_type_value]" id="field_type_value-{{$key1}}" style="@if($rep['field_type']=='dropdown' || $rep['field_type']=='radio' || $rep['field_type']=='multiselect' || $rep['field_type']=='checkbox' || $rep['field_type']=='function') @else display:none; @endif height:40px;" placeholder="red:Red" class="form-control">{!!$rep['field_type_value']!!}</textarea>
                          <span style=" @if($rep['field_type']!='repeat') display:none; @endif float:right;" class="add-repeat-repeat" id="add-repeat-{{$key1}}" root_cus="{{$key}}" cus="{{$key1}}"><a href="javascript:void(0);">Add Row</a></span></td>
                        <td data-text="Label Name"><input type="text" value="{{$rep['label_name']}}" name="row[{{$key}}][repeat][{{$key1}}][label_name]" id="label_name" class="form-control" /></td>
                        <td data-text="Field Name"><input type="text" value="{{$rep['field_name']}}" readonly name="row[{{$key}}][repeat][{{$key1}}][field_name]" id="field_name" class="form-control field_name required" required /></td>
                        <td data-text="Is Array"><input type="checkbox" @if($rep['is_array']==1) checked @endif value="1" name="row[{{$key}}][repeat][{{$key1}}][is_array]" id="is_array-{{$key1}}" /></td>
                        <td data-text="Is Unique"><input type="checkbox" @if($rep['is_unique']==1) checked @endif  value="1" name="row[{{$key}}][repeat][{{$key1}}][is_unique]" id="is_unique-{{$key}}" /></td>
                        <td data-text="Is Readonly"><input type="checkbox" @if($rep['is_readonly']==1) checked @endif  value="1" name="row[{{$key}}][repeat][{{$key1}}][is_readonly]" id="is_readonly-{{$key}}" /></td>
                        <td data-text="Min Length"><input type="text" @if($rep['field_type']=='content') readonly @endif value="{{$rep['min_length']}}" name="row[{{$key}}][repeat][{{$key1}}][min_length]" id="min_length-{{$key}}" class="form-control small-text-box" /></td>
                        <td data-text="Max Length"><input type="text" value="{{$rep['character_limit']}}" name="row[{{$key}}][repeat][{{$key1}}][character_limit]" id="character_limit-{{$key1}}" class="form-control small-text-box" /></td>
                        <td data-text="Layout"><select class="form-control required" id="layout-{{$key1}}" name="row[{{$key}}][repeat][{{$key1}}][layout]">
                            <option value="">-- Select --</option>
                            
                                @foreach(getLayout() as $key2=>$layout_val)
                                
                            <option @if($key2==$rep['layout']) selected @endif value="{{$key2}}">{{$layout_val}}</option>
                            
                                @endforeach
                                
                          </select></td>
                        <td data-text="ID"><input type="text" value="{{$rep['field_id']}}" name="row[{{$key}}][repeat][{{$key1}}][field_id]" id="field_id" class="form-control" /></td>
                        <td data-text="Class"><input type="text" value="{{$rep['field_class']}}" name="row[{{$key}}][repeat][{{$key1}}][field_class]" id="field_class" class="form-control" /></td>
                        <td data-text="Placeholder Text"><input type="text" value="{{$rep['placeholder_text']}}" name="row[{{$key}}][repeat][{{$key1}}][placeholder_text]" id="placeholder_text-{{$key1}}" class="form-control" /></td>
                        <td data-text="Field Instructions"><input type="text" value="{{$rep['field_instructions']}}" name="row[{{$key}}][repeat][{{$key1}}][field_instructions]" id="field_instructions-{{$key1}}" class="form-control" /></td>
                        <td data-text="Is Required"><input type="checkbox" value="1" @if($rep['is_required']==1) checked @endif name="row[{{$key}}][repeat][{{$key1}}][is_required]" id="is_required-{{$key1}}" /></td>
                        <td data-text="Required Message"><input value="{{$rep['required_message']}}" type="text" name="row[{{$key}}][repeat][{{$key1}}][required_message]" id="required_message-{{$key1}}" class="form-control"/></td>
                        <td data-text="Restrictions"><?php echo getRestrictions('row['.$key.'][repeat]['.$key1.'][restrictions]',$key1,$rep['restrictions']);?></td>
                        <td data-text="Show in List"><input @if($rep['show_list']==1) checked @endif type="checkbox" value="1" name="row[{{$key}}][repeat][{{$key1}}][show_list]" id="show_list-{{$key1}}" /></td>
                        <td data-text="Is Disable"><input @if($rep['is_disable']==1) checked @endif type="checkbox" value="1" name="row[{{$key}}][repeat][{{$key1}}][is_disable]" id="is_disable" /></td>
                        <td data-text="Conditional" class="con-{{$key}}"><?php echo getConditional($forms[$key]['repeat'][$key1]['module_name'],'row['.$key.'][conditional]',$key1,$erp['conditional']);?>
                          <?php 
                            if($rep['sub_cond']!=''){
                            echo getSubConditional($forms[$key]['repeat'][$key1]['module_name'],$rows['conditional'],$key1,$rep['sub_cond']);
                            }
                            ?></td>
                        <td><a href="javascript:void(0);" onclick="return removeRowRepeat({{$key1}});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row-repeat waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                      </tr>
                      @if(!empty($rep['repeat_new'])) 
                      @foreach($rep['repeat_new'] as $key_rep=>$rep_data)
                      <tr class="row-repeat" id="data-row-repeat-new<?php echo $key_rep;?>">
                        <td data-text="Field Type"><?php echo field_type_dropdown('row['.$key.'][repeat]['.$key1.'][repeat_new]['.$key_rep.'][field_type]',$key_rep,$rep_data["field_type"]);?>
                          <textarea name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][field_type_value]" id="field_type_value-{{$key_rep}}" style="@if($rep_data['field_type']=='dropdown' || $rep_data['field_type']=='radio' || $rep_data['field_type']=='multiselect' || $rep_data['field_type']=='checkbox' || $rep_data['field_type']=='function') @else display:none; @endif height:40px;" placeholder="red:Red" class="form-control">{!!$rep_data['field_type_value']!!}</textarea></td>
                        <td data-text="Label Name"><input type="text" value="{{$rep_data['label_name']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][label_name]" id="label_name" class="form-control" /></td>
                        <td data-text="Field Name"><input type="text" value="{{$rep_data['field_name']}}" readonly name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][field_name]" id="field_name" class="form-control field_name required" required /></td>
                        <td data-text="Is Array"><input type="checkbox" @if($rep_data['is_array']==1) checked @endif value="1" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][is_array]" id="is_array-{{$key_rep}}" /></td>
                        <td data-text="Is Unique"><input type="checkbox" @if($rep_data['is_unique']==1) checked @endif  value="1" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][is_unique]" id="is_unique-{{$key_rep}}" /></td>
                        <td data-text="Is Readonly"><input type="checkbox" @if($rep_data['is_readonly']==1) checked @endif  value="1" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][is_readonly]" id="is_readonly-{{$key_rep}}" /></td>
                        <td data-text="Min Length"><input type="text" @if($rep['field_type']=='content') readonly @endif value="{{$rep_data['min_length']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][min_length]" id="min_length-{{$key_rep}}" class="form-control  small-text-box" /></td>
                        <td data-text="Max Length"><input type="text" value="{{$rep_data['character_limit']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][character_limit]" id="character_limit-{{$key_rep}}" class="form-control  small-text-box" /></td>
                        <td data-text="Layout"><select class="form-control required" id="layout-{{$key_rep}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][layout]">
                            <option value="">-- Select --</option>
                            
                                @foreach(getLayout() as $key2=>$layout_val)
                                
                            <option @if($key2==$rep_data['layout']) selected @endif value="{{$key2}}">{{$layout_val}}</option>
                            
                                @endforeach
                                
                          </select></td>
                        <td data-text="ID"><input type="text" value="{{$rep_data['field_id']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][field_id]" id="field_id" class="form-control" /></td>
                        <td data-text="Class"><input type="text" value="{{$rep_data['field_class']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][field_class]" id="field_class" class="form-control" /></td>
                        <td data-text="Placeholder Text"><input type="text" value="{{$rep_data['placeholder_text']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][placeholder_text]" id="placeholder_text-{{$key_rep}}" class="form-control" /></td>
                        <td data-text="Field Instructions"><input type="text" value="{{$rep_data['field_instructions']}}" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][field_instructions]" id="field_instructions-{{$key_rep}}" class="form-control" /></td>
                        <td data-text="Is Required"><input type="checkbox" value="1" @if($rep_data['is_required']==1) checked @endif name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][is_required]" id="is_required-{{$key_rep}}" /></td>
                        <td data-text="Required Message"><input value="{{$rep_data['required_message']}}" type="text" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][required_message]" id="required_message-{{$key_rep}}" class="form-control"/></td>
                        <td data-text="Restrictions"><?php echo getRestrictions('row['.$key.'][repeat]['.$key1.'][repeat_new]['.$key_rep.'][restrictions]',$key1,$rep_data['restrictions']);?></td>
                        <td data-text="Show in List"><input @if($rep_data['show_list']==1) checked @endif type="checkbox" value="1" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][show_list]" id="show_list-{{$key_rep}}" /></td>
                        <td data-text="Is Disable"><input @if($rep_data['is_disable']==1) checked @endif type="checkbox" value="1" name="row[{{$key}}][repeat][{{$key1}}][repeat_new][{{$key_rep}}][is_disable]" id="is_disable" /></td>
                        <td data-text="Conditional" class="con-{{$key}}"><?php echo getConditional($forms[$key]['repeat'][$key1]['repeat_new'][$key_rep]['module_name'],'row['.$key.'][repeat]['.$key1.'][repeat_new]['.$key_rep.'][conditional]',$key1,$rep_data['conditional']);?>
                          <?php 
                            if($rep_data['sub_cond']!=''){
                            echo getSubConditional($forms[$key]['repeat'][$key1]['repeat_new'][$key_rep]['module_name'],$rows['conditional'],$key1,$rep_data['sub_cond']);
                            }
                            ?></td>
                        <td><a href="javascript:void(0);" onclick="return removeRowRepeatNew({{$key_rep}});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row-repeat waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                      </tr>
                      @endforeach
                      @endif
                      @endforeach
                      @endif
                      @endforeach
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
                      <a href="{{ url('admin/formlist') }}"  class="btn btn-secondary">Cancel</a> </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.js"></script> 
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script> 
<script>

$(function(){

       $(document).on('click','.add-new-line',function(){

	      var count_of_row = $(this).attr('cus');

		  var rowCount = $('#editableTable1 >tbody >tr').length;

		  for(var i = 1; i <= count_of_row; i++) {

	 rowCount++;

	    var html = '<tr id="data-row-'+rowCount+'"> <td data-text="Field Type">{!!field_type_dropdown("row['+rowCount+'][field_type]","'+rowCount+'")!!}<textarea name="row['+rowCount+'][field_type_value]" id="field_type_value-'+rowCount+'" placeholder="red:Red" style="display:none; height:40px;" class="form-control"></textarea><span style="display:none; float:right;" class="add-repeat" id="add-repeat-'+rowCount+'" cus="'+rowCount+'"><a href="javascript:void(0);">Add Row</a></span></td> <td data-text="Label Name"><input type="text" name="row['+rowCount+'][label_name]" id="label_name" class="form-control" /> </td><td data-text="Field Name"> <input type="text" name="row['+rowCount+'][field_name]" id="field_name" class="form-control field_name required" required /> </td> <td data-text="Is Array"> <input type="checkbox" value="1" name="row['+rowCount+'][is_array]" id="is_array-'+rowCount+'" /> </td><td data-text="Is Unique"> <input type="checkbox" value="1" name="row['+rowCount+'][is_unique]" id="is_unique-'+rowCount+'" /><td data-text="Is Readonly"> <input type="checkbox" value="1" name="row['+rowCount+'][is_readonly]" id="is_readonly-'+rowCount+'" /> </td> <td data-text="Min Length"> <input type="text" name="row['+rowCount+'][min_length]" id="min_length-'+rowCount+'" class="form-control" /> </td> <td data-text="Max Length"> <input type="text" name="row['+rowCount+'][character_limit]" id="character_limit-'+rowCount+'" class="form-control" /> </td> <td data-text="Layout"> <select class="form-control required" id="layout-'+rowCount+'" name="row['+rowCount+'][layout]"><option value="">-- Select --</option>@foreach(getLayout() as $key=>$layout_val)<option value="{{$key}}">{{$layout_val}}</option>@endforeach</select> </td> <td data-text="ID"> <input type="text" name="row['+rowCount+'][field_id]" id="field_id" class="form-control" /> </td> <td data-text="Class"> <input type="text" name="row['+rowCount+'][field_class]" id="field_class" class="form-control" /> </td> <td data-text="Placeholder Text"> <input type="text" name="row['+rowCount+'][placeholder_text]" id="placeholder_text-'+rowCount+'" class="form-control" /> </td> <td data-text="Field Instructions"> <input type="text" name="row['+rowCount+'][field_instructions]" id="field_instructions-'+rowCount+'" class="form-control" /> </td> <td data-text="Is Required"> <input type="checkbox" value="1" name="row['+rowCount+'][is_required]" id="is_required-'+rowCount+'" /> </td> <td data-text="Required Message"> <input type="text" name="row['+rowCount+'][required_message]" id="required_message-'+rowCount+'" class="form-control"/> </td> <td data-text="Restrictions">{!!getRestrictions("row['+rowCount+'][restrictions]","'+rowCount+'","")!!}</td> <td data-text="Show in List"> <input type="checkbox" value="1" name="row['+rowCount+'][show_list]" id="show_list-'+rowCount+'" /> </td> <td data-text="Is Disable"> <input type="checkbox" value="1" name="row['+rowCount+'][is_disable]" id="is_disable" /> </td> <td data-text="Conditional" class="con-'+rowCount+'"></td><td><a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';

	     $('#results').append(html);

		 }

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
    $(function(){
        var j= parseInt(100)+$('#editableTable1 >tbody >tr').length;
       $(document).on('click','.add-repeat',function(){
           //alert();
           j++;
          var id = $(this).attr('cus');
          var html = '<tr class="row-repeat" id="data-row-repeat'+j+'"> <td data-text="Field Type">{!!field_type_dropdown("row['+id+'][repeat]['+j+'][field_type]","'+j+'")!!}<textarea name="row['+id+'][repeat]['+j+'][field_type_value]" id="field_type_value-'+j+'" placeholder="red:Red" style="display:none; height:40px;" class="form-control"></textarea><span style="display:none; float:right;" class="add-repeat-repeat" id="add-repeat-'+j+'" root_cus="'+id+'" cus="'+j+'"><a href="javascript:void(0);">Add Row</a></span></td> <td data-text="Label Name"><input type="text" name="row['+id+'][repeat]['+j+'][label_name]" id="label_name" class="form-control" /> </td><td data-text="Field Name"> <input type="text" name="row['+id+'][repeat]['+j+'][field_name]" id="field_name" class="form-control field_name required" required /> </td><td data-text="Is Array"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_array]" id="is_array-'+j+'" /> </td> <td data-text="Is Unique"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_unique]" id="is_unique-'+j+'" /> </td><td data-text="Min Length"> <input type="text" name="row['+id+'][repeat]['+j+'][min_length]" id="min_length-'+j+'" class="form-control" /> </td><td data-text="Max Length"> <input type="text" name="row['+id+'][repeat]['+j+'][character_limit]" id="character_limit-'+j+'" class="form-control" /> </td> <td data-text="Layout"> <select class="form-control required" id="layout-'+j+'" name="row['+id+'][repeat]['+j+'][layout]"><option value="">-- Select --</option>@foreach(getLayout() as $key=>$layout_val)<option value="{{$key}}">{{$layout_val}}</option>@endforeach</select> </td><td data-text="ID"> <input type="text" name="row['+id+'][repeat]['+j+'][field_id]" id="field_id" class="form-control" /> </td> <td data-text="Class"> <input type="text" name="row['+id+'][repeat]['+j+'][field_class]" id="field_class" class="form-control" /> </td> <td data-text="Placeholder Text"> <input type="text" name="row['+id+'][repeat]['+j+'][placeholder_text]" id="placeholder_text-'+j+'" class="form-control" /> </td> <td data-text="Field Instructions"> <input type="text" name="row['+id+'][repeat]['+j+'][field_instructions]" id="field_instructions-'+j+'" class="form-control" /> </td> <td data-text="Is Required"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_required]" id="is_required-'+j+'" /> </td><td data-text="Required Message"> <input type="text" name="row['+id+'][repeat]['+j+'][required_message]" id="required_message-'+j+'" class="form-control"/> </td> <td data-text="Restrictions">{!!getRestrictions("row['+id+'][repeat]['+j+'][restrictions]","'+j+'","")!!}</td><td data-text="Show in List"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][show_list]" id="show_list-'+j+'" /> </td> <td data-text="Is Disable"> <input type="checkbox" value="1" name="row['+id+'][repeat]['+j+'][is_disable]" id="is_disable" /> </td><td data-text="Conditional" class="con-'+j+'"><td><a href="javascript:void(0);" onclick="return removeRowRepeat('+j+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row-repeat waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
          //alert(html);
          $('#data-row-'+id).after(html);
       }); 
    });
</script> 
<script>

  function removeRowRepeat(row){

    if(row!=''){

    $('#data-row-repeat'+row).remove();	

	}

  }

  </script> 
<script>

  function removeRowRepeatNew(row){

    if(row!=''){

    $('#data-row-repeat-new'+row).remove();	

	}

  }

  </script> 
<script>
    $(function(){
        var j=parseInt(1000)+$('#editableTable1 >tbody >tr').length;
       $(document).on('click','.add-repeat-repeat',function(){
           j++;
          var root_id = $(this).attr('root_cus');
          //alert(root_id);
          var id = $(this).attr('cus');
          var html = '<tr class="row-repeat" id="data-row-repeat-new'+j+'"> <td data-text="Field Type">{!!field_type_dropdown("row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][field_type]","'+j+'")!!}<textarea name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][field_type_value]" id="field_type_value-'+j+'" placeholder="red:Red" style="display:none; height:40px;" class="form-control"></textarea></td> <td data-text="Label Name"><input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][label_name]" id="label_name" class="form-control" /> </td><td data-text="Field Name"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][field_name]" id="field_name" class="form-control field_name required" required /> </td><td data-text="Is Array"> <input type="checkbox" value="1" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][is_array]" id="is_array-'+j+'" /> </td> <td data-text="Is Unique"> <input type="checkbox" value="1" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][is_unique]" id="is_unique-'+j+'" /> </td> <td data-text="Min Length"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][min_length]" id="min_length-'+j+'" class="form-control" /> </td><td data-text="Max Length"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][character_limit]" id="character_limit-'+j+'" class="form-control" /> </td> <td data-text="Layout"> <select class="form-control required" id="layout-'+j+'" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][layout]"><option value="">-- Select --</option>@foreach(getLayout() as $key=>$layout_val)<option value="{{$key}}">{{$layout_val}}</option>@endforeach</select> </td><td data-text="ID"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][field_id]" id="field_id" class="form-control" /> </td> <td data-text="Class"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][field_class]" id="field_class" class="form-control" /> </td> <td data-text="Placeholder Text"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][placeholder_text]" id="placeholder_text-'+j+'" class="form-control" /> </td> <td data-text="Field Instructions"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][field_instructions]" id="field_instructions-'+j+'" class="form-control" /> </td> <td data-text="Is Required"> <input type="checkbox" value="1" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][is_required]" id="is_required-'+j+'" /> </td><td data-text="Required Message"> <input type="text" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][required_message]" id="required_message-'+j+'" class="form-control"/> </td> <td data-text="Restrictions">{!!getRestrictions("row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][restrictions]","'+j+'","")!!}</td> <td data-text="Show in List"> <input type="checkbox" value="1" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][show_list]" id="show_list-'+j+'" /> </td> <td data-text="Is Disable"> <input type="checkbox" value="1" name="row['+root_id+'][repeat]['+id+'][repeat_new]['+j+'][is_disable]" id="is_disable" /> </td> <td data-text="Conditional" class="con-'+j+'"></td><td><a href="javascript:void(0);" onclick="return removeRowRepeatNew('+j+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row-repeat waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td></tr>';
          //alert(html);
          $('#data-row-repeat'+id).after(html);
       }); 
    });
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

           }
           else if(field_type_val=='function')

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
<script>
    $(function(){
       $(document).on('change','.con',function(){
           var con_val = $(this).val();
           var id = $(this).attr('cus');
           var module_name = "{{$forms[0]['module_name']}}";
           if(con_val!='' && id!='')
           {
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
                url: "{{ url('admin/conditional') }}" ,
                type: "POST",
                data: {con_val:con_val,id:id,module_name:module_name},
                success: function( response ) {
                //alert(response);
                if(response!='')
                {
                    //alert(response);
                $('.sub_cond-'+id).remove();
                $('.con-'+id).append(response);
                }
                }
                });
           }else{
               $('.sub_cond-'+id).remove();
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