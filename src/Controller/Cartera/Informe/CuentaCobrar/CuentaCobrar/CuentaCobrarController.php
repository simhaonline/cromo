<?php

namespace App\Controller\Cartera\Informe\CuentaCobrar\CuentaCobrar;

use App\Controller\MaestroController;
use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCompromiso;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\General\GenAsesor;
use App\Form\Type\Cartera\CompromisoType;
use App\Formato\Cartera\CarteraEdad;
use App\Formato\Cartera\CarteraEdadCliente;
use App\Formato\Cartera\CuentaCobrar;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CuentaCobrarController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "cari0001";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/informe/cuentaCobrar/cuentaCobrar/pendiente", name="cartera_informe_cuentaCobrar_cuentaCobrar_pendiente")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnEstadoCuenta', SubmitType::class, array('label' => 'Estado cuenta'))
            ->add('btnGenerarVencimientos', SubmitType::class, array('label' => 'Generar rango'))
            ->add('btnCarteraEdadesCliente', SubmitType::class, array('label' => 'Cartera edades'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('cboAsesor', EntityType::class, $em->getRepository(GenAsesor::class)->llenarCombo())
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCuentaCobrarNumero')])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnEstadoCuenta')->isClicked() || $form->get('btnCarteraEdadesCliente')->isClicked() || $form->get('btnExcel')->isClicked()) {
            if ($request->get('cboTipoCuentaRel')){
                $codigosCuenta = null;
                foreach ($request->get('cboTipoCuentaRel') as $codigo){
                    $codigosCuenta .= "'{$codigo}',";
                }
                $session->set('filtroCarCuentaCobrarTipo', substr($codigosCuenta, 0, -1));
                $session->set('selectCuentaCobrarTipo', $request->get('cboTipoCuentaRel'));
            }else{
                $session->set('filtroCarCuentaCobrarTipo', null);
                $session->set('selectCuentaCobrarTipo', null);

            }
            $arAsesor = $form->get('cboAsesor')->getData();
            if ($arAsesor != '') {
                $session->set('filtroGenAsesor', $form->get('cboAsesor')->getData()->getCodigoAsesorPk());
            } else {
                $session->set('filtroGenAsesor', null);
            }
            $session->set('filtroCarNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
            $session->set('filtroCarCuentaCobrarNumero', $form->get('txtNumero')->getData());
            if (is_numeric($form->get('txtCodigoCliente')->getData())) {
                $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
            } else {
                $session->set('filtroCarCodigoCliente', null);
            }

            $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
            $session->set('filtroFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
        }
        if ($form->get('btnEstadoCuenta')->isClicked()) {
            $formato = new CuentaCobrar();
            $formato->Generar($em);
        }
        $session->set('arrCuentasCobrar', $request->request->get('ChkSeleccionar'));
        if ($form->get('btnCarteraEdadesCliente')->isClicked()) {
            $formato = new CarteraEdadCliente();
            $formato->Generar($em);
        }
        if ($form->get('btnGenerarVencimientos')->isClicked()) {
            $em->getRepository(CarCuentaCobrar::class)->generarVencimientos();
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(CarCuentaCobrar::class)->pendientes()->getQuery()->getResult(), "Cuenta cobrar");
        }
        $query = $this->getDoctrine()->getRepository(CarCuentaCobrar::class)->pendientes();
        $arCuentasCobrar = $paginator->paginate($query, $request->query->getInt('page', 1), 50);
        $cboTipoCuentaRel = $em->getRepository(CarCuentaCobrarTipo::class)->selectCodigoNombre();
        return $this->render('cartera/informe/cuentaCobrar/pendientes.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'cboTipoCuentaRel'=>$cboTipoCuentaRel,
            'form' => $form->createView()]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/informe/cuentaCobrar/cuentaCobrar/aplicar/{id}", name="cartera_informe_cuentaCobrar_cuentaCobrar_aplicar")
     */
    public function aplicar(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($request->request->get('OpAplicar')) {
                    set_time_limit(0);
                    ini_set("memory_limit", -1);
                    $arrControles = $request->request->All();
                    $codigoCuentaCobrarAplicar = $request->request->get('OpAplicar');
                    $vrAplicar = isset($arrControles['TxtSaldo' . $codigoCuentaCobrarAplicar]) && $arrControles['TxtSaldo' . $codigoCuentaCobrarAplicar] != '' ? $arrControles['TxtSaldo' . $codigoCuentaCobrarAplicar] : 0;

                    if ($arCuentaCobrar->getVrSaldo() >= $vrAplicar) {
                        if (is_numeric($vrAplicar)) {
                            $saldo = $arCuentaCobrar->getVrSaldo() - $vrAplicar;
                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                            $arCuentaCobrar->setVrSaldo($saldo);
                            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $vrAplicar);
                            $em->persist($arCuentaCobrar);

                            $arCuentaCobrarAplicar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrarAplicar);
                            $saldo = $arCuentaCobrarAplicar->getVrSaldo() - $vrAplicar;
                            $saldoOperado = $saldo * $arCuentaCobrarAplicar->getOperacion();
                            $arCuentaCobrarAplicar->setVrSaldo($saldo);
                            $arCuentaCobrarAplicar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrarAplicar->setVrAbono($arCuentaCobrarAplicar->getVrAbono() + $vrAplicar);
                            $em->persist($arCuentaCobrarAplicar);

                            $arAplicacion = new CarAplicacion();
                            $arAplicacion->setCuentaCobrarRel($arCuentaCobrar);
                            $arAplicacion->setCuentaCobrarAplicacionRel($arCuentaCobrarAplicar);
                            $arAplicacion->setVrAplicacion($vrAplicar);
                            $arAplicacion->setUsuario($this->getUser()->getUsername());
                            $arAplicacion->setFecha(new \DateTime('now'));
                            $arAplicacion->setNumeroDocumento($arCuentaCobrar->getNumeroDocumento());
                            $arAplicacion->setNumeroDocumentoAplicacion($arCuentaCobrarAplicar->getNumeroDocumento());
                            $em->persist($arAplicacion);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        } else {
                            Mensajes::error("El valor agregado no es numerico o contiene caracteres que no son permitidos");
                        }
                    } else {
                        Mensajes::error("El valor a aplicar" . $vrAplicar . " es mayor al saldo " . $arCuentaCobrar->getVrSaldo());
                    }
                }
            }
        }
        $arCuentasCobrarAplicar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrarAplicar($arCuentaCobrar);
        $arCuentasCobrarAplicar = $paginator->paginate($arCuentasCobrarAplicar, $request->query->get('page', 1), 50);
        return $this->render('cartera/informe/cuentaCobrar/aplicar.html.twig', [
            'arCuentaCobrar' => $arCuentaCobrar,
            'arCuentasCobrarAplicar' => $arCuentasCobrarAplicar,
            'form' => $form->createView()
        ]);
    }


}

