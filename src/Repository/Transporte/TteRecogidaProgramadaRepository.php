<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecogidaProgramada;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteRecogidaProgramadaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecogidaProgramada::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT rp.codigoRecogidaProgramadaPk, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad,
            rp.anunciante, rp.hora, rp.codigoOperacionFk, rp.direccion, rp.telefono, r.nombre
        FROM App\Entity\Transporte\TteRecogidaProgramada rp 
        LEFT JOIN rp.rutaRecogidaRel r
        LEFT JOIN rp.clienteRel c
        LEFT JOIN rp.ciudadRel co'
        );
        return $query->execute();

    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if($arrSeleccionados) {
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $ar = $this->getEntityManager()->getRepository(TteRecogidaProgramada::class)->find($arrSeleccionado);
                if ($ar) {
                    $this->getEntityManager()->remove($ar);
                }
            }
            $this->getEntityManager()->flush();
        }
    }

}