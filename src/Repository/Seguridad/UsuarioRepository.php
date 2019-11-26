<?php

namespace App\Repository\Seguridad;

use App\Entity\General\GenConfiguracion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteConfiguracion;
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

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => Usuario::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.username', 'ASC');
            },
            'choice_label' => 'username',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroUsuario')) {
            $array['data'] = $this->getEntityManager()->getReference(Usuario::class, $session->get('filtroUsuario'));
        }
        return $array;
    }

    public function exportarExcelPermisos(){
        $em=$this->getEntityManager();
        $arUsuarios = $em->createQueryBuilder()
            ->from('App:Seguridad\SegUsuarioModelo','seg')
            ->addSelect('seg.codigoUsuarioFk as USUARIO')
            ->addSelect('seg.codigoModeloFk as TIPO')
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

    public function apiWindowsValidar($raw) {
        $em = $this->getEntityManager();
        $usuario = $raw['usuario']?? null;
        $clave = $raw['clave']?? null;
        if($usuario && $clave) {
            $arUsuario = $em->getRepository(Usuario::class)->findOneBy(['username'=> $usuario, 'claveEscritorio'=>$clave]);
            if($arUsuario) {
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
                $arConfiguracionTransporte = $em->getRepository(TteConfiguracion::class)->find(1);
                return [
                    "nombreCorto" => $arUsuario->getNombreCorto(),
                    "versionBaseDatos" => $arConfiguracion->getVersionBaseDatos(),
                    "numeroUnicoGuia" => $arConfiguracionTransporte->getNumeroUnicoGuia(),
                    "codigoPrecioGeneral" => $arConfiguracionTransporte->getCodigoPrecioGeneralFk(),
                    "codigoCondicionGeneral" => $arConfiguracionTransporte->getCodigoCondicionGeneralFk(),
                    "codigoFormatoGuia" => $arConfiguracionTransporte->getCodigoFormatoGuia(),
                ];
            } else {
                return ["error" => "Usuario o clave invalidos"];
            }
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

}