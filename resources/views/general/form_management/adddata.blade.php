@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add '.$adddata['form_name'])
@section('pagecontent')
@php
$arr = array();
foreach($adddata['row'] as $da){
if($da['repeat']!=''){
array_push($arr,$da);
}
}

//pr($arr[1]['repeat']);
foreach($arr[0]['repeat'] as $new_data){
if(!empty($new_data['repeat_new']))
{
//pr($arr[1]['repeat']);
}
}
//echo $arr[1]['field_name'];
//die;
//pr($arr[0]['repeat'][1]['is_unique']); die;
//pr(getValidationList($adddata['module_name'])); die;
@endphp
@if($adddata['_id']=='617f8983d0490476da34a012')
<style>
    .add-more{display:none;}
</style>
@endif
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
	<div class="page-header">
        <h1 class="page-title">Add {{$adddata['form_name']}}</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('listdata',$adddata['_id']) }}">{{$adddata['form_name']}} : List</a></li>
			<li class="breadcrumb-item">Add {{$adddata['form_name']}}</li>
        </ol>
	</div>
	
	<div class="page-content container-fluid">
		<div class="row justify-content-center">
			@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div><br />
			@endif
			<div class="col-md-12">
				<div class="card">					
					<div class="card-body_rename card-body">					
						<?php //pr($adddata);
						echo GetFormByModule($adddata['module_name'],$adddata['module_name'],$adddata['table_name'],'POST','formupdate/'.$adddata['id'],'','');
						?>
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="myModal-search" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Search Items</h4>
        </div>
        <div class="modal-body search-data-items">
          <form method="POST" id="reloadform" action="" class="custom-inline-form item-form-apply">
            @csrf
            <div class="new-form-box">
            <div class="form-wrap small-box"> 
            <label>Vendor SKU</label>
            <input class="form-control" type="text" id="vendor_sku" name="vendor_sku" value="{{@$vendor_sku}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap"> 
            <label>Name</label>
            <input class="form-control" type="text" id="name" name="name" value="{{@$name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Synonyms</label>
            <input class="form-control" type="text" id="synonyms" name="synonyms" value="{{@$synonyms}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Grade</label>
            <input class="form-control" type="text" id="grade" name="grade" value="{{@$grade}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Brand</label>
            <input class="form-control" type="text" id="brand" name="brand" value="{{@$brand}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box"> 
            <label>Packing</label>
            <input class="form-control" type="text" id="packing_name" name="packing_name" value="{{@$packing_name}}" autocomplete="off" placeholder=""></div>
            <div class="form-wrap small-box"> 
            <label>HSN Code</label>
            <input class="form-control" type="text" id="hsn_code" name="hsn_code" value="{{@$hsn_code}}" autocomplete="off" placeholder=""></div>
            </div>
            <div class="new-form-box new-form-box-radio-box">
            <div class="new-form-radio-box">
            <input type="radio" id="html" name="is_verified" @if($is_verified=='TRUE') checked @endif value="TRUE"><label for="html">Verified</label>
            </div>
            <div class="new-form-radio-box">
            <input type="radio" id="css" @if($is_verified=='FALSE') checked @endif name="is_verified" value="FALSE"><label for="css">Unverified</label>
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic order_search" type="button" name="order_search">Search</button>
            </div>
            
            <div class="form-wrap form-wrap-submit"> <a id="reload" href="javascript:void(0);" class="btn btn-primary reload waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
            
            </div>
            
          </form>
          <div id="data-table-result">
          <table class="table table-bordered t1">
              <thead>
                <th class="bg-info">Apply</th>
                <th class="bg-info">Vendor SKU</th>
                <th class="bg-info">Group Name</th>
                <th class="bg-info">HSN Code</th>
                <th class="bg-info">Grade</th>
                <th class="bg-info">Brand</th>
                <th class="bg-info">Packing Name</th>
                <th class="bg-info">List Price</th>
                <th class="bg-info">MRP</th>
                <!--<th class="bg-info">Net Rate</th>-->
                <th class="bg-info">Stock</th>
                  </thead>
              <tbody id="results">              
              @if(!empty($datalist))
              @foreach($datalist as $user)
              <tr>
                <td style="text-align:center;" class="white-space-normal"><input id="{{$user[_id]}}" value="{{$user['vendor_sku']}}-{{$user['name']}}-{{$user['hsn_code']}}-{{$user['grade']}}-{{$user['brand']}}-{{$user['packing_name']}}-{{$user['list_price']}}-{{$user['mrp']}}-{{$user['net_rate']}}-{{$user['stock']}}" name="apply-radio" type="radio"></td>
                <td style="text-align:center;" class="white-space-normal">{{$user['vendor_sku']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['name']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['hsn_code']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['grade']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['brand']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['packing_name']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['list_price']}}</td>
                <td style="text-align:center;" class="white-space-normal">{{$user['mrp']}}</td>
                <!--<td style="text-align:center;" class="white-space-normal">{{$user['net_rate']}}</td>-->
                <td style="text-align:center;" class="white-space-normal">{{$user['stock']}}</td>
               </tr>
             
              @endforeach
              @else
              <tr>
                <td colspan="8">No record found.</td>
              </tr>
              @endif
                </tbody>
            </table>
        </div>
        <div class="container" style="margin-top:10px;">
	
	<div class="box">
	    <ul id="example-2" class="pagination"></ul>
	    <div class="show"></div>
	</div>
	
