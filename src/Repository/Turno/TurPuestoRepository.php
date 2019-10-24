<?php


namespace App\Repository\Turno;


use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Turno\TurPuesto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TurPuestoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPuesto::class);
    }

    public function cliente($id)
    {

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPuesto::class, 'p')
            ->select('p.codigoPuestoPk')
            ->addSelect("p.nombre")
            ->addSelect('pro.nombre as programadorNombre')
            ->addSelect('p.direccion')
            ->addSelect('p.telefono')
            ->addSelect('p.comunicacion')
            ->addSelect('c.nombre as ciudadNombre')
            ->addSelect('p.codigoCentroCostoFk')
            ->addSelect('s.nombre as salario')
            ->leftJoin('p.programadorRel', 'pro')
            ->leftJoin('p.ciudadRel', 'c')
            ->leftJoin('p.salarioRel', 's')
            ->where('p.codigoClienteFk = ' . $id);
        $arPuestos = $queryBuilder->getQuery()->getResult();
        return $arPuestos;

    }

    public function lista()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPuesto::class, 'p')
            ->select('p.codigoPuestoPk')
            ->addSelect("p.nombre")
            ->addSelect("cl.nombreCorto AS cliente")
            ->addSelect('pro.nombre as programadorNombre')
            ->addSelect('p.direccion')
            ->addSelect('p.telefono')
            ->addSelect('p.comunicacion')
            ->addSelect('c.nombre as ciudadNombre')
            ->addSelect('p.codigoCentroCostoFk')
            ->leftJoin('p.programadorRel', 'pro')
            ->leftJoin('p.ciudadRel', 'c')
        ->leftJoin('p.clienteRel', 'cl');
        return $queryBuilder;
    }

}