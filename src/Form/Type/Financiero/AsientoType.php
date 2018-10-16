<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinAsiento;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('fecha', DateType::class, ['label' => 'Fecha:'])
            ->add('numero', NumberType::class, ['label' => 'Número:'])
            ->add('comentario', TextareaType::class, ['label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinAsiento::class,
        ]);
    }

    public function getEstructuraPropiedadesLista() {
        $campos = '[
            {"campo":"codigoAsientoPk",     "tipo":"pk",    "ayuda":"Codigo del asiento",   "titulo":"ID"},
            {"campo":"fecha",               "tipo":"fecha", "ayuda":"Fecha de registro",    "titulo":"FECHA"},
            {"campo":"fechaContable",       "tipo":"fecha", "ayuda":"Fecha de contabilidad","titulo":"F_CONT"},
            {"campo":"fechaDocumento",      "tipo":"fecha", "ayuda":"Fecha de elaboracion", "titulo":"F_DOC"},
            {"campo":"estadoAutorizado",    "tipo":"bool",  "ayuda":"",                     "titulo":"AUT"},
            {"campo":"estadoAprobado",      "tipo":"bool",  "ayuda":"",                     "titulo":"APR"},
            {"campo":"estadoAnulado",       "tipo":"bool",  "ayuda":"",                     "titulo":"ANU"}
                                
        ]';
        return $campos;
    }
}
