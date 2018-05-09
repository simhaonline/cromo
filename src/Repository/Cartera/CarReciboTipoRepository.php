<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarReciboTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarReciboTipo::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarReciboTipo::class, 'rt');
        $qb->select('rt.codigoReciboTipoPk')
            ->addSelect('rt.nombre')
            ->where('rt.codigoReciboTipoPk <> 0')
            ->orderBy('rt.codigoReciboTipoPk', 'DESC');
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(CarReciboTipo::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }
}