<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteDespachoAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteDespachoAuxiliar::class);
    }
//    public function camposPredeterminados(){
//        $qb = $this-> _em->createQueryBuilder()
//            ->from('App:Transporte\TteAuxiliar','au')
//            ->select('au.codigoAuxiliarPk AS ID')
//            ->addSelect('au.nombreCorto AS NOMBRE_COMPLETO')
//            ->addSelect('au.numeroIdentificacion AS IDENTIFICACION');
//        $query = $this->_em->createQuery($qb->getDQL());
//        return $query->execute();
//    }
    public function despacho($codigoDespacho)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoAuxiliar::class, 'a')
            ->select('a.codigoDespachoAuxiliarPk')
            ->addSelect('aux.numeroIdentificacion AS identificacionAuxilair')
            ->addSelect('aux.nombreCorto AS auxiliar')
            ->where("a.codigoDespachoFk = {$codigoDespacho}")
            ->leftJoin('a.auxiliarRel', 'aux');
        return $qb->getQuery()->execute();
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $arDespachoAuxiliar = $this->getEntityManager()->getRepository(TteDespachoAuxiliar::class)->find($arrSeleccionado);
            if ($arDespachoAuxiliar) {
                $this->getEntityManager()->remove($arDespachoAuxiliar);
            }
        }
        $this->getEntityManager()->flush();
    }
}