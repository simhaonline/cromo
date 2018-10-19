<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComConcepto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComConcepto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComConcepto[]    findAll()
 * @method ComConcepto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComConcepto::class);
    }

    public function lista()
    {
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('App:Compra\ComConcepto', 'c')
            ->where('c.codigoConceptoPk <> 0');

        return $query->getQuery();

    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComConcepto::class, 'gc')
            ->select('gc.codigoConceptoPk as ID')
            ->addSelect('gc.nombre')
            ->where('gc.codigoConceptoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
