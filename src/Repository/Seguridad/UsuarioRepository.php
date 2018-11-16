<?php

namespace App\Repository\Seguridad;

use App\Entity\Seguridad\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => Usuario::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->orderBy('r.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteDespachoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(Usuario::class, $session->get('filtroTteDespachoTipo'));
        }
        return $array;
    }

    public function exportarExcelPermisos(){
        $em=$this->getEntityManager();
        $arUsuarios = $em->createQueryBuilder()
            ->from('App:Seguridad\SegUsuarioModelo','seg')
            ->join('seg.modeloRel','m')
            ->addSelect('seg.codigoUsuarioFk as USUARIO')
            ->addSelect('m.codigoModuloFk as TIPO')
            ->addSelect('m.codigoModeloPk as MODELO')
            ->addSelect('seg.lista as LISTA')
            ->addSelect('seg.nuevo as NUEVO')
            ->addSelect('seg.detalle as DETALLE')
            ->addSelect('seg.autorizar as AUTORIZAR')
            ->addSelect('seg.aprobar as APROBAR')
            ->addSelect('seg.anular as ANULAR')
            ->orderBy('seg.codigoUsuarioFk','ASC')
            ->getQuery()->getResult();
        return $arUsuarios;
    }

}