<?php

namespace App\Controller\Transporte\Administracion\General\Ciudad;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCiudad;
use App\Form\Type\Transporte\CiudadType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CiudadController extends AbstractController
{
    protected $clase= TteCiudad::class;
    protected $claseNombre = "TteCiudad";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Ciudad";

    /**
     * @Route("/transporte/administracion/general/ciudad/lista", name="transporte_administracion_general_ciudad_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('nombre', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(TteCiudad::class)->lista($raw)->getQuery()->execute(), "Facturas");
            }
        }
        $arCiudades = $paginator->paginate($em->getRepository(TteCiudad::class)->lista($raw), $request->query->getInt('page', 1), 30);


        return $this->render('transporte/administracion/general/ciudad/lista.html.twig', [
            'arCiudades'=>$arCiudades,
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/administracion/general/ciudad/nuevo/{id}", name="transporte_administracion_general_ciudad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCiudad = new TteCiudad();
        if ($id != 0) {
            $arCiudad = $em->getRepository(TteCiudad::class)->find($id);
        }
        $form = $this->createForm(CiudadType::class, $arCiudad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCiudad);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_general_ciudad_detalle',['id'=>$arCiudad->getCodigoCiudadPk()]));

            }
            if($form->get('guardarnuevo')->isClicked()){
                $em->persist($arCiudad);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_general_ciudad_nuevo',['id'=>0]));

            }
        }
        return $this->render('transporte/administracion/general/ciudad/nuevo.html.twig',
            ['arCiudad' => $arCiudad, 'form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/administracion/general/ciudad/detalle/{id}", name="transporte_administracion_general_ciudad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCiudad = $em->getRepository(TteCiudad::class)->find($id);

        return $this->render('transporte/administracion/general/ciudad/detalle.html.twig',[
            'arCiudad'=>$arCiudad,
        ]);
    }

    public function getFiltro($form){

        return $filtro = [
            'nombre' => $form->get('nombre')->getData(),
        ];

    }
}
