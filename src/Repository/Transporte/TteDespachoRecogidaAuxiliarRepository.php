<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaAuxiliar;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDespachoRecogidaAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoRecogidaAuxiliar::class);
    }

    public function despacho($codigoDespachoRecogida): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT dra.codigoDespachoRecogidaAuxiliarPk, a.numeroIdentificacion, a.nombreCorto 
        FROM App\Entity\Transporte\TteDespachoRecogidaAuxiliar dra 
        LEFT JOIN dra.auxiliarRel a
        WHERE dra.codigoDespachoRecogidaFk = :codigoDespachoRecogida'
        )->setParameter('codigoDespachoRecogida', $codigoDespachoRecogida);

        return $query->execute();
    }

    /**
     * @param $arrSeleccionados
     * @param $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados, $id)
    {
        $em = $this->getEntityManager();
        $arRegistro = $em->getRepository(TteDespachoRecogida::class)->find($id);
        if ($arRegistro->getEstadoAutorizado() == 0) {
            if ($arrSeleccionados) {
                if (count($arrSeleccionados)) {
                    foreach ($arrSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TteDespachoRecogidaAuxiliar::class)->find($codigo);
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

}