<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvImportacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvImportacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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

    public function lista(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = 'SELECT pd.codigoImportacionTipoPk
        FROM App\Entity\Inventario\InvImportacionTipo it  
        WHERE pt.codigoImportacionTipoPk <> 0 ';
        $dql .= " ORDER BY it.codigoImportacionTipoPk";
        $query = $em->createQuery($dql);
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

}