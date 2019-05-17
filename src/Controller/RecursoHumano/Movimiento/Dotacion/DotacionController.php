<?php


namespace App\Controller\RecursoHumano\Movimiento\Dotacion;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuDotacion;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\DotacionType;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DotacionController extends BaseController
{
    protected $clase = RhuDotacion::class;
    protected $claseFormulario = DotacionType::class;
    protected $claseNombre = "RhuDotacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Dotacion";
    protected $nombre = "Dotacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/moviento/dotacion/empleado/lista", name="recursohumano_movimiento_dotacion_dotacion_lista")
     */
    public function lista(Request $request){
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        return $this->render('recursohumano/movimiento/dotacion/dotacion/lista.html.twig', [
            'arrDatosLista' =>  $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }
    
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/moviento/dotacion/dotacion/nuevo/{id}", name="recursohumano_movimiento_dotacion_dotacion_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacion = new RhuDotacion();
        if ($id != 0) {
            $arDotacion = $em->getRepository(RhuDotacion::class)->find($id);
        }
        $form = $this->createForm(DotacionType::class, $arDotacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoEmpleado = $request->request->get('txtCodigoEmpleado');

                if($txtCodigoEmpleado != ""){
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($txtCodigoEmpleado);
                    if ($arEmpleado){
                        $arDotacion->setFecha(new \DateTime('now'));
                        $arDotacion->setEmpleadoRel($arEmpleado);
                        $arDotacion->setCodigoUsuario($this->getUser()->getUserName());
                        $em->persist($arDotacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_dotacion_dotacion_detalle', ['id' => $arDotacion->getCodigoDotacionPk()]));

                    }else {
                        Mensajes::error('Debe seleccionar un empleado');
                    }
                }
            }
        }
        return $this->render('recursohumano/movimiento/dotacion/dotacion/nuevo.html.twig',[
            'form' => $form->createView(),
            'arDotacion' => $arDotacion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/moviento/dotacion/dotacion/detalle/{id}", name="recursohumano_movimiento_dotacion_dotacion_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacion = $em->getRepository(RhuDotacion::class)->find($id);
        return $this->render('recursohumano/movimiento/dotacion/dotacion/detalle.html.twig',[
            'arDotacion'=>$arDotacion
        ]);

    }

}