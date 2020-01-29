<?php

namespace App\Controller\Transporte\Proceso\General;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTemporal;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteRecogidaProgramada;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class UnificarClienteController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttep0014";


    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/general/cliente/unificar", name="transporte_proceso_general_cliente_unificar")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('btnUnificar', SubmitType::class, ['label' => 'Unificar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            set_time_limit(0);
            ini_set("memory_limit", -1);
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            $arCliente = null;
            if ($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
            }

            $txtCodigoClienteDestino = $request->request->get('txtCodigoClienteDestino');
            $arClienteDestino = null;
            if ($txtCodigoClienteDestino != '') {
                $arClienteDestino = $em->getRepository(TteCliente::class)->find($txtCodigoClienteDestino);
            }

            if($arCliente && $arClienteDestino) {
                $arGuias = $em->getRepository(TteGuia::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arGuias as $arGuia) {
                    $arGuia->setClienteRel($arClienteDestino);
                    $em->persist($arGuia);
                }

                $arRecogidas = $em->getRepository(TteRecogida::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arRecogidas as $arRecogida) {
                    $arRecogida->setClienteRel($arClienteDestino);
                    $em->persist($arRecogida);
                }

                $arRecogidasProgramadas = $em->getRepository(TteRecogidaProgramada::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arRecogidasProgramadas as $arRecogidaProgramada) {
                    $arRecogidaProgramada->setClienteRel($arClienteDestino);
                    $em->persist($arRecogidaProgramada);
                }

                $arCumplidos = $em->getRepository(TteCumplido::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arCumplidos as $arCumplido) {
                    $arCumplido->setClienteRel($arClienteDestino);
                    $em->persist($arCumplido);
                }

                $arRecaudos = $em->getRepository(TteRecaudoDevolucion::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arRecaudos as $arRecaudo) {
                    $arRecaudo->setClienteRel($arClienteDestino);
                    $em->persist($arRecaudo);
                }

                $arFacturas = $em->getRepository(TteFactura::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arFacturas as $arFactura) {
                    $arFactura->setClienteRel($arClienteDestino);
                    $em->persist($arFactura);
                }

                $arRecibos = $em->getRepository(TteRecibo::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arRecibos as $arRecibo) {
                    $arRecibo->setClienteRel($arClienteDestino);
                    $em->persist($arRecibo);
                }

                $arClientesCondiciones = $em->getRepository(TteClienteCondicion::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arClientesCondiciones as $arClienteCondicion) {
                    $arClienteCondicion->setClienteRel($arClienteDestino);
                    $em->persist($arClienteCondicion);
                }

                $arGuiasTemporales = $em->getRepository(TteGuiaTemporal::class)->findBy(array('codigoClienteFk' => $arCliente->getCodigoClientePk()));
                foreach ($arGuiasTemporales as $arGuiaTemporal) {
                    $arGuiaTemporal->setClienteRel($arClienteDestino);
                    $em->persist($arGuiaTemporal);
                }

                $em->remove($arCliente);
                $em->flush();
                Mensajes::success("El proceso se ejecuto con exito");
            } else {
                Mensajes::error("Debe selecionar el cliente origen y el cliente destino");
            }
        }

        return $this->render('transporte/proceso/general/cliente/unificar.html.twig',
            ['form' => $form->createView()]);
    }

}

