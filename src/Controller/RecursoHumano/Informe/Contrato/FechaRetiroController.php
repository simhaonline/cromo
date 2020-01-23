<?php


namespace App\Controller\RecursoHumano\Informe\Contrato;


use App\Entity\General\GenSegmento;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuGrupo;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FechaRetiroController extends AbstractController
{
    /**
     * @Route("/recursohumano/informe/contrato/fecha/terminacion/lista", name="recursohumano_informe_contrato_fecha_terminacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroInformeTurPedido')
        ];
        $form = $this->createFormBuilder()
            ->add('contratoTipoRel', EntityType::class, [
                'class' => RhuContratoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            ])
            ->add('grupoRel', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaDesde']?date_create($raw['filtros']['fechaDesde']):null ])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaDesde']?date_create($raw['filtros']['fechaDesde']):null ])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuContrato::class)->informeContratoFechaRetiro($raw), "Retiro");
            }
        }
        $arContratos = $paginator->paginate($em->getRepository(RhuContrato::class)->informeContratoFechaRetiro($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/nomina/contrato/FechaRetiro.html.twig', [
            'arContratos' => $arContratos,
            'form' => $form->createView(),
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();

        $filtro = [
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
        ];

        $arContratoTipo = $form->get('contratoTipoRel')->getData();
        $arGrupo = $form->get('grupoRel')->getData();

        if (is_object($arContratoTipo)) {
            $filtro['codigoContratoTipo'] = $arContratoTipo->getCodigoContratoTipoPk();
        } else {
            $filtro['codigoContratoTipo'] = $arContratoTipo;
        }

        if (is_object($arGrupo)) {
            $filtro['codigoGrupo'] = $arGrupo->getCodigoGrupoPk();
        } else {
            $filtro['codigoGrupo'] = $arGrupo;
        }
        $session->set('filtroInformeTurPedido', $filtro);
        return $filtro;

    }
}