<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Adicional;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\AdicionalType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdicionalController extends AbstractController
{
    protected $clase = RhuAdicional::class;
    protected $claseFormulario = AdicionalType::class;
    protected $claseNombre = "RhuAdicional";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Adicional";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/adicional/lista", name="recursohumano_movimiento_nomina_adicional_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('estadoInactivo', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroRhuAdicionalEstadoInactivo'), 'required' => false])
            ->add('estadoInactivoPeriodo', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroRhuAdicionalEstadoInactivo'), 'required' => false])
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
                General::get()->setExportar($em->getRepository(RhuAdicional::class)->lista($raw), "Adicionales al pago permanentes");
            }
        }
        $arAdicionales = $paginator->paginate($em->getRepository(RhuAdicional::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/adicional/lista.html.twig', [
            'arAdicionales' => $arAdicionales,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/adicional/nuevo/{id}", name="recursohumano_movimiento_nomina_adicional_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAdicional = new RhuAdicional();
        if ($id != 0) {
            $arAdicional = $em->getRepository(RhuAdicional::class)->find($id);
        } else {
            $arAdicional->setFecha(new \DateTime('now'));
            $arAdicional->setAplicaNomina(true);
        }
        $form = $this->createForm(AdicionalType::class, $arAdicional);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arAdicional->getCodigoEmpleadoFk());
                if ($arEmpleado->getCodigoContratoFk()) {
                    $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                    $arAdicional->setEmpleadoRel($arEmpleado);
                    $arAdicional->setContratoRel($arContrato);
                    $arAdicional->setPermanente(1);
                    $em->persist($arAdicional);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicional_lista'));
                } else {
                    Mensajes::error('El empleado no tiene un contrato activo en el sistema');
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicional/nuevo.html.twig', [
            'form' => $form->createView(),
            'arAdicional' => $arAdicional
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/adicional/detalle/{id}", name="recursohumano_movimiento_nomina_adicional_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        return $this->render('recursohumano/movimiento/nomina/adicional/detalle.html.twig', [
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     * @param $form
     * @return array
     */
    public function getFiltros($form)
    {
        $filtro = [
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'estadoInactivo' => $form->get('estadoInactivo')->getData(),
            'estadoInactivoPeriodo' => $form->get('estadoInactivoPeriodo')->getData(),
        ];

        return $filtro;
    }
}

