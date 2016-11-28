<?php
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;


/**
 * @JMS\ExclusionPolicy("all")
 * @ORM\Table(options={"collate"="utf8_general_ci"})
 * @ORM\Entity
 */
class Image
{

    /**
     * @return mixed
     */
    public function __toString() {

    }
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     *
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     * @JMS\Expose
     */
    private $path;



    /**
     *  @ORM\OneToMany(targetEntity="User", mappedBy="image")
     *
     */

    public $users;
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Address
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
