<?php
require_once 'model/usuarios.php';

class AuthController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Usuarios();
    }

    public function Login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        require_once 'view/auth/login.php';
    }

    public function Auth() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($username) || empty($password)) {
            $error = 'Por favor, complete todos los campos.';
            require_once 'view/auth/login.php';
            return;
        }

        try {
            $user = $this->model->Obtener($username);
            
            // Check if user exists and password matches
            if ($user && $user->UsrPas === $password) {
                // Check if user is active (UsrAct != 'N')
                if ($user->UsrAct === 'N') {
                    $error = 'Usuario inactivo. Por favor consulte al administrador.';
                    require_once 'view/auth/login.php';
                } else {
                    $_SESSION['user'] = [
                        'UsrCod' => $user->UsrCod,
                        'UsrInf' => $user->UsrInf,
                        'UsrAdm' => $user->UsrAdm,
                        'GruCod' => $user->GruCod
                    ];
                    header('Location: index.php');
                    exit;
                }
            } else {
                $error = 'Usuario o contraseña incorrectos.';
                require_once 'view/auth/login.php';
            }
        } catch (Exception $e) {
            $error = 'Error en el sistema: ' . $e->getMessage();
            require_once 'view/auth/login.php';
        }
    }

    public function Logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: index.php?c=auth&a=Login');
        exit;
    }
}
