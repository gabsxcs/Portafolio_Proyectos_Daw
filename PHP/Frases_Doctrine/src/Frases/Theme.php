<?php
namespace Frases\Entity;

/**
 * Theme
 * 
 * @Entity(repositoryClass="Frases\Repository\ThemeRepository")
 * @Table(name="tbl_Themes")
 */
class Theme{
    
    /**
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    
    /**
     * @var string
     * @Column(type="string", length=50, unique=true)
     */
    private $name;
    
    /**
     * @var Phrase[]
     * @ManyToMany(targetEntity="Phrase", mappedBy="themes")
     */
    private $phrases;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phrases = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Obtiene un recuento de las frases
     */
    public function getNumFrases() {
        return count($this->phrases);
    }
    

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
     * Set name.
     *
     * @param string $name
     *
     * @return Theme
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add phrase.
     *
     * @param \Frases\Entity\Phrase $phrase
     *
     * @return Theme
     */
    public function addPhrase(\Frases\Entity\Phrase $phrase)
    {
        $this->phrases[] = $phrase;

        return $this;
    }

    /**
     * Remove phrase.
     *
     * @param \Frases\Entity\Phrase $phrase
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePhrase(\Frases\Entity\Phrase $phrase)
    {
        return $this->phrases->removeElement($phrase);
    }

    /**
     * Get phrases.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhrases()
    {
        return $this->phrases;
    }
    
}

