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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConceptoController extends ControllerListenerGeneral
{
    protected $clase = RhuConcepto::class;
    protected $claseNombre = "RhuConcepto";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "Concepto";

    /**
     * @Route("recursohumano/adminsitracion/nomina/concepto/lista", name="recursohumano_administracion_nomina_concepto_lista")
     */
    public function lista(Request $request)
    {
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
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Conceptoes");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuConcepto::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_concepto_lista'));
            }
        }
        return $this->render('recursohumano/administracion/nomina/concepto/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
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
                if($arCuenta) {
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

}