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
        if ($instance === null) {
            $instance = new Estandares();
        }
        return $instance;
    }

    public static function getForm()
    {
        return self::getInstance()->form;
    }

    /**
     * @param $estadoAutorizado
     * @param $estadoAprobado
     * @param $estadoAnulado
     * @return \Symfony\Component\Form\FormInterface
     */
    public static function botonera($estadoAutorizado, $estadoAprobado, $estadoAnulado)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($estadoAutorizado) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            if ($estadoAprobado) {
                $arrBtnDesautorizar['disabled'] = true;
                if (!$estadoAnulado) {
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
            ->getForm();
    }



}