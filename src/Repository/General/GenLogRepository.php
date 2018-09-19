<?php

namespace App\Repository\General;

use App\Entity\General\GenLog;
use App\Entity\General\GenLogAccion;
use App\Entity\Seguridad\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GenLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenLog::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenLog::class, 'l')
            ->select('l.codigoLogPk')
            ->leftJoin('l.usuarioRel', 'u')
            ->orderBy('l.codigoLogPk', 'DESC');
        if ($session->get('filtroCodigoLog')) {
            $queryBuilder->andWhere("l.codigoLogPk = {$session->get('filtroCodigoLog')}");
        }
        if ($session->get('filtroUsuario')) {
            $queryBuilder->andWhere("u.codigoUsuarioPk = {$session->get('filtroUsuario')}");
        }

        return $queryBuilder;

    }

    public function crearLog($codigoUsuario, $codigoAccion, $id, $descripcion = "")
    {
        $em = $this->getEntityManager();
        $arUsuario = $em->getRepository(Usuario::class)->find($codigoUsuario);
        $arAccion = $em->getRepository(GenLogAccion::class)->find($codigoAccion);
        $arLog = new GenLog();
        $arLog->setUsuarioRel($arUsuario);
        $arLog->setLogAccionRel($arAccion);
        $arLog->setFecha(new \DateTime('now'));
        $arLog->setDescripcion($descripcion);
        $arLog->setId($id);
        $em->persist($arLog);
        $em->flush();

        return TRUE;
    }

}