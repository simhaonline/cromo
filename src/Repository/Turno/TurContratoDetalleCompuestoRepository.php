<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurContratoDetalleCompuesto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TurContratoDetalleCompuestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurContratoDetalleCompuesto::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalleCompuesto::class, 'cdc')
            ->select('cdc.codigoContratoDetalleCompuestoPk')
            ->addSelect('cdc.codigoContratoDetalleFk')
            ->addSelect('cdc.horaDesde')
            ->addSelect('cdc.horaHasta')
            ->addSelect('cdc.diasReales')
            ->addSelect('cdc.periodo')
            ->addSelect('cdc.cantidad')
            ->addSelect('cdc.lunes')
            ->addSelect('cdc.martes')
            ->addSelect('cdc.miercoles')
            ->addSelect('cdc.jueves')
            ->addSelect('cdc.viernes')
            ->addSelect('cdc.sabado')
            ->addSelect('cdc.domingo')
            ->addSelect('cdc.festivo')
            ->addSelect('cdc.horas')
            ->addSelect('cdc.horasDiurnas')
            ->addSelect('cdc.horasNocturnas')
            ->addSelect('cdc.dias')
            ->addSelect('cdc.vrPrecioMinimo')
            ->addSelect('cdc.vrPrecioAjustado')
            ->addSelect('cdc.vrSubtotal')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('cdc.conceptoRel', 'c')
            ->leftJoin('cdc.modalidadRel', 'm')
            ->where("cdc.codigoContratoDetalleFk = {$id}");
        return $queryBuilder->getQuery()->getResult();
    }


    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            foreach ($arrDetallesSeleccionados as $codigo) {
                $ar = $this->getEntityManager()->getRepository(TurContratoDetalleCompuesto::class)->find($codigo);
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