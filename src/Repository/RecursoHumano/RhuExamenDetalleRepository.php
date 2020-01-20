<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuExamenDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuExamenDetalle::class);
    }

    /**
     * @param $arRegistro RhuExamen
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrDetallesSeleccionados, $id)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(RhuExamen::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(RhuExamenDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                            $this->getEntityManager()->flush();
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arExamenes
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arExamenes)
    {
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(RhuExamen::class)->contarDetalles($arExamenes->getCodigoExamenPk()) > 0) {
            $arrPrecio = $arrControles['arrPrecio'];
            $arrCodigo = $arrControles['arrCodigo'];
            $arrComentario = $arrControles['arrComentario'];
            foreach ($arrCodigo as $codigoExamenDetalle) {
                $arExamenDetalle = $this->getEntityManager()->getRepository(RhuExamenDetalle::class)->find($codigoExamenDetalle);
                $arExamenDetalle->setVrPrecio(floatval($arrPrecio[$codigoExamenDetalle]));
                $arExamenDetalle->setComentario($arrComentario[$codigoExamenDetalle]);
                $em->persist($arExamenDetalle);
                $em->flush();
            }

            $em->getRepository(RhuExamen::class)->liquidar($arExamenes);
            $this->getEntityManager()->flush();
        }
    }

    public function cerrar($arExamen, $arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigoExamenDetalle) {
                $arExamenDetalle = $em->getRepository(RhuExamenDetalle::class)->find($arExamen->getCodigoExamenPk());
//                if ($arExamenDetalle->getEstadoCerrado() == 0) {
//                    $arExamenDetalle->setEstadoCerrado(1);
//                    $em->persist($arExamenDetalle);
//                }
            }
            $em->flush();
        } else {
            Mensajes::error("No se ha seleccionado elementos");
        }
    }


    public function aprobar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigoExamenDetalle) {
                $arExamenDetalle = $this->getEntityManager()->getRepository(RhuExamenDetalle::class)->find($codigoExamenDetalle);
                if ($arExamenDetalle->getEstadoAprobado() == 0) {
                    $arExamenDetalle->setEstadoAprobado(1);
                    $em->persist($arExamenDetalle);
                }
            }
        }
        $em->flush();
    }
}

























