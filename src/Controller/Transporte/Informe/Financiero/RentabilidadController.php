<?php

namespace App\Controller\Transporte\Informe\Financiero;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteOperacion;
use App\Formato\Transporte\Rentabilidad;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RentabilidadController extends MaestroController
{


    public $tipo = "informe";
    public $proceso = "ttei0029";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/financiero/despacho/rentabilidad", name="transporte_informe_financiero_despacho_rentabilidad")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => $fecha])
            ->add('cboDespachoTipoRel', EntityType::class, $em->getRepository(TteDespachoTipo::class)->llenarCombo())
            ->add('codigoVehiculo', TextType::class, array('required' => false))
            ->add('centroOperacion', EntityType::class, [
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gt')
                        ->orderBy('gt.codigoOperacionPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $arDespachos = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnPdf')->isClicked()) {
                    if ($form->get('fechaDesde')->getData() && $form->get('fechaHasta')->getData()) {
                        $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                        $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                        $arDespachoTipo = $form->get('cboDespachoTipoRel')->getData();
                        $arCentroOperacion = $form->get('centroOperacion')->getData();
                        if ($arDespachoTipo) {
                            $session->set('filtroTteDespachoCodigoDespachoTipo', $arDespachoTipo->getCodigoDespachoTipoPk());
                        } else {
                            $session->set('filtroTteDespachoCodigoDespachoTipo', null);
                        }
                        if ($arCentroOperacion) {
                            $session->set('filtroTteDespachoCodigoCentroOperacion', $arCentroOperacion->getCodigoOperacionPk());
                        } else {
                            $session->set('filtroTteDespachoCodigoCentroOperacion', null);
                        }
                        $session->set('filtroTteDespachoCodigoVehiculo', $form->get('codigoVehiculo')->getData());
                        $queryBuilder = $this->getDoctrine()->getRepository(TteDespacho::class)->rentabilidad($fechaDesde, $fechaHasta);
                        $arDespachos = $queryBuilder->getQuery()->getResult();
                        $arDespachos = $paginator->paginate($arDespachos, $request->query->getInt('page', 1), 1000);
                    }
                }
                if ($form->get('btnPdf')->isClicked()) {
                    $formato = new Rentabilidad();
                    $formato->Generar($em, $form->get('fechaDesde')->getData()->format('Y-m-d'), $form->get('fechaHasta')->getData()->format('Y-m-d'));
                }
                if ($form->get('btnExcel')->isClicked()) {
                    $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                    $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                    General::get()->setExportar($em->getRepository(TteDespacho::class)->rentabilidad($fechaDesde, $fechaHasta)->getQuery()->getResult(), "Desembarcos");
                }
            }
        }

        return $this->render('transporte/informe/financiero/rentabilidad.html.twig', [
            'arDespachos' => $arDespachos,
            'form' => $form->createView()]);
    }

}

