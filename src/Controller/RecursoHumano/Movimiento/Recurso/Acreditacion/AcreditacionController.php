<?php

namespace App\Controller\RecursoHumano\Movimiento\Recurso\Acreditacion;


use App\DataFixtures\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAcreditacion;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\AcreditacionAcreditarType;
use App\Form\Type\RecursoHumano\AcreditacionType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AcreditacionController extends AbstractController
{
    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/lista", name="recursohumano_movimiento_recurso_acreditacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoAcreditacionPk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaDesdeVenceCurso', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHastaVenceCurso', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoRechazado', ChoiceType::class, array('choices' => array('TODOS' => '', 'RECHAZADO' => 1, 'SIN RECHAZAR' => 0), 'required' => false))
            ->add('estadoValidado', ChoiceType::class, array('choices' => array('TODOS' => '', 'VALIDADO' => 1, 'SIN VALIDAR' => 0), 'required' => false))
            ->add('estadoAcreditado', ChoiceType::class, array('choices' => array('TODOS' => '', 'ACREDITADO' => 1, 'SIN ACREDITAR' => 0), 'required' => false))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnInformeApo', SubmitType::class, array('label' => 'Informe apo'))
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
                General::get()->setExportar($em->getRepository(RhuAcreditacion::class)->lista($raw)->getQuery()->execute(), "Acreditaciones");

            }
            if ($form->get('btnEliminar')->isClicked()) {

            }
            if ($form->get('btnInformeApo')->isClicked()) {
                ob_clean();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuAcreditacion::class)->generarInfromeApo();
            }

        }
        $arAcreditaciones = $paginator->paginate($em->getRepository(RhuAcreditacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/recurso/acreditacion/lista.html.twig', [
            'arAcreditaciones' => $arAcreditaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/nuevo/{id}", name="recursohumano_movimiento_recurso_acreditacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAcreditacion = new RhuAcreditacion();
        if ($id != 0) {
            $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);
        }

        $form = $this->createForm(AcreditacionType::class, $arAcreditacion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arAcreditacion = $form->getData();
                $em->persist($arAcreditacion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_acreditacion_detalle', array('id' => $arAcreditacion->getCodigoAcreditacionPk())));
            }
        }
        return $this->render('recursohumano/movimiento/recurso/acreditacion/nuevo.html.twig', [
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/detalle/{id}", name="recursohumano_movimiento_recurso_acreditacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(RhuAcreditacion::class)->find($id);
        $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);

        return $this->render('recursohumano/movimiento/recurso/acreditacion/detalle.html.twig', [
            'arAcreditacion' => $arAcreditacion,
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/cargar/validacion", name="recursohumano_movimiento_recurso_acreditacion_cargar_validacion")
     */
    public function cargarValidacion(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('required' => true))
            ->add('fecha', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \DateTime('now')])
            ->add('attachment', FileType::class)
            ->add('btnCargar', SubmitType::class, array('label' => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnCargar')->isClicked()) {
                $rutaTemporal = $em->getRepository(GenConfiguracion::class)->find(1);
                $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "archivo.csv");
                $numero = $form->get('numero')->getData();
                $fecha = $form->get('fecha')->getData();
                $ruta = $rutaTemporal->getRutaTemporal() . "archivo.csv";
                if (($gestor = fopen($ruta, "r")) !== FALSE) {
                    $encabezado = fgets($gestor);
                    $separador = $this->identificarSeparador($encabezado);
                    rewind($gestor); # Movemos el puntero del archivo a la primera fila
                    $primeraFila = true;
                    $cont = 0;
                    while (($datos = fgetcsv($gestor, 1000, $separador)) !== FALSE) {
                        $cont++;
                        $registro = $datos;
                        if ($primeraFila || count($registro) <= 5) {
                            $primeraFila = false;
                            continue;
                        }
                        if (count($registro) >= 1) {
                            $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(array('numeroIdentificacion' => $registro[3]));
                            if ($arEmpleado) {
                                $arAcreditaciones = $em->getRepository(RhuAcreditacion::class)->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoValidado' => 0, 'estadoRechazado' => 0));
                                foreach ($arAcreditaciones as $arAcreditacion) {
                                    $cargo = $arAcreditacion->getAcreditacionTipoRel()->getCargoCodigo();
                                    $cargo2 = strip_tags($registro[10]);
                                    $detalle = urlencode($registro[26]);
                                    if ($cargo == $cargo2) {
                                        $arAcreditacionActualizar = $em->getRepository(RhuAcreditacion::class)->find($arAcreditacion->getCodigoAcreditacionPk());
                                        $arAcreditacionActualizar->setNumeroValidacion($numero);
                                        $arAcreditacionActualizar->setFechaValidacion($fecha);
                                        $arAcreditacionActualizar->setEstadoValidado(1);
                                        $arAcreditacionActualizar->setDetalleValidacion($detalle);
                                        $em->persist($arAcreditacionActualizar);
                                    }
                                }
                            }
                        }
                    }
                    fclose($gestor);
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('recursohumano/movimiento/recurso/acreditacion/cargarValiacion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/cargar/acreditacion", name="recursohumano_movimiento_recurso_acreditacion_cargar_acreditacion")
     */
    public function cargarAcreditacion(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class)
            ->add('btnCargar', SubmitType::class, array('label' => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnCargar')->isClicked()) {
                $rutaTemporal = $em->getRepository(GenConfiguracion::class)->find(1);
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "archivo.csv");
                $ruta = $rutaTemporal->getRutaTemporal() . "archivo.csv";
                if (($gestor = fopen($ruta, "r")) !== FALSE) {
                    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
//                        $registro = explode(",", $datos[0]);
                        if ($datos) {
                            $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(array('numeroIdentificacion' => str_replace("'","",$datos[4])));
                            if ($arEmpleado) {
                                $arAcreditaciones = $em->getRepository(RhuAcreditacion::class)->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoValidado' => 1, 'estadoAcreditado' => 0));
                                foreach ($arAcreditaciones as $arAcreditacion) {
                                    $cargo = $arAcreditacion->getAcreditacionTipoRel()->getCargo();
                                    $cargo2 = strip_tags(str_replace("'","",$datos[5]));
                                    $strFecha = strip_tags(str_replace("'","",$datos[6]));
                                    $arrFecha = explode('/', $strFecha);
                                    $strFechaFinal = $arrFecha[0] . '-' . $arrFecha[1] . '-' . $arrFecha[2];
                                    if ($cargo == $cargo2) {
                                        $arAcreditacionActualizar = $em->getRepository(RhuAcreditacion::class)->find($arAcreditacion->getCodigoAcreditacionPk());
                                        $arAcreditacionActualizar->setEstadoAcreditado(1);
                                        $arAcreditacionActualizar->setFechaAcreditacion(new \DateTime('now'));
                                        $arAcreditacionActualizar->setFechaVencimiento(date_create($strFechaFinal));
                                        $em->persist($arAcreditacionActualizar);
                                    }
                                }
                            }
                        }
                    }
                    fclose($gestor);
                    $em->flush();
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('recursohumano/movimiento/recurso/acreditacion/cargarAcreditacion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/detalle/{id}/validar", name="recursohumano_movimiento_recurso_acreditacion_detalle_validar")
     */
    public function validar(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $respuesta = "";
        $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('required' => true))
            ->add('fecha', DateType::class, array('data' => new \DateTime('now'), 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('detalle', TextareaType::class, array('required' => true))
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar validacion'))
            ->add('BtnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $numero = $form->get('numero')->getData();
                $fecha = $form->get('fecha')->getData();
                $detalle = $form->get('detalle')->getData();
                $arAcreditacion->setNumeroValidacion($numero);
                $arAcreditacion->setFechaValidacion($fecha);
                $arAcreditacion->setEstadoValidado(1);
                $arAcreditacion->setDetalleValidacion($detalle);
                $em->persist($arAcreditacion);
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                if ($arAcreditacion->getEstadoCerrado() == 1) {
                    $respuesta = 'No se puede eliminar, el curso se encuentra cerrado';
                } else {
                    if ($arAcreditacion->getEstadoAcreditado() == 1) {
                        $respuesta = 'No se puede eliminar, el curso ya se encuentra acreditado';
                    } else {
                        if ($arAcreditacion->getEstadoValidado() == 1) {
                            $arAcreditacion->setEstadoValidado(0);
                            $arAcreditacion->setFechaValidacion(null);
                            $em->persist($arAcreditacion);
                        }
                    }
                }
            }
            $em->flush();
            if ($respuesta == "") {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                Mensajes::error($respuesta);
            }
        }
        return $this->render('recursohumano/movimiento/recurso/acreditacion/validacion.html.twig', [
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/acreditacion/detalle/{id}/acreditar", name="recursohumano_movimiento_recurso_acreditacion_detalle_acreditar")
     */
    public function acreditar(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAcreditacion = $em->getRepository(RhuAcreditacion::class)->find($id);
        $form = $this->createForm(AcreditacionAcreditarType::class, $arAcreditacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arAcreditacion->setFechaAcreditacion(new \DateTime('now'));
                $arAcreditacion = $form->getData();
                $em->persist($arAcreditacion);
            }
            if ($form->get('BtnEliminar')->isClicked()) {
                if ($arAcreditacion->getEstadoAcreditado() == 1) {
                    $arAcreditacion->setEstadoAcreditado(0);
                    if ($arAcreditacion->getEstadoCerrado() == 1) {
                        $arAcreditacion->setEstadoCerrado(0);
                    }
                    $em->persist($arAcreditacion);
                }
            }
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('recursohumano/movimiento/recurso/acreditacion/acreditacion.html.twig', array(
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()
        ));
    }

    public function getFiltros($form)
    {
        return $filtro = [
            'codigoAcreditacionPk' => $form->get('codigoAcreditacionPk')->getData(),
            'fechaIngresoDesde' => $form->get('fechaIngresoDesde')->getData() ? $form->get('fechaIngresoDesde')->getData()->format('Y-m-d') : null,
            'fechaIngresoHasta' => $form->get('fechaIngresoHasta')->getData() ? $form->get('fechaIngresoHasta')->getData()->format('Y-m-d') : null,
            'fechaDesdeVenceCurso' => $form->get('fechaDesdeVenceCurso')->getData() ? $form->get('fechaDesdeVenceCurso')->getData()->format('Y-m-d') : null,
            'fechaHastaVenceCurso' => $form->get('fechaHastaVenceCurso')->getData() ? $form->get('fechaHastaVenceCurso')->getData()->format('Y-m-d') : null,
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoRechazado' => $form->get('estadoRechazado')->getData(),
            'estadoValidado' => $form->get('estadoValidado')->getData(),
            'estadoAcreditado' => $form->get('estadoAcreditado')->getData()
        ];
    }
}