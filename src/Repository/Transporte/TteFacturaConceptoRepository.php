<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaConcepto;
use App\Entity\Transporte\TteFacturaTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class TteFacturaConceptoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteFacturaConcepto::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TteFacturaTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ft')
                    ->orderBy('ft.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteFacturaCodigoFacturaTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TteFacturaTipo::class, $session->get('filtroTteFacturaCodigoFacturaTipo'));
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


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaConcepto::class, 'fc')
            ->select('fc.codigoFacturaConceptoPk')
            ->addSelect('fc.nombre')
            ->addSelect('fc.liberarGuias');

        if ($codigo) {
            $queryBuilder->andWhere("fc.codigoFacturaConceptoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("fc.codigoFacturaConceptoPk LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('fc.codigoFacturaConceptoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteFacturaConcepto::class)->find($codigo);
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