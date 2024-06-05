<div class="modal fade" id="modal-add-to-cart-{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row my-3 px-3">
                    {{-- @foreach ($product as $item) --}}
                    {{-- @if ($product->current_stock > 0 && $product->status == true) --}}
                    {{-- <div class="col-12 col-md-4 text-center">
                        <a href="#" onclick="addToCart({{ $product->id }})" class="cursor-pointer text-dark">
                            <img src="{{ $product->picture ? asset('images/products/'.$product->picture) : 'https://ui-avatars.com/api/?name='. str_replace(' ', '+', $product->name ?? '') }}" class="card-img-top" alt="">
                            <p class="mb-0 fw-bold mt-1">{{ $product->name ?? '' }} </p>
                            <p class="mb-0 fw-bold mt-1"> ({{ $product->current_stock ?? 0 }} )</p>
                        </a>
                    </div> --}}
                    {{-- <small class="mb-0 fw-medium">Rp.{{ number_format($product->getSalePriceProductUnit($product->id,$product->unit_id), 0, ',', '.') }}</small> --}}
                    {{-- @endif --}}
                    {{-- @endforeach --}}

                    <div class="col-12 d-flex bd-highlight">
                        <div class="p-2 flex-shrink-1 bd-highlight w-50 text-center">
                            <img src="{{ $product->picture ? asset('images/products/'.$product->picture) : 'https://ui-avatars.com/api/?name='. str_replace(' ', '+', $product->name ?? '') }}" class="card-img-top" alt="">
                        </div>
                        <div class="p-2 w-100 bd-highlight">
                            <h4>{{ $product->name }}</h4>
                            <p style="text-align: justify !important;">{{ mb_strimwidth($product->description, 0, 150, "...") ?? '' }}</p>

                            <div class="row">
                                <div class="col-12">
                                    <p class="m-0 p-0">
                                        <i class='bx bxs-component me-1' style="font-size:1.2rem;"></i>
                                        Stock: <span id="current-stock-{{ $product->id }}">{{ $product->current_stock }}</span>
                                        @if (($product->current_stock ?? 0) <= 0)
                                            <small class="ms-2 p-1 badge badge-light-danger" style="font-size: 10px;">Out of Stock</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-12">
                                    @php
                                        $priceForPercent = $product->selling_price ?? 0;
                                        $priceAfterDiscount = $priceForPercent;
                                        $isDiscounted = false;

                                        if ($product->is_discount) {
                                            if ($product->price_discount && $product->price_discount > 0) {
                                                $priceAfterDiscount = $product->price_discount;
                                                $isDiscounted = true;
                                            } elseif ($product->percent_discount && $product->percent_discount > 0 && $product->percent_discount <= 100) {
                                                $discount_price = $priceForPercent * ($product->percent_discount / 100);
                                                $priceAfterDiscount = $priceForPercent - $discount_price;
                                                $isDiscounted = true;
                                            }
                                        }
                                    @endphp

                                    <p class="m-0 p-0">
                                        <i class='bx bx-dollar-circle me-1' style="font-size:1.2rem;"></i>
                                        Price: <span>Rp. {{ number_format($priceAfterDiscount, 0, ',', '.') }}</span>
                                        @if($isDiscounted)
                                            <small class="ms-2 text-danger">
                                                <del>Rp. {{ number_format($priceForPercent, 0, ',', '.') }}</del>
                                            </small>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3">

                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <div class="btn-group w-50">
                                    <button class="btn btn-default text-white" style="background: #0c0f1d !important;" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }}>-</button>
                                    <input type="number" name="qty" id="" class="qty-add form-control rounded-0 text-center p-1" value="1" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }}>
                                    <button class="btn btn-default text-white" style="background: #0c0f1d !important;" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }}>+</button>
                                </div>
                                <button type="button" class="btn btn-default bg-primary text-white" {{ (($product->current_stock ?? 0) <= 0) ? 'disabled' : '' }} onclick="addToCart({{ $product->id }})">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
