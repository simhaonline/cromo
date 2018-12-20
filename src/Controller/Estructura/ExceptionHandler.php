<?php
/**
 * Created by PhpStorm.
 * User: juanfelipe
 * Date: 17/10/18
 * Time: 04:17 PM
 */

namespace App\Controller\Estructura;


use App\Entity\Seguridad\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExceptionHandler
{
    /**
     * Variable para obtenerl el EntityManager* Variable para obtenerl el EntityManager* Variable para obtenerl el EntityManager
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * Variable para obtener el contenedor de Symfony
     * @var ContainerInterface
     */
    private $container;
    /**
     * Variable para controlar la excepción
     * @var \Exception
     */
    private $excepcion;
    /**
     * Nombre del entorno donde se encuentra la aplicacion (dev || prod)
     * @var string
     */
    private $env;
    /**
     * @var Usuario
     */
    private $arUsuario;
    /**
     * Variable que contiene el codigo del cliente
     * @var integer
     */
    private $codigoClienteOro;
    /**
     * Url de la api para consumir el control web que almacenara el error en el sistema.
     * @var string
     */
    private $urlWebService;
    /**
     * Variable con el nombre del cliente
     * @var string
     */
    private $nombreCliente;
    /**
     * Ruta (del controller) en donde se produjo el error.
     * @var string
     */
    private $ruta;
    /**
     * Url completa en donde se genero el error.
     * @var string
     */
    private $url;
    /**
     * Protocolo utilizado para la peticion.
     * @var string
     */
    private $scheme;
    /**
     * Servidor utilizado para la peticion.
     * @var string
     */
    private $host;
    /**
     * Variable string que contiene la descripcion del error en caso de que se obtenga un error de sintaxis en query
     * @var string
     */
    private $queryString;
    /**
     * Mensaje arrojado por la excepcion.
     * @var string
     */
    private $mensaje;
    /**
     * Codigo arrojado por la excepcion
     * @var int
     */
    private $codigo;
    /**
     * Numero de linea en donde se genero el error.
     * @var int
     */
    private $linea;
    /**
     * Traza de errores donde se produjo la excepcion.
     * @var array
     */
    private $traza;

    /**
     * Ruta del archivo en el cual se genero la excepcion
     * @var string
     */
    private $archivo;

    /**
     * @var RouterInterface
     */
    private $router;


    /**
     * ExceptionHandler constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->container = $container;
        $this->em = $em;
        $this->env = $container->get("kernel")->getEnvironment();
        $this->arUsuario = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser() : null;
        $this->router = $router;
    }

    /**
     * Función que detecta el evento de excepciones que reporta el sistema, la cual tendrá su lógica para reportar el error a la API
     * @param GetResponseForExceptionEvent $event
     * @return bool
     */
    public function getException(GetResponseForExceptionEvent $event)
    {
        $this->excepcion = $event->getException();
        $excepcion = $this->excepcion;
        if ($this->env == "dev" || (method_exists($this->excepcion, 'getStatusCode') && $this->excepcion->getStatusCode() == 404)) {
            return false;
        }
        $this->obtenerConfiguracion();
        $request = $this->router->getContext();
        $this->ruta = $request->getBaseUrl();
        $this->scheme = $request->getScheme();
        $this->host = $request->getHost();
        $this->queryString = $request->getQueryString();
        $this->url = "{$this->scheme}://{$this->host}{$request->getBaseUrl()}{$this->ruta}" . ($this->queryString ? "?{$this->queryString}" : "");
        $this->mensaje = $excepcion->getMessage();
        $this->codigo = $excepcion->getCode();
        $this->archivo = $excepcion->getFile();
        $this->linea = $excepcion->getLine();
        $this->traza = $excepcion->getTrace();
        $this->enviarWebService();
    }

    /**
     * Esta funcion consulta la configuracion del sistema
     */
    private function obtenerConfiguracion()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->from('App:General\GenConfiguracion', "gc")
            ->select('gc.codigoClienteOro')
            ->addSelect("gc.webServiceOroUrl")
            ->addSelect("gc.nombre")
            ->where("gc.codigoConfiguracionPk = 1");
        $resultado = $qb->getQuery()->getSingleResult();
        $this->codigoClienteOro = $resultado ? $resultado['codigoClienteOro'] : 'Sin definir';
        $this->nombreCliente = $resultado ? $resultado['nombre'] : 'Sin definir';
        $this->urlWebService = $resultado ? $resultado['webServiceOroUrl'] . "/api/error/nuevo" : null;
    }

    /**
     * Esta funcion se encarga de enviar las excepciones a oro a traves de su api, si ocurren errores en el proceso
     * se escribira un log.
     * @return bool
     */
    private function enviarWebService()
    {

        $curl = curl_init($this->urlWebService);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        $fecha = date("Y-m-d H:i:s");
        $campos = [
            "codigo_cliente" => $this->codigoClienteOro,
            "nombre_cliente" => $this->nombreCliente,
            "mensaje" => $this->mensaje,
            "codigo" => $this->codigo,
            "ruta" => $this->ruta,
            "archivo" => $this->archivo,
            "traza" => json_encode($this->traza),
            "fecha" => $fecha,
            "url" => $this->url,
            "linea" => $this->linea,
            "usuario" => $this->arUsuario->getUsername(),
            "nombre_usuario" => $this->arUsuario->getNombreCorto() == null ? $this->arUsuario->getUsername() : $this->arUsuario->getNombreCorto(),
            "email" => $this->arUsuario->getEmail(),
        ];
        $data = json_encode($campos);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 8);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );

        $response = curl_exec($curl) == 'true';

        if (!$this->urlWebService) {
            $this->guardarLog("[{$fecha}] No se ha definido una ruta para el webservice de oro: {$data}\n");
            return false;
        } else if ($response == false) {
            $mensaje = "[{$fecha}] Error al guardar en oro: " . $data . "\n";
            $this->guardarLog($mensaje);
            return false;
        }
        return true;
    }

    /**
     * Esta funcion permite escribir en un archivo de log.
     * @param $mensaje string
     */
    private function guardarLog($mensaje)
    {
        $ds = DIRECTORY_SEPARATOR;
        $logs_dir = realpath($this->container->get("kernel")->getRootDir() . "/../var/log/");
        $fileName = "excepciones_cromo.txt";
        $archivo = fopen($logs_dir . $ds . $fileName, 'a');
        fwrite($archivo, $mensaje);
        fclose($archivo);
    }

}