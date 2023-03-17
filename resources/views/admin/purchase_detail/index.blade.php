@extends('layouts.admin')
@section('header', 'Purchase_Detail')

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

    .table-purchase tbody tr:last-child {
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
            <div class="card-header with-border">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $suppliers->name }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $suppliers->phone_number }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $suppliers->address }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-body">

                <form class="form-item">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <div class="input-group">
                                <input type="hidden" name="purchase_id" id="purchase_id" value="{{ $purchase_id }}">
                                <input type="hidden" name="id" id="id">
                                <span class="input-group-btn">
                                    <button onclick="showItem()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right">Select Item</i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-purchase">
                    <thead>
                        <th width="5%">No</th>
                        <th>Purchase ID</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th width="15%">Qty</th>
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
                        <form action="{{ route('purchases.store') }}" class="form-purchase" method="post">
                            @csrf
                            <input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="payment" id="payment">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-4 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-4 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $diskon }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="payment" class="col-lg-4 control-label">Payment</label>
                                <div class="col-lg-8">
                                    <input type="text" id="pay" class="form-control">
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


@includeIf('admin.purchase_detail.item')
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

        table = $('.table-purchase').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('purchase_detail.data', $purchase_id) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'id'},
                {data: 'item_name'},
                {data: 'buy_price'},
                {data: 'qty'},
                {data: 'subtotal'},
                {data: 'action', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
            
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
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

            $.post(`{{ url('/purchases_details') }}/${id}`, {
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
                    alert('tidak dapat menyimpan data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            loadForm($(this).val());
        });

        $('.btn-save').on('click', function () {
            $('.form-purchase').submit();
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
        $.post('{{ route('purchases_details.store') }}', $('.form-item').serialize())
        .done(response => {
            $('#id').focus();
            table.ajax.reload(() => loadForm($('#diskon').val()));
        })
        .fail(errors => {
            alert('Tidak dapat menyimpan data');
            return;
        });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail((errors) => {
                alert('Tidak dapat menghapus data');
                return;
            });
        }
    }

    function loadForm(diskon = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/purchases_details/loadform') }}/${diskon}/${$('.total').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#pay').val('Rp. '+ response.pay);
                $('#payment').val(response.payment);
                $('.show-payment').text('Rp. '+ response.pay);
                $('.show-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                alert('Cannot show the data');
                return;
            })
    }
</script>
@endsection