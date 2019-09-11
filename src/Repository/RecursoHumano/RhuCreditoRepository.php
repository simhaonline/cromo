<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuCreditoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCredito::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCredito::class, 'c')
            ->select('c.codigoCreditoPk')
            ->addSelect('ct.nombre as creditoTipo')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('c.codigoEmpleadoFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto as empleado')
            ->addSelect('e.estadoContrato as estadoContrato')
            ->addSelect('g.nombre as grupo')
            ->addSelect('c.fecha')
            ->addSelect('c.vrCredito')
            ->addSelect('c.vrCuota')
            ->addSelect('c.numeroCuotaActual')
            ->addSelect('c.numeroCuotas')
            ->addSelect('c.vrAbonos')
            ->addSelect('c.vrSaldo')
            ->addSelect('c.estadoPagado')
            ->addSelect('c.inactivoPeriodo')
            ->leftJoin('c.creditoTipoRel', 'ct')
            ->leftJoin('c.empleadoRel', 'e')
            ->leftJoin('c.grupoRel', 'g');

        if ($session->get('RhuCredito_codigoCreditoPk')) {
            $queryBuilder->andWhere("c.codigoCreditoPk = '{$session->get('RhuCredito_codigoCreditoPk')}'");
        }
        if ($session->get('RhuCredito_codigoCreditoTipoFk')) {
            $queryBuilder->andWhere("c.codigoCreditoTipoFk = '{$session->get('RhuCredito_codigoCreditoTipoFk')}'");
        }
        if ($session->get('RhuCredito_codigoEmpleadoFk')) {
            $queryBuilder->andWhere("c.codigoEmpleadoFk = '{$session->get('RhuCredito_codigoEmpleadoFk')}'");
        }
        if ($session->get('RhuCredito_fechaDesde') != null) {
            $queryBuilder->andWhere("c.fechaDesde >= '{$session->get('RhuCredito_fechaDesde')} 00:00:00'");
        }

        if ($session->get('RhuCredito_fechaHasta') != null) {
            $queryBuilder->andWhere("c.fechaHasta <= '{$session->get('RhuCredito_fechaHasta')} 23:59:59'");
        }

        switch ($session->get('RhuCredito_estadoPagado')) {
            case '0':
                $queryBuilder->andWhere("c.estadoPagado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoPagado = 1");
                break;
        }

        switch ($session->get('RhuCredito_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("c.estadoSuspendido = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoSuspendido = 1");
                break;
        }
        
        return $queryBuilder;

    }
    
    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuCredito::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    public function pendientes($codigoEmpleado){
        $em = $this->getEntityManager();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCredito::class, 'c')
            ->select('c.codigoCreditoPk')
            ->addSelect('ct.nombre AS tipo')
            ->addSelect('c.vrSaldo')
            ->addSelect('c.vrCuota')
            ->where('c.vrSaldo > 0')
            ->andWhere("c.codigoEmpleadoFk = {$codigoEmpleado}")
        ->leftJoin('c.creditoTipoRel' ,'ct');
        return $queryBuilder->getQuery()->getArrayResult();
    }
}