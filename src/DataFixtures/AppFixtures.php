<?php

namespace App\DataFixtures;

use App\Entity\Articles;
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

        //user1
        $user1 = new Users();
        $user1->setEmail('theo@mail.fr');
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, '123456'));
        $manager->persist($user1);

        //user2
        $user2 = new Users();
        $user2->setEmail('alex@mail.fr');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, '123456'));
        $manager->persist($user2);

        $users = [
            $user1,
            $user2
        ];

        //articles
        for ($i = 1; $i <= 10; $i++){
            $article = new Articles();
            $article->setTitre('Article'.$i);
            $article->setSlug('art'.$i);
            $article->setContenu($faker->realText($maxNbChars = 200, $indexSize = 1));
            $article->setCreatedAt(new \DateTime('now'));
            $article->setUpdatedAt(new \DateTime('now'));
            $article->setUsers($users[rand(0,1)]);
            $article->setFeaturedImage('image');
            $manager->persist($article);
        }

        $manager->flush();
    }
}
