<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurHoraTipo;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Entity\Turno\TurSoporteHora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteHoraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporteHora::class);
    }

    public function soporteContrato($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteHora::class, 'sh')
            ->select('sh.codigoSoporteHoraPk')
            ->addSelect('sh.fecha')
            ->addSelect('sh.fechaReal')
            ->addSelect('t.nombre as turno')
            ->addSelect('sh.dias')
            ->addSelect('sh.descanso')
            ->addSelect('sh.horasDiurnas')
            ->addSelect('sh.horasNocturnas')
            ->addSelect('sh.horasFestivasDiurnas')
            ->addSelect('sh.horasFestivasNocturnas')
            ->addSelect('sh.horasExtrasOrdinariasDiurnas')
            ->addSelect('sh.horasExtrasOrdinariasNocturnas')
            ->addSelect('sh.horasExtrasFestivasDiurnas')
            ->addSelect('sh.horasExtrasFestivasNocturnas')
            ->addSelect('sh.horasRecargoNocturno')
            ->addSelect('sh.horasRecargoFestivoDiurno')
            ->addSelect('sh.horasRecargoFestivoNocturno')
            ->addSelect('sh.horas')
            ->addSelect('sh.complementario')
            ->leftJoin('sh.turnoRel', 't')
            ->where('sh.codigoSoporteContratoFk = ' . $id);
        $arSoporteHoras = $queryBuilder->getQuery()->getResult();

        return $arSoporteHoras;
    }

    public function retirarSoporteContrato($codigo)
    {
        $em = $this->getEntityManager();
        $q = $em->createQuery('delete from App\Entity\Turno\TurSoporteHora sh where sh.codigoSoporteContratoFk = ' . $codigo);
        $numeroRegistros = $q->execute();
        $em->flush();
    }
}

