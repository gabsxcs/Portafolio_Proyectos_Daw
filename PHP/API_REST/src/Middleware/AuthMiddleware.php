<?php
namespace Evento\Middleware;

use Evento\Service\JwtService;

/**
 * Clase Middleware que sirve para manejar la autenticación de tokens jwt
 */
class AuthMiddleware{
    private JwtService $jwtService;

    /**
     * Constructor del middleware de autenticación
     * 
     * @param JwtService $jwtService Servicio JWT
     */
    public function __construct(JwtService $jwtService){
        $this->jwtService = $jwtService;
    }

    /**
     * Verifica si hay un token válido en la solicitud
     * 
     * @return bool true si el token es válido, false si no hay o si es inválido.
     */
    public function verifyAuth(){
        $token = $this->getAuthorizationToken();
        
        if (!$token) {
            return false;
        }

        $datosToken = $this->jwtService->verifyToken($token);
        
        return $datosToken !== null;
    }

    /**
     * Obtiene el datosToken del token de autorización
     * 
     * @return array|null datosToken del token o null si no es válido
     */
    public function getTokenDatosToken(): ?array{
        $token = $this->getAuthorizationToken();
        
        if (!$token) {
            return null;
        }

        return $this->jwtService->verifyToken($token);
    }

    /**
     * Obtiene el id del administrador del token
     * 
     * @return int|null id del administrador o null si no hay token válido
     */
    public function getAdminId(){
        $datosToken = $this->getTokenDatosToken();
        
        if (!$datosToken || !isset($datosToken['idAdmin'])) {
            return null;
        }

        return (int)$datosToken['idAdmin'];
    }

    /**
     * Obtiene el token de autorización del encabezado HTTP Authorization. y espera por el Bearer token
     * 
     * @return string|null Token o null si no existe
     */
    private function getAuthorizationToken(): ?string
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return null;
        }

        return $matches[1];
    }
}