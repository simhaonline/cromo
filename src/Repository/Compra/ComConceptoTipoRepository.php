<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComConceptoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComConceptoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComConceptoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComConceptoTipo[]    findAll()
 * @method ComConceptoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComConceptoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComConceptoTipo::class);
    }


    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComConceptoTipo::class, 'gc')
            ->select('gc.codigoConceptoTipoPk as ID')
            ->addSelect('gc.nombre')
            ->where('gc.codigoConceptoTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
