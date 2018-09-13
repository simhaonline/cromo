<?php


namespace App\Controller\Transporte\Utilidad\Transporte;


use App\Entity\Transporte\TteDespachoDetalle;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PrecioReexpedicionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Rest\Route("/transporte/utilidad/transporte/precio/reexpedicion", name="transporte_proceso_transporte_precio_reexpedicion")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, array('data' => $session->get('filtroTtePrecioReexpedicionDespachoCodigo'), 'required' => false))
            ->add('txtGuiaNumero', TextType::class, array('data' => $session->get('filtroTtePrecioReexpedicionGuiaNumero'), 'required' => false))
            ->add('txtGuiaCodigo', TextType::class, array('data' => $session->get('filtroTtePrecioReexpedicionGuiaCodigo'), 'required' => false))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnActualizar', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTtePrecioReexpedicionDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $session->set('filtroTtePrecioReexpedicionGuiaNumero', $form->get('txtGuiaNumero')->getData());
                $session->set('filtroTtePrecioReexpedicionGuiaCodigo', $form->get('txtGuiaCodigo')->getData());
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $arrDespachoDetalles = $request->request->get('arrVrPrecioReexpedicion');
                if (count($arrDespachoDetalles) > 0) {
                    foreach ($arrDespachoDetalles as $pk => $vrPrecioReexpedicion) {
                        $arDespachoDetalle = $em->getRepository(TteDespachoDetalle::class)->find($pk);
                        if ($arDespachoDetalle) {
                            if($vrPrecioReexpedicion > 0 || ($vrPrecioReexpedicion == 0 && $arDespachoDetalle->getVrPrecioReexpedicion() > 0)){
                                $arDespachoDetalle->setVrPrecioReexpedicion($vrPrecioReexpedicion);
                                $em->persist($arDespachoDetalle);
                            }
                        }
                    }
                    $em->flush();
                }
            }
        }
        $arDespachoDetalles = $paginator->paginate($em->getRepository('App:Transporte\TteDespachoDetalle')->reexpedicion(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/utilidad/transporte/despacho/precioReexpedicion.html.twig', [
            'arDespachoDetalles' => $arDespachoDetalles,
            'form' => $form->createView()
        ]);
    }
}

