<?php


namespace App\Controller\Crm\Administracion\Comercial\Contacto;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Crm\CrmCliente;
use App\Entity\Crm\CrmContacto;
use App\Form\Type\Crm\ContactoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends ControllerListenerGeneral
{
    protected $clase= CrmContacto::class;
    protected $claseFormulario = ContactoType::class;
    protected $claseNombre = "CrmContacto";
    protected $modulo   = "Crm";
    protected $funcion  = "Administracion";
    protected $grupo    = "Comercial";
    protected $nombre   = "Contacto";

    /**
     * @Route("/crm/administracion/comercial/contacto/lista", name="crm_administracion_comercial_contacto_lista")
     */
    public function lista(Request $request )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class,['required' => false, 'data' => $session->get('filtroContactoCodigoCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroContactoCodigoCliente', $form->get('txtCodigoCliente')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(CrmContacto::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('crm_administracion_comercial_contacto_lista'));
            }
        }

        $arContactos= $paginator->paginate($em->getRepository(CrmContacto::class)->lista(),$request->query->getInt('page', 1), 30);
        return $this->render('crm/administracion/comercial/contacto/lista.html.twig', [
            'arContactos' => $arContactos,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/crm/administracion/comercial/contacto/nuevo/{id}/{codigoCliente}", name="crm_administracion_comercial_contacto_nuevo")
     */
    public function nuevo(Request $request, $id, $codigoCliente)
    {
        $em = $this->getDoctrine()->getManager();
        $arContacto = new CrmContacto();
        if ($id != 0) {
            $arContacto = $em->getRepository(CrmContacto::class)->find($id);
            if (!$arContacto) {
                return $this->redirect($this->generateUrl('crm_administracion_comercial_cliente_lista'));
            }
        }
        $form = $this->createForm(ContactoType::class, $arContacto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $em->getRepository(CrmCliente::class)->find($codigoCliente);
                if ($arCliente){
                    $arContacto = $form->getData();
                    $arContacto->setClienteRel($arCliente);
                    $em->persist($arContacto);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

                }
            }
        }
        return $this->render('crm/administracion/comercial/contacto/nuevo.html.twig', [
            'form' => $form->createView(),
            'arContacto' => $arContacto
        ]);
    }
    
    /**
     * @Route("/crm/administracion/comercial/contacto/nuevo/{id}", name="crm_administracion_comercial_contacto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arContacto = $em->getRepository(CrmContacto::class)->find($id);
            if (!$arContacto) {
                return $this->redirect($this->generateUrl('crm_administracion_comercial_contacto_lista'));
            }
        }
        $arContacto = $em->getRepository(CrmContacto::class)->find($id);
        return $this->render('crm/administracion/comercial/contacto/detalle.html.twig', [
            'arContacto' => $arContacto
        ]);
	}
}