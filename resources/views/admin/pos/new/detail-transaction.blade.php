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
										<a href="{{ route('detail-transaction') }}" class="list-group-item py-1"><i class='bx bx-analyse me-2'></i><span>Transction</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <div class="col-lg-9">
                        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                            <div class="breadcrumb-title pe-3">Detail Transaction</div>
                            <div class="ps-3">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 p-0">
                                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-lg-flex align-items-center mb-4 gap-3">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-5 radius-30" placeholder="Search Order"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order#</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>View Details</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-2">
                                                                <h6 class="mb-0 font-14">#{{ ($item->invoice_no) }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class='bx bxs-circle me-1'></i>{{ $item->status_pembayaran }}</div></td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>Rp.{{ number_format($item->total_price,0) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDetail-{{ $item->id }}">Detail</button>
                                                    </td>
                                                </tr>
                                                @foreach ($item->orderDetail as $order_pivot)
                                                            <div class="modal fade" id="modalDetail-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <li>{{ $order_pivot->product->name }}</li>
                                                                            <li>Rp. {{ number_format($order_pivot->price_discount * $order_pivot->qty,0)  }}</li>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                            <button type="button" class="btn btn-primary">Save changes</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
	<!--end switcher-->
	@include('layouts.foot')
</body>

</html>
