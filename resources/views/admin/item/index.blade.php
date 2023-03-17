@extends('layouts.admin')
@section('header', 'Item')

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
					<div class="btn-group">
	                	<a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus-circle">Create New Item</i></a>
	                    <a href="#" @click="deleteSelected('{{ route('items.delete_selected') }}')" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-trash"></i>Delete Selected</a>
                	</div>					
				</div>
				<div class="card-body">
					<form action="" class="form-item">
						@csrf
						<table id="datatable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>
										<input type="checkbox" name="select_all" id="select_all">
									</th>
									<th>No</th>
									<th>Name</th>
									<th>Form</th>
									<th>Classification</th>
									<th>Sell Price</th>
									<th>Buy Price</th>
									<th>Diskon</th>
									<th>Qty</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-default">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST" :action="actionUrl" autocomplete="off" @submit="submitForm($event, data.id)">
					<div class="modal-header">
						<h4 class="modal-title">Item</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						@csrf	
						<input type="hidden" name="_method" value="PUT" v-if="editStatus">

						<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" class="form-control" placeholder="Enter name" :value="data.name">
						</div>
						<div class="form-group">
							<label>Form</label>
							<select name="form_id" class="form-control">
								@foreach($forms as $form)
								<option :selected="data.form_id == {{ $form->id }}" value="{{ $form->id }}">{{ $form->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Classification</label>
							<select name="classification_id" class="form-control">
								@foreach($classifications as $classification)
								<option :selected="data.classification_id == {{ $classification->id }}" value="{{ $classification->id }}">{{ $classification->name }}</option>
								@endforeach
							</select>
						</div>	
						<div class="form-group">
							<label>Sell Price</label>
							<input type="number" name="sell_price" class="form-control" placeholder="Enter Price" :value="data.sell_price">
						</div>	
						<div class="form-group">
							<label>Buy Price</label>
							<input type="number" name="buy_price" class="form-control" placeholder="Enter Price" :value="data.buy_price">
						</div>
						<div class="form-group">
							<label>Diskon</label>
							<input type="number" name="diskon" class="form-control" placeholder="Enter Diskon" :value="data.diskon ?? 0">
						</div>
						<div class="form-group">
							<label>Qty</label>
							<input type="number" name="qty" class="form-control" placeholder="Enter Qty" :value="data.qty ?? 0">		        		
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
	var	actionUrl = '{{ url('items') }}';
	var apiUrl = '{{ url('api/items') }}';

	var columns = [
	{data: 'select_all', orderable: false, class: 'text-center'},
	{data: 'DT_RowIndex', class: 'text-center', orderable: true},
	{data: 'name', class: 'text-center', orderable: true},
	{data: 'form.name', class: 'text-center', orderable: true},
	{data: 'classification.name' , class: 'text-center', orderable: true},
	{data: 'sell_price', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ), class: 'text-center', orderable: false},
	{data: 'buy_price', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ), class: 'text-center', orderable: false},
	{data: 'diskon', class: 'text-center', orderable: false},
	{data: 'qty', class: 'text-center', orderable: true},
	{render: function (index,row,data,meta) {
		return `
		<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event,${meta.row})">
		<i class="fas fa-edit">Edit</i>
		</a>
		<a class="btn btn-danger btn-sm" onclick="controller.deleteData(event,${data.id})">
		<i class="fas fa-trash">Delete</i>
		</a>`;
	}, orderable: false, width: '100px', class: 'text-center'},
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
			editData(event,row) {
				this.data = this.datas[row];								
				this.editStatus = true;
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
			numberWithCommas(x) {
				return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			},
			deleteSelected(url) {
				const _this = this;
		        if ($('input:checked').length > 1) {
		            if (confirm('Are you sure?')) {
		                $.post(url, $('.form-item').serialize())
		                    .done((response) => {
		                    	alert('Data has been removed');
		                        _this.table.ajax.reload();
		                    })
		                    .fail((errors) => {
		                        alert('Cannot delete the data');
		                        return;
		                    });
		            }
		        } else {
		            alert('Choose the data');
		            return;
		        }
		    }
		}		
	});

	// delete multiple
	$('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
    });
</script>
@endsection