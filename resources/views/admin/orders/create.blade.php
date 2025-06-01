@extends('admin.__layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>{{ $title }}</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">{{ $subtitle ?? '' }}</h3>
                            <div class="card-toolbar float-right">
                                <a href="{{ route($ROUTE_PATH.'index') }}" class="btn btn-sm btn-danger">
                                    {{ __('button.back') }}
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            {{-- saat ada action benar atau salah --}}
                            @include('admin.__components.alert_session')
                            <form action="{{ route($ROUTE_PATH.'store') }}" method="POST" id="transactionForm">
                                @csrf
                                @method('post')

                                <div class="form-group mb-3">
                                    <label for="customer_id" class="form-label">
                                        {{ __($LANG_PATH.'form.customer_id.title') }}<span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" id="customer_id" name="customer_id" required>
                                        <option value="">{{ __($LANG_PATH.'form.customer_id.placeholder') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer['id'] }}" {{ old('customer_id') == $customer['id'] ? 'selected' : '' }}>{{ $customer['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <span class="text-danger ml-2" style="font-size:14px;">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="product_id" class="form-label">
                                        {{ __('Product') }}<span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select2" id="product_id">
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product['id'] }}"
                                                data-name="{{ $product['name'] }}"
                                                data-stock="{{ $product['stock'] }}"
                                                data-price="{{ $product['price'] }}">
                                                {{ $product['name'] }} (Stock: {{ $product['stock'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="discount_percentage" class="form-label">
                                        {{ __('Discount Percentage') }}<span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" min="0" max="100" value="0" required>
                                    @error('discount_percentage')
                                        <span class="text-danger ml-2" style="font-size:14px;">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="note" class="form-label">
                                        {{ __('Note') }}
                                    </label>
                                    <textarea class="form-control" id="note" name="note" rows="3" maxlength="500" placeholder="{{ __('Enter note here (optional)') }}"></textarea>
                                    @error('note')
                                        <span class="text-danger ml-2" style="font-size:14px;">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered" id="productTable">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Stock</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right"><strong>Total Without Discount:</strong></td>
                                                <td colspan="2"><strong id="totalWithoutDiscount">0</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right"><strong>Total Discount:</strong></td>
                                                <td colspan="2"><strong id="totalDiscount">0</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right"><strong>Amount/Grand Total:</strong></td>
                                                <td colspan="2"><strong id="grandTotal">0</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary m-2 col-12">
                                    {{ __('button.save') }}
                                </button>
                            </form>
                        </div>

                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('before_styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin-template') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('admin-template') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@push('before_scripts')
    <!-- Select2 -->
    <script src="{{ asset('admin-template') }}/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(function(){
            //init select2
            $('.select2').select2();

            let selectedProducts = new Map();

            function clearProductSelection() {
                selectedProducts.clear();
                $('#productTable tbody').empty();
                updateGrandTotal();
            }

            $('#product_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const productId = $(this).val();

                if (!productId) return;

                const productName = selectedOption.data('name');
                const stock = selectedOption.data('stock');
                const price = selectedOption.data('price');

                // Check if product exists in table instead of Map
                if ($(`#productTable tbody tr[data-product-id="${productId}"]`).length > 0) {
                    alert('Product already selected!');
                    $(this).val('').trigger('change');
                    return;
                }

                const row = `
                    <tr data-product-id="${productId}">
                        <td>${productName}</td>
                        <td>${stock}</td>
                        <td>${price}</td>
                        <td>
                            <input type="number" class="form-control qty-input"
                                min="1" max="${stock}" value="1"
                                data-price="${price}" data-stock="${stock}">
                        </td>
                        <td class="item-total">${price}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#productTable tbody').append(row);
                selectedProducts.set(productId, {
                    name: productName,
                    stock: stock,
                    price: price
                });

                $(this).val('').trigger('change');
                updateGrandTotal();
            });

            $(document).on('change', '.qty-input', function() {
                const qty = parseInt($(this).val());
                const price = $(this).data('price');
                const stock = $(this).data('stock');

                // Enforce minimum of 1
                if (qty < 1) {
                    $(this).val(1);
                    return;
                }

                // Check if quantity exceeds stock
                if (qty > stock) {
                    alert(`Stock tidak mencukupi! Maksimal stock yang tersedia: ${stock}`);
                    $(this).val(stock);
                    return;
                }

                const total = qty * price;
                $(this).closest('tr').find('.item-total').text(total);
                updateGrandTotal();
            });

            $(document).on('click', '.remove-item', function() {
                const productId = $(this).closest('tr').data('product-id');
                // Remove the specific product from tracking
                selectedProducts.delete(productId);
                // Remove the specific row from table
                $(this).closest('tr').remove();

                // If table is empty, clear all tracking
                if ($('#productTable tbody tr').length === 0) {
                    clearProductSelection();
                }

                updateGrandTotal();
            });

            function updateGrandTotal() {
                let totalWithoutDiscount = 0;
                $('.item-total').each(function() {
                    totalWithoutDiscount += parseFloat($(this).text());
                });

                const discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
                const totalDiscount = (totalWithoutDiscount * discountPercentage) / 100;
                const grandTotal = totalWithoutDiscount - totalDiscount;

                $('#totalWithoutDiscount').text(totalWithoutDiscount);
                $('#totalDiscount').text(totalDiscount);
                $('#grandTotal').text(grandTotal);
            }

            // Add event listener for discount percentage changes
            $('#discount_percentage').on('change', function() {
                const value = parseFloat($(this).val());
                if (value < 0) {
                    $(this).val(0);
                } else if (value > 100) {
                    $(this).val(100);
                }
                updateGrandTotal();
            });

            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();

                const items = [];
                $('#productTable tbody tr').each(function() {
                    const productId = $(this).data('product-id');
                    const qty = $(this).find('.qty-input').val();

                    items.push({
                        product_id: productId,
                        qty: qty
                    });
                });

                // Add items to form data
                const formData = new FormData(this);
                formData.append('items', JSON.stringify(items));

                // Submit form
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = '{{ route($ROUTE_PATH."index") }}';
                        }
                    },
                    error: function(xhr) {
                        alert('Error occurred while saving transaction');
                    }
                });
            });
        });
    </script>
@endpush
