<?php

namespace App\Enum;

enum Statut: string
{
    case EN_ATTENTE = 'enAttente';
    case accepte = 'accepte';
    case refuse = 'refuse';
}
