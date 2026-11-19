<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kerangka Login (implementasi penuh pada Fase 2).
 */
class AuthController extends Controller
{
    public function index(): View
    {
        return view('login');
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('notice', 'Masih dalam pengembangan');
    }
}
