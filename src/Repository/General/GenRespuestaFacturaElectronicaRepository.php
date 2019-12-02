<?php

namespace App\Repository\General;

use App\Entity\General\GenAsesor;
use App\Entity\General\GenResolucionFactura;
use App\Entity\General\GenRespuestaFacturaElectronica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenRespuestaFacturaElectronicaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenRespuestaFacturaElectronica::class);
    }

    public function lista($modelo, $codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenRespuestaFacturaElectronica::class, 'r')
            ->select('r.codigoRespuestaFacturaElectronicaPk')
            ->addSelect('r.fecha')
            ->addSelect('r.statusCode')
            ->addSelect('r.errorMessage')
            ->addSelect('r.errorReason')
            ->where("r.codigoModeloFk = '" . $modelo . "'")
            ->andWhere('r.codigoDocumento = ' . $codigo)
            ->orderBy('r.fecha', 'DESC')
        ->setMaxResults(5);
        $i = 0;
        $arRespuestas = $queryBuilder->getQuery()->getResult();
        foreach ($arRespuestas as $arRespuesta) {
            $array = json_decode($arRespuesta['errorReason']);
            $arRespuestas[$i]['errorReason'] = $array;
            $i++;
        }
        return $arRespuestas;
    }

}