</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-apply" data-dismiss="modal">Apply</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('custom_validation_script')

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>


<script>

$(document).ready(function() {
    
    var value = $("#password").val();

$.validator.addMethod("checklower", function(value) {
  return /[a-z]/.test(value);
});
$.validator.addMethod("checkupper", function(value) {
  return /[A-Z]/.test(value);
});
$.validator.addMethod("checkdigit", function(value) {
  return /[0-9]/.test(value);
});
$.validator.addMethod("pwcheck", function(value) {
  return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
});

$.validator.addMethod("checkExists", function(value)
{
    return true;
});

$.validator.addMethod("lettersonly", function(value, element) 
{
return this.optional(element) || /^[a-z. ]+$/i.test(value);
}, "Letters and spaces only please");


    $("#{{$adddata['module_name']}}").validate({
        
        rules: {
                @foreach(getValidationList($adddata['module_name']) as $vali)
                @if($vali['field_type']!='file')
                @if($vali['field_type']=='multiselect' || $vali['field_type']=='checkbox') '{{$vali['field_name']}}[]' @else {{$vali['field_name']}} @endif: {
                required: true,
                @if($vali['field_type']!='radio' && $vali['field_type']!='multiselect' && $vali['field_type']!='checkbox' && $vali['field_type']!='function' && $vali['field_type']!='dropdown')
                @if($vali['min_length']=='')
                minlength: 1,
                @else
                minlength: {{$vali['min_length']}},
                @endif
                @endif
                @if($vali['restrictions']=='lettersonly')
                lettersonly: true,
                @endif
                @if($vali['character_limit']!='')
                @if($vali['field_type']=='password')
                minlength: 6,
                checklower: true,
                checkupper: true,
                checkdigit: true,
                @elseif($vali['field_type']=='email')
                checkExists: true,
                email: true,
                remote: {
                url: "{{url('admin/emailchecker')}}",
                type: "post",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
		        email: function() {
                return $( "#email" ).val();
                },
                table_name: function() {
                return $( "input[name=table_name]" ).val();
                }
                }
                },
                @elseif($vali['field_type']=='repeat' && $arr[0]['repeat'][1]['is_unique']==1)
                remote:{
                    
                },
                @elseif($vali['field_type']=='radio')
                
                @else
                
                @endif
                maxlength: {{$vali['character_limit']}},
                @endif
                },
                @endif
                @endforeach
    },
    messages: {
        @foreach(getValidationList($adddata['module_name']) as $vali_msg)
        @if($vali_msg['field_type']!='file')
        @if($vali_msg['field_type']=='multiselect' || $vali_msg['field_type']=='checkbox') '{{$vali_msg['field_name']}}[]' @else {{$vali_msg['field_name']}} @endif: {
            @if($vali_msg['required_message']!='')
            required: "{{$vali_msg['required_message']}}",
            @endif
            @if($vali_msg['field_type']=='password')
            pwcheck: "Password is not strong enough.",
            checklower: "Need atleast 1 lowercase alphabet.",
            checkupper: "Need atleast 1 uppercase alphabet.",
            checkdigit: "Need atleast 1 digit.",
            @endif
            @if($vali_msg['field_type']=='email')
                remote:"This email address is already registered.",
            @endif
            @if($vali_msg['character_limit']!='')
            maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
            minlength: jQuery.validator.format("Please enter at least {0} characters."),
            @endif
            @if($vali_msg['restrictions']!='')
            lettersonly:jQuery.validator.format("Please enter only alphabets and space."),
            @endif
        },
        @endif
        @endforeach
    }
    });

});

