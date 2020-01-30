<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Intercambio;

use App\Controller\MaestroController;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Controller\Estructura\FuncionesController;

class TerceroController extends MaestroController
{


    public $tipo = "utilidad";
    public $proceso = "finu0002";


    /**
     * @param Request $request
     * @return Response
     * @Route("/financiero/utilidad/contabilidad/intercambio/tercero", name="financiero_utilidad_contabilidad_intercambio_tercero")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('btnIlimitada', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnIlimitada')->isClicked()) {
                $this->ilimitada();
            }

        }
        $arTerceros = $paginator->paginate($em->getRepository(FinTercero::class)->listaIntercambio(), $request->query->getInt('page', 1), 20);
        return $this->render('financiero/utilidad/contabilidad/intercambio/tercero/tercero.html.twig', [
            'arTerceros' => $arTerceros,
            'form' => $form->createView()
        ]);
    }

    private function ilimitada()
    {
        $em = $this->getDoctrine()->getManager();
        $rutaTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
        $strArchivo = $rutaTemporal . $strNombreArchivo;
        $ar = fopen($strArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $arTerceros = $em->getRepository(FinTercero::class)->listaIntercambio()->getQuery()->getResult();
        foreach ($arTerceros as $arTercero) {
            fputs($ar, FuncionesController::RellenarNr($arTercero['numeroIdentificacion'], ' ', 11,'R') . "\t");
            if ($arTercero['codigoIdentificacionFk'] == 'NI') {
                $tipoDocumento = 'A';
            } elseif ($arTercero['codigoIdentificacionFk'] == 'CC') {
                $tipoDocumento = 'C';
            }
            fputs($ar, $tipoDocumento . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['nombreCorto']), ' ', 30, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['direccion']), ' ', 30, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['ciudadNombre']), ' ', 15, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['telefono']), ' ', 7, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr($arTercero['codigoDaneCiudad'], '0', 5, 'R') . "\t");
            fputs($ar, 'S' . "\t");
            fputs($ar, 'N' . "\t");
            //Pais
            fputs($ar, FuncionesController::RellenarNr("169", '0', 3, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['nombre1']), ' ', 30, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['nombre2']), ' ', 30, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['apellido1']), ' ', 30, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['apellido2']), ' ', 30, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['email']), ' ', 60, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(utf8_decode($arTercero['celular']), ' ', 15, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr('0', ' ', 3, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(' ', ' ', 4, 'R') . "\t");
            fputs($ar, FuncionesController::RellenarNr(' ', ' ', 5, 'R') . "\t");
            fputs($ar, ' '  ."|");
            fputs($ar, "\n");
        }
        fclose($ar);
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;

    }

}

