<?php

namespace App\Repository\Tesoreria;

use App\Entity\Compra\ComCuentaPagarTipo;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method ComCuentaPagarTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComCuentaPagarTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComCuentaPagarTipo[]    findAll()
 * @method ComCuentaPagarTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesCuentaPagarTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCuentaPagarTipo::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TesCuentaPagarTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cp')
                    ->orderBy('cp.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTesCuentaPagarTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TesCuentaPagarTipo::class, $session->get('filtroTesCuentaPagarTipo'));
        }
        return $array;
    }
}
