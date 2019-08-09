<?php

namespace App\Controller\General\Utilidad\Seguridad\Log;

use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocImagen;
use App\Entity\Documental\DocMasivo;
use App\Entity\General\GenLog;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VerController extends Controller
{
    /**
     * @Route("/general/utilidad/seguridad/log/ver/{entidad}/{codigo}", name="general_utilidad_seguridad_log_ver")
     */
    public function ver(Request $request, $entidad, $codigo)
    {
        $em= $this->getDoctrine()->getManager();
        $detalles=$em->getRepository(GenLog::class)->getCampoSeguimiento($codigo, $entidad);
        $getCampoSeguimiento=[];
        foreach ($detalles as $json){
            array_push($getCampoSeguimiento, $json['camposSeguimiento']);
        }
        $detalleSeguimiento = $getCampoSeguimiento;
        $cabeceras = null;
        if($detalleSeguimiento) {
            $cabeceras=json_decode($detalleSeguimiento[0], true);
            $cabeceras=array_keys($cabeceras);
            array_unshift($cabeceras,"fecha","accion", "codigoUsuarioFk");
            for ($i=0;$i<count($detalleSeguimiento);$i++) {
                $detalleSeguimiento[$i]=json_decode($detalleSeguimiento[$i], true);
                $strFecha=$detalles[$i]['fecha']->format('Y-m-d H:i:s');
                $detalleSeguimiento[$i]=array("fecha"=>$strFecha,"accion"=>$detalles[$i]['accion'], "codigoUsuarioFk"=>$detalles[$i]['codigoUsuarioFk'])+$detalleSeguimiento[$i];
                $actualizacionCabeceras = array_keys($detalleSeguimiento[$i]);
                $nuevo=array_diff($actualizacionCabeceras, $cabeceras);
                if(count($nuevo)>0){
                    foreach ($nuevo as $n){
                        array_push($cabeceras,$n);
                    }
                }
            }
        }
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*if($form->get('btnExcel')->isClicked()){
                ob_clean();
                $this->generarExcelLogComparativo($detalleSeguimiento,"LogExtendido");
            }*/
        }

        return $this->render('general/utilidad/log/ver.html.twig', [
            'detalles'      =>  $detalleSeguimiento,
            'cabeceras'     =>  $cabeceras,
            'form' => $form->createView()
        ]);
    }


}

