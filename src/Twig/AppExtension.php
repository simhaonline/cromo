<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\Session\Session;
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
            new \Twig_Function('notificar', [$this, 'getNotifies']),
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

    /**
     * Esta función se encarga de imprimir las notificaciones para usuario (Mensajes).
     * @return string
     */
    public function getNotifies() {
        $session = new Session();
        $flashes = $session->getFlashBag()->all();
        $html = [];
        foreach($flashes as $type=>$messages) {
            foreach($messages AS $message) {
                $span = $this->createTag("span", "&times;", ['aria-hidden'=>'true']);
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
        $attrs = implode(" ", array_map(function($attr, $value){ return "{$attr}=\"{$value}\""; }, array_keys($attrs), $attrs));
        return "<{$tag}" . ($attrs? " {$attrs}" : "") . ">{$content}</{$tag}>";
    }
}