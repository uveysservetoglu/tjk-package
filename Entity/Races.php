<?php

namespace TjkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Races
 *
 * @ORM\Table(name="races")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RacesRepository")
 */
class Races
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="raceDate", type="date")
     */
    private $raceDate;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=30)
     */
    private $city;

    /**
     * @var json
     *
     * @ORM\Column(name="data", type="json")
     */
    private $data;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set raceDate.
     *
     * @param \DateTime $raceDate
     *
     * @return Races
     */
    public function setRaceDate($raceDate)
    {
        $this->raceDate = $raceDate;

        return $this;
    }

    /**
     * Get raceDate.
     *
     * @return \DateTime
     */
    public function getRaceDate()
    {
        return $this->raceDate;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return Races
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set data.
     *
     * @param json $data
     *
     * @return Races
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return json
     */
    public function getData()
    {
        return $this->data;
    }
}
