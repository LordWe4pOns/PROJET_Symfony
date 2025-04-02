<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'Identifiant',
            ])
            ->add('password', PasswordType::class, [
                'label' => "Mot de passe",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('country',
                EntityType::class,
                [
                    'class' => Country::class,
                    'label' => 'Pays',
                    'choice_label' => function (Country $country) {
                        return $country->getName() . ' (' . $country->getCode() . ')';
                    },
                    'placeholder' => 'Select a country',
                    'expanded' => false,
                    'required' => false,
                    'empty_data' => null,
                ]
            )
            ->add('birthday',
                DateType::class,
                [
                    'label' => 'Date de naissance',
                    'widget' => 'choice',
                    'format' => 'dd MM yyyy',
                    'years' => range('1900', date('Y')),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
