<?php

namespace App\Repository\General;

use App\Entity\General\GenLogConexion;
use App\Entity\Seguridad\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GenLogConexionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenLogConexion::class);
    }

    /**
     * @param $token TokenInterface
     * @param $accion
     */
    public function insertar($token, $accion){
        $em = $this->_em;
        $arUsuarioLog = new GenLogConexion();
        $arUsuarioLog->setFecha(new \DateTime('now'));
        $arUsuarioLog->setCodigoUsuario($token->getUsername());
        $arUsuarioLog->setAccion($accion);
        $em->persist($arUsuarioLog);
        $em->flush();
    }

    /**
     * @param $token TokenInterface
     */
    public function actualizarUltimaConexion($token){
        $em = $this->_em;
        $arUsuario = $em->getRepository(Usuario::class)->find($token->getUsername());
        if($arUsuario){
            $arUsuario->setFechaUltimoIngreso(new \DateTime('now'));
            $em->persist($arUsuario);
            $em->flush();
        }
    }

    /**
     * @param $token TokenInterface
     */
    public function actualizarConexiones($token){
        $em = $this->_em;
        $arUsuario = $em->getRepository(Usuario::class)->find($token->getUsername());
        if($arUsuario){
            $arUsuario->setNumeroConexiones($arUsuario->getNumeroConexiones() + 1);
            $em->persist($arUsuario);
            $em->flush();
        }
    }
}