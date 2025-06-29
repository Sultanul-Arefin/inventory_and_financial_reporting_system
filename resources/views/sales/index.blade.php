@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Sales List</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">+ Create Sale</a>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#ID</th>
                <th>Items</th>
                <th>Total</th>
                <th>Discount</th>
                <th>VAT</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>
                    <ul class="mb-0">
                        @foreach($sale->saleItems as $item)
                            <li>{{ $item->product->name }} x {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ number_format($sale->total_amount, 2) }} TK</td>
                <td>{{ number_format($sale->discount, 2) }} TK</td>
                <td>{{ number_format($sale->vat, 2) }} TK</td>
                <td>{{ number_format($sale->amount_paid, 2) }} TK</td>
                <td>{{ number_format($sale->due_amount, 2) }} TK</td>
                <td>{{ $sale->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No sales yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
