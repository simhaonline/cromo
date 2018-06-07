<?php

namespace App\Repository\Inventario;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvOrdenCompra;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvOrdenCompraDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenCompraDetalle::class);
    }

    public function camposPredeterminados(){
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvOrdenCompra','ioc');
        $qb
            ->select('ioc.codigoOrdenCompraPk as ID')
            ->addSelect('ioc.numero as NUMERO')
            ->addSelect('ioc.fecha as FECHA')
            ->addSelect('ioc.soporte as SOPORTE')
            ->addSelect('ioc.estadoAutorizado as AUTORIZADO')
            ->addSelect('ioc.estadoAprobado as APROBADO')
            ->addSelect('ioc.estadoAnulado as ANULADO');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @param $arOrdenCompra InvOrdenCompra
     * @param $arrDetallesSeleccionados
     */
    public function eliminar($arOrdenCompra, $arrDetallesSeleccionados)
    {
        if ($arOrdenCompra->getEstadoAutorizado() == 0) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigoOrdenCompraDetalle) {
                    $arOrdenCompraDetalle = $this->_em->getRepository('App:Inventario\InvOrdenCompraDetalle')->find($codigoOrdenCompraDetalle);
                    if ($arOrdenCompraDetalle) {
                        $this->_em->remove($arOrdenCompraDetalle);
                    }
                }
                try{
                    $this->_em->flush();
                } catch (\Exception $e){
                    MensajesController::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        } else {
            MensajesController::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }
}