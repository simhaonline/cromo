<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteOperacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteOperacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteOperacion::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteOperacion','op')
            ->select('op.codigoOperacionPk AS ID')
            ->addSelect('op.nombre AS NOMBRE');
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
            'class' => TteOperacion::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('o')
                    ->orderBy('o.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteOperacion')) {
            $array['data'] = $this->getEntityManager()->getReference(TteOperacion::class, $session->get('filtroTteOperacion'));
        }
        return $array;
    }


    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TteOperacion::class,'o')
            ->select('o');
        if ($session->get('filtroTteOperacionCodigo') != '') {
            $queryBuilder->andWhere("o.codigoOperacionPk = '{$session->get('filtroTteOperacionCodigo')}'");
        }
        if ($session->get('filtroTteOperacionNombre') != '') {
            $queryBuilder->andWhere("o.nombre LIKE '%{$session->get('filtroTteOperacionNombre')}%'");
        }
        return $queryBuilder;

    }

    public function apiWindowsValidar($raw) {
        $em = $this->getEntityManager();
        $codigo = $raw['codigoOperacion']?? null;
        if($codigo) {
            /** @var $arOperacion TteOperacion */
            $arOperacion = $em->getRepository(TteOperacion::class)->find($codigo);
            if($arOperacion) {
                $codigoOperacionCargo = $arOperacion->getCodigoOperacionCargoFk()?? $arOperacion->getCodigoOperacionPk();
                $arrOperacion = [
                    'codigoOperacionPk' => $arOperacion->getCodigoOperacionPk(),
                    'codigoOperacionCargoFk' => $codigoOperacionCargo,
                    'codigoCiudadFk' => $arOperacion->getCodigoCiudadFk()
                ];
                return $arrOperacion;
            } else {
                return ["error" => "No se encontro la operacion " . $codigo];
            }
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }
}