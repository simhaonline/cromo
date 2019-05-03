<?php


namespace App\Utilidades;
use Doctrine\ORM\EntityManager;


class Modelo
{
    private $em;
    /*
    */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }
    public function eliminar($entidad, $arrSeleccionados){
        try{
            $em = $this->em;
            if ($arrSeleccionados && is_array($arrSeleccionados)) {
                foreach ($arrSeleccionados as $codigo) {
                    $arData = $em->getRepository($entidad)->find($codigo);
                    if ($arData) {
                        $em->remove($arData);
                    }
                }
                $em->flush();
            }
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
    }
}