<?php

namespace App\Controller\Inventario\Administracion\General\Precio;


use App\Controller\Estructura\ControllerListenerPermisosFunciones;
use App\Entity\Inventario\InvPrecio;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Form\Type\Inventario\PrecioType;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrecioController extends ControllerListenerPermisosFunciones
{
    protected $class= InvPrecio::class;
    protected $claseNombre = "InvPrecio";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Precio";

    /**
     * @Route("/inventario/administracion/general/precio/lista", name="inventario_administracion_general_precio_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroInvNombrePrecio')])
            ->add('chkTipoPrecio', ChoiceType::class, ['choices' => ['TODOS' => '', 'VENTA' => '1', 'COMPRA' => '0'], 'data' => $session->get('filtroInvTipoPrecio'), 'required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('filtroInvNombrePrecio', $form->get('txtNombre')->getData());
            $session->set('filtroInvTipoPrecio', $form->get('chkTipoPrecio')->getData());
        }
        $query = $this->getDoctrine()->getRepository(InvPrecio::class)->lista();
        $arPrecios = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/administracion/general/precio/lista.html.twig', [
            'arPrecios' => $arPrecios,
            'form' => $form->createView()]);
    }

    /**
     * @Route("inventario/administracion/general/precio/nuevo/{id}", name="inventario_administracion_general_precio_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecio = new InvPrecio();
        if ($id != 0) {
            $arPrecio = $em->getRepository('App:Inventario\InvPrecio')->find($id);
            if (!$arPrecio) {
                return $this->redirect($this->generateUrl('inventario_administracion_general_precio_lista'));
            }
        } else {
            $arPrecio->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(PrecioType::class, $arPrecio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('guardar')->isClicked()) {
                if (isset($arrControles["tipo"])) {
                    switch ($arrControles["tipo"]) {
                        case 1:
                            $arPrecio->setCompra(1);
                            break;
                        case 2:
                            $arPrecio->setVenta(1);
                            break;
                    }
                }
                $em->persist($arPrecio);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_general_precio_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arPrecio);
                $em->flush($arPrecio);
                return $this->redirect($this->generateUrl('inventario_administracion_general_precio_nuevo', ['codigoPrecio' => 0]));
            }
        }
        return $this->render('inventario/administracion/general/precio/nuevo.html.twig', [
            'form' => $form->createView(), 'arPrecio' => $arPrecio
        ]);
    }

    /**
     * @Route("/inventario/administracion/general/precio/detalle/{id}", name="inventario_administracion_general_precio_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPrecio = $em->getRepository(InvPrecio::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminarDetalle', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvPrecioDetalle::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_administracion_general_precio_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvPrecioDetalle::class)->lista($id))->execute(), "Precio detalles");
            }
        }
        $query = $em->getRepository(InvPrecioDetalle::class)->lista($id);
        $arPrecioDetalles = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/administracion/general/precio/detalle.html.twig', [
            'form' => $form->createView(),
            'arPrecioDetalles' => $arPrecioDetalles,
            'arPrecio' => $arPrecio
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoPrecio
     * @param $id
     * @Route("/inventario/administracion/general/precio/detalle/nuevo/{codigoPrecio}/{id}", name="inventario_administracion_general_precio_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, $codigoPrecio, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecioDetalle = new InvPrecioDetalle();
        $arPrecio = $em->getRepository(InvPrecio::class)->find($codigoPrecio);
        if ($id != 0) {
            $arPrecioDetalle = $em->getRepository(InvPrecioDetalle::class)->find($id);
        }
        $form = $this->createFormBuilder()
            ->add('itemRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvItem',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'data' => $arPrecioDetalle->getItemRel()
            ])
            ->add('vrPrecio', IntegerType::class, array('required' => true, 'data' => $arPrecioDetalle->getVrPrecio()))
            ->add('diasPromedioEntrega', IntegerType::class, array('required' => false, 'data' => $arPrecioDetalle->getDiasPromedioEntrega()))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $itemRel = $form->get('itemRel')->getData();
            $arItemExistente = $em->getRepository(InvPrecioDetalle::class)
                ->findBy(['itemRel' => $itemRel, 'codigoPrecioFk' => $codigoPrecio]);
            if (!$arItemExistente) {
                if ($form->get('guardar')->isClicked()) {
                    $arPrecioDetalle->setItemRel($itemRel);
                    $arPrecioDetalle->setVrPrecio($form->get('vrPrecio')->getData());
                    $arPrecioDetalle->setDiasPromedioEntrega($form->get('diasPromedioEntrega')->getData());
                    $arPrecioDetalle->setPrecioRel($arPrecio);
                    $em->persist($arPrecioDetalle);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            } else {
                Mensajes::error('El item seleccionado ya existe para esta lista de precios');
            }
        }
        return $this->render('inventario/administracion/general/precio/detalleNuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

