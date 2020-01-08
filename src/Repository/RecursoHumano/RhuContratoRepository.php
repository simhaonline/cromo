<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Turno\Informe\Juridico\contratoController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuContratoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuContrato::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.numero')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('c.fecha')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.fechaUltimoPago')
            ->addSelect('c.fechaUltimoPagoCesantias')
            ->addSelect('c.fechaUltimoPagoPrimas')
            ->addSelect('c.fechaUltimoPagoVacaciones')
            ->addSelect('c.vrSalario')
            ->addSelect('c.vrDevengadoPactado')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('c.estadoTerminado')
            ->addSelect('c.ibpCesantiasInicial')
            ->addSelect('c.ibpPrimasInicial')
            ->addSelect('c.cargoDescripcion')
            ->addSelect('c.comentarioTerminacion')
            ->addSelect('c.salarioIntegral')
            ->addSelect('c.auxilioTransporte')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombre1')
            ->addSelect('e.nombre2')
            ->addSelect('e.apellido1')
            ->addSelect('e.apellido2')
            ->addSelect('ct.nombre AS tipo')
            ->addSelect('gp.nombre AS nombreGrupo')
            ->addSelect('t.nombre AS tiempo')
            ->addSelect('cr.nombre as riesgo')
            ->addSelect('ca.nombre as caja')
            ->addSelect('sa.nombre as salud')
            ->addSelect('pa.nombre as pension')
            ->addSelect('ec.nombre as cesantia')
            ->addSelect('dis.nombre as distribucion')
            ->addSelect('tc.nombre as cotizante')
            ->addSelect('stc.nombre as subCotizante')
            ->addSelect('st.nombre as salarioTipo')
            ->addSelect('cg.nombre as cargo')
            ->addSelect('ctr.nombre as centroTrabajo')
            ->addSelect('sex.nombre as sexo')
            ->addSelect('cc.nombre as ciudadContrato')
            ->addSelect('cm.motivo')
            ->addSelect('lc.estadoAprobado as liquidado')

            ->leftJoin('c.contratoTipoRel', 'ct')
            ->leftJoin('c.clasificacionRiesgoRel', 'cr')
            ->leftJoin('c.tiempoRel', 't')
            ->leftJoin('c.grupoRel', 'gp')
            ->leftJoin('c.cargoRel', 'cg')
            ->leftJoin('c.empleadoRel', 'e')
            ->leftJoin('e.sexoRel', 'sex')
            ->leftJoin('c.entidadCajaRel', 'ca')
            ->leftJoin('c.entidadSaludRel', 'sa')
            ->leftJoin('c.entidadPensionRel', 'pa')
            ->leftJoin('c.entidadCesantiaRel', 'ec')
            ->leftJoin('c.distribucionRel', 'dis')
            ->leftJoin('c.tipoCotizanteRel', 'tc')
            ->leftJoin('c.subtipoCotizanteRel', 'stc')
            ->leftJoin('c.salarioTipoRel', 'st')
            ->leftJoin('c.centroTrabajoRel', 'ctr')
            ->leftJoin('c.ciudadContratoRel', 'cc')
            ->leftJoin('c.contratoMotivoRel', 'cm')
            ->leftJoin('c.liquidacionesContratoRel', 'lc')

            ->andWhere('c.codigoContratoPk <> 0')
            ->orderBy('c.codigoContratoPk', 'ASC');
        if ($session->get('filtroRhuNombreEmpleado') != '') {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroRhuNombreEmpleado')}%' ");
        }
        if ($session->get('filtroRhuNumeroIdentificacionEmpleado') != '') {
            $queryBuilder->andWhere("e.numeroIdentificacion = {$session->get('filtroRhuNumeroIdentificacionEmpleado')} ");
        }
        if ($session->get('filtroRhuCodigoContrato') != '') {
            $queryBuilder->andWhere("c.codigoContratoPk = {$session->get('filtroRhuCodigoContrato')} ");
        }
        if ($session->get('filtroRhuGrupo')) {
            $queryBuilder->andWhere("c.codigoGrupoFk = '" . $session->get('filtroRhuGrupo') . "'");
        }
        if ($session->get('filtroRhuContratoTipo')) {
            $queryBuilder->andWhere("c.codigoContratoTipoFk = '" . $session->get('filtroRhuContratoTipo') . "'");
        }
        switch ($session->get('filtroRhuContratoEstadoTerminado')) {
            case '0':
                $queryBuilder->andWhere("c.estadoTerminado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoTerminado = 1");
                break;
        }

        if ($session->get('filtroRhuContratoFechaDesde')) {
            $queryBuilder->andWhere("c.fechaDesde >= '" . $session->get('filtroRhuContratoFechaDesde') . " 00:00:00'");
        }
        if ($session->get('filtroRhuContratoFechaHasta')) {
            $queryBuilder->andWhere("c.fechaHasta <= '" . $session->get('filtroRhuContratoFechaHasta') . " 23:59:59'");
        }

        return $queryBuilder->getQuery()->execute();
    }

    public function contratosEmpleado($codigoEmpleado)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.numero')
            ->addSelect('c.codigoGrupoFk')
            ->addSelect('c.codigoCostoClaseFk')
            ->addSelect('c.codigoClasificacionRiesgoFk')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.vrSalario')
            ->addSelect('c.auxilioTransporte')
            ->addSelect('cr.nombre')
            ->addSelect('cg.nombre as nombreCargo')
            ->addSelect('gp.nombre as nombreGrupo')
            ->addSelect('c.estadoTerminado')
            ->leftJoin('c.clasificacionRiesgoRel', 'cr')
            ->leftJoin('c.grupoRel', 'gp')
            ->leftJoin('c.cargoRel', 'cg')
            ->where('c.codigoEmpleadoFk = ' . $codigoEmpleado)
            ->andWhere('c.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function generarPago($codigoContrato)
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.vrSalario')
            ->where('c.codigoContratoPk = ' . $codigoContrato);
        return $queryBuilder->getQuery()->execute();
    }

    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 're')
            ->select('re.codigoContratoPk')
            ->addSelect('re.fechaDesde')
            ->addSelect('re.numero')
            ->addSelect('re.codigoGrupoFk')
            ->addSelect('re.codigoCargoFk')
            ->addSelect('re.codigoCostoTipoFk')
            ->addSelect('re.codigoClasificacionRiesgoFk')
            ->addSelect('re.fechaHasta')
            ->addSelect('re.vrSalario')
            ->addSelect('cr.nombre')
            ->addSelect('cg.nombre as nombreCargo')
            ->addSelect('gp.nombre as nombreGrupo')
            ->addSelect('re.estadoTerminado')
            ->leftJoin('re.clasificacionRiesgoRel', 'cr')
            ->leftJoin('re.grupoRel', 'gp')
            ->leftJoin('re.cargoRel', 'cg')
            ->where('re.codigoContratoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function contratosPeriodoAporte($fechaDesde = "", $fechaHasta = "", $codigoSucursal = null)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->leftJoin('c.empleadoRel', 'e')
            ->where("(c.fechaHasta >= '" . $fechaDesde . "' OR c.indefinido = 1) "
                . "AND c.fechaDesde <= '" . $fechaHasta . "' ")
            ->andWhere("c.codigoSucursalFk = '{$codigoSucursal}'");
        $arContratos = $queryBuilder->getQuery()->getResult();
        return $arContratos;
    }

    public function soportePago($codigoEmpleado, $fechaDesde, $fechaHasta, $contratoTerminado, $codigoGrupo)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuContrato::class, "c")
            ->select("c.codigoContratoPk")
            ->addSelect('c.vrSalarioPago')
            ->addSelect('c.vrDevengadoPactado')
            ->addSelect('c.estadoTerminado')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.codigoDistribucionFk')
            ->addSelect('c.auxilioTransporte')
            ->addSelect('c.turnoFijo')
            ->addSelect('c.indefinido')
            ->where("c.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("c.fechaUltimoPago < '{$fechaHasta}'")
            ->andWhere("c.fechaDesde <= '{$fechaHasta}'")
            ->andWhere("c.fechaHasta >= '{$fechaDesde}' OR c.indefinido = 1")
            ->andWhere("c.codigoGrupoFk = '{$codigoGrupo}'");
        if ($contratoTerminado) {
            $queryBuilder->andWhere("c.estadoTerminado = 1");
        }
        $arContratos = $queryBuilder->getQuery()->getResult();

        return $arContratos;
    }

    public function informeContrato()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c');
        return $queryBuilder;
    }

    public function ContratoIntercambio($codigoEmpleado)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuContrato::class, 'con')
            ->select('con.codigoContratoPk')
            ->addSelect('con.numero')
            ->addSelect('con.fechaDesde')
            ->addSelect('con.fechaHasta')
            ->addSelect('con.vrSalario')
            ->addSelect('em.codigoEmpleadoPk')
            ->addSelect('g.nombre as grupo')
            ->addSelect('t.nombre as tipo')
            ->addSelect('cl.nombre as clase')
            ->addSelect('car.nombre as cargo')
            ->leftJoin('con.empleadoRel', 'em')
            ->leftJoin('con.grupoRel', 'g')
            ->leftJoin('con.contratoTipoRel', 't')
            ->leftJoin('con.contratoClaseRel', 'cl')
            ->leftJoin('con.cargoRel', 'car')
            ->where("em.codigoEmpleadoPk = {$codigoEmpleado}");
        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function numtoletras($xcifra)
    {
        $em = $this->getEntityManager();
        $xarray = array(0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );
//
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
        $xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            } else {
                                $key = (int)substr($xaux, 0, 3);
                                if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = $em->getRepository(RhuContrato::class)->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100)
                                        $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int)substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {

                            } else {
                                $key = (int)substr($xaux, 1, 2);
                                if (TRUE === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = $em->getRepository(RhuContrato::class)->subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20)
                                        $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    $xy = 3;
                                } else {
                                    $key = (int)substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10)
                                        $xcadena = " " . $xcadena . " " . $xseek;
                                    else
                                        $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            } else {
                                $key = (int)substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = $em->getRepository(RhuContrato::class)->subfijo($xaux);
                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena .= " DE";

            if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                $xcadena .= " DE";

            // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
            if (trim($xaux) != "") {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena .= "UN BILLON ";
                        else
                            $xcadena .= " BILLONES ";
                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                            $xcadena .= "UN MILLON ";
                        else
                            $xcadena .= " MILLONES ";
                        break;
                    case 2:
                        if ($xcifra < 1) {
                            $xcadena = "CERO PESOS M/C";
                        }
                        if ($xcifra >= 1 && $xcifra < 2) {
                            $xcadena = "UN PESO M/C ";
                        }
                        if ($xcifra >= 2) {
                            $xcadena .= " PESOS M/C "; //
                        }
                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
            $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
            $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
            $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }

    public function subfijo($xx)
    { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    }

    public function informeContratoFechaIngreso($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $fechaDesde = null;
        $fechaHasta = null;


        if ($filtros) {
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select("c.codigoContratoPk")
            ->addSelect("c.fecha")
            ->addSelect("c.fechaDesde")
            ->addSelect("c.fechaHasta")
            ->addSelect("ct.nombre as tipo")
            ->addSelect("car.nombre as cargo")
            ->addSelect("e.nombreCorto as nombreEmpleado")
            ->addSelect("e.numeroIdentificacion as numeroIdentificacion")
            ->addSelect("gp.nombre as centroCosto")
            ->leftJoin("c.empleadoRel", "e")
            ->leftJoin("c.contratoTipoRel", "ct")
            ->leftJoin("c.cargoRel", "car")
            ->leftJoin("c.centroCostoRel", "gp");
        if ($fechaDesde) {
            $queryBuilder->andWhere("c.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("c.fecha <= '{$fechaHasta} 23:59:59'");
        }
        $queryBuilder->addOrderBy('c.codigoContratoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function informeVacacionesPendiente($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $fechaDesde = null;
        $fechaHasta = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
        }
        $fechaActual = new \DateTime('now');

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('c.codigoContratoPk')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('c.fecha')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.fechaUltimoPagoVacaciones')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('e.numeroIdentificacion')
            ->leftJoin('c.empleadoRel', 'e')
            ->where("c.fechaUltimoPagoVacaciones <= '{$fechaActual->format('Y-m-d')} 23:59:59' ");
        if ($codigoCliente) {
            $queryBuilder->andWhere("c.codigoClienteFk = '{$codigoCliente}'");
        }
        
        if ($fechaDesde) {
            $queryBuilder->andWhere("c.fechaDesde >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("c.fechaHasta <= '{$fechaHasta} 23:59:59'");
        }

        $queryBuilder->addOrderBy('c.codigoContratoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }
}