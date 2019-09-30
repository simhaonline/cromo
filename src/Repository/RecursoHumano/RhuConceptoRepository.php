<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConcepto::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoConcepto = null;
        $nombreConcepto = null;

        if ($filtros) {
            $codigoConcepto = $filtros['codigoConcepto'] ?? null;
            $nombreConcepto = $filtros['nombreConcepto'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuConcepto::class, 'c')
            ->select('c.codigoConceptoPk')
            ->addSelect('c.nombre')
            ->addSelect('c.operacion')
            ->addSelect('c.porcentaje')
            ->addSelect('c.adicional')
            ->addSelect('c.adicionalTipo')
            ->addSelect('c.comision')
            ->addSelect('c.salud')
            ->addSelect('c.pension')
            ->addSelect('c.fondoSolidaridadPensional')
            ->addSelect('c.auxilioTransporte')
            ->addSelect('c.cesantia')
            ->addSelect('c.vacacion')
            ->addSelect('c.incapacidad')
            ->addSelect('c.incapacidadEntidad')
            ->addSelect('c.recargoNocturno')
            ->addSelect('c.generaIngresoBasePrestacionVacacion')
            ->addSelect('c.generaIngresoBaseCotizacion')
            ->addSelect('c.generaIngresoBasePrestacion');
        if ($codigoConcepto) {
            $queryBuilder->andWhere("c.codigoConceptoPk = '{$codigoConcepto}'");
        }
        if ($nombreConcepto) {
            $queryBuilder->andWhere("c.nombre LIKE '%{$nombreConcepto}%' ");
        }
        $queryBuilder->addOrderBy('c.codigoConceptoPk', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuConcepto::class)->find($arrSeleccionado);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                }
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
    }

}