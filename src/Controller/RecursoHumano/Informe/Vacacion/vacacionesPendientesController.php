<?php


namespace App\Controller\RecursoHumano\Informe\Vacacion;


use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuInformeVacacionPendiente;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class vacacionesPendientesController extends AbstractController
{
    /**
     * @Route("/recursohumano/informe/vacacion/pendiente/lista", name="recursohumano_informe_vacacion_pendiente_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroInformeRhuContrato')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=> new \DateTime('now') ])
            ->add('estadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoTerminado'] ])
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $fecha = $form->get('fechaHasta')->getData();
                $em->getRepository(RhuVacacion::class)->pendientePagarInforme($fecha);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuContrato::class)->informeVacacionesPendiente($raw), "Vacaciones pendientes");
            }
        }

        $arVacacionesPendientes = $paginator->paginate($em->getRepository(RhuInformeVacacionPendiente::class)->informe(), $request->query->getInt('page', 1), 500);
        return $this->render('recursohumano/informe/vacacion/pendiente.html.twig', [
            'arVacacionesPendientes' => $arVacacionesPendientes,
            'form' => $form->createView(),
        ]);
    }



    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'estadoTerminado' => $form->get('estadoTerminado')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
        ];
        $session->set('filtroInformeRhuContrato', $filtro);

        return $filtro;

    }
}