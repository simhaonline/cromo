<?php

namespace App\Controller\Transporte\Administracion\Poseedor;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TtePoseedor;
use App\Form\Type\Transporte\PoseedorType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PoseedorController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "TtePoseedor";

    protected $clase = TtePoseedor::class;
    protected $claseNombre = "TtePoseedor";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Poseedor";
    protected $nombre = "Poseedor";

    /**
     * @Route("/transporte/administracion/poseedor/lista", name="transporte_administracion_poseedor_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoPoseedor', TextType::class, ['label' => 'Codigo cliente: ', 'required' => false, 'data' => $session->get('filtroTteCodigoPoseedor')])
            ->add('txtNombreCorto', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNombrePoseedor')])
            ->add('txtNumeroIdentificacion', NumberType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroNumeroIdentificacionPoseedor')])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteCodigoPoseedor', $form->get('txtCodigoPoseedor')->getData());
            $session->set('filtroTteNombrePoseedor', $form->get('txtNombreCorto')->getData());
            $session->set('filtroNumeroIdentificacionPoseedor', $form->get('txtNumeroIdentificacion')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TtePoseedor::class)->lista()->getQuery()->getResult(), "Poseedor");
        }
        $arPoseedores = $paginator->paginate($em->getRepository(TtePoseedor::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('transporte/administracion/poseedor/lista.html.twig',
            ['arPoseedores' => $arPoseedores,
                'form' => $form->createView()]);

    }

    /**
     * @Route("/transporte/administracion/poseedor/nuevo/{id}", name="transporte_administracion_transporte_poseedor_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPoseedor = new TtePoseedor();
        if ($id != '0') {
            $arPoseedor = $em->getRepository(TtePoseedor::class)->find($id);
            if (!$arPoseedor) {
                return $this->redirect($this->generateUrl('transporte_administracion_poseedor_lista'));
            }
        }
        $form = $this->createForm(PoseedorType::class, $arPoseedor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked() || $form->get('guardarnuevo')->isClicked()) {
                /*$arPoseedor = $em->getRepository(TtePoseedor::class)->findOneBy(['numeroIdentificacion'=> $form->get('numeroIdentificacion')->getData()]);
                if ($id == 0 and is_object($arPoseedor) ){
                    Mensajes::info("El número de identificación ya esta registrado");
                }*/
                $arPoseedor->setNombre1($form->get('nombre1')->getData());
                $arPoseedor->setNombre2($form->get('nombre2')->getData());
                $arPoseedor->setApellido1($form->get('apellido1')->getData());
                $arPoseedor->setApellido2($form->get('apellido2')->getData());
                $arPoseedor->setCodigoIdentificacionFk($form->get('identificacionRel')->getData()->getCodigoIdentificacionPk());
                $arPoseedor->setNumeroIdentificacion($form->get('numeroIdentificacion')->getData());
                $arPoseedor->setDireccion($form->get('direccion')->getData());
                $arPoseedor->setCorreo($form->get('correo')->getData());
                $arPoseedor->setTelefono($form->get('telefono')->getData());
                $arPoseedor->setMovil($form->get('movil')->getData());
                $arPoseedor->setNombreCorto($arPoseedor->getNombre1() . " " . $arPoseedor->getNombre2() . " " . $arPoseedor->getApellido1() . " " . $arPoseedor->getApellido2());
                $em->persist($arPoseedor);
                $em->flush();
                if ($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('transporte_administracion_transporte_poseedor_nuevo', ['id' => 0]));
                } else {

                    return $this->redirect($this->generateUrl('transporte_administracion_poseedor_lista'));
                }
            }
        }
        return $this->render('transporte/administracion/poseedor/nuevo.html.twig', [
            'arPoseedor' => $arPoseedor,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/administracion/poseedor/detalle/{id}", name="transporte_administracion_transporte_poseedor_detalle")
     */
    public function detalle()
    {
        return $this->redirect($this->generateUrl('transporte_administracion_poseedor_lista'));
    }
}
