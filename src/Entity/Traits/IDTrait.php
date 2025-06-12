<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IDTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
}
