<?php

namespace App\Controller\General\Prueba;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenBanco;
use App\Entity\General\GenCiudad;
use App\Entity\General\GenCobertura;
use App\Entity\General\GenDepartamento;
use App\Entity\General\GenDimension;
use App\Entity\General\GenEstadoCivil;
use App\Entity\General\GenFormaPago;
use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenOrigenCapital;
use App\Entity\General\GenPais;
use App\Entity\General\GenRegimen;
use App\Entity\General\GenSectorComercial;
use App\Entity\General\GenSectorEconomico;
use App\Entity\General\GenSegmento;
use App\Entity\General\GenSexo;
use App\Entity\General\GenTipoPersona;
use App\Entity\RecursoHumano\RhuAcademia;
use App\Entity\RecursoHumano\RhuAcreditacion;
use App\Entity\RecursoHumano\RhuAcreditacionTipo;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAspirante;
use App\Entity\RecursoHumano\RhuCambioSalario;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuCargoSupervigilancia;
use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoClase;
use App\Entity\RecursoHumano\RhuContratoMotivo;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCostoClase;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuDepartamento;
use App\Entity\RecursoHumano\RhuDisciplinario;
use App\Entity\RecursoHumano\RhuDisciplinarioFalta;
use App\Entity\RecursoHumano\RhuDisciplinarioMotivo;
use App\Entity\RecursoHumano\RhuDisciplinarioTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use App\Entity\RecursoHumano\RhuEmbargoTipo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuEstudio;
use App\Entity\RecursoHumano\RhuEstudioTipo;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuIncapacidadDiagnostico;
use App\Entity\RecursoHumano\RhuIncapacidadTipo;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuLicenciaTipo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuPension;
use App\Entity\RecursoHumano\RhuRh;
use App\Entity\RecursoHumano\RhuSalarioTipo;
use App\Entity\RecursoHumano\RhuSalud;
use App\Entity\RecursoHumano\RhuSector;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionAntecedente;
use App\Entity\RecursoHumano\RhuSeleccionEntrevista;
use App\Entity\RecursoHumano\RhuSeleccionEntrevistaTipo;
use App\Entity\RecursoHumano\RhuSeleccionPrueba;
use App\Entity\RecursoHumano\RhuSeleccionPruebaTipo;
use App\Entity\RecursoHumano\RhuSeleccionReferencia;
use App\Entity\RecursoHumano\RhuSeleccionReferenciaTipo;
use App\Entity\RecursoHumano\RhuSeleccionTipo;
use App\Entity\RecursoHumano\RhuSeleccionVisita;
use App\Entity\RecursoHumano\RhuSolicitud;
use App\Entity\RecursoHumano\RhuSolicitudAspirante;
use App\Entity\RecursoHumano\RhuSolicitudExperiencia;
use App\Entity\RecursoHumano\RhuSolicitudMotivo;
use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use App\Entity\RecursoHumano\RhuSucursal;
use App\Entity\RecursoHumano\RhuTiempo;
use App\Entity\RecursoHumano\RhuTipoCotizante;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\RecursoHumano\RhuVisitaTipo;
use App\Entity\RecursoHumano\RhuZona;
use App\Entity\Seguridad\Usuario;
use App\Entity\Turno\TurArea;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurContratoDetalleCompuesto;
use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurFacturaTipo;
use App\Entity\Turno\TurGrupo;
use App\Entity\Turno\TurItem;
use App\Entity\Turno\TurModalidad;
use App\Entity\Turno\TurOperacion;
use App\Entity\Turno\TurOperacionTipo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoDetalleCompuesto;
use App\Entity\Turno\TurPedidoTipo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurProyecto;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSecuencia;
use App\Entity\Turno\TurTurno;
use App\Entity\Turno\TurZona;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MigracionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general/migracion", name="general_migracion")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('servidor', TextType::class, ['required' => false, 'data' => '192.168.2.199', 'attr' => ['class' => 'form-control']])
            ->add('basedatos', TextType::class, ['required' => false, 'data' => 'bdseracis', 'attr' => ['class' => 'form-control']])
            ->add('usuario', TextType::class, ['required' => false, 'data' => 'consulta', 'attr' => ['class' => 'form-control']])
            ->add('clave', TextType::class, ['required' => false, 'data' => 'SoporteErp2018@', 'attr' => ['class' => 'form-control']])
            ->add('btnIniciar', SubmitType::class, ['label' => 'Migrar datos basicos', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnValidar', SubmitType::class, ['label' => 'Validar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $servername = $form->get('servidor')->getData();
            $database = $form->get('basedatos')->getData();
            $username = $form->get('usuario')->getData();
            $password = $form->get('clave')->getData();

            if ($servername && $database && $username && $password) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $conn = mysqli_connect($servername, $username, $password, $database);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                if ($form->get('btnIniciar')->isClicked()) {
                    //$this->generalPais($conn);
                    //$this->generalDepartamento($conn);
                    //$this->generalCiudad($conn);
                    //$this->rhuGrupo($conn);
                    //$this->rhuCargo($conn);
                    //$this->rhuConcepto($conn);
                    //$this->rhuEmbargoJuzgado($conn);
                    //$this->rhuCreditoTipo($conn);
                    //$this->rhuIncapacidadDiagnostico($conn);
                    //$this->rhuLicenciaTipo($conn);
//                    $this->rhuDisciplinario($conn);
//                    $this->rhuAcreditacion($conn);
//                    $this->rhuEstudio($conn);
                    $this->rhuVisita($conn);
                    //$this->turTurno($conn);
                    //$this->turSecuencia($conn);
                    //$this->turConcepto($conn);
                    //$this->finCuenta($conn);
                    Mensajes::success("Se migro la informacion con exito");
                }
                if ($form->get('btnValidar')->isClicked()) {
                    $this->validarRhu($conn);
                }

                if ($request->request->get('OpGenerar')) {
                    $codigo = $request->request->get('OpGenerar');
                    switch ($codigo) {
                        case 'fin_centro_costo':
                            $this->finCentroCosto($conn);
                            break;
                        case 'rhu_empleado':
                            $this->rhuEmpleado($conn);
                            break;
                        case 'rhu_contrato':
                            $this->rhuContrato($conn);
                            break;
                        case 'rhu_aspirante':
                            $this->rhuAspirante($conn);
                            break;
                        case 'rhu_solicitud':
                            $this->rhuSolicitud($conn);
                            break;
                        case 'rhu_solicitud_aspirante':
                            $this->rhuSolicitudAspirante($conn);
                            break;
                        case 'rhu_seleccion':
                            $this->rhuSeleccion($conn);
                            break;
                        case 'rhu_seleccion_entrevista':
                            $this->rhuSeleccionEntrevista($conn);
                            break;
                        case 'rhu_seleccion_prueba':
                            $this->rhuSeleccionPrueba($conn);
                            break;
                        case 'rhu_seleccion_referencia':
                            $this->rhuSeleccionReferencia($conn);
                            break;
                        case 'rhu_seleccion_visita':
                            $this->rhuSeleccionVisita($conn);
                            break;
                        case 'rhu_seleccion_antecedente':
                            $this->rhuSeleccionAntecedente($conn);
                            break;
                        case 'rhu_cambio_salario':
                            $this->rhuCambioSalario($conn);
                            break;
                        case 'rhu_adicional':
                            $this->rhuAdicional($conn);
                            break;
                        case 'rhu_embargo':
                            $this->rhuEmbargo($conn);
                            break;
                        case 'rhu_credito':
                            $this->rhuCredito($conn);
                            break;
                        case 'rhu_credito_pago':
                            $this->rhuCreditoPago($conn);
                            break;
                        case 'rhu_vacacion':
                            $this->rhuVacacion($conn);
                            break;
                        case 'rhu_liquidacion':
                            $this->rhuLiquidacion($conn);
                            break;
                        case 'rhu_pago':
                            $this->rhuPago($conn);
                            break;
                        case 'rhu_pago_detalle':
                            $this->rhuPagoDetalle($conn);
                            break;
                        case 'rhu_aporte':
                            $this->rhuAporte($conn);
                            break;
                        case 'rhu_aporte_detalle':
                            $this->rhuAporteDetalle($conn);
                            break;
                        case 'rhu_incapacidad':
                            $this->rhuIncapacidad($conn);
                            break;
                        case 'rhu_licencia':
                            $this->rhuLicencia($conn);
                            break;
                        case 'rhu_disciplinario':
                            $this->rhuDisciplinario($conn);
                            break;
                        case 'rhuAcreditacion':
                            $this->rhuAcreditacion($conn);
                            break;
                        case 'rhuEstudio':
                            $this->rhuEstudio($conn);
                            break;
                        case 'rhuVisita':
                            $this->rhuVisita($conn);
                            break;
                        case 'tur_cliente':
                            $this->turCliente($conn);
                            break;
                        case 'tur_grupo':
                            $this->turGrupo($conn);
                            break;
                        case 'tur_operacion':
                            $this->turOperacion($conn);
                            break;
                        case 'tur_puesto':
                            $this->turPuesto($conn);
                            break;
                        case 'tur_contrato':
                            $this->turContrato($conn);
                            break;
                        case 'tur_contrato_detalle':
                            $this->turContratoDetalle($conn);
                            break;
                        case 'tur_contrato_detalle_compuesto':
                            $this->turContratoDetalleCompuesto($conn);
                            break;
                        case 'tur_pedido':
                            $this->turPedido($conn);
                            break;
                        case 'tur_pedido_detalle':
                            $this->turPedidoDetalle($conn);
                            break;
                        case 'tur_pedido_detalle_compuesto':
                            $this->turPedidoDetalleCompuesto($conn);
                            break;
                        case 'tur_factura':
                            $this->turFactura($conn);
                            break;
                        case 'tur_factura_detalle':
                            $this->turFacturaDetalle($conn);
                            break;
                        case 'tur_programacion':
                            $this->turProgramacion($conn);
                            break;
                        case 'tur_prototipo':
                            $this->turPrototipo($conn);
                            break;
                        case 'usuarios':
                            $this->usuarios($conn);
                            break;
                    }
                }

                mysqli_close($conn);
            } else {
                Mensajes::error("Debe seleccionar los parametros de conexion");
            }
        }
        $arrProcesos = [
            ['clase' => 'fin_centro_costo', 'registros' => $this->contarRegistros('FinCentroCosto', 'Financiero', 'codigoCentroCostoPk')],
            ['clase' => 'rhu_empleado', 'registros' => $this->contarRegistros('RhuEmpleado', 'RecursoHumano', 'codigoEmpleadoPk')],
            ['clase' => 'rhu_contrato', 'registros' => $this->contarRegistros('RhuContrato', 'RecursoHumano', 'codigoContratoPk')],
            ['clase' => 'rhu_aspirante', 'registros' => $this->contarRegistros('RhuAspirante', 'RecursoHumano', 'codigoAspirantePk')],
            ['clase' => 'rhu_solicitud', 'registros' => $this->contarRegistros('RhuSolicitud', 'RecursoHumano', 'codigoSolicitudPk')],
            ['clase' => 'rhu_solicitud_aspirante', 'registros' => $this->contarRegistros('RhuSolicitudAspirante', 'RecursoHumano', 'codigoSolicitudAspirantePk')],
            ['clase' => 'rhu_seleccion', 'registros' => $this->contarRegistros('RhuSeleccion', 'RecursoHumano', 'codigoSeleccionPk')],
            ['clase' => 'rhu_seleccion_entrevista', 'registros' => $this->contarRegistros('RhuSeleccionEntrevista', 'RecursoHumano', 'codigoSeleccionEntrevistaPk')],
            ['clase' => 'rhu_seleccion_prueba', 'registros' => $this->contarRegistros('RhuSeleccionPrueba', 'RecursoHumano', 'codigoSeleccionPruebaPk')],
            ['clase' => 'rhu_seleccion_referencia', 'registros' => $this->contarRegistros('RhuSeleccionReferencia', 'RecursoHumano', 'codigoSeleccionReferenciaPk')],
            ['clase' => 'rhu_seleccion_visita', 'registros' => $this->contarRegistros('RhuSeleccionVisita', 'RecursoHumano', 'codigoSeleccionVisitaPk')],
            ['clase' => 'rhu_seleccion_antecedente', 'registros' => $this->contarRegistros('RhuSeleccionAntecedente', 'RecursoHumano', 'codigoSeleccionAntecedentePk')],
            ['clase' => 'rhu_cambio_salario', 'registros' => $this->contarRegistros('RhuCambioSalario', 'RecursoHumano', 'codigoCambioSalarioPk')],
            ['clase' => 'rhu_adicional', 'registros' => $this->contarRegistros('RhuAdicional', 'RecursoHumano', 'codigoAdicionalPk')],
            ['clase' => 'rhu_embargo', 'registros' => $this->contarRegistros('RhuEmbargo', 'RecursoHumano', 'codigoEmbargoPk')],
            ['clase' => 'rhu_credito', 'registros' => $this->contarRegistros('RhuCredito', 'RecursoHumano', 'codigoCreditoPk')],
            ['clase' => 'rhu_credito_pago', 'registros' => $this->contarRegistros('RhuCreditoPago', 'RecursoHumano', 'codigoCreditoPagoPk')],
            ['clase' => 'rhu_vacacion', 'registros' => $this->contarRegistros('RhuVacacion', 'RecursoHumano', 'codigoVacacionPk')],
            ['clase' => 'rhu_liquidacion', 'registros' => $this->contarRegistros('RhuLiquidacion', 'RecursoHumano', 'codigoLiquidacionPk')],
            ['clase' => 'rhu_aporte', 'registros' => $this->contarRegistros('RhuAporte', 'RecursoHumano', 'codigoAportePk')],
            ['clase' => 'rhu_aporte_detalle', 'registros' => $this->contarRegistros('RhuAporteDetalle', 'RecursoHumano', 'codigoAporteDetallePk')],
            ['clase' => 'rhu_pago', 'registros' => $this->contarRegistros('RhuPago', 'RecursoHumano', 'codigoPagoPk')],
            ['clase' => 'rhu_pago_detalle', 'registros' => $this->contarRegistros('RhuPagoDetalle', 'RecursoHumano', 'codigoPagoDetallePk')],
            ['clase' => 'rhu_incapacidad', 'registros' => $this->contarRegistros('RhuIncapacidad', 'RecursoHumano', 'codigoIncapacidadPk')],
            ['clase' => 'rhu_licencia', 'registros' => $this->contarRegistros('RhuLicencia', 'RecursoHumano', 'codigoLicenciaPk')],
            ['clase' => 'rhu_disciplinario', 'registros' => $this->contarRegistros('RhuDisciplinario', 'RecursoHumano', 'codigoDisciplinarioPk')],
            ['clase' => 'rhu_acreditacion', 'registros' => $this->contarRegistros('RhuAcreditacion', 'RecursoHumano', 'codigoAcreditacionPk')],
            ['clase' => 'rhu_estudio', 'registros' => $this->contarRegistros('RhuEstudio', 'RecursoHumano', 'codigoEstudioPk')],
            ['clase' => 'rhu_visita', 'registros' => $this->contarRegistros('RhuVisita', 'RecursoHumano', 'codigoVisitaPk')],
            ['clase' => 'tur_cliente', 'registros' => $this->contarRegistros('TurCliente', 'Turno', 'codigoClientePk')],
            ['clase' => 'tur_grupo', 'registros' => $this->contarRegistros('TurGrupo', 'Turno', 'codigoGrupoPk')],
            ['clase' => 'tur_operacion', 'registros' => $this->contarRegistros('TurOperacion', 'Turno', 'codigoOperacionPk')],
            ['clase' => 'tur_puesto', 'registros' => $this->contarRegistros('TurPuesto', 'Turno', 'codigoPuestoPk')],
            ['clase' => 'tur_contrato', 'registros' => $this->contarRegistros('TurContrato', 'Turno', 'codigoContratoPk')],
            ['clase' => 'tur_contrato_detalle', 'registros' => $this->contarRegistros('TurContratoDetalle', 'Turno', 'codigoContratoDetallePk')],
            ['clase' => 'tur_contrato_detalle_compuesto', 'registros' => $this->contarRegistros('TurContratoDetalleCompuesto', 'Turno', 'codigoContratoDetalleCompuestoPk')],
            ['clase' => 'tur_pedido', 'registros' => $this->contarRegistros('TurPedido', 'Turno', 'codigoPedidoPk')],
            ['clase' => 'tur_pedido_detalle', 'registros' => $this->contarRegistros('TurPedidoDetalle', 'Turno', 'codigoPedidoDetallePk')],
            ['clase' => 'tur_pedido_detalle_compuesto', 'registros' => $this->contarRegistros('TurPedidoDetalleCompuesto', 'Turno', 'codigoPedidoDetalleCompuestoPk')],
            ['clase' => 'tur_factura', 'registros' => $this->contarRegistros('TurFactura', 'Turno', 'codigoFacturaPk')],
            ['clase' => 'tur_factura_detalle', 'registros' => $this->contarRegistros('TurFacturaDetalle', 'Turno', 'codigoFacturaDetallePk')],
            ['clase' => 'tur_programacion', 'registros' => $this->contarRegistros('TurProgramacion', 'Turno', 'codigoProgramacionPk')],
            ['clase' => 'tur_prototipo', 'registros' => $this->contarRegistros('TurPrototipo', 'Turno', 'codigoPrototipoPk')],
            ['clase' => 'usuarios', 'registros' => $this->contarRegistros('Usuario', 'Seguridad', 'username')]
        ];
        return $this->render('general/migracion/migracion.html.twig', [
            'arrProcesos' => $arrProcesos,
            'form' => $form->createView()
        ]);
    }

    private function validarRhu($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $arrEntidadSalud = $conn->query("SELECT
                    codigo_entidad_salud_fk, 
                    rhu_entidad_salud.codigo_interface as codigo_entidad_salud_externo
                  FROM rhu_contrato 
                  left join rhu_entidad_salud on rhu_contrato.codigo_entidad_salud_fk=rhu_entidad_salud.codigo_entidad_salud_pk 
                  group by codigo_entidad_salud_fk, codigo_interface");
        while ($row = mysqli_fetch_assoc($arrEntidadSalud)) {
            if ($row['codigo_entidad_salud_externo']) {
                $arEntidad = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_salud_externo']]);
                if (!$arEntidad) {
                    Mensajes::error("La entidad de salud con el codigo {$row['codigo_entidad_salud_fk']} externo {$row['codigo_entidad_salud_externo']} no existe");
                }
            } else {
                Mensajes::error("Existen contratos sin entidad de salud o la entidad de salud no tiene codigo externo");
            }
        }

        $arrEntidadPension = $conn->query("SELECT
                    codigo_entidad_pension_fk, 
                    rhu_entidad_pension.codigo_interface as codigo_entidad_pension_externo
                  FROM rhu_contrato 
                  left join rhu_entidad_pension on rhu_contrato.codigo_entidad_pension_fk=rhu_entidad_pension.codigo_entidad_pension_pk 
                  group by codigo_entidad_pension_fk, codigo_interface");
        while ($row = mysqli_fetch_assoc($arrEntidadPension)) {
            if ($row['codigo_entidad_pension_externo']) {
                $arEntidad = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_pension_externo']]);
                if (!$arEntidad) {
                    Mensajes::error("La entidad de pension con el codigo {$row['codigo_entidad_pension_fk']} externo {$row['codigo_entidad_pension_externo']} no existe");
                }
            } else {
                Mensajes::error("Existen contratos sin entidad de pension o la entidad de pension no tiene codigo externo");
            }
        }

        $arrEntidadCesantia = $conn->query("SELECT
                    codigo_entidad_cesantia_fk, 
                    rhu_entidad_cesantia.codigo_interface as codigo_entidad_cesantia_externo
                  FROM rhu_contrato 
                  left join rhu_entidad_cesantia on rhu_contrato.codigo_entidad_cesantia_fk=rhu_entidad_cesantia.codigo_entidad_cesantia_pk 
                  group by codigo_entidad_cesantia_fk, codigo_interface");
        while ($row = mysqli_fetch_assoc($arrEntidadCesantia)) {
            if ($row['codigo_entidad_cesantia_externo']) {
                $arEntidad = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_cesantia_externo']]);
                if (!$arEntidad) {
                    Mensajes::error("La entidad de cesantia con el codigo {$row['codigo_entidad_cesantia_fk']} externo {$row['codigo_entidad_cesantia_externo']} no existe");
                }
            } else {
                Mensajes::error("Existen contratos sin entidad de cesantia o la entidad de cesantia no tiene codigo externo");
            }
        }

        $arrEntidadCaja = $conn->query("SELECT                                        
                    codigo_entidad_caja_fk, 
                    rhu_entidad_caja.codigo_interface as codigo_entidad_caja_externo
                  FROM rhu_contrato 
                  left join rhu_entidad_caja on rhu_contrato.codigo_entidad_caja_fk=rhu_entidad_caja.codigo_entidad_caja_pk 
                  group by codigo_entidad_caja_fk, codigo_interface");
        while ($row = mysqli_fetch_assoc($arrEntidadCaja)) {
            if ($row['codigo_entidad_caja_externo']) {
                $arEntidad = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_caja_externo']]);
                if (!$arEntidad) {
                    Mensajes::error("La entidad de caja con el codigo {$row['codigo_entidad_caja_fk']} externo {$row['codigo_entidad_caja_externo']} no existe");
                }
            } else {
                Mensajes::error("Existen contratos sin entidad de caja o la entidad de caja no tiene codigo externo");
            }
        }
    }

    private function generalPais($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query('SELECT codigo_pais_pk, pais FROM gen_pais');
        foreach ($datos as $row) {
            $arPais = new GenPais();
            $arPais->setCodigoPaisPk($row['codigo_pais_pk']);
            $arPais->setNombre(utf8_decode($row['pais']));
            $em->persist($arPais);
            $metadata = $em->getClassMetaData(get_class($arPais));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

        if ($em->flush()) {
            return true;
        } else {
            return false;
        }
    }

    private function generalDepartamento($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query('SELECT
                  codigo_departamento_pk,
                  nombre,
                  codigo_pais_fk,
                  codigo_dane
                 FROM gen_departamento');
        foreach ($datos as $row) {
            $arDepartamento = new GenDepartamento();
            $arDepartamento->setCodigoDepartamentoPk($row['codigo_departamento_pk']);
            $arDepartamento->setNombre(utf8_decode($row['nombre']));
            $arDepartamento->setPaisRel($em->getReference(GenPais::class, $row['codigo_pais_fk']));
            $arDepartamento->setCodigoDane($row['codigo_dane']);
            $em->persist($arDepartamento);
            $metadata = $em->getClassMetaData(get_class($arDepartamento));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

        if ($em->flush()) {
            return true;
        } else {
            return false;
        }
    }

    private function generalCiudad($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query('SELECT
                                codigo_ciudad_pk,
                                nombre,
                                codigo_departamento_fk,
                                codigo_dane
                                FROM gen_ciudad');
        foreach ($datos as $row) {
            $arCiudad = new GenCiudad();
            $arCiudad->setCodigoCiudadPk($row['codigo_ciudad_pk']);
            $arCiudad->setNombre(utf8_decode($row['nombre']));
            $arCiudad->setDepartamentoRel($em->getReference(GenDepartamento::class, $row['codigo_departamento_fk']));
            $arCiudad->setCodigoDane($row['codigo_dane']);
            $em->persist($arCiudad);
            $metadata = $em->getClassMetaData(get_class($arCiudad));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

        if ($em->flush()) {
            return true;
        } else {
            return false;
        }
    }

    private function finCentroCosto($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_centro_costo_pk FROM ctb_centro_costo ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_centro_costo_pk,
                    nombre,            
                    estado_inactivo
                  FROM ctb_centro_costo 
                  ORDER BY codigo_centro_costo_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arCentroCosto = new FinCentroCosto();
                $arCentroCosto->setCodigoCentroCostoPk(utf8_encode($row['codigo_centro_costo_pk']));
                $arCentroCosto->setNombre(utf8_encode($row['nombre']));
                $arCentroCosto->setEstadoInactivo($row['estadoInactivo']);
                $em->persist($arCentroCosto);
                $metadata = $em->getClassMetaData(get_class($arCentroCosto));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
        $em->flush();
    }

    private function rhuGrupo($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                  codigo_centro_costo_pk ,
                  nombre  
                 FROM rhu_centro_costo");
        foreach ($datos as $row) {
            $arGrupo = new RhuGrupo();
            $arGrupo->setCodigoGrupoPk($row['codigo_centro_costo_pk']);
            $arGrupo->setNombre(utf8_encode($row['nombre']));
            $em->persist($arGrupo);
        }

        if ($em->flush()) {
            return true;
        } else {
            return false;
        }
    }

    private function rhuCargo($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_cargo_pk,
                nombre,
                codigo_cargo_supervigilancia_fk
                FROM rhu_cargo");
        foreach ($datos as $row) {
            $arCargo = new RhuCargo();
            $arCargo->setCodigoCargoPk($row['codigo_cargo_pk']);
            $arCargo->setNombre(utf8_encode($row['nombre']));
            if ($row['codigo_cargo_supervigilancia_fk']) {
                $arCargo->setCargoSupervigilanciaRel($em->getReference(RhuCargoSupervigilancia::class, $row['codigo_cargo_supervigilancia_fk']));
            }

            $em->persist($arCargo);
        }
        $em->flush();
    }

    private function rhuEmpleado($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_empleado_pk FROM rhu_empleado ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_empleado_pk,
                numero_identificacion,
                codigo_ciudad_fk,
                codigo_clasificacion_riesgo_fk,                
                codigo_contrato_activo_fk,
                codigo_contrato_ultimo_fk,
                numero_identificacion,
                discapacidad,
                estado_contrato_activo, /*aparece con el nombre de estado_contracto en cromo*/
                carro,
                moto,
                padre_familia,
                cabeza_hogar,
                libreta_militar,
                nombre_corto,
                nombre1,
                nombre2,
                apellido1,
                apellido2,
                telefono,
                celular,
                direccion,
                codigo_ciudad_expedicion_fk, /*aparece con el nombre de codigo_ciudad_expedicion_identificacion_fk en cromo*/
                fecha_expedicion_identificacion,
                barrio,
                codigo_rh_fk,
                codigo_sexo_fk,
                correo,
                fecha_nacimiento,
                tipo_cuenta, /*aparece con el nombre de cuenta_tipo en cromo*/
                codigo_ciudad_nacimiento_fk,
                codigo_estado_civil_fk,
                cuenta,
                codigo_banco_fk,
                camisa, /*aparece con el nombre de talla_camisa en cromo*/
                jeans, /*aparece con el nombre de talla_pantalon en cromo*/
                calzado, /*aparece con el nombre de talla_calzado en cromo*/
                estatura,
                peso, 
                pagado_entidad_salud,
                digito_verificacion,
                codigo_zona_fk,
                codigo_subzona_fk,
                codigo_departamento_empresa_fk
                FROM rhu_empleado 
                ORDER BY codigo_empleado_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arEmpleado = new RhuEmpleado();
                $arEmpleado->setCodigoEmpleadoPk($row['codigo_empleado_pk']);
                $arEmpleado->setIdentificacionRel($em->getReference(GenIdentificacion::class, 'CC'));
                $arEmpleado->setNumeroIdentificacion($row['numero_identificacion']);
                $arEmpleado->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                $arEmpleado->setDiscapacidad($row['discapacidad']);
                $arEmpleado->setEstadoContrato($row['estado_contrato_activo']);
                $arEmpleado->setPadreFamilia($row['padre_familia']);
                $arEmpleado->setCabezaHogar($row['cabeza_hogar']);
                $arEmpleado->setNombreCorto(utf8_encode($row['nombre_corto']));
                $arEmpleado->setNombre1(utf8_encode($row['nombre1']));
                $arEmpleado->setNombre2(utf8_encode($row['nombre2']));
                $arEmpleado->setApellido1(utf8_encode($row['apellido1']));
                $arEmpleado->setApellido2(utf8_encode($row['apellido2']));
                $arEmpleado->setTelefono($row['telefono']);
                $arEmpleado->setCelular($row['celular']);
                $arEmpleado->setDireccion(utf8_encode($row['direccion']));
                $arEmpleado->setBarrio(utf8_encode($row['barrio']));
                $arEmpleado->setCiudadExpedicionRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_expedicion_fk']));
                $arEmpleado->setFechaExpedicionIdentificacion(date_create($row['fecha_expedicion_identificacion']));
                $arEmpleado->setRhRel($em->getReference(RhuRh::class, $row['codigo_rh_fk']));
                $arEmpleado->setSexoRel($em->getReference(GenSexo::class, $row['codigo_sexo_fk']));
                $arEmpleado->setCorreo(utf8_encode($row['correo']));
                $arEmpleado->setFechaNacimiento(date_create($row['fecha_nacimiento']));
                $arEmpleado->setCodigoCuentaTipoFk($row['tipo_cuenta']);
                if ($row['codigo_ciudad_nacimiento_fk']) {
                    $arEmpleado->setCiudadNacimientoRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_nacimiento_fk']));
                }
                $arEmpleado->setEstadoCivilRel($em->getReference(GenEstadoCivil::class, $row['codigo_estado_civil_fk']));
                $arEmpleado->setCuenta($row['cuenta']);
                $arEmpleado->setTallaCamisa($row['camisa']);
                $arEmpleado->setTallaPantalon($row['jeans']);
                $arEmpleado->setTallaCalzado($row['calzado']);
                $arEmpleado->setEstatura($row['estatura']);
                $arEmpleado->setPeso($row['peso']);
                $arEmpleado->setDigitoVerificacion($row['digito_verificacion']);
                $arEmpleado->setCarro($row['carro']);
                $arEmpleado->setMoto($row['moto']);
                $arEmpleado->setCodigoContratoUltimoFk($row['codigo_contrato_ultimo_fk']);
                /*if($row['codigo_contrato_activo_fk']) {
                    $arEmpleado->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_activo_fk']));
                }*/
                $arEmpleado->setCodigoContratoUltimoFk($row['codigo_contrato_ultimo_fk']);
                if ($row['codigo_zona_fk']) {
                    $arEmpleado->setZonaRel($em->getReference(RhuZona::class, $row['codigo_zona_fk']));
                }
                if ($row['codigo_subzona_fk']) {
                    $arEmpleado->setSectorRel($em->getReference(RhuSector::class, $row['codigo_subzona_fk']));
                }
                $arEmpleado->setLibretaMilitar($row['libreta_militar']);
                if ($row['codigo_banco_fk']) {
                    $arEmpleado->setBancoRel($em->getReference(GenBanco::class, $row['codigo_banco_fk']));
                }
                if ($row['codigo_departamento_empresa_fk']) {
                    $arEmpleado->setDepartamentoRel($em->getReference(RhuDepartamento::class, $row['codigo_departamento_empresa_fk']));
                }
                $em->persist($arEmpleado);
                $metadata = $em->getClassMetaData(get_class($arEmpleado));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function rhuContrato($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_contrato_pk FROM rhu_contrato ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    rhu_contrato.codigo_contrato_pk,
                    rhu_contrato_tipo.codigo_externo as codigo_contrato_tipo_externo,
                    rhu_contrato_clase.codigo_externo as codigo_contrato_clase_externo,
                    rhu_clasificacion_riesgo.codigo_externo as codigo_clasificacion_riesgo_externo,
                    rhu_motivo_terminacion_contrato.codigo_externo as codigo_motivo_terminacion_externo,                    
                    rhu_contrato.fecha,
                    rhu_contrato.fecha_desde,
                    rhu_contrato.fecha_hasta,
                    rhu_tipo_tiempo.codigo_externo as codigo_tipo_tiempo_externo,
                    rhu_contrato.factor_horas_dia,
                    rhu_tipo_pension.codigo_externo as codigo_tipo_pension_externo,
                    rhu_tipo_salud.codigo_externo as codigo_tipo_salud_externo,
                    rhu_contrato.codigo_empleado_fk,
                    rhu_contrato.numero,
                    rhu_contrato.codigo_cargo_fk,
                    rhu_contrato.cargo_descripcion,
                    rhu_contrato.vr_salario,
                    rhu_contrato.vr_salario_pago,
                    rhu_contrato.vr_devengado_pactado,
                    rhu_contrato.estado_terminado,
                    rhu_contrato.indefinido,
                    rhu_contrato.comentarios_terminacion,
                    rhu_contrato.codigo_centro_costo_fk,
                    rhu_contrato.fecha_ultimo_pago_cesantias,
                    rhu_contrato.fecha_ultimo_pago_vacaciones,
                    rhu_contrato.fecha_ultimo_pago_primas,
                    rhu_contrato.fecha_ultimo_pago,
                    rhu_contrato.codigo_tipo_cotizante_fk,
                    rhu_contrato.codigo_subtipo_cotizante_fk,
                    rhu_contrato.salario_integral,
                    rhu_entidad_salud.codigo_interface as codigo_entidad_salud_externo,
                    rhu_entidad_pension.codigo_interface as codigo_entidad_pension_externo,
                    rhu_entidad_cesantia.codigo_externo as codigo_entidad_cesantia_externo,
                    rhu_entidad_caja.codigo_interface as codigo_entidad_caja_externo,
                    rhu_contrato.codigo_ciudad_contrato_fk,
                    rhu_contrato.codigo_ciudad_labora_fk,
                    rhu_contrato.codigo_centro_trabajo_fk,
                    rhu_contrato.auxilio_transporte,
                    rhu_contrato.turno_fijo_ordinario,
                    rhu_contrato.codigo_salario_tipo_fk,
                    rhu_empleado.codigo_empleado_tipo_fk,
                    rhu_empleado.centro_costo_fijo,
                    rhu_empleado.centro_costo_distribuido,
                    rhu_empleado.centro_costo_distribuido_fijo,
                    rhu_contrato.codigo_sucursal_fk,
                    rhu_contrato.codigo_centro_costo_contabilidad_fk
                  FROM rhu_contrato 
                  left join rhu_contrato_tipo on rhu_contrato.codigo_contrato_tipo_fk = rhu_contrato_tipo.codigo_contrato_tipo_pk
                  left join rhu_contrato_clase on rhu_contrato_clase.codigo_contrato_clase_pk=rhu_contrato.codigo_contrato_clase_fk
                  left join rhu_clasificacion_riesgo on rhu_clasificacion_riesgo.codigo_clasificacion_riesgo_pk=rhu_contrato.codigo_clasificacion_riesgo_fk
                  left join rhu_motivo_terminacion_contrato on rhu_contrato.codigo_motivo_terminacion_contrato_fk = rhu_motivo_terminacion_contrato.codigo_motivo_terminacion_contrato_pk
                  left join rhu_tipo_pension on rhu_tipo_pension.codigo_tipo_pension_pk=rhu_contrato.codigo_tipo_pension_fk
                  left join rhu_tipo_salud on rhu_tipo_salud.codigo_tipo_salud_pk=rhu_contrato.codigo_tipo_salud_fk
                  left join rhu_entidad_salud on rhu_entidad_salud.codigo_entidad_salud_pk=rhu_contrato.codigo_entidad_salud_fk
                  left join rhu_entidad_pension on rhu_entidad_pension.codigo_entidad_pension_pk=rhu_contrato.codigo_entidad_pension_fk
                  left join rhu_entidad_cesantia on rhu_entidad_cesantia.codigo_entidad_cesantia_pk=rhu_contrato.codigo_entidad_cesantia_fk
                  left join rhu_entidad_caja on rhu_entidad_caja.codigo_entidad_caja_pk=rhu_contrato.codigo_entidad_caja_fk
                  left join rhu_tipo_tiempo on rhu_contrato.codigo_tipo_tiempo_fk=rhu_tipo_tiempo.codigo_tipo_tiempo_pk
                  left join rhu_empleado on rhu_contrato.codigo_empleado_fk=rhu_empleado.codigo_empleado_pk
                  ORDER BY codigo_contrato_pk  limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arContrato = new RhuContrato();
                $arContrato->setCodigoContratoPk($row['codigo_contrato_pk']);
                $arContrato->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                $arContrato->setContratoTipoRel($em->getReference(RhuContratoTipo::class, $row['codigo_contrato_tipo_externo']));
                $arContrato->setContratoClaseRel($em->getReference(RhuContratoClase::class, $row['codigo_contrato_clase_externo']));
                $arContrato->setClasificacionRiesgoRel($em->getReference(RhuClasificacionRiesgo::class, $row['codigo_clasificacion_riesgo_externo']));
                if ($row['codigo_motivo_terminacion_externo']) {
                    $arContrato->setContratoMotivoRel($em->getReference(RhuContratoMotivo::class, $row['codigo_motivo_terminacion_externo']));
                }
                $arContrato->setFecha(date_create($row['fecha']));
                $arContrato->setFechaDesde(date_create($row['fecha_desde']));
                $arContrato->setFechaHasta(date_create($row['fecha_hasta']));
                $arContrato->setFechaUltimoPago(date_create($row['fecha_ultimo_pago']));
                $arContrato->setFechaUltimoPagoCesantias(date_create($row['fecha_ultimo_pago_cesantias']));
                $arContrato->setFechaUltimoPagoPrimas(date_create($row['fecha_ultimo_pago_primas']));
                $arContrato->setFechaUltimoPagoVacaciones(date_create($row['fecha_ultimo_pago_vacaciones']));
                $arContrato->setNumero($row['numero']);
                $arContrato->setTiempoRel($em->getReference(RhuTiempo::class, $row['codigo_tipo_tiempo_externo']));
                $arContrato->setPensionRel($em->getReference(RhuPension::class, $row['codigo_tipo_pension_externo']));
                $arContrato->setSaludRel($em->getReference(RhuSalud::class, $row['codigo_tipo_salud_externo']));
                $arContrato->setFactorHorasDia($row['factor_horas_dia']);
                $arContrato->setCargoRel($em->getReference(RhuCargo::class, $row['codigo_cargo_fk']));
                $arContrato->setCargoDescripcion(utf8_encode($row['cargo_descripcion']));
                $arContrato->setVrSalario($row['vr_salario']);
                $arContrato->setVrSalarioPago($row['vr_salario_pago']);
                $arContrato->setVrDevengadoPactado($row['vr_devengado_pactado']);
                $arContrato->setComentarioTerminacion(utf8_encode($row['comentarios_terminacion']));
                $arContrato->setEstadoTerminado($row['estado_terminado']);
                $arContrato->setIndefinido($row['indefinido']);
                $arContrato->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
                $arContrato->setTipoCotizanteRel($em->getReference(RhuTipoCotizante::class, $row['codigo_tipo_cotizante_fk']));
                $arContrato->setSubtipoCotizanteRel($em->getReference(RhuSubtipoCotizante::class, $row['codigo_subtipo_cotizante_fk']));
                $arContrato->setSalarioIntegral($row['salario_integral']);
                $arContrato->setAuxilioTransporte($row['auxilio_transporte']);
                $arContrato->setTurnoFijo($row['turno_fijo_ordinario']);
                $arContrato->setCiudadLaboraRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_labora_fk']));
                $arContrato->setCiudadContratoRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_contrato_fk']));
                $arEntidadSalud = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_salud_externo']]);
                $arContrato->setEntidadSaludRel($arEntidadSalud);
                $arEntidadPension = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_pension_externo']]);
                $arContrato->setEntidadPensionRel($arEntidadPension);
                $arEntidadCaja = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_caja_externo']]);
                $arContrato->setEntidadCajaRel($arEntidadCaja);
                $arEntidadCesantia = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_cesantia_externo']]);
                $arContrato->setEntidadCesantiaRel($arEntidadCesantia);
                if ($row['codigo_salario_tipo_fk']) {
                    If ($row['codigo_salario_tipo_fk'] == 1) {
                        $arContrato->setSalarioTipoRel($em->getReference(RhuSalarioTipo::class, 'FIJ'));
                    } else {
                        $arContrato->setSalarioTipoRel($em->getReference(RhuSalarioTipo::class, 'VAR'));
                    }
                }
                if ($row['codigo_empleado_tipo_fk']) {
                    $codigoClase = "ADM";
                    switch ($row['codigo_empleado_tipo_fk']) {
                        case 1:
                            $codigoClase = "ADM";
                            break;
                        case 2:
                            $codigoClase = "ADO";
                            break;
                        case 3:
                            $codigoClase = "OPE";
                            break;
                        case 4:
                            $codigoClase = "COM";
                            break;
                    }
                    $arContrato->setCostoClaseRel($em->getReference(RhuCostoClase::class, $codigoClase));
                }
                if ($row['codigo_sucursal_fk']) {
                    $arContrato->setSucursalRel($em->getReference(RhuSucursal::class, $row['codigo_sucursal_fk']));
                }
                $costoTipo = 'FIJ';
                if ($row['centro_costo_fijo'] == 1) {
                    $costoTipo = 'FIJ';
                }
                if ($row['centro_costo_distribuido'] == 1) {
                    $costoTipo = 'OPE';
                }
                if ($row['centro_costo_distribuido_fijo'] == 1) {
                    $costoTipo = 'DIS';
                }
                $arContrato->setCodigoCostoTipoFk($costoTipo);
                if ($row['codigo_centro_costo_contabilidad_fk']) {
                    $arContrato->setCentroCostoRel($em->getReference(FinCentroCosto::class, $row['codigo_centro_costo_contabilidad_fk']));
                }
                $em->persist($arContrato);
                $metadata = $em->getClassMetaData(get_class($arContrato));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

        $arr = $conn->query("SELECT codigo_empleado_pk, codigo_contrato_activo_fk FROM rhu_empleado WHERE codigo_contrato_activo_fk is not null");
        foreach ($arr as $row) {
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($row['codigo_empleado_pk']);
            $arEmpleado->setEstadoContrato(1);
            $arEmpleado->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_activo_fk']));
            $em->persist($arEmpleado);
        }
        $em->flush();
    }

    private function rhuCambioSalario($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_cambio_salario_pk FROM rhu_cambio_salario ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_cambio_salario_pk,
                    codigo_empleado_fk,
                    codigo_contrato_fk,
                    fecha,
                    vr_salario_anterior,
                    vr_salario_nuevo,
                    detalle,
                    codigo_usuario,
                    fecha_inicio
                  FROM rhu_cambio_salario 
                  ORDER BY codigo_cambio_salario_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arCambioSalario = new RhuCambioSalario();
                $arCambioSalario->setCodigoCambioSalarioPk($row['codigo_cambio_salario_pk']);
                $arCambioSalario->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                $arCambioSalario->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                $arCambioSalario->setFecha(date_create($row['fecha']));
                $arCambioSalario->setFechaInicio(date_create($row['fecha_inicio']));
                $arCambioSalario->setVrSalarioAnterior($row['vr_salario_anterior']);
                $arCambioSalario->setVrSalarioNuevo($row['vr_salario_nuevo']);
                $arCambioSalario->setDetalle(utf8_encode($row['detalle']));
                $arCambioSalario->setUsuario(utf8_encode($row['codigo_usuario']));
                $em->persist($arCambioSalario);
                $metadata = $em->getClassMetaData(get_class($arCambioSalario));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }


    }

    private function rhuConcepto($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_pago_concepto_pk,
                nombre,
                por_porcentaje, 
                genera_ingreso_base_prestacion,
                genera_ingreso_base_cotizacion,
                operacion,
                concepto_adicion,
                tipo_adicional,
                concepto_auxilio_transporte,
                concepto_incapacidad,
                concepto_incapacidad_entidad,
                concepto_pension,
                concepto_salud,
                concepto_vacacion,
                concepto_comision,
                concepto_cesantia,
                numero_dian,
                recargo_nocturno,
                concepto_fondo_solidaridad_pensional
                 FROM rhu_pago_concepto");
        foreach ($datos as $row) {
            $arConcepto = new RhuConcepto();
            $arConcepto->setCodigoConceptoPk($row['codigo_pago_concepto_pk']);
            $arConcepto->setNombre(utf8_encode($row['nombre']));
            $arConcepto->setPorcentaje($row['por_porcentaje']);
            $arConcepto->setGeneraIngresoBasePrestacion($row['genera_ingreso_base_prestacion']);
            $arConcepto->setGeneraIngresoBaseCotizacion($row['genera_ingreso_base_cotizacion']);
            $arConcepto->setOperacion($row['operacion']);
            $arConcepto->setAdicional($row['concepto_adicion']);
            $arConcepto->setAuxilioTransporte($row['concepto_auxilio_transporte']);
            $arConcepto->setAdicionalTipo($row['tipo_adicional']);
            $arConcepto->setIncapacidad($row['concepto_incapacidad']);
            $arConcepto->setIncapacidadEntidad($row['concepto_incapacidad_entidad']);
            $arConcepto->setPension($row['concepto_pension']);
            $arConcepto->setSalud($row['concepto_salud']);
            $arConcepto->setVacacion($row['concepto_vacacion']);
            $arConcepto->setComision($row['concepto_comision']);
            $arConcepto->setCesantia($row['concepto_cesantia']);
            $arConcepto->setRecargoNocturno($row['recargo_nocturno']);
            $arConcepto->setFondoSolidaridadPensional($row['concepto_fondo_solidaridad_pensional']);
            $arConcepto->setNumeroDian($row['numero_dian']);
            $em->persist($arConcepto);
        }
        $em->flush();


    }

    private function rhuAdicional($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pago_adicional_pk FROM rhu_pago_adicional ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                        codigo_pago_adicional_pk,
                        codigo_pago_concepto_fk,
                        codigo_empleado_fk,
                        codigo_contrato_fk,
                        fecha,
                        valor,
                        permanente,
                        aplica_dia_laborado,
                        aplica_prima,
                        aplica_cesantia,
                        detalle,
                        estado_inactivo,
                        estado_inactivo_periodo
                 FROM rhu_pago_adicional ORDER BY codigo_pago_adicional_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arAdicional = new RhuAdicional();
                $arAdicional->setCodigoAdicionalPk($row['codigo_pago_adicional_pk']);
                $arAdicional->setConceptoRel($em->getReference(RhuConcepto::class, $row['codigo_pago_concepto_fk']));
                $arAdicional->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                if ($row['codigo_contrato_fk']) {
                    $arAdicional->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                }
                $arAdicional->setFecha(date_create($row['fecha']));
                $arAdicional->setVrValor($row['valor']);
                $arAdicional->setAplicaNomina(1);
                $arAdicional->setAplicaCesantia($row['aplica_cesantia']);
                $arAdicional->setAplicaPrima($row['aplica_prima']);
                if ($row['aplica_prima'] || $row['aplica_cesantia']) {
                    $arAdicional->setAplicaNomina(0);
                }
                $arAdicional->setAplicaDiaLaborado($row['aplica_dia_laborado']);
                $arAdicional->setPermanente($row['permanente']);
                $arAdicional->setDetalle(utf8_encode($row['detalle']));
                $arAdicional->setEstadoInactivo($row['estado_inactivo']);
                $arAdicional->setEstadoInactivoPeriodo($row['estado_inactivo_periodo']);
                $em->persist($arAdicional);
                $metadata = $em->getClassMetaData(get_class($arAdicional));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function rhuEmbargoJuzgado($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                    codigo_embargo_juzgado_pk,
                    nombre,
                    oficina,
                    cuenta                                               
                 FROM rhu_embargo_juzgado");
        foreach ($datos as $row) {
            $arEmbargoJuzgado = new RhuEmbargoJuzgado();
            $arEmbargoJuzgado->setCodigoEmbargoJuzgadoPk($row['codigo_embargo_juzgado_pk']);
            $arEmbargoJuzgado->setNombre(utf8_encode($row['nombre']));
            $arEmbargoJuzgado->setOficina(utf8_encode($row['oficina']));
            $arEmbargoJuzgado->setCuenta(utf8_encode($row['cuenta']));
            $em->persist($arEmbargoJuzgado);
            $metadata = $em->getClassMetaData(get_class($arEmbargoJuzgado));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function rhuEmbargo($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                    codigo_embargo_pk,
                    IF(codigo_embargo_tipo_fk = 1, 'JUD', IF(codigo_embargo_tipo_fk = 2, 'COM', 'ALI')) AS tipo_embargo,
                    codigo_empleado_fk,
                    fecha,
                    numero,
                    estado_activo,
                    valor_fijo,
                    porcentaje_devengado,
                    porcentaje_devengado_menos_descuento_ley,
                    partesExcedaSalarioMinimo,
                    partesExcedaSalarioMinimoMenosDescuentoLey,
                    partesExcedaSalarioMinimoPrestacionalesMenosDescuentoLey,
                    partes,
                    valor,
                    porcentaje,
                    codigo_usuario,
                    comentarios,
                    porcentaje_devengado_prestacional,
                    porcentajeExcedaSalarioMinimo,
                    codigo_embargo_juzgado_fk,
                    cuenta,
                    tipo_cuenta,
                    numero_expediente,
                    numero_proceso,
                    oficio,
                    fecha_inicio_folio,
                    numero_identificacion_demandante,
                    nombre_corto_demandante,
                    numero_identificacion_beneficiario,
                    nombre_corto_beneficiario,
                    fecha_inactivacion,
                    oficina,
                    vr_monto_maximo,
                    porcentaje_devengado_prestacional_menos_descuento_ley,
                    porcentaje_devengado_prestacional_menos_descuento_ley_transporte,
                    porcentaje_devengado_menos_descuento_ley_transporte,
                    numero_radicado,
                    oficio_inactivacion,
                    afecta_nomina,
                    afecta_vacacion,
                    afecta_prima,
                    afecta_liquidacion,
                    afecta_cesantia,                     
                    porcentaje_salario_minimo, 
                    saldo, 
                    descuento, 
                    validar_monto_maximo, 
                    oficina_destino, 
                    consecutivo_juzgado, 
                    codigo_instancia, 
                    apellidos_demandante, 
                    afecta_indemnizacion                                                                      
                 FROM rhu_embargo");
        foreach ($datos as $row) {
            $arEmbargo = new RhuEmbargo();
            $arEmbargo->setCodigoEmbargoPk($row['codigo_embargo_pk']);
            $arEmbargo->setEmbargoTipoRel($em->getReference(RhuEmbargoTipo::class, $row['tipo_embargo']));
            $arEmbargo->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $arEmbargo->setEmbargoJuzgadoRel($em->getReference(RhuEmbargoJuzgado::class, $row['codigo_embargo_juzgado_fk']));
            $arEmbargo->setFecha(date_create($row['fecha']));
            $arEmbargo->setNumero($row['numero']);
            $arEmbargo->setEstadoActivo($row['estado_activo']);
            $arEmbargo->setValorFijo($row['valor_fijo']);
            $arEmbargo->setPorcentajeDevengado($row['porcentaje_devengado']);
            $arEmbargo->setPorcentajeDevengadoMenosDescuentoLey($row['porcentaje_devengado_menos_descuento_ley']);
            $arEmbargo->setPartes($row['partes']);
            $arEmbargo->setPartesExcedaSalarioMinimo($row['partesExcedaSalarioMinimo']);
            $arEmbargo->setPartesExcedaSalarioMinimoMenosDescuentoLey($row['partesExcedaSalarioMinimoMenosDescuentoLey']);
            $arEmbargo->setPartesExcedaSalarioMinimoPrestacionalesMenosDescuentoLey($row['partesExcedaSalarioMinimoPrestacionalesMenosDescuentoLey']);
            $arEmbargo->setVrValor($row['valor']);
            $arEmbargo->setPorcentaje($row['porcentaje']);
            $arEmbargo->setCodigoUsuario($row['codigo_usuario']);
            $arEmbargo->setComentarios(utf8_encode($row['comentarios']));
            $arEmbargo->setPorcentajeDevengado($row['porcentaje_devengado']);
            $arEmbargo->setPorcentajeDevengadoPrestacional($row['porcentaje_devengado_prestacional']);
            $arEmbargo->setPorcentajeDevengadoPrestacionalMenosDescuentoLey($row['porcentaje_devengado_prestacional_menos_descuento_ley']);
            $arEmbargo->setPorcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte($row['porcentaje_devengado_prestacional_menos_descuento_ley_transporte']);
            $arEmbargo->setPorcentajeDevengadoMenosDescuentoLey($row['porcentaje_devengado_menos_descuento_ley']);
            $arEmbargo->setPorcentajeDevengadoMenosDescuentoLeyTransporte($row['porcentaje_devengado_menos_descuento_ley_transporte']);
            $arEmbargo->setPorcentajeExcedaSalarioMinimo($row['porcentajeExcedaSalarioMinimo']);
            $arEmbargo->setPorcentajeSalarioMinimo($row['porcentaje_salario_minimo']);
            $arEmbargo->setCuenta($row['cuenta']);
            $arEmbargo->setTipoCuenta(utf8_encode($row['tipo_cuenta']));
            $arEmbargo->setNumeroExpediente($row['numero_expediente']);
            $arEmbargo->setNumeroProceso($row['numero_proceso']);
            $arEmbargo->setOficio($row['oficio']);
            $arEmbargo->setFechaInicioFolio(date_create($row['fecha_inicio_folio']));
            $arEmbargo->setNumeroIdentificacionBeneficiario(utf8_encode($row['numero_identificacion_beneficiario']));
            $arEmbargo->setNombreCortoBeneficiario(utf8_encode($row['nombre_corto_beneficiario']));
            $arEmbargo->setNumeroIdentificacionDemandante(utf8_encode($row['numero_identificacion_demandante']));
            $arEmbargo->setNombreCortoDemandante(utf8_encode($row['nombre_corto_demandante']));
            $arEmbargo->setApellidosDemandante(utf8_encode($row['apellidos_demandante']));
            $arEmbargo->setFechaInactivacion(date_create($row['fecha_inactivacion']));
            $arEmbargo->setOficina(utf8_encode($row['oficina']));
            $arEmbargo->setOficinaDestino(utf8_encode($row['oficina_destino']));
            $arEmbargo->setVrMontoMaximo($row['vr_monto_maximo']);
            $arEmbargo->setNumeroRadicado($row['numero_radicado']);
            $arEmbargo->setOficioInactivacion($row['oficio_inactivacion']);
            $arEmbargo->setAfectaNomina($row['afecta_nomina']);
            $arEmbargo->setAfectaVacacion($row['afecta_vacacion']);
            $arEmbargo->setAfectaPrima($row['afecta_prima']);
            $arEmbargo->setAfectaLiquidacion($row['afecta_liquidacion']);
            $arEmbargo->setAfectaCesantia($row['afecta_cesantia']);
            $arEmbargo->setAfectaIndemnizacion($row['afecta_indemnizacion']);
            $arEmbargo->setSaldo($row['saldo']);
            $arEmbargo->setDescuento($row['descuento']);
            $arEmbargo->setValidarMontoMaximo($row['validar_monto_maximo']);
            $arEmbargo->setConsecutivoJuzgado($row['consecutivo_juzgado']);
            $arEmbargo->setCodigoInstancia($row['codigo_instancia']);
            $em->persist($arEmbargo);
            $metadata = $em->getClassMetaData(get_class($arEmbargo));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function rhuCreditoTipo($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                        codigo_credito_tipo_pk,
                        nombre,
                        cupo_maximo,
                        codigo_pago_concepto_fk
                 FROM rhu_credito_tipo ");
        foreach ($datos as $row) {
            $arCreditoTipo = new RhuCreditoTipo();
            $arCreditoTipo->setCodigoCreditoTipoPk($row['codigo_credito_tipo_pk']);
            $arCreditoTipo->setNombre(utf8_encode($row['nombre']));
            $arCreditoTipo->setCupoMaximo($row['cupo_maximo']);
            $arCreditoTipo->setConceptoRel($em->getReference(RhuConcepto::class, $row['codigo_pago_concepto_fk']));
            $em->persist($arCreditoTipo);
        }
        $em->flush();
    }

    private function rhuCredito($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                        codigo_credito_pk,
                        codigo_empleado_fk,
                        vr_pagar,
                        vr_cuota,
                        vr_abonos,
                        fecha,
                        fecha_inicio,
                        fecha_credito,
                        fecha_finalizacion,
                        codigo_credito_tipo_fk,
                        codigo_centro_costo_fk,
                        numero_cuotas,
                        numero_cuota_actual,
                        nro_libranza,
                        codigo_usuario,
                        comentarios,
                        aplicar_cuota_prima,
                        aplicar_cuota_cesantia,
                        validar_cuotas,
                        estado_suspendido,
                        estado_inactivo_periodo,
                        saldo,
                        estado_pagado,
                        rhu_credito_tipo_pago.codigo_externo as codigo_credito_pago_tipo_externo
                 FROM rhu_credito 
                 left join rhu_credito_tipo_pago ON rhu_credito.codigo_credito_tipo_pago_fk = rhu_credito_tipo_pago.codigo_credito_tipo_pago_pk");
        foreach ($datos as $row) {
            $arCredito = new RhuCredito();
            $arCredito->setCodigoCreditoPk($row['codigo_credito_pk']);
            $arCredito->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $arCredito->setVrCredito($row['vr_pagar']);
            $arCredito->setVrCuota($row['vr_cuota']);
            $arCredito->setVrSaldo($row['saldo']);
            $arCredito->setVrAbonos($row['vr_abonos']);
            $arCredito->setFecha(date_create($row['fecha']));
            $arCredito->setFechaInicio(date_create($row['fecha_inicio']));
            $arCredito->setFechaCredito(date_create($row['fecha_credito']));
            $arCredito->setFechaFinalizacion(date_create($row['fecha_finalizacion']));
            $arCredito->setCreditoTipoRel($em->getReference(RhuCreditoTipo::class, $row['codigo_credito_tipo_fk']));
            if ($row['codigo_centro_costo_fk']) {
                $arCredito->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
            }
            $arCredito->setNumeroCuotas($row['numero_cuotas']);
            $arCredito->setNumeroCuotaActual($row['numero_cuota_actual']);
            $arCredito->setNumeroLibranza($row['nro_libranza']);
            $arCredito->setUsuario(utf8_encode($row['codigo_usuario']));
            $arCredito->setComentario(utf8_encode($row['comentarios']));
            $arCredito->setAplicarCuotaPrima($row['aplicar_cuota_prima']);
            $arCredito->setAplicarCuotaCesantia($row['aplicar_cuota_cesantia']);
            $arCredito->setValidarCuotas($row['validar_cuotas']);
            $arCredito->setEstadoSuspendido($row['estado_suspendido']);
            $arCredito->setEstadoPagado($row['estado_pagado']);
            $arCredito->setInactivoPeriodo($row['estado_inactivo_periodo']);
            $arCredito->setCreditoPagoTipoRel($em->getReference(RhuCreditoPagoTipo::class, $row['codigo_credito_pago_tipo_externo']));
            $em->persist($arCredito);
            $metadata = $em->getClassMetaData(get_class($arCredito));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();


    }

    private function rhuCreditoPago($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_credito_pago_pk FROM rhu_credito_pago ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                        codigo_credito_pago_pk,
                        codigo_credito_fk,
                        rhu_credito_tipo_pago.codigo_externo as codigo_credito_pago_tipo_externo, 
                        vr_cuota, 
                        saldo, 
                        numero_cuota_actual,
                        fecha_pago                                                
                 FROM rhu_credito_pago
                 left join rhu_credito_tipo_pago ON rhu_credito_pago.codigo_credito_tipo_pago_fk = rhu_credito_tipo_pago.codigo_credito_tipo_pago_pk 
                 ORDER BY codigo_credito_pago_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arCreditoPago = new RhuCreditoPago();
                $arCreditoPago->setCodigoCreditoPagoPk($row['codigo_credito_pago_pk']);
                $arCreditoPago->setCreditoRel($em->getReference(RhuCredito::class, $row['codigo_credito_fk']));
                if ($row['codigo_credito_pago_tipo_externo']) {
                    $arCreditoPago->setCreditoPagoTipoRel($em->getReference(RhuCreditoPagoTipo::class, $row['codigo_credito_pago_tipo_externo']));
                }
                $arCreditoPago->setVrPago($row['vr_cuota']);
                $arCreditoPago->setVrSaldo($row['saldo']);
                $arCreditoPago->setNumeroCuotaActual($row['numero_cuota_actual']);
                $arCreditoPago->setFechaPago(date_create($row['fecha_pago']));
                $em->persist($arCreditoPago);
                $metadata = $em->getClassMetaData(get_class($arCreditoPago));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuVacacion($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                        codigo_vacacion_pk,
                        numero,
                        codigo_empleado_fk,                        
                        codigo_contrato_fk,
                        codigo_centro_costo_fk,
                        fecha,
                        fecha_desde_periodo,
                        fecha_hasta_periodo,
                        fecha_desde_disfrute,
                        fecha_hasta_disfrute,
                        fecha_contabilidad,
                        fecha_inicio_labor,
                        dias_disfrutados,
                        dias_disfrutados_reales,
                        dias_pagados,
                        dias_vacaciones,
                        dias_ausentismo,
                        dias_ausentismo_propuesto,
                        vr_ibc_promedio,
                        vr_salud,
                        vr_salud_propuesto,
                        vr_pension,
                        vr_pension_propuesto,
                        vr_fondo_solidaridad,
                        vr_ibc,
                        vr_deduccion,
                        vr_bonificacion,
                        vr_vacacion_disfrute,
                        vr_vacacion_dinero,
                        vr_vacacion,
                        vr_salario_actual,
                        vr_salario_promedio,
                        vr_salario_promedio_propuesto,
                        vr_vacacion_bruto,
                        vr_recargo_nocturno,
                        vr_recargo_nocturno_inicial,
                        vr_promedio_recargo_nocturno,
                        comentarios
                 FROM rhu_vacacion");
        foreach ($datos as $row) {
            $arVacacion = new RhuVacacion();
            $arVacacion->setCodigoVacacionPk($row['codigo_vacacion_pk']);
            $arVacacion->setVacacionTipoRel($em->getReference(RhuVacacionTipo::class, 'GEN'));
            $arVacacion->setNumero($row['numero']);
            $arVacacion->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $arVacacion->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
            $arVacacion->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
            $arVacacion->setFecha(date_create($row['fecha']));
            $arVacacion->setFechaDesdePeriodo(date_create($row['fecha_desde_periodo']));
            $arVacacion->setFechaHastaPeriodo(date_create($row['fecha_hasta_periodo']));
            $arVacacion->setFechaDesdeDisfrute(date_create($row['fecha_desde_disfrute']));
            $arVacacion->setFechaHastaDisfrute(date_create($row['fecha_hasta_disfrute']));
            $arVacacion->setFechaInicioLabor(date_create($row['fecha_inicio_labor']));
            $arVacacion->setDiasDisfrutados($row['dias_disfrutados']);
            $arVacacion->setDiasDisfrutadosReales($row['dias_disfrutados_reales']);
            $arVacacion->setDiasPagados($row['dias_pagados']);
            $arVacacion->setDias($row['dias_vacaciones']);
            $arVacacion->setDiasAusentismo($row['dias_ausentismo']);
            $arVacacion->setDiasAusentismoPropuesto($row['dias_ausentismo_propuesto']);
            $arVacacion->setVrIbcPromedio($row['vr_ibc_promedio']);
            $arVacacion->setVrSalud($row['vr_salud']);
            $arVacacion->setVrSaludPropuesto($row['vr_salud_propuesto']);
            $arVacacion->setVrPension($row['vr_pension']);
            $arVacacion->setVrPensionPropuesto($row['vr_pension_propuesto']);
            $arVacacion->setVrFondoSolidaridad($row['vr_fondo_solidaridad']);
            $arVacacion->setVrIbc($row['vr_ibc']);
            $arVacacion->setVrDeduccion($row['vr_deduccion']);
            $arVacacion->setVrBonificacion($row['vr_bonificacion']);
            $arVacacion->setVrDisfrute($row['vr_vacacion_disfrute']);
            $arVacacion->setVrDinero($row['vr_vacacion_dinero']);
            $arVacacion->setVrValor($row['vr_vacacion']);
            $arVacacion->setVrSalarioActual($row['vr_salario_actual']);
            $arVacacion->setVrSalarioPromedio($row['vr_salario_promedio']);
            $arVacacion->setVrSalarioPromedioPropuesto($row['vr_salario_promedio_propuesto']);
            $arVacacion->setVrBruto($row['vr_vacacion_bruto']);
            $arVacacion->setVrRecargoNocturno($row['vr_recargo_nocturno']);
            $arVacacion->setVrRecargoNocturnoInicial($row['vr_recargo_nocturno_inicial']);
            $arVacacion->setVrPromedioRecargoNocturno($row['vr_promedio_recargo_nocturno']);
            $arVacacion->setComentarios(utf8_encode($row['comentarios']));
            $em->persist($arVacacion);
            $metadata = $em->getClassMetaData(get_class($arVacacion));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();


    }

    private function rhuLiquidacion($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                        codigo_liquidacion_pk,
                        codigo_empleado_fk,
                        codigo_contrato_fk,
                        rhu_motivo_terminacion_contrato.codigo_externo as codigo_contrato_motivo_externo,
                        codigo_motivo_terminacion_contrato_fk,
                        fecha,
                        numero,
                        fecha_desde,
                        fecha_hasta,
                        numero_dias,
                        vr_cesantias,
                        vr_intereses_cesantias,
                        vr_cesantias_anterior,
                        vr_intereses_cesantias_anterior,
                        vr_prima,
                        vr_deduccion_prima,
                        vr_vacaciones,
                        vr_indemnizacion,
                        comentarios,
                        dias_cesantias,
                        dias_cesantias_ausentismo,
                        dias_cesantias_anterior,
                        dias_cesantias_ausentismo_anterior,
                        dias_vacaciones,
                        dias_vacaciones_ausentismo,
                        dias_primas,
                        dias_primas_ausentismo,
                        dias_laborados,
                        fecha_ultimo_pago,
                        vr_ingreso_base_prestacion_adicional,
                        vr_ingreso_base_prestacion_cesantias,
                        vr_ingreso_base_prestacion_primas,
                        vr_ingreso_base_prestacion_cesantias_inicial,
                        vr_ingreso_base_prestacion_primas_inicial,
                        dias_adicionales_ibp,
                        vr_base_prestaciones,
                        vr_base_prestaciones_total,
                        vr_auxilio_transporte,
                        vr_salario,
                        vr_salario_promedio_cesantias,
                        vr_salario_promedio_cesantias_anterior,
                        vr_salario_promedio_primas,
                        vr_salario_vacaciones,
                        vr_total,
                        liquidar_cesantias,
                        liquidar_vacaciones,
                        liquidar_prima,
                        fecha_ultimo_pago_primas,
                        fecha_ultimo_pago_vacaciones,
                        fecha_ultimo_pago_cesantias,
                        fecha_ultimo_pago_cesantias_anterior,
                        vr_deducciones,
                        vr_bonificaciones,
                        estado_autorizado,
                        estado_generado,
                        estado_anulado,
                        fecha_inicio_contrato,
                        codigo_usuario,
                        liquidar_manual,
                        estado_pago_banco,
                        liquidar_salario,
                        porcentaje_ibp,
                        estado_contabilizado,
                        dias_ausentismo_adicional,
                        vr_salario_vacacion_propuesto,
                        vr_salario_prima_propuesto,
                        vr_salario_cesantias_propuesto,
                        eliminar_ausentismo,
                        dias_ausentismo_propuesto,
                        codigo_programacion_pago_detalle_fk,
                        codigo_pago_fk,
                        omitir_cesantias_anterior,
                        eliminar_ausentismo_cesantia,
                        eliminar_ausentismo_primas,
                        eliminar_ausentismo_vacacion,
                        dias_ausentismo_propuesto_cesantias,
                        dias_ausentismo_propuesto_primas,
                        dias_ausentismo_propuesto_vacaciones,
                        vr_suplementario_censatias,
                        vr_suplementario_primas,
                        vr_suplementario_vacaciones,
                        porcentaje_intereses_cesantias,
                        vr_deduccion_prima_propuesto,
                        dias_deduccion_primas_,
                        omitir_interes_cesantias_anterior,
                        codigo_programacion_pago_detalle_interes_fk,
                        codigo_pago_interes_fk,
                        estado_indemnizacion,
                        fecha_hasta_contrato_fijo,
                        vr_indemnizacion_propuesto,
                        vr_intereses_propuesto
                 FROM rhu_liquidacion
                 left join rhu_motivo_terminacion_contrato on codigo_motivo_terminacion_contrato_fk = rhu_motivo_terminacion_contrato.codigo_motivo_terminacion_contrato_pk");
        foreach ($datos as $row) {
            $arLiquidacion = new RhuLiquidacion();
            $arLiquidacion->setCodigoLiquidacionPk($row['codigo_liquidacion_pk']);
            $arLiquidacion->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $arLiquidacion->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
            $arLiquidacion->setLiquidacionTipoRel($em->getReference(RhuLiquidacionTipo::class, 'GEN'));
            if ($row['codigo_contrato_motivo_externo']) {
                $arLiquidacion->setMotivoTerminacionRel($em->getReference(RhuContratoMotivo::class, $row['codigo_contrato_motivo_externo']));
            }
            $arLiquidacion->setFecha(date_create($row['fecha']));
            $arLiquidacion->setNumero($row['numero']);
            $arLiquidacion->setFechaDesde(date_create($row['fecha_desde']));
            $arLiquidacion->setFechaHasta(date_create($row['fecha_hasta']));
            $arLiquidacion->setFechaInicioContrato(date_create($row['fecha_inicio_contrato']));
            $arLiquidacion->setNumeroDias($row['numero_dias']);
            $arLiquidacion->setVrCesantias($row['vr_cesantias']);
            $arLiquidacion->setVrInteresesCesantias($row['vr_intereses_cesantias']);
            $arLiquidacion->setVrPrima($row['vr_prima']);
            $arLiquidacion->setVrVacacion($row['vr_vacaciones']);
            $arLiquidacion->setVrIndemnizacion($row['vr_indemnizacion']);
            $arLiquidacion->setVrDeducciones($row['vr_deducciones']);
            $arLiquidacion->setVrBonificaciones($row['vr_bonificaciones']);
            $arLiquidacion->setVrAuxilioTransporte($row['vr_auxilio_transporte']);
            $arLiquidacion->setVrSalario($row['vr_salario']);
            $arLiquidacion->setVrTotal($row['vr_total']);
            $arLiquidacion->setVrIngresoBasePrestacionCesantias($row['vr_ingreso_base_prestacion_cesantias']);
            $arLiquidacion->setVrIngresoBasePrestacionCesantiasInicial($row['vr_ingreso_base_prestacion_cesantias_inicial']);
            $arLiquidacion->setVrSalarioPromedioCesantiasAnterior($row['vr_salario_promedio_cesantias_anterior']);
            $arLiquidacion->setVrIngresoBasePrestacionPrimas($row['vr_ingreso_base_prestacion_primas']);
            $arLiquidacion->setVrIngresoBasePrestacionPrimasInicial($row['vr_ingreso_base_prestacion_primas_inicial']);
            $arLiquidacion->setVrSalarioPromedioPrimas($row['vr_salario_promedio_primas']);
            $arLiquidacion->setVrSalarioVacacionPropuesto($row['vr_salario_vacacion_propuesto']);
            $arLiquidacion->setDiasCesantias($row['dias_cesantias']);
            $arLiquidacion->setDiasCesantiasAusentismo($row['dias_cesantias_ausentismo']);
            $arLiquidacion->setDiasVacacion($row['dias_vacaciones']);
            $arLiquidacion->setDiasVacacionAusentismo($row['dias_vacaciones_ausentismo']);
            $arLiquidacion->setDiasPrima($row['dias_primas']);
            $arLiquidacion->setDiasPrimaAusentismo($row['dias_primas_ausentismo']);
            $arLiquidacion->setDiasCesantiasAnterior($row['dias_cesantias_anterior']);
            $arLiquidacion->setDiasAusentismoPropuestoPrimas($row['dias_ausentismo_propuesto_primas']);
            $arLiquidacion->setDiasAusentismoAdicional($row['dias_ausentismo_adicional']);
            $arLiquidacion->setVrCesantiasAnterior($row['vr_cesantias_anterior']);
            $arLiquidacion->setVrInteresesCesantiasAnterior($row['vr_intereses_cesantias_anterior']);
            $arLiquidacion->setDiasCesantiasAusentismoAnterior($row['dias_cesantias_ausentismo_anterior']);
            $arLiquidacion->setDiasAusentismoPropuestoCesantias($row['dias_ausentismo_propuesto_cesantias']);
            $arLiquidacion->setPorcentajeInteresesCesantias($row['porcentaje_intereses_cesantias']);
            $arLiquidacion->setVrInteresesPropuesto($row['vr_intereses_propuesto']);
            $arLiquidacion->setVrDeduccionPrima($row['vr_deduccion_prima']);
            $arLiquidacion->setVrDeduccionPrimaPropuesto($row['vr_deduccion_prima_propuesto']);
            $arLiquidacion->setDiasDeduccionPrimas($row['dias_deduccion_primas_']);
            $arLiquidacion->setEstadoIndemnizacion($row['estado_indemnizacion']);
            $arLiquidacion->setPorcentajeIbp($row['porcentaje_ibp']);
            $arLiquidacion->setDiasDeduccionPrimas($row['dias_deduccion_primas_']);
            $arLiquidacion->setDiasAdicionalesIBP($row['dias_adicionales_ibp']);
            $arLiquidacion->setVrIndemnizacionPropuesto($row['vr_indemnizacion_propuesto']);
            $arLiquidacion->setVrIngresoBasePrestacionAdicional($row['vr_ingreso_base_prestacion_adicional']);
            $arLiquidacion->setVrSalarioCesantiasPropuesto($row['vr_salario_cesantias_propuesto']);
            $arLiquidacion->setVrSalarioPrimaPropuesto($row['vr_salario_prima_propuesto']);
            $arLiquidacion->setDiasAusentismoPropuestoVacaciones($row['dias_ausentismo_propuesto_vacaciones']);
            $arLiquidacion->setLiquidarCesantias($row['liquidar_cesantias']);
            $arLiquidacion->setLiquidarVacaciones($row['liquidar_vacaciones']);
            $arLiquidacion->setLiquidarPrima($row['liquidar_prima']);
            $arLiquidacion->setLiquidarSalario($row['liquidar_salario']);
            $arLiquidacion->setLiquidarManual($row['liquidar_manual']);
            $arLiquidacion->setVrSalarioVacaciones($row['vr_salario_vacaciones']);
            $arLiquidacion->setFechaUltimoPago(date_create($row['fecha_ultimo_pago']));
            $arLiquidacion->setFechaUltimoPagoPrima(date_create($row['fecha_ultimo_pago_primas']));
            $arLiquidacion->setFechaUltimoPagoVacacion(date_create($row['fecha_ultimo_pago_vacaciones']));
            $arLiquidacion->setFechaUltimoPagoCesantias(date_create($row['fecha_ultimo_pago_cesantias']));
            $arLiquidacion->setFechaUltimoPagoCesantiasAnterior(date_create($row['fecha_ultimo_pago_cesantias_anterior']));
            $arLiquidacion->setEliminarAusentismo($row['eliminar_ausentismo']);
            $arLiquidacion->setEliminarAusentismoCesantia($row['eliminar_ausentismo_cesantia']);
            $arLiquidacion->setEliminarAusentismoPrima($row['eliminar_ausentismo_primas']);
            $arLiquidacion->setEliminarAusentismoVacacion($row['eliminar_ausentismo_vacacion']);
            $arLiquidacion->setEstadoAutorizado($row['estado_autorizado']);
            $arLiquidacion->setEstadoAprobado($row['estado_generado']);
            $arLiquidacion->setEstadoAnulado($row['estado_anulado']);
            $arLiquidacion->setEstadoContabilizado($row['estado_contabilizado']);
            $arLiquidacion->setOmitirCesantiasAnterior($row['omitir_cesantias_anterior']);
            $arLiquidacion->setOmitirInteresCesantiasAnterior($row['omitir_interes_cesantias_anterior']);
            $em->persist($arLiquidacion);
            $metadata = $em->getClassMetaData(get_class($arLiquidacion));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();


    }

    private function rhuAporte($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pago_pk FROM rhu_pago ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_periodo_detalle_pk,
                codigo_sucursal_fk,
                rhu_sso_periodo.fecha_desde AS fecha_desde,
		        rhu_sso_periodo.fecha_hasta AS fecha_hasta,
		        rhu_sso_periodo.anio AS anio,
		        rhu_sso_periodo.mes AS mes,
		        rhu_sso_periodo.anio_pago AS anio_salud,
		        rhu_sso_periodo.mes_pago AS mes_salud,
                numero_registros,
                numero_empleados,
                total_cotizacion,
                total_ingreso_base_cotizacion_caja
                 FROM rhu_sso_periodo_detalle  
                 LEFT JOIN rhu_sso_periodo ON rhu_sso_periodo_detalle.codigo_periodo_fk = rhu_sso_periodo.codigo_periodo_pk 
                 ORDER BY codigo_periodo_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arAporte = new RhuAporte();
                $arAporte->setCodigoAportePk($row['codigo_periodo_detalle_pk']);
                $arAporte->setSucursalRel($em->getReference(RhuSucursal::class, $row['codigo_sucursal_fk']));
                $arAporte->setNumero($row['numero_registros']);
                $arAporte->setAnio($row['anio']);
                $arAporte->setMes($row['mes']);
                $arAporte->setAnioSalud($row['anio_salud']);
                $arAporte->setMesSalud($row['mes_salud']);
                $arAporte->setFechaDesde(date_create($row['fecha_desde']));
                $arAporte->setFechaHasta(date_create($row['fecha_hasta']));
                $arAporte->setFormaPresentacion('S');
                $arAporte->setCantidadEmpleados($row['numero_empleados']);
                $arAporte->setVrTotal($row['total_cotizacion']);
                $arAporte->setVrIngresoBaseCotizacion($row['total_ingreso_base_cotizacion_caja']);
                $arAporte->setEstadoAutorizado(1);
                $arAporte->setEstadoAprobado(1);
                $arAporte->setEstadoAnulado(0);
                $arAporte->setEstadoContabilizado(0);
                $em->persist($arAporte);
                $metadata = $em->getClassMetaData(get_class($arAporte));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuAporteDetalle($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_aporte_pk FROM rhu_sso_aporte ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_aporte_pk,
                codigo_periodo_detalle_fk,
                codigo_sucursal_fk,
                codigo_empleado_fk,
                codigo_contrato_fk,
                rhu_entidad_salud.codigo_interface as codigo_entidad_salud_externo,
                rhu_entidad_pension.codigo_interface as codigo_entidad_pension_externo,
                rhu_entidad_caja.codigo_interface as codigo_entidad_caja_externo,
                anio,
                mes,
                fecha_desde,
                fecha_hasta,
                tipo_registro,
                secuencia,
                tipo_documento,
                tipo_cotizante,
                subtipo_cotizante,
                extranjero_no_obligado_cotizar_pension,
                colombiano_residente_exterior,
                codigo_departamento_ubicacion_laboral,
                codigo_municipio_ubicacion_laboral,
                primer_nombre,
                segundo_nombre,
                primer_apellido,
                segundo_apellido,
                ingreso,
                retiro,
                traslado_desde_otra_eps,
                traslado_a_otra_eps,
                traslado_desde_otra_pension,
                traslado_a_otra_pension,
                variacion_permanente_salario,
                correcciones,
                variacion_transitoria_salario,
                suspension_temporal_contrato_licencia_servicios,
                dias_licencia,
                incapacidad_general,    
                dias_incapacidad_general,
                licencia_maternidad,
                dias_licencia_maternidad,
                vacaciones,
                dias_vacaciones,
                aporte_voluntario,
                variacion_centros_trabajo,
                incapacidad_accidente_trabajo_enfermedad_profesional,
                codigo_entidad_pension_pertenece,
                codigo_entidad_pension_traslada,
                codigo_entidad_salud_pertenece,
                codigo_entidad_salud_traslada,
                codigo_entidad_caja_pertenece,
                dias_cotizados_pension,
                dias_cotizados_salud,
                dias_cotizados_riesgos_profesionales,
                dias_cotizados_caja_compensacion,
                salario_basico,
                salario_mes_anterior,
                vr_vacaciones,
                salario_integral,
                suplementario,
                vr_ingreso_base_cotizacion,
                ibc_pension,
                ibc_salud,
                ibc_riesgos_profesionales,
                ibc_caja,
                tarifa_pension,
                tarifa_salud,
                tarifa_riesgos,
                tarifa_caja,
                tarifa_sena,
                tarifa_icbf,
                cotizacion_pension,
                cotizacion_salud,
                cotizacion_riesgos,
                cotizacion_caja,
                cotizacion_sena,
                cotizacion_icbf,
                aporte_voluntario_fondo_pensiones_obligatorias,
                cotizacion_voluntario_fondo_pensiones_obligatorias,
                total_cotizacion_fondos,
                aportes_fondo_solidaridad_pensional_solidaridad,
                aportes_fondo_solidaridad_pensional_subsistencia,
                valor_upc_adicional,
                numero_autorizacion_incapacidad_enfermedad_general,
                valor_incapacidad_enfermedad_general,
                numero_autorizacion_licencia_maternidad_paternidad,
                valor_incapacidad_licencia_maternidad_paternidad,
                centro_trabajo_codigo_ct,
                tarifa_aporte_esap,
                valor_aporte_esap,
                tarifa_aporte_men,
                valor_aporte_men,
                tipo_documento_responsable_upc,
                numero_identificacion_responsable_upc_adicional,
                cotizante_exonerado_pago_aporte_parafiscales_salud,
                codigo_administradora_riesgos_laborales,
                clase_riesgo_afiliado,
                total_cotizacion,
                indicador_tarifa_especial_pensiones,
                fecha_ingreso,
                fecha_retiro,
                fecha_inicio_vsp,
                fecha_inicio_sln,
                fecha_fin_sln,
                fecha_inicio_ige,
                fecha_fin_ige,
                fecha_inicio_lma,
                fecha_fin_lma,
                fecha_inicio_vac_lr,
                fecha_fin_vac_lr,
                fecha_inicio_vct,
                fecha_fin_vct,
                fecha_inicio_irl,
                fecha_fin_irl,
                ibc_otros_parafiscales_diferentes_ccf,
                numero_horas_laboradas
                  FROM rhu_sso_aporte
                  left join rhu_entidad_salud on rhu_entidad_salud.codigo_entidad_salud_pk=rhu_sso_aporte.codigo_entidad_salud_fk
                  left join rhu_entidad_pension on rhu_entidad_pension.codigo_entidad_pension_pk=rhu_sso_aporte.codigo_entidad_pension_fk
                  left join rhu_entidad_caja on rhu_entidad_caja.codigo_entidad_caja_pk=rhu_sso_aporte.codigo_entidad_caja_fk  
                 ORDER BY codigo_aporte_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arAporteDetalle = new RhuAporteDetalle();
                $arAporteDetalle->setCodigoAporteDetallePk($row['codigo_aporte_pk']);
                $arAporteDetalle->setAporteRel($em->getReference(RhuAporte::class, $row['codigo_periodo_detalle_fk']));
                $arAporteDetalle->setSucursalRel($em->getReference(RhuSucursal::class, $row['codigo_sucursal_fk']));
                $arAporteDetalle->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                $arAporteDetalle->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                $arEntidadSalud = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_salud_externo']]);
                $arAporteDetalle->setEntidadSaludRel($arEntidadSalud);
                $arEntidadPension = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_pension_externo']]);
                $arAporteDetalle->setEntidadPensionRel($arEntidadPension);
                $arEntidadCaja = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_caja_externo']]);
                $arAporteDetalle->setEntidadCajaRel($arEntidadCaja);
                $arAporteDetalle->setAnio($row['anio']);
                $arAporteDetalle->setMes($row['mes']);
                $arAporteDetalle->setFechaDesde(date_create($row['fecha_desde']));
                $arAporteDetalle->setFechaHasta(date_create($row['fecha_hasta']));
                $arAporteDetalle->setTipoRegistro($row['tipo_registro']);
                $arAporteDetalle->setSecuencia($row['secuencia']);
                $arAporteDetalle->setTipoDocumento($row['tipo_documento']);
                $arAporteDetalle->setTipoCotizante($row['tipo_cotizante']);
                $arAporteDetalle->setSubtipoCotizante($row['subtipo_cotizante']);
                $arAporteDetalle->setExtranjeroNoObligadoCotizarPension($row['extranjero_no_obligado_cotizar_pension']);
                $arAporteDetalle->setColombianoResidenteExterior($row['colombiano_residente_exterior']);
                $arAporteDetalle->setCodigoDepartamentoUbicacionlaboral($row['codigo_departamento_ubicacion_laboral']);
                $arAporteDetalle->setCodigoMunicipioUbicacionlaboral($row['codigo_municipio_ubicacion_laboral']);
                $arAporteDetalle->setPrimerNombre(utf8_encode($row['primer_nombre']));
                $arAporteDetalle->setSegundoNombre(utf8_encode($row['segundo_nombre']));
                $arAporteDetalle->setPrimerApellido(utf8_encode($row['primer_apellido']));
                $arAporteDetalle->setSegundoApellido(utf8_encode($row['segundo_apellido']));
                $arAporteDetalle->setIngreso($row['ingreso']);
                $arAporteDetalle->setRetiro($row['retiro']);
                $arAporteDetalle->setTrasladoDesdeOtraEps($row['traslado_desde_otra_eps']);
                $arAporteDetalle->setTrasladoAOtraEps($row['traslado_a_otra_eps']);
                $arAporteDetalle->setTrasladoDesdeOtraPension($row['traslado_desde_otra_pension']);
                $arAporteDetalle->setTrasladoAOtraPension($row['traslado_a_otra_pension']);
                $arAporteDetalle->setVariacionPermanenteSalario($row['variacion_permanente_salario']);
                $arAporteDetalle->setCorrecciones($row['correcciones']);
                $arAporteDetalle->setVariacionTransitoriaSalario($row['variacion_transitoria_salario']);
                $arAporteDetalle->setSuspensionTemporalContratoLicenciaServicios($row['suspension_temporal_contrato_licencia_servicios']);
                $arAporteDetalle->setDiasLicencia($row['dias_licencia']);
                $arAporteDetalle->setIncapacidadGeneral($row['incapacidad_general']);
                $arAporteDetalle->setDiasIncapacidadGeneral($row['dias_incapacidad_general']);
                $arAporteDetalle->setLicenciaMaternidad($row['licencia_maternidad']);
                $arAporteDetalle->setDiasLicenciaMaternidad($row['dias_licencia_maternidad']);
                $arAporteDetalle->setVacaciones($row['vacaciones']);
                $arAporteDetalle->setDiasVacaciones($row['dias_vacaciones']);
                $arAporteDetalle->setAporteVoluntario($row['aporte_voluntario']);
                $arAporteDetalle->setVariacionCentrosTrabajo($row['variacion_centros_trabajo']);
                $arAporteDetalle->setIncapacidadAccidenteTrabajoEnfermedadProfesional($row['incapacidad_accidente_trabajo_enfermedad_profesional']);
                $arAporteDetalle->setCodigoEntidadPensionPertenece($row['codigo_entidad_pension_pertenece']);
                $arAporteDetalle->setCodigoEntidadPensionTraslada($row['codigo_entidad_pension_traslada']);
                $arAporteDetalle->setCodigoEntidadSaludPertenece($row['codigo_entidad_salud_pertenece']);
                $arAporteDetalle->setCodigoEntidadSaludTraslada($row['codigo_entidad_salud_traslada']);
                $arAporteDetalle->setCodigoEntidadCajaPertenece($row['codigo_entidad_caja_pertenece']);
                $arAporteDetalle->setDiasCotizadosPension($row['dias_cotizados_pension']);
                $arAporteDetalle->setDiasCotizadosSalud($row['dias_cotizados_salud']);
                $arAporteDetalle->setDiasCotizadosRiesgosProfesionales($row['dias_cotizados_riesgos_profesionales']);
                $arAporteDetalle->setDiasCotizadosCajaCompensacion($row['dias_cotizados_caja_compensacion']);
                $arAporteDetalle->setSalarioBasico($row['salario_basico']);
                $arAporteDetalle->setSalarioMesAnterior($row['salario_mes_anterior']);
                $arAporteDetalle->setVrVacaciones($row['vr_vacaciones']);
                $arAporteDetalle->setSalarioIntegral($row['salario_integral']);
                $arAporteDetalle->setSuplementario($row['suplementario']);
                $arAporteDetalle->setVrIngresoBaseCotizacion($row['vr_ingreso_base_cotizacion']);
                $arAporteDetalle->setIbcPension($row['ibc_pension']);
                $arAporteDetalle->setIbcSalud($row['ibc_salud']);
                $arAporteDetalle->setIbcRiesgosProfesionales($row['ibc_riesgos_profesionales']);
                $arAporteDetalle->setIbcCaja($row['ibc_caja']);
                $arAporteDetalle->setTarifaPension($row['tarifa_pension']);
                $arAporteDetalle->setTarifaSalud($row['tarifa_salud']);
                $arAporteDetalle->setTarifaRiesgos($row['tarifa_riesgos']);
                $arAporteDetalle->setTarifaCaja($row['tarifa_caja']);
                $arAporteDetalle->setTarifaSena($row['tarifa_sena']);
                $arAporteDetalle->setTarifaIcbf($row['tarifa_icbf']);
                $arAporteDetalle->setCotizacionPension($row['cotizacion_pension']);
                $arAporteDetalle->setCotizacionSalud($row['cotizacion_salud']);
                $arAporteDetalle->setCotizacionRiesgos($row['cotizacion_riesgos']);
                $arAporteDetalle->setCotizacionCaja($row['cotizacion_caja']);
                $arAporteDetalle->setCotizacionSena($row['cotizacion_sena']);
                $arAporteDetalle->setCotizacionIcbf($row['cotizacion_icbf']);
                $arAporteDetalle->setAporteVoluntarioFondoPensionesObligatorias($row['aporte_voluntario_fondo_pensiones_obligatorias']);
                $arAporteDetalle->setCotizacionVoluntarioFondoPensionesObligatorias($row['cotizacion_voluntario_fondo_pensiones_obligatorias']);
                $arAporteDetalle->setTotalCotizacionFondos($row['total_cotizacion_fondos']);
                $arAporteDetalle->setAportesFondoSolidaridadPensionalSolidaridad($row['aportes_fondo_solidaridad_pensional_solidaridad']);
                $arAporteDetalle->setAportesFondoSolidaridadPensionalSubsistencia($row['aportes_fondo_solidaridad_pensional_subsistencia']);
                $arAporteDetalle->setValorUpcAdicional($row['valor_upc_adicional']);
                $arAporteDetalle->setNumeroAutorizacionIncapacidadEnfermedadGeneral($row['numero_autorizacion_incapacidad_enfermedad_general']);
                $arAporteDetalle->setValorIncapacidadEnfermedadGeneral($row['valor_incapacidad_enfermedad_general']);
                $arAporteDetalle->setNumeroAutorizacionLicenciaMaternidadPaternidad($row['numero_autorizacion_licencia_maternidad_paternidad']);
                $arAporteDetalle->setValorIncapacidadLicenciaMaternidadPaternidad($row['valor_incapacidad_licencia_maternidad_paternidad']);
                $arAporteDetalle->setCentroTrabajoCodigoCt($row['centro_trabajo_codigo_ct']);
                $arAporteDetalle->setCodigoCargoFk(null);
                $arAporteDetalle->setTarifaAportesESAP($row['tarifa_aporte_esap']);
                $arAporteDetalle->setValorAportesESAP($row['valor_aporte_esap']);
                $arAporteDetalle->setTarifaAportesMEN($row['tarifa_aporte_men']);
                $arAporteDetalle->setValorAportesMEN($row['valor_aporte_men']);
                $arAporteDetalle->setTipoDocumentoResponsableUPC($row['tipo_documento_responsable_upc']);
                $arAporteDetalle->setNumeroIdentificacionResponsableUPCAdicional($row['numero_identificacion_responsable_upc_adicional']);
                $arAporteDetalle->setCotizanteExoneradoPagoAporteParafiscalesSalud($row['cotizante_exonerado_pago_aporte_parafiscales_salud']);
                $arAporteDetalle->setCodigoAdministradoraRiesgosLaborales($row['codigo_administradora_riesgos_laborales']);
                $arAporteDetalle->setClaseRiesgoAfiliado($row['clase_riesgo_afiliado']);
                $arAporteDetalle->setTotalCotizacion($row['total_cotizacion_fondos']);
                $arAporteDetalle->setIndicadorTarifaEspecialPensiones($row['indicador_tarifa_especial_pensiones']);
                $arAporteDetalle->setFechaIngreso($row['fecha_ingreso']);
                $arAporteDetalle->setFechaRetiro($row['fecha_retiro']);
                $arAporteDetalle->setFechaInicioVsp($row['fecha_inicio_vsp']);
                $arAporteDetalle->setFechaInicioSln($row['fecha_inicio_sln']);
                $arAporteDetalle->setFechaFinSln($row['fecha_fin_sln']);
                $arAporteDetalle->setFechaInicioIge($row['fecha_inicio_ige']);
                $arAporteDetalle->setFechaFinIge($row['fecha_fin_ige']);
                $arAporteDetalle->setFechaInicioLma($row['fecha_inicio_lma']);
                $arAporteDetalle->setFechaFinLma($row['fecha_fin_lma']);
                $arAporteDetalle->setFechaInicioVacLr($row['fecha_inicio_vac_lr']);
                $arAporteDetalle->setFechaFinVacLr($row['fecha_fin_vac_lr']);
                $arAporteDetalle->setFechaInicioVct($row['fecha_inicio_vct']);
                $arAporteDetalle->setFechaFinVct($row['fecha_fin_vct']);
                $arAporteDetalle->setFechaInicioIrl($row['fecha_inicio_irl']);
                $arAporteDetalle->setFechaFinIrl($row['fecha_fin_irl']);
                $arAporteDetalle->setFechaInicioIrl($row['fecha_inicio_irl']);
                $arAporteDetalle->setIbcOtrosParafiscalesDiferentesCcf($row['ibc_otros_parafiscales_diferentes_ccf']);
                $arAporteDetalle->setNumeroHorasLaboradas($row['numero_horas_laboradas']);
                $em->persist($arAporteDetalle);
                $metadata = $em->getClassMetaData(get_class($arAporteDetalle));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuPago($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pago_pk FROM rhu_pago ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_pago_pk,
                rhu_pago_tipo.codigo_externo as codigo_pago_tipo_externo,
                codigo_centro_costo_fk,
                codigo_periodo_pago_fk,
                numero,
                codigo_empleado_fk,
                codigo_contrato_fk,
                fecha_desde,
                fecha_hasta,
                fecha_desde_pago,
                fecha_hasta_pago,
                vr_salario_empleado,
                vr_devengado,
                vr_deducciones,
                vr_neto,
                estado_anulado,
                comentarios,
                codigo_usuario,
                codigo_vacacion_fk,
                codigo_liquidacion_fk 
                 FROM rhu_pago  
                 left join rhu_pago_tipo on rhu_pago.codigo_pago_tipo_fk = rhu_pago_tipo.codigo_pago_tipo_pk 
                 ORDER BY codigo_pago_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPago = new RhuPago();
                $arPago->setCodigoPagoPk($row['codigo_pago_pk']);
                $arPago->setPagoTipoRel($em->getReference(RhuPagoTipo::class, $row['codigo_pago_tipo_externo']));
                $arPago->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
                //$arPago->setCodigoPeriodoFk($row['codigo_periodo_pago_fk']);
                $arPago->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                $arPago->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                $arPago->setNumero($row['numero']);
                $arPago->setFechaDesde(date_create($row['fecha_desde']));
                $arPago->setFechaHasta(date_create($row['fecha_hasta']));
                $arPago->setFechaDesdeContrato(date_create($row['fecha_desde_pago']));
                $arPago->setFechaHastaContrato(date_create($row['fecha_hasta_pago']));
                $arPago->setVrSalarioContrato($row['vr_salario_empleado']);
                $arPago->setVrDevengado($row['vr_devengado']);
                $arPago->setVrDeduccion($row['vr_deducciones']);
                $arPago->setVrNeto($row['vr_neto']);
                $arPago->setEstadoAnulado($row['estado_anulado']);
                $arPago->setComentario($row['comentarios']);
                $arPago->setEstadoAutorizado(1);
                $arPago->setEstadoAprobado(1);
                $arPago->setUsuario(utf8_encode($row['codigo_usuario']));
                if ($row['codigo_vacacion_fk']) {
                    $arPago->setVacacionRel($em->getReference(RhuVacacion::class, $row['codigo_vacacion_fk']));
                }
                if ($row['codigo_liquidacion_fk']) {
                    $arPago->setLiquidacionRel($em->getReference(RhuLiquidacion::class, $row['codigo_liquidacion_fk']));
                }
                $em->persist($arPago);
                $metadata = $em->getClassMetaData(get_class($arPago));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuPagoDetalle($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pago_detalle_pk FROM rhu_pago_detalle ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_pago_detalle_pk,
                codigo_pago_fk,
                codigo_pago_concepto_fk,
                codigo_credito_fk,
                vr_pago,
                operacion,
                vr_pago_operado,
                numero_horas,
                vr_hora,
                porcentaje_aplicado,
                numero_dias,
                detalle, 
                vr_ingreso_base_cotizacion,
                vr_ingreso_base_prestacion
                FROM rhu_pago_detalle ORDER BY codigo_pago_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPagoDetalle = new RhuPagoDetalle();
                $arPagoDetalle->setCodigoPagoDetallePk($row['codigo_pago_detalle_pk']);
                $arPagoDetalle->setPagoRel($em->getReference(RhuPago::class, $row['codigo_pago_fk']));
                $arPagoDetalle->setConceptoRel($em->getReference(RhuConcepto::class, $row['codigo_pago_concepto_fk']));
                if ($row['codigo_credito_fk']) {
                    $arPagoDetalle->setCreditoRel($em->getReference(RhuCredito::class, $row['codigo_credito_fk']));
                }
                $arPagoDetalle->setVrPago($row['vr_pago']);
                $arPagoDetalle->setVrPagoOperado($row['vr_pago_operado']);
                $arPagoDetalle->setOperacion($row['operacion']);
                $arPagoDetalle->setHoras($row['numero_horas']);
                $arPagoDetalle->setVrHora($row['vr_hora']);
                $arPagoDetalle->setPorcentaje($row['porcentaje_aplicado']);
                $arPagoDetalle->setDias($row['numero_dias']);
                $arPagoDetalle->setDetalle(utf8_encode($row['detalle']));
                if ($row['operacion'] == 1) {
                    $arPagoDetalle->setVrDevengado($row['vr_pago']);
                }
                if ($row['operacion'] == -1) {
                    $arPagoDetalle->setVrDeduccion($row['vr_pago']);
                }
                $arPagoDetalle->setVrIngresoBasePrestacion($row['vr_ingreso_base_prestacion']);
                $arPagoDetalle->setVrIngresoBaseCotizacion($row['vr_ingreso_base_cotizacion']);
                $em->persist($arPagoDetalle);
                $metadata = $em->getClassMetaData(get_class($arPagoDetalle));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function rhuAspirante($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_aspirante_pk FROM rhu_aspirante ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_aspirante_pk,
                codigo_ciudad_fk,
                codigo_cargo_fk,
                codigo_estado_civil_fk,
                codigo_ciudad_nacimiento_fk,
                codigo_ciudad_expedicion_fk,
                codigo_rh_fk,
                fecha,
                numero_identificacion,
                nombre_corto,
                nombre1,
                nombre2,
                apellido1,
                apellido2,
                telefono,
                celular,
                direccion,
                barrio,
                codigo_sexo_fk,
                correo,
                fecha_nacimiento,
                estado_autorizado,
                estado_aprobado,
                bloqueado,
                libreta_militar,
                peso,
                estatura,
                cargo_aspira,
                codigo_zona_fk,
                comentarios
                 FROM rhu_aspirante ORDER BY codigo_aspirante_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arAspirante = new RhuAspirante();
                $arAspirante->setCodigoAspirantePk($row['codigo_aspirante_pk']);
                $arAspirante->setNumeroIdentificacion($row['numero_identificacion']);
                $arAspirante->setIdentificacionRel($em->getReference(GenIdentificacion::class, 'CC'));
                if ($row['codigo_cargo_fk']) {
                    $arAspirante->setCargoRel($em->getReference(RhuCargo::class, $row['codigo_cargo_fk']));
                }
                if ($row['codigo_ciudad_fk']) {
                    $arAspirante->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                }
                if ($row['codigo_ciudad_nacimiento_fk']) {
                    $arAspirante->setCiudadNacimientoRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_nacimiento_fk']));
                }
                if ($row['codigo_ciudad_expedicion_fk']) {
                    $arAspirante->setCiudadExpedicionRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_expedicion_fk']));
                }
                if ($row['codigo_zona_fk']) {
                    $arAspirante->setZonaRel($em->getReference(RhuZona::class, $row['codigo_zona_fk']));
                }
                if ($row['codigo_estado_civil_fk']) {
                    $arAspirante->setEstadoCivilRel($em->getReference(GenEstadoCivil::class, $row['codigo_estado_civil_fk']));
                }
                $arAspirante->setRhRel($em->getReference(RhuRh::class, $row['codigo_rh_fk']));
                $arAspirante->setSexoRel($em->getReference(GenSexo::class, $row['codigo_sexo_fk']));
                $arAspirante->setFecha(date_create($row['fecha']));
                $arAspirante->setFechaNacimiento(date_create($row['fecha_nacimiento']));
                $arAspirante->setNombreCorto(utf8_encode($row['nombre_corto']));
                $arAspirante->setNombre1(utf8_encode($row['nombre1']));
                $arAspirante->setNombre2(utf8_encode($row['nombre2']));
                $arAspirante->setApellido1(utf8_encode($row['apellido1']));
                $arAspirante->setApellido2(utf8_encode($row['apellido2']));
                $arAspirante->setTelefono(utf8_encode($row['telefono']));
                $arAspirante->setCelular(utf8_encode($row['celular']));
                $arAspirante->setCorreo(utf8_encode($row['correo']));
                $arAspirante->setDireccion(utf8_encode($row['direccion']));
                $arAspirante->setBarrio(utf8_encode($row['barrio']));
                $arAspirante->setLibretaMilitar(utf8_encode($row['libreta_militar']));
                $arAspirante->setPeso(utf8_encode($row['peso']));
                $arAspirante->setEstatura(utf8_encode($row['estatura']));
                $arAspirante->setCargoAspira(utf8_encode($row['cargo_aspira']));
                $arAspirante->setEstadoAutorizado($row['estado_autorizado']);
                $arAspirante->setEstadoAprobado($row['estado_aprobado']);
                $arAspirante->setEstadoBloqueado($row['bloqueado']);
                $em->persist($arAspirante);
                $metadata = $em->getClassMetaData(get_class($arAspirante));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();

        }
    }

    private function rhuSolicitud($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_requisito_pk FROM rhu_seleccion_requisito ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                rhu_seleccion_requisito.codigo_seleccion_requisito_pk,
                rhu_seleccion_requisito.codigo_centro_costo_fk,
                rhu_seleccion_requisito.codigo_cargo_fk,
                rhu_clasificacion_riesgo.codigo_externo as codigo_clasificacion_riesgo_externo,
                rhu_seleccion_requisito.fecha,
                rhu_seleccion_requisito.nombre as solicitud_nombre,
                rhu_seleccion_requisito.cantidad_solicitada,
                rhu_seleccion_requisito.fecha_pruebas,
                rhu_seleccion_requisito.fecha_vencimiento,
                rhu_seleccion_requisito.codigo_estado_civil_fk,
                rhu_seleccion_requisito.codigo_ciudad_fk,
                rhu_seleccion_requisito.codigo_estudio_tipo_fk,
                rhu_seleccion_requisito.codigo_sexo_fk,
                rhu_seleccion_requisito.codigo_religion_fk,
                rhu_seleccion_requisito.codigo_disponibilidad_fk,
                rhu_seleccion_requisito.codigo_tipo_vehiculo_fk,
                rhu_seleccion_requisito.codigo_licencia_carro_fk,
                rhu_seleccion_requisito.codigo_licencia_moto_fk,
                rhu_seleccion_requisito.edad_minima,
                rhu_seleccion_requisito.edad_maxima,
                rhu_seleccion_requisito.codigo_estudio_tipo_fk,
                rhu_seleccion_requisito.codigo_experiencia_requisicion_fk,
                rhu_seleccion_requisito.codigo_seleccion_requisito_motivo_fk,
                rhu_seleccion_requisito.cliente_referencia,
                rhu_seleccion_requisito.vr_salario,
                rhu_seleccion_requisito.salario_fijo,
                rhu_seleccion_requisito.salario_variable,
                rhu_seleccion_requisito.vr_no_salarial,
                rhu_seleccion_requisito.fecha_contratacion,
                rhu_seleccion_requisito.estado_autorizado,
                rhu_seleccion_requisito.estado_aprobado,
                rhu_seleccion_requisito.estado_cerrado,
                rhu_seleccion_requisito. soporte,
                rhu_seleccion_requisito.comentarios
                FROM rhu_seleccion_requisito
                left join rhu_clasificacion_riesgo on rhu_clasificacion_riesgo.codigo_clasificacion_riesgo_pk=rhu_seleccion_requisito.codigo_clasificacion_riesgo_fk
                ORDER BY codigo_seleccion_requisito_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSolicitud = new RhuSolicitud();
                $arSolicitud->setCodigoSolicitudPk($row['codigo_seleccion_requisito_pk']);
                $arSolicitud->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
                $arSolicitud->setCargoRel($em->getReference(RhuCargo::class, $row['codigo_cargo_fk']));
                $arSolicitud->setEstudioTipoRel($em->getReference(RhuEstudioTipo::class, $row['codigo_estudio_tipo_fk']));
                $arSolicitud->setSolicitudExperienciaRel($em->getReference(RhuSolicitudExperiencia::class, $row['codigo_experiencia_requisicion_fk']));
                $arSolicitud->setFecha(date_create($row['fecha']));
                $arSolicitud->setFechaContratacion(date_create($row['fecha_contratacion']));
                $arSolicitud->setFechaVencimiento(date_create($row['fecha_vencimiento']));
                $arSolicitud->setNombre(utf8_encode($row['solicitud_nombre']));
                $arSolicitud->setCantidadSolicitada(utf8_encode($row['cantidad_solicitada']));
                $arSolicitud->setVrSalario(utf8_encode($row['vr_salario']));
                $arSolicitud->setVrNoSalarial(($row['vr_no_salarial']));
                $arSolicitud->setSalarioFijo(utf8_encode($row['salario_fijo']));
                $arSolicitud->setSalarioVariable(utf8_encode($row['salario_variable']));
                $arSolicitud->setFechaPruebas(date_create($row['fecha_pruebas']));
                $arSolicitud->setEdadMinima(utf8_encode($row['edad_minima']));
                $arSolicitud->setEdadMaxima(utf8_encode($row['edad_maxima']));
                $arSolicitud->setCodigoTipoVehiculoFk($row['codigo_tipo_vehiculo_fk']);
                $arSolicitud->setCodigoLicenciaMotoFk(utf8_encode($row['codigo_licencia_moto_fk']));
                $arSolicitud->setCodigoLicenciaCarroFk(utf8_encode($row['codigo_licencia_carro_fk']));
                $arSolicitud->setEdadMaxima(utf8_encode($row['edad_maxima']));
                $arSolicitud->setEstadoAutorizado($row['estado_aprobado']);
                $arSolicitud->setEstadoAprobado($row['estado_cerrado']);
                $arSolicitud->setComentarios(utf8_encode($row['comentarios']));
                if ($row['codigo_clasificacion_riesgo_externo']) {
                    $arSolicitud->setClasificacionRiesgoRel($em->getReference(RhuClasificacionRiesgo::class, $row['codigo_clasificacion_riesgo_externo']));
                }
                if ($row['codigo_seleccion_requisito_motivo_fk']) {
                    $arSolicitud->setSolicitudMotivoRel($em->getReference(RhuSolicitudMotivo::class, $row['codigo_seleccion_requisito_motivo_fk']));
                }
                if ($row['codigo_sexo_fk']) {
                    $codigoClase = null;
                    switch ($row['codigo_sexo_fk']) {
                        case 'M':
                            $codigoClase = "M";
                            break;
                        case 'F':
                            $codigoClase = "F";
                            break;
                        case 'I':
                            $codigoClase = "I";
                            break;
                    }
                    $arSolicitud->setSexoRel($em->getReference(GenSexo::class, $codigoClase));
                }
                if ($row['codigo_ciudad_fk']) {
                    $arSolicitud->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                }
                if ($row['codigo_estado_civil_fk']) {
                    $arSolicitud->setEstadoCivilRel($em->getReference(GenEstadoCivil::class, $row['codigo_estado_civil_fk']));
                }
                $em->persist($arSolicitud);
                $metadata = $em->getClassMetaData(get_class($arSolicitud));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuSolicitudAspirante($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_requisicion_aspirante_pk FROM rhu_seleccion_requisicion_aspirante ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_requisicion_aspirante_pk,
                codigo_seleccion_requisito_fk,
                codigo_aspirante_fk,
                estado_aprobado,
                codigo_motivo_descarte_requisicion_aspirante_fk,
                fechaDescarte,
                comentarios
                 FROM rhu_seleccion_requisicion_aspirante ORDER BY codigo_seleccion_requisicion_aspirante_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSolicitudAspirante = new RhuSolicitudAspirante();
                $arSolicitudAspirante->setCodigoSolicitudAspirantePk($row['codigo_seleccion_requisicion_aspirante_pk']);
                $arSolicitudAspirante->setSolicitudRel($em->getReference(RhuSolicitud::class, $row['codigo_seleccion_requisito_fk']));
                $arSolicitudAspirante->setAspiranteRel($em->getReference(RhuAspirante::class, $row['codigo_aspirante_fk']));
                $arSolicitudAspirante->setCodigoMotivoDescarteSolicitudAspiranteFk($row['codigo_motivo_descarte_requisicion_aspirante_fk']);
                $arSolicitudAspirante->setFechaDescarte(date_create($row['fechaDescarte']));
                $arSolicitudAspirante->setEstadoGenerado($row['estado_aprobado']);
                $arSolicitudAspirante->setComentarios(utf8_encode($row['comentarios']));
                $em->persist($arSolicitudAspirante);
                $metadata = $em->getClassMetaData(get_class($arSolicitudAspirante));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();

        }
    }

    private function rhuSeleccion($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_pk FROM rhu_seleccion ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_pk,
                rhu_seleccion_tipo.codigo_externo as codigo_seleccion_tipo_externo,
                codigo_tipo_identificacion_fk,
                codigo_estado_civil_fk,
                codigo_centro_costo_fk,
                codigo_ciudad_fk,
                codigo_ciudad_nacimiento_fk,
                codigo_ciudad_expedicion_fk,
                codigo_rh_fk,
                codigo_seleccion_requisito_fk,
                codigo_cargo_fk,
                fecha,
                fecha_expedicion,
                fecha_pruebas,
                numero_identificacion,
                nombre_corto,
                nombre1,
                nombre2,
                apellido1,
                apellido2,
                telefono,
                celular,
                direccion,
                barrio,
                codigo_sexo_fk,
                correo,
                fecha_nacimiento,
                comentarios,
                estado_autorizado,
                estado_aprobado,
                presenta_pruebas,
                referencias_verificadas,
                fecha_entrevista,
                fecha_pruebas,
                vr_servicio,
                codigo_zona_fk,
                codigo_motivo_cierre_seleccion_fk,
                fechaCierre
                 FROM rhu_seleccion
                 left join rhu_seleccion_tipo on rhu_seleccion.codigo_seleccion_tipo_fk = rhu_seleccion_tipo.codigo_seleccion_tipo_pk 
                ORDER BY codigo_seleccion_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSeleccion = new RhuSeleccion();
                $arSeleccion->setCodigoSeleccionPk($row['codigo_seleccion_pk']);
                $arSeleccion->setCodigoGrupoPagoFk($row['codigo_centro_costo_fk']);
                $arSeleccion->setIdentificacionRel($em->getReference(GenIdentificacion::class, 'CC'));
                $arSeleccion->setEstadoCivilRel($em->getReference(GenEstadoCivil::class, $row['codigo_estado_civil_fk']));
                $arSeleccion->setRhRel($em->getReference(RhuRh::class, $row['codigo_rh_fk']));
                $arSeleccion->setCargoRel($em->getReference(RhuCargo::class, $row['codigo_cargo_fk']));
                $arSeleccion->setFecha(date_create($row['fecha']));
                $arSeleccion->setFechaExpedicion(date_create($row['fecha_expedicion']));
                $arSeleccion->setNumeroIdentificacion($row['numero_identificacion']);
                $arSeleccion->setNombreCorto(utf8_encode($row['nombre_corto']));
                $arSeleccion->setNombre1(utf8_encode($row['nombre1']));
                $arSeleccion->setNombre2(utf8_encode($row['nombre2']));
                $arSeleccion->setApellido1(utf8_encode($row['apellido1']));
                $arSeleccion->setApellido2(utf8_encode($row['apellido2']));
                $arSeleccion->setTelefono($row['telefono']);
                $arSeleccion->setCelular($row['celular']);
                $arSeleccion->setDireccion(utf8_encode($row['direccion']));
                $arSeleccion->setBarrio(utf8_encode($row['barrio']));
                $arSeleccion->setCorreo(utf8_encode($row['correo']));
                $arSeleccion->setFechaNacimiento(date_create($row['fecha_nacimiento']));
                $arSeleccion->setEstadoAutorizado(1);
                $arSeleccion->setEstadoAprobado($row['estado_aprobado']);
                $arSeleccion->setPresentaPruebas($row['presenta_pruebas']);
                $arSeleccion->setReferenciasVerificadas($row['referencias_verificadas']);
                $arSeleccion->setFechaEntrevista(date_create($row['fecha_entrevista']));
                $arSeleccion->setFechaPrueba(date_create($row['fecha_pruebas']));
                $arSeleccion->setComentarios(utf8_encode($row['comentarios']));
                if ($row['codigo_seleccion_tipo_externo']) {
                    $arSeleccion->setSeleccionTipoRel($em->getReference(RhuSeleccionTipo::class, $row['codigo_seleccion_tipo_externo']));
                }
                if ($row['codigo_ciudad_fk']) {
                    $arSeleccion->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                }
                if ($row['codigo_seleccion_requisito_fk']) {
                    $arSeleccion->setSolicitudRel($em->getReference(RhuSolicitud::class, $row['codigo_seleccion_requisito_fk']));
                }
                if ($row['codigo_sexo_fk']) {
                    $arSeleccion->setSexoRel($em->getReference(GenSexo::class, $row['codigo_sexo_fk']));
                }
                if ($row['codigo_ciudad_nacimiento_fk']) {
                    $arSeleccion->setCiudadNacimientoRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_nacimiento_fk']));
                }
                if ($row['codigo_ciudad_expedicion_fk']) {
                    $arSeleccion->setCiudadExpedicionRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_expedicion_fk']));
                }
                if ($row['codigo_zona_fk']) {
                    $arSeleccion->setZonaRel($em->getReference(RhuZona::class, $row['codigo_zona_fk']));
                }
                $em->persist($arSeleccion);
                $metadata = $em->getClassMetaData(get_class($arSeleccion));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuSeleccionEntrevista($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_entrevista_pk FROM rhu_seleccion_entrevista ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_entrevista_pk,
                codigo_seleccion_fk,
                codigo_seleccion_entrevista_tipo_fk,
                fecha,
                resultado,
                resultado_cuantitativo,
                nombre_quien_entrevista,
                comentarios
                 FROM rhu_seleccion_entrevista
                ORDER BY codigo_seleccion_entrevista_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSeleccionEntrevista = new RhuSeleccionEntrevista();
                $arSeleccionEntrevista->setCodigoSeleccionEntrevistaPk($row['codigo_seleccion_entrevista_pk']);
                $arSeleccionEntrevista->setFecha(date_create($row['fecha']));
                $arSeleccionEntrevista->setSeleccionEntrevistaTipoRel($em->getReference(RhuSeleccionEntrevistaTipo::class, $row['codigo_seleccion_entrevista_tipo_fk']));
                $arSeleccionEntrevista->setSeleccionRel($em->getReference(RhuSeleccion::class, $row['codigo_seleccion_fk']));
                $arSeleccionEntrevista->setResultado(utf8_encode($row['resultado']));
                $arSeleccionEntrevista->setResultadoCuantitativo($row['resultado_cuantitativo']);
                $arSeleccionEntrevista->setNombreQuienEntrevista(utf8_encode($row['nombre_quien_entrevista']));
                $arSeleccionEntrevista->setComentarios(utf8_encode($row['comentarios']));
                $arSeleccionEntrevista->setCodigoUsuario(utf8_encode($row['codigo_usuario']));
                $em->persist($arSeleccionEntrevista);
                $metadata = $em->getClassMetaData(get_class($arSeleccionEntrevista));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuSeleccionPrueba($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_prueba_pk FROM rhu_seleccion_prueba ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_prueba_pk,
                codigo_seleccion_fk,
                codigo_seleccion_prueba_tipo_fk,
                fecha,
                resultado,
                resultado_cuantitativo,
                nombre_quien_hace_prueba,
                comentarios
                 FROM rhu_seleccion_prueba
                ORDER BY codigo_seleccion_prueba_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSeleccionPrueba = new RhuSeleccionPrueba();
                $arSeleccionPrueba->setCodigoSeleccionPruebaPk($row['codigo_seleccion_prueba_pk']);
                $arSeleccionPrueba->setFecha(date_create($row['fecha']));
                $arSeleccionPrueba->setSeleccionPruebaTipoRel($em->getReference(RhuSeleccionPruebaTipo::class, $row['codigo_seleccion_prueba_tipo_fk']));
                $arSeleccionPrueba->setSeleccionRel($em->getReference(RhuSeleccion::class, $row['codigo_seleccion_fk']));
                $arSeleccionPrueba->setResultado(utf8_encode($row['resultado']));
                $arSeleccionPrueba->setResultadoCuantitativo($row['resultado_cuantitativo']);
                $arSeleccionPrueba->setNombreQuienHacePrueba(utf8_encode($row['nombre_quien_hace_prueba']));
                $arSeleccionPrueba->setComentarios(utf8_encode($row['comentarios']));
                $em->persist($arSeleccionPrueba);
                $metadata = $em->getClassMetaData(get_class($arSeleccionPrueba));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuSeleccionReferencia($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_referencia_pk FROM rhu_seleccion_referencia ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_referencia_pk,
                codigo_seleccion_fk,
                codigo_seleccion_tipo_referencia_fk,
                codigo_ciudad_fk,
                nombre_corto,
                telefono,
                celular,
                direccion,
                estado_verificada,
                empresa,
                suministra_informacion,
                cargo,
                motivo_retiro,
                tiempo_laborado,    
                comentarios
                 FROM rhu_seleccion_referencia
                ORDER BY codigo_seleccion_referencia_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSeleccionReferencia = new RhuSeleccionReferencia();
                $arSeleccionReferencia->setCodigoSeleccionReferenciaPk($row['codigo_seleccion_referencia_pk']);
                $arSeleccionReferencia->setSeleccionReferenciaTipoRel($em->getReference(RhuSeleccionReferenciaTipo::class, $row['codigo_seleccion_tipo_referencia_fk']));
                if ($row['codigo_ciudad_fk']) {
                    $arSeleccionReferencia->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                }
                $arSeleccionReferencia->setSeleccionRel($em->getReference(RhuSeleccion::class, $row['codigo_seleccion_fk']));
                $arSeleccionReferencia->setNombreCorto(utf8_encode($row['nombre_corto']));
                $arSeleccionReferencia->setTelefono(utf8_encode($row['telefono']));
                $arSeleccionReferencia->setCelular(utf8_encode($row['celular']));
                $arSeleccionReferencia->setDireccion(utf8_encode($row['direccion']));
                $arSeleccionReferencia->setEstadoVerificada(utf8_encode($row['estado_verificada']));
                $arSeleccionReferencia->setEmpresa(utf8_encode($row['empresa']));
                $arSeleccionReferencia->setSuministraInformacion(utf8_encode($row['suministra_informacion']));
                $arSeleccionReferencia->setCargo(utf8_encode($row['cargo']));
                $arSeleccionReferencia->setMotivoRetiro(utf8_encode($row['motivo_retiro']));
                $arSeleccionReferencia->setTiempoLaborado(utf8_encode($row['tiempo_laborado']));
                $arSeleccionReferencia->setComentarios(utf8_encode($row['comentarios']));
                $em->persist($arSeleccionReferencia);
                $metadata = $em->getClassMetaData(get_class($arSeleccionReferencia));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuSeleccionVisita($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_visita_pk FROM rhu_seleccion_visita ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_visita_pk,
                codigo_seleccion_fk,
                fecha,
                nombre_quien_visita,
                cliente_referencia,
                comentarios
                 FROM rhu_seleccion_visita
                ORDER BY codigo_seleccion_visita_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSeleccionVisita = new RhuSeleccionVisita();
                $arSeleccionVisita->setCodigoSeleccionVisitaPk($row['codigo_seleccion_visita_pk']);
                $arSeleccionVisita->setFecha(date_create($row['fecha']));
                $arSeleccionVisita->setSeleccionRel($em->getReference(RhuSeleccion::class, $row['codigo_seleccion_fk']));
                $arSeleccionVisita->setNombreQuienVisita(utf8_encode($row['nombre_quien_visita']));
                $arSeleccionVisita->setClienteReferencia(utf8_encode($row['cliente_referencia']));
                $arSeleccionVisita->setComentarios(utf8_encode($row['comentarios']));
                $em->persist($arSeleccionVisita);
                $metadata = $em->getClassMetaData(get_class($arSeleccionVisita));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuSeleccionAntecedente($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_seleccion_antecedente_pk FROM rhu_seleccion_antecedente ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_seleccion_antecedente_pk,
                codigo_seleccion_fk,
                fecha,
                nombre_quien_suministra,
                comentarios,
                verificado,
                codigo_usuario
                 FROM rhu_seleccion_antecedente
                ORDER BY codigo_seleccion_antecedente_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arSeleccionAntecedente = new RhuSeleccionAntecedente();
                $arSeleccionAntecedente->setCodigoSeleccionAntecedentePk($row['codigo_seleccion_antecedente_pk']);
                $arSeleccionAntecedente->setFecha(date_create($row['fecha']));
                $arSeleccionAntecedente->setEstadoVerificado($row['verificado']);
                $arSeleccionAntecedente->setSeleccionRel($em->getReference(RhuSeleccion::class, $row['codigo_seleccion_fk']));
                $arSeleccionAntecedente->setNombreQuienSuministra(utf8_encode($row['nombre_quien_suministra']));
                $arSeleccionAntecedente->setComentarios(utf8_encode($row['comentarios']));
                $em->persist($arSeleccionAntecedente);
                $metadata = $em->getClassMetaData(get_class($arSeleccionAntecedente));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function rhuIncapacidadDiagnostico($conn)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_incapacidad_diagnostico_pk FROM rhu_incapacidad_diagnostico ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_incapacidad_diagnostico_pk, 
                    nombre,
                    codigo,
                    codigo_grupo_enfermedad_fk                                                                          
                 FROM rhu_incapacidad_diagnostico ORDER BY codigo_incapacidad_diagnostico_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arIncapacidadDiagnostico = new RhuIncapacidadDiagnostico();
                $arIncapacidadDiagnostico->setCodigoIncapacidadDiagnosticoPk($row['codigo_incapacidad_diagnostico_pk']);
                $arIncapacidadDiagnostico->setNombre(utf8_encode($row['nombre']));
                $arIncapacidadDiagnostico->setCodigo(utf8_encode($row['codigo']));
                $arIncapacidadDiagnostico->setCodigoGrupoEnfermedadFk(utf8_encode($row['codigo_grupo_enfermedad_fk']));
                $em->persist($arIncapacidadDiagnostico);
                $metadata = $em->getClassMetaData(get_class($arIncapacidadDiagnostico));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
        }


    }

    private function rhuIncapacidad($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_incapacidad_pk FROM rhu_incapacidad ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                        codigo_incapacidad_pk,
                        numero,
                        fecha,
                        fecha_desde,
                        fecha_hasta,
                        codigo_centro_costo_fk,
                        codigo_incapacidad_tipo_fk,
                        codigo_incapacidad_diagnostico_fk,
                        cantidad,
                        dias_cobro,
                        estado_transcripcion,
                        vr_incapacidad,
                        vr_pagado,
                        vr_saldo,
                        porcentaje_pago,
                        codigo_usuario,
                        estado_legalizado,
                        vr_cobro,
                        vr_ibc_propuesto,
                        dias_entidad,
                        dias_empresa,
                        dias_acumulados,
                        pagar_empleado,
                        vr_ibc_mes_anterior,
                        dias_ibc_mes_anterior,
                        vr_hora,
                        codigo_incapacidad_prorroga_fk,
                        fecha_desde_empresa,
                        fecha_hasta_empresa,
                        fecha_desde_entidad,
                        fecha_hasta_entidad,
                        vr_propuesto,
                        vr_hora_empresa,
                        fecha_documento_fisico,
                        aplicar_adicional,
                        fecha_aplicacion,
                        vr_abono,
                        medico,
                        vr_salario,
                        codigo_empleado_fk,
                        codigo_contrato_fk,
                        numero_eps,
                        comentarios,
                        estado_cobrar,
                        estado_prorroga,
                        codigo_cliente_fk,
                        codigo_cobro_fk,
                        estado_cobrado,
                        estado_cobrar_cliente,
                        rhu_entidad_salud.codigo_interface as codigo_entidad_salud_externo
                 FROM rhu_incapacidad
                 left join rhu_entidad_salud on rhu_entidad_salud.codigo_entidad_salud_pk=rhu_incapacidad.codigo_entidad_salud_fk
                 ORDER BY codigo_incapacidad_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arIncapacidad = new RhuIncapacidad();
                $arIncapacidad->setCodigoIncapacidadPk($row['codigo_incapacidad_pk']);
                $arIncapacidad->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                if ($row['codigo_contrato_fk']) {
                    $arIncapacidad->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                }
                $arIncapacidad->getNumero($row['numero']);
                $arIncapacidad->setNumeroEps($row['numero_eps']);
                $arIncapacidad->setFecha(date_create($row['fecha']));
                $arIncapacidad->setFechaDesde(date_create($row['fecha_desde']));
                $arIncapacidad->setFechaHasta(date_create($row['fecha_hasta']));

                if ($row['codigo_centro_costo_fk']) {
                    $arIncapacidad->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
                }
                if ($row['codigo_incapacidad_tipo_fk']) {
                    $arIncapacidad->setIncapacidadTipoRel($em->getReference(RhuIncapacidadTipo::class, $row['codigo_incapacidad_tipo_fk']));
                }
                $arIncapacidad->setIncapacidadDiagnosticoRel($em->getReference(RhuIncapacidadDiagnostico::class, $row['codigo_incapacidad_diagnostico_fk']));
                $arIncapacidad->setCantidad($row['cantidad']);
                $arIncapacidad->setDiasCobro($row['dias_cobro']);
                $arIncapacidad->setEstadoTranscripcion($row['estado_transcripcion']);
                $arIncapacidad->setEstadoLegalizado($row['estado_legalizado']);
                $arIncapacidad->setVrIncapacidad($row['vr_incapacidad']);
                $arIncapacidad->setVrPagado($row['vr_pagado']);
                $arIncapacidad->setVrSaldo($row['vr_saldo']);
                $arIncapacidad->setPorcentajePago($row['porcentaje_pago']);
                $arIncapacidad->setCodigoUsuario($row['codigo_usuario']);
                $arIncapacidad->setVrCobro($row['vr_cobro']);
                $arIncapacidad->setVrIbcPropuesto($row['vr_ibc_propuesto']);
                $arIncapacidad->setDiasEntidad($row['dias_entidad']);
                $arIncapacidad->setDiasEmpresa($row['dias_empresa']);
                $arIncapacidad->setDiasAcumulados($row['dias_acumulados']);
                $arIncapacidad->setPagarEmpleado($row['pagar_empleado']);
                $arIncapacidad->setVrIbcMesAnterior($row['vr_ibc_mes_anterior']);
                $arIncapacidad->setDiasIbcMesAnterior($row['dias_ibc_mes_anterior']);
                $arIncapacidad->setVrHora($row['vr_hora']);
                /*if($row['codigo_incapacidad_prorroga_fk']) {
                    $arIncapacidad->setIncapacidadProrrogaRel($em->getReference(RhuIncapacidad::class, $row['codigo_incapacidad_prorroga_fk']));
                }*/
                if ($row['fecha_desde_empresa'] == null) {
                    $arIncapacidad->setFechaDesdeEmpresa(null);
                } else {
                    $arIncapacidad->setFechaDesdeEmpresa(date_create($row['fecha_desde_empresa']));
                }
                if ($row['fecha_hasta_empresa'] == null) {
                    $arIncapacidad->setFechaHastaEmpresa(null);
                } else {
                    $arIncapacidad->setFechaHastaEmpresa(date_create($row['fecha_hasta_empresa']));
                }
                if ($row['fecha_desde_entidad'] == null) {
                    $arIncapacidad->setFechaDesdeEntidad(null);
                } else {
                    $arIncapacidad->setFechaDesdeEntidad(date_create($row['fecha_desde_entidad']));
                }
                if ($row['fecha_hasta_entidad'] == null) {
                    $arIncapacidad->setFechaHastaEntidad(null);
                } else {
                    $arIncapacidad->setFechaHastaEntidad(date_create($row['fecha_hasta_entidad']));
                }


                $arIncapacidad->setVrPropuesto($row['vr_propuesto']);
                $arIncapacidad->setVrHoraEmpresa($row['vr_hora']);
                $arIncapacidad->setFechaDocumentoFisico(date_create($row['fecha_documento_fisico']));
                $arIncapacidad->setAplicarAdicional($row['aplicar_adicional']);
                $arIncapacidad->setFechaAplicacion(date_create($row['fecha_aplicacion']));
                $arIncapacidad->setVrAbono($row['vr_abono']);
                $arIncapacidad->setMedico(utf8_encode($row['medico']));
                $arIncapacidad->setVrSalario($row['vr_salario']);
                $arIncapacidad->setComentarios(utf8_encode($row['comentarios']));
                $arIncapacidad->setEstadoCobrar($row['estado_cobrar']);
                $arIncapacidad->setEstadoProrroga($row['estado_prorroga']);
                $arEntidadSalud = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_salud_externo']]);
                $arIncapacidad->setEntidadSaludRel($arEntidadSalud);

                $em->persist($arIncapacidad);
                $metadata = $em->getClassMetaData(get_class($arIncapacidad));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
        }

        $arr = $conn->query("SELECT codigo_incapacidad_pk, codigo_incapacidad_prorroga_fk FROM rhu_incapacidad where codigo_incapacidad_prorroga_fk is not null");
        foreach ($arr as $row) {
            $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($row['codigo_incapacidad_pk']);
            $arIncapacidad->setIncapacidadProrrogaRel($em->getReference(RhuIncapacidad::class, $row['codigo_incapacidad_prorroga_fk']));
            $em->persist($arIncapacidad);
        }
        $em->flush();
    }

    private function rhuLicenciaTipo($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                    codigo_licencia_tipo_pk,  
                    nombre, 
                    codigo_pago_concepto_fk,
                    afecta_salud, 
                    ausentismo, 
                    maternidad, 
                    paternidad, 
                    suspension_contrato_trabajo, 
                    tipo_novedad_turno, 
                    remunerada
                 FROM rhu_licencia_tipo");
        foreach ($datos as $row) {
            $arLicenciaTipo = new RhuLicenciaTipo();
            $arLicenciaTipo->setCodigoLicenciaTipoPk($row['codigo_licencia_tipo_pk']);
            $arLicenciaTipo->setConceptoRel($em->getReference(RhuConcepto::class, $row['codigo_pago_concepto_fk']));
            $arLicenciaTipo->setNombre(utf8_encode($row['nombre']));
            $arLicenciaTipo->setAfectaSalud($row['afecta_salud']);
            $arLicenciaTipo->setAusentismo($row['ausentismo']);
            $arLicenciaTipo->setMaternidad($row['maternidad']);
            $arLicenciaTipo->setPaternidad($row['paternidad']);
            $arLicenciaTipo->setSuspensionContratoTrabajo($row['suspension_contrato_trabajo']);
            $arLicenciaTipo->setTipoNovedadTurno($row['tipo_novedad_turno']);
            $arLicenciaTipo->setRemunerada($row['remunerada']);
            $em->persist($arLicenciaTipo);
            $metadata = $em->getClassMetaData(get_class($arLicenciaTipo));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function rhuLicencia($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_licencia_pk FROM rhu_licencia ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_licencia_pk,
                    codigo_licencia_tipo_fk,
                    codigo_centro_costo_fk,
                    codigo_empleado_fk,
                    codigo_contrato_fk,
                    fecha,
                    fecha_desde,
                    fecha_hasta,
                    cantidad,
                    comentarios,
                    afecta_transporte,
                    codigo_usuario,
                    maternidad,
                    codigo_entidad_salud_fk,
                    paternidad,
                    estado_cobrar,
                    estado_cobrar_cliente,
                    dias_cobro,
                    estado_prorroga,
                    estado_transcripcion,
                    estado_legalizado,
                    porcentaje_pago,
                    vr_cobro,
                    vr_licencia,
                    vr_saldo,
                    vr_pagado,
                    pagar_empleado,
                    vr_ibc_mes_anterior,
                    dias_ibc_mes_anterior,
                    vr_hora,
                    codigo_novedad_programacion,
                    aplicar_adicional,
                    fecha_aplicacion,
                    vr_abono, 
                    vr_ibc_propuesto, 
                    vr_propuesto,
                    rhu_entidad_salud.codigo_interface as codigo_entidad_salud_externo
                 FROM rhu_licencia 
                 left join rhu_entidad_salud on rhu_entidad_salud.codigo_entidad_salud_pk=rhu_licencia.codigo_entidad_salud_fk 
                 ORDER BY codigo_licencia_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arLicencia = new RhuLicencia();
                $arLicencia->setCodigoLicenciaPk($row['codigo_licencia_pk']);
                $arLicencia->setLicenciaTipoRel($em->getReference(RhuLicenciaTipo::class, $row['codigo_licencia_tipo_fk']));
                if ($row['codigo_centro_costo_fk']) {
                    $arLicencia->setGrupoRel($em->getReference(RhuGrupo::class, $row['codigo_centro_costo_fk']));
                }
                $arLicencia->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
                $arLicencia->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                $arLicencia->setFecha(date_create($row['fecha']));
                $arLicencia->setFechaDesde(date_create($row['fecha_desde']));
                $arLicencia->setFechaHasta(date_create($row['fecha_hasta']));
                $arLicencia->setCantidad($row['cantidad']);
                $arLicencia->setComentarios(utf8_encode($row['comentarios']));
                $arLicencia->setAfectaTransporte($row['afecta_transporte']);
                $arLicencia->setCodigoUsuario(utf8_encode($row['codigo_usuario']));
                $arLicencia->setMaternidad($row['maternidad']);
                $arEntidadSalud = $em->getRepository(RhuEntidad::class)->findOneBy(['codigoInterface' => $row['codigo_entidad_salud_externo']]);
                $arLicencia->setEntidadSaludRel($arEntidadSalud);
                $arLicencia->setPaternidad($row['paternidad']);
                $arLicencia->setEstadoCobrar($row['estado_cobrar']);
                $arLicencia->setEstadoCobrarCliente($row['estado_cobrar_cliente']);
                $arLicencia->setDiasCobro($row['dias_cobro']);
                $arLicencia->setEstadoProrroga($row['estado_prorroga']);
                $arLicencia->setEstadoTranscripcion($row['estado_transcripcion']);
                $arLicencia->setEstadoLegalizado($row['estado_legalizado']);
                $arLicencia->setPorcentajePago($row['porcentaje_pago']);
                $arLicencia->setVrCobro($row['vr_cobro']);
                $arLicencia->setVrLicencia($row['vr_licencia']);
                $arLicencia->setVrSaldo($row['vr_saldo']);
                $arLicencia->setVrPagado($row['vr_pagado']);
                $arLicencia->setPagarEmpleado($row['pagar_empleado']);
                $arLicencia->setVrIbcMesAnterior($row['vr_ibc_mes_anterior']);
                $arLicencia->setDiasIbcMesAnterior($row['dias_ibc_mes_anterior']);
                $arLicencia->setVrHora($row['vr_hora']);
                $arLicencia->setCodigoNovedadProgramacion($row['codigo_novedad_programacion']);
                $arLicencia->setAplicarAdicional($row['aplicar_adicional']);
                $arLicencia->setFechaAplicacion(date_create($row['fecha_aplicacion']));
                $arLicencia->setVrAbono($row['vr_abono']);
                $arLicencia->setVrIbcPropuesto($row['vr_ibc_propuesto']);
                $arLicencia->setVrPropuesto($row['vr_propuesto']);
                $em->persist($arLicencia);
                $metadata = $em->getClassMetaData(get_class($arLicencia));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function rhuDisciplinario($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_disciplinario_pk,
                codigo_disciplinario_tipo_fk,
                codigo_disciplinario_motivo_fk,
                codigo_disciplinario_falta_fk,
                codigo_empleado_fk,
                fecha,
                fecha_notificacion,
                codigo_contrato_fk,
                asunto,
                fecha_incidente,
                fecha_desde_sancion,
                fecha_hasta_sancion,
                fecha_ingreso_trabajo,
                dias_suspencion,
                reentrenamiento,
                estado_procede,
                estado_suspension,
                estado_autorizado,
                estado_cerrado,
                comentarios
                 FROM rhu_disciplinario");
        foreach ($datos as $row) {
            $arDisciplinario = new RhuDisciplinario();
            $arDisciplinario->setCodigoDisciplinarioPk($row['codigo_disciplinario_pk']);
            $arDisciplinario->setDisciplinarioTipoRel($em->getReference(RhuDisciplinarioTipo::class, $row['codigo_disciplinario_tipo_fk']));
            $arDisciplinario->setDisciplinarioMotivoRel($em->getReference(RhuDisciplinarioMotivo::class, $row['codigo_disciplinario_motivo_fk']));
            $arDisciplinario->setDisciplinariosFaltaRel($em->getReference(RhuDisciplinarioFalta::class, $row['codigo_disciplinario_falta_fk']));
            $arDisciplinario->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $arDisciplinario->setFecha(date_create($row['fecha']));
            $arDisciplinario->setFechaNotificacion(date_create($row['fecha_notificacion']));
            $arDisciplinario->setCodigoContratoFk($row['codigo_contrato_fk']);
            $arDisciplinario->setAsunto(utf8_encode($row['asunto']));
            $arDisciplinario->setFechaIncidente(date_create($row['fecha_incidente']));
            $arDisciplinario->setFechaDesdeSancion(date_create($row['fecha_desde_sancion']));
            $arDisciplinario->setFechaHastaSancion(date_create($row['fecha_hasta_sancion']));
            $arDisciplinario->setFechaIngresoTrabajo(date_create($row['fecha_ingreso_trabajo']));
            $arDisciplinario->setDiasSuspencion($row['dias_suspencion']);
            $arDisciplinario->setReentrenamiento($row['reentrenamiento']);
            $arDisciplinario->setEstadoProcede($row['estado_procede']);
            $arDisciplinario->setEstadoSuspendido($row['estado_suspension']);
            $arDisciplinario->setEstadoAutorizado($row['estado_autorizado']);
            $arDisciplinario->setEstadoAprobado($row['estado_cerrado']);
            $arDisciplinario->setComentarios(utf8_encode($row['comentarios']));
            $em->persist($arDisciplinario);
            $metadata = $em->getClassMetaData(get_class($arDisciplinario));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();

    }

    private function rhuAcreditacion($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_acreditacion_pk,
                codigo_acreditacion_tipo_fk,
                codigo_academia_fk,
                codigo_empleado_fk,
                codigo_acreditacion_rechazo_fk,
                fecha,
                fecha_acreditacion,
                fecha_validacion,
                fecha_vence_curso,
                fecha_vencimiento,
                numero_acreditacion,
                numero_validacion,
                numero_registro,
                comentarios,
                detalle_validacion,
                estado_acreditado,
                estado_rechazado
                FROM rhu_acreditacion");
        foreach ($datos as $row) {
            $arAcreditacion = new RhuAcreditacion();
            $arAcreditacion->setCodigoAcreditacionPk($row['codigo_acreditacion_pk']);
            $arAcreditacion->setFecha(date_create($row['fecha']));
            $arAcreditacion->setFechaAcreditacion(date_create($row['fecha_acreditacion']));
            $arAcreditacion->setFechaValidacion(date_create($row['fecha_validacion']));
            $arAcreditacion->setFechaVenceCurso(date_create($row['fecha_vence_curso']));
            $arAcreditacion->setFechaVencimiento(date_create($row['fecha_vencimiento']));
            $arAcreditacion->setNumeroAcreditacion($row['numero_acreditacion']);
            $arAcreditacion->setNumeroValidacion($row['numero_validacion']);
            $arAcreditacion->setNumeroRegistro(utf8_decode($row['numero_registro']));
            $arAcreditacion->setCodigoAcreditacionRechazoFk($row['codigo_acreditacion_rechazo_fk']);
            $arAcreditacion->setComentarios(utf8_decode($row['comentarios']));
            $arAcreditacion->setDetalleValidacion(utf8_decode($row['detalle_validacion']));
            $arAcreditacion->setEstadoAcreditado($row['estado_acreditado']);
            $arAcreditacion->setEstadoRechazado($row['estado_rechazado']);
            $arAcreditacion->setAcreditacionTipoRel($em->getReference(RhuAcreditacionTipo::class, $row['codigo_acreditacion_tipo_fk']));
            $arAcreditacion->setAcademiaRel($em->getReference(RhuAcademia::class, $row['codigo_academia_fk']));
            $arAcreditacion->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $em->persist($arAcreditacion);
            $metadata = $em->getClassMetaData(get_class($arAcreditacion));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function rhuEstudio($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_empleado_estudio_pk,
                codigo_empleado_fk,
                codigo_empleado_estudio_tipo_fk,
                codigo_ciudad_fk,
                institucion,
                titulo,
                fecha,
                fecha_inicio,
                fecha_terminacion,
                fecha_vencimiento_curso,
                fecha_inicio_acreditacion,
                fecha_vencimiento_acreditacion,
                validar_vencimiento,
                codigo_grado_bachiller_fk,
                codigo_academia_fk,
                graduado,
                numero_registro,
                numero_acreditacion,
                comentarios,
                codigo_estudio_tipo_acreditacion_fk,
                codigo_estudio_estado_fk,
                codigo_estudio_estado_invalido_fk,
                fecha_estado,
                fecha_estado_invalido
                FROM rhu_empleado_estudio");
        foreach ($datos as $row) {
            $arEstudio = new RhuEstudio();
            $arEstudio->setCodigoEstudioPk($row['codigo_empleado_estudio_pk']);
            $arEstudio->setInstitucion(utf8_encode($row['institucion']));
            $arEstudio->setTitulo(utf8_encode($row['titulo']));
            $arEstudio->setFecha(date_create($row['fecha']));
            $arEstudio->setFechaInicio(date_create($row['fecha_inicio']));
            $arEstudio->setFechaTerminacion(date_create($row['fecha_terminacion']));
            $arEstudio->setFechaVencimientoCurso(date_create($row['fecha_vencimiento_curso']));
            $arEstudio->setFechaInicioAcreditacion(date_create($row['fecha_inicio_acreditacion']));
            $arEstudio->setFechaVencimientoAcreditacion(date_create($row['fecha_vencimiento_acreditacion']));
            $arEstudio->setValidarVencimiento($row['validar_vencimiento']);
            $arEstudio->setCodigoGradoBachillerFk($row['codigo_grado_bachiller_fk']);
            $arEstudio->setCodigoAcademiaFk($row['codigo_academia_fk']);
            $arEstudio->setGraduado($row['graduado']);
            $arEstudio->setNumeroRegistro($row['numero_registro']);
            $arEstudio->setNumeroAcreditacion($row['numero_acreditacion']);
            $arEstudio->setCodigoEstudioTipoAcreditacionFk($row['codigo_estudio_tipo_acreditacion_fk']);
            $arEstudio->setCodigoEstudioEstadoFk($row['codigo_estudio_estado_fk']);
            $arEstudio->setCodigoEstudioEstadoInvalidoFk($row['codigo_estudio_estado_invalido_fk']);
            $arEstudio->setComentarios(utf8_encode($row['comentarios']));
            $arEstudio->setFechaEstado(date_create($row['fecha_estado']));
            $arEstudio->setFechaEstadoInvalido(date_create($row['fecha_estado_invalido']));

            $arEstudio->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $arEstudio->setEstudioTipoRel($em->getReference(RhuEstudioTipo::class, $row['codigo_empleado_estudio_tipo_fk']));
            $arEstudio->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));

            $em->persist($arEstudio);
            $metadata = $em->getClassMetaData(get_class($arEstudio));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function rhuVisita($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("select 
                codigo_visita_pk,
                codigo_visita_tipo_fk,
                codigo_empleado_fk,
                fecha_vence,
                codigo_tipo_identificacion_fk,
                fecha,
                fecha_creacion,
                codigo_cliente_fk,
                codigo_centro_costo_fk,
                codigo_cobro_fk,
                validar_vencimiento,
                nombre_quien_visita,
                comentarios,
                nombre_corto,
                numero_identificacion,
                estado_autorizado,
                estado_cerrado,
                estado_cobrado,
                vr_total
                from rhu_visita");
        foreach ($datos as $row) {
            $arVisita = new RhuVisita();
            $arVisita->setCodigoVisitaPk($row['codigo_visita_pk']);
            $arVisita->setFechaVence(date_create($row['fecha_vence']));
            $arVisita->setCodigoIdentificacionFk($row['codigo_tipo_identificacion_fk']);
            $arVisita->setFechaCreacion(date_create($row['fecha_creacion']));
            $arVisita->setCodigoClienteFk($row['codigo_cliente_fk']);
            if ($row['fecha']) {
                $arVisita->setFecha(date_create($row['fecha']));
            }
            $arVisita->setCodigoCentroCostoFk($row['codigo_centro_costo_fk']);
            $arVisita->setCodigoCobroFk($row['codigo_cobro_fk']);
            $arVisita->setValidarVencimiento($row['validar_vencimiento']);
            $arVisita->setNombreQuienVisita(utf8_encode($row['nombre_quien_visita']));
            $arVisita->setComentarios(utf8_encode($row['comentarios']));
            $arVisita->setNombreCorto(utf8_encode($row['nombre_corto']));
            $arVisita->setNumeroIdentificacion($row['numero_identificacion']);
            $arVisita->setEstadoAutorizado($row['estado_autorizado']);
            $arVisita->setEstadoCerrado($row['estado_cerrado']);
            $arVisita->setEstadoCobrado($row['estado_cobrado']);
            $arVisita->setVrTotal($row['vr_total']);
            $arVisita->setVisitaTipoRel($em->getReference(RhuVisitaTipo::class, $row['codigo_visita_tipo_fk']));
            $arVisita->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_empleado_fk']));
            $em->persist($arVisita);
            $metadata = $em->getClassMetaData(get_class($arVisita));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();

    }

    private function turTurno($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_turno_pk, 
                nombre, 
                hora_desde, 
                hora_hasta, 
                horas,                  
                horas_diurnas, 
                horas_nocturnas, 
                novedad, 
                descanso, 
                incapacidad,
                incapacidad_no_legalizada, 
                licencia, 
                licencia_no_remunerada, 
                vacacion, 
                ingreso, 
                retiro, 
                induccion,                   
                complementario, 
                turno_completo,                  
                ausentismo, 
                dia, 
                noche
                 FROM tur_turno");
        foreach ($datos as $row) {
            $arTurno = new TurTurno();
            $arTurno->setCodigoTurnoPk($row['codigo_turno_pk']);
            $arTurno->setNombre(utf8_encode($row['nombre']));
            $arTurno->setHoraDesde(date_create($row['hora_desde']));
            $arTurno->setHoraHasta(date_create($row['hora_hasta']));
            $arTurno->setHoras($row['horas']);
            $arTurno->setHorasDiurnas($row['horas_diurnas']);
            $arTurno->setHorasNocturnas($row['horas_nocturnas']);
            $arTurno->setNovedad($row['novedad']);
            $arTurno->setDescanso($row['descanso']);
            $arTurno->setIncapacidad($row['incapacidad']);
            $arTurno->setIncapacidadNoLegalizada($row['incapacidad_no_legalizada']);
            $arTurno->setLicencia($row['licencia']);
            $arTurno->setLicenciaNoRemunerada($row['licencia_no_remunerada']);
            $arTurno->setVacacion($row['vacacion']);
            $arTurno->setIngreso($row['ingreso']);
            $arTurno->setRetiro($row['retiro']);
            $arTurno->setInduccion($row['induccion']);
            $arTurno->setComplementario($row['complementario']);
            $arTurno->setCompleto($row['turno_completo']);
            $arTurno->setAusentismo($row['ausentismo']);
            $arTurno->setDia($row['dia']);
            $arTurno->setNoche($row['noche']);
            $em->persist($arTurno);
            $metadata = $em->getClassMetaData(get_class($arTurno));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function turSecuencia($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                                codigo_secuencia_pk,
                                nombre,
                                dia_1,
                                dia_2,
                                dia_3,
                                dia_4,
                                dia_5,
                                dia_6,
                                dia_7,
                                dia_8,
                                dia_9,
                                dia_10,
                                dia_11,
                                dia_12,
                                dia_13,
                                dia_14,
                                dia_15,
                                dia_16,
                                dia_17,
                                dia_18,
                                dia_19,
                                dia_20,
                                dia_21,
                                dia_22,
                                dia_23,
                                dia_24,
                                dia_25,
                                dia_26,
                                dia_27,
                                dia_28,
                                dia_29,
                                dia_30,
                                dia_31,
                                lunes,
                                martes,
                                miercoles,
                                jueves,
                                viernes,
                                sabado,
                                domingo,
                                festivo,
                                domingo_festivo,
                                horas,
                                dias,
                                homologar                                
                                    FROM tur_secuencia");
        foreach ($datos as $row) {
            $arSecuencia = new TurSecuencia();
            $arSecuencia->setCodigoSecuenciaPk($row['codigo_secuencia_pk']);
            $arSecuencia->setNombre(utf8_encode($row['nombre']));
            $arSecuencia->setDia1($row['dia_1']);
            $arSecuencia->setDia2($row['dia_2']);
            $arSecuencia->setDia3($row['dia_3']);
            $arSecuencia->setDia4($row['dia_4']);
            $arSecuencia->setDia5($row['dia_5']);
            $arSecuencia->setDia6($row['dia_6']);
            $arSecuencia->setDia7($row['dia_7']);
            $arSecuencia->setDia8($row['dia_8']);
            $arSecuencia->setDia9($row['dia_9']);
            $arSecuencia->setDia10($row['dia_10']);
            $arSecuencia->setDia11($row['dia_11']);
            $arSecuencia->setDia12($row['dia_12']);
            $arSecuencia->setDia13($row['dia_13']);
            $arSecuencia->setDia14($row['dia_14']);
            $arSecuencia->setDia15($row['dia_15']);
            $arSecuencia->setDia16($row['dia_16']);
            $arSecuencia->setDia17($row['dia_17']);
            $arSecuencia->setDia18($row['dia_18']);
            $arSecuencia->setDia19($row['dia_19']);
            $arSecuencia->setDia20($row['dia_20']);
            $arSecuencia->setDia21($row['dia_21']);
            $arSecuencia->setDia22($row['dia_22']);
            $arSecuencia->setDia23($row['dia_23']);
            $arSecuencia->setDia24($row['dia_24']);
            $arSecuencia->setDia25($row['dia_25']);
            $arSecuencia->setDia26($row['dia_26']);
            $arSecuencia->setDia27($row['dia_27']);
            $arSecuencia->setDia28($row['dia_28']);
            $arSecuencia->setDia29($row['dia_29']);
            $arSecuencia->setDia30($row['dia_30']);
            $arSecuencia->setDia31($row['dia_31']);
            $arSecuencia->setLunes($row['lunes']);
            $arSecuencia->setMartes($row['martes']);
            $arSecuencia->setMiercoles($row['miercoles']);
            $arSecuencia->setJueves($row['jueves']);
            $arSecuencia->setViernes($row['viernes']);
            $arSecuencia->setSabado($row['sabado']);
            $arSecuencia->setDomingo($row['domingo']);
            $arSecuencia->setDomingoFestivo($row['domingo_festivo']);
            $arSecuencia->setHoras($row['horas']);
            $arSecuencia->setDias($row['dias']);
            $arSecuencia->setHomologar($row['homologar']);
            $em->persist($arSecuencia);
            $metadata = $em->getClassMetaData(get_class($arSecuencia));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function turCliente($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_cliente_pk FROM tur_cliente ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_cliente_pk,
                nit,
                digito_verificacion,
                nombre_corto,
                nombre_completo,
                estrato,
                direccion,
                telefono,
                codigo_forma_pago_fk,
                regimen_comun,
                codigo_origen_judicial_fk,
                codigo_sector_comercial_fk,
                codigo_cobertura_fk,
                codigo_dimension_fk,
                codigo_origen_capital_fk,
                codigo_sector_economico_fk,
                codigo_ciudad_fk,
                codigo_segmento_fk
                 FROM tur_cliente 
                 ORDER BY codigo_cliente_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arCliente = new TurCliente();
                $arCliente->setCodigoClientePk($row['codigo_cliente_pk']);
                $arCliente->setNumeroIdentificacion($row['nit']);
                $arCliente->setDigitoVerificacion($row['digito_verificacion']);
                $arCliente->setNombreCorto(utf8_encode($row['nombre_corto']));
                $arCliente->setNombreExtendido(utf8_encode($row['nombre_completo']));
                $arCliente->setEstrato($row['estrato']);
                $arCliente->setDireccion(utf8_encode($row['direccion']));
                $arCliente->setTelefono($row['telefono']);
                if ($row['codigo_ciudad_fk']) {
                    $arCliente->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                }
                if ($row['codigo_segmento_fk']) {
                    $arCliente->setSegmentoRel($em->getReference(GenSegmento::class, $row['codigo_segmento_fk']));
                }
                if ($row['codigo_sector_comercial_fk']) {
                    $arCliente->setSectorComercialRel($em->getReference(GenSectorComercial::class, $row['codigo_sector_comercial_fk']));
                }
                if ($row['codigo_cobertura_fk']) {
                    $arCliente->setCoberturaRel($em->getReference(GenCobertura::class, $row['codigo_cobertura_fk']));
                }
                if ($row['codigo_dimension_fk']) {
                    $arCliente->setDimensionRel($em->getReference(GenDimension::class, $row['codigo_dimension_fk']));
                }
                if ($row['codigo_origen_capital_fk']) {
                    $arCliente->setOrigenCapitalRel($em->getReference(GenOrigenCapital::class, $row['codigo_origen_capital_fk']));
                }
                if ($row['codigo_sector_economico_fk']) {
                    $arCliente->setSectorEconomicoRel($em->getReference(GenSectorEconomico::class, $row['codigo_sector_economico_fk']));
                }
                if ($row['codigo_forma_pago_fk']) {
                    if ($row['codigo_forma_pago_fk'] == 1) {
                        $formaPago = 'CON';
                    } else {
                        $formaPago = 'CRE';
                    }
                    $arCliente->setFormaPagoRel($em->getReference(GenFormaPago::class, $formaPago));
                }
                if ($row['codigo_origen_judicial_fk']) {
                    if ($row['codigo_origen_judicial_fk'] == 1) {
                        $tipoPersonal = 'J';
                    } else {
                        $tipoPersonal = 'N';
                    }
                    $arCliente->setTipoPersonaRel($em->getReference(GenTipoPersona::class, $tipoPersonal));
                }
                if ($row['regimen_comun'] == 1) {
                    $regimen = 'O';
                } else {
                    $regimen = 'S';
                }
                $arCliente->setRegimenRel($em->getReference(GenRegimen::class, $regimen));
                $em->persist($arCliente);
                $metadata = $em->getClassMetaData(get_class($arCliente));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turGrupo($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_grupo_facturacion_pk FROM tur_grupo_facturacion ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_grupo_facturacion_pk,
                    codigo_cliente_fk,
                    nombre,
                    abreviatura,
                    concepto,
                    codigo_servicio_factura_fk
                 FROM tur_grupo_facturacion 
                 ORDER BY codigo_grupo_facturacion_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arGrupo = new TurGrupo();
                $arGrupo->setCodigoGrupoPk($row['codigo_grupo_facturacion_pk']);
                $arGrupo->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                $arGrupo->setNombre(utf8_encode($row['nombre']));
                $arGrupo->setConcepto(utf8_encode($row['concepto']));
                $arGrupo->setAbreviatura($row['abreviatura']);
                $em->persist($arGrupo);
                $metadata = $em->getClassMetaData(get_class($arGrupo));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turOperacion($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_operacion_pk FROM tur_operacion ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_operacion_pk,
                    codigo_cliente_fk,
                    nombre_corto,
                    nombre,
                    codigo_proyecto_fk,
                    codigo_operacion_tipo_fk
                 FROM tur_operacion 
                 ORDER BY codigo_operacion_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arOperacion = new TurOperacion();
                $arOperacion->setCodigoOperacionPk($row['codigo_operacion_pk']);
                $arOperacion->setNombre(utf8_encode($row['nombre']));
                $arOperacion->setNombreCorto(utf8_encode($row['nombre_corto']));
                if ($row['codigo_cliente_fk']) {
                    $arOperacion->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                }
                if ($row['codigo_operacion_tipo_fk']) {
                    $arOperacion->setOperacionTipoRel($em->getReference(TurOperacionTipo::class, $row['codigo_operacion_tipo_fk']));
                }
                $em->persist($arOperacion);
                $metadata = $em->getClassMetaData(get_class($arOperacion));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turPuesto($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_puesto_pk FROM tur_puesto ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_puesto_pk,
                codigo_cliente_fk,
                nombre,
                direccion,
                telefono,
                celular,
                contacto,
                codigo_ciudad_fk,
                codigo_centro_costo_contabilidad_fk,
                latitud,
                longitud, 
                codigo_operacion_fk,
                codigo_zona_fk,
                codigo_area_fk,
                codigo_proyecto_fk    
                 FROM tur_puesto 
                 ORDER BY codigo_puesto_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPuesto = new TurPuesto();
                $arPuesto->setCodigoPuestoPk($row['codigo_puesto_pk']);
                if ($row['codigo_cliente_fk']) {
                    $arPuesto->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                }
                if ($row['codigo_centro_costo_contabilidad_fk']) {
                    $arPuesto->setCentroCostoRel($em->getReference(FinCentroCosto::class, $row['codigo_centro_costo_contabilidad_fk']));
                }
                $arPuesto->setNombre(utf8_encode($row['nombre']));
                $arPuesto->setDireccion(utf8_encode($row['direccion']));
                $arPuesto->setTelefono(utf8_encode($row['telefono']));
                $arPuesto->setCelular(utf8_encode($row['celular']));
                $arPuesto->setContacto(utf8_encode($row['contacto']));
                if ($row['codigo_ciudad_fk']) {
                    $arPuesto->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
                }
                if ($row['latitud']) {
                    $arPuesto->setLatitud($row['latitud']);
                }
                if ($row['longitud']) {
                    $arPuesto->setLongitud($row['longitud']);
                }
                if ($row['codigo_operacion_fk']) {
                    $arPuesto->setOperacionRel($em->getReference(TurOperacion::class, $row['codigo_operacion_fk']));
                }
                if ($row['codigo_zona_fk']) {
                    $arPuesto->setZonaRel($em->getReference(TurZona::class, $row['codigo_zona_fk']));
                }
                if ($row['codigo_area_fk']) {
                    $arPuesto->setAreaRel($em->getReference(TurArea::class, $row['codigo_area_fk']));
                }
                if ($row['codigo_proyecto_fk']) {
                    $arPuesto->setProyectoRel($em->getReference(TurProyecto::class, $row['codigo_proyecto_fk']));
                }
                $em->persist($arPuesto);
                $metadata = $em->getClassMetaData(get_class($arPuesto));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turConcepto($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query("SELECT
                codigo_concepto_servicio_pk,
                nombre,
                nombre_facturacion,
                horas,
                horas_diurnas,
                horas_nocturnas,
                por_iva
                 FROM tur_concepto_servicio");
        foreach ($datos as $row) {
            $arConcepto = new TurConcepto();
            $arConcepto->setCodigoConceptoPk($row['codigo_concepto_servicio_pk']);
            $arConcepto->setNombre(utf8_encode($row['nombre']));
            $arConcepto->setNombreFacturacion(utf8_encode($row['nombre_facturacion']));
            $arConcepto->setHoras($row['horas']);
            $arConcepto->setHorasDiurnas($row['horas_diurnas']);
            $arConcepto->setHorasNocturnas($row['horas_nocturnas']);
            $em->persist($arConcepto);
            $metadata = $em->getClassMetaData(get_class($arConcepto));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
        $em->flush();
    }

    private function turContrato($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_servicio_pk FROM tur_servicio ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_servicio_pk,
                codigo_cliente_fk,  
                tur_sector.codigo_externo as codigo_sector_externo,              
                fecha_generacion,
                soporte,
                estado_autorizado,
                estado_cerrado,
                horas,
                horas_diurnas,
                horas_nocturnas,                
                vr_total_precio_ajustado,
                vr_total_precio_minimo,
                vr_subtotal,
                vr_iva,
                vr_base_aiu,
                vr_total,
                usuario,
                tur_servicio.comentarios,
                vr_salario_base,
                estrato 
                 FROM tur_servicio 
                 left join tur_sector on tur_servicio.codigo_sector_fk = tur_sector.codigo_sector_pk 
                 ORDER BY codigo_servicio_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arContrato = new TurContrato();
                $arContrato->setCodigoContratoPk($row['codigo_servicio_pk']);
                $arContrato->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                $arContrato->setSectorRel($em->getReference(TurSector::class, $row['codigo_sector_externo']));
                $arContrato->setContratoTipoRel($em->getReference(TurContratoTipo::class, 'PER'));
                $arContrato->setFechaGeneracion(date_create($row['fecha_generacion']));
                $arContrato->setSoporte($row['soporte']);
                $arContrato->setEstadoAutorizado($row['estado_autorizado']);
                $arContrato->setEstadoTerminado($row['estado_cerrado']);
                $arContrato->setHoras($row['horas']);
                $arContrato->setHorasDiurnas($row['horas_diurnas']);
                $arContrato->setHorasNocturnas($row['horas_nocturnas']);
                $arContrato->setVrTotalPrecioAjustado($row['vr_total_precio_ajustado']);
                $arContrato->setVrTotalPrecioMinimo($row['vr_total_precio_minimo']);
                $arContrato->setVrSubtotal($row['vr_subtotal']);
                $arContrato->setVrIva($row['vr_iva']);
                $arContrato->setVrBaseAiu($row['vr_base_aiu']);
                $arContrato->setVrTotal($row['vr_total']);
                $arContrato->setUsuario($row['usuario']);
                $arContrato->setComentarios(utf8_encode($row['comentarios']));
                $arContrato->setVrSalarioBase($row['vr_salario_base']);
                if ($row['estrato']) {
                    $arContrato->setEstrato($row['estrato']);
                }
                $em->persist($arContrato);
                $metadata = $em->getClassMetaData(get_class($arContrato));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turContratoDetalle($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_servicio_detalle_pk FROM tur_servicio_detalle ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_servicio_detalle_pk,
                    codigo_servicio_fk,
                    codigo_puesto_fk,
                    codigo_concepto_servicio_fk,
                    tur_modalidad_servicio.codigo_externo as codigo_modalidad_servicio_externo,
                    fecha_desde,
                    fecha_hasta,
                    hora_inicio,
                    hora_fin,                    
                    liquidar_dias_reales,
                    dias,
                    horas,
                    horas_diurnas,
                    horas_nocturnas,
                    cantidad,
                    vr_precio_minimo,
                    vr_precio_ajustado,
                    vr_precio,
                    vr_subtotal,
                    vr_iva,
                    vr_base_aiu,
                    vr_total_detalle,
                    lunes,
                    martes,
                    miercoles,
                    jueves,
                    viernes,
                    sabado,
                    domingo,
                    festivo,
                    estado_cerrado,
                    vr_salario_base,
                    porcentaje_iva,
                    porcentaje_base_iva,
                    codigo_grupo_facturacion_fk,
                    compuesto,
                    no_facturar
                 FROM tur_servicio_detalle 
                 left join tur_modalidad_servicio on tur_servicio_detalle.codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk 
                 ORDER BY codigo_servicio_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arContratoDetalle = new TurContratoDetalle();
                $arContratoDetalle->setCodigoContratoDetallePk($row['codigo_servicio_detalle_pk']);
                $arContratoDetalle->setContratoRel($em->getReference(TurContrato::class, $row['codigo_servicio_fk']));
                $arContratoDetalle->setPuestoRel($em->getReference(TurPuesto::class, $row['codigo_puesto_fk']));
                $arContratoDetalle->setConceptoRel($em->getReference(TurConcepto::class, $row['codigo_concepto_servicio_fk']));
                $arContratoDetalle->setModalidadRel($em->getReference(TurModalidad::class, $row['codigo_modalidad_servicio_externo']));
                if ($row['codigo_grupo_facturacion_fk']) {
                    $arContratoDetalle->setGrupoRel($em->getReference(TurGrupo::class, $row['codigo_grupo_facturacion_fk']));
                }
                $arContratoDetalle->setFechaDesde(date_create($row['fecha_desde']));
                $arContratoDetalle->setFechaHasta(date_create($row['fecha_hasta']));
                $arContratoDetalle->setLiquidarDiasReales($row['liquidar_dias_reales']);
                $arContratoDetalle->setDias($row['dias']);
                $arContratoDetalle->setHoras($row['horas']);
                $arContratoDetalle->setHorasDiurnas($row['horas_diurnas']);
                $arContratoDetalle->setHorasNocturnas($row['horas_nocturnas']);
                $arContratoDetalle->setCantidad($row['cantidad']);
                $arContratoDetalle->setVrPrecioMinimo($row['vr_precio_minimo']);
                $arContratoDetalle->setVrPrecioAjustado($row['vr_precio_ajustado']);
                $arContratoDetalle->setVrPrecio($row['vr_precio']);
                $arContratoDetalle->setVrSubtotal($row['vr_subtotal']);
                $arContratoDetalle->setVrIva($row['vr_iva']);
                $arContratoDetalle->setVrBaseAiu($row['vr_base_aiu']);
                $arContratoDetalle->setVrTotalDetalle($row['vr_total_detalle']);
                $arContratoDetalle->setLunes($row['lunes']);
                $arContratoDetalle->setMartes($row['martes']);
                $arContratoDetalle->setMiercoles($row['miercoles']);
                $arContratoDetalle->setJueves($row['jueves']);
                $arContratoDetalle->setViernes($row['viernes']);
                $arContratoDetalle->setSabado($row['sabado']);
                $arContratoDetalle->setDomingo($row['domingo']);
                $arContratoDetalle->setFestivo($row['festivo']);
                $arContratoDetalle->setEstadoTerminado($row['estado_cerrado']);
                $arContratoDetalle->setVrSalarioBase($row['vr_salario_base']);
                $arContratoDetalle->setPorcentajeIva($row['porcentaje_iva']);
                $arContratoDetalle->setPorcentajeBaseIva($row['porcentaje_base_iva']);
                $arContratoDetalle->setHoraDesde(date_create($row['hora_inicio']));
                $arContratoDetalle->setHoraHasta(date_create($row['hora_fin']));
                $arContratoDetalle->setPeriodo('M');
                $arContratoDetalle->setCompuesto($row['compuesto']);
                $arContratoDetalle->setProgramar(1);
                $arContratoDetalle->setCortesia($row['no_facturar']);

                $ar = $conn->query("SELECT codigo_pk, hora_inicio, hora_fin, horas, horas_diurnas, horas_nocturnas FROM temporal_conceptos WHERE modalidad='" . $row['codigo_modalidad_servicio_externo'] . "' AND codigo_concepto_fk = " . $row['codigo_concepto_servicio_fk']);
                $registro = $ar->fetch_assoc();
                if ($registro) {
                    $codigoItem = $registro['codigo_pk'];
                    $horaDesde = date_create($registro['hora_inicio']);
                    $horaHasta = date_create($registro['hora_fin']);
                    $arContratoDetalle->setItemRel($em->getReference(TurItem::class, $codigoItem));
                    $arContratoDetalle->setHorasUnidad($registro['horas']);
                    $arContratoDetalle->setHorasDiurnasUnidad($registro['horas_diurnas']);
                    $arContratoDetalle->setHorasNocturnasUnidad($registro['horas_nocturnas']);
                    $arContratoDetalle->setHoraDesde($horaDesde);
                    $arContratoDetalle->setHoraHasta($horaHasta);
                } else {
                    Mensajes::error("No existe el registro modalidad " . $row['codigo_modalidad_servicio_externo'] . " concepto = " . $row['codigo_concepto_servicio_fk']);
                    break 2;
                }

                $em->persist($arContratoDetalle);
                $metadata = $em->getClassMetaData(get_class($arContratoDetalle));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turContratoDetalleCompuesto($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_servicio_detalle_compuesto_pk FROM tur_servicio_detalle_compuesto ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_servicio_detalle_compuesto_pk,
                    codigo_servicio_detalle_fk,
                    codigo_concepto_servicio_fk,
                    codigo_modalidad_servicio_fk,
                    tur_modalidad_servicio.codigo_externo as codigo_modalidad_servicio_externo,
                    codigo_periodo_fk,
                    liquidar_dias_reales,
                    dias,
                    horas,
                    horas_diurnas,
                    horas_nocturnas,
                    cantidad,
                    vr_precio_ajustado,
                    vr_precio_minimo,
                    vr_precio,
                    vr_subtotal,  
                    vr_iva,                    
                    vr_base_aiu,       
                    vr_total_detalle,       
                    porcentaje_iva,   
                    lunes,
                    martes,
                    miercoles,
                    jueves,
                    viernes,
                    sabado,
                    domingo,
                    festivo,
                    no_facturar             
                 FROM tur_servicio_detalle_compuesto 
                 left join tur_modalidad_servicio on codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk 
                 ORDER BY codigo_servicio_detalle_compuesto_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arContratoDetalleCompuesto = new TurContratoDetalleCompuesto();
                $arContratoDetalleCompuesto->setCodigoContratoDetalleCompuestoPk($row['codigo_servicio_detalle_compuesto_pk']);
                $arContratoDetalleCompuesto->setContratoDetalleRel($em->getReference(TurContratoDetalle::class, $row['codigo_servicio_detalle_fk']));
                $arContratoDetalleCompuesto->setConceptoRel($em->getReference(TurConcepto::class, $row['codigo_concepto_servicio_fk']));
                $arContratoDetalleCompuesto->setModalidadRel($em->getReference(TurModalidad::class, $row['codigo_modalidad_servicio_externo']));
                if ($row['codigo_periodo_fk'] == 1) {
                    $arContratoDetalleCompuesto->setPeriodo("M");
                } else {
                    $arContratoDetalleCompuesto->setPeriodo("D");
                }
                $arContratoDetalleCompuesto->setDias($row['dias']);
                $arContratoDetalleCompuesto->setHoras($row['horas']);
                $arContratoDetalleCompuesto->setHorasDiurnas($row['horas_diurnas']);
                $arContratoDetalleCompuesto->setHorasNocturnas($row['horas_nocturnas']);
                $arContratoDetalleCompuesto->setCantidad($row['cantidad']);
                $arContratoDetalleCompuesto->setVrPrecioAjustado($row['vr_precio_ajustado']);
                $arContratoDetalleCompuesto->setVrPrecioMinimo($row['vr_precio_minimo']);
                $arContratoDetalleCompuesto->setVrPrecio($row['vr_precio']);
                $arContratoDetalleCompuesto->setVrSubtotal($row['vr_subtotal']);
                $arContratoDetalleCompuesto->setVrIva($row['vr_iva']);
                $arContratoDetalleCompuesto->setLunes($row['lunes']);
                $arContratoDetalleCompuesto->setMartes($row['martes']);
                $arContratoDetalleCompuesto->setMiercoles($row['miercoles']);
                $arContratoDetalleCompuesto->setJueves($row['jueves']);
                $arContratoDetalleCompuesto->setViernes($row['viernes']);
                $arContratoDetalleCompuesto->setSabado($row['sabado']);
                $arContratoDetalleCompuesto->setDomingo($row['domingo']);
                $arContratoDetalleCompuesto->setFestivo($row['festivo']);
                $arContratoDetalleCompuesto->setPorcentajeIva($row['porcentaje_iva']);
                $arContratoDetalleCompuesto->setCortesia($row['no_facturar']);

                $ar = $conn->query("SELECT codigo_pk, hora_inicio, hora_fin, horas, horas_diurnas, horas_nocturnas FROM temporal_conceptos WHERE modalidad='" . $row['codigo_modalidad_servicio_externo'] . "' AND codigo_concepto_fk = " . $row['codigo_concepto_servicio_fk']);
                $registro = $ar->fetch_assoc();
                if ($registro) {
                    $horaDesde = date_create($registro['hora_inicio']);
                    $horaHasta = date_create($registro['hora_fin']);
                    $arContratoDetalleCompuesto->setHorasUnidad($registro['horas']);
                    $arContratoDetalleCompuesto->setHorasDiurnasUnidad($registro['horas_diurnas']);
                    $arContratoDetalleCompuesto->setHorasNocturnasUnidad($registro['horas_nocturnas']);
                    $arContratoDetalleCompuesto->setHoraDesde($horaDesde);
                    $arContratoDetalleCompuesto->setHoraHasta($horaHasta);
                } else {
                    Mensajes::error("No existe el registro modalidad " . $row['codigo_modalidad_servicio_externo'] . " concepto = " . $row['codigo_concepto_servicio_fk']);
                    break 2;
                }

                $em->persist($arContratoDetalleCompuesto);
                $metadata = $em->getClassMetaData(get_class($arContratoDetalleCompuesto));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turPedido($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pedido_pk FROM tur_pedido ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_pedido_pk,
                codigo_cliente_fk,  
                tur_sector.codigo_externo as codigo_sector_externo,
                numero,              
                fecha,
                estado_autorizado,
                horas,
                horas_diurnas,
                horas_nocturnas,
                vr_total_servicio,
                vr_total_precio_ajustado,
                vr_total_precio_minimo,
                vr_subtotal,
                vr_iva,
                vr_base_aiu,
                vr_total,
                usuario,
                tur_pedido.comentarios,
                vr_salario_base,
                estrato                 
                 FROM tur_pedido
                 left join tur_sector on tur_pedido.codigo_sector_fk = tur_sector.codigo_sector_pk 
                 ORDER BY codigo_pedido_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPedido = new TurPedido();
                $arPedido->setCodigoPedidoPk($row['codigo_pedido_pk']);
                $arPedido->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                $arPedido->setSectorRel($em->getReference(TurSector::class, $row['codigo_sector_externo']));
                $arPedido->setPedidoTipoRel($em->getReference(TurPedidoTipo::class, 'CON'));
                $arPedido->setFecha(date_create($row['fecha']));
                $arPedido->setNumero($row['numero']);
                $arPedido->setEstadoAutorizado($row['estado_autorizado']);
                $arPedido->setHoras($row['horas']);
                $arPedido->setHorasDiurnas($row['horas_diurnas']);
                $arPedido->setHorasNocturnas($row['horas_nocturnas']);
                $arPedido->setVrTotalPrecioAjustado($row['vr_total_precio_ajustado']);
                $arPedido->setVrTotalPrecioMinimo($row['vr_total_precio_minimo']);
                $arPedido->setVrSubtotal($row['vr_subtotal']);
                $arPedido->setVrIva($row['vr_iva']);
                $arPedido->setVrBaseIva($row['vr_base_aiu']);
                $arPedido->setVrTotal($row['vr_total']);
                $arPedido->setUsuario($row['usuario']);
                $arPedido->setComentario(utf8_encode($row['comentarios']));
                $arPedido->setVrSalarioBase($row['vr_salario_base']);
                $arPedido->setEstrato($row['estrato']);
                $em->persist($arPedido);
                $metadata = $em->getClassMetaData(get_class($arPedido));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turPedidoDetalle($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pedido_detalle_pk FROM tur_pedido_detalle ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_pedido_detalle_pk,
                    codigo_pedido_fk,
                    codigo_puesto_fk,
                    codigo_concepto_servicio_fk,
                    codigo_servicio_detalle_fk,
                    codigo_periodo_fk,
                    tur_modalidad_servicio.codigo_externo as codigo_modalidad_servicio_externo,
                    dia_desde,
                    dia_hasta,
                    hora_inicio,
                    hora_fin,                    
                    anio,
                    mes,
                    dias,
                    horas,
                    horas_diurnas,
                    horas_nocturnas,
                    horas_programadas,
                    horas_diurnas_programadas,
                    horas_nocturnas_programadas,
                    cantidad,
                    vr_precio_ajustado,
                    vr_precio_minimo,
                    vr_precio,
                    vr_subtotal,
                    vr_iva,
                    vr_base_aiu,
                    vr_total_detalle,
                    vr_total_detalle_afectado,
                    vr_total_detalle_pendiente,
                    vr_total_detalle_devolucion,
                    vr_total_detalle_adicion,
                    lunes,
                    martes,
                    miercoles,
                    jueves,
                    viernes,
                    sabado,
                    domingo,
                    festivo,
                    compuesto,
                    vr_salario_base,
                    porcentaje_iva,
                    porcentaje_base_iva,
                    estado_programado,
                    codigo_grupo_facturacion_fk,
                    no_facturar
                 FROM tur_pedido_detalle 
                 left join tur_modalidad_servicio on codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk 
                 ORDER BY codigo_pedido_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPedidoDetalle = new TurPedidoDetalle();
                $arPedidoDetalle->setCodigoPedidoDetallePk($row['codigo_pedido_detalle_pk']);
                $arPedidoDetalle->setPedidoRel($em->getReference(TurPedido::class, $row['codigo_pedido_fk']));
                $arPedidoDetalle->setPuestoRel($em->getReference(TurPuesto::class, $row['codigo_puesto_fk']));
                $arPedidoDetalle->setConceptoRel($em->getReference(TurConcepto::class, $row['codigo_concepto_servicio_fk']));
                $arPedidoDetalle->setModalidadRel($em->getReference(TurModalidad::class, $row['codigo_modalidad_servicio_externo']));
                if ($row['codigo_periodo_fk'] == 1) {
                    $arPedidoDetalle->setPeriodo("M");
                } else {
                    $arPedidoDetalle->setPeriodo("D");
                }
                if ($row['codigo_servicio_detalle_fk']) {
                    $arPedidoDetalle->setContratoDetalleRel($em->getReference(TurContratoDetalle::class, $row['codigo_servicio_detalle_fk']));
                }
                if ($row['codigo_grupo_facturacion_fk']) {
                    $arPedidoDetalle->setGrupoRel($em->getReference(TurGrupo::class, $row['codigo_grupo_facturacion_fk']));
                }
                $arPedidoDetalle->setAnio($row['anio']);
                $arPedidoDetalle->setMes($row['mes']);
                $arPedidoDetalle->setDiaDesde($row['dia_desde']);
                $arPedidoDetalle->setDiaHasta($row['dia_hasta']);
                $arPedidoDetalle->setDias($row['dias']);
                $arPedidoDetalle->setHoras($row['horas']);
                $arPedidoDetalle->setHorasDiurnas($row['horas_diurnas']);
                $arPedidoDetalle->setHorasNocturnas($row['horas_nocturnas']);
                $arPedidoDetalle->setHorasProgramadas($row['horas_programadas']);
                $arPedidoDetalle->setHorasDiurnasProgramadas($row['horas_diurnas_programadas']);
                $arPedidoDetalle->setHorasNocturnasProgramadas($row['horas_nocturnas_programadas']);
                $arPedidoDetalle->setCantidad($row['cantidad']);
                $arPedidoDetalle->setVrPrecioAjustado($row['vr_precio_ajustado']);
                $arPedidoDetalle->setVrPrecioMinimo($row['vr_precio_minimo']);
                $arPedidoDetalle->setVrPrecio($row['vr_precio']);
                $arPedidoDetalle->setVrSubtotal($row['vr_subtotal']);
                $arPedidoDetalle->setVrIva($row['vr_iva']);
                $arPedidoDetalle->setVrBaseIva($row['vr_base_aiu']);
                $arPedidoDetalle->setVrTotal($row['vr_total_detalle']);
                $arPedidoDetalle->setVrAfectado($row['vr_total_detalle_afectado']);
                $arPedidoDetalle->setVrPendiente($row['vr_total_detalle_pendiente']);
                $arPedidoDetalle->setVrDevolucion($row['vr_total_detalle_devolucion']);
                $arPedidoDetalle->setVrAdicion($row['vr_total_detalle_adicion']);
                $arPedidoDetalle->setLunes($row['lunes']);
                $arPedidoDetalle->setMartes($row['martes']);
                $arPedidoDetalle->setMiercoles($row['miercoles']);
                $arPedidoDetalle->setJueves($row['jueves']);
                $arPedidoDetalle->setViernes($row['viernes']);
                $arPedidoDetalle->setSabado($row['sabado']);
                $arPedidoDetalle->setDomingo($row['domingo']);
                $arPedidoDetalle->setFestivo($row['festivo']);
                $arPedidoDetalle->setCompuesto($row['compuesto']);
                $arPedidoDetalle->setEstadoProgramado($row['estado_programado']);
                $arPedidoDetalle->setVrSalarioBase($row['vr_salario_base']);
                $arPedidoDetalle->setPorcentajeIva($row['porcentaje_iva']);
                $arPedidoDetalle->setPorcentajeBaseIva($row['porcentaje_base_iva']);
                $arPedidoDetalle->setCortesia($row['no_facturar']);

                $ar = $conn->query("SELECT codigo_pk, hora_inicio, hora_fin, horas, horas_diurnas, horas_nocturnas FROM temporal_conceptos WHERE modalidad='" . $row['codigo_modalidad_servicio_externo'] . "' AND codigo_concepto_fk = " . $row['codigo_concepto_servicio_fk']);
                $registro = $ar->fetch_assoc();
                if ($registro) {
                    $codigoItem = $registro['codigo_pk'];
                    $horaDesde = date_create($registro['hora_inicio']);
                    $horaHasta = date_create($registro['hora_fin']);
                    $arPedidoDetalle->setItemRel($em->getReference(TurItem::class, $codigoItem));
                    $arPedidoDetalle->setHorasUnidad($registro['horas']);
                    $arPedidoDetalle->setHorasDiurnasUnidad($registro['horas_diurnas']);
                    $arPedidoDetalle->setHorasNocturnasUnidad($registro['horas_nocturnas']);
                    $arPedidoDetalle->setHoraDesde($horaDesde);
                    $arPedidoDetalle->setHoraHasta($horaHasta);
                } else {
                    Mensajes::error("No existe el registro modalidad " . $row['codigo_modalidad_servicio_externo'] . " concepto = " . $row['codigo_concepto_servicio_fk']);
                    break 2;
                }

                $em->persist($arPedidoDetalle);
                $metadata = $em->getClassMetaData(get_class($arPedidoDetalle));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turPedidoDetalleCompuesto($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_pedido_detalle_compuesto_pk FROM tur_pedido_detalle_compuesto ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_pedido_detalle_compuesto_pk,
                    codigo_pedido_detalle_fk,
                    codigo_concepto_servicio_fk,
                    codigo_modalidad_servicio_fk,
                    tur_modalidad_servicio.codigo_externo as codigo_modalidad_servicio_externo,
                    codigo_periodo_fk,
                    dia_desde,
                    dia_hasta,
                    liquidar_dias_reales,
                    dias,
                    horas,
                    horas_diurnas,
                    horas_nocturnas,
                    cantidad,
                    vr_precio_ajustado,
                    vr_precio_minimo,
                    vr_precio,
                    vr_subtotal,  
                    vr_iva,                    
                    vr_base_aiu,       
                    vr_total_detalle,       
                    porcentaje_iva,   
                    lunes,
                    martes,
                    miercoles,
                    jueves,
                    viernes,
                    sabado,
                    domingo,
                    festivo,
                    detalle,
                    no_facturar             
                 FROM tur_pedido_detalle_compuesto 
                 left join tur_modalidad_servicio on codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk 
                 ORDER BY codigo_pedido_detalle_compuesto_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPedidoDetalleCompuesto = new TurPedidoDetalleCompuesto();
                $arPedidoDetalleCompuesto->setCodigoPedidoDetalleCompuestoPk($row['codigo_pedido_detalle_compuesto_pk']);
                $arPedidoDetalleCompuesto->setPedidoDetalleRel($em->getReference(TurPedidoDetalle::class, $row['codigo_pedido_detalle_fk']));
                $arPedidoDetalleCompuesto->setConceptoRel($em->getReference(TurConcepto::class, $row['codigo_concepto_servicio_fk']));
                $arPedidoDetalleCompuesto->setModalidadRel($em->getReference(TurModalidad::class, $row['codigo_modalidad_servicio_externo']));
                if ($row['codigo_periodo_fk'] == 1) {
                    $arPedidoDetalleCompuesto->setPeriodo("M");
                } else {
                    $arPedidoDetalleCompuesto->setPeriodo("D");
                }
                $arPedidoDetalleCompuesto->setDiaDesde(date_create($row['dia_desde']));
                $arPedidoDetalleCompuesto->setDiaHasta(date_create($row['dia_hasta']));
                $arPedidoDetalleCompuesto->setDias($row['dias']);
                $arPedidoDetalleCompuesto->setHoras($row['horas']);
                $arPedidoDetalleCompuesto->setHorasDiurnas($row['horas_diurnas']);
                $arPedidoDetalleCompuesto->setHorasNocturnas($row['horas_nocturnas']);
                $arPedidoDetalleCompuesto->setCantidad($row['cantidad']);
                $arPedidoDetalleCompuesto->setVrPrecioAjustado($row['vr_precio_ajustado']);
                $arPedidoDetalleCompuesto->setVrPrecioMinimo($row['vr_precio_minimo']);
                $arPedidoDetalleCompuesto->setVrPrecio($row['vr_precio']);
                $arPedidoDetalleCompuesto->setVrSubtotal($row['vr_subtotal']);
                $arPedidoDetalleCompuesto->setVrIva($row['vr_iva']);
                $arPedidoDetalleCompuesto->setVrBaseIva($row['vr_base_aiu']);
                $arPedidoDetalleCompuesto->setVrTotal($row['vr_total_detalle']);
                $arPedidoDetalleCompuesto->setLunes($row['lunes']);
                $arPedidoDetalleCompuesto->setMartes($row['martes']);
                $arPedidoDetalleCompuesto->setMiercoles($row['miercoles']);
                $arPedidoDetalleCompuesto->setJueves($row['jueves']);
                $arPedidoDetalleCompuesto->setViernes($row['viernes']);
                $arPedidoDetalleCompuesto->setSabado($row['sabado']);
                $arPedidoDetalleCompuesto->setDomingo($row['domingo']);
                $arPedidoDetalleCompuesto->setFestivo($row['festivo']);
                $arPedidoDetalleCompuesto->setPorcentajeIva($row['porcentaje_iva']);
                $arPedidoDetalleCompuesto->setCortesia($row['no_facturar']);

                $ar = $conn->query("SELECT codigo_pk, hora_inicio, hora_fin, horas, horas_diurnas, horas_nocturnas FROM temporal_conceptos WHERE modalidad='" . $row['codigo_modalidad_servicio_externo'] . "' AND codigo_concepto_fk = " . $row['codigo_concepto_servicio_fk']);
                $registro = $ar->fetch_assoc();
                if ($registro) {
                    $horaDesde = date_create($registro['hora_inicio']);
                    $horaHasta = date_create($registro['hora_fin']);
                    $arPedidoDetalleCompuesto->setHorasUnidad($registro['horas']);
                    $arPedidoDetalleCompuesto->setHorasDiurnasUnidad($registro['horas_diurnas']);
                    $arPedidoDetalleCompuesto->setHorasNocturnasUnidad($registro['horas_nocturnas']);
                    $arPedidoDetalleCompuesto->setHoraDesde($horaDesde);
                    $arPedidoDetalleCompuesto->setHoraHasta($horaHasta);
                } else {
                    Mensajes::error("No existe el registro modalidad " . $row['codigo_modalidad_servicio_externo'] . " concepto = " . $row['codigo_concepto_servicio_fk']);
                    break 2;
                }

                $em->persist($arPedidoDetalleCompuesto);
                $metadata = $em->getClassMetaData(get_class($arPedidoDetalleCompuesto));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turFactura($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_factura_pk FROM tur_factura ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                codigo_factura_pk,
                codigo_cliente_fk,  
                numero,              
                fecha,
                fecha_vence,
                soporte,
                plazo_pago,
                vr_total,
                vr_total_neto,
                vr_subtotal, 
                vr_iva,
                vr_base_aiu,
                vr_base_retencion_fuente,
                vr_retencion_fuente,
                vr_retencion_iva,
                vr_retencion_renta,
                vr_total,
                usuario,
                comentarios,
                estado_autorizado,
                estado_anulado,
                codigo_factura_tipo_fk
                 FROM tur_factura ORDER BY codigo_factura_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arFactura = new TurFactura();
                $arFactura->setCodigoFacturaPk($row['codigo_factura_pk']);
                $arFactura->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                $arFactura->setFacturaTipoRel($em->getReference(TurFacturaTipo::class, $row['codigo_factura_tipo_fk']));
                $arFactura->setFecha(date_create($row['fecha']));
                $arFactura->setFechaVence(date_create($row['fecha_vence']));
                $arFactura->setPlazoPago($row['plazo_pago']);
                $arFactura->setNumero($row['numero']);
                $arFactura->setVrIva($row['vr_iva']);
                $arFactura->setVrBaseAiu($row['vr_base_aiu']);
                $arFactura->setVrTotal($row['vr_total']);
                $arFactura->setVrNeto($row['vr_total_neto']);
                $arFactura->setVrSubtotal($row['vr_subtotal']);
                $arFactura->setVrRetencionFuente($row['vr_retencion_fuente']);
                $arFactura->setVrRetencionIva($row['vr_retencion_iva']);
                $arFactura->setUsuario($row['usuario']);
                $arFactura->setEstadoAutorizado($row['estado_autorizado']);
                if ($row['estado_autorizado'] == 1) {
                    $arFactura->setEstadoAprobado(1);
                }
                $arFactura->setEstadoAnulado($row['estado_anulado']);
                $arFactura->setComentarios(utf8_encode($row['comentarios']));

                $em->persist($arFactura);
                $metadata = $em->getClassMetaData(get_class($arFactura));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function turFacturaDetalle($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_factura_detalle_pk FROM tur_factura_detalle ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_factura_detalle_pk,
                    codigo_factura_fk,
                    tur_concepto_servicio.codigo_concepto_servicio_pk as codigo_concepto_servicio_fk,
                    codigo_pedido_detalle_fk,
                    cantidad,
                    tur_factura_detalle.por_iva,
                    iva,
                    vr_precio,
                    subtotal,
                    total,
                    codigo_grupo_facturacion_fk,
                    tur_modalidad_servicio.codigo_externo as codigo_modalidad_servicio_externo
                 FROM tur_factura_detalle
                 left join tur_concepto_servicio on codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk 
                 left join tur_modalidad_servicio on codigo_modalidad_servicio_fk = tur_modalidad_servicio.codigo_modalidad_servicio_pk 
                 ORDER BY codigo_factura_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arFacturaDetalle = new TurFacturaDetalle();
                $arFacturaDetalle->setCodigoFacturaDetallePk($row['codigo_factura_detalle_pk']);
                $arFacturaDetalle->setFacturaRel($em->getReference(TurFactura::class, $row['codigo_factura_fk']));
                //$arFacturaDetalle->setItemRel($em->getReference(TurItem::class, $row['codigo_concepto_servicio_fk']));
                if ($row['codigo_pedido_detalle_fk']) {
                    $arFacturaDetalle->setPedidoDetalleRel($em->getReference(TurPedidoDetalle::class, $row['codigo_pedido_detalle_fk']));
                } else {
                    $arFacturaDetalle->setPedidoDetalleRel(null);
                }
                if ($row['codigo_grupo_facturacion_fk']) {
                    $arFacturaDetalle->setGrupoRel($em->getReference(TurGrupo::class, $row['codigo_grupo_facturacion_fk']));
                }
                if ($row['codigo_modalidad_servicio_externo']) {
                    $arFacturaDetalle->setModalidadRel($em->getReference(TurModalidad::class, $row['codigo_modalidad_servicio_externo']));
                }
                $arFacturaDetalle->setCantidad($row['cantidad']);
                $arFacturaDetalle->setVrPrecio($row['vr_precio']);
                $arFacturaDetalle->setVrSubtotal($row['subtotal']);
                $arFacturaDetalle->setPorcentajeIva($row['por_iva']);
                $arFacturaDetalle->setVrIva($row['iva']);
                $arFacturaDetalle->setVrTotal($row['total']);

                $ar = $conn->query("SELECT codigo_pk, hora_inicio, hora_fin, horas, horas_diurnas, horas_nocturnas FROM temporal_conceptos WHERE modalidad='" . $row['codigo_modalidad_servicio_externo'] . "' AND codigo_concepto_fk = " . $row['codigo_concepto_servicio_fk']);
                $registro = $ar->fetch_assoc();
                if ($registro) {
                    $codigoItem = $registro['codigo_pk'];
                    if ($codigoItem) {
                        $arFacturaDetalle->setItemRel($em->getReference(TurItem::class, $codigoItem));
                    }
                } else {
                    Mensajes::error("No existe el registro modalidad " . $row['codigo_modalidad_servicio_externo'] . " concepto = " . $row['codigo_concepto_servicio_fk']);
                    break 2;
                }

                $em->persist($arFacturaDetalle);
                $metadata = $em->getClassMetaData(get_class($arFacturaDetalle));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }
    }

    private function turProgramacion($conn)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_programacion_detalle_pk FROM tur_programacion_detalle ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_programacion_detalle_pk,
                    codigo_recurso_fk,
                    codigo_puesto_fk,
                    codigo_pedido_detalle_fk,
                    anio,
                    mes,
                    dia_1,
                    dia_2,
                    dia_3,
                    dia_4,
                    dia_5,
                    dia_6,
                    dia_7,
                    dia_8,
                    dia_9,
                    dia_10,
                    dia_11,
                    dia_12,
                    dia_13,
                    dia_14,
                    dia_15,
                    dia_16,
                    dia_17,
                    dia_18,
                    dia_19,
                    dia_20,
                    dia_21,
                    dia_22,
                    dia_23,
                    dia_24,
                    dia_25,
                    dia_26,
                    dia_27,
                    dia_28,
                    dia_29,
                    dia_30,
                    dia_31,
                    horas,
                    horas_diurnas,
                    horas_nocturnas,
                    complementario,
                    adicional
                 FROM tur_programacion_detalle  ORDER BY codigo_programacion_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                if ($row['codigo_recurso_fk']) {
                    $arProgramacion = new TurProgramacion();
                    $arProgramacion->setCodigoProgramacionPk($row['codigo_programacion_detalle_pk']);
                    if ($row['codigo_recurso_fk']) {
                        $arProgramacion->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_recurso_fk']));
                    }
                    if ($row['codigo_pedido_detalle_fk']) {
                        $arProgramacion->setPedidoDetalleRel($em->getReference(TurPedidoDetalle::class, $row['codigo_pedido_detalle_fk']));
                    }
                    if ($row['codigo_puesto_fk']) {
                        $arProgramacion->setPuestoRel($em->getReference(TurPuesto::class, $row['codigo_puesto_fk']));
                    }
                    $arProgramacion->setAnio($row['anio']);
                    $arProgramacion->setMes($row['mes']);
                    $arProgramacion->setDia1($row['dia_1']);
                    $arProgramacion->setDia2($row['dia_2']);
                    $arProgramacion->setDia3($row['dia_3']);
                    $arProgramacion->setDia4($row['dia_4']);
                    $arProgramacion->setDia5($row['dia_5']);
                    $arProgramacion->setDia6($row['dia_6']);
                    $arProgramacion->setDia7($row['dia_7']);
                    $arProgramacion->setDia8($row['dia_8']);
                    $arProgramacion->setDia9($row['dia_9']);
                    $arProgramacion->setDia10($row['dia_10']);
                    $arProgramacion->setDia11($row['dia_11']);
                    $arProgramacion->setDia12($row['dia_12']);
                    $arProgramacion->setDia13($row['dia_13']);
                    $arProgramacion->setDia14($row['dia_14']);
                    $arProgramacion->setDia15($row['dia_15']);
                    $arProgramacion->setDia16($row['dia_16']);
                    $arProgramacion->setDia17($row['dia_17']);
                    $arProgramacion->setDia18($row['dia_18']);
                    $arProgramacion->setDia19($row['dia_19']);
                    $arProgramacion->setDia20($row['dia_20']);
                    $arProgramacion->setDia21($row['dia_21']);
                    $arProgramacion->setDia22($row['dia_22']);
                    $arProgramacion->setDia23($row['dia_23']);
                    $arProgramacion->setDia24($row['dia_24']);
                    $arProgramacion->setDia25($row['dia_25']);
                    $arProgramacion->setDia26($row['dia_26']);
                    $arProgramacion->setDia27($row['dia_27']);
                    $arProgramacion->setDia28($row['dia_28']);
                    $arProgramacion->setDia29($row['dia_29']);
                    $arProgramacion->setDia30($row['dia_30']);
                    $arProgramacion->setDia31($row['dia_31']);
                    $arProgramacion->setHoras($row['horas']);
                    $arProgramacion->setHorasDiurnas($row['horas_diurnas']);
                    $arProgramacion->setHorasNocturnas($row['horas_nocturnas']);
                    $arProgramacion->setComplementario($row['complementario']);
                    $arProgramacion->setAdicional($row['adicional']);
                    $em->persist($arProgramacion);
                    $metadata = $em->getClassMetaData(get_class($arProgramacion));
                    $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
                }
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function turPrototipo($conn)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $rango = 5000;
        $arr = $conn->query("SELECT codigo_servicio_detalle_recurso_pk FROM tur_servicio_detalle_recurso ");
        $registros = $arr->num_rows;
        $totalPaginas = $registros / $rango;
        for ($pagina = 0; $pagina <= $totalPaginas; $pagina++) {
            $lote = $pagina * $rango;
            $datos = $conn->query("SELECT
                    codigo_servicio_detalle_recurso_pk,
                    codigo_servicio_detalle_fk,
                    codigo_recurso_fk,
                    posicion,
                    codigo_secuencia_fk,
                    fecha_inicio_secuencia,
                    inicio_secuencia,
                    turno_a,
                    turno_b,
                    turno_c,
                    turno_d,
                    turno_e,
                    complementario
                 FROM tur_servicio_detalle_recurso  ORDER BY codigo_servicio_detalle_recurso_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPrototipo = new TurPrototipo();
                $arPrototipo->setCodigoPrototipoPk($row['codigo_servicio_detalle_recurso_pk']);
                if ($row['codigo_servicio_detalle_fk']) {
                    $arPrototipo->setContratoDetalleRel($em->getReference(TurContratoDetalle::class, $row['codigo_servicio_detalle_fk']));
                }
                if ($row['codigo_recurso_fk']) {
                    $arPrototipo->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_recurso_fk']));
                }
                if ($row['codigo_secuencia_fk']) {
                    $arPrototipo->setSecuenciaRel($em->getReference(TurSecuencia::class, $row['codigo_secuencia_fk']));
                }
                if ($row['fecha_inicio_secuencia']) {
                    $arPrototipo->setFechaInicioSecuencia(date_create($row['fecha_inicio_secuencia']));
                }
                $arPrototipo->setPosicion($row['posicion']);
                $arPrototipo->setInicioSecuencia($row['inicio_secuencia']);
                $arPrototipo->setTurnoA($row['turno_a']);
                $arPrototipo->setTurnoB($row['turno_b']);
                $arPrototipo->setTurnoC($row['turno_c']);
                $arPrototipo->setTurnoD($row['turno_d']);
                $arPrototipo->setTurnoE($row['turno_e']);
                $em->persist($arPrototipo);
                $metadata = $em->getClassMetaData(get_class($arPrototipo));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
            }
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function finCuenta($conn)
    {
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query('SELECT codigo_cuenta_pk, nombre_cuenta, codigo_cuenta_padre_fk, permite_movimientos, exige_centro_costos, exige_base, exige_nit, porcentaje_retencion FROM ctb_cuenta');
        foreach ($datos as $row) {
            $arCuenta = new FinCuenta();
            $arCuenta->setCodigoCuentaPk($row['codigo_cuenta_pk']);
            $arCuenta->setNombre(utf8_decode($row['nombre_cuenta']));
            $arCuenta->setCodigoCuentaPadreFk($row['codigo_cuenta_padre_fk']);
            $arCuenta->setPermiteMovimiento($row['permite_movimientos']);
            $arCuenta->setExigeCentroCosto($row['exige_centro_costos']);
            $arCuenta->setExigeBase($row['exige_base']);
            $arCuenta->setExigeTercero($row['exige_nit']);
            $arCuenta->setPorcentajeBaseRetencion($row['porcentaje_retencion']);
            $em->persist($arCuenta);
            $metadata = $em->getClassMetaData(get_class($arCuenta));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

        if ($em->flush()) {
            return true;
        } else {
            return false;
        }
    }

    private function usuarios($conn)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $datos = $conn->query('SELECT username, nombre_corto,numero_identificacion, cargo, is_active,roles,email,notificaciones_pendientes FROM users');
        foreach ($datos as $row) {
            $arUsuario = new Usuario();
            $arUsuario->setUsername(utf8_decode($row['username']));
            $arUsuario->setNombreCorto(utf8_decode($row['nombre_corto']));
            $arUsuario->setNumeroIdentificacion(utf8_decode($row['numero_identificacion']));
            $arUsuario->setCargo(utf8_decode($row['cargo']));
            $arUsuario->setPassword(utf8_decode(password_hash($row['username'], PASSWORD_BCRYPT)));
            $arUsuario->setEmail(utf8_decode($row['email']));
            $arUsuario->setIsActive($row['is_active']);
            $arUsuario->setRol($row['roles']);
            $arUsuario->setNotificacionesPendientes($row['notificaciones_pendientes']);
            $em->persist($arUsuario);
            $metadata = $em->getClassMetaData(get_class($arUsuario));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }

        if ($em->flush()) {
            return true;
        } else {
            return false;
        }
    }

    private function contarRegistros($entidad, $modulo, $key)
    {
        $em = $this->getDoctrine()->getManager();
        $repoArticles = $em->getRepository("App\\Entity\\{$modulo}\\{$entidad}");
        $totalArticles = $repoArticles->createQueryBuilder('e')
            ->select("count(e.$key)")
            ->getQuery()
            ->getSingleScalarResult();
        return $totalArticles;
    }

}



