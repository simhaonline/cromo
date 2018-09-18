<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TteRecogida;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDespachoRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoRecogida::class);
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespachoRecogida::class, 'dr')
            ->select('dr.codigoDespachoRecogidaPk')
            ->addSelect('dr.fecha')
            ->addSelect('dr.numero')
            ->addSelect('dr.codigoOperacionFk')
            ->addSelect('dr.codigoVehiculoFk')
            ->addSelect('dr.codigoRutaRecogidaFk')
            ->addSelect('dr.cantidad')
            ->addSelect('dr.unidades')
            ->addSelect('dr.pesoReal')
            ->addSelect('dr.pesoVolumen')
            ->addSelect('dr.estadoDescargado')
            ->addSelect('dr.vrFletePago')
            ->addSelect('cond.nombreCorto AS conductorNombreCorto')
            ->where('dr.codigoDespachoRecogidaPk <> 0')
        ->leftJoin('dr.conductorRel', 'cond');
        if($session->get('filtroTteDespachoVehiculoCodigo') != ''){
            $queryBuilder->andWhere("dr.codigoVehiculoFk = '{$session->get('filtroTteDespachoVehiculoCodigo')}'");
        }
        if($session->get('filtroTteDespachoEstadoAprobado') != ''){
            $queryBuilder->andWhere("dr.estadoAprobado = {$session->get('filtroTteDespachoEstadoAprobado')}");
        }
        $queryBuilder->orderBy('dr.fecha', 'DESC');
        return $queryBuilder->getQuery();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteDespachoRecogida::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteRecogida::class)->findBy(['codigoDespachoRecogidaFk' => $arRegistro->getCodigoDespachoRecogidaPk()])) <= 0) {
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

    public function liquidar($codigoDespachoRecogida): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad, SUM(r.unidades+0) as unidades, SUM(r.pesoReal+0) as pesoReal, SUM(r.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Transporte\TteRecogida r
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida')
            ->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);
        $arrRecogidas = $query->getSingleResult();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $arDespachoRecogida->setUnidades(intval($arrRecogidas['unidades']));
        $arDespachoRecogida->setPesoReal(intval($arrRecogidas['pesoReal']));
        $arDespachoRecogida->setPesoVolumen(intval($arrRecogidas['pesoVolumen']));
        $arDespachoRecogida->setCantidad(intval($arrRecogidas['cantidad']));
        $em->persist($arDespachoRecogida);
        $em->flush();
        return true;
    }

    public function retirarRecogida($arrRecogidas): bool
    {
        $em = $this->getEntityManager();
        if ($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    if ($arRecogida->getEstadoRecogido() == 0) {
                        $arRecogida->setDespachoRecogidaRel(null);
                        $arRecogida->setEstadoProgramado(0);
                        $em->persist($arRecogida);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    public function descargarRecogida($arrRecogidas): bool
    {
        $em = $this->getEntityManager();
        if ($arrRecogidas) {
            if (count($arrRecogidas) > 0) {
                foreach ($arrRecogidas AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    if ($arRecogida->getEstadoRecogido() == 0 && $arRecogida->getUnidades() > 0 && $arRecogida->getPesoReal() > 0 && $arRecogida->getPesoVolumen() > 0) {
                        $arRecogida->setEstadoRecogido(1);
                        $em->persist($arRecogida);
                    }
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arDespachoRecogida){
        if($this->getEntityManager()->getRepository(TteRecogida::class)->contarDetalles($arDespachoRecogida->getCodigoDespachoRecogidaPk()) > 0){
            $arDespachoRecogida->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arDespachoRecogida);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no tiene detalles');
        }
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arDespachoRecogida){
        $arDespachoRecogida->setEstadoAutorizado(0);
        $this->getEntityManager()->persist($arDespachoRecogida);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arDespachoRecogida){
        $em = $this->getEntityManager();
        if($arDespachoRecogida->getEstadoAutorizado()){
            $arDespachoRecogidaTipo = $em->getRepository(TteDespachoRecogidaTipo::class)->find($arDespachoRecogida->getCodigoDespachoRecogidaTipoFk());
            if ($arDespachoRecogida->getNumero() == 0 || $arDespachoRecogida->getNumero() == NULL) {
                $arDespachoRecogida->setEstadoAprobado(1);
                $arDespachoRecogida->setNumero($arDespachoRecogidaTipo->getConsecutivo());
                $arDespachoRecogidaTipo->setConsecutivo($arDespachoRecogidaTipo->getConsecutivo() + 1);
                $em->persist($arDespachoRecogidaTipo);
            }

            $arDespachoRecogida->setEstadoAprobado(0);
            $this->getEntityManager()->persist($arDespachoRecogida);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arDespachoRecogida TteDespachoRecogida
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arDespachoRecogida){
        if($arDespachoRecogida->getEstadoAprobado()){
            $arDespachoRecogida->setEstadoAnulado(0);
            $this->getEntityManager()->persist($arDespachoRecogida);
            $this->getEntityManager()->flush();
        }
    }
}