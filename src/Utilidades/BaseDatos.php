<?php

namespace App\Utilidades;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;


final class BaseDatos
{
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
     * @param $tipo
     * @return array|null
     */
    public static function llenarCombo($em, $tipo)
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
                    $array['data'] = $em->getReference('App:Inventario\InvSolicitudTipo', self::getInstance()->session->get('filtroSolicitudTipo')->getCodigoSolicitudTipoPk());
                }
                break;
        }
        return $array;
    }


}