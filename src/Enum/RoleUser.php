<?php

namespace App\Enum;

enum RoleUser: string
{
  case CHEF_PROJET = 'Chef de projet';

  case CONSULTANT = 'Consultant';

  public static function choices(): array
  {
    return [
      'Chef de projet' => self::CHEF_PROJET,
      'Consultant' => self::CONSULTANT
    ];
  }
}
