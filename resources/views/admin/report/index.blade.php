@extends('layouts.admin')
@section('header', 'Report Income')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://fontawesome.com/releases/v5.15/css/all.css"/>
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header with-border">
	 			<button onclick="updatePeriode()" class="btn btn-success btn-xs btn-flat"><i class="far fa-calendar">Periode</i></button>
	 			<a href="{{ route('report.export_pdf', [$firstDate, $endDate]) }}" target="_blank" class="btn btn-info btn-xs btn-flat"><i class="fa fa-file-pdf">Export PDF</i></a><br><br>
                <h4>Income Report from {{ en_date($firstDate, false) }} until {{ en_date($endDate, false) }}</h4>
			</div>
  			<div class="card-body table-responsive">
				<table class="table table-bordered table-striped table">
					<thead>
				        <tr>
				          	<th width="5%">No.</th>
							<th width="20%">Date</th>
							<th width="15%">Sale</th>
							<th width="15%">Purchase</th>
							<th width="15%">Stock Out</th>		
							<th width="15%">Total Income</th>		
				        </tr>
		      		</thead>
				</table>
	  		</div>
		</div>
	</div>
</div>
@includeIf('admin.report.form')
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
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('report.data', [$firstDate, $endDate]) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'date'},
                {data: 'sale'},
                {data: 'purchase'},
                {data: 'stock_out'},
                {data: 'income'}
            ],
            dom: 'Brt',
            bSort: false,
            bPaginate: false,
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }
</script>
@endsection