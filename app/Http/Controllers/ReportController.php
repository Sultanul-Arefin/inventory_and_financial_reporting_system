<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $journals = JournalEntry::whereBetween('date', [$from, $to])->get();

        $totals = [
            'sales' => $journals->where('type', 'sales')->sum('amount'),
            'discount' => $journals->where('type', 'discount')->sum('amount'),
            'vat' => $journals->where('type', 'vat')->sum('amount'),
            'payment' => $journals->where('type', 'payment')->sum('amount'),
        ];

        $totals['profit'] = $totals['sales'] - $totals['discount'];

        return view('reports.index', compact('from', 'to', 'totals'));
    }
}
