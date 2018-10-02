<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Extension\AbstractExtension;

//Funciones personalizadas para twig
class AppExtension extends AbstractExtension
{
    public function getEnv($env) {
        return getenv($env);
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('validarTipo', [$this, 'validarTipo']),
            new \Twig_Function('validarTipoDetalle', [$this, 'validarTipoDetalle']),
            new \Twig_Function('separarPorGuionbajo', [$this, 'separarPorGuionbajo']),
            new \Twig_Function('validarSeleccionado', [$this, 'validarSeleccionado']),
            new \Twig_Function('esId', [$this, 'esId']),
            new \Twig_Function('esEstado', [$this, 'esEstado']),
            new \Twig_Function('mod', [$this, 'mod']),
            new \Twig_Function('validarLongitudTexto', [$this, 'validarLongitudTexto']),
            new \Twig_Function('crearTitulo', [$this, 'crearTitulo']),
            new \Twig_Function('llenarArray', [$this, 'llenarArray']),
            new \Twig_Function('notificar', [$this, 'getNotifies']),
            new \Twig_Function('encriptar', [$this, 'encriptar']),
            new \Twig_Function('env', [$this, "getEnv"]),
            new \Twig_Function('mesATexto', [$this, "mesATexto"]),
            new \Twig_Function('crearEncabezadoTabla', [$this, "crearEncabezadoTabla"]),
            new \Twig_Function('crearCuerpoTabla', [$this, "crearCuerpoTabla"]),
            new \Twig_Function('generarArrRegistros', [$this, "generarArrRegistros"]),
        ];
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @param string $tabla
     * @return string
     */
    public function validarTipo($dato, $tabla = "")
    {
        if ($tabla != '') {
            if ($this->esFecha($dato)) {
                $tabla .= "<td style='text-align: left;'>" . $dato->format('Y-m-d') . "</td>";
            } elseif ($this->esBooleano($dato)) {
                if ($dato) {
                    $tabla .= "<td style='text-align: left;'>" . 'SI' . "</td>";
                } else {
                    $tabla .= "<td style='text-align: left;'>" . 'NO' . "</td>";
                }
            } elseif (is_numeric($dato)) {
                $tabla .= "<td style='text-align: right;'>" . $dato . "</td>";
            } else {
                $tabla .= "<td style='text-align: left;'>" . $dato . "</td>";
            }
            return $tabla;
        } else {
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
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     */
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

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return string
     */
    public function separarPorGuionbajo($dato)
    {
        $dato = preg_split('/(?=[A-Z])/', $dato);
        $dato = implode('_', $dato);
        return strtoupper($dato);
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return string
     */
    public function validarSeleccionado($dato)
    {
        if ($dato) {
            return 'checked';
        } else {
            return '';
        }
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return mixed
     */
    public function mesATexto($dato)
    {
        $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        return $meses[$dato - 1];
    }

    /**
     * @author Andres Acevedo
     * @param $dato
     * @return bool
     */
    public function esId($dato)
    {
        if (preg_match('/Pk/', $dato)) {
            return true;
        } else {
            return false;
        }

    }

    public function encriptar($dato)
    {
        return str_replace('/', '&', password_hash($dato, PASSWORD_BCRYPT));
    }

    public function esEstado($dato)
    {
        if (preg_match('/estado/', $dato)) {
            if (preg_match('/Anulado/', $dato)) {
                return true;
            } elseif (preg_match('/Aprobado/', $dato)) {
                return true;
            } elseif (preg_match('/Autorizado/', $dato)) {
                return true;
            }
        } else {
            return false;
        }
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

    public function generarArrRegistros($arrOpciones, $request)
    {
        global $kernel;
        $paginator = $kernel->getContainer()->get('knp_paginator');
        return $paginator->paginate($arrOpciones['query'], $request->query->getInt('page', 1), 3);
    }

    public function crearEncabezadoTabla($arrOpciones, $check)
    {
        $campos = json_decode($arrOpciones['json']);
        $arrTipos = [];
        $header = '';
        foreach ($campos as $campo) {
            $arrTipos[$campo->campo] = $campo->tipo;
            $header .= "<th title='" . $campo->ayuda . "'>" . $campo->titulo . "</th>";
        }
        if ($check) {
            $header .= "<th></th>";
        }
        $header .= "</tr>";
        echo $header;
    }

    public function crearCuerpoTabla($arrOpciones, $check, $request)
    {
        $pk = 0;
        $arRegistros = $this->generarArrRegistros($arrOpciones, $request);
        $tabla = '';
        foreach ($arRegistros as $arRegistro) {
            $tabla .= "<tr>";
            foreach ($arRegistro as $key => $dato) {
                $tabla = $this->validarTipo($dato, $tabla);
                if (strpos($key, 'Pk')) {
                    $pk = $dato;
                }
            }
            if ($check) {
                $tabla .= "<td style='text-align: center;'><input type='checkbox' name='ChkSeleccionar[]' value='" . $pk . "'></td>";
            }
            $tabla .= "</tr>";
        }
        echo $tabla;
    }

    public function llenarArray($array, $alias, $dato)
    {
        $array[$alias] = $dato;
        return $array;
    }

    /**
     * Esta función se encarga de imprimir las notificaciones para usuario (Mensajes).
     * @return string
     */
    public function getNotifies()
    {
        $session = new Session();
        $flashes = $session->getFlashBag()->all();
        $html = [];
        foreach ($flashes as $type => $messages) {
            foreach ($messages AS $message) {
                $span = $this->createTag("span", "&times;", ['aria-hidden' => 'true']);
                $button = $this->createTag("button", $span, ['class' => 'close', 'data-dismiss' => 'alert', 'aria-label' => 'Close']);
                $alert = $this->createTag("div", $button . $message, ['class' => "alert alert-{$type}", 'data']);
                $html[] = $alert;
            }
        }
        $session->getFlashBag()->clear();
        return implode('', $html);
    }

    /**
     * Esta función nos permite obtener código html sin violar estandares de mezcla de código.
     * @param $tag
     * @param string $content
     * @param array $attrs
     * @return string
     */
    private function createTag($tag, $content = '', $attrs = [])
    {
        $attrs = implode(" ", array_map(function ($attr, $value) {
            return "{$attr}=\"{$value}\"";
        }, array_keys($attrs), $attrs));
        return "<{$tag}" . ($attrs ? " {$attrs}" : "") . ">{$content}</{$tag}>";
    }

    private function esFecha($dato)
    {
        if ($dato instanceof \DateTime) {
            return true;
        } else {
            return false;
        }
    }

    private function esBooleano($dato)
    {
        if (is_bool($dato)) {
            return true;
        } else {
            return false;
        }
    }
}