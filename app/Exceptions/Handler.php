<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    /**
     * Personaliza o comportamento quando a autenticação falha.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Se a requisição for API (JSON), devolve erro 401 em vez de redirecionar.
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json([
                'message' => 'Não autenticado. Token ausente ou inválido.'
            ], 401);
        }

        // Caso contrário, redireciona apenas se houver rota web configurada
        return redirect('/login');
    }
}
