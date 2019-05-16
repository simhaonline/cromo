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
            ->addSelect('cd.horaInicio')
            ->addSelect('cd.horaFin')
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
            ->addSelect('cd.liquidarDiasReales')
            ->addSelect('cd.fechaDesde')
            ->addSelect('cd.fechaHasta')
            ->addSelect('cd.horas')
            ->addSelect('cd.horasDiurnas')
            ->addSelect('cd.horasNocturnas')
            ->addSelect('coc.nombre as nombreConcepto')
            ->addSelect('com.nombre as nombreModalidad')
            ->leftJoin('cd.contratoConceptoRel', 'coc')
            ->leftJoin('cd.contratoModalidadRel', 'com')
            ->where("cd.codigoContratoFk = {$id}");

        return $queryBuilder;
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arContratos TurContrato
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
            $arrPorcentajeBaseIva = $arrControles['arrPorcentajeBaseIva'];
            $arrPorcentajeIva = $arrControles['arrPorcentajeIva'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoContratoDetalle) {
                $arContratoDetalle = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->find($codigoContratoDetalle);
                $arContratoDetalle->setVrCosto($arContratoDetalle->getCantidad() * $arContratoDetalle->getContratoConceptoRel()->getVrCosto());
                $arContratoDetalle->setHoras($arContratoDetalle->getContratoConceptoRel()->getHoras());
                $arContratoDetalle->setHorasDiurnas($arContratoDetalle->getContratoConceptoRel()->getHorasDiurnas());
                $arContratoDetalle->setHorasNocturnas($arContratoDetalle->getContratoConceptoRel()->getHorasNocturnas());
                $arContratoDetalle->setVrSalarioBase($arContratoDetalle->getVrSalarioBase());
                $arContratoDetalle->setVrPrecioAjustado($arrPrecioAjustado[$codigoContratoDetalle]);
                $arContratoDetalle->setPorcentajeIva($arrPorcentajeIva[$codigoContratoDetalle]);
                $arContratoDetalle->setPorcentajeBaseIva($arrPorcentajeBaseIva[$codigoContratoDetalle]);
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
    public function eliminar($arrDetallesSeleccionados,$id)
    {
//        $em = $this->getDoctrine()->getManager();
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

}
