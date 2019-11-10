<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurPedidoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurPedidoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.periodo')
            ->addSelect('pd.codigoContratoDetalleFk')
            ->addSelect('pd.codigoModalidadFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('i.nombre as itemNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.itemRel', 'i')
            ->where('pd.codigoPedidoFk = ' . $id);

        return $queryBuilder;
    }

    public function pendienteProgramar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('cl.nombreCorto AS cliente')
            ->addSelect('pd.mes')
            ->addSelect('pd.anio')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.codigoContratoDetalleFk')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm')
            ->leftJoin('pd.pedidoRel', 'p')
            ->leftJoin('p.clienteRel', 'cl')
            ->where('pd.estadoProgramado = 0')
        ->orderBy('p.codigoPedidoPk', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arPedido
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arPedido)
    {
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(TurPedido::class)->contarDetalles($arPedido->getCodigoPedidoPk()) > 0) {
            $arrPrecioAjustado = $arrControles['arrPrecioAjustado'];
            $arrPorcentajeBaseIva = $arrControles['arrPorcentajeBaseIva'];
            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoPedidoDetalle) {
                $arPedidoDetalle = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
                $arPedidoDetalle->setHoras($arPedidoDetalle->getConceptoRel()->getHoras());
                $arPedidoDetalle->setHorasDiurnas($arPedidoDetalle->getConceptoRel()->getHorasDiurnas());
                $arPedidoDetalle->setHorasNocturnas($arPedidoDetalle->getConceptoRel()->getHorasNocturnas());
                $arPedidoDetalle->setVrSalarioBase($arPedidoDetalle->getVrSalarioBase());
                $arPedidoDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoPedidoDetalle]);
                $arPedidoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoPedidoDetalle]);
                $arPedidoDetalle->setPorcentajeBaseIva($arrPorcentajeBaseIva[$codigoPedidoDetalle]);
                $arPedidoDetalle->setVrSubtotal($arPedidoDetalle->getVrPrecioAjustado() * $arPedidoDetalle->getCantidad());
                $arPedidoDetalle->setVrIva($arPedidoDetalle->getVrSubtotal() * $arPedidoDetalle->getPorcentajeIva() / 100);
                $arPedidoDetalle->setVrTotalDetalle($arPedidoDetalle->getVrSubtotal() + $arPedidoDetalle->getVrIva());
                $em->persist($arPedidoDetalle);
                $em->flush();
            }
            $em->getRepository(TurPedido::class)->liquidar($arPedido);
            $em->flush();
        }
    }

    /**
     * @param $arrDetallesSeleccionados
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($id, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TurPedido::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurPedidoDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                            $this->getEntityManager()->flush();
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

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.vrSalarioBase')
            ->addSelect('pd.vrPrecioMinimo')
            ->addSelect('pd.vrPrecioAjustado')
            ->addSelect('pd.porcentajeBaseIva')
            ->addSelect('pd.porcentajeIva')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('c.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->leftJoin('pd.conceptoRel', 'c')
            ->leftJoin('pd.modalidadRel', 'm');

        return $queryBuilder;
    }

    public function pendienteFacturar ($raw)
    {
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.liquidarDiasReales')
            ->addSelect('pd.dias')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.vrTotalDetallePendiente')
            ->addSelect('pd.vrTotalDetalle')
            ->addSelect('p.numero')
            ->addSelect('p.fecha as pedidoFecha')
            ->addSelect('p.fechaGeneracion')
            ->addSelect('p.estadoAutorizado as pedidoEstadoAutorizado')
            ->addSelect('p.estadoProgramado as pedidoEstadoProgramado')
            ->addSelect('p.estadoFacturado as pedidoEstadoFacturado')
            ->addSelect('p.estadoAnulado as pedidoEstadoAnulado')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.codigoClientePk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('pu.nombre as puestoNombre')
            ->addSelect('cs.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->addSelect('pt.nombre as pedidoTipoNombre')
            ->addSelect('s.nombre as sectorNombre')
            ->addSelect('i.nombre as itemNombre')
            ->leftJoin("pd.pedidoRel", "p")
            ->leftJoin("p.clienteRel", "c")
            ->leftJoin("p.pedidoTipoRel", "pt")
            ->leftJoin("p.sectorRel", "s")
            ->leftJoin("pd.puestoRel", "pu")
            ->leftJoin("pd.conceptoRel", "cs")
            ->leftJoin("pd.itemRel", 'i')
            ->leftJoin("pd.modalidadRel", "m")
            ->where("pd.vrTotalDetallePendiente > 0")
            ->andWhere('p.estadoAutorizado = 1');

        if ($codigoCliente) {
            $queryBuilder->andWhere("c.codigoClientePk = '{$codigoCliente}'");
        }
        $queryBuilder->orderBy('pd.anio', 'DESC');
        $queryBuilder->addOrderBy('pd.mes', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function pendienteFacturarInforme($raw)
    {
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $numero = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoProgramado = null;
        $estadoFacturado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoProgramado = $filtros['estadoProgramado'] ?? null;
            $estadoFacturado = $filtros['estadoFacturado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurPedidoDetalle::class, 'pd')
            ->select('pd.codigoPedidoDetallePk')
            ->addSelect('pd.diaDesde')
            ->addSelect('pd.diaHasta')
            ->addSelect('pd.cantidad')
            ->addSelect('pd.lunes')
            ->addSelect('pd.martes')
            ->addSelect('pd.miercoles')
            ->addSelect('pd.jueves')
            ->addSelect('pd.viernes')
            ->addSelect('pd.sabado')
            ->addSelect('pd.domingo')
            ->addSelect('pd.festivo')
            ->addSelect('pd.horas')
            ->addSelect('pd.horasDiurnas')
            ->addSelect('pd.horasNocturnas')
            ->addSelect('pd.horasDiurnasProgramadas')
            ->addSelect('pd.horasNocturnasProgramadas')
            ->addSelect('pd.horasProgramadas')
            ->addSelect('pd.liquidarDiasReales')
            ->addSelect('pd.dias')
            ->addSelect('pd.vrIva')
            ->addSelect('pd.vrSubtotal')
            ->addSelect('pd.vrTotalDetallePendiente')
            ->addSelect('pd.vrTotalDetalle')
            ->addSelect('p.numero')
            ->addSelect('p.fecha as pedidoFecha')
            ->addSelect('p.fechaGeneracion')
            ->addSelect('p.estadoAutorizado as pedidoEstadoAutorizado')
            ->addSelect('p.estadoProgramado as pedidoEstadoProgramado')
            ->addSelect('p.estadoFacturado as pedidoEstadoFacturado')
            ->addSelect('p.estadoAnulado as pedidoEstadoAnulado')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.codigoClientePk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.digitoVerificacion')
            ->addSelect('pu.nombre as puesto')
            ->addSelect('cs.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->addSelect('pt.nombre as pedidoTipoNombre')
            ->addSelect('s.nombre as sectorNombre')
            ->leftJoin("pd.pedidoRel", "p")
            ->leftJoin("p.clienteRel", "c")
            ->leftJoin("p.pedidoTipoRel", "pt")
            ->leftJoin("p.sectorRel", "s")
            ->leftJoin("pd.puestoRel", "pu")
            ->leftJoin("pd.conceptoRel", "cs")
            ->leftJoin("pd.modalidadRel", "m")
            ->where("pd.vrTotalDetallePendiente > 0")
            ->andWhere('p.estadoAutorizado = 1');

        if ($codigoCliente) {
            $queryBuilder->andWhere("c.codigoClientePk = '{$codigoCliente}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("p.$numero = '{$numero}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("p.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("p.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("p.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoAutorizado = 1");
                break;
        }
        switch ($estadoProgramado) {
            case '0':
                $queryBuilder->andWhere("p.$estadoProgramado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.$estadoProgramado = 1");
                break;
        }
        switch ($estadoFacturado) {
            case '0':
                $queryBuilder->andWhere("p.estadoFacturado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("p.estadoFacturado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("i.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('pd.codigoPedidoDetallePk', 'DESC');
        return $queryBuilder->getQuery()->getResult();
    }
}
