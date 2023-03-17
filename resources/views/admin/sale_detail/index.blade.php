@extends('layouts.admin')
@section('header', 'Sale Transaction')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .show-payment {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .show-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-sale tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .show-payment {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">           
            <div class="card-body">
                <form class="form-item">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="input-group">
                                <input type="hidden" name="sale_id" id="sale_id" value="{{ $sale_id }}">
                                <input type="hidden" name="id" id="id">
                                <span class="input-group-btn">
                                    <button onclick="showItem()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right">Select Item</i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-sale">
                    <thead>
                        <th width="5%">No</th>
                        <th>Sale ID</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th width="15%">Qty</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                        <th width="15%">Action</th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="show-payment bg-primary"></div>
                        <div class="show-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transaction.save') }}" class="form-sale" method="post">
                            @csrf
                            <input type="hidden" name="sale_id" value="{{ $sale_id }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="payment" id="payment">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ $customerSelected->customer_id }}">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-4 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-lg-4 control-label">Customer</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="name" value="{{ $customerSelected->name }}">
                                        <span class="input-group-btn">
                                            <button onclick="showCustomer()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-4 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $diskon ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="payment" class="col-lg-4 control-label">Payment</label>
                                <div class="col-lg-8">
                                    <input type="text" id="pay" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="receive" class="col-lg-4 control-label">Receive</label>
                                <div class="col-lg-8">
                                    <input type="text" id="receive" name="receive" class="form-control" value="{{ $sale->receive ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="change" class="col-lg-4 control-label">Change</label>
                                <div class="col-lg-8">
                                    <input type="text" id="change" name="change" value="0" class="form-control" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-save"><i class="fa fa-floppy-o"></i> Save Transaction</button>
            </div>
        </div>
    </div>
</div>


@includeIf('admin.sale_detail.item')
@includeIf('admin.sale_detail.customer')
@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<!-- <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> -->
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-sale').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaction.data', $sale_id) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'id'},
                {data: 'item_name'},
                {data: 'sell_price'},
                {data: 'qty'},
                {data: 'diskon'},
                {data: 'subtotal'},
                {data: 'action', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
            
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
            setTimeout(() => {
                $('#receive').trigger('input');
            }, 300);
        });
        table2 = $('.table-item').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let qty = parseInt($(this).val());

            if (qty < 1) {
                $(this).val(1);
                alert('Quantity cannot be less than 1');
                return;
            }
            if (qty > 1000) {
                $(this).val(1000);
                alert('Quantity cannot over 1000');
                return;
            }

            $.post(`{{ url('/transaction') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'qty': qty
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })
                .fail(errors => {
                    alert('Cannot save the data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            loadForm($(this).val());
        });

        $('#receive').on('input', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            loadForm($('#diskon').val(), $(this).val());
        }).focus(function() {
            $(this).select();
        });

        $('.btn-save').on('click', function () {
            $('.form-sale').submit();
        });
    });

    function showItem() {
        $('#modal-item').modal('show');
    }

    function hideItem() {
        $('#modal-item').modal('hide');
    }

    function selectItem(id) {
        $('#id').val(id);
        hideItem();
        addItem();
    }

    function addItem() {
        $.post('{{ route('transaction.store') }}', $('.form-item').serialize())
        .done(response => {
            $('#id').focus();
            table.ajax.reload(() => loadForm($('#diskon').val()));
        })
        .fail(errors => {
            alert('Cannot save the data');
            return;
        });
    }

    function showCustomer() {
        $('#modal-customer').modal('show');
    }

    function selectCustomer(id, name) {
        $('#customer_id').val(id);
        $('#name').val(name);
        loadForm($('#diskon').val());
        $('#receive').val(0).focus().select();
        hideCustomer();
    }

    function hideCustomer() {
        $('#modal-customer').modal('hide');
    }

    function deleteData(url) {
        if (confirm('Are you sure?')) {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail((errors) => {
                alert('Cannot delete the data');
                return;
            });
        }
    }

    function loadForm(diskon = 0, receive = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaction/loadform') }}/${diskon}/${$('.total').text()}/${receive}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#pay').val('Rp. '+ response.pay);
                $('#payment').val(response.payment);
                $('.show-payment').text('Cost: Rp. '+ response.pay);
                $('.show-terbilang').text(response.terbilang);

                $('#change').val('Rp.'+ response.changerp);
                if ($('#receive').val() != 0) {
                    $('.show-payment').text('Change: Rp. '+ response.changerp);
                    $('.show-terbilang').text(response.change_terbilang);
                }
            })
            .fail(errors => {
                alert('Cannot show the data');
                return;
            })
    }
</script>
@endsection