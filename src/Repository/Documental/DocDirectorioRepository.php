<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocRegistroCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocDirectorioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocDirectorio::class);
    }

    public function devolverDirectorio($tipo, $clase) {
        $em = $this->getEntityManager();
        $directorio = "";
        $arDirectorio = $em->getRepository(DocDirectorio::class)->findOneBy(array('tipo' => $tipo, 'clase' => $clase));
        if($arDirectorio) {
            if($arDirectorio->getNumeroArchivos() >= 50000) {
                $arDirectorio->setNumeroArchivos(1);
                $arDirectorio->setDirectorio($arDirectorio->getDirectorio()+1);
                $em->persist($arDirectorio);
                $directorio = $arDirectorio->getDirectorio();
            } else {
                $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos()+1);
                $directorio = $arDirectorio->getDirectorio();
            }
        } else {
            $arDirectorio = new DocDirectorio();
            $arDirectorio->setDirectorio(1);
            $arDirectorio->setNumeroArchivos(1);
            $arDirectorio->setTipo($tipo);
            $arDirectorio->setClase($clase);
            $em->persist($arDirectorio);
            $directorio = "1";
        }
        $em->flush();
        return $directorio;
    }
}