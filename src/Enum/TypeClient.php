<?php

namespace App\Enum;

enum TypeClient: string
{
  case PERSONNE_PHYSIQUE = 'Personne physique';

  case PERSONNE_MORALE = 'Personne morale';

  public static function choices(): array
  {
    return [
      'Personne physique' => self::PERSONNE_PHYSIQUE,
      'Personne morale' => self::PERSONNE_MORALE
    ];
  }
}
