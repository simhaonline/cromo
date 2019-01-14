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
    public static $imagen;
    private function __construct()
    {
        global $kernel;
        $this->form = $kernel->getContainer()->get("form.factory");
        $this->router = $kernel->getContainer()->get("router");
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

    public static function getRouter()
    {
        return self::getInstance()->router;
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

    public static function botoneraLista()
    {
        return self::getForm()->createBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-deafult']])
            ->getForm();
    }

    /**
     * @param $pdf
     * @param string $titulo
     * @param $em
     * @param null $imagen
     * @param null $extension
     */
    public static function generarEncabezado($pdf, $titulo = ' ', $em)
    {
        /** @var  $arConfiguracion GenConfiguracion */
        $arConfiguracion = BaseDatos::getEm()->getRepository(GenConfiguracion::class)->find(1);
        $pdf->SetFont('Arial', '', 5);
        $date = new \DateTime('now');
        $pdf->Text(168, 8, $date->format('Y-m-d H:i:s') . ' [Cromo | ERP]');
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial', 'B', 10);
        //Logo
        $pdf->SetXY(53, 10);

        try {
            if(self::getLogo(BaseDatos::getEm()) ){
                $pdf->Image(self::getLogo(BaseDatos::getEm())['imagen'], 12, 13, 40, 25,self::getLogo(BaseDatos::getEm())['extension']);
            }
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

    public function getLogo($em){
        try {
            $logo=$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if($logo ){
                if(!self::$imagen){

                $imagenBase64=base64_encode(stream_get_contents($logo->getImagen()));
                $imagen="data:image/'{$logo->getExtension()}';base64,".$imagenBase64;
                    self::$imagen=$imagen;
                }else{

                $imagen=self::$imagen;
                }

                return [
                    'imagen'=>$imagen,
                    'extension'=>$logo->getExtension(),
                ];
            }
        } catch (\Exception $exception) {
        }
    }

}