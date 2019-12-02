<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurHoraTipo;
use App\Entity\Turno\TurSector;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Entity\Turno\TurSoporteHora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSoporteHoraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurSoporteHora::class);
    }

    public function soporteContrato($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSoporteHora::class, 'sh')
            ->select('sh.codigoSoporteHoraPk')
            ->addSelect('sh.fecha')
            ->addSelect('sh.fechaReal')
            ->addSelect('sh.codigoTurnoFk')
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

    public function numeroLicencias($codigoSoporteContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $intLicencia = 0;
        $novedades = 0;
        $dql = "SELECT SUM(sh.licenciaNoRemunerada + sh.incapacidadNoLegalizada + sh.licencia + sh.ausentismo) as licencia "
            . "FROM App\Entity\Turno\TurSoporteHora sh "
            . "WHERE sh.codigoSoporteContratoFk =  " . $codigoSoporteContrato . " AND (sh.fecha >='" . $fechaDesde . "' AND sh.fecha <= '" . $fechaHasta . "')";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        if ($arrayResultado) {
            $intLicencia = $arrayResultado[0]['licencia'];
            if ($intLicencia == null) {
                $intLicencia = 0;
            }
        }
        $novedades = $intLicencia;
        return $novedades;
    }

    public function numeroLicenciasNoRemunerada($codigoSoporteContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $intLicenciaNoRemunerada = 0;
        $novedades = 0;
        $dql = "SELECT SUM(sh.licenciaNoRemunerada + sh.incapacidadNoLegalizada + sh.ausentismo) as licenciaNoRemunerada "
            . "FROM App\Entity\Turno\TurSoporteHora sh "
            . "WHERE sh.codigoSoporteContratoFk =  " . $codigoSoporteContrato . " AND (sh.fecha >='" . $fechaDesde . "' AND sh.fecha <= '" . $fechaHasta . "')";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        if ($arrayResultado) {
            $intLicenciaNoRemunerada = $arrayResultado[0]['licenciaNoRemunerada'];
            if ($intLicenciaNoRemunerada == null) {
                $intLicenciaNoRemunerada = 0;
            }
        }
        $novedades = $intLicenciaNoRemunerada;
        return $novedades;
    }

    public function numeroIngresoRetiros($codigoSoporteContrato, $fechaDesde, $fechaHasta)
    {
        $em = $this->getEntityManager();
        $intIngresoRetiro = 0;
        $novedades = 0;
        $dql = "SELECT SUM(spd.ingreso) as ingreso, SUM(spd.retiro) as retiro "
            . "FROM App\Entity\Turno\TurSoporteHora spd "
            . "WHERE spd.codigoSoporteContratoFk =  " . $codigoSoporteContrato . " AND (spd.fecha >='" . $fechaDesde . "' AND spd.fecha <= '" . $fechaHasta . "')";
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        if ($arrayResultado) {
            $intIngreso = $arrayResultado[0]['ingreso'];
            if ($intIngreso == null) {
                $intIngreso = 0;
            }
            $intRetiro = $arrayResultado[0]['retiro'];
            if ($intRetiro == null) {
                $intRetiro = 0;
            }
            $intIngresoRetiro = $intIngreso + $intRetiro;
        }
        $novedades = $intIngresoRetiro;
        return $novedades;
    }

}

