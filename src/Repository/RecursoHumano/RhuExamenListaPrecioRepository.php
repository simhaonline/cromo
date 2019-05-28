<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEntidadExamen;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuExamenListaPrecioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuExamenListaPrecio::class);
    }

    /**
     * @param $arrControles
     * @param $arEntidadExamenes
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $arEntidadExamenes)
    {
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(RhuEntidadExamen::class)->contarDetalles($arEntidadExamenes->getCodigoEntidadExamenPk()) > 0) {
            $arrPrecio = $arrControles['arrPrecio'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoExamenListaPrecio) {
                $arExamenListaPrecio = $this->getEntityManager()->getRepository(RhuExamenListaPrecio::class)->find($codigoExamenListaPrecio);
                $arExamenListaPrecio->setVrPrecio($arrPrecio[$codigoExamenListaPrecio]);
                $em->persist($arExamenListaPrecio);
//                dd($arExamenListaPrecio);
                $em->flush();
            }
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $arrDetallesSeleccionados
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrDetallesSeleccionados, $id)
    {
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $this->getEntityManager()->getRepository(RhuExamenListaPrecio::class)->find($codigo);
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
    }
}