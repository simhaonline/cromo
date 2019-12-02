<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCuenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinCuentaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinCuenta::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinCuenta::class, 'c')
            ->select('c.codigoCuentaPk')
            ->addSelect('c.nombre')
            ->where('c.codigoCuentaPk IS NOT NULL');
        if ($session->get('filtroFinBuscarCuentaCodigo') != '') {
            $queryBuilder->andWhere("c.codigoCuentaPk LIKE '{$session->get('filtroFinBuscarCuentaCodigo')}%'");
        }
        if ($session->get('filtroFinBuscarCuentaNombre') != '') {
            $queryBuilder->andWhere("c.nombre LIKE '%{$session->get('filtroFinBuscarCuentaNombre')}%'");
        }
        return $queryBuilder;
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(FinCuenta::class, 'c')
            ->select('c.codigoCuentaPk as ID')
            ->addSelect('c.nombre')
            ->addSelect('c.permiteMovimiento as Permite_movimientos')
            ->addSelect('c.exigeTercero as Exige_Tercero')
            ->addSelect('c.exigeCentroCosto as Exige_centro_de_costo')
            ->addSelect('c.exigeBase as Exige_base')
            ->where('c.codigoCuentaPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }

    public function generarEstructura()
    {
        $em = $this->getEntityManager();
        $arCuentas = $em->getRepository(FinCuenta::class)->findAll();
        foreach ($arCuentas as $arCuenta) {
            $clase = substr($arCuenta->getCodigoCuentaPk(),0,1);
            $grupo = substr($arCuenta->getCodigoCuentaPk(),0,2);
            $cuenta = substr($arCuenta->getCodigoCuentaPk(),0,4);
            $subcuenta = substr($arCuenta->getCodigoCuentaPk(),0,6);
            if(strlen($grupo) != 2) {
                $grupo = "";
            }
            if(strlen($cuenta) != 4) {
                $cuenta = "";
            }
            if(strlen($subcuenta) != 6) {
                $subcuenta = "";
            }
            $arCuenta->setClase($clase);
            $arCuenta->setGrupo($grupo);
            $arCuenta->setCuenta($cuenta);
            $arCuenta->setSubcuenta($subcuenta);
            $em->persist($arCuenta);
        }
        $em->flush();
        return true;
    }

}