<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEmbargoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEmbargo::class);
    }

    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if (is_array($arrSeleccionados) && count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuEmbargo::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    public function listaEmbargo($codigoEmpleado, $afectaLiquidacion = 0, $afectaVacacion = 0, $afectaPrima = 0, $afectaCesantias = 0)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuEmbargo::class, "em")
            ->select("em")
            ->where("em.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("em.estadoActivo = 1");
        if ($afectaLiquidacion) {
            $query->andWhere("em.afectaLiquidacion = 1");
        }
        if ($afectaVacacion) {
            $query->andWhere("em.afectaVacacion = 1");
        }
        if ($afectaPrima) {
            $query->andWhere("em.afectaPrima = 1");
        }
        if ($afectaCesantias) {
            $query->andWhere("em.afectaCesantia = 1");
        }

        $arEmbargos = $query->getQuery()->getResult();

        return $arEmbargos;
    }
}