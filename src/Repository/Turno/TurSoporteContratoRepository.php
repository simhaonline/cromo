<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSoporteContrato::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteContrato::class, 'sc');
        $queryBuilder
            ->select('sc.codigoSoporteContratoPk')
            ->addSelect('sc.codigoEmpleadoFk')
            ->addSelect('e.nombreCorto AS empleado')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('sc.dias')
            ->addSelect('sc.diasTransporte')
            ->addSelect('sc.novedad')
            ->addSelect('sc.induccion')
            ->addSelect('sc.retiro')
            ->addSelect('sc.incapacidad')
            ->addSelect('sc.licencia')
            ->addSelect('sc.ausentismo')
            ->addSelect('sc.vacacion')
            ->addSelect('sc.horas')
            ->addSelect('sc.horasDescanso')
            ->addSelect('sc.horasDiurnas')
            ->addSelect('sc.horasNocturnas')
            ->addSelect('sc.horasFestivasDiurnas')
            ->addSelect('sc.horasFestivasNocturnas')
            ->addSelect('sc.horasExtrasOrdinariasDiurnas')
            ->addSelect('sc.horasExtrasOrdinariasNocturnas')
            ->addSelect('sc.horasExtrasFestivasDiurnas')
            ->addSelect('sc.horasExtrasFestivasNocturnas')
            ->addSelect('sc.horasRecargoNocturno')
            ->addSelect('sc.horasRecargoFestivoDiurno')
            ->addSelect('sc.horasRecargoFestivoNocturno')
            ->addSelect('sc.horasRecargo')
            ->leftJoin('sc.contratoRel', 'c')
            ->leftJoin('sc.empleadoRel', 'e')
            ->where('sc.codigoSoporteFk = ' . $id);

        return $queryBuilder;
    }

    public function listaTiempo($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteContrato::class, 'sc');
        $queryBuilder
            ->select('sc.codigoSoporteContratoPk')
            ->where('sc.codigoSoporteFk = ' . $id);
        $arSoporteContratos = $queryBuilder->getQuery()->getResult();

        return $arSoporteContratos;
    }
}

