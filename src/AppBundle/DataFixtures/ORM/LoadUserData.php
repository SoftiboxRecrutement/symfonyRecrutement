<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\User;

class LoadUserData extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $users = [
            [
                'name'=>'Marotia',
                'lastname'=>'Dodson',
                'username'=>'Dodson29',
                'telephone'=>'000 00 000 00',
                'email'=>'marotia2904@yahoo.com',
                'password'=>'123456789'
            ],
            [
                'name'=>'Fa Nomena',
                'lastname'=>'Feno',
                'username'=>'Feno',
                'telephone'=>'000 00 000 00',
                'email'=>'feno@gmail.com',
                'password'=>'123456789'
            ],
            [
                'name'=>'Moraintsoa',
                'lastname'=>'Renel',
                'username'=>'Nala',
                'telephone'=>'000 00 000 00',
                'email'=>'renel@gmail.com',
                'password'=>'123456789'
            ],
            [
                'name'=>'Andriniaina',
                'lastname'=>'Promesse',
                'username'=>'Pro92',
                'telephone'=>'000 00 000 00',
                'email'=>'promesse@gmail.com',
                'password'=>'123456789'
            ],
            [
                'name'=>'Odile',
                'lastname'=>'Samoelah',
                'username'=>'Sam',
                'telephone'=>'000 00 000 00',
                'email'=>'sam@gmail.com',
                'password'=>'123456789'
            ],
            [
                'name'=>'Maroarivo',
                'lastname'=>'Prosper',
                'username'=>'Prosper',
                'telephone'=>'000 00 000 00',
                'email'=>'prosper@yahoo.com',
                'password'=>'123456789'
            ],
            [
                'name'=>'Rembala',
                'lastname'=>'Ronaldo',
                'username'=>'naly98',
                'telephone'=>'000 00 000 00',
                'email'=>'naly@yahoo.com',
                'password'=>'123456789'
            ]
        ];
        foreach ($users as $i => $user) {
            $tag[$i] = new User();
            $tag[$i]->setUsername($user['username']);
            $tag[$i]->setEmail($user['email']);
            $tag[$i]->setSalt(uniqid(mt_rand()));
            $password = $this->encoder->encodePassword($tag[$i],$user['password']);
            $tag[$i]->setPassword($password);
            $tag[$i]->setName($user['name']);
            $tag[$i]->setLastname($user['lastname']);
            $tag[$i]->setTelephone($user['telephone']);
            $tag[$i]->setEnabled(true);

            if ($i == 0) {
                $tag[$i]->setRoles(array('ROLE_ADMIN'));
            }

            $manager->persist($tag[$i]);
        }
        $manager->flush();
    }
}