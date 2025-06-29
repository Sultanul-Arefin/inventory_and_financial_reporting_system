@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Create Sale</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        <div id="product-list">
            <div class="row mb-3 product-row">
                <div class="col-md-5">
                    <label>Product</label>
                    <select name="product_id[]" class="form-select" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->stock }} in stock)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-row">X</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-row">+ Add Product</button>

        <div class="mb-3">
            <label>Discount (TK)</label>
            <input type="number" name="discount" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>VAT (%)</label>
            <input type="number" name="vat_percent" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>Amount Paid (TK)</label>
            <input type="number" name="amount_paid" class="form-control" required>
        </div>

        <button class="btn btn-success">Submit Sale</button>
    </form>
</div>

<script>
    document.getElementById('add-row').addEventListener('click', function () {
        const productRow = document.querySelector('.product-row').cloneNode(true);
        productRow.querySelector('select').value = '';
        productRow.querySelector('input').value = '';
        document.getElementById('product-list').appendChild(productRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            if (document.querySelectorAll('.product-row').length > 1) {
                e.target.closest('.product-row').remove();
            }
        }
    });
</script>
@endsection