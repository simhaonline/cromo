<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 29/06/18
 * Time: 02:29 PM
 */

namespace App\Utilidades;


use App\Entity\General\GenConfiguracion;
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

    /**
     * @param $pdf
     * @param string $titulo
     */
    public static function generarEncabezado($pdf, $titulo = ' ')
    {
        /** @var  $arConfiguracion GenConfiguracion */
        $arConfiguracion = BaseDatos::getEm()->getRepository(GenConfiguracion::class)->find(1);
        $pdf->SetFont('Arial', '', 5);
        $date = new \DateTime('now');
        $pdf->Text(168, 8, $date->format('Y-m-d H:i:s') . ' [Cromo | Inventario]');
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial', 'B', 10);
        //Logo
        $pdf->SetXY(53, 10);
        try {
            $pdf->Image('../public/assets/img/empresa/logo.jpeg', 12, 13, 40, 25);
        } catch (\Exception $exception) {
        }
        //INFORMACIÓN EMPRESA
        $pdf->Cell(147, 7, utf8_decode($titulo), 0, 0, 'C', 1);
        $pdf->SetXY(53, 18);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 4, "EMPRESA:", 0, 0, 'L', 1);
        $pdf->Cell(100, 4, utf8_decode($arConfiguracion ? $arConfiguracion->getNombre() : ''), 0, 0, 'L', 0);
        $pdf->SetXY(53, 22);
        $pdf->Cell(20, 4, "NIT:", 0, 0, 'L', 1);
        $pdf->Cell(100, 4, $arConfiguracion ? $arConfiguracion->getNit() : '', 0, 0, 'L', 0);
        $pdf->SetXY(53, 26);
        $pdf->Cell(20, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L', 1);
        $pdf->Cell(100, 4, utf8_decode($arConfiguracion ? $arConfiguracion->getDireccion() : ''), 0, 0, 'L', 0);
        $pdf->SetXY(53, 30);
        $pdf->Cell(20, 4, utf8_decode("TELÉFONO:"), 0, 0, 'L', 1);
        $pdf->Cell(100, 4, $arConfiguracion ? $arConfiguracion->getTelefono() : '', 0, 0, 'L', 0);
    }

}