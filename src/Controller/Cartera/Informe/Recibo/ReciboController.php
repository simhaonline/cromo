<?php

namespace App\Controller\Cartera\Informe\Recibo;

use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboTipo;
use App\General\General;
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

class ReciboController extends AbstractController
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/Cartera/informe/recibo/recibo/lista", name="cartera_informe_recibo_recibo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroInformeReciboFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroInformeReciboFechaHasta'))])
            ->add('cboTipoReciboRel', EntityType::class, $em->getRepository(CarReciboTipo::class)->llenarCombo())
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroCarReciboNumero')])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroCarInformeReciboEstadoAprobado'), 'required' => false])
            ->add('chkEstadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroCarInformeReciboEstadoAnulado'), 'required' => false])
            ->add('chkEstadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroCarInformeReciboEstadoAutorizado'), 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
            $session->set('filtroCarReciboNumero', $form->get('txtNumero')->getData());
            $session->set('filtroCarInformeReciboEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
            $session->set('filtroCarInformeReciboEstadoAnulado', $form->get('chkEstadoAnulado')->getData());
            $session->set('filtroCarInformeReciboEstadoAutorizado', $form->get('chkEstadoAutorizado')->getData());
            $session->set('filtroInformeReciboFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroInformeReciboFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
            if ($form->get('txtCodigoCliente')->getData() != '') {
                $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroCarCodigoCliente', null);
                $session->set('filtroCarNombreCliente', null);
            }
            $arReciboTipo = $form->get('cboTipoReciboRel')->getData();
            if ($arReciboTipo) {
                $session->set('filtroCarInformeReciboTipo', $arReciboTipo->getCodigoReciboTipoPk());
            } else {
                $session->set('filtroCarInformeReciboTipo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(CarRecibo::class)->informe()->getQuery()->getResult(), "Recibos");
        }
        $arRecibos = $paginator->paginate($em->getRepository(CarRecibo::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('cartera/informe/recibo.html.twig', [
            'arRecibos' => $arRecibos,
            'form' => $form->createView()
        ]);
    }
}

