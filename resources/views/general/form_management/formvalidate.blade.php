@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'From Create Page')



@section('pagecontent')

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

.auto-fill-field{

	background-color : #d1d1d1;

}





.big-table table td input[type="number"] {

    width: 117px !important;

}



/* for auto select option */

.autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }

.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }

.autocomplete-no-suggestion { padding: 2px 5px;}

.autocomplete-selected { background: #F0F0F0; }

.autocomplete-suggestions strong { font-weight: bold; color: #000; }

.autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }



.numbers-only{

    width: 80px !important;

}

.color-field{

	width: 300px !important;

}

</style>

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

            <form class="" action="{{ url('admin/formupdate') }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();">

              @csrf

              <div class="card">

                <div class="card-body">

                  <div class="row" style="margin:0;">

                    

                    <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="first_name" autocomplete="off" name="first_name" class="form-control required" id="first_name">
                    </div>
                    <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" autocomplete="off" name="email" class="form-control required" id="email">
                    </div>
                    <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" autocomplete="off" name="password" class="form-control required" id="password">
                    </div>
                    <div class="checkbox form-group">
                    <label><input type="checkbox" name="option[]" value="Option 1">Option 1</label>
                    </div>
                    <div class="checkbox">
                    <label><input type="checkbox" name="option[]" value="Option 2">Option 2</label>
                    </div>
                    <div class="form-group">
                    <label for="sel1">Select list (select one):</label>
                    <select class="form-control" name="sel1" id="sel1">
                    <option value="">--Select--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    </select>
                    </div>
                    <div class="form-group">
                    <label for="pwd">Profile Image:</label>
                    <input type="file" id="myFile" class="required" name="filename">
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>

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

    $("#invoiceform").validate({
        
        rules: {
        first_name: {
            required: true,
            minlength: 2,
            maxlength: 30,
        },
        email: {
            required: true,
            email:true,
        },
        password:{
            required:true,
            //pwcheck:true,
            minlength: 6,
            maxlength: 30,
            checklower: true,
            checkupper: true,
            checkdigit: true,
        },
        'option[]': {
                required: true,
            },sel1:{
                required: true,
            },filename:{
                required: true,
            }
    },
    messages: {
        first_name: {
            required: "Enter your firstname",
            maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
            minlength: jQuery.validator.format("Please enter at least {0} characters."),
        },email: {
            required: "Enter your Email",
            email: "Please enter a valid email address.",
        },password:{
            required: "Enter your Password",
            pwcheck: "Password is not strong enough",
            checklower: "Need atleast 1 lowercase alphabet",
            checkupper: "Need atleast 1 uppercase alphabet",
            checkdigit: "Need atleast 1 digit",
        },'option[]': {
                required: "You must check at least 1 checkbox",
            },sel1: {
                required: "Please select one",
            },filename: {
                required: "Please Upload file",
            }
    }
    });

});

</script>


@endsection