</script>



    <script>
    $(function(){
    @foreach(getConditionalList($adddata['module_name']) as $vali)
    @if(!empty($vali['conditional']) && !empty($vali['sub_cond']))   
    $(window).on('load',function(){
        //alert($("input[name='{{$vali['conditional']}}']").is(':checked'));
    if ($("input[name='{{$vali['conditional']}}']").is(':checked')) {
    var check_val = $("input[name='{{$vali['conditional']}}']:checked").val();
    if(check_val=='{{$vali["sub_cond"]}}'){
    $('.{{$vali["field_name"]}}').show();
    }else{
    $('.{{$vali["field_name"]}}').hide();
    }
    }else{
        //alert('hii');
    $('.{{$vali["field_name"]}}').hide();
    }
    });
    @endif
    @endforeach
    });
    </script>
    <script>
    $(function(){
    @foreach(getConditionalList($adddata['module_name']) as $vali)
    @if(!empty($vali['conditional']) && !empty($vali['sub_cond']))   
    $('input[name="{{$vali['conditional']}}"]').click(function(){
    if ($("input[name='{{$vali['conditional']}}']").is(':checked')) {
    var check_val = $(this).val();
    if(check_val=='{{$vali["sub_cond"]}}'){
    $('.{{$vali["field_name"]}}').show();
    }else{
    //alert(check_val);
    $('.{{$vali["field_name"]}}').hide();
    }
    }else{
    $('.{{$vali["field_name"]}}').hide();
    }
    });
    @endif
    @endforeach
    });
    </script>
    

<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{ url('theme/require/jquery-ui-timepicker-addon.css')}}" />
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 
<script>
		$('.date_with_time').datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
            changeYear: true,
		});
		
	</script>
@if($adddata['_id']=='619c7679d0490455f258a8f2')
<script>
    $(function(){
        $(document).on('change',"select[name='pack_size']",function(){
           var pack_size_val = $("select[name='pack_size']").val();
           var list_price =$("input[name='list_price']").val();
           if(pack_size_val!='' && list_price!='')
           {
           var unit_price = eval(list_price)/eval(pack_size_val);
           $('.unit_price').val(unit_price.toFixed(2));
           }
        });
    });
    $(function(){
        $(document).on('keyup change',"input[name='list_price']",function(){
           var pack_size_val = $("select[name='pack_size']").val();
           var list_price =$("input[name='list_price']").val();
           if(pack_size_val!='' && list_price!='')
           {
           var unit_price = eval(list_price)/eval(pack_size_val);
           $('.unit_price').val(unit_price.toFixed(2));
           }
        });
    });
</script>


<script>
	$(function () { 
		var getItemArr = '<?php print_r(getItemGroup()); ?>';
		getItemArr = JSON.parse(getItemArr);
		//console.log(buyerArr);
		var getItemData = $.map(getItemArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var getItemHtml='<option value="">--Select--</option>';
		for(var i=0; i<getItemData.length; i++){
			getItemHtml+='<option value="'+getItemData[i].data+'">'+getItemData[i].value+'</option>';
		}
		$('.group_name').html(getItemHtml);
		//$('#buyer_name').html(buyerHtml);
		//console.log(buyerHtml);
	});
</script>


@endif
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
    });
    </script>

<script>
    $(function(){
       $(document).on('click','.delete-row',function(){
          var id = $(this).attr('cus');
           $('#rep-'+id).remove();
       });
    });
</script>
<script>
    $(function(){
       $(document).on('click','.delete-row-repeat',function(){
          var id = $(this).attr('cus');
           $('#rep-repeat'+id).remove();
       });
    });
