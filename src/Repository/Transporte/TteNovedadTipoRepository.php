<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;


class TteNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteNovedadTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteNovedadTipo','nt')
            ->select('nt.codigoNovedadTipoPk AS ID')
            ->addSelect('nt.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Transporte\TteNovedadTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('nt')
                    ->orderBy('nt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroTteCodigoNovedadTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TteNovedadTipo::class, $session->get('filtroTteCodigoNovedadTipo'));
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


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteNovedadTipo::class, 'nt')
            ->select('nt.codigoNovedadTipoPk')
            ->addSelect('nt.interna')
            ->addSelect('nt.nombre');

        if ($codigo) {
            $queryBuilder->andWhere("nt.codigoNovedadTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("nt.nombre LIKE '%{$nombre}%'");
        }



        $queryBuilder->addOrderBy('nt.codigoNovedadTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteNovedadTipo::class)->find($codigo);
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