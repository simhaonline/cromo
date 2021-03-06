<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFacturaConceptoDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class TteFacturaConceptoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteFacturaConceptoDetalle::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TteFacturaConceptoDetalle::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('fcd')
                    ->orderBy('fcd.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteFacturaCodigoFacturaConceptoDetalle')) {
            $array['data'] = $this->getEntityManager()->getReference(TteFacturaConceptoDetalle::class, $session->get('filtroTteFacturaCodigoFacturaConceptoDetalle'));
        }
        return $array;
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaConceptoDetalle::class, 'fcd')
            ->select('fcd.codigoFacturaConceptoDetallePk')
            ->addSelect('fcd.nombre')
            ->addSelect('fcd.codigoCuentaFk')
            ->addSelect('ir.nombre as impuestoRetencion')
            ->addSelect('iv.nombre as impuestoIvaVenta')
            ->leftJoin('fcd.impuestoRetencionRel', 'ir')
            ->leftJoin('fcd.impuestoIvaVentaRel', 'iv');

        if ($codigo) {
            $queryBuilder->andWhere("fcd.codigoFacturaConceptoDetallePk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("fcd.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('fcd.codigoFacturaConceptoDetallePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteFacturaConceptoDetalle::class)->find($codigo);
                if ($arRegistro) {
                    $em->remove($arRegistro);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }

}