</script>
<script>
    $(function(){
       $(document).on('change','.brand_item',function(){
          var brand_item = $(this).val();
          var cus_item = $(this).attr('cus');
          //alert(brand_item);
          var result1 = cus_item.split('[');
              var data = result1[1].split(']');
          if(brand_item!=''){
             if(brand_item=='brand')
             {
                 $('#rep-'+data[0]+'> div.per_discount').show();
                 $('#rep-'+data[0]+'> div.discounted_price').hide();
             }
             else if(brand_item=='item')
             {
                 $('#rep-'+data[0]+'> div.per_discount').hide();
                 $('#rep-'+data[0]+'> div.discounted_price').show();
             }
          }else{
                 $('#rep-'+data[0]+'> div.per_discount').show();
                 $('#rep-'+data[0]+'> div.discounted_price').show();
             }
       });
    });
</script>
@if($adddata['_id']=='617f8983d0490476da34a012')
<script>
    $(document).on('blur','.pack_size',function(){
        var pack_size_name = $(this).attr('name');
        var data = pack_size_name.match(/\d+/);
        
        var pack_size_val = $('input[name="repeat['+data+'][pack_size]"]').val();
        var unit_val = $('select[name="repeat['+data+'][unit]"]').val();
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
        if(pack_size_val!='' && unit_val!=''){
          $('#loader').show();
            $.ajax({
            url: "{{ url('admin/uniqueValueCheckPackSize') }}" ,
            type: "POST",
            data: {pack_size_val:pack_size_val,unit_val:unit_val},
            success: function( response ) {
            //alert(response); return false;
            if(response=='1')
            {
            alert('Please enter unique value.');
            //$('.submit').prop('disabled',true);
            $('input[name="repeat['+data+'][pack_size]"]').val('');
            $('select[name="repeat['+data+'][unit]"]').val('');
            $('#loader').hide();
            return false;
            }else{
            $('#loader').hide();
            //$('.submit').prop('disabled',false);    
            }
            }
            });
        }
        
    });
</script>
@endif

@if($adddata['_id']=='61e4eb47d049042b09371132')
<script>
    $(document).on('blur','.dept',function(){
        var task_category_name = $(this).attr('name');
        var data = task_category_name.match(/\d+/);
        //alert(data); return false;
        var task_category_val = $('input[name="repeat['+data+'][task_category]"]').val();
        var  dept_val= $('select[name="repeat['+data+'][department]"]').val();
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        //alert(dept_val+'-'+task_category_val); return false;
        if(dept_val!='' && task_category_val!=''){
          $('#loader').show();
            $.ajax({
            url: "{{ url('admin/uniqueValueCheckTaskCategory') }}" ,
            type: "POST",
            data: {dept_val:dept_val,task_category_val:task_category_val},
            success: function( response ) {
            //alert(response); return false;
            if(response=='1')
            {
            alert('Please enter unique value.');
            //$('.submit').prop('disabled',true);
            $('input[name="repeat['+data+'][task_category]"]').val('');
            $('select[name="repeat['+data+'][department]"]').val('');
            $('#loader').hide();
            return false;
            }else{
            $('#loader').hide();
            //$('.submit').prop('disabled',false);    
            }
            }
            });
        }
        
    });
</script>
@endif
<script>
    function uniqueValueCheck(val)
    {
        var input_val = val.value;
        var table_name = "{{$adddata['table_name']}}";
        var field_name = $(val).attr('cus');
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        if(input_val!='' && table_name!='' && field_name!='')
        {
        //alert(field_name); return false;
        //$('.submit').prop('disabled',true);
        $('#loader').show();
        $.ajax({
        url: "{{ url('admin/uniqueValueCheck') }}" ,
        type: "POST",
        data: {input_val:input_val,table_name:table_name,field_name:field_name},
        success: function( response ) {
          //alert(response); return false;
		  if(response=='1')
		  {
		  alert('Please enter unique value.');
		  //$('.submit').prop('disabled',true);
		  $(val).val('');
		  $('#loader').hide();
		  return false;
		  }else{
		      $('#loader').hide();
		  //$('.submit').prop('disabled',false);    
		  }
        }
      });
        }
    }
</script>
<style>
    .ui-tooltip.ui-widget { display:none; }
