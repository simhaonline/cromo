<?php

namespace App\Controller\Transporte\Proceso\Venta;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaElectronicaController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttep0019";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/venta/facturaelectronica/lista", name="transporte_proceso_venta_facturaelectronica_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroTteFacturaCodigo')])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroTteFacturaNumero')])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('chkEstadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteFacturaEstadoAnulado'), 'required' => false])
            ->add('cboFacturaTipoRel', EntityType::class, $em->getRepository(TteFacturaTipo::class)->llenarCombo())
            ->add('btnEnviar', SubmitType::class, ['label' => 'Enviar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteFacturaEstadoAnulado', $form->get('chkEstadoAnulado')->getData());
                $session->set('filtroTteFacturaNumero', $form->get('txtNumero')->getData());
                $session->set('filtroTteFacturaCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFecha', $form->get('filtrarFecha')->getData());

                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroTteCodigoCliente', null);
                    $session->set('filtroTteNombreCliente', null);
                }

                $arFacturaTipo = $form->get('cboFacturaTipoRel')->getData();
                if ($arFacturaTipo) {
                    $session->set('filtroTteFacturaCodigoFacturaTipo', $arFacturaTipo->getCodigoFacturaTipoPk());
                } else {
                    $session->set('filtroTteFacturaCodigoFacturaTipo', null);
                }
            }
            if ($form->get('btnEnviar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                if($arr) {
                    $this->getDoctrine()->getRepository(TteFactura::class)->facturaElectronica($arr);
                }

            }
        }
        $arFacturas = $paginator->paginate($em->getRepository(TteFactura::class)->listaFacturaElectronica(), $request->query->getInt('page', 1),500);
        return $this->render('transporte/proceso/venta/facturaElectronica.html.twig',
            ['arFacturas' => $arFacturas,
                'form' => $form->createView()]);
    }

}