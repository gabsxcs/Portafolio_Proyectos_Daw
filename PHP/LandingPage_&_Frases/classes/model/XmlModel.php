<?php

class XmlModel{
    
    private $authorModel;
    private $themeModel;
    private $phraseModel;
    private $authors = [];
    private $themes = [];
    private $phrases = [];
    private $db;
    
    public function __construct() {
        
        $this->authorModel = new AuthorModel();
        $this->themeModel = new ThemesModel();
        $this->phraseModel = new PhrasesModel();
        $this->db = PDODatabase::getInstance();
    }
    
    //recorre el xml y lo procesa
    public function processXML($xmlPath) {
        if (!file_exists($xmlPath)) {
            die("Error: No se encontró el archivo XML.");
        }
        
        $xml = simplexml_load_file($xmlPath);
        
        foreach ($xml->autor as $autor) {
            $nombre = (string) $autor->nombre;
            $descripcion = (string) $autor->descripcion;
            $url = (string) $autor['url']; 
            
            if (!$this->authorModel->getAuthorByName($nombre)) {
                $this->authors[] = new Author(null, $nombre, $descripcion, $url, 0);
            }
            
            foreach ($autor->frases->frase as $frase) {
                $texto = (string) $frase->texto;
                $temas = explode(" ", (string) $frase->tema);
                
                foreach ($temas as $temaNombre) {
                
                    if (!isset($this->themes[$temaNombre])) {
                        $this->themes[$temaNombre] = new Theme($temaNombre); 
                    }
                }
                
                $this->phrases[] = [
                    'texto' => $texto,
                    'autor' => $nombre,
                    'temas' => $temas
                ];
            }
        }
        
        $this->insertData();
    }
    
    //hace la funcion de insertar la informacion en la bbdd
    private function insertData() {
        $authorIds = [];
        foreach ($this->authors as $author) {
            $authorId = $this->authorModel->createAuthor($author);
            $authorIds[$author->__get("nombre")] = $authorId;
        }
        
        $themeIds = [];
        foreach ($this->themes as $theme) {
            $temaNombre = $theme->__get("nom");
            if (empty($temaNombre)) {
                continue;
            }
            
            $themeId = $this->themeModel->createTheme($theme);
            $themeIds[$temaNombre] = $themeId;
        }
        
        $phraseIds = [];
        $message = '';
        
        foreach ($this->phrases as $phrase) {
            $message .= "Insertando frase: " . $phrase['texto'] . "<br>";
            
            $authorName = $phrase['autor'];
            $authorId = $this->authorModel->getAuthorIdByName($authorName);
            
            if ($authorId !== null) {
                $phraseId = $this->phraseModel->insertPhrase(new Phrase(null, $phrase['texto'], $authorId));
            } else {
                $message .= "El autor '$authorName' no se encuentra en la base de datos. No se insertará la frase.<br>";
                continue;
            }
            
            $phraseIds[] = $phraseId;
            
            foreach ($phrase['temas'] as $tema) {
                $sql = "INSERT INTO tbl_frases_temas (id_frase, id_tema) VALUES (:id_frase, :id_tema)";
                $this->db->query($sql, [
                    ':id_frase' => $phraseId,
                    ':id_tema' => $themeIds[$tema]
                ]);
            }
        }
        
        header("Location: ?Phrases/show");
        exit();
    }
    
    
    public function eliminarTablas() {
        try {
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->query("DROP DATABASE IF EXISTS frases_Sandoval_Gabriela");
            
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            
            header("Location: index.php?Xml/show");
            exit();
        } catch (Exception $e) {
            echo "Error al borrar las tablas: " . $e->getMessage();
        }
    }
    
    public function crearTablas() {
        try {
            $this->db->query("CREATE DATABASE IF NOT EXISTS frases_Sandoval_Gabriela");
            $this->db->query("USE frases_Sandoval_Gabriela");
            $this->db->query("CREATE TABLE IF NOT EXISTS tbl_autores (
            id_autor INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(1000) NOT NULL,
            descripcion TEXT,
            url VARCHAR(255)
        )");
            
            $this->db->query("CREATE TABLE IF NOT EXISTS tbl_temas (
            id_tema INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255) NOT NULL
        )");
            
            $this->db->query("CREATE TABLE IF NOT EXISTS tbl_frases (
            id_frase INT AUTO_INCREMENT PRIMARY KEY,
            texto TEXT NOT NULL,
            id_autor INT,
            FOREIGN KEY (id_autor) REFERENCES tbl_autores(id_autor) ON DELETE CASCADE
        )");
            
            $this->db->query("CREATE TABLE IF NOT EXISTS tbl_frases_temas (
            id_frase INT,
            id_tema INT,
            PRIMARY KEY (id_frase, id_tema),
            FOREIGN KEY (id_frase) REFERENCES tbl_frases(id_frase) ON DELETE CASCADE,
            FOREIGN KEY (id_tema) REFERENCES tbl_temas(id_tema)
        )");
            
        } catch (Exception $e) {
            throw new Exception("Error al crear las tablas: " . $e->getMessage());
        }
    }
    
    
    
}

