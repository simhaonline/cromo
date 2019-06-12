<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;

class TteCumplidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCumplido::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCumplido::class, 'c');
        $queryBuilder
            ->select('c.codigoCumplidoPk')
            ->join('c.clienteRel', 'cl')
            ->addSelect('cl.nombreCorto')
            ->addSelect('c.fecha')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->addSelect('c.comentario')
            ->addSelect('ct.nombre AS tipoCumplido')
            ->where('c.codigoCumplidoPk <> 0')
        ->leftJoin('c.cumplidoTipoRel', 'ct');
        $queryBuilder->orderBy('c.fecha', 'DESC');

        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("c.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteCumplido::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteGuia::class)->findBy(['codigoCumplidoFk' => $arRegistro->getCodigoCumplidoPk()])) <= 0) {
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

    public function factura($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCumplido::class, 'c');
        $queryBuilder
            ->select('c.codigoCumplidoPk')
            ->addSelect('c.fecha')
            ->where('c.codigoClienteFk = ' . $codigoCliente);
        $queryBuilder->orderBy('c.fecha', 'DESC');

        return $queryBuilder;
    }

    public function liquidar($codigoCumplido): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from("App:Transporte\TteGuia","g")
            ->select("COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen")
            ->where("g.codigoCumplidoFk = {$codigoCumplido}");
        $arGuias = $query->getQuery()->getSingleResult();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $arCumplido->setCantidad(intval($arGuias['cantidad']));
        $em->persist($arCumplido);
        $em->flush();
        return true;
    }

    public function retirarGuia($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $arGuia->setCumplidoRel(null);
                    $arGuia->setFechaCumplido(null);
                    $arGuia->setEstadoCumplido(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function autorizar($arCumplido)
    {
            $arCumplido->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arCumplido);
            $this->getEntityManager()->flush();
    }

    public function aprobar($arCumplido)
    {
        $em = $this->getEntityManager();
        $arCumplido->setEstadoAprobado(1);
        $fecha = new \DateTime('now');
        $arCumplido->setFecha($fecha);
        $em->persist($arCumplido);
        $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoCumplido = 1, g.fechaCumplido=:fecha 
                      WHERE g.codigoCumplidoFk = :codigoCumplido')
            ->setParameter('codigoCumplido', $arCumplido->getCodigoCumplidoPk())
            ->setParameter('fecha', $fecha->format('Y-m-d H:i'));
        $query->execute();
        $em->flush();
    }

    public function desAutorizar($arCumplido)
    {
        if ($arCumplido->getEstadoAutorizado() == 1 && $arCumplido->getEstadoAprobado() == 0) {
            $arCumplido->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arCumplido);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

}