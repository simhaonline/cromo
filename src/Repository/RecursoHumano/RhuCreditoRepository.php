<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuCreditoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCredito::class);
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