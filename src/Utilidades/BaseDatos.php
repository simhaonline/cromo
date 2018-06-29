<?php

namespace App\Utilidades;

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
        if($instance === null) {
            $instance = new BaseDatos();
        }
        return $instance;
    }

    /**
     * Esta función permite enviar un flash personalizado.
     * @param $message
     * @param $type
     */
    public static function llenarCombo($tipo)
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
                /*if($session->get('filtroSolicitudTipo')){
                    $array['data'] = $this->getDoctrine()->getManager()->getReference('App:Inventario\InvSolicitudTipo',$session->get('filtroSolicitudTipo')->getCodigoSolicitudTipoPk());
                }
                break;*/
        }
        return $array;
    }


}