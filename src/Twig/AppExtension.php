<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;

//Funciones personalizadas para twig
class AppExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new \Twig_Function('esBooleano', [$this, 'esBooleano']),
            new \Twig_Function('esFecha', [$this, 'esFecha']),
            new \Twig_Function('validarTipo', [$this, 'validarTipo']),
            new \Twig_Function('validarTipoDetalle', [$this, 'validarTipoDetalle']),
            new \Twig_Function('separarPorGuionbajo', [$this, 'separarPorGuionbajo']),
            new \Twig_Function('validarSeleccionado', [$this, 'validarSeleccionado']),
            new \Twig_Function('esId', [$this, 'esId']),
            new \Twig_Function('mod', [$this, 'mod']),
            new \Twig_Function('validarLongitudTexto', [$this, 'validarLongitudTexto']),
            new \Twig_Function('crearTitulo', [$this, 'crearTitulo']),
            new \Twig_Function('llenarArray', [$this, 'llenarArray']),
        ];
    }

    public function esBooleano($dato)
    {
        if (is_bool($dato)) {
            return true;
        } else {
            return false;
        }
    }

    public function esFecha($dato)
    {
        if ($dato instanceof \DateTime) {
            return true;
        } else {
            return false;
        }
    }

    public function validarTipo($dato)
    {
        if ($this->esFecha($dato)) {
            echo "<td style='text-align: left;'>" . $dato->format('Y-m-d') . "</td>";
        } elseif ($this->esBooleano($dato)) {
            if ($dato) {
                echo "<td style='text-align: left;'>" . 'SI' . "</td>";
            } else {
                echo "<td style='text-align: left;'>" . 'NO' . "</td>";
            }
        } elseif (is_numeric($dato)) {
            echo "<td style='text-align: right;'>" . $dato . "</td>";
        } else {
            echo "<td style='text-align: left;'>" . $dato . "</td>";
        }
    }

    public function validarTipoDetalle($dato)
    {
        if ($this->esFecha($dato)) {
            echo "<td style='text-align: left;'>" . $dato->format('Y-m-d') . "</td>";
        } elseif ($this->esBooleano($dato)) {
            if ($dato) {
                echo "<td style='text-align: left;'>" . 'SI' . "</td>";
            } else {
                echo "<td style='text-align: left;'>" . 'NO' . "</td>";
            }
        } elseif (is_numeric($dato)) {
            echo "<td style='text-align: left;'>" . $dato . "</td>";
        } else {
            echo "<td style='text-align: left;'>" . $dato . "</td>";
        }
    }

    public function separarPorGuionbajo($dato)
    {
        $dato = preg_split('/(?=[A-Z])/', $dato);
        $dato = implode('_', $dato);
        return strtoupper($dato);
    }

    public function validarSeleccionado($dato)
    {
        if ($dato) {
            return 'checked';
        } else {
            return '';
        }
    }

    public function esId($dato)
    {
        if (preg_match('/Pk/', $dato)) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $dato \App\Entity\General\GenConfiguracionEntidad
     * @return string
     */
    public function crearTitulo($dato, $tipo)
    {
        $modulo = $dato->getModulo();
        $submodulo = substr($dato->getCodigoConfiguracionEntidadPk(), 3).' ';
        $arrSubmodulo = preg_split('/(?=[A-Z])/', str_replace(' ', '', $submodulo));
        $flag = false;
        if (count($arrSubmodulo) > 2) {
            $submodulo = '';
            foreach ($arrSubmodulo as $nombre) {
                if ($nombre != '') {
                    if(!$flag){
                        $submodulo .= $nombre.' ';
                        $flag = true;
                    } else {
                        $submodulo .= strtolower($nombre).' ';
                    }
                }
            }
        }
        switch ($tipo) {
            case 1:
                $accion = 'lista';
                break;
            case 2:
                $accion = 'detalle';
                break;
            case 3:
                $accion = 'nuevo';
                break;
        }
        echo "<h3 class='page-header'>" . $modulo . "<small style='font-size: 20px'> " . rtrim($submodulo) . ": " . $accion . "</small>" . "</h3>";
    }

    public function mod($dato, $numero)
    {
        if (($dato % $numero) == 0) {
            return true;
        }
    }

    public function validarLongitudTexto($dato)
    {
        if (!$dato instanceof \DateTime) {
            if (strlen($dato) > 8) {
                return true;
            }
        }
    }

    public function llenarArray($array, $alias, $dato)
    {
        $array[$alias] = $dato;
        return $array;
    }
}