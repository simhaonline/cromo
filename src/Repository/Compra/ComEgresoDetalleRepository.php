<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComEgresoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComEgresoDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgresoDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgresoDetalle[]    findAll()
 * @method ComEgresoDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComEgresoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComEgresoDetalle::class);
    }

    public function lista($codigoEgreso)
    {
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('ed')
            ->from('App:Compra\ComEgresoDetalle', 'ed')
            ->where("ed.codigoEgresoFk = '{$codigoEgreso}'");

        return $query->getQuery();
    }

    /**
     * @param $arEgreso
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arEgreso, $arrDetallesSeleccionados)
    {
        if ($arEgreso->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(ComEgresoDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
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
