<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\MotsCles;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //create fake information
        $faker = \Faker\Factory::create('fr_FR');

        //users
        $user1 = new Users();
        $user1->setEmail('theo@mail.fr');
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, '123456'));
        $user1->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setEmail('alex@mail.fr');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, '123456'));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        $users = [
            $user1,
            $user2
        ];

        //catégories
        $cat1 = new Categories();
        $cat1->setNom('Faits divers');
        $manager->persist($cat1);

        $cat2 = new Categories();
        $cat2->setNom('Sport');
        $manager->persist($cat2);

        $cat3 = new Categories();
        $cat3->setNom('Politique');
        $manager->persist($cat3);

        $categories = [
            $cat1,
            $cat2,
            $cat3
        ];

        //Mots-clés
        $motCle1 = new MotsCles();
        $motCle1->setMotCle('France');
        $manager->persist($motCle1);

        $motCle2 = new MotsCles();
        $motCle2->setMotCle('Angers');
        $manager->persist($motCle2);

        $motCle3 = new MotsCles();
        $motCle3->setMotCle('Tramway');
        $manager->persist($motCle3);

        $motsCles = [
            $motCle1,
            $motCle2,
            $motCle3
        ];

        //articles
        for ($i = 1; $i <= 10; $i++){
            $article = new Articles();
            $article->setTitre('Article'.$i);
            $article->setContenu($faker->realText($maxNbChars = 200, $indexSize = 1));
            $article->setUsers($users[rand(0,1)]);
            $article->addCategory($categories[rand(0,2)]);
            $article->addMotsCle($motsCles[rand(0,2)]);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
