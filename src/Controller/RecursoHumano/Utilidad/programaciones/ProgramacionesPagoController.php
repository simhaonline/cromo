<?php


namespace App\Controller\RecursoHumano\Utilidad\programaciones;

use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

    class ProgramacionesPagoController extends Controller
{
    /**
     * @Route("/recursohumano/utilidad/intercambio/programaciones", name="recursohumano_utilidad_pago_programaciones_lista")
     */
    public function programaciones(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($request->request->get('btnTransferir')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $codigoProgramacion = $request->request->get('btnTransferir');
                $arProgramacion = $em->getRepository(RhuProgramacion::class)->find($codigoProgramacion);
                $arConfiguracion =  $em->getRepository(GenConfiguracion::class)->find(1);
                $codigoEmpresa = $arConfiguracion->getCodigoEmpresa();
                $arPagos = $em->getRepository(RhuPago::class)->findBy(["codigoProgramacionFk"=>$codigoProgramacion]);
                foreach ($arPagos as $arPago) {
                    $arrDatos['pagos'] = array(
                        "codigoPagoPk"=>$arPago->getcodigoPagoPk(),
                        'codigoPagotipo'=>$arPago->getcodigoPagoTipoFk(),
                        'codigoEntidadSaludFk'=>$arPago->getcodigoEntidadSaludFk(),
                        'codigoEntidadPensionFk'=>$arPago->getcodigoEntidadPensionFk(),
                        'codigoProgramacionFk'=>$arPago->getcodigoProgramacionFk(),
                        'codigoPeriodoFk'=>$arPago->getcodigoPeriodoFk(),
                        'numero'=>$arPago->getnumero(),
                        'codigoEmpleadoFk'=>$arPago->getcodigoEmpleadoFk(),
                        'codigoContratoFk'=>$arPago->getcodigoContratoFk(),
                        'codigoGrupoFk'=>$arPago->getcodigoGrupoFk(),
                        'codigoProgramacionDetalleFk'=>$arPago->getcodigoProgramacionFk(),
                        'fechaDesde'=>$arPago->getfechaDesde()->format('Y-m-d'),
                        'fechaHasta'=>$arPago->getfechahasta()->format('Y-m-d'),
                        'fechaDesdeContrato'=>$arPago->getfechaDesdeContrato()->format('Y-m-d'),
                        'fechaHastaContrato'=>$arPago->getfechaHastaContrato()?$arPago->getfechaHastaContrato()->format('Y-m-d'):null,
                        'vrSalarioContrato'=>(float)$arPago->getvrSalarioContrato(),
                        'vrDevengado'=>(float)$arPago->getvrDevengado(),
                        'vrDeduccion'=>(float)$arPago->getvrDeduccion(),
                        'vrNeto'=>(float)$arPago->getvrNeto(),
                        'diasAusentismo'=>$arPago->getdiasAusentismo(),
                        'estadoAutorizado'=>$arPago->getestadoAutorizado()??false,
                        'estadoAprobado'=>$arPago->getestadoAprobado()??false,
                        'estadoAnulado'=>$arPago->getestadoAnulado()??false,
                        'estadoEgreso'=>$arPago->getestadoEgreso()??false,
                        'comentario'=>$arPago->getcomentario(),
                        'codigoVacacionFk'=>$arPago->getcodigoVacacionFk(),
                        'salud'=>$arPago->getentidadSaludRel()->getnombre(),
                        'pension'=>$arPago->getentidadPensionRel()->getnombre(),
                        'banco'=>$arPago->getempleadoRel()->getbancoRel()->getnombre()??"no",
                        'cuenta'=>$arPago->getempleadoRel()->getcuenta(),
                        'grupo'=>$arPago->getgrupoRel()->getNombre(),
                        'salario'=>$arPago->getContratoRel()->getVrSalario(),
                        'usuario'=>$arPago->getusuario()
                    );
                    $arrDatos['programacionDetalle'] = $em->getRepository(RhuPagoDetalle::class)->PagoDetalleIntercambio($arPago->getCodigoPagoPK());
                    $arrDatos['empleados'] = $em->getRepository(RhuEmpleado::class)->empleadoIntercambio($arPago->getCodigoEmpleadoFK());
                    $arrDatos['contrato'] = $em->getRepository(RhuContrato::class)->contratoIntercambio($arPago->getCodigoEmpleadoFK());
                    $arrDatos['empresa']= $codigoEmpresa;
                    $arrDatos = json_encode($arrDatos);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $arConfiguracion->getWebServiceOxigenoUrl().'/api/'.'pagos/lista');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($arrDatos))
                    );
                    $booRespuesta = json_decode(curl_exec($ch));
                    if ($booRespuesta){
                        $arProgramacion->setEstadoTransferido(true);
                        $em->persist($arProgramacion);
                        $em->flush();
                    }
                    curl_close($ch);
                    unset($arrDatos);

                }
            }
        }
        $arProgrmaciones = $paginator->paginate($em->getRepository(RhuProgramacion::class)->Trasferencia(), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/utilidad/programacion/programacion.html.twig', [
            'arProgrmaciones' => $arProgrmaciones,
            'form' => $form->createView()
        ]);
    }
}