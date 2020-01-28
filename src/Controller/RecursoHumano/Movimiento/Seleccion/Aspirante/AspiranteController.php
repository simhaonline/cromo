<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Aspirante;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuAspirante;
use App\Entity\RecursoHumano\RhuSolicitud;
use App\Entity\RecursoHumano\RhuSolicitudAspirante;
use App\Form\Type\RecursoHumano\AspiranteType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AspiranteController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "RhuAspirante";


    protected $clase = RhuAspirante::class;
    protected $claseFormulario = AspiranteType::class;
    protected $claseNombre = "RhuAspirante";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Aspirante";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/aspirante/lista", name="recursohumano_movimiento_seleccion_aspirante_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoAspirantePk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
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
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuAspirante::class)->lista($raw), "Aspirantes");
            }
        }
        $arAspirantes = $paginator->paginate($em->getRepository(RhuAspirante::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seleccion/aspirante/lista.html.twig', [
            'arAspirantes' => $arAspirantes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/seleccion/aspirante/nuevo/{id}", name="recursohumano_movimiento_seleccion_aspirante_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAspirante = new RhuAspirante();
        if ($id != 0) {
            $arAspirante = $em->getRepository('App:RecursoHumano\RhuAspirante')->find($id);
            if (!$arAspirante) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        } else {
            $arAspirante->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AspiranteType::class, $arAspirante);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAspirante->setNombreCorto($arAspirante->getNombre1() . ' ' . $arAspirante->getNombre2() . ' ' . $arAspirante->getApellido1() . ' ' . $arAspirante->getApellido2());
                $em->persist($arAspirante);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/aspirante/nuevo.html.twig', [
            'form' => $form->createView(), 'arAspirante' => $arAspirante
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/seleccion/aspirante/detalle/{id}", name="recursohumano_movimiento_seleccion_aspirante_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAspirante = $em->getRepository(RhuAspirante::class)->find($id);
        if ($id != 0) {
            $form = Estandares::botonera($arAspirante->getEstadoAutorizado(), $arAspirante->getEstadoAprobado(), $arAspirante->getEstadoAnulado());
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('btnAutorizar')->isClicked()) {
                    $em->getRepository(RhuAspirante::class)->autorizar($arAspirante);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_detalle', ['id' => $id]));
                }
                if ($form->get('btnAnular')->isClicked()) {
                    $em->getRepository(RhuAspirante::class)->anular($arAspirante);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_detalle', ['id' => $id]));
                }
                if ($form->get('btnDesautorizar')->isClicked()) {
                    $em->getRepository(RhuAspirante::class)->desautorizar($arAspirante);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_detalle', ['id' => $id]));
                }
            }
            if (!$arAspirante) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/aspirante/detalle.html.twig', [
            'arAspirante' => $arAspirante,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/seleccion/aspirante/aplicar/{id}", name="recursohumano_movimiento_seleccion_aspirante_aplicar")
     */
    public function aplicar(Request $request, $id)
    {
        /**
         * @var $arAspirante RhuAspirante
         */
        $em = $this->getDoctrine()->getManager();
        $arAspirante = $em->getRepository(RhuAspirante::class)->find($id);
        $arrSolicitudesRel = array('class' => RhuSolicitud::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('sr')
                    ->where('sr.estadoAnulado = 0')
                    ->andWhere('sr.estadoAutorizado = 1')
                    ->orderBy('sr.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => true,
            'disabled' => false);
        $ctrlBoton = false;
        $arSolicitudes = $em->getRepository(RhuSolicitud::class)->findBy(['estadoAutorizado' => 1, 'estadoAprobado' => 0]);

        if (count($arSolicitudes) == 0) {
            $arrSolicitudesRel['disabled'] = true;
            $ctrlBoton = true;
            Mensajes::error('No se encontraron solicitudes autorizadas');

        }
        $form = $this->createFormBuilder()
            ->add('solicitudRel', EntityType::class, $arrSolicitudesRel)
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar', 'disabled' => $ctrlBoton))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arSolicitudAspirante = new RhuSolicitudAspirante();
            $arSolicitudAspirante->setAspiranteRel($arAspirante);
            $arSolicitudAspirante->setSolicitudRel(($form->get('solicitudRel')->getData()));
            $em->persist($arSolicitudAspirante);
            $arSolicitud = $form->get('solicitudRel')->getData();
            if ($arAspirante->getEstadoBloqueado() == 0) {
                if ($arSolicitud->getEstadoAnulado() == 0) {
                    //Calculo edad
                    $varFechaNacimientoAnio = $arAspirante->getFechaNacimiento()->format('Y');
                    $varFechaNacimientoMes = $arAspirante->getFechaNacimiento()->format('m');
                    $varMesActual = date('m');
                    if ($varMesActual >= $varFechaNacimientoMes) {
                        $varEdad = date('Y') - $varFechaNacimientoAnio;
                    } else {
                        $varEdad = date('Y') - $varFechaNacimientoAnio - 1;
                    }
                    //Fin calculo edad
                    $edadMinima = $arSolicitud->getEdadMinima();
                    $edadMaxima = $arSolicitud->getEdadMaxima();
                    if ($edadMinima != "" && $edadMaxima != "") {
                        if ($varEdad <= $edadMaxima && $varEdad >= $edadMinima) {
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        } else {
                            Mensajes::error("El aspirante debe tener una edad minima entre . {$edadMinima} . y . {$edadMaxima} .  para aplicar a esta solicitud");
                        }
                    } else {
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                } else {
                    Mensajes::error("El aspirante ya se encuenta en la solicitud");
                }
            } else {
                Mensajes::error('El aspirante no puede aplicar a la solicitud, tiene inconsistencias o se encuentra bloqueado');
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/aspirante/aplicar.html.twig', array(
            'arAspirante' => $arAspirante,
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoAspirante' => $form->get('codigoAspirantePk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}

