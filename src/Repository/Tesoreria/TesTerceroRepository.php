<?php

namespace App\Repository\Tesoreria;

use App\Entity\Financiero\FinTercero;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TesTerceroRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TesTercero::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoTerceroPk = null;
        $nombreCorto = null;
        $numeroIdentificacion =null;
        if ($filtros){
            $codigoTerceroPk = $filtros['codigoTerceroPk']??null;
            $nombreCorto = $filtros['nombreCorto']??null;
            $numeroIdentificacion = $filtros['numeroIdentificacion']??null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.telefono')
            ->addSelect('t.direccion')
            ->addSelect('t.email')
            ->where('t.codigoTerceroPk <> 0')
            ->orderBy('t.codigoTerceroPk', 'DESC');
        if($codigoTerceroPk){
            $queryBuilder->andWhere("t.codigoTerceroPk = {$codigoTerceroPk}");
        }
        if($nombreCorto){
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$nombreCorto}%'");
        }
        if($numeroIdentificacion){
            $queryBuilder->andWhere("t.numeroIdentificacion = '{$numeroIdentificacion}' ");
        }
        $queryBuilder->addOrderBy('t.codigoTerceroPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComProveedor::class, 'p')
            ->select('p.codigoProveedorPk')
            ->addSelect('p.numeroIdentificacion')
            ->addSelect('p.nombreCorto')
            ->addSelect('p.nombre1')
            ->addSelect('p.nombre2')
            ->addSelect('p.apellido1')
            ->addSelect('p.apellido2')
            ->addSelect('p.telefono')
            ->addSelect('p.direccion')
            ->addSelect('p.fax')
            ->addSelect('p.plazoPago')
            ->where('p.codigoProveedorPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    /**
     *
     */
    public function eliminar($arrSeleccion)
    {
        $em = $this->getEntityManager();
        try {
            foreach ($arrSeleccion as $codigoProveedor) {
                $arProveedor = $em->getRepository(ComProveedor::class)->find($codigoProveedor);
                $em->remove($arProveedor);
                $em->flush();
            }
        } catch (\Exception $ex) {
            Mensajes::error('No se puede eliminar, el registro se encuentra relacionado con algun documento');
        }

    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arCliente = $em->getRepository(TesTercero::class)->find($codigo);
        if($arCliente) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arCliente->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arCliente->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arCliente->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arCliente->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arCliente->getNombreCorto());
                $arTercero->setDireccion($arCliente->getDireccion());
                $arTercero->setTelefono($arTercero->getTelefono());
                //$arTercero->setEmail($arCliente->getCorreo());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

    public function unificar()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesTercero::class, 't')
            ->select('COUNT(t.codigoTerceroPk) as cantidad')
            ->addSelect('t.codigoIdentificacionFk')
            ->addSelect('t.numeroIdentificacion')
            ->groupBy('t.codigoIdentificacionFk')
            ->addGroupBy('t.numeroIdentificacion');
        $arTerceros = $queryBuilder->getQuery()->getResult();
        foreach ($arTerceros as $arTercero) {
            if($arTercero['cantidad']>1) {
                $arTerceroFijo = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arTercero['codigoIdentificacionFk'], 'numeroIdentificacion' => $arTercero['numeroIdentificacion']), array('codigoTerceroPk' => 'ASC'));
                $arTercerosActualizar = $em->getRepository(TesTercero::class)->findBy(array('codigoIdentificacionFk' => $arTercero['codigoIdentificacionFk'], 'numeroIdentificacion' => $arTercero['numeroIdentificacion']));
                foreach ($arTercerosActualizar as $arTerceroActualizar) {
                    if($arTerceroActualizar->getCodigoTerceroPk() != $arTerceroFijo->getCodigoTerceroPk()) {
                        $arCuentasPagar = $em->getRepository(TesCuentaPagar::class)->findBy(array('codigoTerceroFk' => $arTerceroActualizar->getCodigoTerceroPk()));
                        foreach ($arCuentasPagar as $arCuentaPagar) {
                            $arCuentaPagar->setTerceroRel($arTerceroFijo);
                            $em->persist($arCuentaPagar);
                        }

                        $arMovimientos = $em->getRepository(TesMovimiento::class)->findBy(array('codigoTerceroFk' => $arTerceroActualizar->getCodigoTerceroPk()));
                        foreach ($arMovimientos as $arMovimiento) {
                            $arMovimiento->setTerceroRel($arTerceroFijo);
                            $em->persist($arMovimiento);
                        }

                        $arMovimientosDetalle = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoTerceroFk' => $arTerceroActualizar->getCodigoTerceroPk()));
                        foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                            $arMovimientoDetalle->setTerceroRel($arTerceroFijo);
                            $em->persist($arMovimientoDetalle);
                        }

                        $em->remove($arTerceroActualizar);
                    }
                }
            }
        }
        $em->flush();
        return true;
    }

    public function autoCompletar($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $nombreCorto = null;
        $numeroIdentificacion = null;
        if ($filtros){
            $nombreCorto = $filtros['nombreCorto']??null;
            $numeroIdentificacion = $filtros['numeroIdentificacion']??null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesTercero::class, 't')
            ->select('t.codigoTerceroPk as value')
            ->addSelect("CONCAT(t.nombreCorto, ' ', t.numeroIdentificacion)  as label")
            ->orderBy('t.nombreCorto', 'ASC');
        if($nombreCorto){
            $queryBuilder->orWhere("t.nombreCorto LIKE '%{$nombreCorto}%'");
        }
        if($numeroIdentificacion){
            $queryBuilder->orWhere("t.numeroIdentificacion LIKE '%{$numeroIdentificacion}%'");
        }
        $queryBuilder->addOrderBy('t.codigoTerceroPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
}