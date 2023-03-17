<div class="modal fade" id="modal-item" tabindex="-1" role="dialog" aria-labelledby="modal-item">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-item">
                        <thead>
                            <th width="5%">No</th>
                            <th>Name</th>
                            <th>Sell Price</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ 'Rp. '. format_uang($item->sell_price) }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                    onclick="selectItem('{{ $item->id }}')">
                                    <i class="fa fa-check-circle"></i>
                                    Select
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>