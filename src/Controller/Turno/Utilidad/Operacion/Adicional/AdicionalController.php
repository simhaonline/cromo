<?php


namespace App\Controller\Turno\Utilidad\Operacion\Adicional;


use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\Turno\TurAdicional;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionInconsistencia;
use App\Entity\Turno\TurPuestoAdicional;
use App\Entity\Turno\TurTurno;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdicionalController extends AbstractController
{

    /**
     * @Route("turno/utilidad/operacion/adicional/adicional", name="turno_utilidad_operacion_adicional_adicional")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');
        $primerDiaDelMes = $fechaActual->format('Y/m/') . "01";
        $intUltimoDia = date("d", (mktime(0, 0, 0, $fechaActual->format('m') + 1, 1, $fechaActual->format('Y')) - 1));
        $ultimoDiaDelMes = $fechaActual->format('Y/m/') . $intUltimoDia;
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'data' => date_create($primerDiaDelMes), 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'data' => date_create($ultimoDiaDelMes), 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnGenerar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                $diaDesde = $fechaDesde->format('j');
                $diaHasta = $fechaHasta->format('j');
                $mes = $fechaDesde->format('m');
                $anio = $fechaDesde->format('Y');
                $arEmpleados = $em->getRepository(TurProgramacion::class)->listaPagoAdicionalPuesto($anio, $mes);
                foreach ($arEmpleados as $arEmpleadoPeriodo) {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arEmpleadoPeriodo['codigoEmpleadoFk']);
                    $arProgramacionesDetalles = $em->getRepository(TurProgramacion::class)->listaProgramacionAdicionalPagoPuesto($anio, $mes, $arEmpleadoPeriodo['codigoEmpleadoFk']);
                    foreach ($arProgramacionesDetalles as $arProgramacionDetalle) {
                        $arPuestoAdicionales = $em->getRepository(TurPuestoAdicional::class)->findBy(array('codigoPuestoFk' => $arProgramacionDetalle['codigoPuestoFk']));
                        if ($arPuestoAdicionales) {
                            foreach ($arPuestoAdicionales as $arPuestoAdicional) {
                                $numeroTurnos = 0;
                                for ($i = $diaDesde; $i <= $diaHasta; $i++) {
                                    $turno = $arProgramacionDetalle['dia' . $i];
                                    if ($turno) {
                                        $arTurno = $em->getRepository(TurTurno::class)->find($turno);
                                        if ($arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 0) {
                                            $numeroTurnos++;
                                        }
                                        if ($arPuestoAdicional->getIncluirDescanso() && $arTurno->getNovedad() == 0 && $arTurno->getDescanso() == 1) {
                                            $numeroTurnos++;
                                        }
                                    }

                                }
                                if ($numeroTurnos > 0) {
                                    $vrTurno = $arPuestoAdicional->getValor();
                                    $vrPago = $vrTurno * $numeroTurnos;
                                    $arAdicional = new TurAdicional();
                                    $arAdicional->setAnio($anio);
                                    $arAdicional->setMes($mes);
                                    $arAdicional->setDesde($diaDesde);
                                    $arAdicional->setHasta($diaHasta);
                                    $arAdicional->setEmpleadoRel($arEmpleado);
                                    $arAdicional->setPuestoRel($arPuestoAdicional->getPuestoRel());
                                    $arAdicional->setConceptoRel($arPuestoAdicional->getConceptoRel());
                                    $arAdicional->setNumeroTurnos($numeroTurnos);
                                    $arAdicional->setVrTurno($vrTurno);
                                    $arAdicional->setVrPago($vrPago);
                                    $em->persist($arAdicional);
                                }
                            }
                        }
                    }
                }
                $em->flush();
            }


        }
        $arAdicionales = $paginator->paginate($em->getRepository(TurAdicional::class)->findAll(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/utilidad/operacion/puesto/adicional.html.twig', [
            'arAdicionales' => $arAdicionales,
            'form' => $form->createView()]);

    }
}