<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCompraDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComCompraDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComCompraDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComCompraDetalle[]    findAll()
 * @method ComCompraDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComCompraDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComCompraDetalle::class);
    }


    public function lista($codigoCompra)
    {
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('cd')
            ->from('App:Compra\ComCompraDetalle', 'cd')
            ->where("cd.codigoCompraFk = '{$codigoCompra}'");

        return $query->getQuery();
    }

    /**
     * @param $arCompra
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arCompra, $arrDetallesSeleccionados)
    {
        if ($arCompra->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(ComCompraDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }
}
