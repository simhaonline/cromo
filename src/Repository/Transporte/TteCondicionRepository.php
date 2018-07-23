<?php
namespace App\Repository\Transporte;
use App\Entity\Transporte\TteCondicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class TteCondicionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCondicion::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteCondicion','cc')
            ->select('cc.codigoCondicionPk AS ID')
            ->addSelect('cc.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCondicion::class, 'cc')
            ->select('cc.codigoCondicionPk')
            ->addSelect('cc.nombre')
            ->addSelect('cc.porcentajeManejo')
            ->addSelect('cc.manejoMinimoUnidad')
            ->addSelect('cc.manejoMinimoDespacho')
            ->addSelect('cc.precioPeso')
            ->addSelect('cc.precioUnidad')
            ->addSelect('cc.precioAdicional')
            ->addSelect('cc.descuentoPeso')
            ->addSelect('cc.descuentoUnidad')
            ->addSelect('cc.permiteRecaudo')
            ->where('cc.codigoCondicionPk IS NOT NULL')
            ->orderBy('cc.codigoCondicionPk', 'ASC');
        if ($session->get('filtroNombreCondicion') != '') {
            $queryBuilder->andWhere("cc.nombre LIKE '%{$session->get('filtroNombreCondicion')}%' ");
        }
        return $queryBuilder;
    }
}