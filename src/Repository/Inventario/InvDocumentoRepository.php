<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvDocumento::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvDocumento','id')
            ->select('id.codigoDocumentoPk as ID')
            ->addSelect('id.abreviatura')
            ->addSelect('id.nombre')
            ->addSelect('id.consecutivo');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvDocumento::class, 'd')
            ->select('d.codigoDocumentoPk')
            ->addSelect('d.abreviatura')
            ->addSelect('d.nombre')
            ->addSelect('d.consecutivo')
            ->addSelect('d.operacionComercial')
            ->addOrderBy('d.codigoDocumentoPk', 'ASC');

        return $queryBuilder;
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvDocumento',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('dt')
                    ->orderBy('dt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCodigoDocumento')) {
            $array['data'] = $this->getEntityManager()->getReference(InvDocumento::class, $session->get('filtroInvCodigoDocumento'));
        }
        return $array;
    }
}