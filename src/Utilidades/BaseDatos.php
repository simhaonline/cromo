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

    /**
     * @param $em
     * @param $tipo
     * @return array|null
     * @throws \Doctrine\ORM\ORMException
     */
    public static function llenarCombo( $tipo)
    {
        $array = null;
        switch ($tipo) {

            case 1:
                $array = [
                    'class' => 'App:Inventario\InvSolicitudTipo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('st')
                            ->orderBy('st.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                    'required' => false,
                    'empty_data' => "",
                    'placeholder' => "TODOS",
                    'data' => ""];
                if (self::getInstance()->session->get('filtroSolicitudTipo')) {
                    $array['data'] = self::getEm()->getReference('App:Inventario\InvSolicitudTipo', self::getInstance()->session->get('filtroSolicitudTipo')->getCodigoSolicitudTipoPk());
                }
                break;
        }
        return $array;
    }
}