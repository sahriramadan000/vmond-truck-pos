<div class="modal fade modal-notification" id="tabs-add-coupon" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('coupons.store') }}" method="post" class="modal-content">
        @csrf
        @method('POST')
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="icon-content m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>

                <div class="text-center mb-3 mt-3">
                    <h4 class="mb-0">ADD COUPON</h4>
                </div>

                <div class="mt-0 row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="code">Code</label>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="Ex:CPN00001" aria-label="code" id="code" value="{{ $code ?? old('code') }}" readonly>

                            @if($errors->has('code'))
                                <p class="text-danger">{{ $errors->first('code') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ex:Happy New Year" aria-label="name" id="name" value="{{ old('name') }}">

                            @if($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="type">Type</label>
                            <select class="form-control form-control-sm" name="type" id="type">
                                <option selected value="Percentage Discount" {{ (old('type') == 'Percentage Discount') ? 'selected' : '' }}>Percentage Discount</option>
                                <option value="Flat Discount" {{ (old('type') == 'Flat Discount') ? 'selected' : '' }}>Flat Discount</option>
                            </select>
                            @if($errors->has('type'))
                                <p class="text-danger">{{ $errors->first('type') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="discount_value" class="text-white" style="opacity: .8;">Discount Value</label>
                        <input type="text" name="discount_value" id="discount_value" class="form-control form-control-sm" aria-label="Discount Cart" placeholder="Ex:10.000 / 10%" value="{{ old('discount_value') }}">

                        @if($errors->has('discount_value'))
                            <p class="text-danger">{{ $errors->first('discount_value') }}</p>
                        @endif
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="minimum_cart" class="text-white" style="opacity: .8;">Minimum Cart</label>
                        <div class="input-group">
                            <span class="input-group-text" id="group-rp">Rp</span>
                            <input type="text" name="minimum_cart" id="minimum_cart" class="form-control form-control-sm" aria-label="Minimum Cart" placeholder="Ex:10.000" value="{{ old('minimum_cart') }}">
                        </div>

                        @if($errors->has('minimum_cart'))
                            <p class="text-danger">{{ $errors->first('minimum_cart') }}</p>
                        @endif
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="expired_at">Expired</label>
                            <input type="date" name="expired_at" class="form-control form-control-sm" placeholder="Ex:10-10-2024" aria-label="expired_at" id="expired_at" value="{{ old('expired_at') }}">

                            @if($errors->has('expired_at'))
                                <p class="text-danger">{{ $errors->first('expired_at') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="limit_usage">Limit Usage</label>
                            <input type="number" name="limit_usage" class="form-control form-control-sm" min="0" placeholder="Ex:10" aria-label="limit_usage" id="limit_usage" value="{{ old('limit_usage') }}">

                            @if($errors->has('limit_usage'))
                                <p class="text-danger">{{ $errors->first('limit_usage') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control form-control-sm" name="status" id="status">
                                <option selected value="true">Active</option>
                                <option value="false">Inactive</option>
                            </select>
                            @if($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

