<?php


namespace App\Repository\Crm;


use App\Entity\Crm\CrmNegocio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CrmNegocioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmNegocio::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmNegocio::class, 'n')
            ->select('n.codigoNegocioPk')
            ->addSelect('c.nombreCorto')
            ->addSelect('n.fecha')
            ->addSelect('n.fechaNegocio')
            ->addSelect('n.fechaCierre')
            ->addSelect('n.valor')
            ->addSelect('c.nombreCorto')
            ->addSelect('f.nombre as faseNombre')
            ->leftJoin('n.clienteRel', 'c')
        ->leftJoin('n.faseRel', 'f');
        if($session->get('filtroCrmNegocioCodigoCliente')){
            $queryBuilder->andWhere("n.codigoClienteFk  = '{$session->get('filtroCrmNegocioCodigoCliente')}' ");
        }

        return $queryBuilder;
    }
    
    public function GraficaNegociosporFace()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmNegocio::class, 'n')
            ->Select('COUNT(n.codigoFaseFk) as cuenta')
            ->addSelect('f.nombre')
            ->leftJoin('n.faseRel', 'f')
//            ->where("Date_format(n.fecha,'%Y-%m) = Date_format(now(),'Y-m')")
            ->groupby('n.codigoFaseFk');
         $queryBuilder->getQuery()->getResult() ;

    }
}