</style>
<script>
    $(function(){
        $("select[name='hsn_code']").change(function(){
           var hsn_code_val = $(this).val();
           
           if(hsn_code_val!=''){
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $('#loader').show();
            $.ajax({
            url: "{{ url('admin/hsn_task_rate') }}" ,
            type: "POST",
            data: {hsn_code_val:hsn_code_val},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            $('#taxrate').val(response);
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
        $("select[name='unit'],select[name='pack_size']").change(function(){
           var unit_val = $("select[name='unit']").val();
           var pack_size_val = $("select[name='pack_size']").val();
           if(unit_val!='' && pack_size_val!=''){
            $('#packing_name').val(pack_size_val+" "+unit_val);
           }
        });
    });
</script>
<script>
$('.cas_number').keypress(function (e) {
    var allowedChars = new RegExp("^[0-9\-]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (allowedChars.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
}).keyup(function() {
    // the addition, which whill check the value after a keyup (triggered by Ctrl+V)
    // We take the same regex as for allowedChars, but we add ^ after the first bracket : it means "all character BUT these"
    var forbiddenChars = new RegExp("[^0-9\-]", 'g');
    if (forbiddenChars.test($(this).val())) {
        $(this).val($(this).val().replace(forbiddenChars, ''));
    }
});
</script>
@if($adddata['_id']=='6188f3acd049047e9b2f35d2' || $adddata['_id']=='619b4690d0490447703e8b72')
<script>
    $(function(){
       $("input[type='radio']").click(function(){
           var radioValue = $("input[name='register_type']:checked").val();
           if(radioValue==1)
           {
            $('.GST_number').attr('required',true); 
            $('.PAN_number ').attr('required',true); 
           }else{
            $('.GST_number').attr('required',false); 
            $('.PAN_number ').attr('required',false);   
           }
       });
    });
</script>
<script>
    $(function(){
       $("input[type='radio']").click(function(){
           var radioValue = $("input[name='medical_type']:checked").val();
           if(radioValue==1)
           {
            $('.drug_license_number').attr('required',true); 
           }else{
            $('.drug_license_number').attr('required',false); 
           }
       });
    });
</script>
<script>
    $(function(){
        $("#copy_head_address").click(function(){
        if ($("#copy_head_address").is(":checked")) {
        $('.b_area').val($('.h_area').val());
        $("input[name='b_landmark']").val($("input[name='h_landmark']").val());
        $("input[name='b_country']").val($("input[name='h_country']").val());
        $("input[name='b_city']").val($("input[name='h_city']").val());
        $("input[name='b_state']").val($("input[name='h_state']").val());
        $("input[name='b_pin_code']").val($("input[name='h_pin_code']").val());
        }else{
        $('.b_area').val(''); 
        $("input[name='b_landmark']").val('');
        $("input[name='b_country']").val('');
        $("input[name='b_city']").val('');
        $("input[name='b_state']").val('');
        $("input[name='b_pin_code']").val('');
        }
        });
    });
</script>
<script>
    $(function(){
        $("#copy_shipping_address").click(function(){
        if ($("#copy_shipping_address").is(":checked")) {
        $('.s_area').val($('.b_area').val());
        $("input[name='s_landmark']").val($("input[name='b_landmark']").val());
        $("input[name='s_country']").val($("input[name='b_country']").val());
        $("input[name='s_city']").val($("input[name='b_city']").val());
        $("input[name='s_state']").val($("input[name='b_state']").val());
        $("input[name='s_pin_code']").val($("input[name='b_pin_code']").val());
        }else{
        $('.s_area').val(''); 
        $("input[name='s_landmark']").val('');
        $("input[name='s_country']").val('');
        $("input[name='s_city']").val('');
        $("input[name='s_state']").val('');
        $("input[name='s_pin_code']").val('');
        }
        });
    });
</script>

<script>
    $(function(){
        $(document).on('click',".copy_depot_address",function(){
        var name_data = $(this).attr('name');
        var id_name = $(this).attr('cus');
        var ischecked= $(this).is(':checked');
        if (ischecked) {
        $("textarea[name='create_depot_address"+id_name+"[d_area]']").val($('.s_area').val());
        $("input[name='create_depot_address"+id_name+"[d_landmark]']").val($("input[name='s_landmark']").val());
        $("input[name='create_depot_address"+id_name+"[d_country]']").val($("input[name='s_country']").val());
        $("input[name='create_depot_address"+id_name+"[d_city]']").val($("input[name='s_city']").val());
        $("input[name='create_depot_address"+id_name+"[d_state]']").val($("input[name='s_state']").val());
        $("input[name='create_depot_address"+id_name+"[d_pin_code]']").val($("input[name='s_pin_code']").val());
        }else{
         if(!ischecked){
          //   alert(id_name);
        $("textarea[name='create_depot_address"+id_name+"[d_area]']").val('');
        $("input[name='create_depot_address"+id_name+"[d_landmark]']").val('');
        $("input[name='create_depot_address"+id_name+"[d_country]']").val('');
        $("input[name='create_depot_address"+id_name+"[d_city]']").val('');
        $("input[name='create_depot_address"+id_name+"[d_state]']").val('');
        $("input[name='create_depot_address"+id_name+"[d_pin_code]']").val('');
         }
        }
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
                    url: "{{ url('admin/purchase_net_rate_search') }}" ,
                    type: "POST",
                    data: formulario.serialize(),
                    success: function( response ) {
                    //alert(response); return false;

                    if(response!='')
                    {
                    $('#loader').hide();
                    var returnedData = JSON.parse(response);
                   $('#data-table-result').html(returnedData.html);
                    
                    //alert(returnedData.record_count);
                    $('#example-2').pagination({
                    total: returnedData.record_count, 
                    current: 1,
                    length: 10,
                    size: 2,
                    prev: 'Previous',
                    next: 'Next',
                    click: function(options, refresh, $target){
                    $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    var vendor_sku = $("#vendor_sku").val();
                    var name = $("#name").val();
                    var synonyms = $("#synonyms").val();
                    var grade = $("#grade").val();
                    var brand = $("#brand").val();
                    var packing_name = $("#packing_name").val();
                    var hsn_code = $("#hsn_code").val();
                    if($('input[name="is_verified"]').is(':checked'))
                    {
                    var is_verified = $('input[name="is_verified"]').val();
                    }else
                    {
                    var is_verified = '';
                    }
                    
                    $.ajax({
                    url: "{{ url('admin/purchase_net_rate_search') }}" ,
                    type: "POST",
                    data: {page:options.current,brand:brand,vendor_sku:vendor_sku,name:name,synonyms:synonyms,grade:grade,packing_name:packing_name,hsn_code:hsn_code,is_verified:is_verified},
                    success: function( response ) {
                    //alert(response); return false;
                    if(response!='')
                    {
                    var returnedData = JSON.parse(response);
                    $('#data-table-result').html(returnedData.html);  
                    }
                    }
                    }); 
                    }
                    });   
                   //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                   $('.btn-apply').show();
                    }else{
                   $('#data-table-result').html(''); 
                   //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                   $('#loader').hide();
                   $('.btn-apply').hide();
                    }
                    }
                    }); 
        });
    });
</script>
<script>
    $(function(){
        $(document).on('click','#reload',function(){
            //alert();
           $('#reloadform')[0].reset();
           $('#example-2').pagination({
    total: {{$itemlist_count}}, 
    current: 1,
    length: 10,
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
            url: "{{ url('admin/purchase_net_rate_search') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              var returnedData = JSON.parse(response);
              $('#data-table-result').html(returnedData.html);  
            }
            }
            }); 
    }
});
           $('.order_search').trigger('click');
        });
    });
