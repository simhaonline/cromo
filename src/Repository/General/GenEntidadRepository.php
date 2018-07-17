<?php

namespace App\Repository\General;

use App\Controller\Estructura\MensajesController;
use App\Entity\General\GenEntidad;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Zend\Json\Json;

class GenEntidadRepository extends ServiceEntityRepository
{
    private $excepcionCamposCubo = ['jsonCubo', 'sqlCubo'];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenEntidad::class);
    }

    /**
     * @author Andres Acevedo
     * @param $arEntidad GenEntidad
     * @param $opcion
     * @return mixed
     */
    public function generarDql($arEntidad, $opcion)
    {
        $qb = $this->_em->createQueryBuilder()->from('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()), 'tbl');
        switch ($opcion) {
            case 0:
                $arrLista = json_decode($arEntidad->getJsonLista());
                break;
            case 1:
                $arrLista = json_decode($arEntidad->getJsonExcel());
                break;
        }
        $qb->select('tbl.' . $arrLista[0]->campo . ' AS ID');
        $i = 0;
        foreach ($arrLista as $lista) {
            if ($lista->mostrar) {
                if (!strpos($lista->campo, 'Pk') && !in_array($lista->campo, $this->excepcionCamposCubo)) {
                    $qb->addSelect("tbl.{$lista->campo} AS {$lista->alias}");
                }
            }
            $i++;
        }
        $qb->orderBy('tbl.' . $arrLista[0]->campo);
        $query = $qb->getDQL();
        return $query;
    }

    /**
     * @author Andres Acevedo
     * @param $arEntidad
     * @return string
     */
    public function generarDqlFixtures($arEntidad)
    {
        $arrLista = json_decode($arEntidad->getJsonLista());
        $qb = $this->_em->createQueryBuilder()->from('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()), 'tbl');
        $qb->select('tbl.' . $arrLista[0]->campo . ' AS ID');
        $i = 0;
        foreach ($arrLista as $lista) {
            if ($lista->mostrar) {
                if (!strpos($lista->campo, 'Pk') && !in_array($lista->campo, $this->excepcionCamposCubo)) {
                    $qb->addSelect("tbl.{$lista->campo} AS {$lista->alias} ");
                }
            }
            $i++;
        }
        $qb->orderBy('tbl.' . $arrLista[0]->campo);
        $query = $qb->getDQL();
        return $query;
    }

    /**
     * @author Andres Acevedo
     * @param $arConfiguracionEntidad GenEntidad
     * @param $arrFiltros
     * @return mixed
     */
    public function listaFiltro($arConfiguracionEntidad, $arrFiltros)
    {
        $qb = $this->_em->createQueryBuilder()->from($arConfiguracionEntidad->getRutaRepositorio(), 'tbl');
        $arrLista = json_decode($arConfiguracionEntidad->getJsonLista());
        $i = 0;
        $boolPrimerFiltro = true;
        foreach ($arrLista as $lista) {
            if ($lista->mostrar) {
                if ($boolPrimerFiltro) {
                    $qb->Select("tbl.{$lista->campo} AS {$lista->alias}");
                    $boolPrimerFiltro = false;
                } else {
                    $qb->addSelect("tbl.{$lista->campo} AS {$lista->alias}");
                }
            }
            $i++;
        }
        $qb->where('tbl.' . $arrLista[0]->campo . " <> 0");
        foreach ($arrFiltros as $key => $value) {
            if ($value != '') {
                if ($value instanceof \DateTime) {
                    $qb->andWhere('tbl.' . $key . " = '{$value->format('Y-m-d')}'");
                } elseif (is_numeric($value)) {
                    $qb->andWhere('tbl.' . $key . " = {$value}");
                } else {
                    $qb->andWhere('tbl.' . $key . " LIKE '%{$value}%'");
                }
            }
        }
        $qb->orderBy('tbl.' . $arrLista[0]->campo);
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @author Andres Acevedo
     * @param $arEntidad GenEntidad
     * @param $opcion
     * @return mixed
     */
    public function lista($arEntidad, $opcion)
    {
        switch ($opcion) {
            case 0:
                $query = $arEntidad->getDqlLista();
                break;
            case 1:
                $query = $this->generarDql($arEntidad, 1);
                break;
        }
        $arrRegistros = $this->_em->createQuery($query);
        return $arrRegistros->execute();
    }

    /**
     * @author Andres Acevedo
     * @param $arEntidad GenEntidad
     * @return mixed
     */
    public function listaDetalles($arEntidad, $id)
    {
        $qb = $this->_em->createQueryBuilder()->from('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()), 'tbl');
        $arrLista = json_decode($arEntidad->getJsonLista());
        foreach ($arrLista as $lista) {
            $qb->addSelect("tbl.{$lista->campo} AS {$lista->alias}");
        }
        if ($arrLista[0]->tipo == 'integer') {
            $qb->where("tbl.{$arrLista[0]->campo} = {$id}");
        } else {
            $qb->where("tbl.{$arrLista[0]->campo} = '{$id}'");
        }
        $qb->orderBy("tbl.{$arrLista[0]->campo}");
        $arrRegistros = $this->_em->createQuery($qb->getDQL());
        return $arrRegistros->execute();
    }

    /**
     * @author Andres Acevedo
     * @param $arEntidad
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arEntidad, $arrSeleccionados)
    {
        $arrRespuestas = [];
        $respuesta = '';
        $arrCampos = json_decode($arEntidad->getJsonLista());
        $getCodigoPk = "getC" . substr($arrCampos[0]->campo, 1);
        if (is_array($arrSeleccionados) && count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->_em->getRepository('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()))->find($codigo);
                if ($arRegistro) {
                    if (property_exists($arRegistro, 'estadoAutorizado')) {
                        if ($arRegistro->getEstadoAutorizado()) {
                            $respuesta = "No se puede eliminar, el registro con ID {$arRegistro->$getCodigoPk()} se encuentra autorizado.";
                        }
                    }
                    if (property_exists($arRegistro, 'estadoAprobado')) {
                        if ($arRegistro->getEstadoAprobado()) {
                            $respuesta = "No se puede eliminar, el registro con ID {$arRegistro->$getCodigoPk()} se encuentra aprobado.";
                        }
                    }
                    if (property_exists($arRegistro, 'estadoAnulado')) {
                        if ($arRegistro->getEstadoAnulado()) {
                            $respuesta = "No se puede eliminar, el registro con ID {$arRegistro->$getCodigoPk()} se encuentra anulado.";
                        }
                    }
                    $this->_em->remove($arRegistro);
                }
                if ($respuesta != "") {
                    $arrRespuestas[] = $respuesta;
                }
            }
        }
        if (count($arrRespuestas) > 0) {
            foreach ($arrRespuestas as $error) {
                Mensajes::error($error);
            }
        } else {
            $this->getEntityManager()->flush();
        }
    }


    public function generarNavigator($modulo, $funcion, $grupo, $entidad)
    {
        $qb = $this->_em->createQueryBuilder()->from('App:General\GenEntidad', 'ge')
            ->select('ge.modulo')
            ->addSelect('ge.funcion')
            ->addSelect('ge.grupo')
            ->addSelect('ge.entidad')
            ->where('ge.codigoEntidadPk IS NOT NULL');
        if ($modulo != '') {
            $qb->andWhere("ge.modulo = '{$modulo}'");
        }
        if ($grupo != '') {
            $qb->andWhere("ge.grupo = '{$grupo}'");
        }
        if ($entidad != '') {
            $qb->andWhere("ge.entidad = '{$entidad}'");
        }
        if ($funcion != '') {
            $qb->andWhere("ge.funcion = '{$funcion}'");
        }
        $qb->orderBy("ge.modulo", 'ASC');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}