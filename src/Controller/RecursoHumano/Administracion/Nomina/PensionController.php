<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuPension;
use App\Form\Type\RecursoHumano\PensionType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PensionController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuPension";

    protected $clase = RhuPension::class;
    protected $claseNombre = "RhuPension";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "Pension";



    /**
     * @Route("recursohumano/adminsitracion/nomina/pension/lista", name="recursohumano_administracion_nomina_pension_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoPensionPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
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
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuPension ::class)->lista($raw), "Pension");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuPension::class)->eliminar($arrSeleccionados);
            }
        }
        $arPensiones = $paginator->paginate($em->getRepository(RhuPension::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/pension/lista.html.twig', [
            'arPensiones' => $arPensiones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/pension/nuevo/{id}", name="recursohumano_administracion_nomina_pension_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPension = new RhuPension();
        if ($id != 0 || $id != "") {
            $arPension = $em->getRepository(RhuPension::class)->find($id);
        }
        $form = $this->createForm(PensionType::class, $arPension);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arPension = $form->getData();
                $em->persist($arPension);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_pension_detalle', array('id' => $arPension->getCodigoPensionPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/pension/nuevo.html.twig', [
            'arPension' => $arPension,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/pension/detalle/{id}", name="recursohumano_administracion_nomina_pension_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPension = $em->getRepository(RhuPension::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/pension/detalle.html.twig', [
            'arPension' => $arPension,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoPension' => $form->get('codigoPensionPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}