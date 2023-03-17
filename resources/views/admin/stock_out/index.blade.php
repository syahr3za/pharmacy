@extends('layouts.admin')
@section('header', 'Stock Out')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div id="controller">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
	   	 			<a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right">Create New Stock Out</a>
				</div>
		  			<div class="card-body">
	    				<table id="datatable" class="table table-bordered table-striped table-stock">
      						<thead>
						        <tr>
						          	<th>No</th>
									<th>Date</th>
									<th>Item</th>
									<th>Detail</th>
									<th>Qty</th>		
									<th>Total Price</th>		
									<th>Action</th>
						        </tr>
				      		</thead>
    					</table>
		  			</div>
				</div>
			</div>
		</div>

<div class="modal fade" id="modal-default">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" :action="actionUrl" autocomplete="off" @submit="submitForm($event, data.id)">
  				<div class="modal-header">
					<h4 class="modal-title">Stock Out</h4>
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      					<span aria-hidden="true">&times;</span>
	        		</button>
  				</div>
			    <div class="modal-body">
		        @csrf	
			        <input type="hidden" name="_method" value="PUT" v-if="editStatus">

			        <div class="form-group">
						<label>Item</label>
						<select name="item_id" class="form-control">
							@foreach($items as $item)
							<option :selected="data.item_id == {{ $item->id }}" value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
						</select>
					</div>
			        <div class="form-group">
		        		<label>Detail</label>
		        		<input type="text" name="detail" class="form-control" :value="data.detail">		        		
			        </div>
			        <div class="form-group">
			        	<label>Qty</label>
			        	<input type="number" name="qty" class="form-control" :value="data.qty ?? 0">
			        </div>
	      		</div>
			    <div class="modal-footer justify-content-between">
				    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				    <button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
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
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script type="text/javascript">
	var	actionUrl = '{{ url('stock_outs') }}';
	var apiUrl = '{{ url('api/stock_outs') }}';

	var columns = [
		{data: 'DT_RowIndex', width: '30px', class: 'text-center', orderable: true},
		{data: 'created_at', width: '150px', class: 'text-center', orderable: true},
		{data: 'item_id', orderable: false},
		{data: 'detail' , orderable: false},
		{data: 'qty', width: '50px', class: 'text-center', orderable: true},
		{data: 'total_price', width: '100px', class: 'text-center', orderable: true},
		{render: function (index,row,data,meta) {
				return `
					<a class="btn btn-danger btn-sm" onclick="controller.deleteData(event,${data.id})">
							Delete
					</a>`;
			}, orderable: false, width: '50px', class: 'text-center'}
	];

	var controller = new Vue({
		el: '#controller',
		data: {
			datas: [],
			data: {},
			actionUrl,
			apiUrl,
			editStatus: false,
		},
		mounted: function() {
			this.datatable();
		},
		methods: {
			datatable() {
				const _this = this;
				_this.table = $('#datatable').DataTable({
					ajax: {
						url: _this.apiUrl,
						type: 'GET',
					},
					columns: columns
				}).on('xhr', function () {
					_this.datas = _this.table.ajax.json().data;									
				});
			},
			addData() {
				this.data = {};
				this.editStatus = false;
				$('#modal-default').modal();

			},
			deleteData(event,id) {
				if( confirm("Are you sure?")) {
					$(event.target).parents('tr').remove();
					axios.post(this.actionUrl+'/'+id, {_method: 'DELETE'}).then(response => {
						alert('Data has been removed');
					});
				}
			},
			submitForm(event,id) {
				event.preventDefault();
				const _this = this;
				var actionUrl = ! this.editStatus ? this.actionUrl : this.actionUrl+'/'+id;
				axios.post(actionUrl, new FormData($(event.target)[0])).then(response=> {
					$('#modal-default').modal('hide');
					_this.table.ajax.reload();
				});
			},
		}
	});
</script>
@endsection