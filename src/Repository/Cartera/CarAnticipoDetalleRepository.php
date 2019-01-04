<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoDetalle;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarAnticipoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarAnticipoDetalle::class);
    }

    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAnticipoDetalle::class, 'ad');
        $queryBuilder
            ->select('ad.codigoAnticipoDetallePk')
            ->addSelect('ad.vrPago')
            ->addSelect('ac.nombre AS concepto')
            ->leftJoin('ad.anticipoConceptoRel', 'ac')
            ->where('ad.codigoAnticipoFk = ' . $id);

        return $queryBuilder;
    }

    /**
     * @param $arAnticipo
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arAnticipo, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arAnticipo->getEstadoAutorizado()) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(CarAnticipoDetalle::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro porque se encuentra en uso');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

}