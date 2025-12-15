<?php

namespace App\Service;

use App\Entity\Annee;
use App\Repository\AnneeRepository;
use DateTime;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class AnneeService
{
   private $request;

   public function __construct(
      private AnneeRepository $anneeRepository,
      RequestStack $requestStack,
      Security $security
   ) {
      $this->request = $requestStack->getCurrentRequest();
   }

   /**
    * Get active year
    * @return Annee
    */
   public function getAnneeEncours()
   {
      /** @var Annee $annee */
      $annee = $this->anneeRepository->findOneBy([
         'statut' => "En cours"
      ]);

      return $annee;
   }

   /**
    * Get current year, selected by user
    * @return Annee
    */
   public function getCurrenteAnnee()
   {
      /** @var Annee $annee */
      $annee = $this->anneeRepository->findOneBy([
         'currente' => true
      ]);

      if (!empty($this->getAnneeInsession())) {
         /** @var Annee $annee */
         $annee = $this->getAnneeInsession();
      }

      return $annee;
   }

   /**
    * Vérifie si l'écart entre deux dates est exactement de 1 an (pas plus, pas moins)
    *
    * @param string|\DateTimeInterface|null $startDate Date de début
    * @param string|\DateTimeInterface|null $endDate Date de fin
    * @return bool true si exactement 1 an, sinon false
    */
   public function isExactlyOneYear($startDate, $endDate): bool
   {
      if ($startDate === null || $endDate === null) {
         return false;
      }

      // Conversion si c'est une string
      if (is_string($startDate)) {
         $startDate = DateTime::createFromFormat('Y-m-d', $startDate) ?: null;
      }
      if (is_string($endDate)) {
         $endDate = DateTime::createFromFormat('Y-m-d', $endDate) ?: null;
      }

      // Vérifie que la conversion a réussi
      if (!($startDate instanceof \DateTimeInterface) || !($endDate instanceof \DateTimeInterface)) {
         return false;
      }

      // Vérifie que endDate est après startDate
      if ($endDate <= $startDate) {
         return false;
      }

      $interval = $startDate->diff($endDate);

      // Doit être exactement 1 an, 0 mois et 0 jours
      return $interval->y === 1 && $interval->m === 0 && $interval->d === 0;
   }

   /**
    * Summary of setAnneeInSession
    * @param \App\Entity\Annee $annee
    */
   public function setAnneeInSession(Annee $annee)
   {
      // Enregistrement de la valeur en session
      $this->request->getSession()->set('annee', $annee);
   }

   /**
    * Summary of getAnneeInsession
    * @return object|null
    */
   public function getAnneeInsession()
   {
      $annee = $this->anneeRepository->findOneBy([
         'currente' => true,
      ]);
      $checkAnnee = $this->request->getSession()->get('annee');

      if ($checkAnnee) {
         $annee = $annee = $this->anneeRepository->findOneBy([
            'id' => $checkAnnee->getId()
         ]);
      }

      return $annee;
   }

   /**
    * Calculate Date End
    * @param \DateTime $dateDebut
    * @param int $intervalTemps
    * @throws \InvalidArgumentException
    * @return bool|DateTime
    */
   public function calculateDateEnd(DateTime $dateDebut, int $intervalTemps = 1): DateTime
   {
      if ($intervalTemps < 1) {
         throw new \InvalidArgumentException('La durée doit être d’au moins 1 an.');
      }

      // Récupération du jour/mois/année de début
      $jour = (int) $dateDebut->format('d');
      $mois = (int) $dateDebut->format('m');
      $annee = (int) $dateDebut->format('Y');

      // Ajout de la durée en années
      $nouvelleAnnee = $annee + $intervalTemps;

      // Création de la date de fin exacte
      return DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $nouvelleAnnee, $mois, $jour));
   }

   /**
    * Calculate member age
    * @param mixed $dateNaissance
    * @return int|null
    */
   public function calculateAge(?\DateTimeInterface $dateNaissance): ?int
   {
      if (!$dateNaissance) {
         return null;
      }

      $today = new \DateTimeImmutable();
      return $today->diff($dateNaissance)->y;
   }
}
