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
            ->addSelect('rc.archivo')
            ->where('rc.codigoRegistroCargaPk <> 0');
        return $queryBuilder;
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        if (count($arrDetallesSeleccionados)) {
            foreach ($arrDetallesSeleccionados as $codigo) {
                $ar = $this->getEntityManager()->getRepository(DocRegistroCarga::class)->find($codigo);
                if ($ar) {
                    $this->getEntityManager()->remove($ar);
                }
            }
            $this->getEntityManager()->flush();
        }
    }
}