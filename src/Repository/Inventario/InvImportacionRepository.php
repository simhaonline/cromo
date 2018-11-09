<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionCosto;
use App\Entity\Inventario\InvImportacionDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvImportacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacion::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacion::class, 'i')
            ->select('i.codigoImportacionPk')
            ->addSelect('i.numero')
            ->addSelect('i.fecha')
            ->addSelect('it.nombre')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->addSelect('i.vrSubtotal')
            ->addSelect('i.vrIva')
            ->addSelect('i.vrNeto')
            ->addSelect('i.vrTotal')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->leftJoin('i.terceroRel', 't')
            ->leftJoin('i.importacionTipoRel', 'it')
            ->where('i.codigoImportacionPk <> 0')
            ->orderBy('i.codigoImportacionPk', 'DESC');
        if ($session->get('filtroInvNumeroImportacion')) {
            $queryBuilder->andWhere("i.numero = {$session->get('filtroInvNumeroImportacion')}");
        }
        if ($session->get('filtroInvImportacionTipo')) {
            $queryBuilder->andWhere("i.codigoImportacionTipoFk = '{$session->get('filtroInvImportacionTipo')}'");
        }
        if ($session->get('filtroInvCodigoTercero')) {
            $queryBuilder->andWhere("i.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        return $queryBuilder;

    }

    /**
     * @param $codigoImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoImportacion)
    {
        $em = $this->getEntityManager();
        $arImportacion = $em->getRepository(InvImportacion::class)->find($codigoImportacion);
        $arImportacionDetalles = $em->getRepository(InvImportacionDetalle::class)->findBy(['codigoImportacionFk' => $codigoImportacion]);
        $subtotalGeneral = 0;
        $ivaGeneral = 0;
        $totalGeneral = 0;
        foreach ($arImportacionDetalles as $arImportacionDetalle) {
            $arImportacionDetalleAct = $em->getRepository(InvImportacionDetalle::class)->find($arImportacionDetalle->getCodigoImportacionDetallePk());
            $subtotal = $arImportacionDetalle->getCantidad() * $arImportacionDetalle->getVrPrecioExtranjero();
//            $porcentajeIva = $arImportacionDetalle->getPorcentajeIva();
//            $iva = $subtotal * $porcentajeIva / 100;
            $subtotalGeneral += $subtotal;
//            $ivaGeneral += $iva;
//            $total = $subtotal + $iva;
            $total = $subtotal;
            $totalGeneral += $total;
            $arImportacionDetalleAct->setVrSubtotalExtranjero($subtotal);
//            $arImportacionDetalleAct->setVrIva($iva);
            $arImportacionDetalleAct->setVrTotalExtranjero($total);
            $em->persist($arImportacionDetalleAct);
        }
        $vrTotalCosto = $em->getRepository(InvImportacionCosto::class)->totalCostos($arImportacion->getCodigoImportacionPk());
        $arImportacion->setVrTotalCosto($vrTotalCosto);
        $arImportacion->setVrSubtotal($subtotalGeneral);
        $arImportacion->setVrIva($ivaGeneral);
        $arImportacion->setVrTotal($totalGeneral);
        $em->persist($arImportacion);
        $em->flush();
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($this->contarDetalles($arImportacion->getCodigoImportacionPk()) > 0) {
            if (!$arImportacion->getEstadoAutorizado()) {
                $arImportacion->setEstadoAutorizado(1);
                $em->persist($arImportacion);
                $em->flush();
            }
        } else {
            Mensajes::error('El movimiento no tiene detalles');
        }
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($arImportacion->getEstadoAutorizado() && !$arImportacion->getEstadoAnulado()) {
            $arImportacion->setEstadoAprobado(1);
            $em->persist($arImportacion);
            $em->flush();
        }
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($arImportacion->getEstadoAprobado()) {
            $arImportacion->setEstadoAnulado(1);
            $em->persist($arImportacion);
            $em->flush();
        }
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($arImportacion->getEstadoAutorizado()) {
            $arImportacion->setEstadoAutorizado(0);
            $em->persist($arImportacion);
            $em->flush();
        }
    }

    /**
     * @param $id
     * @param $arrControles
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($id, $arrControles)
    {
        if (isset($arrControles['TxtCodigo'])) {
            $em = $this->getEntityManager();
            $arrCantidades = $arrControles['TxtCantidad'];
            $arrPrecios = $arrControles['TxtPrecioExtranjero'];
            if ($arrControles) {
                foreach ($arrControles['TxtCodigo'] as $codigoImportacionDetalle) {
                    $arImportacionDetalle = $em->getRepository(InvImportacionDetalle::class)->find($codigoImportacionDetalle);
                    if ($arImportacionDetalle) {
                        $arImportacionDetalle->setCantidad($arrCantidades[$codigoImportacionDetalle]);
                        $arImportacionDetalle->setVrPrecioExtranjero($arrPrecios[$codigoImportacionDetalle]);
                        $em->persist($arImportacionDetalle);
                    }
                }
                $em->flush();
            }
        }
    }

    /**
     * @param $codigoImportacion integer
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function contarDetalles($codigoImportacion){
        return $this->_em->createQueryBuilder()->from(InvImportacionDetalle::class, 'imd')
            ->select('COUNT(imd.codigoImportacionDetallePk)')
            ->where("imd.codigoImportacionFk = {$codigoImportacion}")->getQuery()->getSingleResult()[1];
    }
}
