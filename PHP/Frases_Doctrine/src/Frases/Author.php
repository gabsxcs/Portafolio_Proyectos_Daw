<?php
namespace Frases\Entity;

/**
 * Author
 * 
 * @Entity(repositoryClass="Frases\Repository\AuthorRepository")
 * @Table(name="tbl_Authors")
 */
class Author{
    
    /**
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    
    /**
     * @var string
     * @Column(type="string", length=100, unique=true)
     */
    private $name;
    
    
    /**
     * @var string
     * @Column(type="string", length=500, nullable=true)
     */
    private $description;
    
    
    /**
     * @var \Frases\Entity\Phrase[]
     * @OneToMany(targetEntity="Frases\Entity\Phrase", mappedBy="author")
     */
    private $phrases;
    
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->phrases = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Obtiene un recuento de frases
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
     * @return Author
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Author
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Obtener las frases
     */
    public function getPhrases() {
        return $this->phrases;
    }
    
}

