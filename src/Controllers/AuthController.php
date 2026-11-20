<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Session;

/**
 * Controller Otentikasi / Login (Fase Placeholder).
 * Sesuai instruksi: "login biarin aja" (status Masih dalam pengembangan).
 */
class AuthController
{
    public function index(Request $request): Response
    {
        return Response::view('login', [
            'title' => 'Login Pengguna / Member',
            'notice' => Session::getFlash('notice', 'Masih dalam pengembangan'),
        ]);
    }

    public function store(Request $request): Response
    {
        Session::flash('notice', 'Masih dalam pengembangan');
        return Response::redirect('/login');
    }
}