</script>
<link rel="stylesheet" type="text/css" href="{{ url('css/pagination.min.css')}}">
  
<script src="{{ url('js/pagination.js')}}"></script>
<script>
    $('#example-2').pagination({
    total: {{$itemlist_count}}, 
    current: 1,
    length: 10,
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
            url: "{{ url('admin/purchase_net_rate_search') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              var returnedData = JSON.parse(response);
              $('#data-table-result').html(returnedData.html);  
            }
            }
            }); 
    }
});
</script>
<script>
    $(function(){
       $(document).on('click','.search-pop-up',function(){
          var name_id = $(this).attr('cus');
          //alert(name_id);
          $('#item-name-id').val(name_id);
       }); 
    });
</script>
<script>
    //$(".item_data").attr('readonly',true);
    $(document).ready(function(){
        $(".btn-apply").click(function(){
            var radioValue = $("input[name='apply-radio']:checked").val();
           var id = $("input[name='apply-radio']:checked").attr('id');
           //alert(radioValue); die;
           var name_id = $('#item-name-id').val();
           var result_id = name_id.replace("[create_customer_item]", "[item_id]");
           //alert(result_id);
           $("input[name='"+name_id+"']").val(radioValue);
           $("input[name='"+result_id+"']").val(id);
        });
    });
</script> 
@endif

@endsection