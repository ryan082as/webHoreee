<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kerangka Langganan (implementasi penuh pada Fase 3).
 */
class SubscriptionController extends Controller
{
    public function index(): View
    {
        return view('langganan');
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('notice', 'Masih dalam pengembangan');
    }
}
