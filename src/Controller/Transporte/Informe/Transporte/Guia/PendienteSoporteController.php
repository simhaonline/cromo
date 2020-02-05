<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PendienteSoporteController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "ttei0009";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/pendiente/soporte", name="transporte_informe_transporte_guia_pendiente_soporte")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = New Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('chkEstadoDespachado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTtePendienteSoporteEstadoDespachado'), 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroTtePendienteSoporteFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTtePendienteSoporteFechaHasta'))])
            ->add('txtDespacho', NumberType::class, ['label' => 'Despacho: ', 'required' => false, 'data' => $session->get('filtroNumeroDespacho')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTtePendienteSoporteFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroTtePendienteSoporteFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroTtePendienteSoporteEstadoDespachado', $form->get('chkEstadoDespachado')->getData());
            $session->set('filtroNumeroDespacho', $form->get('txtDespacho')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteGuia::class)->pendienteSoporte()->getQuery()->getResult(), "Pendiente soporte");
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->pendienteSoporte(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/pendienteSoporte.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }
}

