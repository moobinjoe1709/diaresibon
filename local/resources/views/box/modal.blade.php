<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Shipment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product2">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>sales_ccn</th>
                                <th>mas_loc</th>
                                <th>promise_date</th>
                                <th>ship_via</th>
                                <th>customer</th>
                                <th>cus_ship_loc</th>
                                <th>so</th>
                                <th>so_line</th>
                                <th>fullfill_from</th>
                                <th>ordered_qty</th>
                                <th>pack_qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boxs as $key => $box)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $box->bo_sale_ccn }}</td>
                                <td>{{ $box->bo_mas_loc }}</td>
                                <td>{{ $box->bo_promise_date }}</td>
                                <td>{{ $box->bo_ship_via }}</td>
                                <td>{{ $box->bo_customer }}</td>
                                <td>{{ $box->bo_cus_ship_loc }}</td>
                                <td>{{ $box->bo_so }}</td>
                                <td>{{ $box->bo_so_line }}</td>
                                <td>{{ $box->bo_fullfill_from }}</td>
                                <td>{{ $box->bo_order_qty_sum }}</td>
                                <td>{{ $box->bo_pack_qty}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
