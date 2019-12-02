<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDocumental;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;

class TteDocumentalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteDocumental::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoDocumentalPk = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAnulado = null;
        $estadoAprobado = null;
        if ($filtros){
            $codigoDocumentalPk = $filtros['codigoDocumentalPk'];
            $fechaDesde = $filtros['fechaDesde'];
            $fechaHasta = $filtros['fechaHasta'];
            $estadoAutorizado = $filtros['estadoAutorizado'];
            $estadoAnulado = $filtros['estadoAnulado'];
            $estadoAprobado= $filtros['estadoAprobado'];
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDocumental::class, 'doc');
        $queryBuilder
            ->select('doc.codigoDocumentalPk')
            ->addSelect('doc.fecha')
            ->addSelect('doc.comentario')
            ->addSelect('doc.estadoAutorizado')
            ->addSelect('doc.estadoAprobado')
            ->addSelect('doc.estadoAnulado')
            ->where('doc.codigoDocumentalPk <> 0');
        $queryBuilder->orderBy('doc.fecha', 'DESC');

        if ($codigoDocumentalPk) {
            $queryBuilder->andWhere("doc.codigoDocumentalPk = '{$codigoDocumentalPk}'");
        }

        if ($fechaDesde) {
            $queryBuilder->andWhere("doc.fecha >= '{$fechaDesde} 00:00:00'");
        }

        if ($fechaHasta) {
            $queryBuilder->andWhere("doc.fecha <= '{$fechaHasta} 23:59:59'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("doc.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("doc.estadoAutorizado = 1");
                break;
        }

        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("doc.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("doc.estadoAprobado = 1");
                break;
        }

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("doc.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("doc.estadoAnulado = 1");
                break;
        }
        
        return $queryBuilder;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteDocumental::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteGuia::class)->findBy(['codigoDocumentalFk' => $arRegistro->getCodigoDocumentalPk()])) <= 0) {
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
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    /**
     * @param $codigoDocumental TteDocumental
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoDocumental): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from("App:Transporte\TteGuia", "g")
            ->select("COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen")
            ->where("g.codigoDocumentalFk = {$codigoDocumental}");
        $arGuias = $query->getQuery()->getSingleResult();
        $arDocumental = $em->getRepository(TteDocumental::class)->find($codigoDocumental);
        $arDocumental->setCantidad(intval($arGuias['cantidad']));
        $em->persist($arDocumental);
        $em->flush();
        return true;
    }

    /**
     * @param $arrGuias TteGuia
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function retirarGuia($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if ($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $arGuia->setDocumentalRel(null);
                    $arGuia->setEstadoDocumental(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arDocumental TteDocumental
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arDocumental)
    {
        $arDocumental->setEstadoAutorizado(1);
        $this->getEntityManager()->persist($arDocumental);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arDocumental TteDocumental
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arDocumental)
    {
        $arDocumental->setEstadoAprobado(1);
        $fecha = new \DateTime('now');
        $arDocumental->setFecha($fecha);
        $this->getEntityManager()->persist($arDocumental);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arDocumental TteDocumental
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desAutorizar($arDocumental)
    {
        if ($arDocumental->getEstadoAutorizado() == 1 && $arDocumental->getEstadoAprobado() == 0) {
            $arDocumental->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arDocumental);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    /**
     * @param $arDocumental TteDocumental
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arDocumental){
        $em = $this->getEntityManager();
        if($arDocumental->getEstadoAutorizado() == 1 ) {
            if($arDocumental->getEstadoAnulado() == 0){
                $arGuias = $em->getRepository(TteGuia::class)->findBy(['codigoDocumentalFk' => $arDocumental->getCodigoDocumentalPk()]);
                foreach ($arGuias AS $arGuia) {
                    $arGuia->setDocumentalRel(null);
                    $arGuia->setEstadoDocumental(0);
                    $arGuia->setFechaDocumental(null);
                    $em->persist($arGuia);
                }
                $arDocumental->setEstadoAnulado(1);
                $arDocumental->setCantidad(0);
                $em->persist($arDocumental);
                $em->flush();
            }else{
                Mensajes::error('La relacion documental ya esta anulado');
            }

        } else {
            Mensajes::error('La relacion documental no esta autorizada');
        }
    }

}