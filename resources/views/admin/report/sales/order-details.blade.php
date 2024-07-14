<div>
    <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-0 p-0 m-0 pb-1">
            Invoice:
            <span class="">{{ $order->no_invoice }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-0 p-0 m-0 pb-1">
            Datetime:
            <span class="">{{ $order->created_at->format('d-m-Y H:i:s') }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-0 p-0 m-0 pb-1">
            Cashier:
            <span class="">{{ $order->cashier_name ?? '-' }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-0 p-0 m-0 pb-1">
            Customer:
            <span class="">{{ $order->customer_name ?? '-' }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-0 p-0 m-0 pb-1">
            Payment Method:
            <span class="badge bg-primary rounded-pill">{{ $order->payment_method }}</span>
        </li>
    </ul>

    <h6>Products</h6>
    <div class="table-responsive">
        <table class="table" id="products-table">
            <thead class="">
                <tr>
                    <th scope="col" style="border-top-left-radius: 15px; border-bottom-left-radius: 15px;">No</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Qty</th>
                    <th scope="col" style="border-top-right-radius: 15px; border-bottom-right-radius: 15px;">Addons</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderProducts as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->qty }}</td>
                    <td>
                        @if($product->orderProductAddons->isNotEmpty())
                            <ul>
                                @foreach($product->orderProductAddons as $addon)
                                    <li>{{ $addon->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    $('#products-table').DataTable({
        paging: false,         // Disable pagination
        searching: false,      // Disable search
        info: false,           // Disable table information display
        ordering: false,       // Disable sorting
        lengthChange: false    // Disable entries per page dropdown
    });
</script>
