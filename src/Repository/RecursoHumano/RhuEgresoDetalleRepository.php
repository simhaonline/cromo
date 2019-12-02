<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use App\Entity\RecursoHumano\RhuPago;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuEgresoDetalleRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEgresoDetalle::class);
    }

    public function listaEgresosDetalle($codigoEgreso)
    {
        return $this->_em->createQueryBuilder()
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.codigoPagoFk')
            ->addSelect('pt.nombre  AS pagoTipo')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('b.nombre')
            ->addSelect('p.numero as pagoNumero')
            ->addSelect('ed.cuenta')
            ->addSelect('ed.vrPago')
            ->from(RhuEgresoDetalle::class, 'ed')
            ->leftJoin('ed.pagoRel', 'p')
            ->leftJoin('p.pagoTipoRel', 'pt')
            ->leftJoin('ed.empleadoRel', 'e')
            ->leftJoin('ed.bancoRel', 'b')
            ->where("ed.codigoEgresoFk = {$codigoEgreso}")
            ->getQuery()->execute();
    }

    /**
     * @param $arrSeleccionados
     */
    public function eliminar($arrSeleccionados)
    {
        $em = $this->_em;
        foreach ($arrSeleccionados as $codigoEgresoDetalle) {
            $arEgresoDetalle = $em->find(RhuEgresoDetalle::class, $codigoEgresoDetalle);
            if ($arEgresoDetalle) {
                $arPago = $em->find(RhuPago::class, $arEgresoDetalle->getCodigoPagoFk());
                $arPago->setEstadoEgreso(0);
                $em->persist($arPago);
                $em->remove($arEgresoDetalle);
            }
        }
        try {
            $em->flush();
        } catch (\Exception $exception) {
            Mensajes::error('No se pueden eliminar registros que estan siendo utilizados en el sistema');
        }
    }


    /**
     * @param $id
     */
    public function eliminarTodos($id)
    {
        $em = $this->_em;
        $arEgresoDetalles = $em->getRepository(RhuEgresoDetalle::class)->findBy(['codigoEgresoFk' => $id]);
        if ($arEgresoDetalles) {
            foreach ($arEgresoDetalles as $arEgresoDetalle) {
                $arPago = $em->find(RhuPago::class, $arEgresoDetalle->getCodigoPagoFk());
                $arPago->setEstadoEgreso(0);
                $em->persist($arPago);
                $em->remove($arEgresoDetalle);
            }
        }
        try {
            $em->flush();
        } catch (\Exception $exception) {
            Mensajes::error('No se pueden eliminar registros que estan siendo utilizados en el sistema');
        }
    }
}