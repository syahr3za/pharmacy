@extends('layouts.admin')
@section('header', 'Purchases List')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">
                <button onclick="addForm()" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus-circle"></i> New Purchase</button>
                @empty(! session('purchase_id'))
                <a href="{{ route('purchases_details.index') }}" class="btn btn-info btn-xs btn-flat"><i class="bi bi-pencil"></i>Active Transaction</a>
                @endempty
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-purchase">
                    <thead>
                        <th>No</th>
                        <th>Date</th>
                        <th>Supplier Name</th>
                        <th>Total Item</th>
                        <th>Total Price</th>
                        <th>Diskon</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('admin.purchase.supplier')
@includeIf('admin.purchase.detail')
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
<!-- <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> -->
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script>
let table, table1;

$(function() {
    table = $('.table-purchase').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: '{{ route('purchase.data') }}',
        },
        columns: [
            { data: 'DT_RowIndex', searchable: false, sortable: false },
            { data: 'date' },
            { data: 'supplier' },
            { data: 'total_item' },
            { data: 'total_price' },
            { data: 'diskon' },
            { data: 'payment' },
            { data: 'action', searchable: false, sortable: false },
        ]
    });

    $('.table-supplier').DataTable();
    table1 = $('.table-detail').DataTable({
        processing: true,
        bSort: false,
        dom: 'Brt',
        columns: [
            { data: 'DT_RowIndex', searchable: false, sortable: false },
            { data: 'item_id' },
            { data: 'item_name' },
            { data: 'buy_price' },
            { data: 'qty' },
            { data: 'subtotal' },
        ]
    })
});

function addForm() {
    $('#modal-supplier').modal('show');
}

function showDetail(url) {
    $('#modal-detail').modal('show');

    table1.ajax.url(url);
    table1.ajax.reload();
}

function deleteData(url) {
    if (confirm('Are you sure?')) {
        $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('Cannot delete data');
                return;
            });
    }
}
</script>
@endsection
