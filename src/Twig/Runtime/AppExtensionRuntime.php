<?php

namespace App\Twig\Runtime;

use App\Repository\AnneeRepository;
use App\Service\AnneeService;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private AnneeService $anneeService,
        private AnneeRepository $anneeRepository
    ) {
        // Inject dependencies if needed
    }

    /**
     * Get Badge Badge
     * @param mixed $string
     * @return string
     */
    public function getBadge($string)
    {
        $badge = 'warning';

        if ($string == "Terminé" || $string == "success" || $string == "COLLABORATEUR") {
            $badge = "success";
        } elseif ($string == "primary" || $string == "A venir" || $string == "En cours") {
            $badge = "primary";
        } elseif ($string == "Confirmée" || $string == "Entrées") {
            $badge = "success";
        } elseif ($string == "dark") {
            $badge = "dark";
        } elseif ($string == "info" || $string == "MANAGER") {
            $badge = "info";
        } elseif ($string == "danger" || $string == "Annulé" || $string == "Rejetée" || $string == "ADMIN") {
            $badge = "danger";
        }

        return "badge  bg-$badge-subtle text-$badge";
    }

    /**
     * Get année en cours
     * @return \App\Entity\Annee
     */
    public function getAnneeEnCours()
    {
        return $this->anneeService->getAnneeEncours();
    }

    /**
     * Get all Années
     * @return array
     */
    public function getAnnees()
    {
        return $this->anneeRepository->findBy([], ['id' => 'DESC']);
    }

    /**
     * Get current année
     * @return \App\Entity\Annee
     */
    public function getCurrenteAnnee()
    {
        return $this->anneeService->getCurrenteAnnee();
    }
}
