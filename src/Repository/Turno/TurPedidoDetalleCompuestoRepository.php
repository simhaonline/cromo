<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurContratoDetalleCompuesto;
use App\Entity\Turno\TurPedidoDetalleCompuesto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurPedidoDetalleCompuestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurPedidoDetalleCompuesto::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalleCompuesto::class, 'pdc')
            ->select('pdc.codigoPedidoDetalleCompuestoPk')
            ->addSelect('pdc.codigoPedidoDetalleFk')
            ->addSelect('pdc.diasReales')
            ->addSelect('pdc.periodo')
            ->addSelect('pdc.cantidad')
            ->addSelect('pdc.lunes')
            ->addSelect('pdc.martes')
            ->addSelect('pdc.miercoles')
            ->addSelect('pdc.jueves')
            ->addSelect('pdc.viernes')
            ->addSelect('pdc.sabado')
            ->addSelect('pdc.domingo')
            ->addSelect('pdc.festivo')
            ->addSelect('pdc.horas')
            ->addSelect('pdc.horasDiurnas')
            ->addSelect('pdc.horasNocturnas')
            ->addSelect('pdc.dias')
            ->addSelect('pdc.vrPrecioMinimo')
            ->addSelect('pdc.vrPrecioAjustado')
            ->addSelect('pdc.vrSubtotal')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pdc.conceptoRel', 'c')
            ->leftJoin('pdc.modalidadRel', 'm')
            ->where("pdc.codigoPedidoDetalleFk = {$id}");
        return $queryBuilder->getQuery()->getResult();
    }


    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            foreach ($arrDetallesSeleccionados as $codigo) {
                $ar = $this->getEntityManager()->getRepository(TurPedidoDetalleCompuesto::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                    $em->flush();
                }
            }
            try {
                $this->getEntityManager()->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }
    }

}