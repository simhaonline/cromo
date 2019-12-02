<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocMasivoCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
class DocMasivoCargaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocMasivoCarga::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $identificador = null;
        $estadoDigitalizado = null;
        $existe = null;

        if ($filtros) {
            $identificador = $filtros['identificador'] ?? null;
            $estadoDigitalizado = $filtros['estadoDigitalizado'] ?? null;
            $existe = $filtros['existe'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocMasivoCarga::class, 'mc')
            ->select('mc.codigoMasivoCargaPk')
            ->addSelect('mc.identificador')
            ->addSelect('mc.archivo')
            ->addSelect('mc.existe')
            ->addSelect('mc.estadoDigitalizado')
            ->where('mc.codigoMasivoCargaPk <> 0');

        if ($identificador) {
            $queryBuilder->andWhere("mc.identificador = '{$identificador}'");
        }

        switch ($estadoDigitalizado) {
            case '0':
                $queryBuilder->andWhere("mc.estadoDigitalizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("mc.estadoDigitalizado = 1");
                break;
        }

        switch ($existe) {
            case '0':
                $queryBuilder->andWhere("mc.existe = 0");
                break;
            case '1':
                $queryBuilder->andWhere("mc.existe = 1");
                break;
        }

        return $queryBuilder;
    }

    public function eliminar($arrDetallesSeleccionados, $directorioBandeja)
    {
        if (count($arrDetallesSeleccionados)) {
            foreach ($arrDetallesSeleccionados as $codigo) {
                $ar = $this->getEntityManager()->getRepository(DocMasivoCarga::class)->find($codigo);
                if ($ar) {
                    $archivo = $directorioBandeja . "/" . $ar->getArchivo();
                    if(file_exists($archivo)) {
                        unlink($archivo);
                    }
                    $this->getEntityManager()->remove($ar);
                }
            }
            $this->getEntityManager()->flush();
        }
    }
}