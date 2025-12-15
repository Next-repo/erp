<?php

namespace App\Enum;

enum TypePrestation: string
{
  case TECHNIQUES = 'Techniques';

  case ORGANISATIONNELLES = 'Organisationnelles';

  case STRATEGIQUES = 'Stratégiques';

  public static function choices(): array
  {
    return [
      'Techniques' => self::TECHNIQUES,
      'Organisationnelles' => self::ORGANISATIONNELLES,
      'Stratégiques' => self::STRATEGIQUES
    ];
  }
}
