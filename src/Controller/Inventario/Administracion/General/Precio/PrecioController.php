<?php

namespace App\Controller\Inventario\Administracion\General\Precio;


use App\Entity\Inventario\InvPrecio;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Form\Type\Inventario\PrecioType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrecioController extends Controller
{
    /**
     * @Route("/inv/adm/gen/precio/lista", name="inv_adm_gen_precio_lista")
     */
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                }
            }
        }
        $query = $this->getDoctrine()->getRepository(InvPrecio::class)->lista();
        $arPrecios = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('inventario/administracion/general/precio/lista.html.twig', [
            'arPrecios' => $arPrecios,
            'form' => $form->createView()]);
    }

    /**
     * @Route("inv/adm/gen/precio/nuevo/{id}", name="inv_adm_general_precio_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecio = new InvPrecio();
        if ($id != 0) {
            $arPrecio = $em->getRepository('App:Inventario\InvPrecio')->find($id);
            if (!$arPrecio) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'inventario', 'entidad' => 'precio']));
            }
        }
        $form = $this->createForm(PrecioType::class, $arPrecio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
//                $arPrecio->setFecha(new \DateTime('now'));
                $em->persist($arPrecio);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'inventario','entidad' => 'precio']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arPrecio);
                $em->flush($arPrecio);
                return $this->redirect($this->generateUrl('inv_adm_general_precio_nuevo', ['codigoPrecio' => 0]));
            }
        }
        return $this->render('inventario/administracion/general/precio/nuevo.html.twig', [
            'form' => $form->createView(), 'arPrecio' => $arPrecio
        ]);
    }

    /**
     * @Route("/inv/adm/gen/precio/detalle/{id}", name="inv_adm_general_precio_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator  = $this->get('knp_paginator');
        //$objFormatopedido = new pedido();
        $em = $this->getDoctrine()->getManager();
        $arPrecio = $em->getRepository(InvPrecio::class)->find($id);
        $form = $this->formularioDetalles($arPrecio);
        $form->handleRequest($request);
        $query = $em->getRepository(InvPrecioDetalle::class)->listar($id);
        $arPrecioDetalles = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('inventario/movimiento/comercial/pedido/detalle.html.twig', [
            'form' => $form->createView(),
            'arPrecioDetalles' => $arPrecioDetalles,
            'arPrecio' => $arPrecio
        ]);
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalles($ar)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($ar->getEstadoAnulado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($ar->getEstadoAprobado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = false;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($ar->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = false;
        } else {
            $arrBtnAutorizar['disabled'] = false;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = false;
            $arrBtnAprobar['disabled'] = true;
        }
        return $this
            ->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, $arrBtnAutorizar)
            ->add('btnAprobar', SubmitType::class, $arrBtnAprobar)
            ->add('btnDesautorizar', SubmitType::class, $arrBtnDesautorizar)
            ->add('btnImprimir', SubmitType::class, $arrBtnImprimir)
            ->add('btnAnular', SubmitType::class, $arrBtnAnular)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
    }
}

