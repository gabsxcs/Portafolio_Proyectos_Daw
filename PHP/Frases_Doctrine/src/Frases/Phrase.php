<?php
namespace Frases\Entity;


/**
 * Phrase
 * 
 * @Entity(repositoryClass="Frases\Repository\PhraseRepository")
 * @Table(name="tbl_Phrases")
 */
class Phrase{
    
    /**
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;
    
    /**
     * @var Author
     * @ManyToOne(targetEntity="Frases\Entity\Author", inversedBy="phrases")
     * @JoinColumn(name="autor_id", referencedColumnName="id", nullable=false)
     */
    private $author;
    
    /**
     * @var string
     * @Column(type="string", length=550, unique=true)
     */
    private $texto;
    
    /**
     * @var Theme[]
     * @ManyToMany(targetEntity="Theme", inversedBy="phrases")
     * @JoinTable(name="tbl_Phrase_Theme",
     *      joinColumns={@JoinColumn(name="frase_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="tema_id", referencedColumnName="id")}
     * )
     */
    private $themes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->themes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set texto.
     *
     * @param string $texto
     *
     * @return Phrase
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto.
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set author.
     *
     * @param \Frases\Entity\Author $author
     *
     * @return Phrase
     */
    public function setAuthor(\Frases\Entity\Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \Frases\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add theme.
     *
     * @param \Frases\Entity\Theme $theme
     *
     * @return Phrase
     */
    public function addTheme(\Frases\Entity\Theme $theme)
    {
        $this->themes[] = $theme;

        return $this;
    }

    /**
     * Remove theme.
     *
     * @param \Frases\Entity\Theme $theme
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTheme(\Frases\Entity\Theme $theme)
    {
        return $this->themes->removeElement($theme);
    }

    /**
     * Get themes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThemes()
    {
        return $this->themes;
    }
}

