<?php

namespace App\Entity\Dto;

use App\Entity\Annee;
use App\Entity\Client;
use App\Entity\User;
use App\Enum\TypeClient;
use App\Enum\TypePrestation;
use DateTime;

class Search
{
  /**
   * @var int
   */
  public $page = 1;

  /**
   * @var string
   */
  public $query = null;

  /**
   * @var string
   */
  public $priorite = null;

  /**
   * @var int|null
   */
  public $limit;

  /**
   * @var string
   */
  public $ordre = null;

  /**
   * @var DateTime
   */
  public $from = null;

  /**
   * @var DateTime
   */
  public $to = null;

  /**
   * @var Annee
   */
  public $annee;

  /**
   * @var Client
   */
  public $client;

  /**
   * @var User
   */
  public $affectedTo;

  /**
   * @var TypeClient
   */
  public $typeClient;

  /**
   * @var TypePrestation
   */
  public $typePrestation;

  /**
   * @var int
   */
  public $isValidated = null;

  /**
   * @var string
   */
  public $statutString = null;

  /**
   * @var string
   */
  public $genre = null;

  /**
   * @var array
   */
  public $roles = [];

  public function __construct() {}

  /**
   * Get the value of query
   *
   * @return  string
   */
  public function getQuery()
  {
    return $this->query;
  }

  /**
   * Set the valu of query
   *
   * @param  string  $query
   *
   * @return  self
   */
  public function setQuery(string $query)
  {
    $this->query = $query;

    return $this;
  }

  /**
   * Get the value of limit
   *
   * @return  int|null
   */
  public function getLimit()
  {
    return $this->limit;
  }

  /**
   * Set the value of limit
   *
   * @param  int|null  $limit
   *
   * @return  self
   */
  public function setLimit($limit)
  {
    $this->limit = $limit;

    return $this;
  }

  /**
   * Get the value of ordre
   *
   * @return  string
   */
  public function getOrdre()
  {
    return $this->ordre;
  }

  /**
   * Set the value of ordre
   *
   * @param  string  $ordre
   *
   * @return  self
   */
  public function setOrdre(string $ordre)
  {
    $this->ordre = $ordre;

    return $this;
  }

  /**
   * Get the value of statutString
   *
   * @return  string
   */
  public function getStatutString()
  {
    return $this->statutString;
  }

  /**
   * Set the value of statutString
   *
   * @param  string  $statutString
   *
   * @return  self
   */
  public function setStatutString(string $statutString)
  {
    $this->statutString = $statutString;

    return $this;
  }

  /**
   * Get the value of roles
   *
   * @return  array
   */
  public function getRoles()
  {
    return $this->roles;
  }

  /**
   * Set the value of roles
   *
   * @param  array  $roles
   *
   * @return  self
   */
  public function setRoles(array $roles)
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * Get the value of genre
   *
   * @return  string
   */
  public function getGenre()
  {
    return $this->genre;
  }

  /**
   * Set the value of genre
   *
   * @param  string  $genre
   *
   * @return  self
   */
  public function setGenre(string $genre)
  {
    $this->genre = $genre;

    return $this;
  }

  /**
   * Get the value of annee
   *
   * @return  Annee
   */
  public function getAnnee()
  {
    return $this->annee;
  }

  /**
   * Set the value of annee
   *
   * @param  Annee  $annee
   *
   * @return  self
   */
  public function setAnnee(Annee $annee)
  {
    $this->annee = $annee;

    return $this;
  }

  /**
   * Get the value of priorite
   *
   * @return  string
   */
  public function getPriorite()
  {
    return $this->priorite;
  }

  /**
   * Set the value of priorite
   *
   * @param  string  $priorite
   *
   * @return  self
   */
  public function setPriorite(string $priorite)
  {
    $this->priorite = $priorite;

    return $this;
  }

  /**
   * Get the value of from
   *
   * @return  DateTime
   */
  public function getFrom()
  {
    return $this->from;
  }

  /**
   * Set the value of from
   *
   * @param  DateTime  $from
   *
   * @return  self
   */
  public function setFrom(?DateTime $from)
  {
    $this->from = $from;

    return $this;
  }

  /**
   * Get the value of to
   *
   * @return  DateTime
   */
  public function getTo()
  {
    return $this->to;
  }

  /**
   * Set the value of to
   *
   * @param  DateTime  $to
   *
   * @return  self
   */
  public function setTo(?DateTime $to)
  {
    $this->to = $to;

    return $this;
  }

  /**
   * Get the value of isValidated
   *
   * @return  int
   */
  public function getIsValidated()
  {
    return $this->isValidated;
  }

  /**
   * Set the value of isValidated
   *
   * @param  int  $isValidated
   *
   * @return  self
   */
  public function setIsValidated(int $isValidated)
  {
    $this->isValidated = $isValidated;

    return $this;
  }

  /**
   * Get the value of typeClient
   *
   * @return  TypeClient
   */
  public function getTypeClient()
  {
    return $this->typeClient;
  }

  /**
   * Set the value of typeClient
   *
   * @param  TypeClient  $typeClient
   *
   * @return  self
   */
  public function setTypeClient(TypeClient $typeClient)
  {
    $this->typeClient = $typeClient;

    return $this;
  }

  /**
   * Get the value of typePrestation
   *
   * @return  TypePrestation
   */
  public function getTypePrestation()
  {
    return $this->typePrestation;
  }

  /**
   * Set the value of typePrestation
   *
   * @param  TypePrestation  $typePrestation
   *
   * @return  self
   */
  public function setTypePrestation(TypePrestation $typePrestation)
  {
    $this->typePrestation = $typePrestation;

    return $this;
  }

  /**
   * Get the value of affectedTo
   *
   * @return  User
   */
  public function getAffectedTo()
  {
    return $this->affectedTo;
  }

  /**
   * Set the value of affectedTo
   *
   * @param  User  $affectedTo
   *
   * @return  self
   */
  public function setAffectedTo(User $affectedTo)
  {
    $this->affectedTo = $affectedTo;

    return $this;
  }

  /**
   * Get the value of client
   *
   * @return  Client
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * Set the value of client
   *
   * @param  Client  $client
   *
   * @return  self
   */
  public function setClient(Client $client)
  {
    $this->client = $client;

    return $this;
  }
}
