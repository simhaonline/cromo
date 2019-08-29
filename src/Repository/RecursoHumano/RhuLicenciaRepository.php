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

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuLicencia::class, 'l')
            ->select('l.codigoLicenciaPk')
            ->addSelect('l.fecha')
            ->addSelect('lt.nombre as licenciaTipo')
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

        if($session->get('filtroRhuLicenciaCodigoEmpleado')){
            $queryBuilder->andWhere("l.codigoEmpleadoFk = {$session->get('filtroRhuLicenciaCodigoEmpleado')}");
        }
        if($session->get('filtroRhuLicenciaLiencenciaTipo')){
            $queryBuilder->andWhere("l.codigoLicenciaTipoFk = '{$session->get('filtroRhuLicenciaLiencenciaTipo')}' ");
        }
        if($session->get('filtroRhuLicenciaCodigoGrupo')){
            $queryBuilder->andWhere("l.codigoGrupoFk = '{$session->get('filtroRhuLicenciaCodigoGrupo')}' ");
        }
        if ($session->get('filtroRhuLicenciaFechaDesde') != null) {
            $queryBuilder->andWhere("l.fechaDesde >= '{$session->get('filtroRhuLicenciaFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuLicenciaFechaHasta') != null) {
            $queryBuilder->andWhere("l.fechaHasta <= '{$session->get('filtroRhuLicenciaFechaHasta')} 23:59:59'");
        }
        $queryBuilder->orderBy('l.codigoLicenciaPk', 'DESC');
        return $queryBuilder;

    }

    public function validarFecha($fechaDesde, $fechaHasta, $codigoEmpleado, $codigoLicencia = ""):bool
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
            return  FALSE;
        } else {
            return  TRUE;
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

}