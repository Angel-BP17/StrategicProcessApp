<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Partner;

class AllianceController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->get();
        $agreements = Agreement::with('partner')->latest()->get();

        return response()->json([
            'partners' => $partners,
            'agreements' => $agreements,
        ]);
    }
}
