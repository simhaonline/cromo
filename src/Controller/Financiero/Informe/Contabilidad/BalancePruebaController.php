<?php

namespace App\Controller\Financiero\Informe\Contabilidad;

use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinSaldo;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Formato\Transporte\ControlFactura;
use App\Formato\Transporte\FacturaInforme;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BalancePruebaController extends MaestroController
{

    public $tipo = "informe";
    public $proceso = "fini0003";



    /**
     * @Route("/financiero/informe/contabilidad/balanceprueba/lista", name="financiero_informe_contabilidad_balanceprueba_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtCuenta', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCuenta'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFinRegistroFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaHasta'))])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroFinCuenta', $form->get('txtCuenta')->getData());
                $session->set('filtroFinSaldoFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFinSaldoFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFinSaldoFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(FinSaldo::class)->balancePrueba())->execute(), "Saldos");
            }
        }
        $query = $this->getDoctrine()->getRepository(FinSaldo::class)->balancePrueba();
        $arSaldos = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('financiero/informe/balance/balance.html.twig', [
            'arSaldos' => $arSaldos,
            'form' => $form->createView() ]);
    }
}

