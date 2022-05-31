<?php

namespace App\Entity;

use App\Repository\RPRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RPRepository::class)]
class RP extends User
{
    

}
