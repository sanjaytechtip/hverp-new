@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Form Preview')
@section('pagecontent')

<div class="page formbuilder">
	<div class="page-header">
        <h1 class="page-title">Form Preview</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('formlist') }}">Form List</a></li>
			<li class="breadcrumb-item"> Form Preview</li>
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
						<?php //pr($formpreview);
						echo GetFormByModule($formpreview['module_name'],$formpreview['module_name'],$formpreview['table_name']);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


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


    $("#{{$formpreview['module_name']}}").validate({
        
        rules: {
                @foreach(getValidationList($formpreview['module_name']) as $vali)
                @if($vali['field_type']=='multiselect' || $vali['field_type']=='checkbox') '{{$vali['field_name']}}[]' @else {{$vali['field_name']}} @endif: {
                required: true,
                @if($vali['character_limit']!='')
                @if($vali['field_type']=='password')
                minlength: 6,
                checklower: true,
                checkupper: true,
                checkdigit: true,
                @elseif($vali['field_type']=='email')
                email: true,
                @elseif($vali['field_type']=='radio')
                
                @else
                minlength: 2,
                @endif
                maxlength: {{$vali['character_limit']}},
                @endif
                },
                @endforeach
    },
    messages: {
        @foreach(getValidationList($formpreview['module_name']) as $vali_msg)
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
            @if($vali_msg['character_limit']!='')
            maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
            minlength: jQuery.validator.format("Please enter at least {0} characters."),
            @endif
        },
        @endforeach
    }
    });

});

</script>



    <script>
    $(function(){
    @foreach(getConditionalList($formpreview['module_name']) as $vali)
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
    @foreach(getConditionalList($formpreview['module_name']) as $vali)
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
<script>
    $(function(){
           var j=1;
           $(document).on('click','#add-more',function() {
               j++;
               var last_data = repeat.replaceAll("##", j).replace('__D__',j).replace('__id__',j);
              $("#add-more").before(last_data);
           });
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
<style>
    .ui-tooltip.ui-widget { display:none; }
</style>
@endsection