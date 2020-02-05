<?php

namespace App\Controller\Transporte\Informe\Financiero;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Formato\Transporte\Rentabilidad;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CostosController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "ttei0030";


    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/financiero/general/costos/", name="transporte_informe_financiero_general_costos")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('txtAnio', NumberType::class)
            ->add('txtMes', NumberType::class)
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $arCostos = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    if ($form->get('txtAnio')->getData() && $form->get('txtMes')->getData()) {
                        $anio = $form->get('txtAnio')->getData();
                        $mes = $form->get('txtMes')->getData();
                        $queryBuilder = $this->getDoctrine()->getRepository(TteCosto::class)->listaInforme($anio, $mes);
                        $arCostos = $queryBuilder->getQuery()->getResult();
                        $arCostos = $paginator->paginate($arCostos, $request->query->getInt('page', 1), 1000);
                    }
                }
                if ($form->get('btnExcel')->isClicked()) {
                    if ($form->get('txtAnio')->getData() && $form->get('txtMes')->getData()) {
                        $anio = $form->get('txtAnio')->getData();
                        $mes = $form->get('txtMes')->getData();
                        General::get()->setExportar($em->getRepository(TteCosto::class)->listaInforme($anio, $mes)->getQuery()->getResult(), "Costos");
                    }
                }
            }
        }

        return $this->render('transporte/informe/financiero/costos.html.twig', [
            'arCostos' => $arCostos,
            'form' => $form->createView()]);
    }

}

