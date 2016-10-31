<?php
namespace DIC\DicoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * countries
 *
 * @ORM\Table(name="tc_countries")
 * @ORM\Entity(repositoryClass="Sim100\TutoBundle\Repository\countriesRepository")
 */
class words
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $eid;

    /**
     * @var int
     *
     * @ORM\Column(name="t", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $t;

    /**
     * @var int
     *
     * @ORM\Column(name="w", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $w;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $typeNode;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $nf;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * Get eid
     *
     * @return int
     */
    public function getEId()
    {
        return $this->eid;
    }

    /**
     * Get t
     *
     * @return int
     */
    public function getT()
    {
        return $this->t;
    }
    /**
     * Get W
     *
     * @return int
     */
    public function getW()
    {
        return $this->w;
    }

    /**
     * Get typeNode
     *
     * @return string
     */
    public function getTypeNode()
    {
        return $this->typeNode;
    }

    /**
     * Get nf
     *
     * @return string
     */
    public function getNf()
    {
        return $this->nf;
    }



    /**
     * Set name
     *
     * @param string $name
     *
     * @return countries
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
