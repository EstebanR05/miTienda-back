<?php

header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserControlador extends sistema\nucleo\APControlador
{
    private $_ayuda;
    private $_seg;
    private $_sesion;
    private $_key = AP_KEY_VALUE;

    public function __construct()
    {
        parent::__construct();

        // cargamos la clase ayudantes para usar sus metodos de ayuda
        $this->_ayuda = new sistema\ayudantes\APPHPAyuda;
        $this->_seg = new sistema\ayudantes\APPHPSeguridad;
        $this->_sesion = new sistema\nucleo\APSesion();
        //$session = new \DevCoder\SessionManager();
    }

    public function login()
    {
        try {
            $User = $_GET['user'];
            $password = $_GET['password'];
            $userModelo = $this->cargaModelo('user');
            $usuario = $userModelo->getUsers();

            if ($usuario == null) {
                http_response_code(404);
                echo 'We can´t search user';
            }

            if ($usuario != null) {
                $payload = [
                    'iss' => 'https://TallerPuntoElDesvare.com',
                    'aud' => 'https://TallerPuntoElDesvare.com',
                    'iat' => time(),
                    "exp" => time() + 3600,
                    'nbf' => 1357000000,
                    'usuario_id' => $usuario['id']
                ];

                $jwt = JWT::encode($payload, $this->_key, 'HS256');
                header('Content-Type: application/json');

                if ($jwt == false) {
                    $jwt = json_encode(["jsonError" => json_last_error_msg()]);

                    if ($jwt == false) {
                        $jwt = '{"jsonError":"unknown"}';
                    }

                    http_response_code(500);
                }

                http_response_code(200);
                echo json_encode($jwt);
            }
        } catch (Exception $e) {
            echo 'error ->', $e->getMessage();
        }
    }
}