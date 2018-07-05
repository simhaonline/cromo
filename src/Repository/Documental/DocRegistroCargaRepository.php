<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocRegistroCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocRegistroCargaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocRegistroCarga::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocRegistroCarga::class, 'rc')
            ->select('rc.codigoRegistroCargaPk')
            ->addSelect('rc.identificador')
            ->where('rc.codigoRegistroCargaPk <> 0');
        return $queryBuilder;
    }

    public function eliminar($arrDetallesSeleccionados)
    {

            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigoSolicitudDetalle) {
                    $arSolicitudDetalle = $this->getEntityManager()->getRepository('App:Inventario\InvSolicitudDetalle')->find($codigoSolicitudDetalle);
                    if ($arSolicitudDetalle) {
                        $this->_em->remove($arSolicitudDetalle);
                    }
                }
                try {
                    $this->_em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
    }
}