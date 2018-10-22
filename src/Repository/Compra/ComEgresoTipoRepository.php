<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComEgresoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComEgresoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgresoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgresoTipo[]    findAll()
 * @method ComEgresoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComEgresoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComEgresoTipo::class);
    }


    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComEgresoTipo::class, 'et')
            ->select('et.codigoEgresoTipoPk as ID')
            ->addSelect('et.nombre')
            ->where('et.codigoEgresoTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
