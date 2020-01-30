<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
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

class SoporteFechaController extends MaestroController
{
    public $tipo = "informe";
    public $proceso = "ttei0017";



    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/soporte/fecha", name="transporte_informe_transporte_guia_soporte_fecha")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroFechaSoporteDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaSoporteHasta'))])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('fechaDesde')->getData() != '') {
                $session->set('filtroFechaSoporteDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
            } else {
                $session->set('filtroFechaSoporteDesde', null);
            }
            if ($form->get('fechaHasta')->getData() != '') {
                $session->set('filtroFechaSoporteHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            } else {
                $session->set('filtroFechaSoporteHasta', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteGuia::class)->soporteFecha()->getQuery()->getResult(), "Soporte por fecha");
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->soporteFecha(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/soporteFecha.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }
}

