$(document).ready(function() {
    loadTags();
});

function loadTags(url = '/get-tag') {
    $('#productContainer').html('<p class="text-center">Waiting...</p>');
    $('#tagNavigation').empty();

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            renderTags(response);
        },
        error: function(xhr, status, error) {
            console.error('Failed to load tags: ', error);
        }
    });
}

function loadProducts(categoryId, categoryName, url = '/get-product') {
    $('#productContainer').html('<p class="text-center">Waiting...</p>');
    $('#tagNavigation').append(`${categoryName}`);

    $.ajax({
        url: url +'/'+ categoryId,
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

function renderTags(tags) {
    const productContainer = $('#productContainer');

    productContainer.empty();
    console.log(tags);
    if (tags.length > 0) {
        $.each(tags, function(index, tag) {
            const tagDiv = $('<div class="col-12 col-md-3 text-center"></div>');

            const tagLink = $(`<a href="#!" onclick="loadProducts(${tag.id}, '${tag.name}');" class="cursor-pointer text-dark"></a>`);

            const tagImg = $('<img class="card-img-top" alt="">').attr('src',  'https://ui-avatars.com/api/?name=' + tag.name.replace(' ', '+'));

            const tagName = $('<p class="mb-0 fw-bold mt-1"></p>').text(tag.name);

            tagLink.append(tagImg, tagName);
            tagDiv.append(tagLink);
            productContainer.append(tagDiv);
        });
    } else {
        const noTagDiv = $('<div class="col-12 text-center"></div>');
        const noTagHeader = $('<h3>No Tag Added</h3>');

        noTagDiv.append(noTagHeader);
        productContainer.append(noTagDiv);
    }
}

function renderProducts(products) {
    const productContainer = $('#productContainer');
    productContainer.empty();

    if (products.length > 0) {
        $.each(products, function(index, product) {
            const productDiv = $('<div class="col-12 col-md-3 text-center"></div>');

            const productLink = $(`<a href="#!" onclick="ModalAddToCart(${product.id})" class="cursor-pointer text-dark" data-bs-target="#modal-add-to-cart-${product.id}"></a>`);

            const productImg = $('<img class="card-img-top" alt="">').attr('src', product.picture ? 'images/products/' + product.picture : 'https://ui-avatars.com/api/?name=' + product.name.replace(' ', '+'));

            const productName = $('<p class="mb-0 fw-bold mt-1"></p>').text(product.name);

            // Check jika semua detail produk terjual
            // const allDetailsSold = product.product_detail.every(function(detail) {
            //     return detail.quantity <= 0;
            // });

            // productLink.append(productImg, productName, productPrice);
            productLink.append(productImg, productName);
            productDiv.append(productLink);
            // if (allDetailsSold) {
            //     // Jika semua detail produk terjual, tambahkan tag <p> untuk menampilkan informasi "Sold"
            //     const productSoldInfo = $('<p class="text-muted mb-0">Sold</p>');
            //     productDiv.append(productSoldInfo);
            // }
            productContainer.append(productDiv);
        });
    } else {
        const noProductDiv = $('<div class="col-12 text-center"></div>');
        const noProductHeader = $('<h3>No Product Added</h3>');

        noProductDiv.append(noProductHeader);
        productContainer.append(noProductDiv);
    }
}

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
        theme: 'dark',
        buttons: {
            cancel: {
                text: 'CANCEL',
                btnClass: 'btn-danger'
            },
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

function ModalSearch(url) {
    var getTarget = `#modal-search-product`;
    $.ajax({
        url: url ,
        type: 'GET',
        success: function(data) {
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

function ModalAddToCart(productId, url = '/modal-add-cart') {
    var getTarget = `#modal-add-to-cart-${productId}`;
    $.ajax({
        url: url + '/' +  productId,
        type: 'GET',
        success: function(data) {
            $('#modalContainer').html(data);
            $(`${getTarget}`).modal('show');
            $(`${getTarget}`).on('shown.bs.modal', function () {
                // Fungsi untuk menangani penambahan nilai ketika tombol + ditekan
                $(".btn-group .btn:last-child").on("click", function() {
                    var input = $(this).siblings("input[type='number']");
                    var value = parseInt(input.val());
                    var currentStock = parseInt($("#current-stock-" + productId).text());

                    if (value < currentStock) {
                        input.val(value + 1);
                    } else {
                        alert("Stock tidak cukup");
                    }
                });

                // Fungsi untuk menangani pengurangan nilai ketika tombol - ditekan
                $(".btn-group .btn:first-child").on("click", function() {
                    var input = $(this).siblings("input[type='number']");
                    var value = parseInt(input.val());
                    if (value > 0) {
                        input.val(value - 1);
                    }
                });

                // Memastikan nilai input tidak kurang dari 0
                $(".qty-add").on("change", function() {
                    if ($(this).val() < 0) {
                        $(this).val(0);
                    } else {
                        var currentStock = parseInt($("#current-stock-" + productId).text());
                        if (parseInt($(this).val()) > currentStock) {
                            alert("Stock tidak cukup");
                            $(this).val(currentStock);
                        }
                    }
                });
            });
        },
        error: function(xhr, status, error) {
            console.error('Failed to load Product: ', error);
        }
    });
}

function ModalAddCustomer(url) {
    var getTarget = `#modal-add-customer`;
    $.ajax({
        url: url ,
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

function ModalAddCoupon(url) {
    var getTarget = `#modal-add-coupon`;
    $.ajax({
        url: url,
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


function ModalAddDiscount(url) {
    var getTarget = `#modal-add-discount`;
    $.ajax({
        url: url ,
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

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Add Product To Cart
function addToCart(productId) {
    $.ajax({
        url: "{{ route('add-item') }}",
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "_token": "{{ csrf_token() }}",
            "product_id":productId,
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
        theme: 'dark',
        autoClose: 'cancel|8000',
        buttons: {
            cancel: {
                text: 'CANCEL',
                btnClass: 'btn-danger'
            },
            delete: {
                text: 'yes',
                btnClass: 'btn-primary',
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
function modalMyOrder(url)
{
    var getTarget = `#modal-my-order`;
    $.ajax({
        url: url ,
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
