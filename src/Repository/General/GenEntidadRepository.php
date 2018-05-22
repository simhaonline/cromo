<?php

namespace App\Repository\General;

use App\Entity\General\GenEntidad;
use App\Entity\Seguridad\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Zend\Json\Json;

class GenEntidadRepository extends ServiceEntityRepository
{
    private $excepcionCamposCubo = ['jsonCubo', 'sqlCubo'];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenConfiguracionEntidad::class);
    }

    /**
     * @author Andres Acevedo
     * @param $arConfiguracionEntidad GenEntidad
     * @param $opcion
     * @param string $entidadCubo
     * @return mixed
     */
    public function generarDql($arConfiguracionEntidad, $opcion, $entidadCubo = "")
    {
        global $kernel;
        /** @var  $arUsuario Usuario */
        $arUsuario = $kernel->getContainer()->get("security.token_storage")->getToken()->getUser();
        $qb = $this->_em->createQueryBuilder()->from($arConfiguracionEntidad->getRutaRepositorio(), 'tbl');
        switch ($opcion) {
            case 0:
                $arrLista = json_decode($arConfiguracionEntidad->getJsonLista());
                break;
            case 1:
                $arrLista = json_decode($arConfiguracionEntidad->getJsonExcel());
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
        if ($entidadCubo) {
            $qb->andWhere("tbl.codigoEntidadFk = '{$entidadCubo}'")
                ->andWhere("tbl.usuario = '{$arUsuario->getUsername()}'");
        }
        $qb->orderBy('tbl.' . $arrLista[0]->campo);
        $query = $qb->getDQL();
        return $query;
    }

    public function generarDqlFixtures($arConfiguracionEntidad)
    {
        $arrLista = json_decode($arConfiguracionEntidad->getJsonLista());
        $qb = $this->_em->createQueryBuilder()->from($arConfiguracionEntidad->getRutaRepositorio(), 'tbl');
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
     * @param $arConfiguracionEntidad
     * @param $opcion
     * @param $entidadCubo
     * @return mixed
     */
    public function lista($arConfiguracionEntidad, $opcion, $entidadCubo)
    {
        switch ($opcion) {
            case 0:
                $query = $arConfiguracionEntidad->getDqlLista();
                break;
            case 1:
                $query = $this->generarDql($arConfiguracionEntidad, 1, $entidadCubo );
                break;
        }
        $arrRegistros = $this->_em->createQuery($query);
        return $arrRegistros->execute();
    }

    /**
     * @author Andres Acevedo
     * @param $arConfiguracionEntidad GenEntidad
     * @return mixed
     */
    public function listaDetalles($arConfiguracionEntidad, $id)
    {
        $qb = $this->_em->createQueryBuilder()->from($arConfiguracionEntidad->getRutaRepositorio(), 'tbl');
        $arrLista = json_decode($arConfiguracionEntidad->getJsonLista());
        foreach ($arrLista as $lista) {
            $qb->addSelect("tbl.{$lista->campo} AS {$lista->alias}");

        }
        $qb->where("tbl.{$arrLista[0]->campo} = {$id}");
        $qb->orderBy("tbl.{$arrLista[0]->campo}");
        $arrRegistros = $this->_em->createQuery($qb->getDQL());
        return $arrRegistros->execute();
    }

    /**
     * @param $arConfiguracionEntidad GenEntidad
     * @param $arrSeleccionados
     * @return array
     */
    public function eliminar($arConfiguracionEntidad, $arrSeleccionados)
    {
        $arrRespuestas = [];
        $respuesta = '';
        $arrCampos = json_decode($arConfiguracionEntidad->getJsonLista());
        $getCodigoPk = "getC" . substr($arrCampos[0]->campo, 1);
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->_em->getRepository($arConfiguracionEntidad->getRutaRepositorio())->find($codigo);
                if ($arRegistro) {
                    if (property_exists($arRegistro, 'estadoAutorizado')) {
                        if ($arRegistro->getEstadoAutorizado()) {
                            $respuesta = "No se puede eliminar, el registro con ID {$arRegistro->$getCodigoPk()} se encuentra autorizado.";
                        }
                    }
                    if (property_exists($arRegistro, 'estadoImpreso')) {
                        if ($arRegistro->getEstadoImpreso()) {
                            $respuesta = "No se puede eliminar, el registro con ID {$arRegistro->$getCodigoPk()} se encuentra impreso.";
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

        return $arrRespuestas;
    }
}