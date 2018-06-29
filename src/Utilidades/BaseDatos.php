<?php

namespace App\Utilidades;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;


final class BaseDatos
{
    /**
     * @var EntityManager|object
     */
    private $em;

    const TYPES = [
        'error' => 'danger',
        'ok' => 'success',
        'information' => 'info',
        'warning' => 'warning',
    ];

    private $session = null;

    private function __construct()
    {
        $this->session = new Session();
        global $kernel;
        $this->em = $kernel->getContainer()->get("doctrine.orm.entity_manager");
    }

    /**
     * Método para obtener la instancia única de mensajería.
     * @return BaseDatos|null
     */
    private static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new BaseDatos();
        }
        return $instance;
    }

    /**
     * @return EntityManager|object
     */
    public static function getEm() {
        return self::getInstance()->em;
    }
}