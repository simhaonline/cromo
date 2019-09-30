<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinCuenta;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Form\Type\RecursoHumano\ConceptoCuentaType;
use App\Form\Type\RecursoHumano\ConceptoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConceptoController extends AbstractController
{
    protected $clase = RhuConcepto::class;
    protected $claseNombre = "RhuConcepto";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "Concepto";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/adminsitracion/nomina/concepto/lista", name="recursohumano_administracion_nomina_concepto_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoConceptoPk', TextType::class, array('required' => false))
            ->add('nombreConcepto', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuConcepto::class)->lista($raw), "Conceptos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuConcepto::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_concepto_lista'));
            }
        }
        $arConceptos = $paginator->paginate($em->getRepository(RhuConcepto::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/administracion/nomina/concepto/lista.html.twig', [
            'arConceptos' => $arConceptos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/concepto/nuevo/{id}", name="recursohumano_administracion_nomina_concepto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConcepto = $em->getRepository(RhuConcepto::class)->find($id);
        if ($id != 0) {
            if (gettype($arConcepto) == null) {
                $arConcepto = new RhuConcepto();
            }
        }
        $form = $this->createForm(ConceptoType::class, $arConcepto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arConcepto = $form->getData();
                $em->persist($arConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_concepto_detalle', ['id' => $arConcepto->getCodigoConceptoPk()]));
            }
        }
        return $this->render('recursohumano/administracion/nomina/concepto/nuevo.html.twig', [
            'form' => $form->createView(),
            'arConcepto' => $arConcepto
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/concepto/detalle/{id}", name="recursohumano_administracion_nomina_concepto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuConceptoCuenta::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_concepto_detalle', ['id' => $id]));
            }
        }
        $arConcepto = $em->getRepository(RhuConcepto::class)->find($id);
        $arConceptoCuentas = $em->getRepository(RhuConceptoCuenta::class)->findBy(['codigoConceptoFk' => $id]);
        return $this->render('recursohumano/administracion/nomina/concepto/detalle.html.twig', [
            'arConcepto' => $arConcepto,
            'arConceptoCuentas' => $arConceptoCuentas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recursohumano/adminsitracion/nomina/concepto/detalle/nuevo/{id}/{codigoConcepto}", name="recursohumano_administracion_nomina_concepto_detalle_nuevo")
     */
    public function detalleNuevoAction(Request $request, $id, $codigoConcepto)
    {

        $em = $this->getDoctrine()->getManager();
        $arConcepto = $em->getRepository(RhuConcepto::class)->find($codigoConcepto);
        $arConceptoCuenta = new RhuConceptoCuenta();
        if ($id != 0) {
            $arConceptoCuenta = $em->getRepository(RhuConceptoCuenta::class)->find($id);
        } else {
            $arConceptoCuenta->setConceptoRel($arConcepto);
        }
        $form = $this->createForm(ConceptoCuentaType::class, $arConceptoCuenta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCuenta = $em->getRepository(FinCuenta::class)->find($arConceptoCuenta->getCodigoCuentaFk());
                if ($arCuenta) {
                    $em->persist($arConceptoCuenta);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    Mensajes::error("La cuenta no existe en el plan de cuentas");
                }
            }
        }

        return $this->render('recursohumano/administracion/nomina/concepto/detalleNuevo.html.twig', array(
            'arConcepto' => $arConcepto,
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoConcepto' => $form->get('codigoConceptoPk')->getData(),
            'nombreConcepto' => $form->get('nombreConcepto')->getData(),
        ];

        return $filtro;

    }

}