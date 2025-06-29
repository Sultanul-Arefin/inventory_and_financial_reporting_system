<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('saleItems.product')->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'discount' => 'nullable|numeric',
            'vat_percent' => 'nullable|numeric',
            'amount_paid' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $items = [];

            foreach ($request->product_id as $index => $productId) {
                $product = Product::findOrFail($productId);
                $qty = $request->quantity[$index];

                if ($product->stock < $qty) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $subtotal = $product->sell_price * $qty;
                $total += $subtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $product->sell_price,
                    'subtotal' => $subtotal,
                ];
            }

            $discount = $request->discount ?? 0;
            $totalAfterDiscount = $total - $discount;

            $vatPercent = $request->vat_percent ?? 0;
            $vatAmount = $totalAfterDiscount * ($vatPercent / 100);
            $finalTotal = $totalAfterDiscount + $vatAmount;

            $amountPaid = $request->amount_paid;
            $dueAmount = $finalTotal - $amountPaid;

            // Create sale
            $sale = Sale::create([
                'total_amount' => $finalTotal,
                'discount' => $discount,
                'vat' => $vatAmount,
                'amount_paid' => $amountPaid,
                'due_amount' => $dueAmount,
            ]);

            foreach ($items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce stock
                $item['product']->decrement('stock', $item['quantity']);
            }

            // Journal Entries
            JournalEntry::create(['date' => now(), 'type' => 'sales', 'amount' => $total, 'sale_id' => $sale->id]);
            if ($discount > 0) {
                JournalEntry::create(['date' => now(), 'type' => 'discount', 'amount' => $discount, 'sale_id' => $sale->id]);
            }
            if ($vatAmount > 0) {
                JournalEntry::create(['date' => now(), 'type' => 'vat', 'amount' => $vatAmount, 'sale_id' => $sale->id]);
            }
            JournalEntry::create(['date' => now(), 'type' => 'payment', 'amount' => $amountPaid, 'sale_id' => $sale->id]);

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Sale completed');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
