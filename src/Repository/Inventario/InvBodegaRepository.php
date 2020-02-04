<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvBodegaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvBodega::class);
    }

//    public function lista(){
//        $session = new Session();
//        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvBodega::class,'b')
//            ->select('b.codigoBodegaPk')
//            ->addSelect('b.nombre')
//            ->where('b.codigoBodegaPk IS NOT NULL');
//        if($session->get('filtroInvBuscarBodegaCodigo') != ''){
//            $queryBuilder->andWhere("b.codigoBodegaPk  = '{$session->get('filtroInvBuscarBodegaCodigo')}'");
//            $session->set('filtroInvBuscarBodegaCodigo',null);
//        }
//        if($session->get('filtroInvBuscarBodegaNombre') != ''){
//            $queryBuilder->andWhere("b.nombre LIKE '%{$session->get('filtroInvBuscarBodegaNombre')}%'");
//            $session->set('filtroInvBuscarBodegaNombre',null);
//        }
//        return $queryBuilder;
//    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvBodega','ib')
            ->select('ib.codigoBodegaPk as ID')
            ->addSelect('ib.nombre as NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvBodega',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('b')
                    ->orderBy('b.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvBodega')) {
            $array['data'] = $this->getEntityManager()->getReference(InvBodega::class, $session->get('filtroInvBodega'));
        }
        return $array;
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvBodega::class, 'ib')
            ->select('ib.codigoBodegaPk')
            ->addSelect('ib.nombre');

        if ($codigo) {
            $queryBuilder->andWhere("ib.codigoBodegaPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("ib.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('ib.codigoBodegaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvBodega::class)->find($codigo);
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