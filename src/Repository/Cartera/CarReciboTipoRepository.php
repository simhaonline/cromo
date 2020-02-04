<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarReciboTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class CarReciboTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarReciboTipo::class);
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarReciboTipo::class, 'rt')
            ->select('rt.codigoReciboTipoPk')
            ->addSelect('rt.nombre');

        if ($codigo) {
            $queryBuilder->andWhere("rt.codigoReciboTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("rt.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('rt.codigoReciboTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(CarReciboTipo::class)->find($codigo);
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

    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => CarReciboTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroCarReciboCodigoReciboTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(CarReciboTipo::class, $session->get('filtroCarReciboCodigoReciboTipo'));
        }
        return $array;
    }

}