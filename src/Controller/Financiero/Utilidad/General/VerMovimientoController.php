<?php

namespace App\Controller\Financiero\Utilidad\General;

use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenModelo;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class VerMovimientoController extends Controller
{
    /**
     * @Route("/financiero/utilidad/general/vermovimiento/{clase}/{id}", name="financiero_utilidad_general_vermovimiento")
     */
    public function listaAction(Request $request, $clase, $id) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arModelo = $em->getRepository(GenModelo::class)->find($clase);
        $repositorio = "App:{$arModelo->getCodigoModuloFk()}\\{$clase}";
        $arMovimiento = $em->getRepository($repositorio)->find($id);
        $arrBotonContabilizar = array('label' => 'Contabilizar', 'disabled' => true);
        $arrBotonDescontabilizar = array('label' => 'Descontabilizar', 'disabled' => true);
        if ($arMovimiento->getEstadoContabilizado()) {
            $arrBotonDescontabilizar['disabled'] = false;
            $arRegistroIntercambio = $em->getRepository(FinRegistro::class)->findOneBy(array('codigoModeloFk' => $clase, 'codigoDocumento' => $id, 'estadoIntercambio' => 1));
            if($arRegistroIntercambio) {
                Mensajes::info('No se puede descontabilizar porque ya fue utilizado para intercambio de datos');
                $arrBotonDescontabilizar['disabled'] = true;
            }
        } else {
            $arrBotonContabilizar['disabled'] = false;

        }
        $form = $this->createFormBuilder()
            ->add('btnContabilizar', SubmitType::class, $arrBotonContabilizar)
            ->add('btnDescontabilizar', SubmitType::class, $arrBotonDescontabilizar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnContabilizar')->isClicked()) {
                $resultado = $em->getRepository($repositorio)->contabilizar(array($id));
                return $this->redirect($this->generateUrl('financiero_utilidad_general_vermovimiento', array('clase' => $clase, 'id' => $id)));
            }
            if ($form->get('btnDescontabilizar')->isClicked()) {

                $em->createQueryBuilder()
                    ->delete(FinRegistro::class,'r')
                    ->where("r.codigoModeloFk = '{$clase}' AND r.codigoDocumento = {$id}")->getQuery()->execute();

                //$arRegistroEliminar = $em->getRepository(FinRegistro::class)->findBy(array('codigoModeloFk' => $clase, 'codigoDocumento' => $id));
                //$em->remove($arRegistroEliminar);
                $arMovimiento->setEstadoContabilizado(0);
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_utilidad_general_vermovimiento', array('clase' => $clase, 'id' => $id)));
            }
        }
        $query = $this->getDoctrine()->getRepository(FinRegistro::class)->listaVerMovimiento($clase, $id);
        $arRegistros = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('financiero/utilidad/general/verMovimiento.html.twig', array(
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
        ));
    }

}

