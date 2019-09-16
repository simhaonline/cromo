<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuLicenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLicencia::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoLicencia = null;
        $licenciaTipo = null;
        $grupo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;

        if ($filtros) {
            $codigoLicencia = $filtros['codigoLicencia'] ?? null;
            $licenciaTipo = $filtros['licenciaTipo'] ?? null;
            $grupo = $filtros['grupo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuLicencia::class, 'l')
            ->select('l.codigoLicenciaPk')
            ->addSelect('l.fecha')
            ->addSelect('lt.nombre as tipo')
            ->addSelect('es.nombre as entidadSalud')
            ->addSelect('em.nombreCorto as empleado')
            ->addSelect('em.numeroIdentificacion')
            ->addSelect('g.nombre as grupo')
            ->addSelect('l.fechaDesde')
            ->addSelect('l.fechaHasta')
            ->addSelect('l.vrLicencia')
            ->addSelect('l.vrCobro')
            ->addSelect('l.diasIbcMesAnterior')
            ->addSelect('l.vrIbcPropuesto')
            ->addSelect('l.afectaTransporte')
            ->addSelect('l.estadoCobrar')
            ->addSelect('l.pagarEmpleado')
            ->addSelect('l.estadoProrroga')
            ->addSelect('l.estadoTranscripcion')
            ->addSelect('l.codigoUsuario')
            ->addSelect('l.cantidad')
            ->leftJoin('l.licenciaTipoRel', 'lt')
            ->leftJoin('l.entidadSaludRel', 'es')
            ->leftJoin('l.empleadoRel', 'em')
            ->leftJoin('l.grupoRel', 'g');
        if ($codigoLicencia) {
            $queryBuilder->andWhere("l.codigoLicenciaPk = '{$codigoLicencia}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("l.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($licenciaTipo) {
            $queryBuilder->andWhere("l.codigoLicenciaTipoFk = '{$licenciaTipo}'");
        }
        if ($grupo) {
            $queryBuilder->andWhere("l.codigoGrupoFk = '{$grupo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("l.fechaDesde >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("l.fechaHasta <= '{$fechaHasta} 23:59:59'");
        }
        $queryBuilder->addOrderBy('l.fecha', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoLicencia = ""): bool
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM App\Entity\RecursoHumano\RhuLicencia licencia "
            . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
            . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) "
            . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        if ($codigoLicencia != "") {
            $dql = $dql . "AND licencia.codigoLicenciaPk <> " . $codigoLicencia;
        }
        $objQuery = $em->createQuery($dql);
        $arLicencias = $objQuery->getResult();
        if (count($arLicencias) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function licenciasPerido($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(RhuLicencia::class, "lc")
            ->select("lc")
            ->join("lc.licenciaTipoRel", "lt")
            ->where("lc.fechaDesde <= '{$strFechaHasta}' AND  lc.fechaHasta >= '{$strFechaHasta}'")
            ->orWhere("lc.fechaDesde <= '{$strFechaDesde}' AND  lc.fechaHasta >='{$strFechaDesde}' AND lc.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->orWhere("lc.fechaDesde >= '{$strFechaDesde}' AND  lc.fechaHasta <='{$strFechaHasta}' AND lc.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("lc.codigoEmpleadoFk = '{$codigoEmpleado}'");

        $arLicencias = $qb->getQuery()->getResult();
        $arrLicencias = [0 => ["licenciaNoRemunerada" => 0], 1 => ["licencia" => 0]];
        $intDiasLicenciaNoRemunerada = 0;
        $intDiasLicenciaRemunerada = 0;
        foreach ($arLicencias as $arLicencia) {
            $intDiaInicio = 1;
            $intDiaFin = 30;
            if ($arLicencia->getFechaDesde() < $fechaDesde) {
                $intDiaInicio = $fechaDesde->format('j');
                $dateFechaDesde = $fechaDesde;
            } else {
                $intDiaInicio = $arLicencia->getFechaDesde()->format('j');
                $dateFechaDesde = $arLicencia->getFechaDesde();
            }
            if ($arLicencia->getFechaHasta() > $fechaHasta) {
                $intDiaFin = $fechaHasta->format('j');
                $dateFechaHasta = $fechaHasta;
            } else {
                $intDiaFin = $arLicencia->getFechaHasta()->format('j');
                $dateFechaHasta = $arLicencia->getFechaHasta();
            }
            if ($arLicencia->getLicenciaTipoRel()->getSuspensionContratoTrabajo() || $arLicencia->getLicenciaTipoRel()->getAusentismo()) {
                $tipo = "licenciaNoRemunerada";
                $arrLicencias[0] = [$tipo => $intDiasLicenciaNoRemunerada += (($intDiaFin - $intDiaInicio) + 1)];
            }
            if ($arLicencia->getLicenciaTipoRel()->getRemunerada() || $arLicencia->getLicenciaTipoRel()->getPaternidad() || $arLicencia->getLicenciaTipoRel()->getMaternidad()) {
                $tipo = "licencia";
                $arrLicencias[1] = [$tipo => $intDiasLicenciaRemunerada += (($intDiaFin - $intDiaInicio) + 1)];
            }

        }
        return $arrLicencias;


    }

    public function diasProgramacion($codigoEmpleado, $codigoContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuLicencia::class, 'l')
            ->select('l.codigoLicenciaPk')
            ->addSelect('l.fechaDesde')
            ->addSelect('l.fechaHasta')
            ->andWhere("(((l.fechaDesde BETWEEN '$fechaDesde' AND '$fechaHasta') OR (l.fechaHasta BETWEEN '$fechaDesde' AND '$fechaHasta')) "
                . "OR (l.fechaDesde >= '$fechaDesde' AND l.fechaDesde <= '$fechaHasta') "
                . "OR (l.fechaHasta >= '$fechaHasta' AND l.fechaDesde <= '$fechaDesde')) "
                . "AND l.codigoEmpleadoFk = '" . $codigoEmpleado . "' AND l.cantidad > 0")
            ->andWhere('l.pagarEmpleado = 1');
        if ($codigoContrato) {
            $query->andWhere("l.codigoContratoFk = " . $codigoContrato);
        }
        $arLicencias = $query->getQuery()->getResult();
        $intDiasLicencia = 0;
        foreach ($arLicencias as $arLicencia) {
            $dateFechaDesde = date_create($fechaDesde);
            $dateFechaHasta = date_create($fechaHasta);
            if ($arLicencia['fechaDesde'] < $dateFechaDesde) {
                $dateFechaDesde = $dateFechaDesde;
            } else {
                $dateFechaDesde = $arLicencia['fechaDesde'];
            }

            if ($arLicencia['fechaHasta'] > $dateFechaHasta) {
                $dateFechaHasta = $dateFechaHasta;
            } else {
                $dateFechaHasta = $arLicencia['fechaHasta'];
            }
            $intDias = $dateFechaDesde->diff($dateFechaHasta);
            $intDias = $intDias->format('%a');
            $intDias += 1;
            $intDiasLicencia += $intDias;
        }
        $arrDevolver = array('dias' => $intDiasLicencia);
        return $arrDevolver;
    }

    public function listaLicenciasMes($fechaDesde, $fechaHasta, $codigoEmpleado)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(RhuLicencia::class, "lc")
            ->select("lc.codigoLicenciaPk, lc.fechaDesde, lc.fechaHasta")
            ->where("lc.fechaDesde <= '{$fechaHasta->format('Y-m-d')}' AND  lc.fechaHasta >= '{$fechaHasta->format('Y-m-d')}'")
            ->orWhere("lc.fechaDesde <= '{$fechaDesde->format('Y-m-d')}' AND  lc.fechaHasta >='{$fechaDesde->format('Y-m-d')}' AND lc.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->orWhere("lc.fechaDesde >= '{$fechaDesde->format('Y-m-d')}' AND  lc.fechaHasta <='{$fechaHasta->format('Y-m-d')}' AND lc.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("lc.codigoEmpleadoFk = '{$codigoEmpleado}'");

        $arrLicenciasEmpleado = $qb->getQuery()->execute();
        return $arrLicenciasEmpleado;
    }

    public function periodo($fechaDesde, $fechaHasta, $codigoEmpleado = "", $codigoGrupo = "")
    {
        $em = $this->getEntityManager();
        $strFechaDesde = $fechaDesde->format('Y-m-d');
        $strFechaHasta = $fechaHasta->format('Y-m-d');
        $dql = "SELECT licencia FROM App\Entity\RecursoHumano\RhuLicencia licencia "
            . "WHERE (((licencia.fechaDesde BETWEEN '$strFechaDesde' AND '$strFechaHasta') OR (licencia.fechaHasta BETWEEN '$strFechaDesde' AND '$strFechaHasta')) "
            . "OR (licencia.fechaDesde >= '$strFechaDesde' AND licencia.fechaDesde <= '$strFechaHasta') "
            . "OR (licencia.fechaHasta >= '$strFechaHasta' AND licencia.fechaDesde <= '$strFechaDesde')) ";
        if ($codigoEmpleado != "") {
            $dql = $dql . "AND licencia.codigoEmpleadoFk = '" . $codigoEmpleado . "' ";
        }
        if ($codigoGrupo != "") {
            $dql = $dql . "AND licencia.codigoGrupoFk = " . $codigoGrupo . " ";
        }

        $objQuery = $em->createQuery($dql);
        $arLicencias = $objQuery->getResult();
        return $arLicencias;
    }

}