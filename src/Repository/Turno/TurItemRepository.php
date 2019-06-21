<?php


namespace App\Repository\Turno;

use App\Entity\Turno\TurItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurItem::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TurItem::class, 'i')
            ->select('i.codigoItemPk')
            ->addSelect('i.nombre')
            ->addSelect('i.codigoImpuestoIvaVentaFk')
            ->where('i.codigoItemPk <> 0')
            ->addOrderBy('i.codigoItemPk', 'ASC');
//        if($session->get('itemConExistencia') == true){
//            $queryBuilder->andWhere("i.cantidadExistencia > 0");
//        }
//        if($session->get('filtroItemConDisponibilidad') == true){
//            $queryBuilder->andWhere("i.cantidadDisponible > 0");
//        }
//        if ($session->get('filtroInvBucarItemCodigo') != '') {
//            $queryBuilder->andWhere("i.codigoItemPk = {$session->get('filtroInvBucarItemCodigo')}");
//        }
//        if ($session->get('filtroInvBuscarItemNombre') != '') {
//            $queryBuilder->andWhere("i.nombre LIKE '%{$session->get('filtroInvBuscarItemNombre')}%'");
//        }
//        if ($session->get('filtroInvBuscarItemReferencia') != '') {
//            $queryBuilder->andWhere("i.referencia LIKE '%{$session->get('filtroInvBuscarItemReferencia')}%'");
//        }
//        if ($session->get('filtroInvMarcaItem') != '') {
//            $queryBuilder->andWhere("m.nombre LIKE '%{$session->get('filtroInvMarcaItem')}%'");
//        }
        return $queryBuilder;
    }

}