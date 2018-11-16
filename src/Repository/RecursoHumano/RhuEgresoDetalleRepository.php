<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEgresoDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEgresoDetalle::class);
    }

    public function listaEgresosDetalle($codigoEgreso){
        return $this->_em->createQueryBuilder()
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.codigoPagoFk')
            ->addSelect('pt.nombre  AS pagoTipo')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('b.nombre')
            ->addSelect('e.cuenta')
            ->addSelect('ed.vrPago')
            ->from(RhuEgresoDetalle::class,'ed')
            ->leftJoin('ed.pagoRel','p')
            ->leftJoin('p.pagoTipoRel','pt')
            ->leftJoin('ed.empleadoRel','e')
            ->leftJoin('ed.bancoRel','b')
            ->where("ed.codigoEgresoFk = {$codigoEgreso}")
            ->getQuery()->execute();
    }
}