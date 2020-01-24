<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuTiempo;
use App\Form\Type\RecursoHumano\TiempoType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TiempoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuTiempo";

    protected $clase = RhuTiempo::class;
    protected $claseNombre = "RhuTiempo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "Tiempo";





    /**
     * @Route("recursohumano/adminsitracion/nomina/tiempo/lista", name="recursohumano_administracion_nomina_tiempo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoTiempoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuTiempo ::class)->lista($raw), "Tiempos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuTiempo::class)->eliminar($arrSeleccionados);
            }
        }
        $arTiempos = $paginator->paginate($em->getRepository(RhuTiempo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/tiempo/lista.html.twig', [
            'arTiempos' => $arTiempos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/tiempo/nuevo/{id}", name="recursohumano_administracion_nomina_tiempo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTiempo = new RhuTiempo();
        if ($id != 0 || $id != "") {
            $arTiempo = $em->getRepository(RhuTiempo::class)->find($id);
        }
        $form = $this->createForm(TiempoType::class, $arTiempo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arTiempo = $form->getData();
                $em->persist($arTiempo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_tiempo_detalle', array('id' => $arTiempo->getCodigoTiempoPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/tiempo/nuevo.html.twig', [
            'arTiempo' => $arTiempo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/tiempo/detalle/{id}", name="recursohumano_administracion_nomina_tiempo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTiempo = $em->getRepository(RhuTiempo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/tiempo/detalle.html.twig', [
            'arTiempo' => $arTiempo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoTiempo' => $form->get('codigoTiempoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}