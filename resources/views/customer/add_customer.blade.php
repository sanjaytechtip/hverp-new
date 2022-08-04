@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Customer')
@section('pagecontent')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
    <div class="page-header">
        <h1 class="page-title">Add Customer</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customerlist') }}">Customer : List</a></li>
            <li class="breadcrumb-item">Add Customer</li>
        </ol>
    </div>

    <div class="page-content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body_rename card-body">
                        <h2 class="form-box form-box-heading">Customers</h2>
                        <div class="custom-form-box">
                            <form class="custom-form" action="{{url('admin/add-customer-data')}}" id="customers"
                                method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                @csrf
                                <input type="hidden" name="table_name" value="customers">
                                <div class="form-box col1 block_unblock">
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="radio" value="1" class=" block_unblock " id="" required=""
                                                name="block_unblock" aria-required="true">
                                            Unblock</label>
                                        <label>
                                            <input type="radio" value="2" class=" block_unblock " id="" required=""
                                                name="block_unblock" aria-required="true">
                                            Block</label>
                                        <label id="block_unblock-error" class="error" for="block_unblock"></label>
                                    </div>
                                </div>

                                <div class="form-box col1 register_type">
                                    <label for="register_type">Register Type<span class="required"
                                            aria-required="true">*</span></label>
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="radio" value="1" class=" register_type " id="" required=""
                                                name="register_type" aria-required="true">
                                            Register</label>
                                        <label>
                                            <input type="radio" value="2" class=" register_type " id="" required=""
                                                name="register_type" aria-required="true">
                                            Unregistered</label>
                                        <label id="register_type-error" class="error" for="register_type"></label>
                                    </div>
                                </div>

                                <div class="form-box col1 medical_type">
                                    <label for="medical_type">Medical Type<span class="required"
                                            aria-required="true">*</span></label>
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="radio" value="1" class=" medical_type " id="" required=""
                                                name="medical_type" aria-required="true">
                                            Medical</label>
                                        <label>
                                            <input type="radio" value="2" class=" medical_type " id="" required=""
                                                name="medical_type" aria-required="true">
                                            Non-medical</label>
                                        <label id="medical_type-error" class="error" for="medical_type"></label>
                                    </div>
                                </div>

                                <div class="form-box col1 medical_type">
                                    <label for="medical_type">Is Dealer?<span class="required"
                                            aria-required="true">*</span></label>
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="radio" checked value="0" class=" is_dealer " id="" required=""
                                                name="is_dealer" aria-required="true">
                                            No</label>
                                        <label>
                                            <input type="radio" value="1" class=" medical_type " id="" required=""
                                                name="is_dealer" aria-required="true">
                                            Yes</label>
                                        
                                    </div>
                                </div>

                                <div class="form-box col1 company_name">
                                    <label for="company_name">Company Name<span class="required"
                                            aria-required="true">*</span></label>
                                    <input onBlur="return uniqueValueCheck(this);" autocomplete="off" type="text"
                                        cus="company_name" name="company_name" class="form-control company_name " id=""
                                        placeholder="Company Name" value="" maxlength="50" minlength="4" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box form-box-heading">Head Office Address</div>
                                <div class="form-box col1">
                                    <label for="h_area">Area<span class="required" aria-required="true">*</span></label>
                                    <textarea name="h_area" class="form-control h_area " id="" placeholder="Area"
                                        required="" aria-required="true"></textarea>
                                </div>
                                <div class="form-box col1 h_landmark">
                                    <label for="h_landmark">Landmark</label>
                                    <input autocomplete="off" type="text" name="h_landmark"
                                        class="form-control h_landmark " id="" placeholder="Landmark" value=""
                                        maxlength="50" minlength="2">
                                </div>
                                <div class="form-box col2 h_country">
                                    <label for="h_country">Country<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="h_country"
                                        class="form-control h_country " id="" placeholder="Country" value=""
                                        maxlength="50" minlength="2" required="" aria-required="true">
                                </div>
                                <div class="form-box col2 h_city">
                                    <label for="h_city">City<span class="required" aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="h_city" class="form-control h_city "
                                        id="" placeholder="City" value="" maxlength="50" minlength="2" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box col2 h_state">
                                    <label for="h_state">State<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="h_state" class="form-control h_state "
                                        id="" placeholder="State" value="" maxlength="50" minlength="2" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box col2 h_pin_code">
                                    <label for="h_pin_code">Pin Code<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" min="0" type="number" name="h_pin_code"
                                        class="form-control h_pin_code " id="" placeholder="Pin Code" value=""
                                        maxlength="6" minlength="6" required="" aria-required="true">
                                </div>
                                <div class="form-box form-box-heading">Billing Address</div>
                                <div class="form-box col1">
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="checkbox" value="1" class=" same_billing "
                                                id="copy_head_address" name="same_billing">
                                            Same as Head Office Address</label>
                                    </div>
                                </div>
                                <div class="form-box col1">
                                    <label for="b_area">Area<span class="required" aria-required="true">*</span></label>
                                    <textarea name="b_area" class="form-control b_area " id="" placeholder="Area"
                                        required="" aria-required="true"></textarea>
                                </div>
                                <div class="form-box col1 b_landmark">
                                    <label for="b_landmark">Landmark</label>
                                    <input autocomplete="off" type="text" name="b_landmark"
                                        class="form-control b_landmark " id="" placeholder="Landmark" value=""
                                        maxlength="50" minlength="2">
                                </div>
                                <div class="form-box col2 b_country">
                                    <label for="b_country">Country<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="b_country"
                                        class="form-control b_country " id="" placeholder="Country" value=""
                                        maxlength="50" minlength="2" required="" aria-required="true">
                                </div>
                                <div class="form-box col2 b_city">
                                    <label for="b_city">City<span class="required" aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="b_city" class="form-control b_city "
                                        id="" placeholder="City" value="" maxlength="50" minlength="2" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box col2 b_state">
                                    <label for="b_state">State<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="b_state" class="form-control b_state "
                                        id="" placeholder="State" value="" maxlength="50" minlength="2" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box col2 b_pin_code">
                                    <label for="b_pin_code">Pin Code<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" min="0" type="number" name="b_pin_code"
                                        class="form-control b_pin_code " id="" placeholder="Pin Code" value=""
                                        maxlength="6" minlength="6" required="" aria-required="true">
                                </div>
                                <div class="form-box form-box-heading">Shipping Address</div>
                                <div class="form-box col1">
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="checkbox" value="1" class=" same_shipping "
                                                id="copy_shipping_address" name="same_shipping">
                                            Same as Billing Address</label>
                                    </div>
                                </div>
                                <div class="form-box col1">
                                    <label for="s_area">Area<span class="required" aria-required="true">*</span></label>
                                    <textarea name="s_area" class="form-control s_area " id="" placeholder="Area"
                                        required="" aria-required="true"></textarea>
                                </div>
                                <div class="form-box col1 s_landmark">
                                    <label for="s_landmark">Landmark</label>
                                    <input autocomplete="off" type="text" name="s_landmark"
                                        class="form-control s_landmark " id="" placeholder="Landmark" value=""
                                        maxlength="50" minlength="2">
                                </div>
                                <div class="form-box col2 s_country">
                                    <label for="s_country">Country<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="s_country"
                                        class="form-control s_country " id="" placeholder="Country" value=""
                                        maxlength="50" minlength="2" required="" aria-required="true">
                                </div>
                                <div class="form-box col2 s_city">
                                    <label for="s_city">City<span class="required" aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="s_city" class="form-control s_city "
                                        id="" placeholder="City" value="" maxlength="50" minlength="2" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box col2 s_state">
                                    <label for="s_state">State<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="text" name="s_state" class="form-control s_state "
                                        id="" placeholder="State" value="" maxlength="50" minlength="2" required=""
                                        aria-required="true">
                                </div>
                                <div class="form-box col2 s_pin_code">
                                    <label for="s_pin_code">Pin Code<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" min="0" type="number" name="s_pin_code"
                                        class="form-control s_pin_code " id="" placeholder="Pin Code" value=""
                                        maxlength="6" minlength="6" required="" aria-required="true">
                                </div>

                                <div class="form-box col1 s_google_location">
                                    <label for="s_google_location">Google Location</label>
                                    <input autocomplete="off" type="text" name="s_google_location"
                                        class="form-control s_google_location " id="link" placeholder="Google Location"
                                        value="" maxlength="50" minlength="2">
                                    <a class="form-note" target="_blank" href="https://www.google.com/maps/">Google
                                        Map</a>
                                </div>
                                <div class="form-box ">
                                    <hr>
                                </div>
                                <div class="form-box col1 email">
                                    <label for="email">Email Id<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" type="email" cus="email" name="email"
                                        class="form-control email " id="email" placeholder="Email Id" value=""
                                        maxlength="50" minlength="6" required="" aria-required="true">
                                </div>
                                <div class="form-box col1 website">
                                    <label for="website">Website</label>
                                    <input autocomplete="off" type="url" name="website" class="form-control website "
                                        id="" placeholder="Website" value="" maxlength="50" minlength="6">
                                </div>
                                <div class="form-box col1 contact_number">
                                    <label for="contact_number">Contact Number</label>
                                    <input autocomplete="off" min="0" type="number" name="contact_number"
                                        class="form-control contact_number " id="" placeholder="Contact Number" value=""
                                        maxlength="15" minlength="6">
                                </div>
                                <div class="form-box col2 directors_name">
                                    <label for="directors_name">Directors Name</label>
                                    <input autocomplete="off" type="text" name="directors_name"
                                        class="form-control directors_name " id="" placeholder="Directors Name" value=""
                                        maxlength="50" minlength="2">
                                </div>
                                <div class="form-box col2 contact_no">
                                    <label for="contact_no">Contact No</label>
                                    <input autocomplete="off" min="0" type="number" name="contact_no"
                                        class="form-control contact_no " id="" placeholder="Contact No" value=""
                                        maxlength="15" minlength="6">
                                </div>
                                <div class="form-box col2 2nd_directors_name">
                                    <label for="2nd_directors_name">2nd Directors Name</label>
                                    <input autocomplete="off" type="text" name="2nd_directors_name"
                                        class="form-control 2nd_directors_name " id="" placeholder="2nd Directors Name"
                                        value="" maxlength="50" minlength="2">
                                </div>
                                <div class="form-box col2 2nd_contact_no">
                                    <label for="2nd_contact_no">Contact No</label>
                                    <input autocomplete="off" min="0" type="number" name="2nd_contact_no"
                                        class="form-control 2nd_contact_no " id="" placeholder="Contact No" value=""
                                        maxlength="15" minlength="6">
                                </div>
                                <div class="form-box col2 responsible_emp">
                                    <label for="responsible_emp">Responsible Emp</label>
                                    <input autocomplete="off" type="text" name="responsible_emp"
                                        class="form-control responsible_emp " id="" placeholder="Responsible Employee"
                                        value="" maxlength="50" minlength="2">
                                </div>
                                <div class="form-box col2 responsible_contact_no">
                                    <label for="responsible_contact_no">Contact No</label>
                                    <input autocomplete="off" min="0" type="number" name="responsible_contact_no"
                                        class="form-control responsible_contact_no " id="" placeholder="Contact No"
                                        value="">
                                </div>
                                <div class="form-box col1 GST_number">
                                    <label for="GST_number">GST Number</label>
                                    <input autocomplete="off" type="text" name="GST_number"
                                        class="form-control GST_number " id="" placeholder="GST Number" value=""
                                        maxlength="20" minlength="15">
                                </div>
                                <div class="form-box col1 PAN_number">
                                    <label for="PAN_number">PAN Number</label>
                                    <input autocomplete="off" type="text" name="PAN_number"
                                        class="form-control PAN_number " id="" placeholder="PAN Number" value=""
                                        maxlength="10" minlength="10">
                                </div>
                                <div class="form-box col1 TAN_number">
                                    <label for="TAN_number">TAN Number</label>
                                    <input autocomplete="off" type="text" name="TAN_number"
                                        class="form-control TAN_number " id="" placeholder="TAN Number" value=""
                                        maxlength="20" minlength="10">
                                </div>
                                <div class="form-box col1 advance_required">
                                    <label for="advance_required">Advance Required(%)</label>
                                    <input autocomplete="off" max="100" min="0" type="number" name="advance_required"
                                        class="form-control advance_required " id="" placeholder="Advance Required"
                                        value="" maxlength="5">
                                </div>
                                <div class="form-box col2 credit_limit">
                                    <label for="credit_limit">Credit Limit</label>
                                    <input autocomplete="off" min="0" type="number" name="credit_limit"
                                        class="form-control credit_limit " id="" placeholder="Credit Limit" value=""
                                        maxlength="7">
                                </div>
                                <div class="form-box col2 credit_days">
                                    <label for="credit_days">Credit Days</label>
                                    <input autocomplete="off" min="0" type="number" name="credit_days"
                                        class="form-control credit_days " id="" placeholder="Credit Days" value=""
                                        maxlength="10" minlength="1">
                                </div>
                                <div class="form-box col1 state_code">
                                    <label for="state_code">State Code<span class="required"
                                            aria-required="true">*</span></label>
                                    <input autocomplete="off" min="0" type="number" name="state_code"
                                        class="form-control state_code " id="" placeholder="State Code" value=""
                                        maxlength="5" required="" aria-required="true">
                                </div>
                                <div class="form-box col1 drug_license_number">
                                    <label for="drug_license_number">Drug License Number</label>
                                    <input autocomplete="off" type="text" name="drug_license_number"
                                        class="form-control drug_license_number " id=""
                                        placeholder="Drug License Number" value="" maxlength="20">
                                </div>
                                <div class="form-box col2">
                                    <label for="segment">Segment<span class="required"
                                            aria-required="true">*</span></label>
                                    <select class="form-control" data-plugin="select2" data-placeholder="Type name"
                                        data-minimum-input-length="1" name="segment">
                                        <option value="">--Select Segment--</option>
                                        @foreach($segments as $seg)
                                        <option value="{{$seg['id']}}">{{$seg['segment']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-box col2 category">
                                    <label for="category">Category<span class="required"
                                            aria-required="true">*</span></label>
                                    <select class="form-control" data-plugin="select2" data-placeholder="Type name"
                                        data-minimum-input-length="1" name="category">
                                        <option value="" data-select2-id="6">--Select Category--</option>
                                        @foreach($customer_categories as $customer_cat)
                                        <option value="{{$seg['id']}}">{{$customer_cat['category']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-box col1 quotation_invoice_limit">
                                    <label for="quotation_invoice_limit">Quotation &amp; Invoice Limit</label>
                                    <input autocomplete="off" min="0" type="number" name="quotation_invoice_limit"
                                        class="form-control quotation_invoice_limit " id=""
                                        placeholder="Quotation &amp; Invoice Limit" value="" maxlength="50"
                                        minlength="2">
                                </div>
                                <div class="form-box col1 bank_account_details">
                                    <label for="bank_account_details">Bank Account Details</label>
                                    <input autocomplete="off" min="0" type="number" name="bank_account_details"
                                        class="form-control bank_account_details " id=""
                                        placeholder="Bank Account Details" value="" maxlength="16" minlength="9">
                                </div>
                                <div class="form-box col2 bank_account_name">
                                    <label for="bank_account_name">Bank Account Name</label>
                                    <input autocomplete="off" type="text" name="bank_account_name"
                                        class="form-control bank_account_name " id="" placeholder="Bank Account Name"
                                        value="" maxlength="30">
                                </div>
                                <div class="form-box col2 IFSC_code">
                                    <label for="IFSC_code">IFSC Code</label>
                                    <input autocomplete="off" type="text" name="IFSC_code"
                                        class="form-control IFSC_code " id="" placeholder="IFSC Code" value=""
                                        maxlength="12" minlength="8">
                                </div>
                                <div class="form-box col1 choose_process">
                                    <label for="choose_process">Choose Process</label>
                                    <select class="form-control" data-plugin="select2" data-placeholder="Type name"
                                        data-minimum-input-length="1" name="choose_process">
                                        <option value="">--Select Process--</option>
                                        @foreach($processes as $process)
                                        <option value="{{$process['id']}}">{{$process['process']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-box col1 favorite_products">
                                    <label for="favorite_products">Favorite Products</label>
                                    <select class="form-control favorite_products " id=""
                                        placeholder="Favorite Products" name="favorite_products">
                                        <option value="">--Select--</option>
                                        <option value="pro1">pro1</option>
                                        <option value="pro2">pro2</option>
                                    </select>
                                </div>
                                <div class="form-box col1 add_favorite_products">
                                    <label for="add_favorite_products">Add Favorite Products</label>
                                    <select class="form-control add_favorite_products " id="" placeholder=""
                                        name="add_favorite_products">
                                        <option value="">--Select--</option>
                                        <option value="opt1">opt1</option>
                                        <option value="opt2">opt2</option>
                                    </select>
                                </div>
                                <div class="form-box col1 preference_1">
                                    <label for="preference_1">Preference#1</label>
                                </div>
                                {!! get_preference($data=array('field_id'=>'search','field_name'=>'preference_1'),$edit_data=array(),'preference_1') !!}

                                <div class="form-box col1 preference_2">
                                    <label for="preference_2">Preference#2</label>
                                </div>
                                {!! get_preference($data=array('field_id'=>'search','field_name'=>'preference_2'),$edit_data=array(),'preference_2') !!}

                                <div class="form-box col1 preference_3">
                                    <label for="preference_3">Preference#3</label>
                                </div>
                                {!! get_preference($data=array('field_id'=>'search','field_name'=>'preference_3'),$edit_data=array(),'preference_3') !!}

                                <div class="form-box col1 single_so_single_lot">
                                    <label for="single_so_single_lot">Single SO Single Lot</label>
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="radio" value="yes" class=" single_so_single_lot " id=""
                                                name="single_so_single_lot">
                                            Yes</label>
                                        <label>
                                            <input type="radio" value="no" class=" single_so_single_lot " id=""
                                                name="single_so_single_lot">
                                            No</label>
                                        <label id="single_so_single_lot-error" class="error"
                                            for="single_so_single_lot"></label>
                                    </div>
                                </div>
                                <div class="form-box col1 separate_bills_for_separate_SOs">
                                    <label for="separate_bills_for_separate_SOs">Separate Bills for Separate SOs</label>
                                    <div class="inline-radio-box inline-radio">
                                        <label>
                                            <input type="radio" value="yes" class=" separate_bills_for_separate_SOs "
                                                id="" name="separate_bills_for_separate_SOs">
                                            Yes</label>
                                        <label>
                                            <input type="radio" value="no" class=" separate_bills_for_separate_SOs "
                                                id="" name="separate_bills_for_separate_SOs">
                                            No</label>
                                        <label id="separate_bills_for_separate_SOs-error" class="error"
                                            for="separate_bills_for_separate_SOs"></label>
                                    </div>
                                </div>
                                <div class="form-box col1 payment_terms">
                                    <label for="payment_terms">Payment Terms</label>
                                    <select class="form-control" data-plugin="select2" data-placeholder="Type name"
                                        data-minimum-input-length="1" name="payment_terms">
                                        <option value="">--Select Payment Terms--</option>
                                        @foreach($payment_terms as $pay_term)
                                        <option value="{{$pay_term['id']}}">{{$pay_term['payment_terms']}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-box col1 adv_amount">
                                    <label for="adv_amount">Advance Amount (%)</label>
                                    <input autocomplete="off" min="0" type="number" name="adv_amount"
                                        class="form-control adv_amount " id="" placeholder="Advance Amount (%)" value=""
                                        maxlength="2" minlength="1">
                                    <span class="form-note">Enter percentage (%) value.</span>
                                </div>
                                
                                <div class="form-box form-box-heading">CREATE DEPARTMENT(S)</div>
                                <div class="form-box rows  line_repeat_1" id="rep-0">
                                    <div class="form-box col1 department_name">
                                        <label for="department_name">Department Name</label>
                                        <select class="form-control" data-plugin="select2" data-placeholder="Type name"
                                            data-minimum-input-length="1" name="create_departments[0][department_name]">
                                            <option value="">--Select Department--</option>
                                            @foreach($departments as $department)
                                            <option value="{{$department['id']}}">{{$department['department_name']}}
                                            </option>
                                            @endforeach


                                        </select>
                                    </div>
                                    <div class="form-box rows line_1">
                                        <div class="form-box col1 name">
                                            <label for="name">Name</label>
                                            <input autocomplete="off" type="text"
                                                name="create_departments[0][departments_repeat][0][name]"
                                                class="form-control name " id="" placeholder="" value="" maxlength="50">
                                        </div>
                                        <div class="form-box col1 designation">
                                            <label for="designation">Designation</label>
                                            <select class="form-control" data-plugin="select2"
                                                data-placeholder="Type name" data-minimum-input-length="1"
                                                name="create_departments[0][departments_repeat][0][designation]">
                                                <option value="">--Select Designation--</option>
                                                @foreach($designations as $designation)
                                                <option value="{{$designation['id']}}">{{$designation['designation']}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-box col1 mobile_no">
                                            <label for="mobile_no">Mobile No</label>
                                            <input autocomplete="off" min="0" type="number"
                                                name="create_departments[0][departments_repeat][0][mobile_no]"
                                                class="form-control mobile_no " id="" placeholder="" value=""
                                                maxlength="10" minlength="10">
                                        </div>
                                        <div class="form-box col1 email">
                                            <label for="email">Email</label>
                                            <input autocomplete="off" type="text"
                                                name="create_departments[0][departments_repeat][0][email]"
                                                class="form-control email" id="" placeholder="" value="">
                                        </div>
                                        <div class="form-box col1 whatsapp_no">
                                            <label for="whatsapp_no">Whatsapp No</label>
                                            <input autocomplete="off" min="0" type="number"
                                                name="create_departments[0][departments_repeat][0][whatsapp_no]"
                                                class="form-control whatsapp_no " id="" placeholder="" value=""
                                                maxlength="10" minlength="10">
                                        </div>
                                    </div>
                                    <div class="form-box add-more-add departments_repeat" cus="0"
                                        style="float:right; margin-top:10px;"><a href="javascript:void(0);">+</a>
                                        <script>
                                        $(function() {
                                            var j = 100;
                                            $(document).on("click", ".departments_repeat", function() {
                                                j++;
                                                var departments_repeat =
                                                    '<div class="form-box more-rep-div-add rep-div-add" id="rep-__id__"><div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div><div class="form-box col1 name"><label for="name">Name</label><input autocomplete="off" type="text" name="create_departments[0][departments_repeat][0][name]" class="form-control name " id="" placeholder="" value="" maxlength="50"></div><div class="form-box col1 designation"><label for="designation">Designation</label><select class="form-control " data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="create_departments[0][departments_repeat][0][designation]"><option value="">--Select Designation--</option>@foreach($designations as $designation) <option value="{{$designation['id']}}">{{$designation['designation']}} </option> @endforeach</select></div><div class="form-box col1 mobile_no"><label for="mobile_no">Mobile No</label><input autocomplete="off" min=0 type="number" name="create_departments[0][departments_repeat][0][mobile_no]" class="form-control mobile_no " id="" placeholder="" value="" maxlength="10" minlength="10"></div><div class="form-box col1 email"> <label for="email">Email</label> <input autocomplete="off" type="text" name="create_departments[0][departments_repeat][0][email]" class="form-control email" id="" placeholder="" value=""> </div><div class="form-box col1 whatsapp_no"><label for="whatsapp_no">Whatsapp No</label><input autocomplete="off" min=0 type="number" name="create_departments[0][departments_repeat][0][whatsapp_no]" class="form-control whatsapp_no " id="" placeholder="" value="" maxlength="10" minlength="10"></div></div>';
                                                var last_data_new = departments_repeat.replaceAll("##",
                                                    j).replaceAll("__D__", j).replaceAll("__id__",
                                                    j).replaceAll("[0]", "[" + j + "]");
                                                var data = $(this).closest("div.line_repeat_1").find(
                                                    "input").attr("name");
                                                var result1 = data.split("][");
                                                var result2 = result1[0].split("[");
                                                var data_new_jquery = last_data_new.replaceAll(result2[
                                                        0] + "[" + j + "]", result2[0] + "[" +
                                                    result2[1] + "]");
                                                $(this).before(data_new_jquery);
                                            });
                                        });
                                        </script>
                                    </div>
                                </div>
                                <div class="form-box add-more" style="float:right; margin-top:10px;"><a
                                        id="create_departments" href="javascript:void(0);">+ Add More</a>
                                    <script>
                                    $(function() {
                                        var j = $(".line_repeat_1").length;
                                        $(document).on("click", "#create_departments", function() {
                                            j++;
                                            var create_departments =
                                                '<div class="form-box rep-div-add line_repeat_1" id="rep-__id__"><div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div><div class="form-box col1 department_name"><label for="department_name">Department Name</label><select class="form-control " data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="create_departments[0][department_name]"><option value="">--Select Department--</option>@foreach($departments as $department) <option value="{{$department['id']}}">{{$department['department_name']}} </option> @endforeach</select></div><div class="form-box rows line_1"><div class="form-box col1 name"><label for="name">Name</label><input autocomplete="off" type="text" name="create_departments[0][departments_repeat][0][name]" class="form-control name " id="" placeholder="" value="" maxlength="50"></div><div class="form-box col1 designation"><label for="designation">Designation</label><select class="form-control " data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="create_departments[0][departments_repeat][0][designation]"><option value="">--Select Designation--</option>@foreach($designations as $designation) <option value="{{$designation['id']}}">{{$designation['designation']}} </option> @endforeach</select></div><div class="form-box col1 mobile_no"><label for="mobile_no">Mobile No</label><input autocomplete="off" min=0 type="number" name="create_departments[0][departments_repeat][0][mobile_no]" class="form-control mobile_no " id="" placeholder="" value="" maxlength="10" minlength="10"></div><div class="form-box col1 email"> <label for="email">Email</label> <input autocomplete="off" type="text" name="create_departments[0][departments_repeat][0][email]" class="form-control email" id="" placeholder="" value=""> </div><div class="form-box col1 whatsapp_no"><label for="whatsapp_no">Whatsapp No</label><input autocomplete="off" min=0 type="number" name="create_departments[0][departments_repeat][0][whatsapp_no]" class="form-control whatsapp_no " id="" placeholder="" value="" maxlength="10" minlength="10"></div></div><div class="form-box add-more-add departments_repeat" cus="0" style="float:right; margin-top:10px;"><a href="javascript:void(0);">+</a></div></div>';
                                            var last_data = create_departments.replaceAll("##", j)
                                                .replaceAll("__D__", j).replaceAll("__id__", j)
                                                .replaceAll("[0]", "[" + j + "]");
                                            $(this).parent().before(last_data);
                                        });
                                    });
                                    </script>
                                </div>
                                <input autocomplete="off" type="hidden" name="customer" class="form-control customer "
                                    id="" placeholder="" value="">
                                <div class="form-box submit-reset"></div>
                                <div class="form-box col2">
                                    <input type="submit" name="submit" class="form-control submit " id=""
                                        value="Submit">
                                </div>
                                <div class="form-box col2">
                                    <input type="reset" name="reset" class="form-control reset " id="" value="Reset">
                                </div>
                                <input type="hidden" name="created_on" value="2022-05-30 03:08:02">
                            </form>
                        </div>
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
                            <input class="form-control" type="text" id="vendor_sku" name="vendor_sku"
                                value="{{@$vendor_sku}}" autocomplete="off" placeholder="">
                        </div>
                        <div class="form-wrap">
                            <label>Name</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{@$name}}"
                                autocomplete="off" placeholder="">
                        </div>
                        <div class="form-wrap small-box">
                            <label>Synonyms</label>
                            <input class="form-control" type="text" id="synonyms" name="synonyms" value="{{@$synonyms}}"
                                autocomplete="off" placeholder="">
                        </div>
                        <div class="form-wrap small-box">
                            <label>Grade</label>
                            <input class="form-control" type="text" id="grade" name="grade" value="{{@$grade}}"
                                autocomplete="off" placeholder="">
                        </div>
                        <div class="form-wrap small-box">
                            <label>Brand</label>
                            <input class="form-control" type="text" id="brand" name="brand" value="{{@$brand}}"
                                autocomplete="off" placeholder="">
                        </div>
                        <div class="form-wrap small-box">
                            <label>Packing</label>
                            <input class="form-control" type="text" id="packing_name" name="packing_name"
                                value="{{@$packing_name}}" autocomplete="off" placeholder="">
                        </div>
                        <div class="form-wrap small-box">
                            <label>HSN Code</label>
                            <input class="form-control" type="text" id="hsn_code" name="hsn_code" value="{{@$hsn_code}}"
                                autocomplete="off" placeholder="">
                        </div>
                    </div>
                    <div class="new-form-box new-form-box-radio-box">
                        <div class="new-form-radio-box">
                            <input type="radio" id="html" name="is_verified" @if($is_verified=='TRUE' ) checked @endif
                                value="TRUE"><label for="html">Verified</label>
                        </div>
                        <div class="new-form-radio-box">
                            <input type="radio" id="css" @if($is_verified=='FALSE' ) checked @endif name="is_verified"
                                value="FALSE"><label for="css">Unverified</label>
                        </div>
                        <div class="form-wrap form-wrap-submit">
                            <button class="btn btn-primary waves-effect waves-classic order_search" type="button"
                                name="order_search">Search</button>
                        </div>

                        <div class="form-wrap form-wrap-submit"> <a id="reload" href="javascript:void(0);"
                                class="btn btn-primary reload waves-effect waves-classic" title="Reload"
                                name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>

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
                                <td style="text-align:center;" class="white-space-normal"><input id="{{$user[_id]}}"
                                        value="{{$user['vendor_sku']}}-{{$user['name']}}-{{$user['hsn_code']}}-{{$user['grade']}}-{{$user['brand']}}-{{$user['packing_name']}}-{{$user['list_price']}}-{{$user['mrp']}}-{{$user['net_rate']}}-{{$user['stock']}}"
                                        name="apply-radio" type="radio"></td>
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
        return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) &&
            /[A-Z]/.test(value);
    });

    $.validator.addMethod("checkExists", function(value) {
        return true;
    });

    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z. ]+$/i.test(value);
    }, "Letters and spaces only please");


    $("#customers").validate({

        rules: {
            block_unblock: {
                required: true,
            },
            register_type: {
                required: true,

                maxlength: 20,
            },
            medical_type: {
                required: true,

                maxlength: 20,
            },
            company_name: {
                required: true,
                minlength: 4,

                maxlength: 50,
            },
            h_area: {
                required: true,
                minlength: 2,

                maxlength: 1000,
            },
            h_country: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            h_city: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            h_state: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            h_pin_code: {
                required: true,
                minlength: 6,

                maxlength: 6,
            },
            b_area: {
                required: true,
                minlength: 2,

                maxlength: 1000,
            },
            b_country: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            b_city: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            b_state: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            b_pin_code: {
                required: true,
                minlength: 6,

                maxlength: 6,
            },
            s_area: {
                required: true,
                minlength: 2,

                maxlength: 1000,
            },
            s_country: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            s_city: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            s_state: {
                required: true,
                minlength: 2,

                maxlength: 50,
            },
            s_pin_code: {
                required: true,
                minlength: 6,

                maxlength: 6,
            },
            email: {
                required: true,
                minlength: 6,
                checkExists: true,
                email: true,
                remote: {
                    url: "{{url('/admin/emailchecker')}}",
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        email: function() {
                            return $("#email").val();
                        },
                        table_name: function() {
                            return $("input[name=table_name]").val();
                        }
                    }
                },
                maxlength: 50,
            },
            state_code: {
                required: true,
                minlength: 1,

                maxlength: 5,
            },
            segment: {
                required: true,
            },
            category: {
                required: true,
            },
        },
        messages: {
            block_unblock: {},
            register_type: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            medical_type: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            company_name: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            h_area: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            h_country: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            h_city: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            h_state: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            h_pin_code: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            b_area: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            b_country: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            b_city: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            b_state: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            b_pin_code: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            s_area: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            s_country: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            s_city: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            s_state: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            s_pin_code: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            email: {
                remote: "This email address is already registered.",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            state_code: {
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
            },
            segment: {},
            category: {},
        }
    });

});
</script>



