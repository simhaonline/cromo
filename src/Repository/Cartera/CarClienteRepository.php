<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCompromiso;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Financiero\FinTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class CarClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarCliente::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Cartera\CarCliente','cl')
            ->select('cl.codigoClientePk AS ID')
            ->addSelect('cl.nombreCorto AS NOMBRE ');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCliente = null;
        $identificacion = null;
        $nombre = null;
        $identificacion = null;

        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $identificacion = $filtros['identificacion'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCliente::class, 'cc')
            ->select('cc.codigoClientePk')
            ->addSelect('cc.nombreCorto')
            ->addSelect('cc.numeroIdentificacion')
            ->where('cc.codigoClientePk <> 0')
            ->orderBy('cc.codigoClientePk', 'DESC');

        if ($nombre) {
            $queryBuilder->andWhere("cc.nombreCorto LIKE '%{$nombre}%' ");
        }
        if ($identificacion) {
            $queryBuilder->andWhere("cc.numeroIdentificacion LIKE '%{$identificacion}%' ");
        }
        if ($codigoCliente) {
            $queryBuilder->andWhere("cc.codigoClientePk LIKE '%{$codigoCliente}%' ");
        }

        $queryBuilder->addOrderBy('cc.codigoClientePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(CarCliente::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                Mensajes::error('No se puede eliminar, el registro esta siendo utilizado en el sistema');
            }
        }
    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arCliente = $em->getRepository(CarCliente::class)->find($codigo);
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCliente::class, 'c')
            ->select('COUNT(c.codigoClientePk) as cantidad')
            ->addSelect('c.codigoIdentificacionFk')
            ->addSelect('c.numeroIdentificacion')
            ->groupBy('c.codigoIdentificacionFk')
            ->addGroupBy('c.numeroIdentificacion');
        $arClientes = $queryBuilder->getQuery()->getResult();
        foreach ($arClientes as $arCliente) {
            if($arCliente['cantidad']>1) {
                $arClienteFijo = $em->getRepository(CarCliente::class)->findOneBy(array('codigoIdentificacionFk' => $arCliente['codigoIdentificacionFk'], 'numeroIdentificacion' => $arCliente['numeroIdentificacion']), array('codigoClientePk' => 'ASC'));
                $arClientesActualizar = $em->getRepository(CarCliente::class)->findBy(array('codigoIdentificacionFk' => $arCliente['codigoIdentificacionFk'], 'numeroIdentificacion' => $arCliente['numeroIdentificacion']));
                foreach ($arClientesActualizar as $arClienteActualizar) {
                    if($arClienteActualizar->getCodigoClientePk() != $arClienteFijo->getCodigoClientePk(9)) {
                        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->findBy(array('codigoClienteFk' => $arClienteActualizar->getCodigoClientePk()));
                        foreach ($arCuentasCobrar as $arCuentaCobrar) {
                            $arCuentaCobrar->setClienteRel($arClienteFijo);
                            $em->persist($arCuentaCobrar);
                        }

                        $arRecibos = $em->getRepository(CarRecibo::class)->findBy(array('codigoClienteFk' => $arClienteActualizar->getCodigoClientePk()));
                        foreach ($arRecibos as $arRecibo) {
                            $arRecibo->setClienteRel($arClienteFijo);
                            $em->persist($arRecibo);
                        }

                        $arAnticipos = $em->getRepository(CarAnticipo::class)->findBy(array('codigoClienteFk' => $arClienteActualizar->getCodigoClientePk()));
                        foreach ($arAnticipos as $arAnticipo) {
                            $arAnticipo->setClienteRel($arClienteFijo);
                            $em->persist($arAnticipo);
                        }

                        $arCompromisos = $em->getRepository(CarCompromiso::class)->findBy(array('codigoClienteFk' => $arClienteActualizar->getCodigoClientePk()));
                        foreach ($arCompromisos as $arCompromiso) {
                            $arCompromiso->setClienteRel($arClienteFijo);
                            $em->persist($arCompromiso);
                        }
                        $em->remove($arClienteActualizar);
                    }
                }
            }
        }
        $em->flush();
        return true;
    }
}