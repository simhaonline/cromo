<?php

namespace App\Controller\General\Utilidad\General\Reportar;

use App\Entity\General\GenConfiguracion;
use App\Entity\Seguridad\Usuario;
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
        $arrParametrosRuta = explode('_',$ruta);
        $modulo = isset($arrParametrosRuta[0])?$arrParametrosRuta[0]:'';
        $submodulo = isset($arrParametrosRuta[1])?$arrParametrosRuta[1]:'';
        $grupo = isset($arrParametrosRuta[2])?$arrParametrosRuta[2]:'';
        $accion = isset($arrParametrosRuta[3])?$arrParametrosRuta[3]:'';
        $arConfiguracion = $em->find(GenConfiguracion::class,1);
        $opciones['ruta'] = $ruta;
        $opciones['telefono'] = $arConfiguracion->getTelefono();
        $form = $this->createForm(CasoType::class, $opciones);
        $form->handleRequest($request);

        $descripcionSugerida = "Ruta: {$ruta}\nModulo: {$modulo}\nSubmodulo: {$submodulo}\nGrupo: {$grupo}\nAccion: {$accion}\n\nDescripcion: ";


        if($form->isSubmitted() && $form->isValid()){
            $descripcion = $request->request->get('descripcion');
            $arrReporte = $form->getData();
            $arCaso = array(
                "asunto" => $arrReporte['asunto'].' - '.$ruta,
                "correo" => $this->getUser()->getEmail(),
                "contacto" => $this->getUser()->getNombreCorto() ? $this->getUser()->getNombreCorto() : $this->getUser()->getUsername(),
                "telefono" => $arConfiguracion->getTelefono() ? $arConfiguracion->getTelefono() : 0,
                "extension" => $arrReporte['extension'] ? $arrReporte['extension'] : 0,
                "descripcion" => $descripcion,
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
            'ruta' => $ruta,
            'arConfiguracion' => $arConfiguracion,
            'descripcionSugerida' => $descripcionSugerida,
            'form' => $form->createView()
        ]);
    }
}

