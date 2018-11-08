<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionDetalle;
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacion::class,'i')
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
            ->orderBy('i.codigoImportacionPk','DESC');
        if($session->get('filtroInvNumeroImportacion')) {
            $queryBuilder->andWhere("i.numero = {$session->get('filtroInvNumeroImportacion')}");
        }
        if($session->get('filtroInvImportacionTipo')) {
            $queryBuilder->andWhere("i.codigoImportacionTipoFk = '{$session->get('filtroInvImportacionTipo')}'");
        }
        if($session->get('filtroInvCodigoTercero')){
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
            /*$arImportacionDetalleAct = $em->getRepository(InvImportacionDetalle::class)->find($arImportacionDetalle->getCodigoImportacionDetallePk());
            $subtotal = $arImportacionDetalle->getCantidad() * $arImportacionDetalle->getVrPrecio();
            $porcentajeIva = $arImportacionDetalle->getPorcentajeIva();
            $iva = $subtotal * $porcentajeIva / 100;
            $subtotalGeneral += $subtotal;
            $ivaGeneral += $iva;
            $total = $subtotal + $iva;
            $totalGeneral += $total;
            $arImportacionDetalleAct->setVrSubtotal($subtotal);
            $arImportacionDetalleAct->setVrIva($iva);
            $arImportacionDetalleAct->setVrTotal($total);
            $em->persist($arImportacionDetalleAct);*/
        }
        $arImportacion->setVrSubtotal($subtotalGeneral);
        $arImportacion->setVrIva($ivaGeneral);
        $arImportacion->setVrTotal($totalGeneral);
        $em->persist($arImportacion);
        $em->flush();
    }    
    
}
