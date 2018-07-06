<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteGuiaCargaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuiaCarga::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(TteGuiaCarga::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();

    }
}