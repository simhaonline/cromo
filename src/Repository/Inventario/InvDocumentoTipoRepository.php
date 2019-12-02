<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvDocumentoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvDocumentoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvDocumentoTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvDocumentoTipo','idt')
            ->select('idt.codigoDocumentoTipoPk as ID')
            ->addSelect('idt.nombre AS NOMBRE')
            ->where('idt.codigoDocumentoTipoPk <> 0')
            ->orderBy('idt.codigoDocumentoTipoPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvDocumentoTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('dt')
                    ->orderBy('dt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCodigoDocumentoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvDocumentoTipo::class, $session->get('filtroInvCodigoDocumentoTipo'));
        }
        return $array;
    }
}