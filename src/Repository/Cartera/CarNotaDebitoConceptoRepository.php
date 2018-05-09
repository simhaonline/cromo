<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarNotaDebitoConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarNotaDebitoConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarNotaDebitoConcepto::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarNotaDebitoConcepto::class, 'ndc');
        $qb->select('ndc.codigoNotaDebitoConceptoPk')
            ->addSelect('ndc.nombre')
            ->where('ndc.codigoNotaDebitoConceptoPk <> 0')
            ->orderBy('ndc.codigoNotaDebitoConceptoPk', 'DESC');
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
                $ar = $em->getRepository(CarNotaDebitoConcepto::class)->find($codigo);
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