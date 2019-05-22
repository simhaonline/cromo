<?php


namespace App\Controller\RecursoHumano\Administracion\Dotacion;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuDotacionElemento;
use App\Form\Type\RecursoHumano\DotacionElementoType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DotacionElementoController extends BaseController
{
    protected $clase = RhuDotacionElemento::class;
    protected $claseFormulario = DotacionElementoType::class;
    protected $claseNombre = "RhuDotacionElemento";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Dotacion";
    protected $nombre = "DotacionElemento";
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/dotacion/dotacionelemento/lista", name="recursohumano_administracion_dotacion_dotacionelemento_lista")
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
        return $this->render('recursohumano/administracion/dotacion/dotacionelemento/lista.html.twig', [
            'arrDatosLista' =>  $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }



    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/dotacion/dotacionelemento/nuevo/{id}", name="recursohumano_administracion_dotacion_dotacionelemento_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacionElemento =  new RhuDotacionElemento();
        if ($id != 0) {
            $arDotacionElemento = $em->getRepository(RhuDotacionElemento::class)->find($id);
        }
        $form = $this->createForm(DotacionElementoType::class, $arDotacionElemento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arDotacionElemento);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_dotacion_dotacionelemento_detalle', ['id' => $arDotacionElemento->getCodigoDotacionElementoPk()]));
            }else{
                Mensajes::error('Debe se puede registrar el elemento');
            }

        }
        return $this->render('recursohumano/administracion/dotacion/dotacionelemento/nuevo.html.twig', [
            'form' => $form->createView(),
            'arDotacionElemento' => $arDotacionElemento
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/dotacion/dotacionelemento/detalle/{id}", name="recursohumano_administracion_dotacion_dotacionelemento_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacionElemento = $em->getRepository(RhuDotacionElemento::class)->find($id);
        return $this->render('recursohumano/administracion/dotacion/dotacionelemento/detalle.html.twig',[
            'arDotacionElemento'=>$arDotacionElemento
        ]);
    }
}