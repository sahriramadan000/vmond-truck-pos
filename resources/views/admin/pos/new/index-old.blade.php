<!doctype html>
<html lang="en">

<head>
	@include('layouts.head')

    <style>
        .custom-table th{
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            background: #297dc1;
        }
        .custom-table th:first-child{
            border-radius: 10px 0 0 10px;
        }
        .custom-table th:last-child{
            border-radius: 0 10px 10px 0;
        }
        .costum-form {
            border: 0;
            border-radius: 0;
            border-bottom: 1px solid #e2e2e2;
        }
        tr td:first-child{
            text-align: center;
        }
        tr td{
            vertical-align: middle;
        }
        .custom-height{
            height: 80vh;
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
					<div class="col-12 col-lg-3">
						<div class="card">
							<div class="card-body">
								<h5 class="text-center">Menu</h5>
								<div class="fm-menu">
									<div class="list-group list-group-flush">
                                        <a href="javascript:;" class="list-group-item py-1"><i class='bx bx-folder me-2'></i><span>Dashboard</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class='bx bx-devices me-2'></i><span>Report</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class='bx bx-analyse me-2'></i><span>Daftar Transaction</span></a>
										<a href="javascript:;" class="list-group-item py-1"><i class='bx bx-analyse me-2'></i><span>Transction</span></a>
									</div>
								</div>
							</div>
						</div>
							<div class="card">
							<div class="card-body">
								<p class="mb-0 mt-2"><span class="text-secondary">SUB TOTAL</span><span class="float-end text-primary">Rp.{{ number_format(\Cart::getTotal()  ?? '0',0 ) }}</span>
								@if ($other_setting->pb01 != 0)
									<?php
										$ppn = ((\Cart::getTotal() ?? '0')) * $other_setting->pb01/100;
										$total_payment = (\Cart::getTotal() ?? '0') + $ppn;
									?>
									<p class="mb-0 mt-2"><span class="text-secondary">PPN ({{ $other_setting->pb01 }}%)</span><span class="float-end text-primary">Rp.{{ number_format($ppn,0) }}</span>
									<h5 class="mb-0 mt-2 text-primary font-weight-bold">TOTAL PAYMENT <span class="float-end text-secondary">Rp.{{ number_format($total_payment,0 ) }}</span></h5>
								@else
									<h5 class="mb-0 mt-2 text-primary font-weight-bold">TOTAL PAYMENT <span class="float-end text-secondary">Rp.{{ number_format(\Cart::getTotal()  ?? '0',0 ) }}</span></h5>
								@endif
								</p>
								<div class="progress mt-3" style="height:7px;">
									<div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="mt-3"></div>
								<div class="d-flex align-items-center">
									<div class="fm-file-box bg-light-success text-success"><i class='bx bx-user'></i>
									</div>
									<div class="flex-grow-1 ms-2">
										<h6 class="mb-0">Customer</h6>
										<div class="form-group mb-3">
											<input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Cust" id="code" aria-describedby="name">
										</div>
									</div>
								</div>
								<div class="d-flex align-items-center mt-3">
									<div class="fm-file-box bg-light-primary text-primary"><i class='bx bx-credit-card'></i>
									</div>
									<div class="flex-grow-1 ms-2">
										<h6 class="mb-0">Metode Payment</h6>
										<select name="metode_pembayaran" id="" class="form-control form-control-sm">
											<option selected value="Transfer Bank">Transfer Bank</option>
											<option value="EDC BCA">EDC BCA</option>
											<option value="EDC BRI">EDC BRI</option>
											<option value="EDC BNI">EDC BNI</option>
											<option value="Qris">Qris</option>
										</select>
									</div>
								</div>
								<div class="d-grid mt-3">
									<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Charge</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-9">
						<div class="card">
							<div class="card-body">
								<form action="{{ route('add-item') }}" method="get">
									<div class="row">
										<div class="fm-search col-lg-4">
											<div class="mb-0">
												<label for="">Product</label>
												<div class="input-group input-group-sm mt-2"><span class="input-group-text bg-transparent"><i class='bx bx-search'></i></span>
													<select name="product_id" class="form-select form-select-sm" id="single-select-field" data-placeholder="Choose one thing">
														<option></option>
														@foreach ($products as $product)
															<option value="{{ $product->id }}"
																{{ old('product_id') == $product->id ? 'selected' : '' }}>
																{{ $product->name }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group mb-3">
												<label for="quantity" class="form-label">Qty</label>
												<input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" placeholder="quantity" id="quantity" aria-describedby="quantity">
											</div>
										</div>
										<div class="col-lg-4 mt-4">
											<button type="submit" class="btn btn-sm btn-primary">
												Add Item
											</button>
										</div>
									</div>
								</form>
								<!--end row-->
								<form action="{{ route('checkout-order', md5(strtotime("now"))) }}" method="POST" class="">
									@csrf
									<div class="table-responsive mt-3 custom-height">
										<table class="table table-sm mb-0 custom-table">
											<thead>
												<tr>
													<th width="3%">No</th>
													<th>Name Product</th>
													<th>Quantity</th>
													<th>Price</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												@foreach ($data_items as $key => $item)
												<tr>
													<td>{{ $loop->iteration }}</td>
													<td class="font-weight-bold text-danger mt-2">{{ $item->attributes['product']['name'] }}</td>
													<td>{{ $item->quantity }}</td>
													<input type="hidden" name="qty[]" id="quantityInput" readonly class="min-width-40 flex-grow-0 border border-success text-success fs-4 fw-semibold form-control text-center qty" min="0" style="width: 15%"  value="{{ $item->quantity }}">
													<td>{{ $item->attributes['product']['selling_price'] }}</td>
													<td>
														<a href="{{ route('delete-item', $key)}}" class="">
															<i class='bx bx-trash font-24 text-danger'></i>
														</a>
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">Are You Sure You Want To Complete This Order</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary">Save changes</button>
												</div>
											</div>
										</div>
									</div>
								</form>
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
	@include('layouts.foot')
</body>

</html>
