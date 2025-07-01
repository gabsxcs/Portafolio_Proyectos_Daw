<?php
namespace Evento\Service;

class JwtService {
    private $claveSecreta;
    private $tiempoExpiracion;

    /**
     * Constructor del servicio JWT
     * 
     * @param $claveSecreta Clave secreta para firmar el token
     * @param $tiempoExpiracion Tiempo de expiración, que de predeterminado es de una hora
     */
    public function __construct(string $claveSecreta, int $tiempoExpiracion = 3600) {
        $this->claveSecreta = $claveSecreta;
        $this->tiempoExpiracion = $tiempoExpiracion;
    }

    /**
     * Genera un token JWT para el admin que se loguea
     * 
     * @param  $datosToken Datos para incluir en el token
     * @return string Token JWT generado
     */
    public function generateToken(array $datosToken){
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        //aqui añado el tiempo de expiracion y de crwacion
        $datosToken['exp'] = time() + $this->tiempoExpiracion;
        $datosToken['iat'] = time(); 

        //se codifica los datos del header y del datosToken en
        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $datosTokenEncoded = $this->base64UrlEncode(json_encode($datosToken));

        //se genera la firma y se codifica
        $firma = hash_hmac('sha256', "$headerEncoded.$datosTokenEncoded", $this->claveSecreta, true);
        $firmaEncoded = $this->base64UrlEncode($firma);

        //se unen las 3 partes para crear el token
        $tokenFinal = "$headerEncoded.$datosTokenEncoded.$firmaEncoded";

        return  $tokenFinal;
    }

    /**
     * Verifica que el token sea valido y regresa su contenido 
     * 
     * @param string $token Token a verificar
     * @return array|null datosToken decodificado o null si el token es inválido
     */
    public function verifyToken(string $token){
        // Se divide el token en sus partes
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$headerEncoded, $datosTokenEncoded, $firmaEncoded] = $parts;

        // Se verifica la firma
        $firma = $this->base64UrlDecode($firmaEncoded);
        $expectedfirma = hash_hmac('sha256', "$headerEncoded.$datosTokenEncoded", $this->claveSecreta, true);

        if (!hash_equals($firma, $expectedfirma)) {
            return null;
        }

        // Aquie se descodifican los datos del token
        $datosToken = json_decode($this->base64UrlDecode($datosTokenEncoded), true);

        // Verificar su tiempo de expiracion
        if (isset($datosToken['exp']) && $datosToken['exp'] < time()) {
            return null;
        }

        return $datosToken;
    }

    /**
     * Codifica un string en formato base64url
     * 
     * @param string $data datos a codificar
     * @return string resultado de datos codificados
     */
    private function base64UrlEncode(string $data){
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decodifica un string en formato base64url
     * 
     * @param string $data datos a decodificar
     * @return string datos decodificados
     */
    private function base64UrlDecode(string $data){
        return base64_decode(strtr($data, '-_', '+/'));
    }
}