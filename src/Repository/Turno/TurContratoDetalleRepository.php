<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurContratoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoContratoDetallePk')
            ->addSelect('p.nombre AS puesto')
            ->addSelect('cd.cantidad')
            ->addSelect('cd.lunes')
            ->addSelect('cd.martes')
            ->addSelect('cd.miercoles')
            ->addSelect('cd.jueves')
            ->addSelect('cd.viernes')
            ->addSelect('cd.sabado')
            ->addSelect('cd.domingo')
            ->addSelect('cd.festivo')
            ->addSelect('cd.vrSalarioBase')
            ->addSelect('cd.vrPrecioMinimo')
            ->addSelect('cd.vrPrecioAjustado')
            ->addSelect('cd.porcentajeBaseIva')
            ->addSelect('cd.porcentajeIva')
            ->addSelect('cd.vrIva')
            ->addSelect('cd.vrSubtotal')
            ->addSelect('cd.fechaDesde')
            ->addSelect('cd.fechaHasta')
            ->addSelect('cd.horas')
            ->addSelect('cd.horasDiurnas')
            ->addSelect('cd.horasNocturnas')
            ->addSelect('con.nombre as conceptoNombre')
            ->addSelect('mod.nombre as modalidadNombre')
            ->leftJoin('cd.conceptoRel', 'con')
            ->leftJoin('cd.modalidadRel', 'mod')
            ->leftJoin('cd.puestoRel', 'p')
            ->where("cd.codigoContratoFk = {$id}")
            ->andWhere('cd.estadoCerrado = 0');

        return $queryBuilder;
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arContratos
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arContratos)
    {
        $em = $this->getEntityManager();

        if ($this->getEntityManager()->getRepository(TurContrato::class)->contarDetalles($arContratos->getCodigoContratoPk()) > 0) {
            $arrPrecioAjustado = $arrControles['arrPrecioAjustado'];
            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoContratoDetalle) {
                $arContratoDetalle = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->find($codigoContratoDetalle);
                $arContratoDetalle->setHoras($arContratoDetalle->getConceptoRel()->getHoras());
                $arContratoDetalle->setHorasDiurnas($arContratoDetalle->getConceptoRel()->getHorasDiurnas());
                $arContratoDetalle->setHorasNocturnas($arContratoDetalle->getConceptoRel()->getHorasNocturnas());
                $arContratoDetalle->setVrSalarioBase($arContratoDetalle->getVrSalarioBase());
                $arContratoDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoContratoDetalle]);
                $arContratoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoContratoDetalle]);
                $arContratoDetalle->setVrSubtotal($arContratoDetalle->getVrPrecioAjustado() * $arContratoDetalle->getCantidad());
                $arContratoDetalle->setVrIva($arContratoDetalle->getVrSubtotal() * $arContratoDetalle->getPorcentajeIva() / 100);
                $arContratoDetalle->setVrTotalDetalle($arContratoDetalle->getVrSubtotal() + $arContratoDetalle->getVrIva());
                $em->persist($arContratoDetalle);
                $em->flush();
            }
            $em->getRepository(TurContrato::class)->liquidar($arContratos);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arRegistro TurContrato
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrDetallesSeleccionados, $id)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TurContrato::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->find($codigo);
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd')
            ->select('cd')
            ->leftJoin('cd.contratoRel', 'c');
        if ($session->get('filtroRhuInformeContratoDetalleCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClienteFk  = '{$session->get('filtroRhuInformeContratoDetalleCodigoCliente')}'");
        }
        if ($session->get('filtroRhuInformeContratoDetalleFechaDesde') != null) {
            $queryBuilder->andWhere("cd.fechaDesde >= '{$session->get('filtroRhuInformeContratoDetalleFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroRhuInformeContratoDetalleFechaHasta') != null) {
            $queryBuilder->andWhere("cd.fechaHasta <= '{$session->get('filtroRhuInformeContratoDetalleFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

}
