@extends('layouts.admin')
@section('header', 'Role')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div id="controller">
	<div class="row">
		<div class="col-8">
			<div class="card">
				<div class="card-header">
	   	 			<a href="#" @click="addData()" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus-circle">Create New Role</i></a>
				</div>
		  			<div class="card-body">
	    				<table id="datatable" class="table table-bordered table-striped">
      						<thead>
						        <tr>
						          	<th>No</th>
									<th>Name</th>
									<th>Created At</th>
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
					<h4 class="modal-title">Role</h4>
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      					<span aria-hidden="true">&times;</span>
	        		</button>
  				</div>
			    <div class="modal-body">
		        @csrf	
			        <input type="hidden" name="_method" value="PUT" v-if="editStatus">

			        <div class="form-group">
		        		<label>Name</label>
		        		<input type="text" name="name" class="form-control" placeholder="Enter Role Name" :value="data.name">
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
	var	actionUrl = '{{ url('roles') }}';
	var apiUrl = '{{ url('api/roles') }}';

	var columns = [
			{data: 'DT_RowIndex', width: '10px', class: 'text-center', orderable: true},
			{data: 'name', class: 'text-center', width: '10px', orderable: true},
			{data: 'date', class: 'text-center', width: '100px', orderable: true},
			{render: function (index,row,data,meta) {
				return `
					<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event,${meta.row})">
						<i class="fas fa-edit">Edit</i>
					</a>
					<a class="btn btn-danger btn-sm" onclick="controller.deleteData(event,${data.id})">
						<i class="fas fa-trash">Delete</i>
					</a>`;
			}, orderable: false, width: '100px', class: 'text-center'}
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
		}		
	});
</script>
@endsection