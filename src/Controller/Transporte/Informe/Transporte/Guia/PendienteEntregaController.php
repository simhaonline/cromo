<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PendienteEntregaController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "ttei0010";


    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/pendiente/entrega", name="transporte_informe_transporte_guia_pendiente_entrega")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('chkEstadoNovedad', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteGuiaEstadoNovedad'), 'required' => false])
            ->add('chkEstadoDespachado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteGuiaEstadoDespachado'), 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroTtePendienteEntregaFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTtePendienteEntregaFechaHasta'))])
            ->add('txtGuia', NumberType::class, ['label' => 'Guia: ', 'required' => false, 'data' => $session->get('filtroNumeroGuia')])
            ->add('txtConductor', TextType::class, ['label' => 'Conductor: ', 'required' => false, 'data' => $session->get('filtroConductor')])
            ->add('txtDocumentoCliente', TextType::class, ['label' => 'Documento cliente: ', 'required' => false, 'data' => $session->get('filtroDocumentoCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTtePendienteEntregaFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroTtePendienteEntregaFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroNumeroGuia', $form->get('txtGuia')->getData());
            $session->set('filtroConductor', $form->get('txtConductor')->getData());
            $session->set('filtroDocumentoCliente', $form->get('txtDocumentoCliente')->getData());
            $session->set('filtroTteGuiaEstadoNovedad', $form->get('chkEstadoNovedad')->getData());
            $session->set('filtroTteGuiaEstadoDespachado', $form->get('chkEstadoDespachado')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteGuia::class)->pendienteEntrega()->getQuery()->getResult(), "Pendiente entrega");
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->pendienteEntrega(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/pendienteEntrega.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }


}

