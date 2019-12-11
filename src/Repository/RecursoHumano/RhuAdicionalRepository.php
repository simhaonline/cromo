<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\Turno\TurPedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAdicionalRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuAdicional::class);
    }

    public function eliminar($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuAdicional::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    //Este metodo fue migrado y solo es un ejemplo
    public function programacionPagoEjemplo($codigoEmpleado = "", $fechaDesde, $fechaHasta, $tipoPago = 0, $aplicarAdicionalPermanente = false, $aplicarAdicional = false, $codigoContrato = "")
    {
        $em = $this->getEntityManager();
        $dql = "SELECT a "
            . "FROM App\Entity\Inventario\InvMovimientoDetalle a "
            . "WHERE a.estadoInactivo = 0 AND (a.estadoInactivoPeriodo = 0 OR a.estadoInactivoPeriodo IS NULL) "
            . "AND a.codigoEmpleadoFk = $codigoEmpleado AND (a.codigoContratoFk = '$codigoContrato' OR a.codigoContratoFk IS NULL)";

        if ($aplicarAdicional && $aplicarAdicionalPermanente) {
            $dql .= " AND (((a.fecha >= '$fechaDesde' AND a.fecha <= '$fechaHasta') AND a.modalidad = 2) OR a.modalidad = 1) ";
        } else {
            if (!$aplicarAdicional && $aplicarAdicionalPermanente) {
                $dql .= " AND a.modalidad = 1 ";
            } else {
                $dql .= " AND ((a.fecha >= '$fechaDesde' AND a.fecha <= '$fechaHasta') AND a.modalidad = 2) ";
            }
        }
        if ($tipoPago == 'NOM') {
            $dql .= " AND (a.aplicaPrima = 0 AND a.aplicaCesantia = 0)";
        }
        if ($tipoPago == 'PRI') {
            $dql .= " AND a.aplicaPrima = 1";
        }
        if ($tipoPago == 'CES') {
            $dql .= " AND a.aplicaCesantia = 1";
        }
        $objQuery = $em->createQuery($dql);
        $arPagosAdicionales = $objQuery->getResult();
        return $arPagosAdicionales;
    }

    public function programacionPago($codigoEmpleado = "", $codigoContrato = "", $pagoTipo, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAdicional::class, 'a')
            ->select('a.codigoAdicionalPk')
            ->addSelect('a.codigoConceptoFk')
            ->addSelect('a.vrValor')
            ->addSelect('a.detalle')
            ->addSelect('a.aplicaDiaLaborado')
            ->where('a.estadoInactivo = 0 AND a.estadoInactivoPeriodo = 0')
            ->andWhere("a.codigoEmpleadoFk = {$codigoEmpleado} ")
            ->andWhere("(a.permanente = 1 or (a.fecha >= '" . $fechaDesde . "' AND a.fecha <= '" . $fechaHasta . "'))");

        if ($pagoTipo == 'NOM') {
            $queryBuilder->andWhere('a.aplicaNomina = 1');
        }
        if ($pagoTipo == 'PRI') {
            $queryBuilder->andWhere('a.aplicaPrima = 1');
        }

        $arrResultado = $queryBuilder->getQuery()->getResult();
        return $arrResultado;
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEmpleado = null;
        $estadoInactivo = null;
        $estadoInactivoPeriodo = null;

        if ($filtros) {
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $estadoInactivo = $filtros['estadoInactivo'] ?? null;
            $estadoInactivoPeriodo = $filtros['estadoInactivoPeriodo'] ?? null;
        }

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAdicional::class, 'a')
            ->select('a.codigoAdicionalPk')
            ->addSelect('a.vrValor')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('a.estadoInactivo')
            ->addSelect('a.estadoInactivoPeriodo')
            ->addSelect('a.aplicaDiaLaborado')
            ->leftJoin('a.empleadoRel', 'e')
            ->leftJoin('a.conceptoRel', 'c')
            ->where('a.permanente = true');

        if ($codigoEmpleado) {
            $queryBuilder->andWhere("a.codigoEmpleadoFk  = '{$codigoEmpleado}'");
        }
        switch ($estadoInactivo) {
            case '0':
                $queryBuilder->andWhere("a.estadoInactivo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoInactivo = 1");
                break;
        }
        switch ($estadoInactivoPeriodo) {
            case '0':
                $queryBuilder->andWhere("a.estadoInactivoPeriodo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoInactivoPeriodo = 1");
                break;
        }

        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function adicionalesPorPeriodo($codigoPeriodo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAdicional::class, 'a')
            ->select('a.codigoAdicionalPk')
            ->addSelect('a.fecha')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('a.detalle')
            ->addSelect('g.nombre as grupo')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('a.vrValor')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('a.estadoInactivo')
            ->addSelect('a.estadoInactivoPeriodo')
            ->addSelect('a.aplicaDiaLaborado')
            ->leftJoin('a.empleadoRel', 'e')
            ->leftJoin('a.contratoRel', 'cont')
            ->leftJoin('cont.grupoRel', 'g')
            ->leftJoin('a.conceptoRel', 'c')
            ->where("a.codigoAdicionalPeriodoFk = {$codigoPeriodo}");

        if ($session->get('filtroRhuEmpleadoCodigo') != '') {
            $queryBuilder->andWhere("a.codigoEmpleadoFk  = '{$session->get('filtroRhuEmpleadoCodigo')}'");
        }
        return $queryBuilder;
    }

    public function deduccionLiquidacion($codigoContrato)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAdicional::class, 'a')
            ->select('a.codigoAdicionalPk')
            ->addSelect('a.fecha')
            ->addSelect('a.codigoConceptoFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('a.detalle')
            ->addSelect('g.nombre as grupo')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('a.vrValor')
            ->addSelect('e.nombreCorto as empleadoNombreCorto')
            ->addSelect('a.estadoInactivo')
            ->addSelect('a.estadoInactivoPeriodo')
            ->addSelect('a.aplicaDiaLaborado')
            ->leftJoin('a.empleadoRel', 'e')
            ->leftJoin('a.contratoRel', 'cont')
            ->leftJoin('cont.grupoRel', 'g')
            ->leftJoin('a.conceptoRel', 'c')
            ->where('a.permanente = 1')
            ->andWhere('c.operacion = -1')
            ->andWhere("a.codigoContratoFk = {$codigoContrato}");
        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }

    public function programacionPagoGeneral($codigoEmpleado = "", $fechaDesde, $fechaHasta)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAdicional::class, 'a')
            ->select('a.codigoAdicionalPk')
            ->addSelect('a.fecha')
            ->addSelect('a.codigoConceptoFk')
            ->addSelect('a.detalle')
            ->addSelect('a.vrValor')
            ->addSelect('a.aplicaDiaLaborado')
            ->addSelect('a.permanente')
            ->addSelect('a.estadoInactivo')
            ->addSelect('c.nombre as conceptoNombre')
            ->leftJoin('a.conceptoRel', 'c')
            ->Where("a.codigoEmpleadoFk = '{$codigoEmpleado}'")
            ->andWhere("a.fecha >= '{$fechaDesde} 00:00:00'")
            ->andWhere("a.fecha <= '{$fechaHasta} 23:59:59'");
        return $queryBuilder->getQuery()->getResult();
    }

}
