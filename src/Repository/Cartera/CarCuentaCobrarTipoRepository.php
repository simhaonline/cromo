<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Transporte\TteGuiaTipo;
use App\Utilidades\Mensajes;
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


    public function lista($raw)
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
            ->addSelect('ct.nombre')
            ->addSelect('ct.saldoInicial');
        if ($codigo) {
            $queryBuilder->andWhere("ct.codigoCuentaCobrarTipoPk = '{$codigo}'");
        }

        if ($nombre) {
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
        } else {
            Mensajes::error("No existen registros para eliminar");
        }
    }

    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => CarCuentaCobrarTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroCarCuentaCobrarTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(CarCuentaCobrarTipo::class, $session->get('filtroCarCuentaCobrarTipo'));
        }
        return $array;
    }

    public function selectCodigoNombre()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCuentaCobrarTipo::class, 'cct');
        $qb->select('cct.codigoCuentaCobrarTipoPk')
            ->addSelect('cct.nombre');
        return $qb->getQuery()->getResult();
    }
}