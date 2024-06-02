<div class="modal fade" id="modal-add-to-cart-{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $product->name ?? '' }} : Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="row mt-3 my-product">
                    @foreach ($product->productDetail as $item)
                    @if ($item->quantity > 0 && $item->status != 'sold')
                    <div class="col-12 col-md-4 text-center">
                        <a href="#" onclick="addToCart({{ $product->id }} ,{{ $item->unit_id }},{{ $item->id }} )" class="cursor-pointer text-dark">
                            <img src="{{ $product->picture ? asset('assets/images/product/'.$product->picture) : 'https://ui-avatars.com/api/?name='. str_replace(' ', '+', $item->unitProduct->name ?? '') }}" class="card-img-top" alt="">
                            <p class="mb-0 fw-bold mt-1">{{ $product->name ?? '' }} </p>
                            <p class="mb-0 fw-bold mt-1">{{ date('Y-m-d', strtotime($item->expired_date)) ?? '' }}</p>
                            <p class="mb-0 fw-bold mt-1">{{ $item->unitProduct->name ?? '' }} ({{ $item->quantity ?? 0 }} )</p>
                            <small class="mb-0 fw-medium">Rp.{{ number_format($product->getSalePriceProductUnit($product->id,$item->unit_id), 0, ',', '.') }}</small>
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
