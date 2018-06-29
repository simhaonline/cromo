<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 29/06/18
 * Time: 02:29 PM
 */

namespace App\Utilidades;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;

final class Estandares
{

    private function __construct()
    {
        global $kernel;
        $this->form = $kernel->getContainer()->get("form.factory");
    }

    private static function getInstance()
    {
        static $instance = null;
        if($instance === null) {
            $instance = new Estandares();
        }
        return $instance;
    }

    public static function getForm() {
        return self::getInstance()->form;
    }

    /**
     * @param $ar array
     * @return mixed
     */
    public static function formularioDetalles($ar)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($ar['estadoAutorizado']) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            if ($ar['estadoAprobado']) {
                $arrBtnDesautorizar['disabled'] = true;
                if (!$ar['estadoAnulado']) {
                    $arrBtnAnular['disabled'] = false;
                }
            } else {
                $arrBtnAprobar['disabled'] = false;
            }
        }

        return self::getForm()
            ->createBuilder()
            ->add('btnAutorizar', SubmitType::class, $arrBtnAutorizar)
            ->add('btnAprobar', SubmitType::class, $arrBtnAprobar)
            ->add('btnDesautorizar', SubmitType::class, $arrBtnDesautorizar)
            ->add('btnImprimir', SubmitType::class, $arrBtnImprimir)
            ->add('btnAnular', SubmitType::class, $arrBtnAnular)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
    }


}