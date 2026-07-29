<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Synmfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\Timestampable;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name: 'videos')]
class Video
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    // #[ORM\Column(length: 50)]
    // #[ORM\NotBlank(Message: 'Le titre ne peut pas être vide.')]
    // #[ORM\Length(
    //     min: 3,
    //     max: 50,
    //     minMessage: 'Le titre doit contenir au moins {{ limit }} caractères.',
    //     maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
    // )]
    // #[ORM\Column(length: 500)]
    // #[Assert\NotBlank(message: `Le lien vidéo ne peut pas être vide.`)]

    public function getId(): ?int
    {
        return $this->id;
    }
}
