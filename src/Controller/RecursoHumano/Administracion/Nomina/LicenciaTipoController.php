<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuLicenciaTipo;
use App\Form\Type\RecursoHumano\LicenciaTipoType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LicenciaTipoController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "RhuLicenciaTipo";


    protected $clase = RhuLicenciaTipo::class;
    protected $claseNombre = "RhuLicenciaTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "LicenciaTipo";


    /**
     * @Route("recursohumano/adminsitracion/nomina/licenciaTipo/lista", name="recursohumano_administracion_nomina_licenciaTipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoLicenciaTipoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuLicenciaTipo ::class)->lista($raw), "Licencia tipo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuLicenciaTipo::class)->eliminar($arrSeleccionados);
            }
        }
        $arLicenciaTipos = $paginator->paginate($em->getRepository(RhuLicenciaTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/licenciaTipo/lista.html.twig', [
            'arLicenciaTipos' => $arLicenciaTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/licenciaTipo/nuevo/{id}", name="recursohumano_administracion_nomina_licenciaTipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLicenciaTipo = new RhuLicenciaTipo();
        if ($id != 0 || $id != "") {
            $arLicenciaTipo = $em->getRepository(RhuLicenciaTipo::class)->find($id);
        }
        $form = $this->createForm(LicenciaTipoType::class, $arLicenciaTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arLicenciaTipo = $form->getData();
                $em->persist($arLicenciaTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_licenciaTipo_detalle', array('id' => $arLicenciaTipo->getCodigoLicenciaTipoPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/licenciaTipo/nuevo.html.twig', [
            'arLicenciaTipo' => $arLicenciaTipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/licenciaTipo/detalle/{id}", name="recursohumano_administracion_nomina_licenciaTipo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLicenciaTipo = $em->getRepository(RhuLicenciaTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/licenciaTipo/detalle.html.twig', [
            'arLicenciaTipo' => $arLicenciaTipo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoLicenciaTipo' => $form->get('codigoLicenciaTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}