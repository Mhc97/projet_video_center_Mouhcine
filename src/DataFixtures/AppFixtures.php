<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer 4 utilisateurs
        $users = [];
        
        $userData = [
            ['firstname' => 'Alice', 'lastname' => 'Martin', 'email' => 'alice@test.com', 'verified' => true],
            ['firstname' => 'Bob', 'lastname' => 'Durand', 'email' => 'bob@test.com', 'verified' => true],
            ['firstname' => 'Charlie', 'lastname' => 'Petit', 'email' => 'charlie@test.com', 'verified' => true],
            ['firstname' => 'Diana', 'lastname' => 'Leroy', 'email' => 'diana@test.com', 'verified' => false],
        ];

        foreach ($userData as $data) {
            $user = new User();
            $user->setFirstname($data['firstname'])
                 ->setLastname($data['lastname'])
                 ->setEmail($data['email'])
                 ->setPassword($this->passwordHasher->hashPassword($user, 'password123'))
                 ->setVerified($data['verified'])
                 ->setImageName('default-avatar.png');
            
            $manager->persist($user);
            $users[] = $user;
        }

        // IDs YouTube pour les vidéos
        $youtubeIds = [
            'lcZDWo6hiuI', 'dQw4w9WgXcQ', '3JZ_D3ELwOQ', 'L_jWHffIx5E',
            'kXYiU_JCYtU', 'fJ9rUzIMcZQ', '9bZkp7q19f0', 'KYniUCGPGLs',
            'CevxZvSJLk8', 'u9Dg-g7t2l4', 'rYEDA3JcQqw', '4fndeDfaWCg',
            '6Dh-RL__uN4', 'RgKAFK5djSk', 'QH2-TGUlwu4', 'hT_nvWreIhg',
            'y6Sxv-sUYtM', 'kJQP7kiw5Fk', 'XqZsoesa55w', 'VYOjWnS4cMY'
        ];

        $videoTitles = [
            'Tutoriel Symfony - Débutant', 'Comment installer PHP', 'Découverte de MySQL',
            'Les bases de JavaScript', 'CSS Flexbox expliqué', 'API REST avec Symfony',
            'Introduction à Docker', 'Git pour les nuls', 'Déployer sur AlwaysData',
            'Les hooks React', 'Python pour les data scientists', 'Sécurité web',
            'Bootstrap 5 en 1 heure', 'Comprendre les cookies', 'Les WebSockets',
            'Machine Learning avec Python', 'ElasticSearch pour les devs', 'Redis et le caching',
            'Les microservices', 'Kubernetes en 10 minutes'
        ];

        // Créer 20 vidéos
        // Premium = les 12 premières
        // 8 non-premium (vidéos 12 à 19)
        for ($i = 0; $i < 20; $i++) {
            $video = new Video();
            $isPremium = $i < 12; // 12 premium, 8 non-premium
            
            // Assigner à un utilisateur vérifié (Alice, Bob, Charlie)
            $userIndex = $i % 3;
            $user = $users[$userIndex];
            
            $video->setTitle($videoTitles[$i])
                  ->setVideoLink('https://www.youtube.com/watch?v=' . $youtubeIds[$i])
                  ->setDescription("Description de la vidéo " . ($i + 1) . " : " . $videoTitles[$i] . ". Cette vidéo est " . ($isPremium ? 'réservée aux utilisateurs premium' : 'accessible à tous') . ".")
                  ->setPremiumVideo($isPremium)
                  ->setUser($user);

            $manager->persist($video);
        }

        $manager->flush();
    }
}