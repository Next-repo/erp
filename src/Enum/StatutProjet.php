<?php

namespace App\Enum;

enum StatutProjet: string
{
  case EN_ATTENTE = 'En attente';

  case EN_COURS = 'En cours';

  case TERMINER = 'Terminé';

  case ANNULER = 'Annulé';

  public static function choices(): array
  {
    return [
      'En attente' => self::EN_ATTENTE,
      'En cours' => self::EN_COURS,
      'Terminé' => self::TERMINER,
      'Annulé' => self::ANNULER
    ];
  }
}
