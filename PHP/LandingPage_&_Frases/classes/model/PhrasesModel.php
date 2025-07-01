<?php

class PhrasesModel {
    private $db;
    
    public function __construct() {
        $this->db = PDODatabase::getInstance();
        $result = $this->db->query("SHOW DATABASES LIKE 'frases_Sandoval_Gabriela'");
        
        if ($result && $result->rowCount() > 0) {
            $this->db->query("USE frases_Sandoval_Gabriela");
        }
    }
    
    
    //obtener frase con su id
    public function getPhraseById($id) {
        $sql = "SELECT f.id_frase, f.texto, f.id_autor, a.nombre AS autor,
               GROUP_CONCAT(t.id_tema SEPARATOR ', ') AS temas
            FROM tbl_frases f
            LEFT JOIN tbl_autores a ON f.id_autor = a.id_autor
            LEFT JOIN tbl_frases_temas ft ON f.id_frase = ft.id_frase
            LEFT JOIN tbl_temas t ON ft.id_tema = t.id_tema
            WHERE f.id_frase = ?
            GROUP BY f.id_frase, f.texto, f.id_autor, a.nombre";
        
        $result = $this->db->query($sql, [$id]);
        
        if (!empty($result)) {
            $row = $result[0]; 
            
           
            return new Phrase(
                $row['id_frase'],
                $row['texto'],
                $row['id_autor'],
                $row['autor'],
                $row['temas'] 
                );
        }
        
        return null; 
    }
    
    //crear frase, usado en el form
    public function createPhrase(Phrase $phrase) {
        $texto = $phrase->__get('texto');
        $id_autor = $phrase->__get('id_autor');
        $id_tema = $phrase->__get('temas');
        
        if (empty($id_tema)) {
            throw new Exception("El id_tema no puede ser nulo o vacío.");
        }
        
        $sql = "INSERT INTO tbl_frases (texto, id_autor) VALUES (?, ?)";
        $this->db->execute($sql, [$texto, $id_autor]);
        
        $id_frase = $this->db->lastInsertId();
        
        if (!is_numeric($id_tema)) {
            throw new Exception("El id_tema debe ser un número válido.");
        }
        
        $sqlTema = "INSERT INTO tbl_frases_temas (id_frase, id_tema) VALUES (?, ?)";
        $this->db->execute($sqlTema, [$id_frase, (int)$id_tema]);
        
        return true; 
    }
    
    //Actualizar frase
    public function updatePhrase(Phrase $phrase) {
        $texto = $phrase->__get('texto');
        $id_autor = $phrase->__get('id_autor');
        $id_frase = $phrase->__get('id_frase'); 
        
        $sql = "UPDATE tbl_frases SET texto = ?, id_autor = ? WHERE id_frase = ?";
        
        $result = $this->db->execute($sql, [$texto, $id_autor, $id_frase]);
        
        
        if ($result) {
            $id_tema = $phrase->__get('temas');
            
            if (!empty($id_tema)) {
                $sqlTema = "UPDATE tbl_frases_temas SET id_tema = ? WHERE id_frase = ?";
                $temaActualizado = $this->db->execute($sqlTema, [$id_tema, $id_frase]);
                
                if ($temaActualizado) {
                    return true;
                }
            }
            
            return true;
        }
        return false;
        
    }
    
    //Para borar frase y su relacion en tbl_frases_temas
    public function deletePhrase($id) {
        $sql1 = "DELETE FROM tbl_frases_temas WHERE id_frase = ?";
        $this->db->execute($sql1, [(int)$id]);
        
        $sql2 = "DELETE FROM tbl_frases WHERE id_frase = ?";
        return $this->db->execute($sql2, [(int)$id]);
    }
    
    
    //Obtener todas las frases que hayan
    public function getAllPhrases() {
        $sql = "SELECT f.id_frase, f.texto, f.id_autor, a.nombre AS autor,
                   GROUP_CONCAT(t.nombre SEPARATOR ', ') AS temas
            FROM tbl_frases f
            LEFT JOIN tbl_autores a ON f.id_autor = a.id_autor
            LEFT JOIN tbl_frases_temas ft ON f.id_frase = ft.id_frase
            LEFT JOIN tbl_temas t ON ft.id_tema = t.id_tema
            GROUP BY f.id_frase, f.texto, f.id_autor, a.nombre";
        
        $result = $this->db->query($sql);
        
        $phrases = [];
        foreach ($result as $row) {
            $phrases[] = new Phrase(
                $row['id_frase'],
                $row['texto'],
                $row['id_autor'],
                $row['autor'],
                $row['temas']
                );
        }
        
        return $phrases;
    }
    
