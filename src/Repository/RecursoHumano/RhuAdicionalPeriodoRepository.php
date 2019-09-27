<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuAdicionalPeriodo;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\Transporte\TteMonitoreo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAdicionalPeriodoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAdicionalPeriodo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $estadoCerrado = null;

        if ($filtros) {
            $estadoCerrado = $filtros['estadoCerrado'] ?? null;
        }


        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAdicionalPeriodo::class, 'ap')
            ->select('ap.codigoAdicionalPeriodoPk')
            ->addSelect('ap.fecha')
            ->addSelect('ap.nombre')
            ->addSelect('ap.estadoCerrado');
        switch ($estadoCerrado) {
            case '0':
                $queryBuilder->andWhere("ap.estadoCerrado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("ap.estadoCerrado = 1");
                break;
        }

        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuAdicionalPeriodo::class)->find($arrSeleccionado);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                }
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
    }


}