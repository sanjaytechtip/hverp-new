@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Quotation List')
@section('pagecontent')

<div class="page">
    <div class="new-width-design new-new-width-design">
        <div class="new-design-inner">
            <div class="new-header-div-fot-btn search-design-data">
                <div class="page-header">
                    <h1 class="page-title">Quotation List</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Quotation List</li>
                    </ol>
                </div>
                <div class="payment-heading-box1">
                    <form method="POST" action="{{url('admin/quotation-list')}}" class="custom-inline-form">
                        @csrf
                        <div class="form-box">
                            <input class="form-control" type="text" name="quotation_no"
                                value="{{$request->quotation_no}}" autocomplete="off" placeholder="Qu. No">
                        </div>
                        <div class="form-box">
                            <input class="form-control" type="text" name="quotation_priority"
                                value="{{$request->quotation_priority}}" autocomplete="off" placeholder="Qu. Priority">
                        </div>
                        <div class="form-box">
                            <input class="form-control" type="text" id="quotation_date" name="quotation_date"
                                value="{{$request->quotation_date}}" autocomplete="off" placeholder="Qu. Date">
                        </div>
                        <div class="form-box">
                            <input class="form-control" type="text" name="quotation_ref_no"
                                value="{{$request->quotation_ref_no}}" autocomplete="off" placeholder="Qu. Ref No.">
                        </div>
                        <div class="form-box">
                            <select class="required form-control" id="created_by" name="created_by"
                                data-plugin="select2" data-placeholder="Created By" data-minimum-input-length="1">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                        <div class="form-box">
                            <select class="required form-control" id="approved_by" name="approved_by"
                                data-plugin="select2" data-placeholder="Approved By" data-minimum-input-length="1">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                        <div class="form-box">
                            <select class="required form-control" id="customer_name" name="customer_name"
                                data-plugin="select2" data-placeholder="Type Customer" data-minimum-input-length="1">
                                <option value="">-- Select --</option>
                            </select>
                        </div>
                        <div class="form-box">
                            <input class="form-control" type="text" name="city" value="{{$request->city}}"
                                autocomplete="off" placeholder="City">
                        </div>
                        <div class="form-box">
                            <select class="required form-control" name="quotation_status" data-placeholder="Status"
                                data-minimum-input-length="1">
                                <option value="">-- Select --</option>
                                <option @if($request->quotation_status=='Saved') selected @endif value="Saved">Saved
                                </option>
                                <option @if($request->quotation_status=='Pending Approval') selected @endif
                                    value="Pending Approval">Pending Approval</option>
                                <option @if($request->quotation_status=='Approved') selected @endif
                                    value="Approved">Approved</option>
                            </select>
                        </div>
                        <div class="form-box form-box-submit"><button class="btn btn-primary waves-effect waves-classic"
                                type="submit" name="order_search">Search</button></div>
                        <div class="form-box form-box-submit"><a href="{{route('quotation_list')}}"
                                class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i
                                    class="icon md-refresh" aria-hidden="true"></i></a></div>
                        <div class="form-box  form-box-submit">
                            <a class="btn btn-primary waves-effect waves-classic" style="float:right;"
                                href="{{ route('quotationcreate') }}"> <i class="icon md-plus" aria-hidden="true"></i>
                                Add</a>
                        </div>
                </div>
                </form>
            </div>
            <div class="page-content">
                <div class="row"> @if (\Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <p>{{ \Session::get('success') }}</p>
                        </div>
                    </div>
                    @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model"
                        id="invoice_btn"></a>
                </div>
                <div class="panel">
                    <div class="panel-body"> @if(!empty($quotationlist))
                        <table class="table table-bordered t1 custom-lineheight-table">
                            <thead>
                                <th class="bg-info sort ta" id="ticket_number" style="color:#fff !important;">Qu. No</th>
                                <th class="bg-info sort ta" id="location" style="color:#fff !important;">Priority</th>
                                <th class="bg-info sort ta" id="department" style="color:#fff !important;">Customer</th>
                                <th class="bg-info sort ta" id="department" style="color:#fff !important;">City</th>
                                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Qu. Date</th>
                                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Ref No.</th>
                                <th class="bg-info sort ta" id="GrandTotal" style="color:#fff !important;">Grand Total</th>
                                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Created By</th>
                                <th class="bg-info sort ta" id="approvedBy" style="color:#fff !important;">Approved By</th>
                                <th class="bg-info sort ta" id="customer" style="color:#fff !important;">Status</th>
                                <th class="bg-info" style="color:#fff !important;">Action</th>
                            </thead>
                            <tbody id="fb_table_body">
                                @php
                                $i=($quotationlist->currentpage()-1)* $quotationlist->perpage();
                                $user_data = getAllusersDataList();
                                $cus_data = getAllBuyerData();
                                $cus_data_city = getAllBuyerDataCity();
                                //pr($cus_data); die;
                                @endphp
                                @foreach($quotationlist as $data)
                                @php
                                $arr = "";
                                if(getRevisedOrder($data->id)>0)
                                {
                                    
                                        $arr .='R'.getRevisedOrder($data->id);
                                   
                                }
                                @endphp
                                <tr>
                                    <td class="white-space-normal custom-white-space-normal">{{ $data->quotation_no }}@if(getRevisedOrder($data->id)>0)-{{$arr}} @endif</td>
                                    <td> {{ucfirst($data->quotation_priority)}}</td>
                                    <td class="white-space-normal custom-white-space-normal">
                                        {{ $cus_data[$data->customer_name] }} </td>
                                    <td> @if($cus_data_city[$data->customer_name]['city']!='NULL')
                                        {{ $cus_data_city[$data->customer_name]['city'] }} @endif</td>
                                    <td> {{ globalDateformatNet($data->quotation_date)}} </td>
                                    <td> {{ $data->quotation_ref_no}} </td>
                                    <td> {{ $data->quotation_grand_total}}</td>
                                    <td> {{ $user_data[$data->created_by] }}</td>
                                    <td> {{ $user_data[$data->approved_by] }}</td>
                                    <td> {{ $data->quotation_status}} </td>
                                    <td style="text-align:center;" class="action">
                                        <div class="action-wrap">
                                            <a title="Edit" href="{{url('admin/quotation-edit/'.$data->id)}}"
                                                class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i
                                                    class="icon md-edit" aria-hidden="true"></i></a>
                                            <a title="View" href="{{url('admin/quotation-view/'.$data->id)}}"
                                                class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i
                                                    class="icon md-eye" aria-hidden="true"></i></a>
                                            <a title="Print" target="_blank"
                                                href="{{url('admin/quotation-print/'.$data->id)}}"
                                                class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i
                                                    class="icon md-print" aria-hidden="true"></i></a>
                                            <a title="PDF Download" href="{{url('admin/quotation-pdf/'.$data->id)}}"
                                                class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic"><i
                                                    class="icon md-collection-pdf" aria-hidden="true"></i></a>
                                                    <span id="del_{{$data->id}}">
                                                <a title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete?');"
                                                    href="{{url('admin/delete_quotation/'.$data->id)}}"
                                                    class="btn btn-pure btn-danger icon waves-effect waves-classic"><i
                                                        class="icon md-delete" aria-hidden="true"></i></a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @php $i++ @endphp



                                @endforeach



                                @else
                                <tr>
                                    <td colspan="8">Result not found.</td>
                                </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                    <div class="mt-10">{!! $quotationlist->links('pagination::bootstrap-5') !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_validation_script')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>
<script>
$(document).ready(function() {
    $("input[type='radio']").click(function() {
        var radioValue = $("input[name='action_data']:checked").val();
        if (radioValue != '') {
            $('.action-data-show').hide();
            $('#action-' + radioValue).show();
        } else {
            $('.action-data-show').hide();
        }
    });
});
</script>
<script>
$(function() {

    var table = $('.t1');

    $('#ticket_number,#department,#customer,#subject,#date_time,#location')

        .wrapInner('<span title=""/>')

        .each(function() {





            var th = $(this),

                thIndex = th.index(),

                inverse = false;



            th.click(function() {

                //alert();

                table.find('td').filter(function() {



                    return $(this).index() === thIndex;



                }).sortElements(function(a, b) {



                    return $.text([a]) > $.text([b]) ?

                        inverse ? -1 : 1

                        :
                        inverse ? 1 : -1;



                }, function() {



                    // parentNode is the element we want to move

                    return this.parentNode;



                });



                inverse = !inverse;

                if (inverse == true) {

                    $('.ta').addClass('sort');

                    $('.ta').removeClass('dsc');

                    $('.ta').removeClass('asc');

                    $(this).addClass('asc');

                    $(this).removeClass('sort');

                    $(this).removeClass('dsc');

                } else if (inverse == false) {

                    $('.ta').addClass('sort');

                    $('.ta').removeClass('dsc');

                    $('.ta').removeClass('asc');

                    $(this).addClass('dsc');

                    $(this).removeClass('sort');

                    $(this).removeClass('asc');

                }



            });



        });

});
</script>
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script>
<script>
jQuery(document).ready(function() {

    jQuery('#quotation_date').datetimepicker({

        timepicker: false,

        format: 'd-m-Y',

        autoclose: true,

        minView: 2

    });

});
</script>
<script>
function insertIntoFms(Id) {
    var cf = confirm('Are you sure you want to insert this TASK into FMS?');
    if (!cf) {
        return false;
    } else {
        $('#loader').show();
        var formData = 'id=' + Id;
        var ajax_url = "{{ url('admin/ajax_insert_task_into_fms')}}";

        $.ajax({

            type: 'POST',

            url: ajax_url,

            data: formData,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(res) {
                //alert(res); return false;
                console.log(res); //return false;

                /* check if data alredy inserted */
                if (res == 'yes') {
                    alert('Data already inserted please check.');
                    location.reload();
                    return false;
                }
                var data = JSON.parse(res);
                $('#loader').hide();
                if (data.status) {
                    $('#del_' + Id).html('');

                    $('#pi_' + Id).html('');

                    $('#pi_' + Id).html(
                        '<a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>'
                        );
                }

                console.log(res);

            }

        });

    }
}
</script>
<script>
$(function() {
    $(document).on('click', '.update-net-rate', function() {
        var id = $(this).attr('id');
        var date_rate = $('.date_rate-' + id).val();
        var net_rate = $('.net_rate-' + id).val();
        var date_update = "{{date('d-m-Y')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (id != '' && date_rate != '' && net_rate != '') {
            $.ajax({
                url: "{{ url('admin/netrate_data_update') }}",
                type: "POST",
                data: {
                    id: id,
                    date_rate: date_rate,
                    net_rate: net_rate
                },
                success: function(response) {
                    // alert(response); return false;
                    if (response != '') {
                        $('#date-rate-' + id).html(date_rate);
                        $('#net-rate-' + id).html(net_rate);
                        $('.close-data').trigger('click');
                        $('#updated_on-' + id).html(date_update);
                    }
                }
            });
        }


    });
});
</script>
<script>
$(function() {
    var custArr = '<?php print_r(getAllBuyer()); ?>';
    custArr = JSON.parse(custArr);
    var custData = $.map(custArr, function(value, key) {
        return {
            value: value,
            data: key
        };
    });
    var custHtml = '<option value="">--Select--</option>';
    for (var i = 0; i < custData.length; i++) {
        custHtml += '<option value="' + custData[i].data + '">' + custData[i].value + '</option>';
    }
    $('#customer_name').html(custHtml);
    $('#customer_name').val('<?php echo $request->customer_name; ?>');
});
</script>

<script>
$(function() {
    var userArr = '<?php print_r(getAllusersData()); ?>';
    userArr = JSON.parse(userArr);
    var userData = $.map(userArr, function(value, key) {
        return {
            value: value,
            data: key
        };
    });
    var userHtml = '<option value="">--Select--</option>';
    for (var i = 0; i < userData.length; i++) {
        userHtml += '<option value="' + userData[i].data + '">' + userData[i].value + '</option>';
    }
    $('#created_by').html(userHtml);
    $('#created_by').val('<?php echo $request->created_by; ?>');
    $('#approved_by').html(userHtml);
    $('#approved_by').val('<?php echo $request->approved_by; ?>');
});
</script>

@endsection