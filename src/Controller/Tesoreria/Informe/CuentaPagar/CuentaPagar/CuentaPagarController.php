<?php

namespace App\Controller\Tesoreria\Informe\CuentaPagar\CuentaPagar;





use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Formato\Tesoreria\CarteraEdad;
use App\Formato\Tesoreria\CuentaPagar;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CuentaPagarController extends Controller
{
    /**
     * @Route("/tesoreria/informe/cuentapagar/cuentapagar/pendiente", name="tesoreria_informe_cuentapagar_cuentapagar_pendiente")
     */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnEstadoCuenta', SubmitType::class, array('label' => 'Estado cuenta'))
            ->add('btnGenerarVencimientos', SubmitType::class, array('label' => 'Generar rango'))
            ->add('btnCarteraEdadesCliente', SubmitType::class, array('label' => 'Cartera edades'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroTesCuentaPagarNumero')])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroTesNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroTesCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTesNombreTercero'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnEstadoCuenta')->isClicked() || $form->get('btnCarteraEdadesCliente')->isClicked() || $form->get('btnExcel')->isClicked()) {
            if ($request->get('cboTipoCuentaRel')){
                $codigosCuenta = null;
                foreach ($request->get('cboTipoCuentaRel') as $codigo){
                    $codigosCuenta .= "'{$codigo}',";
                }
                $session->set('filtroTesCuentaPagarTipo', substr($codigosCuenta, 0, -1));
                $session->set('selectCuentaPagarTipo', $request->get('cboTipoCuentaRel'));
            }else{
                $session->set('filtroTesCuentaPagarTipo', null);
                $session->set('selectCuentaPagarTipo', null);

            }
            $session->set('filtroTesNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
            $session->set('filtroTesCuentaPagarNumero', $form->get('txtNumero')->getData());
            if (is_numeric($form->get('txtCodigoTercero')->getData())) {
                $session->set('filtroTesCodigoTercero', $form->get('txtCodigoTercero')->getData());
            } else {
                $session->set('filtroTesCodigoTercero', null);
            }

            $session->set('filtroTesNombreTercero', $form->get('txtNombreCorto')->getData());
            $session->set('filtroFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
        }
        if ($form->get('btnEstadoCuenta')->isClicked()) {
            $formato = new CuentaPagar();
            $formato->Generar($em);
        }
        $session->set('arrCuentasPagar', $request->request->get('ChkSeleccionar'));
        if ($form->get('btnCarteraEdadesCliente')->isClicked()) {
            $formato = new CarteraEdad();
            $formato->Generar($em);
        }
        if ($form->get('btnGenerarVencimientos')->isClicked()) {
            $em->getRepository(TesCuentaPagar::class)->generarVencimientos();
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->createQuery($em->getRepository(TesCuentaPagar::class)->pendientes())->execute(), "Cuenta pagar");
        }
        $query = $this->getDoctrine()->getRepository(TesCuentaPagar::class)->pendientes();
        $arCuentasPagar = $paginator->paginate($query, $request->query->getInt('page', 1), 50);
        $cboTipoCuentaRel = $em->getRepository(TesCuentaPagarTipo::class)->selectCodigoNombre();
        return $this->render('tesoreria/informe/cuentapagar/pendientes.html.twig', [
            'arCuentasPagar' => $arCuentasPagar,
            'cboTipoCuentaRel'=>$cboTipoCuentaRel,
            'form' => $form->createView()]);
    }
    


}

