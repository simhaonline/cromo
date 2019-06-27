<?php


namespace App\Controller\Crm\Administracion\Comercial\Cliente;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Crm\CrmCliente;
use App\Entity\Crm\CrmContacto;
use App\Entity\Crm\CrmNegocio;
use App\Entity\Transporte\TteCliente;
use App\Form\Type\Crm\ClienteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class ClienteController extends ControllerListenerGeneral
{
    protected $clase= CrmCliente::class;
    protected $claseFormulario = ClienteType::class;
    protected $claseNombre = "CrmVisitaTipo";
    protected $modulo   = "Crm";
    protected $funcion  = "Administracion";
    protected $grupo    = "Comercial";
    protected $nombre   = "Cliente";
    /**
     * @Route("/crm/administracion/comercial/cliente/lista", name="crm_administracion_comercial_cliente_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroCrmNombreCliente')))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('btnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->add('btnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroCrmClienteNombre', $form->get('TxtNombre')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arClienterSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(CrmCliente::class, $arClienterSeleccionados);
				return $this->redirect($this->generateUrl('crm_administracion_comercial_cliente_lista'));
			}
        }
        $arClienteClientes = $paginator->paginate($em->getRepository(CrmCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('crm/administracion/comercial/cliente/lista.html.twig', array(
            'arClientes' => $arClienteClientes,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/crm/administracion/comercial/cliente/nuevo/{id}", name="crm_administracion_comercial_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new CrmCliente();
        if ($id != 0) {
            $arCliente = $em->getRepository(CrmCliente::class)->find($id);
			if (!$arCliente) {
                return $this->redirect($this->generateUrl('crm_administracion_comercial_cliente_lista'));
            }
		}
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $form->getData();
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('crm_administracion_comercial_cliente_detalle', ['id' => $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('crm/administracion/comercial/cliente/nuevo.html.twig', [
            'form' => $form->createView(),
            'arCliente' => $arCliente
        ]);
    }

    /**
     * @Route("/crm/administracion/comercial/cliente/detalle/{id}", name="crm_administracion_comercial_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()){
                $arClienterSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(CrmContacto::class, $arClienterSeleccionados);
                return $this->redirect($this->generateUrl('crm_administracion_comercial_cliente_detalle', ['id' => $id]));
            }
        }
        if ($id != 0) {
            $arCliente = $em->getRepository(CrmCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('crm_administracion_comercial_cliente_lista'));
            }
        }

        $arCliente = $em->getRepository(CrmCliente::class)->find($id);
        $arContactos = $em->getRepository(CrmContacto::class)->findBy(array('codigoClienteFk'=>$arCliente->getCodigoClientePk()));
        return $this->render('crm/administracion/comercial/cliente/detalle.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView(),
            'arContactos'=>$arContactos
        ]);
	}
    /**
     * @Route("/crm/bus/cliente/{campoCodigo}/{campoNombre}", name="crm_cliente")
     */
    public function buscar(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroCrmNombreCliente')))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroCrmCodigoCliente')))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCrmCodigoCliente', $form->get('TxtCodigo')->getData());
            $session->set('filtroCrmNombreCliente', $form->get('TxtNombre')->getData());
        }
        $arClienteClientes = $paginator->paginate($em->getRepository(CrmCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('crm/administracion/comercial/cliente/cliente.html.twig', array(
            'arClientes' => $arClienteClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }
}