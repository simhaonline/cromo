<?php


namespace App\Repository\Turno;


use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Turno\TurPuesto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPuestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->addSelect('p.estadoInactivo')
            ->addSelect('p.contacto')
            ->addSelect('s.nombre as salario')
            ->addSelect('su.nombre as supervisor')
            ->addSelect('z.nombre as zona')
            ->addSelect('op.nombre as operacion')
            ->addSelect('coo.nombre as coordinador')
            ->leftJoin('p.programadorRel', 'pro')
            ->leftJoin('p.ciudadRel', 'c')
            ->leftJoin('p.salarioRel', 's')
            ->leftJoin('p.supervisorRel', 'su')
            ->leftJoin('p.zonaRel', 'z')
            ->leftJoin('p.operacionRel', 'op')
            ->leftJoin('p.coordinadorRel', 'coo')
            ->where('p.codigoClienteFk = ' . $id);
        $arPuestos = $queryBuilder->getQuery()->getResult();
        return $arPuestos;

    }

    public function lista()
    {
        $session = new Session();
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

        if ($session->get('filtroTurPuestoCodigoPuesto') !=null){
            $queryBuilder->andWhere("p.codigoPuestoPk = '{$session->get('filtroTurPuestoCodigoPuesto')}'");
        }

        if ($session->get('filtroTurPuestoNombreCliente') !=null){
            $queryBuilder->andWhere("cl.nombreCorto like '%{$session->get('filtroTurPuestoNombreCliente')}%'");
        }

        if ($session->get('filtroTurPuestoCodigoCliente') !=null){
            $queryBuilder->andWhere("cl.codigoClientePk = '{$session->get('filtroTurPuestoCodigoCliente')}'");
        }

        return $queryBuilder->getQuery()->getResult();
    }

}