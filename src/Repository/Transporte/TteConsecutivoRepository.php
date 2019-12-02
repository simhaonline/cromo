<?php

namespace App\Repository\Transporte;


use App\Controller\Transporte\Buscar\ConductorController;
use App\Entity\Transporte\TteConsecutivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteConsecutivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteConsecutivo::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteConsecutivo','c')
            ->select('c.codigoConsecutivoPk AS ID')
            ->addSelect('c.nombre AS NOMBRE_COMPLETO')
            ->addSelect('c.consecutivo AS CONSECUTIVO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function consecutivo($codigoConsecutivo) {
        $em = $this->getEntityManager();
        $intNumero = 0;
        $arConsecutivo = new TteConsecutivo();
        $arConsecutivo = $em->getRepository(TteConsecutivo::class)->find($codigoConsecutivo);
        $intNumero = $arConsecutivo->getConsecutivo();
        $arConsecutivo->setConsecutivo($intNumero + 1);
        $em->persist($arConsecutivo);
        return $intNumero;
    }


}