<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRecaudoCobro;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRecaudoCobroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteRecaudoCobro::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecaudoCobro::class, 'rc');
        $queryBuilder
            ->select('rc.codigoRecaudoCobroPk')
            ->addSelect('rc.fecha')
            ->addSelect('rc.vrTotal')
            ->addSelect('rc.usuario')
            ->addSelect('rc.estadoAutorizado')
            ->addSelect('rc.estadoAprobado')
            ->addSelect('rc.estadoAnulado')
            ->where('rc.codigoRecaudoCobroPk <> 0');
        $queryBuilder->orderBy('rc.fecha', 'DESC');

        switch ($session->get('TteRecaudoCobro_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("rc.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("rc.estadoAutorizado = 1");
                break;
        }

        switch ($session->get('TteRecaudoCobro_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("rc.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("rc.estadoAprobado = 1");
                break;
        }

        switch ($session->get('TteRecaudoCobro_estadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("rc.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("rc.estadoAnulado = 1");
                break;
        }
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteRecaudoCobro::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteGuia::class)->findBy(['codigoRecaudoCobroFk' => $arRegistro->getCodigoRecaudoCobroPk()])) <= 0) {
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

    public function retirarGuia($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $arGuia->setRecaudoCobroRel(null);
                    $arGuia->setEstadoRecaudoCobro(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function liquidar($codigoRecaudo): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from("App:Transporte\TteGuia","g")
            ->select("COUNT(g.codigoGuiaPk) as cantidad, SUM(g.vrRecaudo+0) as vrTotalRecaudo")
            ->where("g.codigoRecaudoCobroFk = {$codigoRecaudo}");
        $arGuias = $query->getQuery()->getSingleResult();
        $arRecaudoCobro = $em->getRepository(TteRecaudoCobro::class)->find($codigoRecaudo);
        $arRecaudoCobro->setCantidad(intval($arGuias['cantidad']));
        $arRecaudoCobro->setVrTotal(intval($arGuias['vrTotalRecaudo']));
        $em->persist($arRecaudoCobro);
        $em->flush();
        return true;
    }

    public function autorizar($arRecaudoCobro){
        if($this->getEntityManager()->getRepository(TteRecaudoCobro::class)->contarDetalles($arRecaudoCobro->getCodigoRecaudoCobroPk()) > 0){
            $arRecaudoCobro->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arRecaudoCobro);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no tiene detalles');
        }
    }

    public function desautorizar($arRecaudoCobro)
    {
        if ($arRecaudoCobro->getEstadoAutorizado() == 1 && $arRecaudoCobro->getEstadoAprobado() == 0) {
            $arRecaudoCobro->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arRecaudoCobro);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta aprobado y no se puede desautorizar');
        }
    }

    public function aprobar($arRecaudoCobro)
    {
        if($arRecaudoCobro->getEstadoAutorizado() == 1 && $arRecaudoCobro->getEstadoAprobado() == 0) {
            $fecha = new \DateTime('now');
            $arRecaudoCobro->setFecha($fecha);
            $arRecaudoCobro->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arRecaudoCobro);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    public function contarDetalles($codigoRecaudoCobro)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select("COUNT(g.codigoGuiaPk)")
            ->where("g.codigoRecaudoCobroFk= {$codigoRecaudoCobro} ");
        $resultado =  $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }
}