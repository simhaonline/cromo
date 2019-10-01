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

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $estadoGanado = null;
        $estadoCerrado = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $estadoGanado = $filtros['estadoGanado'] ?? null;
            $estadoCerrado = $filtros['estadoCerrado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmNegocio::class, 'n')
            ->select('n.codigoNegocioPk')
            ->addSelect('c.nombreCorto')
            ->addSelect('n.fecha')
            ->addSelect('n.fechaNegocio')
            ->addSelect('n.fechaCierre')
            ->addSelect('n.valor')
            ->addSelect('n.estadoCerrado')
            ->addSelect('n.estadoGanado')
            ->addSelect('c.nombreCorto')
            ->addSelect('f.nombre as faseNombre')
            ->leftJoin('n.clienteRel', 'c')
        ->leftJoin('n.faseRel', 'f');
        if($codigoCliente){
            $queryBuilder->andWhere("n.codigoClienteFk  = '{$codigoCliente}' ");
        }
        switch ($estadoGanado) {
            case '0':
                $queryBuilder->andWhere("n.estadoGanado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoGanado = 1");
                break;
        }
        switch ($estadoCerrado) {
            case '0':
                $queryBuilder->andWhere("n.estadoCerrado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("n.estadoCerrado = 1");
                break;
        }
        $queryBuilder->setMaxResults($limiteRegistros);
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