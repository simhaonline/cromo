<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinCuenta;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use App\Form\Type\RecursoHumano\ConceptoCuentaType;
use App\Form\Type\RecursoHumano\ConceptoType;
use App\Form\Type\RecursoHumano\VacacionTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VacacionTipoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "RhuVacacionTipo";

    protected $clase = RhuVacacionTipo::class;
    protected $claseNombre = "RhuVacacionTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "VacacionTipo";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/adminsitracion/nomina/vacaciontipo/lista", name="recursohumano_administracion_nomina_vacaciontipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoVacacionTipoPk', TextType::class, array('required' => false))
            ->add('nombreVacacionTipo', TextType::class, array('required' => false))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuVacacionTipo::class)->lista($raw), "Conceptos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuVacacionTipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_vacaciontipo_lista'));
            }
        }
        $arVacacionTipos = $paginator->paginate($em->getRepository(RhuVacacionTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/administracion/nomina/vacacionTipo/lista.html.twig', [
            'arVacacionTipos' => $arVacacionTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/vacaciontipo/nuevo/{id}", name="recursohumano_administracion_nomina_vacaciontipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacionTipo = $em->getRepository(RhuVacacionTipo::class)->find($id);
        if ($id != 0) {
            if (gettype($arVacacionTipo) == null) {
                $arVacacionTipo = new RhuVacacionTipo();
            }
        }
        $form = $this->createForm(VacacionTipoType::class, $arVacacionTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arVacacionTipo = $form->getData();
                $em->persist($arVacacionTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_vacaciontipo_detalle', ['id' => $arVacacionTipo->getCodigoVacacionTipoPk()]));
            }
        }
        return $this->render('recursohumano/administracion/nomina/vacacionTipo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arVacacionTipo' => $arVacacionTipo
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/vacaciontipo/detalle/{id}", name="recursohumano_administracion_nomina_vacaciontipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        $arVacacionTipo = $em->getRepository(RhuVacacionTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/vacacionTipo/detalle.html.twig', [
            'arVacacionTipo' => $arVacacionTipo,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoVacacionTipo' => $form->get('codigoVacacionTipoPk')->getData(),
            'nombreVacacionTipo' => $form->get('nombreVacacionTipo')->getData(),
        ];

        return $filtro;

    }

}