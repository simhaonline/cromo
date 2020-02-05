<?php
namespace App\Form\Type\Transporte;
use App\Entity\Transporte\TteCondicion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CondicionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('precioRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TtePrecio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => function($er){
                $campo = $er->getCodigoPrecioPk() .  '-'  . $er->getNombre();

                return $campo;

                }
            ])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('porcentajeManejo',NumberType::class,['required' => true,'label' => 'Porcentaje:'])
            ->add('manejoMinimoUnidad',NumberType::class,['required' => true,'label' => 'Unidad minima:'])
            ->add('manejoMinimoDespacho',NumberType::class,['required' => true,'label' => 'Despacho minimo:'])
            ->add('precioPeso', CheckboxType::class, array('required'  => false))
            ->add('precioUnidad', CheckboxType::class, array('required'  => false))
            ->add('precioAdicional', CheckboxType::class, array('required'  => false))
            ->add('descuentoPeso',NumberType::class,['required' => true,'label' => 'Descuento peso:'])
            ->add('descuentoUnidad',NumberType::class,['required' => true,'label' => 'Descuento unidad:'])
            ->add('pesoMinimo',NumberType::class,['required' => true,'label' => 'Peso minimo:'])
            ->add('permiteRecaudo', CheckboxType::class, ['required' => false,'label' => 'Permite recaudo'])
            ->add('precioGeneral', CheckboxType::class, ['required' => false,'label' => 'Precio general'])
            ->add('redondearFlete', CheckboxType::class, ['required' => false,'label' => 'Redondear flete'])
            ->add('limitarDescuentoReexpedicion', CheckboxType::class, ['required' => false,'label' => 'Limitar descuento reexpedicion'])
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCondicion::class,
        ]);
    }



    public function getEstructuraPropiedadesExportar(){
        return '[
            {"campo":"codigoCondicionPk",           "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},
            {"campo":"codigoPrecioFk",              "tipo":"numero",    "ayuda":"Codigo precio",           "titulo":"CODIGO PRECIO"},
            {"campo":"porcentajeManejo",            "tipo":"texto",     "ayuda":"Porcentaje manejo",       "titulo":"PORCENTAJE MANEJO"},
            {"campo":"manejoMinimoUnidad",          "tipo":"texto",     "ayuda":"Manejo minimo unidad",    "titulo":"MANEJO MINIMO UNIDAD"},
            {"campo":"manejoMinimoDespacho",        "tipo":"texto",     "ayuda":"Manejo minimo despacho",  "titulo":"MANEJO MINIMO DESPACHO"},
            {"campo":"precioPeso",                  "tipo":"bool",      "ayuda":"Precio peso",             "titulo":"PRECIO PESO"},
            {"campo":"precioUnidad",                "tipo":"bool",      "ayuda":"Precio unidad",           "titulo":"PRECIO UNIDAD"},
            {"campo":"precioAdicional",             "tipo":"bool",      "ayuda":"Precio adicional",        "titulo":"PRECIO ADICIONAL"},
            {"campo":"descuentoPeso",               "tipo":"texto",     "ayuda":"Descuento peso",          "titulo":"DESCUENTO PESO"},
            {"campo":"descuentoUnidad",             "tipo":"texto",     "ayuda":"Descuento unidad",        "titulo":"DESCUENTO UNIDAD"},
            {"campo":"pesoMinimo",                  "tipo":"numero",    "ayuda":"Peso minimo",             "titulo":"PESO MINIMO"},
            {"campo":"permiteRecaudo",              "tipo":"bool",      "ayuda":"Permite recaudo",         "titulo":"PERMITE RECAUDO"},
            {"campo":"precioGeneral",               "tipo":"bool",      "ayuda":"Precio general",          "titulo":"PRECIO GENERAL"},
            {"campo":"redondearFlete",              "tipo":"bool",      "ayuda":"Redondear flete",         "titulo":"REDONDEAR FLETE"},
            {"campo":"limitarDescuentoReexpedicion","tipo":"bool",      "ayuda":"Limitar descuento reexpedicion","titulo":"LIMITAR DESCUENTO REEXPEDICION"},
            {"campo":"comentario",                  "tipo":"texto",     "ayuda":"Comentario",              "titulo":"COMENTARIO"}
        ]';
    }
}
