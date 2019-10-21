<?php


namespace App\Controller\General\Administracion\General;


use App\Entity\General\GenResolucionFactura;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use App\Form\Type\General\ResolucionFacturaType;
use App\Form\Type\RecursoHumano\VacacionTipoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResolucionController extends AbstractController
{
    protected $clase = GenResolucionFactura::class;
    protected $claseNombre = "GenResolucionFactura";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "ResolucionFactura";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("general/adminsitracion/general/resolucion/lista", name="general_administracion_general_resolucion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoResolucionFacturaPk', TextType::class, array('required' => false))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->setMethod('GET')
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
                General::get()->setExportar($em->getRepository(GenResolucionFactura::class)->lista($raw), "Conceptos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(GenResolucionFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('general_administracion_general_resolucion_lista'));
            }
        }
        $arResolucionFacturas = $paginator->paginate($em->getRepository(GenResolucionFactura::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('general/administracion/general/resolucion/lista.html.twig', [
            'arResolucionFacturas' => $arResolucionFacturas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("general/adminsitracion/general/resolucion/nuevo/{id}", name="general_administracion_general_resolucion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arResolucionFactura = $em->getRepository(GenResolucionFactura::class)->find($id);
        if ($id != 0) {
            if (gettype($arResolucionFactura) == null) {
                $arResolucionFactura = new GenResolucionFactura();
            }
        }
        $form = $this->createForm(ResolucionFacturaType::class, $arResolucionFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arResolucionFactura = $form->getData();
                $em->persist($arResolucionFactura);
                $em->flush();
                return $this->redirect($this->generateUrl('general_administracion_general_resolucion_detalle', ['id' => $arResolucionFactura->getCodigoResolucionFacturaPk()]));
            }
        }
        return $this->render('general/administracion/calidad/nuevo.html.twig', [
            'form' => $form->createView(),
            'arResolucionFactura' => $arResolucionFactura
        ]);
    }

    /**
     * @Route("general/adminsitracion/general/resolucion/detalle/{id}", name="general_administracion_general_resolucion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        $arResolucionFactura = $em->getRepository(GenResolucionFactura::class)->find($id);
        return $this->render('general/administracion/general/resolucion/detalle.html.twig', [
            'arResolucionFactura' => $arResolucionFactura,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoResolucionFactura' => $form->get('codigoResolucionFacturaPk')->getData(),
        ];

        return $filtro;

    }

}