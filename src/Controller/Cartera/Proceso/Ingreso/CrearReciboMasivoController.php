<?php

namespace App\Controller\Cartera\Proceso\Ingreso;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Cartera\ReciboType;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CrearReciboMasivoController extends Controller
{
    /**
     * @param Request $request
     * @param TokenStorageInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/proceso/ingreso/recibomasivo/lista", name="cartera_proceso_ingreso_recibomasivo_lista")
     */
    public function lista(Request $request, TokenStorageInterface $user)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('cboCuentaCobrarTipoRel', EntityType::class, $em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('btnFiltrar', SubmitType::class, ['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        $arReciboInicial = new CarRecibo();
        $arReciboInicial->setFechaPago(new \DateTime('now'));
        $formRecibo = $this->createForm(ReciboType::class, $arReciboInicial);
        $formRecibo->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
                $arCuentaCobrarTipo = $form->get('cboCuentaCobrarTipoRel')->getData();
                if ($arCuentaCobrarTipo) {
                    $session->set('filtroCarReciboCodigoReciboTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
                } else {
                    $session->set('filtroCarReciboCodigoReciboTipo', null);
                }
                $session->set('filtroCarNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
            }
        }
        if ($formRecibo->isSubmitted() && $formRecibo->isValid()) {
            if ($formRecibo->get('guardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $arrRecibos = [];
                    foreach ($arrSeleccionados as $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        if ($arCuentaCobrar) {
                            /** @var  $arrDatos CarRecibo */
                            $arrDatos = $formRecibo->getData();
                            $arRecibo = new CarRecibo();
                            $arRecibo->setCodigoAsesorFk($arrDatos->getAsesorRel() ? $arrDatos->getAsesorRel()->getCodigoAsesorPk() : null);
                            $arRecibo->setAsesorRel($arrDatos->getAsesorRel());
                            $arRecibo->setCodigoReciboTipoFk($arrDatos->getReciboTipoRel()->getCodigoReciboTipoPk());
                            $arRecibo->setComentarios($arrDatos->getComentarios());
                            $arRecibo->setCodigoCuentaFk($arrDatos->getCuentaRel()->getCodigoCuentaPk());
                            $arRecibo->setCuentaRel($arrDatos->getCuentaRel());
                            $arRecibo->setReciboTipoRel($arrDatos->getReciboTipoRel());
                            $arRecibo->setFecha(new \DateTime('now'));
                            $arRecibo->setFechaPago($arrDatos->getFechaPago());
                            $arRecibo->setCodigoClienteFk($arCuentaCobrar->getCodigoClienteFk());
                            $arRecibo->setClienteRel($arCuentaCobrar->getClienteRel());
                            $arRecibo->setVrPago($arCuentaCobrar->getVrSaldo());
                            $arRecibo->setVrPagoTotal($arCuentaCobrar->getVrSaldo());
                            $arRecibo->setUsuario($user->getToken()->getUsername());
                            $arRecibo->setSoporte($arrDatos->getSoporte());
                            $em->persist($arRecibo);

                            $arrRecibos[] = $arRecibo;
                            $arReciboDetalle = new CarReciboDetalle();
                            $arReciboDetalle->setCodigoReciboFk($arRecibo->getCodigoReciboPk());
                            $arReciboDetalle->setReciboRel($arRecibo);
                            $arReciboDetalle->setCodigoCuentaCobrarFk($arCuentaCobrar->getCodigoCuentaCobrarPk());
                            $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                            $arReciboDetalle->setVrRetencionFuente($arCuentaCobrar->getVrRetencionFuente());
                            $arReciboDetalle->setVrPago($arCuentaCobrar->getVrSaldo());
                            $arReciboDetalle->setVrPagoAfectar($arCuentaCobrar->getVrSaldo());
                            $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                            $arReciboDetalle->setCodigoCuentaCobrarTipoFk($arCuentaCobrar->getCodigoCuentaCobrarTipoFk());
                            $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                            $arReciboDetalle->setAsesorRel($arRecibo->getAsesorRel());
                            $arReciboDetalle->setOperacion(1);
                            $em->persist($arReciboDetalle);
                        }
                    }
                    $em->flush();
                    $this->cerrarRecibo($arrRecibos);
                } else {
                    Mensajes::error("No ha seleccionado cuenta por cobrar");
                }
                return $this->redirect($this->generateUrl('cartera_proceso_ingreso_recibomasivo_lista'));
            }
        }
        $arCuentasCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->crearReciboMasivoLista(), $request->query->getInt('page', 1), 100);
        return $this->render('cartera/proceso/contabilidad/crearrecibomasivo/lista.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView(),
            'formRecibo' => $formRecibo->createView()
        ]);
    }

    /**
     * @param $arrRecibos array
     */
    private function cerrarRecibo($arrRecibos)
    {
        $em = $this->getDoctrine()->getManager();
        if ($arrRecibos) {
            /** @var  $arRecibo CarRecibo */
            foreach ($arrRecibos as $arRecibo) {
                $em->getRepository(CarRecibo::class)->autorizar($arRecibo);
                $em->getRepository(CarRecibo::class)->aprobar($arRecibo);
            }
        }
    }
}
