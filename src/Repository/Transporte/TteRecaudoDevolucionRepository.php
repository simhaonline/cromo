<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRecaudoDevolucionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecaudoDevolucion::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecaudoDevolucion::class, 'rc');
        $queryBuilder
            ->select('rc.codigoRecaudoDevolucionPk')
            ->join('rc.clienteRel', 'c')
            ->addSelect('c.nombreCorto')
            ->addSelect('rc.fecha')
            ->addSelect('rc.vrTotal')
            ->addSelect('rc.usuario')
            ->addSelect('rc.estadoAutorizado')
            ->addSelect('rc.estadoAprobado')
            ->addSelect('rc.estadoAnulado')
            ->where('rc.codigoRecaudoDevolucionPk <> 0');
        $queryBuilder->orderBy('rc.fecha', 'DESC');

        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("rc.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteRecaudoDevolucion::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteGuia::class)->findBy(['codigoRecaudoDevolucionFk' => $arRegistro->getCodigoRecaudoDevolucionPk()])) <= 0) {
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
                    $arGuia->setRecaudoDevolucionRel(null);
                    $arGuia->setEstadoRecaudoDevolucion(0);
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
            ->where("g.codigoRecaudoDevolucionFk = {$codigoRecaudo}");
        $arGuias = $query->getQuery()->getSingleResult();
        $arRecaudo = $em->getRepository(TteRecaudoDevolucion::class)->find($codigoRecaudo);
        $arRecaudo->setCantidad(intval($arGuias['cantidad']));
        $arRecaudo->setVrTotal(intval($arGuias['vrTotalRecaudo']));
        $em->persist($arRecaudo);
        $em->flush();
        return true;
    }

    public function autorizar($arRecaudoDevolucion){
        if($this->getEntityManager()->getRepository(TteRecaudoDevolucion::class)->contarDetalles($arRecaudoDevolucion->getCodigoRecaudoDevolucionPk()) > 0){
            $arRecaudoDevolucion->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arRecaudoDevolucion);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no tiene detalles');
        }
    }

    public function desautorizar($arRecaudoDevolucion)
    {
        if ($arRecaudoDevolucion->getEstadoAutorizado() == 1 && $arRecaudoDevolucion->getEstadoAprobado() == 0) {
            $arRecaudoDevolucion->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arRecaudoDevolucion);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta aprobado y no se puede desautorizar');
        }
    }

    public function aprobar($arRecaudoDevolucion)
    {
        if($arRecaudoDevolucion->getEstadoAutorizado() == 1 && $arRecaudoDevolucion->getEstadoAprobado() == 0) {
            $fecha = new \DateTime('now');
            $arRecaudoDevolucion->setFecha($fecha);
            $arRecaudoDevolucion->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arRecaudoDevolucion);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    public function contarDetalles($codigoRecaudo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select("COUNT(g.codigoGuiaPk)")
            ->where("g.codigoRecaudoDevolucionFk= {$codigoRecaudo} ");
        $resultado =  $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }
}