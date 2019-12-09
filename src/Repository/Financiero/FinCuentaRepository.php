<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinCuenta;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class FinCuentaRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinCuenta::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoCuenta = null;
        $nombre = null;
        if ($filtros) {
            $codigoCuenta = $filtros['codigoCuenta'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinCuenta::class, 'c')
            ->select('c.codigoCuentaPk')
            ->addSelect('c.nombre')
            ->addSelect('c.clase')
            ->addSelect('c.grupo')
            ->addSelect('c.cuenta')
            ->addSelect('c.subcuenta')
            ->addSelect('c.exigeTercero')
            ->addSelect('c.exigeCentroCosto')
            ->addSelect('c.exigeBase')
            ->addSelect('c.permiteMovimiento');
        if ($codigoCuenta) {
            $queryBuilder->andWhere("c.codigoCuentaPk LIKE '%{$codigoCuenta}%'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("c.nombre LIKE '%{$nombre}%'");
        }
        $queryBuilder->addOrderBy('c.codigoCuentaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
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

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(FinCuenta::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }

}