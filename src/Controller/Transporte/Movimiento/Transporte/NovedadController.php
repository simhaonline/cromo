<?php
namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use App\General\General;
use App\Utilidades\Estandares;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class NovedadController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "TteNovedad";

    protected $class= TteNovedad::class;
    protected $claseNombre = "TteNovedad";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Novedad";

    /**
     * @Route("/transporte/movimiento/transporte/novedad/lista", name="transporte_movimiento_transporte_novedad_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {    $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', NumberType::class, ['required' => false])
            ->add('guiaNumero', NumberType::class, ['required' => false])
            ->add('fechaReporteDesde', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaReporteHasta', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('codigoNovedadTipoFK',EntityType::class,[
                'class' => TteNovedadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder'=>'Todos',
                'required'=>false
            ])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);

            }
            if ($form->get('btnExcel')->isClicked()){
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TteNovedad::class)->lista($raw)->getQuery()->getResult(), "Novedad");
            }
        }
        $arNovedades = $paginator->paginate($em->getRepository(TteNovedad::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/transporte/novedad/lista.html.twig', [
            'arNovedades'=>$arNovedades,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/novedad/detalle/{id}", name="transporte_movimiento_transporte_novedad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TteNovedad::class)->find($id);
        return $this->render('transporte/movimiento/transporte/novedad/detalle.html.twig', array(
            'arNovedad' => $arNovedad,
        ));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoClienteFk' => $form->get('codigoClienteFk')->getData(),
            'guiaNumero' => $form->get('guiaNumero')->getData(),
            'fechaReporteDesde'=> $form->get('fechaReporteDesde')->getData() ?$form->get('fechaReporteDesde')->getData()->format('Y-m-d'): null,
            'fechaReporteHasta'=> $form->get('fechaReporteHasta')->getData() ?$form->get('fechaReporteHasta')->getData()->format('Y-m-d'): null,
        ];

        $arCodigoNovedadTipo = $form->get('codigoNovedadTipoFK')->getData();
        if (is_object($arCodigoNovedadTipo)) {
            $filtro['codigoNovedadTipoFK'] = $arCodigoNovedadTipo->getCodigoNovedadTipoPk();
        }else{
            $filtro['codigoNovedadTipoFK'] =  $arCodigoNovedadTipo;
        }
        return $filtro;
    }
}

