<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCiudad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteCiudadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCiudad::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TteCiudad', 'c')
            ->select('c.codigoCiudadPk AS ID')
            ->addSelect('c.nombre AS NOMBRE')
            ->addSelect('c.codigoDivision AS DIVISION');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @param $tipo string
     * @return array
     * @throws \Doctrine\ORM\ORMException
     *
     */
    public function llenarCombo($tipo)
    {
        $session = new Session();
        switch ($tipo){
            case 'origen':
                $array = [
                    'class' => TteCiudad::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.nombre', 'ASC');
                    },
                    'choice_label' => function ($er) {
                        $ciudad = $er->getNombre();
                        $ciudad .= " - " . $er->getCodigoCiudadPk();
                        return $ciudad;
                    },
                    'required' => false,
                    'empty_data' => "",
                    'placeholder' => "TODOS",
                    'data' => ""];
                if ($session->get('filtroTteDespachoCodigoCiudadOrigen')) {
                    $array['data'] = $this->getEntityManager()->getReference(TteCiudad::class, $session->get('filtroTteDespachoCodigoCiudadOrigen'));
                }
                break;
            case 'destino':
                $array = [
                    'class' => TteCiudad::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.nombre', 'ASC');
                    },
                    'choice_label' => function ($er) {
                        $ciudad = $er->getNombre();
                        $ciudad .= " - " . $er->getCodigoCiudadPk();
                        return $ciudad;
                    },
                    'required' => false,
                    'empty_data' => "",
                    'placeholder' => "TODOS",
                    'data' => ""];
                if ($session->get('filtroTteDespachoCodigoCiudadDestino')) {
                    $array['data'] = $this->getEntityManager()->getReference(TteCiudad::class, $session->get('filtroTteDespachoCodigoCiudadDestino'));
                }
                break;
        }
        return $array;
    }

    public function listaDql()
    {
        $session = new Session();
        $qb = $this->getEntityManager()->createQueryBuilder()->from(TteCiudad::class, 'c')
            ->select('c.codigoCiudadPk')
            ->addSelect('c.nombre')
//            ->addSelect('c.nombre')
//            ->where('c.codigoConductorPk <> 0')
            ->orderBy('c.nombre');
        if ($session->get('filtroTteCiudadNombre') != '') {
            $qb->andWhere("c.nombre LIKE '%{$session->get('filtroTteCiudadNombre')}%'");
        }
        return $qb->getDQL();
    }
}