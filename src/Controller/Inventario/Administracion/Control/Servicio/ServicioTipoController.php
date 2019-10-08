<?php

namespace App\Controller\Inventario\Administracion\Control\Servicio;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\GeneralEntityListener;
use App\Entity\Inventario\InvServicioTipo;
use App\Form\Type\Inventario\ServicioTipoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicioTipoController extends AbstractController
{
    protected $clase= InvServicioTipo::class;
    protected $claseFormulario = ServicioTipoType::class;
    protected $claseNombre = "InvServicioTipo";
    protected $modulo   = "Inventario";
    protected $funcion  = "Administracion";
    protected $grupo    = "Control";
    protected $nombre   = "ServicioTipo";
    /**
     * @Route("/inventario/administracion/control/serviciotipo/lista", name="inventario_administracion_control_serviciotipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoServicioTipoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(InvServicioTipo::class)->lista($raw)->getQuery()->execute(), "Servicio tipo");

            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvServicioTipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_administracion_control_serviciotipo_lista'));
            }
        }
        $arServicioTipos = $paginator->paginate($em->getRepository(InvServicioTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/administracion/control/serviciotipo/lista.html.twig', [
            'arServicioTipos' => $arServicioTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/inventario/administracion/control/servicio/nuevo/{id}", name="inventario_administracion_control_serviciotipo_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id == "0" ) {
            $arServicioTipo = new InvServicioTipo();
        } else {
            $arServicioTipo = $em->getRepository(InvServicioTipo::class)->find($id);
        }
        $form = $this->createForm(ServicioTipoType::class, $arServicioTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($em->getRepository('App:Inventario\InvServicioTipo')->find($form->get('codigoServicioTipoPk')->getData()) && $id=="0"){
                    Mensajes::error("Ya existe un Servicio tipo con ese mismo nombre");
                }
                else{
                if($em->getRepository('App:Inventario\InvServicio')->findBy(['codigoServicioTipoFk'=>$id])){
                    Mensajes::error("No se puede actualizar el codigo, el servicio tipo esta siendo utilizada");
                }
                else{

                $arServicioTipo->setCodigoServicioTipoPk($form->get('codigoServicioTipoPk')->getData());
                $arServicioTipo->setNombre($form->get('nombre')->getData());
                $em->persist($arServicioTipo);
                $em->flush();
                    return $this->redirect($this->generateUrl('inventario_administracion_control_serviciotipo_detalle', array('id' => $arServicioTipo->getCodigoServicioTipoPk())));
                }
                }
            }
        }
        return $this->render('inventario/administracion/control/serviciotipo/nuevo.html.twig', [
            'arServicio' => $arServicioTipo,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/administracion/control/servicio/detalle/{id}", name="inventario_administracion_control_serviciotipo_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        return $this->render('inventario/administracion/control/serviciotipo/detalle.html.twig', [
            'arRegistro' => $arRegistro,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoServicioTipo' => $form->get('codigoServicioTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }

}
