<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

//Funciones personalizadas para twig
class AppExtension extends AbstractExtension
{

    public function getFunctions(){
        return [
            new \Twig_Function('isBoolean',[$this,'isBoolean']),
            new \Twig_Function('isDate',[$this,'isDate']),
            new \Twig_Function('boolAnswer',[$this,'boolAnswer']),
            new \Twig_Function('dateAnswer',[$this,'dateAnswer']),
        ];
    }

    public function isBoolean($dato){
        if(is_bool($dato)){
            return true;
        } else {
            return false;
        }
    }

    public function isDate($dato){
        if($dato instanceof  \DateTime){
            return true;
        } else {
            return false;
        }
    }

    public function boolAnswer($dato)
    {
        if ($dato) {
            return 'SI';
        } else {
            return 'NO';
        }
    }

    public function dateAnswer($dato){
        if($dato){
            return $dato->format('Y-m-d');
        } else {
            return '';
        }
    }
}