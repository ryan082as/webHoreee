<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Session;

/**
 * Controller Langganan (Fase Placeholder).
 * Sesuai instruksi: "langganan biarin aja" (status Masih dalam pengembangan).
 */
class SubscriptionController
{
    public function index(Request $request): Response
    {
        return Response::view('langganan', [
            'title' => 'Paket Langganan',
            'notice' => Session::getFlash('notice', 'Masih dalam pengembangan'),
        ]);
    }

    public function store(Request $request): Response
    {
        Session::flash('notice', 'Masih dalam pengembangan');
        return Response::redirect('/langganan');
    }
}
