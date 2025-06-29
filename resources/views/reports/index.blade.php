@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Financial Report</h4>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>From</label>
            <input type="date" name="from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-4">
            <label>To</label>
            <input type="date" name="to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="row text-center">
        <div class="col-md-3">
            <div class="border p-3">
                <h6>Total Sales</h6>
                <p class="fs-5 text-success">{{ number_format($totals['sales'], 2) }} TK</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border p-3">
                <h6>Total Discount</h6>
                <p class="fs-5 text-danger">{{ number_format($totals['discount'], 2) }} TK</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border p-3">
                <h6>VAT Collected</h6>
                <p class="fs-5 text-info">{{ number_format($totals['vat'], 2) }} TK</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border p-3">
                <h6>Net Profit</h6>
                <p class="fs-5 fw-bold">{{ number_format($totals['profit'], 2) }} TK</p>
            </div>
        </div>
    </div>
</div>
@endsection
