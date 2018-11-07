<?php

namespace App\Controller\Inventario\Administracion\Inventario;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvItem;
use App\Form\Type\Inventario\DocumentoType;
use App\Form\Type\Inventario\ItemType;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentoController extends ControllerListenerGeneral
{
    protected $class= InvDocumento::class;
    protected $claseNombre = "InvDocumento";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "Inventario";
    protected $nombre = "Documento";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/administracion/inventario/documento/lista", name="inventario_administracion_inventario_documento_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnExcel')->isClicked()){
                General::get()->setExportar($this->getDoctrine()->getRepository(InvDocumento::class)->lista()->getQuery()->execute(), 'Documentos inventario');
            }
        }
        $arDocumentos = $paginator->paginate($this->getDoctrine()->getRepository(InvDocumento::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/administracion/inventario/Documento/lista.html.twig', [
            'arDocumentos' => $arDocumentos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/inventario/administracion/inventario/documento/nuevo/{id}",name="inventario_administracion_inventario_documento_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumento = new InvDocumento();
        if ($id != '0') {
            $arDocumento = $em->getRepository(InvDocumento::class)->find($id);
            if (!$arDocumento) {
                return $this->redirect($this->generateUrl('inventario_administracion_inventario_documento_lista'));
            }
        }
        $form = $this->createForm(DocumentoType::class, $arDocumento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($arDocumento->getDocumentoTipoRel()->getCodigoDocumentoTipoPk() == 'TRA'){
                    if($arDocumento->getOperacionInventario() == 0){
                        $em->persist($arDocumento);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_administracion_inventario_documento_detalle', ['id' => $arDocumento->getCodigoDocumentoPk()]));
                    } else {
                        Mensajes::error('La operacion para el tipo de documento TRASLADO debe ser neutro');
                    }
                } elseif($arDocumento->getOperacionInventario() != 0) {
                    $em->persist($arDocumento);
                    $em->flush();
                    return $this->redirect($this->generateUrl('inventario_administracion_inventario_inventario_documento_detalle', ['id' => $arDocumento->getCodigoDocumentoPk()]));
                } else {
                    Mensajes::error('Debe seleccionar otro tipo de operacion diferente a NEUTRO');
                }
            }
        }
        return $this->render('inventario/administracion/inventario/Documento/nuevo.html.twig', [
            'form' => $form->createView(),
            'arDocumento' => $arDocumento
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/inventario/documento/detalle/{id}",name="inventario_administracion_inventario_documento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arDocumento = $em->getRepository(InvDocumento::class)->find($id);
        return $this->render('inventario/administracion/inventario/Documento/detalle.html.twig', [
            'arDocumento' => $arDocumento
        ]);
    }

}
