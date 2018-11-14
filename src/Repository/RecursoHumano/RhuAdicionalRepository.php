<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuCredito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuAdicionalRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
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

    public function programacionPago ($codigoEmpleado = "", $pagoTipo) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAdicional::class, 'a')
            ->select('a.codigoAdicionalPk')
            ->addSelect('a.codigoConceptoFk')
            ->addSelect('a.vrValor')
            ->addSelect('a.detalle')
            ->where('a.estadoInactivo = 0 AND a.estadoInactivoPeriodo = 0')
            ->andWhere("a.codigoEmpleadoFk = {$codigoEmpleado} ");

        if($pagoTipo == 'NOM') {
            $queryBuilder->andWhere('a.aplicaNomina = 1');
        }

        $arrResultado = $queryBuilder->getQuery()->getResult();
        return $arrResultado;
    }

}