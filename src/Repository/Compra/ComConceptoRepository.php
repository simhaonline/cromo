<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $session = new Session();
        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('App:Compra\ComConcepto', 'c');
        if ($session->get('filtroInvItemCodigo')) {
            $query->andWhere("c.codigoConceptoPk = {$session->get('filtroInvItemCodigo')}");
        }
        if ($session->get('filtroInvItemNombre')) {
            $query->andWhere("c.nombre  LIKE '%{$session->get('filtroInvItemNombre')}%'");
        }
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
