<!doctype html>
<html lang="en">

<head>
	@include('admin.layouts.partials.head')

    <style>
        .custom-table th{
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            background: #324c61;
            text-align: center;
        }
        .costum-form {
            border: 0;
            border-radius: 0;
            border-bottom: 1px solid #e2e2e2;
        }
        .table-cart > td{
            padding: 10px !important;
            vertical-align: middle;
        }
        .table-cart > td:last-child{
            text-align: right;
        }
        .table-cart > td:nth-child(2){
            text-align: center;
        }
        .custom-height{
            height: 48vh;
        }
        .list-group-item{
            border: 0;
            border-top: 1px solid #e2e2e2;
            border-bottom: 1px solid #e2e2e2;
            border-radius: 0 !important;
            padding: 10px 15px !important;
        }
        .my-product{
            height: 70vh;
            overflow-y: auto;
        }
        .my-action{
            height: 30vh;
        }
        .info-cart tr>td{
            width: 33.33333% !important;
        }

        .info-cart tr>td:last-child{
            text-align: right;
        }
        tr>td:first-child{
            border-right: 1px solid #dee2e6 !important;
        }
        tr>td:last-child{
            border-left: 1px solid #dee2e6 !important;
        }
        .card-img-top{
            border-radius: 5px !important;
            width: 80%;

        }
        .page-content{
            padding: 1rem 1.5rem 0.7rem 1.5rem !important;
        }

        .tab-style{
            max-height: 60vh;
            height: auto;
            overflow-y: auto;
        }
        .typeahead{
            border-radius: 0 !important;
            border-bottom-right-radius: 7px !important;
            border-top-right-radius: 7px !important;
        }
        .tt-hint {
            color: #999999;
        }
        .tt-menu {
            background-color: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            margin-top: 0px;
            padding: 8px 0;
            width: 375px;
        }
        .tt-suggestion {
            font-size: 22px;  /* Set suggestion dropdown font size */
            padding: 3px 15px;
        }
        .tt-suggestion:hover {
            cursor: pointer;
            background-color: #0097CF;
            color: #FFFFFF;
        }
        .tt-suggestion p {
            margin: 0;
        }
        .twitter-typeahead{
            width: 80% !important;
        }
    </style>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--start page wrapper -->
		<div class="page-wrapper m-0">
			<div class="page-content">
				<div class="row">
                    <div class="col-12">
                        <div class="multiple-items mb-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-sm bg-seccondary border-secondary-subtle">Home</a>
                            <button class="btn btn-sm bg-seccondary border-secondary-subtle" onclick="modalMyOrder()"  data-bs-target="#modal-my-order">Order</button>
                        </div>
                    </div>
					<div class="col-12 col-md-6">
                        <div class="card">
							<div class="card-body m-0 p-0">
                                <div class="row">
                                    <div class="fm-search col-lg-12 px-4 mt-3">
                                        <div class="mb-0">
                                            <div class="input-group">
                                                {{-- <button class="btn btn-outline-secondary text-dark" type="button" style="font-size:14px;"><i class='bx bx-comment-detail me-0' style="font-size:16px;"></i> <small>Comments</small></button> --}}
                                                <button class="btn btn-outline-secondary text-dark" type="button" style="font-size:14px;" onclick="ModalAddCoupon()" data-bs-target="#modal-add-coupon"><i class='bx bx-tag me-0' style="font-size:16px;"></i> <small id="coupon-info">Coupon</small></button>
                                                <input type="text" class="form-control" placeholder="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<!--end row-->
								<form action="{{ route('checkout-order', md5(strtotime("now"))) }}" method="POST" class="">
									@csrf
                                    <input type="hidden" name="coupon_id" value="">

									<div class="table-responsive mt-3 custom-height">
										<table class="table table-sm mb-0 custom-table">
											<thead>
												<tr>
													<th width="60%">Product</th>
													<th width="15%">Quantity</th>
													<th width="25%">Price</th>
												</tr>
											</thead>
											<tbody id="cart-product">
												@forelse ($data_items as $key => $item)
                                                    {{-- {{ dd($item) }} --}}
                                                    <tr class="table-cart">
                                                        <td>
                                                            <div class="d-flex justify-content-between">
                                                                <div class="">
                                                                    <p class="p-0 m-0">
                                                                        {{ $item->attributes['product']['name'] }}
                                                                    </p>
                                                                    <small>Unit: {{ $item->conditions }}</small>
                                                                </div>

                                                                <div class="">
                                                                    <a href="{{ route('delete-item', $key)}}" class="" style="border-bottom: 1px dashed red;">
                                                                        <i class='bx bx-trash font-14 text-danger'></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <input type="hidden" name="qty[]" id="quantityInput" readonly class="min-width-40 flex-grow-0 border border-success text-success fs-4 fw-semibold form-control text-center qty" min="0" style="width: 15%"  value="{{ $item->quantity }}">
                                                        <td>Rp.{{ number_format($item->attributes['product_unit']['sale_price'], 0, ',', '.') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr class="table-cart">
                                                        <td colspan="3" class="text-center">No products added</td>
                                                    </tr>
                                                @endforelse
											</tbody>
										</table>
									</div>

                                    <div class="row my-action align-items-end align-content-end">
                                        <div class="col-12">
                                            <table width="100%" class="table">
                                                <tbody class="info-cart">
                                                    <tr style="border-top: 1px solid #dee2e6 !important;">
                                                        <td>
                                                            <a href="#!" type="button" onclick="ModalAddCustomer()" class="cursor-pointer text-dark" data-bs-target="#modal-add-customer" style="border-bottom: 1px dashed #000;font-size:12px;"><span>Customer: <small id="data-customer" style="font-size: 12px;">No data</small></span></a>
                                                            <input type="hidden" name="customer_id" value="">
                                                        </td>
                                                        <td>
                                                            <small>Sub Total</small>
                                                        </td>
                                                        <td>
                                                            <small id="subtotal-cart">Rp.{{ number_format($subtotal, 0, ',', '.') }}</small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <span style="font-size: 12px;">Tax({{ $other_setting->pb01 }}%): <small id="tax-cart" style="font-size: 12px;">Rp.{{ number_format($tax, 0, ',', '.') }}</small></span>
                                                        </td>
                                                        <td>
                                                            <span style="font-size: 12px;">Discount<small id="type-discount"></small></span>
                                                            <input type="hidden" name="type_discount" value="">
                                                        </td>
                                                        <td>
                                                            <a href="#!" type="button" onclick="ModalAddDiscount()" class="cursor-pointer text-dark" data-bs-target="#modal-add-discount" style="border-bottom: 1px dashed #000;font-size:14px;">
                                                                <small id="discount-price">Rp.0</small>
                                                            </a>
                                                            <input type="hidden" name="discount_price" value="">
                                                            <input type="hidden" name="discount_percent" value="">
                                                            <input type="hidden" name="ongkir_price" value="">
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <span style="font-size: 12px;">Ongkir : </span><small id="ongkir-price">Rp.0</small>
                                                        </td>
                                                        <td class="bg-light-info text-dark fw-medium">
                                                            <small>Total</small>
                                                        </td>
                                                        <td class="bg-light-info text-dark fw-medium" >
                                                            <small id="total-cart">Rp.{{ number_format($total, 0, ',', '.') }}</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-group w-100 p-3 pt-0" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-lg btn-success fw-bold w-25 p-3" data-bs-toggle="modal" data-bs-target="#modalPayment">
                                                    <h6 class="mb-0 text-white">
                                                        PAY
                                                    </h6>
                                                </button>
                                                <button type="button" class="btn btn-lg btn-primary fw-bold w-25 p-3" onclick="onHoldOrder()">
                                                    <h6 class="mb-0 text-white">
                                                        ON HOLD
                                                    </h6>
                                                </button>
                                                <button type="button" class="btn btn-lg btn-white fw-bold w-25 p-3" onclick="ModalAddDiscount()" data-bs-target="#modal-add-discount">
                                                    <h6 class="mb-0 text-dark">
                                                        DISCOUNT
                                                    </h6>
                                                </button>
                                                <button type="button" class="btn btn-lg btn-warning fw-bold w-25 p-3" onclick="ModalAddOngkir()" data-bs-target="#modal-add-discount">
                                                    <h6 class="mb-0 text-white">
                                                        ONGKIR
                                                    </h6>
                                                </button>
                                                <button type="button" class="btn btn-lg btn-danger fw-bold w-25 p-3" onclick="voidCart()">
                                                    <h6 class="mb-0 text-white">
                                                        VOID
                                                    </h6>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
									<div class="modal fade" id="modalPayment" tabindex="-1" aria-labelledby="modalPaymentLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="modalPaymentLabel">PAYMENT</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <h6 class="mb-3">Metode Payment</h6>
                                                            <select name="metode_pembayaran" id="metodePayment" class="form-control form-control-sm">
                                                                <option selected value="Transfer Bank">Transfer Bank</option>
                                                                <option value="EDC BCA">EDC BCA</option>
                                                                <option value="EDC BRI">EDC BRI</option>
                                                                <option value="EDC BNI">EDC BNI</option>
                                                                <option value="Qris">Qris</option>
                                                                <option value="Cash">Cash</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group mt-2" id="cashInput" style="display: none;">
                                                            <label for="cash" class="form-label">Cash</label>
                                                            <input type="number" name="cash" value="{{ old('cash') }}" class="form-control form-control-sm" placeholder="Ex:50.000" id="cash" aria-describedby="cash">
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <div class="input-group">
                                                                <textarea name="alamat" placeholder="Ex:Jl.sudirman" class="form-control" id="alamat" rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-success">PAY</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
                    </div>
					<div class="col-12 col-md-6">
                        <div class="card">
							<div class="card-body m-0 p-0">
                                <div class="row">
                                    <div class="fm-search col-lg-12 px-4 mt-3">
                                        <div class="mb-0">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary text-dark" type="button" onclick="ModalSearch()"><i class='bx bx-search-alt me-0'></i></button>
                                                <button class="btn btn-outline-secondary text-dark" type="button" onclick="focusInput('barcode')"><i class='bx bx-barcode-reader me-0'></i></button>
                                                <input type="text" id="text-search" class="form-control barcode" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<!--end row-->
								<div class="row mt-3">
                                    <div class="col-12">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-start align-items-center gap-1">
                                                <a href="#!" onclick="loadCategories();" class="text-dark">Home</a> /
                                                <span id="categoryNavigation"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

								<div class="row mt-3 px-3 pb-3 my-product align-content-start" id="productContainer"></div>
							</div>
						</div>
                    </div>
				</div>
				<!--end row-->
			</div>
		</div>
		<!--end page wrapper -->
	</div>
	<!--end wrapper-->
    <div id="modalContainer"></div>
	<!--end switcher-->
	@include('admin.layouts.partials.foot')


    <script>
        const selectInput = document.getElementById('metodePayment');
        const cashInput = document.getElementById('cashInput');

        selectInput.addEventListener('change', function() {
            if (selectInput.value === 'Cash') {
                cashInput.style.display = 'block';
            } else {
                cashInput.style.display = 'none';
            }
        });
    </script>
    <script>

        function onHoldOrder() {
            $.confirm({
                title: `Onhold Order`,
                content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<label>Customer Name</label>' +
                    '<input type="text" placeholder="Enter costomer name..." class="name form-control" />' +
                    '</div>' +
                    '</form>',
                autoClose: 'cancel',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            $.ajax({
                                url: "{{ route('on-hold-order') }}",
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "name" : name
                                },
                                success: function(response) {
                                    console.log(response);
                                    // Clear cart in table
                                    $('#cart-product').empty();

                                    // Add list in table after clear
                                    var addlist = `<tr class="table-cart">`+
                                                    `<td colspan="3" class="text-center">No products added</td>`+
                                                `</tr>`;
                                    $('#cart-product').append(addlist);

                                    $('#subtotal-cart').text(`Rp.0`);
                                    $('#tax-cart').text(`Rp.0`);
                                    $('#tax-cart').text(`Rp.0`);
                                    $('#total-cart').text(`Rp.0`);

                                    // Discount
                                    $('#type-discount').text("");
                                    $('#discount-price').text(`Rp.0`);
                                    $('input[name="discount_price"]').val(0);
                                    $('input[name="discount_percent"]').val(0);
                                    $('input[name="ongkir_price"]').val(0);

                                    // Customer
                                    $('input[name="customer_id"]').val(null);
                                    $('#data-customer').text('No data');
                                },
                                error: function(xhr, status, error) {
                                    console.error('Failed to load Product: ', error);
                                }
                            });
                        }
                    },
                    cancel: function () {

                    }
                }
            });
        }

        function openOnholdOrder(key) {
            $.ajax({
                url: "{{ route('open-on-hold-order') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "key": key,
                },
                success: function(response) {
                    console.log(response);
                    $('#cart-product').empty();
                    $.each(response.data, function(index, cart) {
                        var addList = `<tr class="table-cart">`+
                                            `<td>`+
                                                `<div class="d-flex justify-content-between">`+
                                                    `<div class="">`+
                                                        `<p class="p-0 m-0">`+
                                                            `${cart.name}`+
                                                        `</p>`+
                                                        `<small>Unit: ${cart.conditions}</small>`+
                                                    `</div>`+

                                                    `<div>`+
                                                        `<a href="/delete-item/${index}" class="" style="border-bottom: 1px dashed red;">`+
                                                            `<i class='bx bx-trash font-14 text-danger'></i>`+
                                                        `</a>`+
                                                    `</div>`+
                                                `</div>`+
                                            `</td>`+
                                            `<td>${cart.quantity}</td>`+
                                            `<input type="hidden" name="qty[]" id="quantityInput" class="form-control qty" min="0"  value="${cart.quantity}">`+
                                            `<td>Rp.${numberFormat(cart.attributes['product_unit']['sale_price'])}</td>`+
                                        `</tr>`;

                        $('#cart-product').append(addList);
                    });

                    $('#subtotal-cart').text(`Rp.${formatRupiah(response.subtotal)}`);
                    $('#tax-cart').text(`Rp.${response.tax}`);
                    $('#total-cart').text(`Rp.${formatRupiah(response.total)}`);
                    $('#modal-my-order').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function deleteOnholdOrder(key) {
            $.ajax({
                url: "{{ route('delete-on-hold-order') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "key": key,
                },
                success: function(response) {
                    console.log(response);
                    $(`#onhold-${key}`).remove();
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function focusInput(type){
            if (type == 'barcode') {
                $('#text-search').focus();
                $('#text-search').removeClass('search');
                $('#text-search').addClass('barcode');
            }else if (type == 'search') {
                $('#text-search').removeClass('barcode');
                $('#text-search').addClass('search');
            }
        }

        $(document).ready(function() {
            loadCategories();
        });

        function ModalSearch() {
            var getTarget = `#modal-search-product`;
            $.ajax({
                url: "{{ route('modal-search-product') }}" ,
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        $.ajax({
                            url: "{{ route('search-product') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                initTypeahead(response, getTarget);
                            },
                            error: function(xhr, status, error) {
                                console.error('Gagal memuat Produk: ', error);
                            }
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function initTypeahead(products, target) {
            // Constructing the suggestion engine
            var productsEngine = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: products
            });

            // Initializing the typeahead
            $('.typeahead').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'products',
                display: 'name',
                source: productsEngine,
                templates: {
                    suggestion: function(data) {
                        return '<div>' + data.name + '</div>';
                    }
                }
            }).on('typeahead:selected', function(event, data) {
                $(`${target}`).modal('hide'); // Menutup modal
                ModalAddToCart(data.id);
            });
        }

        function updateDataList(products) {
            var dataList = $('#productList');
            dataList.empty();

            $.each(products, function(index, product) {
                dataList.append('<option value="' + product.name + '">');
            });
        }

        function ModalAddToCart(productId) {
            var getTarget = `#modal-add-to-cart-${productId}`;
            $.ajax({
                url: "{{ route('modal-add-cart', '') }}" +'/'+ productId,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                        $(`${getTarget}`).modal('show');
                        $(`${getTarget}`).on('shown.bs.modal', function () {
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function ModalAddCustomer() {
            var getTarget = `#modal-add-customer`;
            $.ajax({
                url: "{{ route('modal-add-customer') }}" ,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        $.ajax({
                            url: "{{ route('get-data-customers') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                initTypeaheadCustomer(response, getTarget);
                            },
                            error: function(xhr, status, error) {
                                console.error('Gagal memuat Produk: ', error);
                            }
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function initTypeaheadCustomer(customers, target) {
            // Constructing the suggestion engine
            var customersEngine = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: customers
            });

            // Initializing the typeahead
            $('.typeahead').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'customers',
                display: 'name',
                source: customersEngine,
                templates: {
                    suggestion: function(data) {
                        return '<div>' + data.name + '</div>';
                    }
                }
            }).on('typeahead:selected', function(event, data) {
                $('input[name="customer_id"]').val(data.id);
                $('#data-customer').text(data.name);
                $(`${target}`).modal('hide');
            });
        }

        function ModalAddCoupon() {
            var getTarget = `#modal-add-coupon`;
            $.ajax({
                url: "{{ route('modal-add-coupon') }}" ,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        var getValOngkir = $('input[name="ongkir_price"]').val();

                        $('#save-coupon').on('click', function() {
                            var getCoupon = $('#select-coupon').val();
                            $('input[name="coupon_id"]').val(getCoupon);
                            $('input[name="ongkir_price"]').val(getValOngkir);

                            updateCouponInCart(getCoupon,getValOngkir)
                            $(`${getTarget}`).modal('hide'); // Menutup modal
                        })
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        // Coupon update
        function updateCouponInCart(couponId,getValOngkir) {
            $.ajax({
                url: "{{ route('update-cart-by-coupon') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "coupon_id":couponId,
                    "ongkir_price":getValOngkir,
                },
                success: function(response) {
                    console.log(response);
                    $('#coupon-info').text(`Coupon (${response.info})`)
                    $('#subtotal-cart').text(`Rp.${formatRupiah(response.subtotal)}`)
                    $('#tax-cart').text(`Rp.${formatRupiah(response.tax)}`)
                    $('#total-cart').text(`Rp.${formatRupiah(response.total)}`)
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }


        function ModalAddDiscount() {
            var getTarget = `#modal-add-discount`;
            $.ajax({
                url: "{{ route('modal-add-discount') }}" ,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        $('#select-type-discount').on('change', function() {
                            var selectedValue = $(this).val();

                            if (selectedValue == 'price') {
                                $('#input-price').prop('disabled', false);
                                $('#input-percent').prop('disabled', true);
                            } else if (selectedValue == 'percent') {
                                $('#input-percent').prop('disabled', false);
                                $('#input-price').prop('disabled', true);
                            }
                        })

                        $('#input-price').on('keyup', function() {
                            handleInput('input-price');
                        });

                        $('#save-discount').on('click', function() {
                            var getValPrice = $('input[name="input-price"]').val();
                            var getValPercent = $('input[name="input-percent"]').val();
                            var getTypeDiscount = $('#select-type-discount').val();
                            var getValOngkir = $('input[name="ongkir_price"]').val();

                            $('input[name="type_discount"]').val(getTypeDiscount);
                            if (getTypeDiscount == 'price') {
                                $('#type-discount').text(`(price)`);
                                $('#discount-price').text(`Rp.${formatRupiah(getValPrice)}`);
                                $('input[name="discount_price"]').val(getValPrice);
                            } else if(getTypeDiscount == 'percent') {
                                $('#type-discount').text(`(percent)`);
                                $('#discount-price').text(`${getValPercent}%`);
                                $('input[name="discount_percent"]').val(getValPercent);
                            }
                            $('input[name="ongkir_price"]').val(getValOngkir);
                            updateDiscountInCart(getTypeDiscount, getValPrice, getValPercent,getValOngkir)

                            $(`${getTarget}`).modal('hide'); // Menutup modal
                        })
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function ModalAddOngkir() {
            var getTarget = `#modal-add-ongkir`;
            $.ajax({
                url: "{{ route('modal-add-ongkir') }}" ,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        $('#input-price').on('keyup', function() {
                            handleInput('input-price');
                        });

                        var getValTypeDiscount = $('input[name="type_discount"]').val();
                        var getValDiscountPrice = $('input[name="discount_price"]').val();
                        var getValDiscountPercent = $('input[name="discount_percent"]').val();

                        $('#save-ongkir').on('click', function() {
                            var getValOngkir = $('input[name="input-price"]').val();
                            $('input[name="ongkir_price"]').val(getValOngkir);

                            $('#ongkir-price').text(`Rp.${formatRupiah(getValOngkir)}`);
                            updateOngkirInCart(getValOngkir,getValDiscountPrice,getValDiscountPercent,getValTypeDiscount)

                            $(`${getTarget}`).modal('hide'); // Menutup modal
                        })
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function loadCategories() {
            $('#productContainer').html('<p class="text-center">Waiting...</p>');
            $('#categoryNavigation').empty();

            $.ajax({
                url: "{{ route('get-category') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderCategories(response);
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load categories: ', error);
                }
            });
        }

        function loadProducts(categoryId, categoryName) {
            $('#productContainer').html('<p class="text-center">Waiting...</p>');
            $('#categoryNavigation').append(`${categoryName}`);

            $.ajax({
                url: "{{ route('get-product', '') }}" +'/'+ categoryId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderProducts(response);
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        function renderCategories(categories) {
            const productContainer = $('#productContainer');

            productContainer.empty();

            if (categories.length > 0) {
                $.each(categories, function(index, category) {
                    const categoryDiv = $('<div class="col-12 col-md-3 text-center"></div>');

                    const categoryLink = $(`<a href="#!" type"button" onclick="loadProducts(${category.id}, '${category.name}');" class="cursor-pointer text-dark"></a>`);

                    const categoryImg = $('<img class="card-img-top" alt="">').attr('src', category.picture ? 'assets/images/category/' + category.picture : 'https://ui-avatars.com/api/?name=' + category.name.replace(' ', '+'));

                    const categoryName = $('<p class="mb-0 fw-bold mt-1"></p>').text(category.name);

                    categoryLink.append(categoryImg, categoryName);
                    categoryDiv.append(categoryLink);
                    productContainer.append(categoryDiv);
                });
            } else {
                const noCategoryDiv = $('<div class="col-12 text-center"></div>');
                const noCategoryHeader = $('<h3>No Category Added</h3>');

                noCategoryDiv.append(noCategoryHeader);
                productContainer.append(noCategoryDiv);
            }
        }

        function renderProducts(products) {
            const productContainer = $('#productContainer');
            productContainer.empty();

            if (products.length > 0) {
                $.each(products, function(index, product) {
                    const productDiv = $('<div class="col-12 col-md-3 text-center"></div>');

                    const productLink = $(`<a href="#!" onclick="ModalAddToCart(${product.id})" class="cursor-pointer text-dark" type="button" data-bs-target="#modal-add-to-cart-${product.id}"></a>`);

                    const productImg = $('<img class="card-img-top" alt="">').attr('src', product.picture ? 'assets/images/product/' + product.picture : 'https://ui-avatars.com/api/?name=' + product.name.replace(' ', '+'));

                    const productName = $('<p class="mb-0 fw-bold mt-1"></p>').text(product.name);

                    // Check jika semua detail produk terjual
                    const allDetailsSold = product.product_detail.every(function(detail) {
                        return detail.quantity <= 0;
                    });

                    // productLink.append(productImg, productName, productPrice);
                    productLink.append(productImg, productName);
                    productDiv.append(productLink);
                    if (allDetailsSold) {
                        // Jika semua detail produk terjual, tambahkan tag <p> untuk menampilkan informasi "Sold"
                        const productSoldInfo = $('<p class="text-muted mb-0">Sold</p>');
                        productDiv.append(productSoldInfo);
                    }
                    productContainer.append(productDiv);
                });
            } else {
                const noProductDiv = $('<div class="col-12 text-center"></div>');
                const noProductHeader = $('<h3>No Product Added</h3>');

                noProductDiv.append(noProductHeader);
                productContainer.append(noProductDiv);
            }
        }


        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Add Product To Cart
        function addToCart(productId, unitId, productDetailId) {
            $.ajax({
                url: "{{ route('add-item') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "product_id":productId,
                    "unit_id":unitId,
                    "product_detail_id":productDetailId,
                    "quantity":1,
                },
                success: function(response) {
                    console.log(response);
                    $('#cart-product').empty();

                    $.each(response.data, function(index, cart) {
                        var addList = `<tr class="table-cart">`+
                                            `<td>`+
                                                `<div class="d-flex justify-content-between">`+
                                                    `<div class="">`+
                                                        `<p class="p-0 m-0">`+
                                                            `${cart.name}`+
                                                        `</p>`+
                                                        `<small>Unit: ${cart.conditions}</small>`+
                                                    `</div>`+

                                                    `<div>`+
                                                        `<a href="/delete-item/${index}" class="" style="border-bottom: 1px dashed red;">`+
                                                            `<i class='bx bx-trash font-14 text-danger'></i>`+
                                                        `</a>`+
                                                    `</div>`+
                                                `</div>`+
                                            `</td>`+
                                            `<td>${cart.quantity}</td>`+
                                            `<input type="hidden" name="qty[]" id="quantityInput" class="form-control qty" min="0"  value="${cart.quantity}">`+
                                            `<td>Rp.${numberFormat(cart.attributes['product_unit']['sale_price'])}</td>`+
                                        `</tr>`;

                        $('#cart-product').append(addList);
                    });

                    $('#subtotal-cart').text(`Rp.${formatRupiah(response.subtotal)}`)
                    $('#tax-cart').text(`Rp.${formatRupiah(response.tax)}`)
                    $('#total-cart').text(`Rp.${formatRupiah(response.total)}`)
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        // Void cart
        function voidCart() {
            $.confirm({
                title: `Void Cart?`,
                content: `Are you sure want to void cart`,
                autoClose: 'cancel|8000',
                buttons: {
                    delete: {
                        text: 'yes',
                        action: function () {
                            $.ajax({
                                url: "{{ route('void-cart') }}",
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function(response) {
                                    $('#cart-product').empty();

                                    var addList = `<tr class="table-cart">`+
                                                        `<td colspan="3" class="text-center">No products added</td>`+
                                                    `</tr>`;

                                    $('#cart-product').append(addList);

                                },
                                error: function(xhr, status, error) {
                                    console.error('Failed to load Product: ', error);
                                }
                            });
                        }
                    },
                    cancel: function () {

                    }
                }
            });
        }

        $(document).ready(function() {
            var typingTimer;
            var doneTypingInterval = 1000;

            $('.barcode').on('keydown', function() {
                clearTimeout(typingTimer);
            });

            $('.barcode').keyup(function(event) {
                var barcode = $(this).val().trim();

                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    addToCartBarcode(barcode); // Panggil addToCartBarcode setelah selesai mengetik dan sebelum menghapus
                    clearBarcodeInput()
                }, doneTypingInterval);
            });

            function clearBarcodeInput() {
                $('.barcode').val('');
            }
        });


        function addToCartBarcode(barcode) {
            console.log(barcode);
            $.ajax({
                url: "{{ route('add-item-barcode') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "barcode":barcode,
                    "sku":barcode,
                    "quantity":1,
                },
                success: function(response) {
                    $('#cart-product').empty();

                    $.each(response.data, function(index, cart) {
                        var addList = `<tr class="table-cart">`+
                                            `<td>`+
                                                `<div class="d-flex justify-content-between">`+
                                                    `<div class="">`+
                                                        `<p class="p-0 m-0">`+
                                                            `${cart.name}`+
                                                        `</p>`+
                                                        `<small>Unit: ${cart.conditions}</small>`+
                                                    `</div>`+

                                                    `<div>`+
                                                        `<a href="/delete-item/${index}" class="" style="border-bottom: 1px dashed red;">`+
                                                            `<i class='bx bx-trash font-14 text-danger'></i>`+
                                                        `</a>`+
                                                    `</div>`+
                                                `</div>`+
                                            `</td>`+
                                            `<td>${cart.quantity}</td>`+
                                            `<input type="hidden" name="qty[]" id="quantityInput" class="form-control qty" min="0"  value="${cart.quantity}">`+
                                            `<td>Rp.${numberFormat(cart.attributes['product_unit']['sale_price'])}</td>`+
                                        `</tr>`;

                        $('#cart-product').append(addList);
                    });

                    $('#subtotal-cart').text(`Rp.${response.subtotal}`)
                    $('#tax-cart').text(`Rp.${response.tax}`)
                    $('#total-cart').text(`Rp.${response.total}`)
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        // Input Rupiah
        function formatRupiah(angka) {
            var numberString = angka.toString().replace(/\D/g, '');
            var ribuan = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return ribuan;
        }

        function handleInput(inputId) {
            var inputField = $('#' + inputId);
            var input = inputField.val().replace(/\D/g, '');
            var formattedInput = formatRupiah(input);
            inputField.val(formattedInput);
        }

        function extractNumericValue(id) {
            var currencyText = $(`${id}`).text();
            // Menghapus karakter 'Rp.' dan titik dari teks subtotal
            var valueWithoutCurrency = currencyText.replace('Rp.', '').replace('.', '');

            // Mengonversi teks ke angka
            var numericValue = parseInt(valueWithoutCurrency);

            return numericValue;
        }

        // Discount update
        function updateDiscountInCart(typeDiscount, discountPrice, discountPercent,ongkirPrice) {
            $.ajax({
                url: "{{ route('update-cart-by-discount') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "discount_price":discountPrice,
                    "discount_percent":discountPercent,
                    "discount_type":typeDiscount,
                    "ongkir_price":ongkirPrice,
                },
                success: function(response) {
                    console.log(response);
                    $('#subtotal-cart').text(`Rp.${formatRupiah(response.subtotal)}`)
                    $('#tax-cart').text(`Rp.${formatRupiah(response.tax)}`)
                    $('#total-cart').text(`Rp.${formatRupiah(response.total)}`)
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        // Ongkir update
        function updateOngkirInCart(ongkirPrice,discountPrice,discountPercent,discountType) {
            $.ajax({
                url: "{{ route('update-cart-ongkir') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "ongkir_price":ongkirPrice,
                    "discount_price":discountPrice,
                    "discount_percent":discountPercent,
                    "discount_type":discountType,
                },
                success: function(response) {
                    console.log(response);
                    $('#subtotal-cart').text(`Rp.${formatRupiah(response.subtotal)}`)
                    $('#tax-cart').text(`Rp.${formatRupiah(response.tax)}`)
                    $('#total-cart').text(`Rp.${formatRupiah(response.total)}`)
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }

        // My Order
        function modalMyOrder()
        {
            var getTarget = `#modal-my-order`;
            $.ajax({
                url: "{{ route('modal-my-order') }}" ,
                type: 'GET',
                success: function(data) {
                    $('#modalContainer').html(data);
                    $(`${getTarget}`).modal('show');
                    $(`${getTarget}`).on('shown.bs.modal', function () {
                        $(document).on('click', '.select-customer', function() {
                            // $('#modal-my-order').modal('hide');
                        });
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load Product: ', error);
                }
            });
        }
    </script>
</body>

</html>
