<?php

namespace App\Entity\Enumerates;

enum Days: string
{
    case Lundi = "Lundi";
    case Mardi = "Mardi";
    case Mercredi = "Mercredi";
    case Jeudi = "Jeudi";
    case Vendredi = "Vendredi";
    case Samedi = "Samedi";
    case Dimanche = "Dimanche";
    case Ferie = "Ferie";
}