<script>
$(function() {
    @foreach(getConditionalList($adddata['module_name']) as $vali)
    @if(!empty($vali['conditional']) && !empty($vali['sub_cond']))
    $(window).on('load', function() {
        //alert($("input[name='{{$vali['conditional']}}']").is(':checked'));
        if ($("input[name='{{$vali['conditional']}}']").is(':checked')) {
            var check_val = $("input[name='{{$vali['conditional']}}']:checked").val();
            if (check_val == '{{$vali["sub_cond"]}}') {
                $('.{{$vali["field_name"]}}').show();
            } else {
                $('.{{$vali["field_name"]}}').hide();
            }
        } else {
            //alert('hii');
            $('.{{$vali["field_name"]}}').hide();
        }
    });
    @endif
    @endforeach
});
</script>
<script>
$(function() {
    @foreach(getConditionalList($adddata['module_name']) as $vali)
    @if(!empty($vali['conditional']) && !empty($vali['sub_cond']))
    $('input[name="{{$vali['
        conditional ']}}"]').click(function() {
        if ($("input[name='{{$vali['conditional']}}']").is(':checked')) {
            var check_val = $(this).val();
            if (check_val == '{{$vali["sub_cond"]}}') {
                $('.{{$vali["field_name"]}}').show();
            } else {
                //alert(check_val);
                $('.{{$vali["field_name"]}}').hide();
            }
        } else {
            $('.{{$vali["field_name"]}}').hide();
        }
    });
    @endif
    @endforeach
});
</script>


