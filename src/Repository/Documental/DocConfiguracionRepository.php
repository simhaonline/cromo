<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;
class DocConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocConfiguracion::class);
    }

    public function archivoMasivo(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocConfiguracion::class, 'c')
            ->select('c.rutaBandeja')
            ->addSelect('c.rutaAlmacenamiento')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();
    }
}