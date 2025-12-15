<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
   private Security $security;

   public function __construct(
      Security $security
   ) {
      $this->security = $security;
   }

   public function getLogedInUser()
   {
      // Récupérer l'utilisateur connecté
      /** @var User $user */
      $user = $this->security->getUser();
      return $user;
   }

   public function getAccess()
   {
      //return $this->getLogedInUser()->getCompte();
   }

   /**
    * Vérifie si l'utilisateur a le rôle donné.
    *
    * @param string $role Le rôle à vérifier (ex: 'ROLE_ADMIN')
    * @return bool True si l'utilisateur a le rôle, sinon False
    */
   public function hasRole(string $role): bool
   {
      return $this->security->isGranted($role);
   }

   public function isRole(string $role): bool
   {
      if (in_array($role, $this->getLogedInUser()->getRoles())) {
         return true;
      }

      return false;
   }
}
