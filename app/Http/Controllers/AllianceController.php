<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Agreement;

class AllianceController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->get();
        $agreements = Agreement::with('partner')->latest()->get();

        return view('alliances.index', compact('partners', 'agreements'));
    }
}
