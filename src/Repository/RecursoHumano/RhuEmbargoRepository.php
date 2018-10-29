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
            foreach ($arrSeleccionados as $codigoEmbargo) {
                $arEmbargo = $this->_em->getRepository(RhuEmbargo::class)->find($codigoEmbargo);
                if ($arEmbargo) {
                    $this->_em->remove($arEmbargo);
                }
            }
            $this->_em->flush();
        }
    }
}