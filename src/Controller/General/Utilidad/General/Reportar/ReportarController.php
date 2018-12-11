<?php

namespace App\Controller\General\Utilidad\General\Reportar;

use App\Entity\General\GenConfiguracion;
use App\Form\Type\General\CasoType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReportarController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/general/utilidad/general/reportar/lista/{ruta}", name="general_utilidad_general_reportar_lista")
     */
    public function nuevo(Request $request, $ruta)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class,1);
        $opciones['ruta'] = $ruta;
        $opciones['telefono'] = $arConfiguracion->getTelefono();
        $form = $this->createForm(CasoType::class, $opciones);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $arrReporte = $form->getData();
            $arCaso = array(
                "asunto" => $arrReporte['asunto'].' - '.$ruta,
                "correo" => $this->getUser()->getEmail(),
                "contacto" => $this->getUser()->getNombreCorto(),
                "telefono" => $arConfiguracion->getTelefono(),
                "extension" => $arrReporte['extension'],
                "descripcion" => $arrReporte['descripcion'],
                "codigo_categoria_caso_fk" => 'REP',
                "codigo_prioridad_fk" => 'BAJ',
                "codigo_cliente_fk" => $arConfiguracion->getCodigoClienteOro(),
                "codigo_area_fk" => 'NA',
                "codigo_cargo_fk" => 'VAR'
            );

            $arCaso = json_encode($arCaso);

            $ch = curl_init($arConfiguracion->getWebServiceOroUrl().'/api/' . 'caso/nuevo');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arCaso);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($arCaso))
            );

            curl_exec($ch);
            Mensajes::success('El reporte ha sido registrado exitosamente.');
            echo "<script language='JavaScript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('general/utilidad/general/reportar/nuevo.html.twig',[
            'form' => $form->createView(),
            'arConfiguracion' => $arConfiguracion
        ]);
    }
}