    //Obtiene frases dentro de unos parametros, este lo hice para la paginacion. Ya no lo uso porque ahora uso el de filtrar
    public function getPhrasesWithLimit($offset, $limit) {
        $offset = (int)$offset;
        $limit = (int)$limit;
        
        $query = "SELECT f.id_frase, f.texto, f.id_autor,
                   COALESCE(a.nombre, 'Desconocido') AS autor,
                   COALESCE(GROUP_CONCAT(DISTINCT t.nombre ORDER BY t.nombre SEPARATOR ', '), 'Sin tema') AS temas
            FROM tbl_frases f
            LEFT JOIN tbl_autores a ON f.id_autor = a.id_autor
            LEFT JOIN tbl_frases_temas ft ON f.id_frase = ft.id_frase
            LEFT JOIN tbl_temas t ON ft.id_tema = t.id_tema
            GROUP BY f.id_frase, f.texto, f.id_autor, a.nombre
            LIMIT $limit OFFSET $offset
            ";
        
        
        $result = $this->db->query($query);
        
        
        $phrases = [];
        
        foreach ($result as $row) {
            $phrase = new Phrase(
                $row['id_frase'],    
                $row['texto'],         
                $row['id_autor'],     
                $row['autor'],      
                $row['temas']        
                );
            $phrases[] = $phrase;
        }
        
        return $phrases;
    }
    
    //Obtener el numero total de frases, para que el controller haga el calculo para la paginacion de la tabla
    public function getTotalPhrases($busqueda = '') {
        $busqueda = "%" . $busqueda . "%"; 
        
        $query = "SELECT COUNT(DISTINCT p.id_frase) AS total
              FROM tbl_frases p
              LEFT JOIN tbl_autores a ON p.id_autor = a.id_autor
              LEFT JOIN tbl_frases_temas ft ON p.id_frase = ft.id_frase
              LEFT JOIN tbl_temas t ON ft.id_tema = t.id_tema
              WHERE p.texto LIKE :search OR a.nombre LIKE :search OR t.nombre LIKE :search";
        
        $result = $this->db->query($query, [':search' => $busqueda]);
        
        return $result[0]['total'];
    }
    
    
    //inserta frase de xml
    public function insertPhrase(Phrase $phrase) {
        $sql = "INSERT INTO tbl_frases (texto, id_autor)
            VALUES (:texto, :id_autor)";
        
        $params = [
            ':texto' => $phrase->__get("texto"),
            ':id_autor' => $phrase->__get("id_autor")
        ];
        
        $this->db->execute($sql, $params);
        
        return $this->db->lastInsertId();
    }
    
    
    //Metodo que me regresa las frases que cumplen con un criterio de busqueda y de paramentros para la paginacion
    public function getFilteredPhrases($search, $offset, $limit) {
       
        $offset = (int)$offset;
        $limit = (int)$limit;
        
       
        $searchTerm = "%" . $search . "%"; 
        
        $query = "SELECT f.id_frase, f.texto, f.id_autor,
                     COALESCE(a.nombre, 'Desconocido') AS autor,
                     COALESCE(GROUP_CONCAT(DISTINCT t.nombre ORDER BY t.nombre SEPARATOR ', '), 'Sin tema') AS temas
              FROM tbl_frases f
              LEFT JOIN tbl_autores a ON f.id_autor = a.id_autor
              LEFT JOIN tbl_frases_temas ft ON f.id_frase = ft.id_frase
              LEFT JOIN tbl_temas t ON ft.id_tema = t.id_tema
              WHERE f.texto LIKE :search
                 OR a.nombre LIKE :search
                 OR t.nombre LIKE :search
              GROUP BY f.id_frase, f.texto, f.id_autor, a.nombre
              LIMIT $limit OFFSET $offset"; 
        
 
        $params = [
            ':search' => $searchTerm  
        ];
        
        $result = $this->db->query($query, $params);
        
        $phrases = [];
        
        foreach ($result as $row) {
            $phrase = new Phrase(
                $row['id_frase'],
                $row['texto'],
                $row['id_autor'],
                $row['autor'],
                $row['temas']
                );
            $phrases[] = $phrase;
        }
        
        return $phrases;
    }
    
    //Obtener las frases de un autor
    public function getPhrasesByAuthorId($idAutor) {
        $query = "SELECT p.id_frase, p.texto, GROUP_CONCAT(t.nombre SEPARATOR ', ') AS temas
              FROM tbl_frases p
              LEFT JOIN tbl_frases_temas ft ON p.id_frase = ft.id_frase
              LEFT JOIN tbl_temas t ON ft.id_tema = t.id_tema
              WHERE p.id_autor = :id
              GROUP BY p.id_frase";
        
        $result = $this->db->query($query, [':id' => $idAutor]);
        
        
        
        $phrases = [];
        foreach ($result as $row) {
            $phrases[] = new Phrase($row['id_frase'], $row['texto'], $idAutor, "", $row['temas']);
        }
        
        return $phrases;
    }
    
    //Obtener frases con el id de un tema
    public function getPhrasesByThemeId($id_tema) {
        $sql = "SELECT f.id_frase, f.texto, f.id_autor, a.nombre AS autor
            FROM tbl_frases f
            JOIN tbl_frases_temas ft ON f.id_frase = ft.id_frase
            LEFT JOIN tbl_autores a ON f.id_autor = a.id_autor
            WHERE ft.id_tema = ?";
        
        $result = $this->db->query($sql, [$id_tema]);
        
        $phrases = [];
        foreach ($result as $row) {
            $phrases[] = new Phrase(
                $row["id_frase"],
                $row["texto"],
                $row["id_autor"],
                $row["autor"] ?? "Autor desconocido",
                "" 
                );
        }
        
        return $phrases;
    }
    
    
    
}
?>
