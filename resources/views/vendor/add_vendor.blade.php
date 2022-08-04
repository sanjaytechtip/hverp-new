@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Vendor')
@section('pagecontent')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page formbuilder">
    <div class="page-header">
        <h1 class="page-title">Add Vendor</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vendorlist') }}">Vendor : List</a></li>
            <li class="breadcrumb-item">Add Vendor</li>
        </ol>
    </div>

    <div class="page-content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body_rename card-body">
                        <h2 class="form-box form-box-heading">Vendors</h2>
                        <div class="custom-form-box">
                            <form class="custom-form" action="{{url('admin/add-vendor-data')}}" id="vendors"
                                method="POST" enctype="multipart/form-data" novalidate="novalidate">
                                @csrf
                                <input type="hidden" name="table_name" value="vendors">
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
                                <div class="form-box form-box-heading">Depot Address</div>
                                <div class="form-box rows  line_repeat_1" id="rep-0">
                                    <div class="form-box col1">
                                        <div class="inline-radio-box inline-radio"><label><input type="checkbox"
                                                    cus="[0]" value="1" class=" create_depot_address copy_depot_address"
                                                    id="" name="create_depot_address[0][create_depot_address]">Same as
                                                Shipping Address</label></div>
                                    </div>
                                    <div class="form-box col1"><label for="d_area">Area</label><textarea
                                            name="create_depot_address[0][d_area]" class="form-control d_area " id=""
                                            placeholder="Area"></textarea></div>
                                    <div class="form-box col1 d_landmark"><label for="d_landmark">Landmark</label><input
                                            autocomplete="off" type="text" name="create_depot_address[0][d_landmark]"
                                            class="d_landmark-[0] form-control d_landmark " id="" placeholder="Landmark"
                                            value="" maxlength="50" minlength="1"></div>
                                    <div class="form-box col2 d_country"><label for="d_country">Country</label><input
                                            autocomplete="off" type="text" name="create_depot_address[0][d_country]"
                                            class="d_country-[0] form-control d_country " id="" placeholder="Country"
                                            value="" maxlength="50" minlength="1"></div>
                                    <div class="form-box col2 d_city"><label for="d_city">City</label><input
                                            autocomplete="off" type="text" name="create_depot_address[0][d_city]"
                                            class="d_city-[0] form-control d_city " id="" placeholder="City" value=""
                                            maxlength="50" minlength="1"></div>
                                    <div class="form-box col2 d_state"><label for="d_state">State</label><input
                                            autocomplete="off" type="text" name="create_depot_address[0][d_state]"
                                            class="d_state-[0] form-control d_state " id="" placeholder="State" value=""
                                            maxlength="50" minlength="1"></div>
                                    <div class="form-box col2 d_pin_code"><label for="d_pin_code">Pin Code</label><input
                                            autocomplete="off" min="0" type="number"
                                            name="create_depot_address[0][d_pin_code]"
                                            class="d_pin_code-[0] form-control d_pin_code " id="" placeholder="Pin Code"
                                            value="" maxlength="6" minlength="6"></div>
                                </div>
                                <div class="form-box add-more" style="float:right; margin-top:10px;"><a
                                        id="create_depot_address" href="javascript:void(0);">+ Add More</a>
                                    <script>
                                    $(function() {
                                        var j = $(".line_repeat_1").length;
                                        $(document).on("click", "#create_depot_address", function() {
                                            j++;
                                            var create_depot_address =
                                                '<div class="form-box rep-div-add line_repeat_1" id="rep-__id__"><div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div><div class="form-box col1"><div class="inline-radio-box inline-radio"><label><input type="checkbox" cus="[0]" value="1" class=" create_depot_address copy_depot_address" id="" name="create_depot_address[0][create_depot_address]">Same as Shipping Address</label></div></div><div class="form-box col1"><label for="d_area">Area</label><textarea name="create_depot_address[0][d_area]" class="form-control d_area " id="" placeholder="Area"></textarea></div><div class="form-box col1 d_landmark"><label for="d_landmark">Landmark</label><input autocomplete="off" type="text" name="create_depot_address[0][d_landmark]" class="d_landmark-[0] form-control d_landmark " id="" placeholder="Landmark" value="" maxlength="50" minlength="1"></div><div class="form-box col2 d_country"><label for="d_country">Country</label><input autocomplete="off" type="text" name="create_depot_address[0][d_country]" class="d_country-[0] form-control d_country " id="" placeholder="Country" value="" maxlength="50" minlength="1"></div><div class="form-box col2 d_city"><label for="d_city">City</label><input autocomplete="off" type="text" name="create_depot_address[0][d_city]" class="d_city-[0] form-control d_city " id="" placeholder="City" value="" maxlength="50" minlength="1"></div><div class="form-box col2 d_state"><label for="d_state">State</label><input autocomplete="off" type="text" name="create_depot_address[0][d_state]" class="d_state-[0] form-control d_state " id="" placeholder="State" value="" maxlength="50" minlength="1"></div><div class="form-box col2 d_pin_code"><label for="d_pin_code">Pin Code</label><input autocomplete="off" min=0 type="number" name="create_depot_address[0][d_pin_code]" class="d_pin_code-[0] form-control d_pin_code " id="" placeholder="Pin Code" value="" maxlength="6" minlength="6"></div></div>';
                                            var last_data = create_depot_address.replaceAll("##", j)
                                                .replaceAll("__D__", j).replaceAll("__id__", j)
                                                .replaceAll("[0]", "[" + j + "]");
                                            $(this).parent().before(last_data);
                                        });
                                    });
                                    </script>
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
                                <div class="form-box form-box-heading">Minimum value for FOR</div>
                                <div class="form-box rows  line_repeat_1" id="rep-0">
                                    <div class="form-box col1 create_customer_brand"><label
                                            for="create_customer_brand">Brand</label>
                                    </div>
                                    {!!
                                    get_brand($data=array('field_id'=>'search','field_name'=>'create_customer_item_sap[0][create_customer_brand]'),$edit_data=array(),'create_customer_item_sap[0][create_customer_brand]')
                                    !!}
                                    <div class="form-box col1 create_customer_price"><label
                                            for="create_customer_price">Price</label><input autocomplete="off" min="0"
                                            type="number" name="create_customer_item_sap[0][create_customer_price]"
                                            class="create_customer_price-[0] form-control create_customer_price " id=""
                                            placeholder="" value=""></div>
                                </div>
                                <div class="form-box add-more" style="float:right; margin-top:10px;"><a
                                        id="create_customer_item_sap" href="javascript:void(0);">+ Add More</a>
                                    <script>
                                    $(function() {
                                        var j = $(".line_repeat_1").length;
                                        $(document).on("click", "#create_customer_item_sap", function() {
                                            j++;
                                            var create_customer_item_sap =
                                                '<div class="form-box rep-div-add line_repeat_1" id="rep-__id__"><div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div><div class="form-box col1 create_customer_brand"><label for="create_customer_brand">Brand</label><select class="form-control " data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="create_customer_item_sap[0][create_customer_brand]"><option value="">--Select Brand--</option><option value="Alfa Aesar">Alfa Aesar</option><option value="Spectrochem">Spectrochem</option><option value="FMS">FMS</option><option value="Sigma">Sigma</option><option value="Merck (M)">Merck (M)</option><option value="Loba">Loba</option><option value="General">General</option><option value="JSGW">JSGW</option><option value="Rankem">Rankem</option><option value="Fisher">Fisher</option><option value="Hi Media (C)">Hi Media (C)</option><option value="S.Dfine">S.Dfine</option><option value="Merck E">Merck E</option><option value="Finar">Finar</option><option value="Hi Media (TC)">Hi Media (TC)</option><option value="Commercial">Commercial</option><option value="SIGMA VETEC">SIGMA VETEC</option><option value="Merck">Merck</option><option value="SRL">SRL</option><option value="Merck Germany">Merck Germany</option><option value="TCI">TCI</option><option value="CDH">CDH</option><option value="Thomas Baker">Thomas Baker</option><option value="Biosolve">Biosolve</option><option value="Fluka Hw">Fluka Hw</option><option value="Hi Media (M)">Hi Media (M)</option><option value="Eppendorf">Eppendorf</option><option value="Genaxy">Genaxy</option><option value="Molychem">Molychem</option><option value="Genetix">Genetix</option><option value="Hi Media (D)">Hi Media (D)</option><option value="Perkin">Perkin</option><option value="Eurofins">Eurofins</option><option value="OTTO Chemie">OTTO Chemie</option><option value="Medisafe">Medisafe</option><option value="Invitrogen">Invitrogen</option><option value="GE Healthcare">GE Healthcare</option><option value="MP Biomedics">MP Biomedics</option><option value="Novus">Novus</option><option value="Microgen">Microgen</option><option value="Critiqual">Critiqual</option><option value="Meril">Meril</option><option value="ASPIRON/Meril">ASPIRON/Meril</option><option value="Dettol">Dettol</option><option value="Cole Parmer">Cole Parmer</option><option value="Fermentas">Fermentas</option><option value="Raman&Weil">Raman&Weil</option><option value="Hi Media ( M )">Hi Media ( M )</option><option value="Hi Media (P)">Hi Media (P)</option><option value="Hi Media (RP)">Hi Media (RP)</option><option value="Eutech">Eutech</option><option value="Qiagen">Qiagen</option><option value="Reagecon">Reagecon</option><option value="Hi Media(K)">Hi Media(K)</option><option value="Genei">Genei</option><option value="Transasia">Transasia</option><option value="Thermo">Thermo</option><option value="Acco">Acco</option><option value="Hi Media (I)">Hi Media (I)</option><option value="Dr. Ehrenstorfer">Dr. Ehrenstorfer</option><option value="Smobio">Smobio</option><option value="Promega">Promega</option><option value="LTA">LTA</option><option value="Serva Electrophoresis">Serva Electrophoresis</option><option value="Excelsior">Excelsior</option><option value="Brook field">Brook field</option><option value="Millipore">Millipore</option><option value="Mesalab">Mesalab</option><option value="Axygen">Axygen</option><option value="Tarson">Tarson</option><option value="Sony">Sony</option><option value="Abdos">Abdos</option><option value="Corning">Corning</option><option value="Biomatrix">Biomatrix</option><option value="Remi">Remi</option><option value="NEST">NEST</option><option value="Nulife">Nulife</option><option value="Dial">Dial</option><option value="WNCare">WNCare</option><option value="Ansell">Ansell</option><option value="Bonric,Malaysia">Bonric,Malaysia</option><option value="Rups Lifescience">Rups Lifescience</option><option value="Getinge">Getinge</option><option value="Whatman(F)">Whatman(F)</option><option value="Sartorius">Sartorius</option><option value="Cyber">Cyber</option><option value="Merck (G)">Merck (G)</option><option value="Perfit">Perfit</option><option value="Rankem (G)">Rankem (G)</option><option value="Borosil">Borosil</option><option value="Duran (R)">Duran (R)</option><option value="Olympus">Olympus</option><option value="Magnus">Magnus</option><option value="Riviera">Riviera</option><option value="Biorad">Biorad</option><option value="Equitron">Equitron</option><option value="Popular">Popular</option><option value="Water">Water</option><option value="Aqua Lung">Aqua Lung</option><option value="Obromax">Obromax</option><option value="Brand">Brand</option><option value="Rocker">Rocker</option><option value="Narang">Narang</option><option value="Blue star">Blue star</option><option value="Samsung">Samsung</option><option value="LG">LG</option><option value="Ferrotek">Ferrotek</option><option value="Celfrost">Celfrost</option><option value="Aakar">Aakar</option><option value="Nuaire">Nuaire</option><option value="Venus">Venus</option><option value="ANM">ANM</option><option value="pHcbi">pHcbi</option><option value="Velp">Velp</option><option value="Newtronics">Newtronics</option><option value="Jayant">Jayant</option><option value="Ambassador">Ambassador</option><option value="Zenpure (Saint Gobin) USA">Zenpure (Saint Gobin) USA</option><option value="Qualigens">Qualigens</option><option value="ANTECH">ANTECH</option><option value="Shimadzu">Shimadzu</option><option value="METTLER">METTLER</option><option value="SHI-AM">SHI-AM</option><option value="ACZET">ACZET</option><option value="JIEOTECH">JIEOTECH</option><option value="Hoefer">Hoefer</option><option value="PFACT">PFACT</option><option value="IKA">IKA</option><option value="Kipp & Zonen">Kipp & Zonen</option><option value="Labnet">Labnet</option><option value="DBK">DBK</option><option value="Hwashin">Hwashin</option><option value="Nabertherm, Germany">Nabertherm, Germany</option><option value="Ion Exchange">Ion Exchange</option><option value="OHAUS">OHAUS</option><option value="Advance Research">Advance Research</option><option value="Mitutoyo">Mitutoyo</option><option value="Darwin">Darwin</option><option value="International Equipments">International Equipments</option><option value="Burst">Burst</option><option value="Zeal">Zeal</option><option value="Haier">Haier</option><option value="LUTRON">LUTRON</option><option value="HTC">HTC</option><option value="Extech">Extech</option><option value="ETCL">ETCL</option><option value="AQUASOL">AQUASOL</option><option value="Fisher Scientific">Fisher Scientific</option><option value="Hamilton">Hamilton</option><option value="Aquaread">Aquaread</option><option value="Thermo Scientific">Thermo Scientific</option><option value="ESI">ESI</option><option value="Systronics">Systronics</option><option value="Toshcon">Toshcon</option><option value="Elcotest">Elcotest</option><option value="Biotek">Biotek</option><option value="Cleaver">Cleaver</option><option value="Atago">Atago</option><option value="WTW">WTW</option><option value="Waters">Waters</option><option value="New Brunswick">New Brunswick</option><option value="Spectrum">Spectrum</option><option value="Testo">Testo</option><option value="Interscience">Interscience</option><option value="ORION">ORION</option><option value="PCI">PCI</option><option value="Hayman">Hayman</option><option value="Indosaw">Indosaw</option><option value="Vijayanta">Vijayanta</option><option value="Agilent">Agilent</option><option value="Prerana">Prerana</option><option value="Bioshields">Bioshields</option><option value="Xcelris">Xcelris</option><option value="Himedia">Himedia</option><option value="Medica">Medica</option><option value="Merck (Std.)">Merck (Std.)</option><option value="Whatman(S)">Whatman(S)</option><option value="Merck (Pharm)">Merck (Pharm)</option><option value="Supelco">Supelco</option><option value="Calbiochem">Calbiochem</option><option value="Honeywell">Honeywell</option><option value="Across">Across</option><option value="Huwa San">Huwa San</option><option value="Jt baker">Jt baker</option><option value="Riedel-de Haen Hw">Riedel-de Haen Hw</option><option value="True Indicating">True Indicating</option><option value="Aldrich">Aldrich</option><option value="Advent">Advent</option><option value="RCP">RCP</option><option value="Hi Media(S)">Hi Media(S)</option><option value="Avarice">Avarice</option><option value="Novabiochem">Novabiochem</option><option value="LGC">LGC</option><option value="Acros Organics">Acros Organics</option><option value="ABI">ABI</option><option value="Merck (B)">Merck (B)</option><option value="J Mitra">J Mitra</option><option value="Peprotech">Peprotech</option><option value="Brand2">Brand2</option><option value="test">test</option><option value="Primus">Primus</option><option value="kimberly clark">kimberly clark</option><option value="Greiner bio-one">Greiner bio-one</option><option value="Super Wrap">Super Wrap</option><option value="Rankem (F)">Rankem (F)</option><option value="Parafilm">Parafilm</option><option value="Millipore (S)">Millipore (S)</option><option value="Getinge Sweden">Getinge Sweden</option><option value="Nice">Nice</option><option value="Matig White">Matig White</option><option value="Vensil">Vensil</option><option value="Nalgene">Nalgene</option><option value="Loba (G)">Loba (G)</option><option value="Pall">Pall</option><option value="Nichipet">Nichipet</option><option value="Himedia (S)">Himedia (S)</option><option value="Falcon">Falcon</option><option value="BDH">BDH</option><option value="Simco">Simco</option><option value="Fresh Wrapp">Fresh Wrapp</option><option value="Qualikems">Qualikems</option><option value="Weightronics">Weightronics</option><option value="Polylab">Polylab</option><option value="Steritec">Steritec</option><option value="Wako">Wako</option><option value="Gilson">Gilson</option><option value="NEB">NEB</option><option value="Bio Star">Bio Star</option><option value="Sial">Sial</option><option value="Fluka">Fluka</option><option value="Eldex">Eldex</option><option value="Imperial">Imperial</option><option value="BKC">BKC</option><option value="A&D">A&D</option><option value="Virtual">Virtual</option><option value="ERBA">ERBA</option><option value="Leimco">Leimco</option><option value="BrBiochem">BrBiochem</option><option value="HighQu">HighQu</option><option value="Accustandard">Accustandard</option><option value="AND">AND</option><option value="Denver">Denver</option><option value="Himedia (K)">Himedia (K)</option><option value="Rankem Kit">Rankem Kit</option><option value="Heraeus">Heraeus</option><option value="FT-Green">FT-Green</option><option value="Kjeltron">Kjeltron</option><option value="Miltenyi Biotech">Miltenyi Biotech</option><option value="LOSER">LOSER</option><option value="Metrohm">Metrohm</option><option value="Karnavati">Karnavati</option><option value="Akuret">Akuret</option><option value="Borosilicate">Borosilicate</option><option value="Rankem (P)">Rankem (P)</option><option value="Navyug">Navyug</option><option value="Coral">Coral</option><option value="Biobase">Biobase</option><option value="CLT">CLT</option><option value="HX">HX</option><option value="Inorganic Ventures">Inorganic Ventures</option><option value="Solarus">Solarus</option><option value="Brinsea">Brinsea</option><option value="Labtech">Labtech</option><option value="Laxar">Laxar</option><option value="Merck Kit">Merck Kit</option><option value="Oakton">Oakton</option><option value="Sonar">Sonar</option><option value="Axiva">Axiva</option><option value="Sintex">Sintex</option><option value="Veego">Veego</option><option value="Globuz">Globuz</option><option value="Cannon">Cannon</option><option value="Picco">Picco</option><option value="Arkray">Arkray</option><option value="Grant">Grant</option><option value="Nunc">Nunc</option><option value="CML">CML</option><option value="Medispec">Medispec</option><option value="Nikon">Nikon</option><option value="UVP">UVP</option><option value="Lovibond">Lovibond</option><option value="Garmins">Garmins</option><option value="Easy G">Easy G</option><option value="Spincotech">Spincotech</option><option value="Aura">Aura</option><option value="Rohem">Rohem</option><option value="Medsource">Medsource</option><option value="Rivotek">Rivotek</option><option value="Princeton">Princeton</option><option value="Kromasil">Kromasil</option><option value="Zorbax">Zorbax</option><option value="Vestfrost">Vestfrost</option><option value="Lab Guard">Lab Guard</option><option value="Progressive Instrument">Progressive Instrument</option><option value="Phenomenex">Phenomenex</option><option value="Beacon">Beacon</option><option value="Alere">Alere</option><option value="Analab">Analab</option><option value="TULIN">TULIN</option><option value="Thermo fisher">Thermo fisher</option><option value="JSM">JSM</option><option value="EBPI">EBPI</option><option value="STC">STC</option><option value="SGE">SGE</option><option value="Ocimum">Ocimum</option><option value="OYSTER">OYSTER</option><option value="Oasis">Oasis</option><option value="BTL">BTL</option><option value="Boai nky china">Boai nky china</option><option value="Span">Span</option><option value="Biolab">Biolab</option><option value="Doshiion">Doshiion</option><option value="Dynamicro">Dynamicro</option><option value="Saveer">Saveer</option><option value="Linux">Linux</option><option value="Yera">Yera</option><option value="BAUER">BAUER</option><option value="Alpine">Alpine</option><option value="Peerless">Peerless</option><option value="Numex">Numex</option><option value="Soxtron">Soxtron</option><option value="Dispovan">Dispovan</option><option value="HM">HM</option><option value="T.Baker">T.Baker</option><option value="Baxter">Baxter</option><option value="Metroark">Metroark</option><option value="Favorgen">Favorgen</option><option value="Kern">Kern</option><option value="Globus">Globus</option><option value="Top Syringe">Top Syringe</option><option value="3M">3M</option><option value="Imported">Imported</option><option value="Resol">Resol</option><option value="Toronto">Toronto</option><option value="Ozone">Ozone</option><option value="Esco">Esco</option><option value="Bayer">Bayer</option><option value="Unspecified">Unspecified</option><option value="EDVOTech">EDVOTech</option><option value="Dhona">Dhona</option><option value="Dewinter">Dewinter</option><option value="BIO-TECHNICS INDIA">BIO-TECHNICS INDIA</option><option value="Glassco">Glassco</option><option value="Biomerieux">Biomerieux</option><option value="Electronics India">Electronics India</option><option value="Cospheric">Cospheric</option><option value="Syrotech">Syrotech</option><option value="Bryair">Bryair</option><option value="Biotechno Lab">Biotechno Lab</option><option value="Cyanagen">Cyanagen</option><option value="Huber">Huber</option><option value="HANNA">HANNA</option><option value="Interster, Netherlands">Interster, Netherlands</option><option value="Getinge,USA">Getinge,USA</option><option value="Galaxy">Galaxy</option><option value="PackTest">PackTest</option><option value="Spectrolab">Spectrolab</option><option value="Labomed">Labomed</option><option value="Reliable">Reliable</option><option value="Elico">Elico</option><option value="Qarta-Bio">Qarta-Bio</option><option value="Immunolab Germany">Immunolab Germany</option><option value="Equisil">Equisil</option><option value="Genxbio">Genxbio</option><option value="Microflex">Microflex</option><option value="Germany">Germany</option><option value="YMC">YMC</option><option value="Luna">Luna</option><option value="CLS">CLS</option><option value="Jindal">Jindal</option><option value="GL Sciences Japan">GL Sciences Japan</option><option value="Kayo Research">Kayo Research</option><option value="Sonics USA">Sonics USA</option><option value="Medisca Pharmaceutique Inc">Medisca Pharmaceutique Inc</option><option value="Delux">Delux</option><option value="Parcol">Parcol</option><option value="Cargill Europe">Cargill Europe</option><option value="Hanatek">Hanatek</option><option value="Nupore">Nupore</option><option value="Musechem">Musechem</option><option value="Top Air Systems, USA">Top Air Systems, USA</option><option value="DLAB">DLAB</option><option value="Bajaj">Bajaj</option><option value="JLab">JLab</option><option value="Gloveon">Gloveon</option><option value="Paskem">Paskem</option><option value="Takara">Takara</option><option value="Nims">Nims</option><option value="Panreac">Panreac</option><option value="CID Bio-Science">CID Bio-Science</option><option value="Advantec">Advantec</option><option value="Neon">Neon</option><option value="Whirlpool">Whirlpool</option><option value="R-Biopharm">R-Biopharm</option><option value="Restek">Restek</option><option value="Presto">Presto</option><option value="Mikro">Mikro</option><option value="USE">USE</option><option value="Hyman">Hyman</option><option value="J&J">J&J</option><option value="Inertsil">Inertsil</option><option value="Anlatech">Anlatech</option><option value="Kashyap">Kashyap</option><option value="J">J</option><option value="Biometra">Biometra</option><option value="Ultralum">Ultralum</option><option value="Kartell">Kartell</option><option value="USA">USA</option><option value="Hichrom">Hichrom</option><option value="Sansui">Sansui</option><option value="Burrell">Burrell</option></select></div><div class="form-box col1 create_customer_price"><label for="create_customer_price">Price</label><input autocomplete="off" min=0 type="number" name="create_customer_item_sap[0][create_customer_price]" class="create_customer_price-[0] form-control create_customer_price " id="" placeholder="" value=""></div></div>';
                                            var last_data = create_customer_item_sap.replaceAll("##", j)
                                                .replaceAll("__D__", j).replaceAll("__id__", j)
                                                .replaceAll("[0]", "[" + j + "]");
                                            $(this).parent().before(last_data);
                                        });
                                    });
                                    </script>
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
                                <div class="form-box col1 brand"><label for="brand">Brand(s)<span class="required"
                                            aria-required="true">*</span></label></div>
                                {!!
                                get_brand($data=array('field_id'=>'search','field_name'=>'brand[]'),$edit_data=array(),'brand[]')
                                !!}
                                <div class="form-box col1 drug_license_number">
                                    <label for="drug_license_number">Drug License Number</label>
                                    <input autocomplete="off" type="text" name="drug_license_number"
                                        class="form-control drug_license_number " id=""
                                        placeholder="Drug License Number" value="" maxlength="20">
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
                                                    '<div class="form-box more-rep-div-add rep-div-add" id="rep-__id__"><div class="remove_row"><a href="javascript:void(0)" title="remove row" cus="__D__" class="delete-row"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div><div class="form-box col1 name"><label for="name">Name</label><input autocomplete="off" type="text" name="create_departments[0][departments_repeat][0][name]" class="form-control name " id="" placeholder="" value="" maxlength="50"></div><div class="form-box col1 designation"><label for="designation">Designation</label><select class="form-control" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1" name="create_departments[0][departments_repeat][0][designation]"><option value="">--Select Designation--</option>@foreach($designations as $designation) <option value="{{$designation['id']}}">{{$designation['designation']}} </option> @endforeach</select></div><div class="form-box col1 mobile_no"><label for="mobile_no">Mobile No</label><input autocomplete="off" min=0 type="number" name="create_departments[0][departments_repeat][0][mobile_no]" class="form-control mobile_no " id="" placeholder="" value="" maxlength="10" minlength="10"></div><div class="form-box col1 email"> <label for="email">Email</label> <input autocomplete="off" type="text" name="create_departments[0][departments_repeat][0][email]" class="form-control email" id="" placeholder="" value=""> </div><div class="form-box col1 whatsapp_no"><label for="whatsapp_no">Whatsapp No</label><input autocomplete="off" min=0 type="number" name="create_departments[0][departments_repeat][0][whatsapp_no]" class="form-control whatsapp_no " id="" placeholder="" value="" maxlength="10" minlength="10"></div></div>';
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


    $("#vendors").validate({

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