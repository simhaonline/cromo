<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoTipo;
use App\Entity\Cartera\CarReciboTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class CarAnticipoTipoRepository extends ServiceEntityRepository

{

 public function __construct(ManagerRegistry $registry)
{
    parent::__construct($registry, CarAnticipoTipo::class);
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

    $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAnticipoTipo::class, 'xn')
        ->select('xn.codigoAnticipoTipoPk')
        ->addSelect('xn.nombre');


    if ($codigo) {
        $queryBuilder->andWhere("xn.codigoAnticipoTipoPk = '{$codigo}'");
    }

    if($nombre){
        $queryBuilder->andWhere("xn.nombre LIKE '%{$nombre}%'");
    }

    $queryBuilder->addOrderBy('xn.codigoAnticipoTipoPk', 'DESC');
    $queryBuilder->setMaxResults($limiteRegistros);
    return $queryBuilder->getQuery()->getResult();

}

    public function eliminar($arrDetallesSeleccionados)
{
    $em = $this->getEntityManager();
    if ($arrDetallesSeleccionados) {
        if (count($arrDetallesSeleccionados)) {
            foreach ($arrDetallesSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(CarAnticipoTipo::class)->find($codigo);
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