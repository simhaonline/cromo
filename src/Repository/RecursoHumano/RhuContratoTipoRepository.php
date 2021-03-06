<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContratoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;
class RhuContratoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuContratoTipo::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuContratoTipo::class,'ct')
            ->select('ct.codigoContratoTipoPk AS ID')
            ->addSelect('ct.nombre')
            ->where('ct.codigoContratoTipoPk IS NOT NULL');
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => RhuContratoTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ct')
                    ->orderBy('ct.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroRhuContratoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(RhuContratoTipo::class, $session->get('filtroRhuContratoTipo'));
        }
        return $array;
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoContratoTipo = null;
        $nombre = null;
        $codigoContratoClase = null;

        if ($filtros) {
            $codigoContratoTipo = $filtros['codigoContratoTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $codigoContratoClase = $filtros['codigoContratoClase'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuContratoTipo::class, 'ct')
            ->select('ct.codigoContratoTipoPk')
            ->addSelect('ct.nombreCorto')
            ->addSelect('cl.nombre as clase')
            ->leftJoin('ct.contratoClaseRel', 'cl');

        if ($codigoContratoTipo) {
            $queryBuilder->andWhere("ct.codigoContratoTipoPk = {$codigoContratoTipo}");
        }

        if ($nombre) {
            $queryBuilder->andWhere("ct.nombreCorto like '%{$nombre}%' ");
        }

        if ($codigoContratoClase) {
            $queryBuilder->andWhere("ct.codigoContratoClaseFk = '{$codigoContratoClase}'");
        }

        $queryBuilder->addOrderBy('ct.codigoContratoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuContratoTipo::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }
}