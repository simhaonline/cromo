<?php

namespace App\Controller\General\Utilidad\General\Documentacion;

use App\Entity\General\GenConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Tests\Matcher\DumpedUrlMatcherTest;

class DocumentacionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/documentacion/buscar", name="documentacion_buscar")
     */
    public function buscar(Request $request)
    {
        $arrDatos = '';
        $arrModulos = [
            'TTE' => 'Transporte',
            'RHU' => 'Recursos humano',
            'TUR' => 'Turnos',
            'CAR' => 'Cartera',
            'COM' => 'Compra',
            'INV' => 'Inventario',
            'FIN' => 'Financiero',
            'DOC' => 'Documental',
            'GEN' => 'General'
        ];
        $arrFunciones = [
            'MOV' => 'Movimiento',
            'ADM' => 'Administración',
            'PRO' => 'Proceso',
            'UTI' => 'Utilidad',
            'INF' => 'Informe',
            'TBL' => 'Tablero',
        ];
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $form = $this->createFormBuilder()
            ->add('choModulo', ChoiceType::class, [
                'choices' => [
                    'TODOS' => 'TOD',
                    'TRANSPORTE' => 'TTE',
                    'RECURSO HUMANO' => 'RHU',
                    'TURNO' => 'TUR',
                    'CARTERA' => 'CAR',
                    'COMPRA' => 'COM',
                    'INVENTARIO' => 'INV',
                    'FINANCIERO' => 'FIN',
                    'DOCUMENTAL' => 'DOC',
                    'GENERAL' => 'GEN'
                ],
                'attr' => ['class' => 'btn btn-default dropdown-toggle']
            ])
            ->add('choFuncion', ChoiceType::class, [
                'choices' => [
                    'TODOS' => 'TOD',
                    'MOVIMIENTO' => 'MOV',
                    'ADMINISTRACIÓN' => 'ADM',
                    'PROCESO' => 'PRO',
                    'UTILIDAD' => 'UTI',
                    'INFORME' => 'INF',
                    'TABLERO' => 'TBL'
                ],
                'attr' => ['class' => 'btn btn-default dropdown-toggle']
            ])
            ->add('txtBusqueda', TextType::class, ['required' => false, 'attr' => ['class' => 'input-lg']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $arrDatos = json_encode([
                'busqueda' => $form->get('txtBusqueda')->getData(),
                'modulo' => $form->get('choModulo')->getData(),
                'funcion' => $form->get('choFuncion')->getData()
            ]);
        }
        $ch = curl_init($arConfiguracion->getWebServiceCesioUrl() . '/api/documentacion/buscar');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($arrDatos))
        );
        $arTemas = json_decode(curl_exec($ch));
        return $this->render('general/utilidad/general/documentacion/buscar.html.twig', [
            'arTemas' => $arTemas,
            'modulos' => $arrModulos,
            'funciones' => $arrFunciones,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/documentacion/calificar", name="documentacion_calificar")
     */
    public function calificar(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $arrCalificacion = explode('-', $request->query->get('calificacion'));
        $arrDatos['id'] = $arrCalificacion[1];
        $arrDatos['calificacion'] = $arrCalificacion[2];
        $arrDatos = json_encode($arrDatos);
        $ch = curl_init($arConfiguracion->getWebServiceCesioUrl() . '/api/documentacion/calificar');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($arrDatos))
        );
        $respuesta = json_decode(curl_exec($ch));
        return new JsonResponse($respuesta);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/documentacion/consultarHtml", name="documentacion_consultarHtml")
     */
    public function consultarHtml(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class, 1);
        $arrDatos['id'] = $request->query->get('id');
        $arrDatos = json_encode($arrDatos);

        $ch = curl_init($arConfiguracion->getWebServiceCesioUrl() . '/api/documentacion/consultarHtml');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($arrDatos))
        );
        $respuesta = json_decode(curl_exec($ch));
        return new JsonResponse($respuesta);
    }
}