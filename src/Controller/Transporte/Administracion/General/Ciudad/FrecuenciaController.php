<?php

namespace App\Controller\Transporte\Administracion\General\Ciudad;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteFrecuencia;
use App\Form\Type\Transporte\CiudadType;
use App\Form\Type\Transporte\FrecuenciaType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrecuenciaController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "TteFrecuencia";

    protected $clase = TteFrecuencia::class;
    protected $claseNombre = "TteFrecuencia";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Frecuencia";

    /**
     * @Route("/transporte/administracion/general/frecuencia/lista", name="transporte_administracion_general_frecuencia_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
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
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltro($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltro($form);
                General::get()->setExportar($em->getRepository(TteFrecuencia::class)->lista($raw), "Frecuencia");
            }
        }
        $arFrecuencias = $paginator->paginate($em->getRepository(TteFrecuencia::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/administracion/general/frecuencia/lista.html.twig', [
            'arFrecuencias' => $arFrecuencias,
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/administracion/general/frecuencia/nuevo/{id}", name="transporte_administracion_general_frecuencia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFrecuencia = new TteFrecuencia();
        if ($id != 0) {
            $arFrecuencia = $em->getRepository(TteFrecuencia::class)->find($id);
        }
        $form = $this->createForm(FrecuenciaType::class, $arFrecuencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arFrecuencia);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_general_frecuencia_detalle', ['id' => $arFrecuencia->getCodigoFrecuenciaPk()]));

            }
        }
        return $this->render('transporte/administracion/general/frecuencia/nuevo.html.twig',
            ['arFrecuencia' => $arFrecuencia, 'form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/administracion/general/frecuencia/detalle/{id}", name="transporte_administracion_general_frecuencia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFrecuencia = $em->getRepository(TteFrecuencia::class)->find($id);

        return $this->render('transporte/administracion/general/frecuencia/detalle.html.twig', [
            'arFrecuencia' => $arFrecuencia,
        ]);
    }

    public function getFiltro($form)
    {

    }
}