<link rel="stylesheet" media="all" type="text/css"
    href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
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
$(function() {
    $(document).on('change', "select[name='pack_size']", function() {
        var pack_size_val = $("select[name='pack_size']").val();
        var list_price = $("input[name='list_price']").val();
        if (pack_size_val != '' && list_price != '') {
            var unit_price = eval(list_price) / eval(pack_size_val);
            $('.unit_price').val(unit_price.toFixed(2));
        }
    });
});
$(function() {
    $(document).on('keyup change', "input[name='list_price']", function() {
        var pack_size_val = $("select[name='pack_size']").val();
        var list_price = $("input[name='list_price']").val();
        if (pack_size_val != '' && list_price != '') {
            var unit_price = eval(list_price) / eval(pack_size_val);
            $('.unit_price').val(unit_price.toFixed(2));
        }
    });
});
</script>


<script>
$(function() {
    var getItemArr = '<?php print_r(getItemGroup()); ?>';
    getItemArr = JSON.parse(getItemArr);
    //console.log(buyerArr);
    var getItemData = $.map(getItemArr, function(value, key) {
        return {
            value: value,
            data: key
        };
    });
    //console.log(buyerData);
    var getItemHtml = '<option value="">--Select--</option>';
    for (var i = 0; i < getItemData.length; i++) {
        getItemHtml += '<option value="' + getItemData[i].data + '">' + getItemData[i].value + '</option>';
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

togglePassword.addEventListener('click', function(e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
</script>

<script>
$(function() {
    $(document).on('click', '.delete-row', function() {
        var id = $(this).attr('cus');
        $('#rep-' + id).remove();
    });
});
</script>
<script>
$(function() {
    $(document).on('click', '.delete-row-repeat', function() {
        var id = $(this).attr('cus');
        $('#rep-repeat' + id).remove();
    });
});
</script>
<script>
$(function() {
    $(document).on('change', '.brand_item', function() {
        var brand_item = $(this).val();
        var cus_item = $(this).attr('cus');
        //alert(brand_item);
        var result1 = cus_item.split('[');
        var data = result1[1].split(']');
        if (brand_item != '') {
            if (brand_item == 'brand') {
                $('#rep-' + data[0] + '> div.per_discount').show();
                $('#rep-' + data[0] + '> div.discounted_price').hide();
            } else if (brand_item == 'item') {
                $('#rep-' + data[0] + '> div.per_discount').hide();
                $('#rep-' + data[0] + '> div.discounted_price').show();
            }
        } else {
            $('#rep-' + data[0] + '> div.per_discount').show();
            $('#rep-' + data[0] + '> div.discounted_price').show();
        }
    });
});
</script>
@if($adddata['_id']=='617f8983d0490476da34a012')
<script>
$(document).on('blur', '.pack_size', function() {
    var pack_size_name = $(this).attr('name');
    var data = pack_size_name.match(/\d+/);

    var pack_size_val = $('input[name="repeat[' + data + '][pack_size]"]').val();
    var unit_val = $('select[name="repeat[' + data + '][unit]"]').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (pack_size_val != '' && unit_val != '') {
        $('#loader').show();
        $.ajax({
            url: "{{ url('admin/uniqueValueCheckPackSize') }}",
            type: "POST",
            data: {
                pack_size_val: pack_size_val,
                unit_val: unit_val
            },
            success: function(response) {
                //alert(response); return false;
                if (response == '1') {
                    alert('Please enter unique value.');
                    //$('.submit').prop('disabled',true);
                    $('input[name="repeat[' + data + '][pack_size]"]').val('');
                    $('select[name="repeat[' + data + '][unit]"]').val('');
                    $('#loader').hide();
                    return false;
                } else {
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
$(document).on('blur', '.dept', function() {
    var task_category_name = $(this).attr('name');
    var data = task_category_name.match(/\d+/);
    //alert(data); return false;
    var task_category_val = $('input[name="repeat[' + data + '][task_category]"]').val();
    var dept_val = $('select[name="repeat[' + data + '][department]"]').val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //alert(dept_val+'-'+task_category_val); return false;
    if (dept_val != '' && task_category_val != '') {
        $('#loader').show();
        $.ajax({
            url: "{{ url('admin/uniqueValueCheckTaskCategory') }}",
            type: "POST",
            data: {
                dept_val: dept_val,
                task_category_val: task_category_val
            },
            success: function(response) {
                //alert(response); return false;
                if (response == '1') {
                    alert('Please enter unique value.');
                    //$('.submit').prop('disabled',true);
                    $('input[name="repeat[' + data + '][task_category]"]').val('');
                    $('select[name="repeat[' + data + '][department]"]').val('');
                    $('#loader').hide();
                    return false;
                } else {
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
function uniqueValueCheck(val) {
    var input_val = val.value;
    var table_name = "{{$adddata['table_name']}}";
    var field_name = $(val).attr('cus');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (input_val != '' && table_name != '' && field_name != '') {
        //alert(field_name); return false;
        //$('.submit').prop('disabled',true);
        $('#loader').show();
        $.ajax({
            url: "{{ url('admin/uniqueValueCheck') }}",
            type: "POST",
            data: {
                input_val: input_val,
                table_name: table_name,
                field_name: field_name
            },
            success: function(response) {
                //alert(response); return false;
                if (response == '1') {
                    alert('Please enter unique value.');
                    //$('.submit').prop('disabled',true);
                    $(val).val('');
                    $('#loader').hide();
                    return false;
                } else {
                    $('#loader').hide();
                    //$('.submit').prop('disabled',false);    
                }
            }
        });
    }
}
</script>
<style>
.ui-tooltip.ui-widget {
    display: none;
}
</style>
<script>
$(function() {
    $("select[name='hsn_code']").change(function() {
        var hsn_code_val = $(this).val();

        if (hsn_code_val != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#loader').show();
            $.ajax({
                url: "{{ url('admin/hsn_task_rate') }}",
                type: "POST",
                data: {
                    hsn_code_val: hsn_code_val
                },
                success: function(response) {
                    //alert(response); return false;
                    if (response != '') {
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
$(function() {
    $("select[name='unit'],select[name='pack_size']").change(function() {
        var unit_val = $("select[name='unit']").val();
        var pack_size_val = $("select[name='pack_size']").val();
        if (unit_val != '' && pack_size_val != '') {
            $('#packing_name').val(pack_size_val + " " + unit_val);
        }
    });
});
</script>
<script>
$('.cas_number').keypress(function(e) {
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

<script>
$(function() {
    $("input[type='radio']").click(function() {
        var radioValue = $("input[name='register_type']:checked").val();
        if (radioValue == 1) {
            $('.GST_number').attr('required', true);
            $('.PAN_number ').attr('required', true);
        } else {
            $('.GST_number').attr('required', false);
            $('.PAN_number ').attr('required', false);
        }
    });
});
</script>
<script>
$(function() {
    $("input[type='radio']").click(function() {
        var radioValue = $("input[name='medical_type']:checked").val();
        if (radioValue == 1) {
            $('.drug_license_number').attr('required', true);
        } else {
            $('.drug_license_number').attr('required', false);
        }
    });
});
</script>
<script>
$(function() {
    $("#copy_head_address").click(function() {
        if ($("#copy_head_address").is(":checked")) {
            $('.b_area').val($('.h_area').val());
            $("input[name='b_landmark']").val($("input[name='h_landmark']").val());
            $("input[name='b_country']").val($("input[name='h_country']").val());
            $("input[name='b_city']").val($("input[name='h_city']").val());
            $("input[name='b_state']").val($("input[name='h_state']").val());
            $("input[name='b_pin_code']").val($("input[name='h_pin_code']").val());
        } else {
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
$(function() {
    $("#copy_shipping_address").click(function() {
        if ($("#copy_shipping_address").is(":checked")) {
            $('.s_area').val($('.b_area').val());
            $("input[name='s_landmark']").val($("input[name='b_landmark']").val());
            $("input[name='s_country']").val($("input[name='b_country']").val());
            $("input[name='s_city']").val($("input[name='b_city']").val());
            $("input[name='s_state']").val($("input[name='b_state']").val());
            $("input[name='s_pin_code']").val($("input[name='b_pin_code']").val());
        } else {
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
$(function() {
    $(document).on('click', ".copy_depot_address", function() {
        var name_data = $(this).attr('name');
        var id_name = $(this).attr('cus');
        var ischecked = $(this).is(':checked');
        if (ischecked) {
            $("textarea[name='create_depot_address" + id_name + "[d_area]']").val($('.s_area').val());
            $("input[name='create_depot_address" + id_name + "[d_landmark]']").val($(
                "input[name='s_landmark']").val());
            $("input[name='create_depot_address" + id_name + "[d_country]']").val($(
                "input[name='s_country']").val());
            $("input[name='create_depot_address" + id_name + "[d_city]']").val($("input[name='s_city']")
                .val());
            $("input[name='create_depot_address" + id_name + "[d_state]']").val($(
                "input[name='s_state']").val());
            $("input[name='create_depot_address" + id_name + "[d_pin_code]']").val($(
                "input[name='s_pin_code']").val());
        } else {
            if (!ischecked) {
                //   alert(id_name);
                $("textarea[name='create_depot_address" + id_name + "[d_area]']").val('');
                $("input[name='create_depot_address" + id_name + "[d_landmark]']").val('');
                $("input[name='create_depot_address" + id_name + "[d_country]']").val('');
                $("input[name='create_depot_address" + id_name + "[d_city]']").val('');
                $("input[name='create_depot_address" + id_name + "[d_state]']").val('');
                $("input[name='create_depot_address" + id_name + "[d_pin_code]']").val('');
            }
        }
    });
});
</script>
<script>
$(function() {

    $(document).on('click', '.order_search', function() {
        $('#loader').show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formulario = $(".item-form-apply");
        $.ajax({
            url: "{{ url('admin/purchase_net_rate_search') }}",
            type: "POST",
            data: formulario.serialize(),
            success: function(response) {
                //alert(response); return false;

                if (response != '') {
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
                        click: function(options, refresh, $target) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $(
                                            'meta[name="csrf-token"]')
                                        .attr('content')
                                }
                            });
                            var vendor_sku = $("#vendor_sku").val();
                            var name = $("#name").val();
                            var synonyms = $("#synonyms").val();
                            var grade = $("#grade").val();
                            var brand = $("#brand").val();
                            var packing_name = $("#packing_name").val();
                            var hsn_code = $("#hsn_code").val();
                            if ($('input[name="is_verified"]').is(':checked')) {
                                var is_verified = $('input[name="is_verified"]')
                                    .val();
                            } else {
                                var is_verified = '';
                            }

                            $.ajax({
                                url: "{{ url('admin/purchase_net_rate_search') }}",
                                type: "POST",
                                data: {
                                    page: options.current,
                                    brand: brand,
                                    vendor_sku: vendor_sku,
                                    name: name,
                                    synonyms: synonyms,
                                    grade: grade,
                                    packing_name: packing_name,
                                    hsn_code: hsn_code,
                                    is_verified: is_verified
                                },
                                success: function(response) {
                                    //alert(response); return false;
                                    if (response != '') {
                                        var returnedData = JSON
                                            .parse(response);
                                        $('#data-table-result')
                                            .html(returnedData
                                                .html);
                                    }
                                }
                            });
                        }
                    });
                    //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                    $('.btn-apply').show();
                } else {
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
$(function() {
    $(document).on('click', '#reload', function() {
        //alert();
        $('#reloadform')[0].reset();
        $('#example-2').pagination({
            total: {
                {
                    $itemlist_count
                }
            },
            current: 1,
            length: 10,
            size: 2,
            prev: 'Previous',
            next: 'Next',
            click: function(options, refresh, $target) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ url('admin/purchase_net_rate_search') }}",
                    type: "POST",
                    data: {
                        page: options.current
                    },
                    success: function(response) {
                        //alert(response); return false;
                        if (response != '') {
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
    total: {
        {
            $itemlist_count
        }
    },
    current: 1,
    length: 10,
    size: 2,
    prev: 'Previous',
    next: 'Next',
    click: function(options, refresh, $target) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ url('admin/purchase_net_rate_search') }}",
            type: "POST",
            data: {
                page: options.current
            },
            success: function(response) {
                //alert(response); return false;
                if (response != '') {
                    var returnedData = JSON.parse(response);
                    $('#data-table-result').html(returnedData.html);
                }
            }
        });
    }
});
</script>
<script>
$(function() {
    $(document).on('click', '.search-pop-up', function() {
        var name_id = $(this).attr('cus');
        //alert(name_id);
        $('#item-name-id').val(name_id);
    });
});
</script>
<script>
//$(".item_data").attr('readonly',true);
$(document).ready(function() {
    $(".btn-apply").click(function() {
        var radioValue = $("input[name='apply-radio']:checked").val();
        var id = $("input[name='apply-radio']:checked").attr('id');
        //alert(radioValue); die;
        var name_id = $('#item-name-id').val();
        var result_id = name_id.replace("[create_customer_item]", "[item_id]");
        //alert(result_id);
        $("input[name='" + name_id + "']").val(radioValue);
        $("input[name='" + result_id + "']").val(id);
    });
});
</script>


@endsection