<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteEntidad;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporte::class);
    }

    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $anio = null;
        $mes = null;

        if ($filtros) {
            $anio = $filtros['anio'] ?? null;
            $mes = $filtros['mes'] ?? null;
        }

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporte::class, 'a')
            ->select('a.codigoAportePk')
            ->addSelect('a.anio')
            ->addSelect('a.mes')
            ->addSelect('s.nombre as sucursalNombre')
            ->addSelect('a.cantidadContratos')
            ->addSelect('a.cantidadEmpleados')
            ->addSelect('a.formaPresentacion')
            ->addSelect('a.vrTotal')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoAnulado')
            ->leftJoin('a.sucursalRel', 's')
            ->orderBy('a.codigoAportePk', 'DESC');
        if ($anio) {
            $queryBuilder->andWhere("a.anio  = '{$anio}'");
        }
        if ($mes) {
            $queryBuilder->andWhere("a.mes  = '{$mes}'");
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function autorizar($arAporte)
    {
        $em = $this->getEntityManager();
        if(!$arAporte->getEstadoAutorizado()) {
            $em->getRepository(RhuAporteSoporte::class)->generar($arAporte);
            $em->getRepository(RhuAporteDetalle::class)->generar($arAporte);
            $arAporte->setEstadoAutorizado(1);
            $em->persist($arAporte);
            $em->flush();
        }
    }

    public function desAutorizar($arAporte)
    {
        $em = $this->getEntityManager();
        if ($arAporte->getEstadoAutorizado() == 1 && $arAporte->getEstadoAprobado() == 0) {
            $arAporte->setEstadoAutorizado(0);
            $em->persist($arAporte);
            $em->createQueryBuilder()->delete(RhuAporteDetalle::class,'ad')->andWhere("ad.codigoAporteFk = " . $arAporte->getCodigoAportePk())->getQuery()->execute();
            $em->createQueryBuilder()->delete(RhuAporteSoporte::class,'aso')->andWhere("aso.codigoAporteFk = " . $arAporte->getCodigoAportePk())->getQuery()->execute();
            $em->createQueryBuilder()->delete(RhuAporteEntidad::class,'ae')->andWhere("ae.codigoAporteFk = " . $arAporte->getCodigoAportePk())->getQuery()->execute();
            $em->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    public function aprobar($arAporte): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        if (!$arAporte->getEstadoAprobado() && $arAporte->getEstadoAutorizado()) {
            $arAporte->setEstadoAprobado(1);
            $em->persist($arAporte);
            $em->flush();
        } else {
            $respuesta = "El documento no puede estar previamente aprobado y debe estar autorizado";
        }

        return $respuesta;
    }

    public function anular($arAporte): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        if($arAporte->getEstadoContabilizado() == 0) {
            if($arAporte->getEstadoAprobado() == 1) {
                if($arAporte->getEstadoAnulado() == 0) {
                    $arAporte->setEstadoAnulado(1);
                    $em->persist($arAporte);
                    $em->flush();
                } else {
                    Mensajes::error("La factura no puede estar previamente anulada");
                }
            } else {
                Mensajes::error("La factura debe estar aprobada");
            }
        } else {
            Mensajes::error("La factura ya esta contabilizada");
        }

        return $respuesta;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuAporte::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(RhuAporteDetalle::class)->findBy(['codigoAporteFk' => $arRegistro->getCodigoAportePk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    public function liquidar($arAporte) {
        $em = $this->getEntityManager();
        //Salud
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadSaludFk')
            ->addSelect('SUM(ad.cotizacionSalud) as cotizacionSalud')
            ->groupBy('ad.codigoEntidadSaludFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('SALUD');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadSaludFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionSalud']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();

        //Pension
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadPensionFk')
            ->addSelect('SUM(ad.cotizacionPension) as cotizacionPension')
            ->groupBy('ad.codigoEntidadPensionFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('PENSION');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadPensionFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionPension']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();

        //Arl
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadRiesgoFk')
            ->addSelect('SUM(ad.cotizacionRiesgos) as cotizacionRiesgos')
            ->groupBy('ad.codigoEntidadRiesgoFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('RIESGO');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadRiesgoFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionRiesgos']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();

        //Caja
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteDetalle::class, 'ad')
            ->select('ad.codigoEntidadCajaFk')
            ->addSelect('SUM(ad.cotizacionCaja) as cotizacionCaja')
            ->groupBy('ad.codigoEntidadCajaFk')
            ->where("ad.codigoAporteFk = {$arAporte->getCodigoAportePk()}");
        $arAporteDetalles = $queryBuilder->getQuery()->getResult();
        foreach ($arAporteDetalles as $arAporteDetalle) {
            $arAporteEntidad = new RhuAporteEntidad();
            $arAporteEntidad->setAporteRel($arAporte);
            $arAporteEntidad->setTipo('CAJA');
            $arAporteEntidad->setEntidadRel($em->getReference(RhuEntidad::class, $arAporteDetalle['codigoEntidadCajaFk']));
            $arAporteEntidad->setVrAporte($arAporteDetalle['cotizacionCaja']);
            $em->persist($arAporteEntidad);
        }
        $em->flush();
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        return true;
    }

    public function ibcMesAnterior($anio, $mes, $codigoEmpleado)
    {
        if ($mes == 1) {
            $anio -= 1;
            $mes = 12;
        } else {
            $mes -= 1;
        }
        $arrResultado = array('respuesta' => false, 'ibc' => 0, 'dias' => 0);
        $em = $this->getEntityManager();
        $dql = "SELECT SUM(ssoa.ibcPension) as ibcPension, SUM(ssoa.ibcSalud) as ibcSalud, SUM(ssoa.diasCotizadosPension) as diasPension, SUM(ssoa.diasCotizadosSalud) as diasSalud FROM App\Entity\RecursoHumano\RhuAporte ssoa "
            . "WHERE ssoa.anio = $anio AND ssoa.mes = $mes" . " "
            . "AND ssoa.codigoEmpleadoFk = " . $codigoEmpleado;
        $query = $em->createQuery($dql);
        $arrayResultado = $query->getResult();
        $resultados = $arrayResultado[0];
        if ($resultados['ibcPension'] == null) {
            if ($mes == 1) {
                $anio -= 1;
                $mes = 12;
            } else {
                $mes -= 1;
            }
            $arrResultado = array('respuesta' => false, 'ibc' => 0, 'dias' => 0);
            $em = $this->getEntityManager();
            $dql = "SELECT SUM(ssoa.ibcPension) as ibcPension, SUM(ssoa.diasCotizadosPension) as diasPension, SUM(ssoa.ibcSalud) as ibcSalud,SUM(ssoa.diasCotizadosSalud) as diasSalud FROM App\Entity\RecursoHumano\RhuAporte ssoa "
                . "WHERE ssoa.anio = $anio AND ssoa.mes = $mes" . " "
                . "AND ssoa.codigoEmpleadoFk = " . $codigoEmpleado;
            $query = $em->createQuery($dql);
            $arrayResultado = $query->getResult();
            $resultados = $arrayResultado[0];
            if ($resultados['ibcPension'] == null) {
                $arrResultado['ibc'] = 0;
                $arrResultado['dias'] = 0;
                $arrResultado['respuesta'] = false;
            } else {
                $arrResultado['ibc'] = $resultados['ibcPension'];
                $arrResultado['dias'] = $resultados['diasPension'];
                if ($arrResultado['ibc'] == 0 && $arrResultado['dias'] == 0) {
                    $arrResultado['ibc'] = $resultados['ibcSalud'];
                    $arrResultado['dias'] = $resultados['diasSalud'];
                }
                $arrResultado['respuesta'] = true;
            }
        } else {
            $arrResultado['ibc'] = $resultados['ibcPension'];
            $arrResultado['dias'] = $resultados['diasPension'];
            if ($arrResultado['ibc'] == 0 && $arrResultado['dias'] == 0) {
                $arrResultado['ibc'] = $resultados['ibcSalud'];
                $arrResultado['dias'] = $resultados['diasSalud'];
            }
            $arrResultado['respuesta'] = true;
        }
        return $arrResultado;
    }


}