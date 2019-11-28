<?php

namespace App\Controller\General\Prueba;

use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenCiudad;
use App\Entity\General\GenDepartamento;
use App\Entity\General\GenEstadoCivil;
use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenPais;
use App\Entity\General\GenSexo;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuCargoSupervigilancia;
use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoClase;
use App\Entity\RecursoHumano\RhuContratoMotivo;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use App\Entity\RecursoHumano\RhuEmbargoTipo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
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
use App\Entity\RecursoHumano\RhuSalud;
use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use App\Entity\RecursoHumano\RhuTiempo;
use App\Entity\RecursoHumano\RhuTipoCotizante;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use App\Entity\Seguridad\Usuario;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurContratoDetalleCompuesto;
use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurItem;
use App\Entity\Turno\TurModalidad;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoDetalleCompuesto;
use App\Entity\Turno\TurPedidoTipo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSecuencia;
use App\Entity\Turno\TurTurno;
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

            if($servername && $database && $username && $password) {
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
                        case 'rhu_empleado':
                            $this->rhuEmpleado($conn);
                            break;
                        case 'rhu_contrato':
                            $this->rhuContrato($conn);
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
                        case 'rhu_incapacidad':
                            $this->rhuIncapacidad($conn);
                            break;
                        case 'rhu_licencia':
                            $this->rhuLicencia($conn);
                            break;
                        case 'tur_cliente':
                            $this->turCliente($conn);
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
            ['clase' => 'rhu_empleado',         'registros' => $this->contarRegistros('RhuEmpleado','RecursoHumano', 'codigoEmpleadoPk')],
            ['clase' => 'rhu_contrato',         'registros' => $this->contarRegistros('RhuContrato','RecursoHumano', 'codigoContratoPk')],
            ['clase' => 'rhu_adicional',        'registros' => $this->contarRegistros('RhuAdicional','RecursoHumano', 'codigoAdicionalPk')],
            ['clase' => 'rhu_embargo',          'registros' => $this->contarRegistros('RhuEmbargo','RecursoHumano', 'codigoEmbargoPk')],
            ['clase' => 'rhu_credito',          'registros' => $this->contarRegistros('RhuCredito','RecursoHumano', 'codigoCreditoPk')],
            ['clase' => 'rhu_credito_pago',     'registros' => $this->contarRegistros('RhuCreditoPago','RecursoHumano', 'codigoCreditoPagoPk')],
            ['clase' => 'rhu_vacacion',         'registros' => $this->contarRegistros('RhuVacacion','RecursoHumano', 'codigoVacacionPk')],
            ['clase' => 'rhu_liquidacion',      'registros' => $this->contarRegistros('RhuLiquidacion','RecursoHumano', 'codigoLiquidacionPk')],
            ['clase' => 'rhu_pago',             'registros' => $this->contarRegistros('RhuPago','RecursoHumano', 'codigoPagoPk')],
            ['clase' => 'rhu_pago_detalle',     'registros' => $this->contarRegistros('RhuPagoDetalle','RecursoHumano', 'codigoPagoDetallePk')],
            ['clase' => 'rhu_incapacidad',      'registros' => $this->contarRegistros('RhuIncapacidad','RecursoHumano', 'codigoIncapacidadPk')],
            ['clase' => 'rhu_licencia',         'registros' => $this->contarRegistros('RhuLicencia','RecursoHumano', 'codigoLicenciaPk')],
            ['clase' => 'tur_cliente',          'registros' => $this->contarRegistros('TurCliente','Turno', 'codigoClientePk')],
            ['clase' => 'tur_puesto',           'registros' => $this->contarRegistros('TurPuesto','Turno', 'codigoPuestoPk')],
            ['clase' => 'tur_contrato',         'registros' => $this->contarRegistros('TurContrato','Turno', 'codigoContratoPk')],
            ['clase' => 'tur_contrato_detalle', 'registros' => $this->contarRegistros('TurContratoDetalle','Turno', 'codigoContratoDetallePk')],
            ['clase' => 'tur_contrato_detalle_compuesto',   'registros' => $this->contarRegistros('TurContratoDetalleCompuesto','Turno', 'codigoContratoDetalleCompuestoPk')],
            ['clase' => 'tur_pedido',           'registros' => $this->contarRegistros('TurPedido','Turno', 'codigoPedidoPk')],
            ['clase' => 'tur_pedido_detalle',   'registros' => $this->contarRegistros('TurPedidoDetalle','Turno', 'codigoPedidoDetallePk')],
            ['clase' => 'tur_pedido_detalle_compuesto',   'registros' => $this->contarRegistros('TurPedidoDetalleCompuesto','Turno', 'codigoPedidoDetalleCompuestoPk')],
            ['clase' => 'tur_factura',          'registros' => $this->contarRegistros('TurFactura','Turno', 'codigoFacturaPk')],
            ['clase' => 'tur_factura_detalle',  'registros' => $this->contarRegistros('TurFacturaDetalle','Turno', 'codigoFacturaDetallePk')],
            ['clase' => 'tur_programacion',     'registros' => $this->contarRegistros('TurProgramacion','Turno', 'codigoProgramacionPk')],
            ['clase' => 'usuarios',              'registros' => $this->contarRegistros('Usuario','Seguridad', 'username')]
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
                /*codigo_empleado_tipo_fk,*/
                codigo_contrato_activo_fk,
                codigo_contrato_ultimo_fk,
                /*codigo_cuenta_tipo_fk,*/
                numero_identificacion,
                discapacidad,
                estado_contrato_activo, /*aparece con el nombre de estado_contracto en cromo*/
                /*carro,
                moto,*/
                padre_familia,
                cabeza_hogar,
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
                /*codigo_banco_fk,*/
                camisa, /*aparece con el nombre de talla_camisa en cromo*/
                jeans, /*aparece con el nombre de talla_pantalon en cromo*/
                calzado, /*aparece con el nombre de talla_calzado en cromo*/
                estatura,
                peso, 
                pagado_entidad_salud
                /*codigo_cargo_fk*/ FROM rhu_empleado 
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
                $arEmpleado->setCiudadNacimientoRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_nacimiento_fk']));
                $arEmpleado->setEstadoCivilRel($em->getReference(GenEstadoCivil::class, $row['codigo_estado_civil_fk']));
                $arEmpleado->setCuenta($row['cuenta']);
                $arEmpleado->setTallaCamisa($row['camisa']);
                $arEmpleado->setTallaPantalon($row['jeans']);
                $arEmpleado->setTallaCalzado($row['calzado']);
                $arEmpleado->setEstatura($row['estatura']);
                $arEmpleado->setPeso($row['peso']);
                $arEmpleado->setPagadoEntidad($row['pagado_entidad_salud']);
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
                    codigo_contrato_pk,
                    rhu_contrato_tipo.codigo_externo as codigo_contrato_tipo_externo,
                    rhu_contrato_clase.codigo_externo as codigo_contrato_clase_externo,
                    rhu_clasificacion_riesgo.codigo_externo as codigo_clasificacion_riesgo_externo,
                    rhu_motivo_terminacion_contrato.codigo_externo as codigo_motivo_terminacion_externo,                    
                    fecha,
                    fecha_desde,
                    fecha_hasta,
                    rhu_tipo_tiempo.codigo_externo as codigo_tipo_tiempo_externo,
                    rhu_contrato.factor_horas_dia,
                    rhu_tipo_pension.codigo_externo as codigo_tipo_pension_externo,
                    rhu_tipo_salud.codigo_externo as codigo_tipo_salud_externo,
                    codigo_empleado_fk,
                    numero,
                    codigo_cargo_fk,
                    cargo_descripcion,
                    vr_salario,
                    vr_salario_pago,
                    vr_devengado_pactado,
                    estado_terminado,
                    rhu_contrato.indefinido,
                    comentarios_terminacion,
                    codigo_centro_costo_fk,
                    fecha_ultimo_pago_cesantias,
                    fecha_ultimo_pago_vacaciones,
                    fecha_ultimo_pago_primas,
                    fecha_ultimo_pago,
                    codigo_tipo_cotizante_fk,
                    codigo_subtipo_cotizante_fk,
                    salario_integral,
                    /*costo_tipo_fk,*/
                    rhu_entidad_salud.codigo_interface as codigo_entidad_salud_externo,
                    rhu_entidad_pension.codigo_interface as codigo_entidad_pension_externo,
                    rhu_entidad_cesantia.codigo_externo as codigo_entidad_cesantia_externo,
                    rhu_entidad_caja.codigo_interface as codigo_entidad_caja_externo,
                    codigo_ciudad_contrato_fk,
                    codigo_ciudad_labora_fk,
                    codigo_centro_trabajo_fk,
                    auxilio_transporte,
                    turno_fijo_ordinario
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
                  ORDER BY codigo_contrato_pk limit {$lote},{$rango}");
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
            $arEmbargo->setTipoCuenta($row['tipo_cuenta']);
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
            if($row['codigo_contrato_motivo_externo']) {
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
            $arLiquidacion->setEstadoAprobado(1);
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
                $arPago->setCodigoPeriodoFk($row['codigo_periodo_pago_fk']);
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
                if($row['codigo_contrato_fk']) {
                    $arIncapacidad->setContratoRel($em->getReference(RhuContrato::class, $row['codigo_contrato_fk']));
                }
                $arIncapacidad->getNumero($row['numero']);
                $arIncapacidad->setNumeroEps($row['numero_eps']);
                $arIncapacidad->setFecha(date_create($row['fecha']));
                $arIncapacidad->setFechaDesde(date_create($row['fecha_desde']));
                $arIncapacidad->setFechaHasta(date_create($row['fecha_hasta']));

                if($row['codigo_centro_costo_fk']) {
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
                if($row['fecha_desde_entidad'] == null) {
                    $arIncapacidad->setFechaDesdeEntidad(null);
                } else {
                    $arIncapacidad->setFechaDesdeEntidad(date_create($row['fecha_desde_entidad']));
                }
                if($row['fecha_hasta_entidad'] == null) {
                    $arIncapacidad->setFechaHastaEntidad(null);
                } else {
                    $arIncapacidad->setFechaHastaEntidad(date_create($row['fecha_hasta_entidad']));
                }


                $arIncapacidad->setVrPropuesto($row['vr_propuesto']);
                $arIncapacidad->setVrHoraEmpresa($row['vr_hora_empresa']);
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
                telefono
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
                codigo_ciudad_fk
                 FROM tur_puesto 
                 ORDER BY codigo_puesto_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arPuesto = new TurPuesto();
                $arPuesto->setCodigoPuestoPk($row['codigo_puesto_pk']);
                $arPuesto->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
                $arPuesto->setNombre(utf8_encode($row['nombre']));
                $arPuesto->setDireccion(utf8_encode($row['direccion']));
                $arPuesto->setTelefono(utf8_encode($row['telefono']));
                $arPuesto->setCelular(utf8_encode($row['celular']));
                $arPuesto->setContacto(utf8_encode($row['contacto']));
                $arPuesto->setCiudadRel($em->getReference(GenCiudad::class, $row['codigo_ciudad_fk']));
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
                horas,
                horas_diurnas,
                horas_nocturnas,
                por_iva
                 FROM tur_concepto_servicio");
        foreach ($datos as $row) {
            $arConcepto = new TurConcepto();
            $arConcepto->setCodigoConceptoPk($row['codigo_concepto_servicio_pk']);
            $arConcepto->setNombre(utf8_encode($row['nombre']));
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
                $arContrato->setHoras($row['horas']);
                $arContrato->setHorasDiurnas($row['horas_diurnas']);
                $arContrato->setHorasNocturnas($row['horas_nocturnas']);
                $arContrato->setVrTotalServicio($row['vr_total_servicio']);
                $arContrato->setVrTotalPrecioAjustado($row['vr_total_precio_ajustado']);
                $arContrato->setVrTotalPrecioMinimo($row['vr_total_precio_minimo']);
                $arContrato->setVrSubtotal($row['vr_subtotal']);
                $arContrato->setVrIva($row['vr_iva']);
                $arContrato->setVrBaseAiu($row['vr_base_aiu']);
                $arContrato->setVrTotal($row['vr_total']);
                $arContrato->setUsuario($row['usuario']);
                $arContrato->setComentarios(utf8_encode($row['comentarios']));
                $arContrato->setVrSalarioBase($row['vr_salario_base']);
                $arContrato->setEstrato($row['estrato']);
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
                    liquidar_dias_reales,
                    dias,
                    horas,
                    horas_diurnas,
                    horas_nocturnas,
                    cantidad,
                    vr_precio_minimo,
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
                    porcentaje_base_iva
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
                $arContratoDetalle->setFechaDesde(date_create($row['fecha_desde']));
                $arContratoDetalle->setFechaHasta(date_create($row['fecha_hasta']));
                $arContratoDetalle->setLiquidarDiasReales($row['liquidar_dias_reales']);
                $arContratoDetalle->setDias($row['dias']);
                $arContratoDetalle->setHoras($row['horas']);
                $arContratoDetalle->setHorasDiurnas($row['horas_diurnas']);
                $arContratoDetalle->setHorasNocturnas($row['horas_nocturnas']);
                $arContratoDetalle->setCantidad($row['cantidad']);
                $arContratoDetalle->setVrPrecioMinimo($row['vr_precio_minimo']);
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
                $arContratoDetalle->setEstadoCerrado($row['estado_cerrado']);
                $arContratoDetalle->setVrSalarioBase($row['vr_salario_base']);
                $arContratoDetalle->setPorcentajeIva($row['porcentaje_iva']);
                $arContratoDetalle->setPorcentajeBaseIva($row['porcentaje_base_iva']);
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
                    estado_programado
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
                $arPedidoDetalle->setAnio($row['anio']);
                $arPedidoDetalle->setMes($row['mes']);
                $arPedidoDetalle->setDiaDesde(date_create($row['dia_desde']));
                $arPedidoDetalle->setDiaHasta(date_create($row['dia_hasta']));
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
                estado_autorizado
                 FROM tur_factura ORDER BY codigo_factura_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arFactura = new TurFactura();
                $arFactura->setCodigoFacturaPk($row['codigo_factura_pk']);
                $arFactura->setClienteRel($em->getReference(TurCliente::class, $row['codigo_cliente_fk']));
//            $arFactura->setSectorRel($em->getReference(TurSector::class, $row['codigo_sector_externo']));
//            $arFactura->setPedidoTipoRel($em->getReference(TurPedidoTipo::class, 'CON'));
                $arFactura->setFecha(date_create($row['fecha']));
                $arFactura->setFechaVence(date_create($row['fecha_vence']));
                $arFactura->setPlazoPago($row['plazo_pago']);
                $arFactura->setNumero($row['numero']);
                $arFactura->setVrIva($row['vr_iva']);
                $arFactura->setVrTotal($row['vr_total']);
                $arFactura->setVrNeto($row['vr_total_neto']);
                $arFactura->setVrSubtotal($row['vr_subtotal']);
                $arFactura->setVrRetencionFuente($row['vr_retencion_fuente']);
                $arFactura->setVrRetencionIva($row['vr_retencion_iva']);
                $arFactura->setUsuario($row['usuario']);
                $arFactura->setEstadoAutorizado($row['estado_autorizado']);
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
                    total
                 FROM tur_factura_detalle
                 left join tur_concepto_servicio on codigo_concepto_servicio_fk = tur_concepto_servicio.codigo_concepto_servicio_pk 
                 ORDER BY codigo_factura_detalle_pk limit {$lote},{$rango}");
            foreach ($datos as $row) {
                $arFacturaDetalle = new TurFacturaDetalle();
                $arFacturaDetalle->setCodigoFacturaDetallePk($row['codigo_factura_detalle_pk']);
                $arFacturaDetalle->setFacturaRel($em->getReference(TurFactura::class, $row['codigo_factura_fk']));
                $arFacturaDetalle->setItemRel($em->getReference(TurItem::class, $row['codigo_concepto_servicio_fk']));
                if ($row['codigo_pedido_detalle_fk']) {
                    $arFacturaDetalle->setPedidoDetalleRel($em->getReference(TurPedidoDetalle::class, $row['codigo_pedido_detalle_fk']));
                } else {
                    $arFacturaDetalle->setPedidoDetalleRel(null);
                }
                $arFacturaDetalle->setCantidad($row['cantidad']);
                $arFacturaDetalle->setVrPrecio($row['vr_precio']);
                $arFacturaDetalle->setVrSubtotal($row['subtotal']);
                $arFacturaDetalle->setPorcentajeIva($row['por_iva']);
                $arFacturaDetalle->setVrIva($row['iva']);
                $arFacturaDetalle->setVrTotal($row['total']);
                //$arFacturaDetalle->setVrNeto($row['total']);
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
        $arr = $conn->query("SELECT codigo_pago_detalle_pk FROM rhu_pago_detalle ");
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
                $arProgramacion = new TurProgramacion();
                $arProgramacion->setCodigoProgramacionPk($row['codigo_programacion_detalle_pk']);
                if ($row['codigo_recurso_fk']) {
                    $arProgramacion->setEmpleadoRel($em->getReference(RhuEmpleado::class, $row['codigo_recurso_fk']));
                }
                if($row['codigo_pedido_detalle_fk']) {
                    $arProgramacion->setPedidoDetalleRel($em->getReference(TurPedidoDetalle::class, $row['codigo_pedido_detalle_fk']));
                }
                if($row['codigo_puesto_fk']) {
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
            $em->flush();
            $em->clear();
            $datos->free();
            ob_clean();
        }

    }

    private function finCuenta($conn){
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

    private function usuarios ($conn){
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

    private function contarRegistros($entidad, $modulo, $key) {
        $em = $this->getDoctrine()->getManager();
        $repoArticles = $em->getRepository("App\\Entity\\{$modulo}\\{$entidad}");
        $totalArticles = $repoArticles->createQueryBuilder('e')
            ->select("count(e.$key)")
            ->getQuery()
            ->getSingleScalarResult();
        return $totalArticles;
    }

}
