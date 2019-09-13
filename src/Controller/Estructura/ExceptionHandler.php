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
     * @var Usuario
     */
    private $contexto;

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
        $this->arUsuario = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser(): null;
        $this->contexto = $router->getContext();
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
        $this->enviarWebService($excepcion);
    }

    /**
     * Esta funcion se encarga de enviar las excepciones a oro a traves de su api, si ocurren errores en el proceso
     * se escribira un log.
     * @return bool
     */
    private function enviarWebService($excepcion)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->from('App:General\GenConfiguracion', "c")
            ->select('c.codigoClienteMesaAyuda')
            ->where("c.codigoConfiguracionPk = 1");
        $arConfiguracion = $qb->getQuery()->getSingleResult();
        if($arConfiguracion) {
            $curl = curl_init("http://165.22.222.162/mai/public/index.php/api/error/nuevo");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, 1);
            $campos = [
                "codigoCliente" => $arConfiguracion['codigoClienteMesaAyuda'],
                "mensaje" => $excepcion->getMessage(),
                "codigo" => $excepcion->getCode(),
                "ruta" => $this->contexto->getBaseUrl(),
                "archivo" => $excepcion->getFile(),
                "traza" => json_encode($excepcion->getTrace()),
                "linea" => $excepcion->getLine(),
                "usuario" => $this->arUsuario->getUsername(),
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
        }
        return true;
    }

}