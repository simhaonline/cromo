<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteDocumental;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Form\Type\Transporte\CumplidoType;
use App\Form\Type\Transporte\DocumentalType;
use App\Formato\Transporte\Cumplido;
use App\Formato\Transporte\CumplidoEntrega;
use App\Formato\Transporte\Documental;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DocumentalController extends ControllerListenerGeneral
{
    protected $clase = TteDocumental::class;
    protected $claseNombre = "TteDocumental";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Documental";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/transporte/documental/lista", name="transporte_movimiento_transporte_documental_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Documental");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteDocumental::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_lista'));
            }
        }

        return $this->render('transporte/movimiento/transporte/documental/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/transporte/documental/detalle/{id}", name="transporte_movimiento_transporte_documental_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumental = $em->getRepository(TteDocumental::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arDocumental->getEstadoAutorizado(), $arDocumental->getEstadoAprobado(), $arDocumental->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arDocumental->getEstadoAutorizado()) {
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Documental();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteDocumental::class)->autorizar($arDocumental);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteDocumental::class)->desAutorizar($arDocumental);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteDocumental::class)->Aprobar($arDocumental);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(TteDocumental::class)->Anular($arDocumental);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDocumental::class)->retirarGuia($arrGuias);
                if ($respuesta) {
                    $em->flush();
                    $em->getRepository(TteDocumental::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->documental($id), "Documental $id");
            }
        }

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->documental($id);
        return $this->render('transporte/movimiento/transporte/documental/detalle.html.twig', [
            'arDocumental' => $arDocumental,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $codigoDocumental
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/transporte/movimiento/transporte/documental/detalle/adicionar/guia/{codigoDocumental}", name="transporte_movimiento_transporte_documental_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoDocumental)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumental = $em->getRepository(TteDocumental::class)->find($codigoDocumental);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setDocumentalRel($arDocumental);
                    $arGuia->setEstadoDocumental(1);
                    $arGuia->setFechaDocumental(new \DateTime('now'));
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteDocumental::class)->liquidar($codigoDocumental);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->documentalPendiente();
        return $this->render('transporte/movimiento/transporte/documental/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/documental/nuevo/{id}", name="transporte_movimiento_transporte_documental_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDocumental = new TteDocumental();
        if ($id != 0) {
            $arDocumental = $em->getRepository(TteDocumental::class)->find($id);
        }
        $form = $this->createForm(DocumentalType::class, $arDocumental);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fecha = new \DateTime('now');
            $arDocumental->setFecha($fecha);
            $em->persist($arDocumental);
            $em->flush();
            return $this->redirect($this->generateUrl('transporte_movimiento_transporte_documental_detalle', ['id' => $arDocumental->getCodigoDocumentalPk()]));
        }
        return $this->render('transporte/movimiento/transporte/documental/nuevo.html.twig', [
            'arDocumental' => $arDocumental,
            'form' => $form->createView()]);
    }
}

