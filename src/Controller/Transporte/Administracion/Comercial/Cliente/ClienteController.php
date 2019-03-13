<?php

namespace App\Controller\Transporte\Administracion\Comercial\Cliente;

use App\Controller\Estructura\ControllerListenerGeneral;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use App\Form\Type\Transporte\ClienteType;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends ControllerListenerGeneral
{
    protected $class = TteCliente::class;
    protected $claseNombre = "TteCliente";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Cliente";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/administracion/comercial/cliente/lista", name="transporte_administracion_comercial_cliente_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['label' => 'Codigo cliente: ', 'required' => false, 'data' => $session->get('filtroTteCodigoCliente')])
            ->add('txtNombreCorto', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNombreCliente')])
            ->add('txtNumeroIdentificacion', NumberType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNumeroIdentificacionCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            $session->set('filtroTteNitCliente', $form->get('txtNumeroIdentificacion')->getData());
        }
        $arClientes = $paginator->paginate($em->getRepository(TteCliente::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('transporte/administracion/comercial/cliente/lista.html.twig',
            ['arClientes' => $arClientes,
                'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/administracion/comercial/cliente/nuevo/{id}", name="transporte_administracion_comercial_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new TteCliente();
        if ($id != '0') {
            $arCliente = $em->getRepository(TteCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_cliente_detalle', ['id' => $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('transporte/administracion/comercial/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/cliente/detalle/{id}", name="transporte_administracion_comercial_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TteCliente::class)->find($id);
        $arCondicion = $arCliente->getCondicionRel();
        $form = $this->createFormBuilder()
            ->add('btnEliminarDetalle', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnEliminarDetalle')->isClicked()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $em->getRepository(TteClienteCondicion::class)->eliminar($arrSeleccionados);
        }

        $arCondiciones = $em->getRepository(TteClienteCondicion::class)->clienteCondicion($id);
        return $this->render('transporte/administracion/comercial/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente,
            'arCondiciones' => $arCondiciones,
            'arCondicion' => $arCondicion,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/transporte/administracion/comercial/cliente/detalle/nuevo/{id}", name="transporte_administracion_comercial_cliente_detalle_nuevo")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevo(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $respuesta = [];
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    if (!$em->getRepository(TteClienteCondicion::class)->findOneBy(['codigoClienteFk' => $id, 'codigoCondicionFk' => $codigo])) {
                        $arClienteCondicion = new TteClienteCondicion();
                        $arClienteCondicion->setClienteRel($em->getRepository(TteCliente::class)->find($id));
                        $arClienteCondicion->setCondicionRel($em->getRepository(TteCondicion::class)->find($codigo));
                        $em->persist($arClienteCondicion);
                    } else {
                        $respuesta [] = "La condición con código {$codigo} ya se encuentra agregada para el cliente seleccionado";
                    }
                }
                $em->flush();
            }
            if (count($respuesta) > 0) {
                foreach ($respuesta AS $error) {
                    Mensajes::error($error);
                }
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arCondiciones = $paginator->paginate($em->getRepository(TteCondicion::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/administracion/comercial/cliente/detalleNuevo.html.twig', ['arCondiciones' => $arCondiciones, 'form' => $form->createView()]);
    }
}

