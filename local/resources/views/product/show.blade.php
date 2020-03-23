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
                                <th>item</th>
                                <th>ordered_qty</th>
                                <th>pack_qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $product->sales_ccn }}</td>
                                <td>{{ $product->mas_loc }}</td>
                                <td>{{ $product->promise_date }}</td>
                                <td>{{ $product->ship_via }}</td>
                                <td>{{ $product->customer }}</td>
                                <td>{{ $product->cus_ship_loc }}</td>
                                <td>{{ $product->so }}</td>
                                <td>{{ $product->so_line }}</td>
                                <td>{{ $product->fullfill_from }}</td>
                                <td>{{ $product->item }}</td>
                                <td>{{ $product->ordered_qty }}</td>
                                <td>{{ $product->pack_qty}}</td>
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
