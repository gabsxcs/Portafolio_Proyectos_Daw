<?php
namespace Evento\Controller;

use Doctrine\ORM\EntityManager;
use Evento\Controller\UsuariController;
use Evento\Controller\EsdevenimentController;
use Evento\Controller\TicketController;
use Evento\Controller\LocalitzacioController;
use Evento\Controller\CompraController;
use Evento\Controller\SeientController;
use Evento\Controller\AuthController;
use Evento\Middleware\AuthMiddleware;
use Evento\Service\PdfGenerador;
use Evento\Service\JwtService;

class FrontController{

    private EntityManager$entityManager;
    private array $controllers = [];
    private ?PdfGenerador $pdfGenerador = null;
    private JwtService $jwtService;
    private AuthMiddleware $authMiddleware;
    
    /**
     * Rutas que no requieren autenticación
     * Solo el login y el getAll de eventos
     */
    private array $publicRoutes = [
        'auth/login',
    ];
    
    /**
     * Constructor 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, ?PdfGenerador $pdfGenerador = null){
        $this->entityManager = $entityManager;
        $this->pdfGenerador = $pdfGenerador;
        
        // se inicia el servicio de jwt con una clave 
        $this->jwtService = new JwtService($_ENV['JWT_SECRET'] ?? '2025@Thos');
        
        // y luego se inicializa el middleware de autenticación
        $this->authMiddleware = new AuthMiddleware($this->jwtService);
        
        $this->initControllers();
        
        // Configuración de headers para JSON API
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    /**
     * Inicializa todos los controladores disponibles
     */
    private function initControllers(): void
    {
        $this->controllers = [
            'usuaris' => new UsuariController($this->entityManager),
            'esdeveniments' => new EsdevenimentController($this->entityManager),
            'tickets' => new TicketController($this->entityManager, $this->pdfGenerador),
            'localitzacions' => new LocalitzacioController($this->entityManager),
            'compres' => new CompraController($this->entityManager),
            'seients' => new SeientController($this->entityManager),
            'auth' => new AuthController($this->entityManager, $this->jwtService),
            'admin'=> new AdministradorController($this->entityManager)
        ];
    }

    /**
     * Verifica si una ruta es pública.
     */
    private function isPublicRoute(string $resource, array $segments, string $method): bool {
        $routePath = implode('/', $segments);
        
        // verificar rutas exactas públicas del arra
        if (in_array($routePath, $this->publicRoutes)) {
            return true;
        }
        
        // Caso especial: SOLO GET a /esdeveniments sin ningun otro segemento o paraemetro es público
        // Esto permite ver todos los eventos sin autenticación de admin
        if ($resource === 'esdeveniments' && $method === 'GET' && count($segments) === 1) {
            return true;
        }
        
        //la unica ruta public aparte del login, es la del getAll de eventos.
        //no he puesto la ruta de /esdeveniments en el array porque provocaba que todas las rutas en eventos fuesen publicas en lugar de solo la de getAll. 
        //por eso existe este metodo
        
        return false;
    }

    /**
     * Maneja la solicitud entrante
     */
    public function handleRequest(): void {


        // Manejo de la solicitud options para CORS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }

        // se parsea  la ruta
        $path = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($path, PHP_URL_PATH);
        
        // extrae de la url la parte después de index.php/
        if (strpos($path, 'index.php/') !== false) {
            $path = substr($path, strpos($path, 'index.php/') + strlen('index.php/'));
        }
        
        $path = trim($path, '/');
        
        
        // y luego se divide la ruta por segmentos
        $segments = explode('/', $path);
        
        // aqui se determina el recurso, la peticion que se hará
        $resource = $segments[0] ?? '';
        $method = $_SERVER['REQUEST_METHOD'];
        
        // verificar si la ruta requiere autenticación
        if (!$this->isPublicRoute($resource, $segments, $method)) {
            if (!$this->authMiddleware->verifyAuth()) {
                $this->sendResponse([
                    'error' => 'Token requerido o inválido',
                    'message' => 'No autorizado - Se requiere iniciar sesión'
                ], 401);
                return;
            }
           
        }
        
        if ($resource === 'auth') {
            $this->handleAuthRoutes($segments);
            return;
        }
        
        // Casos especiales de rutas
        

        //TICKETS//

        // Caso especial para buscar tickets por código
        if ($resource === 'tickets' && isset($segments[1]) && $segments[1] === 'ref' && isset($segments[2])) {
            $result = $this->controllers['tickets']->readByRef($segments[2]);
            $this->sendResponse($result);
            return;
        }
        
        // Caso especial para buscar tickets por estado
        if ($resource === 'tickets' && isset($segments[1]) && $segments[1] === 'status' && isset($segments[2])) {
            $result = $this->controllers['tickets']->readByStatus($segments[2]);
            $this->sendResponse($result);
            return;
        }
        

        //COMPRAS//

        // Caso especial para buscar compras por usuario
        if ($resource === 'compres' && isset($segments[1]) && $segments[1] === 'user' && isset($segments[2])) {
            $result = $this->controllers['compres']->getByUser((int)$segments[2]);
            $this->sendResponse($result);
            return;
        }
        

        //SEIENTS//

        // Caso especial para buscar asientos por localización
        if ($resource === 'seients' && isset($segments[1]) && $segments[1] === 'venue' && isset($segments[2])) {
            $result = $this->controllers['seients']->getByVenue((int)$segments[2]);
            $this->sendResponse($result);
            return;
        }
        
        // Caso especial para buscar asientos por tipo
        if ($resource === 'seients' && isset($segments[1]) && $segments[1] === 'tipo' && isset($segments[2])) {
            $result = $this->controllers['seients']->getByTipo($segments[2]);
            $this->sendResponse($result);
            return;
        }
        
        // Caso especial para buscar asientos por fila
        if ($resource === 'seients' && isset($segments[1]) && $segments[1] === 'fila' && isset($segments[2])) {
            $result = $this->controllers['seients']->getByFila($segments[2]);
            $this->sendResponse($result);
            return;
        }
        

        // se verifica que el recurso sea valido, de lo contrario envia un error
        if (empty($resource) || !isset($this->controllers[$resource])) {
            $this->sendResponse(['error' => 'Recurso no encontrado'], 404);
            return;
        }
        
        // se obtiene el controlador correspondiente
        $controller = $this->controllers[$resource];
        
        // Determinar el ID (segunda parte de la ruta si existe)
        $id = isset($segments[1]) && is_numeric($segments[1]) ? (int)$segments[1] : null;
        
        // se ejecuta la acción correspondiente según el método HTTP
        $this->executeAction($controller, $id);
    }
    
