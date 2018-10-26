<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCompraDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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

    public function listaFormato($codiigoCompra)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(ComCompraDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoCompraDetallePk')
            ->addSelect('pr.nombreCorto AS clienteNombreCorto')
            ->addSelect('ct.nombre AS cuentaPagarTipo')
            ->addSelect('con.nombre AS concepto')
            ->addSelect('cd.cantidad')
            ->addSelect('cd.vrPrecioUnitario')
            ->addSelect('cd.vrSubtotal')
            ->addSelect('cd.porDescuento')
            ->addSelect('cd.vrDescuento')
            ->addSelect('cd.porIva')
            ->addSelect('cd.vrIva')
            ->addSelect('cd.vrTotal')
            ->leftJoin('cd.compraRel', 'c')
            ->leftJoin('cd.conceptoRel', 'con')
            ->leftJoin('c.proveedorRel', 'pr')
            ->leftJoin('c.compraTipoRel', 'ct')
            ->where('cd.codigoCompraFk = ' . $codiigoCompra);
        $queryBuilder->orderBy('cd.codigoCompraDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
