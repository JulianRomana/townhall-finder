<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class SearchFacilitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $minInputValueLength = 2;
        $maxInputValueLength = 40;
        $frenchZipCodeLength = 5;

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la commune',
                'constraints' => [
                    new Length([
                        'min' => $minInputValueLength,
                        'max'=> $maxInputValueLength,
                        'minMessage' => 'Ce champ doit contenir au moins '.$minInputValueLength.' caractères',
                        'maxMessage' => 'Ce champ doit contenir moins '.$maxInputValueLength.' caractères',
                        'allowEmptyString' => false
                    ])
                ],
                'error_bubbling' => false,
            ])
            ->add('zip_code', NumberType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new Length([
                        'min' => $frenchZipCodeLength,
                        'max'=> $frenchZipCodeLength,
                        'minMessage' => 'Un code postal en france possède '.$frenchZipCodeLength.' chiffres !',
                        'maxMessage' => 'Un code postal en france possède '.$frenchZipCodeLength.' chiffres !',
                        'allowEmptyString' => false
                    ])
                ],
                'error_bubbling' => false,
            ])
          ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
