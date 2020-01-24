<?php

namespace App\Controller\Financiero\Movimiento\Contabilidad\Registro;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Financiero\AsientoType;
use App\Form\Type\Financiero\RegistroType;
use App\Formato\Financiero\Asiento;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RegistroController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "FinRegistro";


    protected $clase = FinRegistro::class;
    protected $claseNombre = "FinRegistro";
    protected $modulo = "Financiero";
    protected $funcion = "Movimiento";
    protected $grupo = "Contabilidad";
    protected $nombre = "Registro";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/movimiento/contabilidad/registro/lista", name="financiero_movimiento_contabilidad_registro_lista")
     */
	public function lista(Request $request, PaginatorInterface $paginator)
    {
	    $em = $this->getDoctrine()->getManager();
	    $form = $this->createFormBuilder()
		    ->add('codigoTerceroFk', TextType::class, array('required' => false))
		    ->add('numero', TextType::class, array('required' => false))
		    ->add('codigoComprobanteFk', EntityType::class, [
			    'class' => FinComprobante::class,
			    'query_builder' => function (EntityRepository $er) {
				    return $er->createQueryBuilder('c')
					    ->orderBy('c.codigoComprobantePk', 'ASC');
			    },
			    'required' => false,
			    'choice_label' => 'nombre',
			    'placeholder' => 'TODOS'
		    ])
		    ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
		    ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
		    ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
		    ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
		    ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
		    ->add('estadoIntercambio', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
		    ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
		    ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
		    ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
		    ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
		    ->setMethod('GET')
		    ->getForm();
	    $form->handleRequest($request);
	    $raw = [
		    'limiteRegistros' => $form->get('limiteRegistros')->getData()
	    ];
	    if ($form->isSubmitted() ) {
	        if ($form->get('btnFiltrar')->isClicked()) {
		        $raw['filtros'] = $this->getFiltros($form);
	        }
            if ($form->get('btnExcel')->isClicked()) {
	            $raw['filtros'] = $this->getFiltros($form);
	            General::get()->setExportar($em->getRepository(FinRegistro::class)->lista($raw), "Registros");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(FinRegistro::class)->eliminar($arrSeleccionados);
            }
        }
	    $arRegistros = $paginator->paginate($em->getRepository(FinRegistro::class)->lista($raw), $request->query->getInt('page', 1), 30);

	    return $this->render('financiero/movimiento/contabilidad/registro/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/registro/nuevo/{id}", name="financiero_movimiento_contabilidad_registro_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new FinRegistro();
        if ($id != 0) {
            $arRegistro = $em->getRepository($this->clase)->find($id);
        } else {
            $arRegistro->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RegistroType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_movimiento_contabilidad_registro_detalle', ['id' => $arRegistro->getCodigoRegistroPk()]));
            }
        }
        return $this->render('financiero/movimiento/contabilidad/registro/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/registro/detalle/{id}", name="financiero_movimiento_contabilidad_registro_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(FinRegistro::class)->find($id);
        $form = Estandares::botonera(false, false, false);
        $form->handleRequest($request);
        return $this->render('financiero/movimiento/contabilidad/registro/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

	public function getFiltros($form)
	{
		$filtro = [
			'codigoTercero' => $form->get('codigoTerceroFk')->getData(),
			'numero' => $form->get('numero')->getData(),
			'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
			'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
			'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
			'estadoAprobado' => $form->get('estadoAprobado')->getData(),
			'estadoAnulado' => $form->get('estadoAnulado')->getData(),
			'estadoIntercambio' => $form->get('estadoIntercambio')->getData(),
		];
		$arComprobante = $form->get('codigoComprobanteFk')->getData();

		if (is_object($arComprobante)) {
			$filtro['codigoComprobante'] = $arComprobante->getCodigoComprobantePk();
		} else {
			$filtro['codigoComprobante'] = $arComprobante;
		}

		return $filtro;

	}
}