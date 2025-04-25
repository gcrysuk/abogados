<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('usuario')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión');
        }

        // Verificación de roles si se necesitan permisos específicos
        if (!empty($arguments)) {
            $rolUsuario = session('usuario.rol');
            if (!in_array($rolUsuario, $arguments)) {
                return redirect()->back()->with('error', 'No tienes permisos para esta acción');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No es necesario hacer nada después
    }
}
