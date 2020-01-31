<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class CarCuentaCobrarTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarCuentaCobrarTipo::class);
    }


    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrarTipo::class, 'ct')
            ->select('ct.codigoCuentaCobrarTipoPk')
            ->addSelect('ct.nombre');

        if ($codigo) {
            $queryBuilder->andWhere("ct.codigoCuentaCobrarTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("ct.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('ct.codigoCuentaCobrarTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(CarCuentaCobrarTipo::class)->find($codigo);
                    if ($arRegistro) {
                        $em->remove($arRegistro);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }
}