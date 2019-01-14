<?php

namespace App\Controller\Cartera\Proceso\Contabilidad;

use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CrearReciboMasivoController extends Controller
{
    /**
     * @Route("/cartera/proceso/contabilidad/crearrecibomasivo/lista", name="cartera_proceso_contabilidad_crearrecibomasivo_lista")
     */
    public function lista(Request $request, TokenStorageInterface $user)
    {
        $em=$this->getDoctrine()->getManager();
        $session=new Session();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('cboCuentaCobrarTipoRel',EntityType::class,$em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('btnFiltrar',SubmitType::class,['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->add('btnReciboCaja',SubmitType::class,['label' => "Recibo caja", 'attr' => ['class' => 'btn btn-default btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCuentaCobrarTipo = $form->get('cboCuentaCobrarTipoRel')->getData();
                if ($arCuentaCobrarTipo) {
                    $session->set('filtroCarReciboCodigoReciboTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
                } else {
                    $session->set('filtroCarReciboCodigoReciboTipo', null);
                }
            }

            if ($form->get('btnReciboCaja')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if($arrSeleccionados){
                    foreach ($arrSeleccionados as $arrSeleccionado){
                        $arCuentaCobrar=$em->getRepository('App:Cartera\CarCuentaCobrar')->find($arrSeleccionado);
                        if($arCuentaCobrar){
                            $arReciboTipo=$em->getRepository('App:Cartera\CarReciboTipo')->find("RC");
//                            $arCuenta=$em->getRepository('App:General\GenCuenta')->find($arCuentaCobrar->getCuentaCobrarTipoRel())
                            $arRecibo=(new CarRecibo())
                                ->setReciboTipoRel($arReciboTipo)
                                ->setFecha(new \DateTime('now'))
                                ->setFechaPago($arCuentaCobrar->getFechaVence())
                                ->setClienteRel($arCuentaCobrar->getClienteRel())
//                                ->setCuentaRel()
                                ->setVrPago($arCuentaCobrar->getVrSaldo())
                                ->setVrPagoTotal($arCuentaCobrar->getVrTotal())
                                ->setUsuario($user->getToken()->getUsername());

                            $arReciboDetalle = (new CarReciboDetalle())
                                ->setReciboRel($arRecibo)
                                ->setCuentaCobrarRel($arCuentaCobrar)
                                ->setVrRetencionFuente($arCuentaCobrar->getVrRetencionFuente())
                                ->setVrPago($arCuentaCobrar->getVrSaldo())
                                ->setVrPagoAfectar($arCuentaCobrar->getVrSaldo())
                                ->setNumeroFactura($arCuentaCobrar->getNumeroDocumento())
                                ->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel())
                                ->setOperacion(1);

                            $em->persist($arReciboDetalle);
                            $em->persist($arRecibo);
                        }
                    }
                    $em->flush();
                }
                else{
                    Mensajes::error("No ha seleccionado cuenta por cobrar");
                }
                return $this->redirect($this->generateUrl('cartera_proceso_contabilidad_crearrecibomasivo_lista'));
            }
        }
        $arCrearReciboMasivos=$paginator->paginate($em->getRepository('App:Cartera\CarCuentaCobrar')->crearReciboMasivoLista(), $request->query->getInt('page', 1),100);
        return $this->render('cartera/proceso/contabilidad/crearrecibomasivo/lista.html.twig', [
            'arCrearReciboMasivos' => $arCrearReciboMasivos,
            'form'                 => $form->createView()
        ]);
    }
}
