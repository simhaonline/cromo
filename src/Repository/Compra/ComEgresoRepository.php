<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComEgreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgreso[]    findAll()
 * @method ComEgreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComEgresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComEgreso::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arEgreso ComEgreso
         *
         */
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(ComEgreso::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(ComEgresoDetalle::class)->findBy(['codigoEgresoFk' => $arRegistro->getCodigoEgresoPk()])) <= 0) {
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
}
