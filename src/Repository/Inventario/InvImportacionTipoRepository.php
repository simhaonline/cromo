<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvImportacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvImportacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvImportacionTipo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvImportacionTipo', 'it');
        $qb
            ->select('it.codigoImportacionTipoPk AS ID')
            ->addSelect('it.nombre AS NOMBRE')
            ->addSelect('it.consecutivo AS CONSECUTIVO')
            ->orderBy('it.codigoImportacionTipoPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

//    public function lista(): array
//    {
//        $session = new Session();
//        $em = $this->getEntityManager();
//        $dql = 'SELECT pd.codigoImportacionTipoPk
//        FROM App\Entity\Inventario\InvImportacionTipo it
//        WHERE pt.codigoImportacionTipoPk <> 0 ';
//        $dql .= " ORDER BY it.codigoImportacionTipoPk";
//        $query = $em->createQuery($dql);
//        return $query->execute();
//    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvImportacionTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('pt')
                    ->orderBy('pt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvImportacionTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvImportacionTipo::class, $session->get('filtroInvImportacionTipo'));
        }
        return $array;
    }



    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;
        $consecutivo = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $consecutivo = $filtros['consecutivo'] ?? null;

        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacionTipo::class, 'it')
            ->select('it.codigoImportacionTipoPk')
            ->addSelect('it.nombre')
            ->addSelect('it.consecutivo');

        if ($codigo) {
            $queryBuilder->andWhere("it.codigoImportacionTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("it.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('it.codigoImportacionTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvImportacionTipo::class)->find($codigo);
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