    /**
     * Maneja las rutas específicas de autenticación del admin. Solo se permite post y get
     * 
     * @param array $segments Segmentos de la ruta
     */
    private function handleAuthRoutes(array $segments): void {
        $authController = $this->controllers['auth'];
        $action = $segments[1] ?? '';
        
        try {
            switch ($action) {
                case 'login':
                    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                        $this->sendResponse(['error' => 'Método no permitido'], 405);
                        return;
                    }
                    
                    $data = $this->getRequestData();
                    $result = $authController->login($data);
                    $this->sendResponse($result, $result['error'] ? 401 : 200);
                    break;
                    
                case 'me':
                    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                        $this->sendResponse(['error' => 'Método no permitido'], 405);
                        return;
                    }
                    
                    // Obtener el administrador actual usando el método getCurrentAdmin
                    $adminData = $authController->getCurrentAdmin();
                    
                    if (!$adminData) {
                        $this->sendResponse(['error' => 'No autenticado o no es administrador'], 401);
                        return;
                    }
                    
                    $this->sendResponse([
                        'success' => true,
                        'user' => [
                            'id' => $adminData['id'],
                            'name' => $adminData['name'],
                            'email' => $adminData['email'],
                            'role' => 'admin'
                        ]
                    ]);
                break;

                default:
                    $this->sendResponse(['error' => 'Ruta de autenticación no válida'], 404);
            }
        } catch (\Exception $e) {
            $this->sendResponse([
                'error' => 'Error en el servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Ejecuta la acción correspondiente en el controlador
     * 
     * @param mixed $controller El controlador a utilizar
     * @param int|null $id ID del recurso si existe
     */
    private function executeAction($controller, ?int $id): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $result = null;
        
        try {
            switch ($method) {
                case 'GET':
                    $result = $controller->read($id);
                    break;
                    
                case 'POST':
                    $data = $this->getRequestData();
                    $result = $controller->create($data);
                    $this->sendResponse($result, 201);
                    return;
                    
                case 'PUT':
                    if ($id === null) {
                        $this->sendResponse(['error' => 'Se requiere ID para actualizar'], 400);
                        return;
                    }
                    $data = $this->getRequestData();
                    $result = $controller->update($id, $data);
                    break;
                    
                case 'DELETE':
                    if ($id === null) {
                        $this->sendResponse(['error' => 'Se requiere ID para eliminar'], 400);
                        return;
                    }
                    $result = $controller->delete($id);
                    break;
                    
                default:
                    $this->sendResponse(['error' => 'Método no permitido'], 405);
                    return;
            }
            
            $this->sendResponse($result);
            
        } catch (\Exception $e) {
            $this->sendResponse([
                'error' => 'Error en el servidor', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtiene los datos de la solicitud
     * 
     * @return array Los datos de la solicitud
     */
    private function getRequestData(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') !== false) {
            $content = file_get_contents('php://input');
            return json_decode($content, true) ?? [];
        }
        
        return $_POST;
    }
    
    /**
     * Envía una respuesta en formato JSON
     * 
     * @param mixed $data Los datos a enviar
     * @param int $statusCode Código de estado HTTP
     */
    private function sendResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}