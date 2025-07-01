<?php
namespace Evento\Controller;
use Doctrine\ORM\EntityManager;
use Evento\Entity\Administrador;
use Evento\Service\JwtService;

/**
 * este controller se encarga de la autenticacion del admin
 */
class AuthController{
    private $administradorRepository;
    private $jwtService;

    public function __construct(EntityManager $entityManager, JwtService $jwtService) {
        $this->administradorRepository = $entityManager->getRepository(Administrador::class);
        $this->jwtService = $jwtService;
    }

    /**
     * Metodo que procesa el login de un admin
     * @param $data los datos recibidos
     * @return array Respuesta con token o error
     */
    public function login(array $data) {
        
        // se valida el email y la contraseña
        if (!isset($data['email']) || !isset($data['contrasenya'])) {
            error_log("Campos faltantes. Email: " . (isset($data['email']) ? 'OK' : 'FALTA') . ", Contrasenya: " . (isset($data['contrasenya']) ? 'OK' : 'FALTA'));
            return [
                'error' => 'Datos incompletos',
                'message' => 'Se requiere email y contraseña',
                'debug' => [
                    'received_fields' => array_keys($data)
                ]
            ];
        }

        // limpia los datos
        $email = trim($data['email']);
        $contrasenya = trim($data['contrasenya']);
        
        // busca el administrador por su email y su no existe devuelve un error
        $administrador = $this->administradorRepository->findOneBy(['email' => $email]);
        
        if (!$administrador) {
            return [
                'error' => 'Credenciales inválidas',
                'message' => 'El email o la contraseña son incorrectos'
            ];
        }

        //verifica  que la contraseña sea correcta
        $passwordValid = $administrador->verifyContrasenya($contrasenya);
        
        if (!$passwordValid) {
            return [
                'error' => 'Credenciales inválidas',
                'message' => 'El email o la contraseña son incorrectos'
            ];
        }

        // si el email y la contraseña son validas, entonces se enera el token con los datos del admin
        $datosToken = [
            'idAdmin' => $administrador->getId(),
            'name' => $administrador->getName(),
            'email' => $administrador->getEmail(),
            'role' => 'admin'
        ];

        $token = $this->jwtService->generateToken($datosToken);
        
        //devuelve la respuesta con el token y datos del admin
        return [
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'error' => false,
            'token' => $token,
            'user' => [
                'id' => $administrador->getId(),
                'name' => $administrador->getName(),
                'email' => $administrador->getEmail(),
                'role' => 'admin'
            ]
        ];
    }


    /**
     * Obtiene los datos del administrador actual a partir del token en la cabecera.
     *
     * @return array dlos datos del admin. o null si no está autenticado
     */
    public function getCurrentAdmin(): ?array  {
        $token = $this->getAuthorizationToken();
        
        if (!$token) {
            return null;
        }

        try {
            $datosToken = $this->jwtService->verifyToken($token);
            
            if (!$datosToken) {
                return null;
            }

            //verifica que el token sea valido
            if (!isset($datosToken['role']) || $datosToken['role'] !== 'admin') {
                return null;
            }   

            //verifica que pertenezca a un admin
            if (!isset($datosToken['idAdmin'])) {
                return null;
            }

            $administrador = $this->administradorRepository->find($datosToken['idAdmin']);
            
            if (!$administrador) {
                return null;
            }

            return [
                'id' => $administrador->getId(),
                'name' => $administrador->getName(),
                'email' => $administrador->getEmail(),
                'role' => 'admin'
            ];

        } catch (\Exception $e) {
            error_log("Error al verificar token: ".$e->getMessage());
            return null;
        }
    }



    /**
     * Extrae el token jwt del header HTTP Authorization.
     * 
     * @return string el token extraído o null si no se encuentra
     */
    private function getAuthorizationToken(){
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        //Extrae el token si el formato es correcto, osea ue es Bearer
        if (empty($authHeader) || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return null;
        }

        return $matches[1];
    }
}