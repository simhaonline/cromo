<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarNotaCreditoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarNotaCreditoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarNotaCreditoConcepto::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarNotaCreditoConcepto::class, 'ncc');
        $qb->select('ncc.codigoNotaCreditoConceptoPk')
            ->addSelect('ncc.nombre')
            ->where('ncc.codigoNotaCreditoConceptoPk <> 0')
            ->orderBy('ncc.codigoNotaCreditoConceptoPk', 'DESC');
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
                $ar = $em->getRepository(CarNotaCreditoConcepto::class)->find($codigo);
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