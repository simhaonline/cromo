<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocMasivoCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocMasivoCargaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocMasivoCarga::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocMasivoCarga::class, 'mc')
            ->select('mc.codigoMasivoCargaPk')
            ->addSelect('mc.identificador')
            ->addSelect('mc.archivo')
            ->where('mc.codigoMasivoCargaPk <> 0');
        return $queryBuilder;
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        if (count($arrDetallesSeleccionados)) {
            foreach ($arrDetallesSeleccionados as $codigo) {
                $ar = $this->getEntityManager()->getRepository(DocMasivoCarga::class)->find($codigo);
                if ($ar) {
                    $this->getEntityManager()->remove($ar);
                }
            }
            $this->getEntityManager()->flush();
        }
    }
}