<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;
use Config\Services;

class AuthController extends BaseController
{
    use ResponseTrait;

    protected $model;
    protected $helpers = ['form'];
    protected $db;

    public function __construct()
    {
        $this->model = new UsuariosModel();
        $this->db = \Config\Database::connect();
        helper(['form', 'url', 'text']);
    }

    /**
     * Muestra el formulario de login
     */
    public function login()
    {
        // Redirigir si ya está autenticado
        if (session()->has('usuario')) {
            return redirect()->to($this->getRedirectUrl());
        }

        $data = [
            'title' => 'Iniciar Sesión',
            'validation' => Services::validation()
        ];

        return view('auth/login', $data);
    }

    /**
     * Procesa el formulario de login
     */
    public function procesarLogin()
    {
        $rules = [
            'username' => 'required|min_length[4]|max_length[50]',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $usuario = $this->model->verificarCredenciales($username, $password);

        if (!$usuario) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Credenciales incorrectas o usuario inactivo');
        }

        $this->crearSesion($usuario);
        $this->registrarIntentoLogin($usuario->id, true);

        return redirect()->to($this->getRedirectUrl())
            ->with('success', 'Bienvenido ' . $usuario->nombre);
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout()
    {
        $this->registrarLogout(session('usuario.id'));
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Muestra formulario de recuperación de contraseña
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Recuperar Contraseña',
            'validation' => Services::validation()
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Procesa el formulario de recuperación
     */
    public function procesarRecuperacion()
    {
        $rules = ['email' => 'required|valid_email|max_length[100]'];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $usuario = $this->model->where('mail', $email)->first();

        if ($usuario) {
            $token = bin2hex(random_bytes(32));
            $this->guardarTokenRecuperacion($usuario->id, $token);

            if ($this->enviarEmailRecuperacion($usuario->mail, $token)) {
                return redirect()->to('/login')
                    ->with('success', 'Se han enviado instrucciones a tu email');
            }

            return redirect()->back()
                ->with('error', 'Error al enviar el correo. Intenta nuevamente.');
        }

        return redirect()->to('/login')
            ->with('success', 'Si el email existe en nuestro sistema, recibirás instrucciones');
    }

    /**
     * Muestra formulario para restablecer contraseña
     */
    public function resetPassword($token = null)
    {
        if (!$token || !$this->validarTokenRecuperacion($token)) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Token inválido o expirado');
        }

        $data = [
            'title' => 'Restablecer Contraseña',
            'token' => $token,
            'validation' => Services::validation()
        ];

        return view('auth/reset_password', $data);
    }

    /**
     * Procesa el restablecimiento de contraseña
     */
    public function procesarResetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]|strong_password',
            'pass_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $tokenData = $this->validarTokenRecuperacion($token);

        if (!$tokenData) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Token inválido o expirado');
        }

        // Actualizar contraseña
        $this->model->update($tokenData->user_id, [
            'password' => $this->request->getPost('password')
        ]);

        // Eliminar token usado
        $this->db->table('password_reset_tokens')
            ->where('id', $tokenData->id)
            ->delete();

        return redirect()->to('/login')
            ->with('success', 'Contraseña actualizada correctamente');
    }

    /**
     * Métodos protegidos/auxiliares
     */

    protected function crearSesion($usuario)
    {
        $sessionData = [
            'id' => $usuario->id,
            'username' => $usuario->username,
            'nombre' => $usuario->nombre,
            'rol' => $usuario->rol,
            'isLoggedIn' => true,
            'last_activity' => time()
        ];

        session()->set('usuario', $sessionData);
    }

    protected function getRedirectUrl()
    {
        switch (session('usuario.rol')) {
            case 'admin':
                return '/dashboard';
            case 'abogado':
                return '/casos';
            case 'cliente':
                return '/mis-casos';
            default:
                return '/';
        }
    }

    protected function guardarTokenRecuperacion($userId, $token)
    {
        $this->db->table('password_reset_tokens')->insert([
            'user_id' => $userId,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ]);
    }

    protected function validarTokenRecuperacion($token)
    {
        return $this->db->table('password_reset_tokens')
            ->where('token', $token)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRow();
    }

    protected function enviarEmailRecuperacion($email, $token)
    {
        $enlace = base_url("reset-password/$token");

        $emailService = Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Restablecer tu contraseña');
        $emailService->setMessage(view('auth/email_reset', [
            'enlace' => $enlace,
            'fecha' => date('d/m/Y H:i')
        ]));

        return $emailService->send();
    }

    protected function registrarIntentoLogin($userId, $exitoso)
    {
        $this->db->table('log_accesos')->insert([
            'usuario_id' => $userId,
            'tipo' => 'login',
            'exitoso' => $exitoso ? 1 : 0,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'fecha' => date('Y-m-d H:i:s')
        ]);
    }

    protected function registrarLogout($userId)
    {
        $this->db->table('log_accesos')->insert([
            'usuario_id' => $userId,
            'tipo' => 'logout',
            'exitoso' => 1,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'fecha' => date('Y-m-d H:i:s')
        ]);
    }
}
