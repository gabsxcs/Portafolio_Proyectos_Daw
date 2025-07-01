<?php

class AuthorModel {
    private $db;
    
    public function __construct() {
        $this->db = PDODatabase::getInstance();
        $result = $this->db->query("SHOW DATABASES LIKE 'frases_Sandoval_Gabriela'");
        
        if ($result && $result->rowCount() > 0) {
            $this->db->query("USE frases_Sandoval_Gabriela");
        }
    }
    
    //Crear autor
    public function createAuthor(Author $author) {
       
        $sql = "INSERT INTO tbl_autores (nombre, descripcion, url)
            VALUES (:nombre, :descripcion, :url)";
        
        $params = [
            ':nombre' => $author->__get("nombre"),
            ':descripcion' => $author->__get("descripcion"),
            ':url' => $author->__get("url")
        ];
        
        $this->db->execute($sql, $params);
        
        return $this->db->lastInsertId(); 
    }
    
    //Obtener autor la id del autor con su nombre
    public function getAuthorIdByName($name) {
        $sql = "SELECT id_autor FROM tbl_autores WHERE nombre = :nombre";
        
        $result = $this->db->query($sql, [':nombre' => $name]);
        
        return $result ? $result[0]['id_autor'] : null;
    }
    
    //obtener el autor con su nombre
    public function getAuthorByName($nombre) {
        $sql = "SELECT * FROM tbl_autores WHERE nombre = :nombre";
        
        $params = [':nombre' => $nombre];
        
        $result = $this->db->query($sql, $params);
        
        return $result ? $result[0] : null; 
    }
    
    
    //Obtener todos los autores
    public function getAllAuthors() {
        $sql = "SELECT id_autor, nombre FROM tbl_autores";
        $result = $this->db->query($sql);
        
        $autores = [];
        foreach ($result as $row) {
            $autores[] = new Author($row['id_autor'], $row['nombre'], '', '', 0);
        }
        
        return $autores;
    }
    
    //Obtener el autor por su id
    public function getAuthorById($id) {
        $sql = "SELECT * FROM tbl_autores WHERE id_autor = ?";
        
        $result = $this->db->query($sql, [$id]);
        
        if (empty($result)) {
            return null; 
        }
        
        $authorData = $result[0]; 
        $numFrases = 0;
        
        $author = new Author(
            $authorData['id_autor'],
            $authorData['nombre'],
            $authorData['descripcion'],
            $authorData['url'],
            $numFrases
            );
        
        return $author;
    }
    
    //actualizar author
    public function updateAuthor(Author $author) {
        $nombre = $author->__get('nombre');
        $id_autor = $author->__get('id_autor');
        $descripcion = $author->__get('descripcion');
        $url = $author->__get('url');
        
        $sql = "UPDATE tbl_autores SET nombre = ?, descripcion = ?, url = ? WHERE id_autor = ?";
        
        $result = $this->db->execute($sql, [$nombre, $descripcion, $url, $id_autor]);
        
        if ($result) {
            return true; 
        } else {
            return false;  
        }
    }
    
    //Borrar author
    public function deleteAuthor($id) {
        $sql = "DELETE FROM tbl_autores WHERE id_autor = ?";
        return $this->db->execute($sql, [(int)$id]);
    }
    
    
    //borrar las frase relacionadas con autor
    public function deleteAllFrasesTemasByAuthor($id) {
        $sql = "SELECT id_frase FROM tbl_frases WHERE id_autor = ?";
        $frases = $this->db->query($sql, [$id]);
        
        var_dump($frases); 
        
        if (!empty($frases)) {
            $idsFrases = array_column($frases, 'id_frase');
            
           
            var_dump($idsFrases); 
            
            if (count($idsFrases) == 1) {
                $sqlDelete = "DELETE FROM tbl_frases_temas WHERE id_frase = ?";
                var_dump($sqlDelete, [$idsFrases[0]]); 
                return $this->db->execute($sqlDelete, [$idsFrases[0]]);
            }
            
            $placeholders = implode(',', array_fill(0, count($idsFrases), '?'));
            $sqlDelete = "DELETE FROM tbl_frases_temas WHERE id_frase IN ($placeholders)";
            
            var_dump($sqlDelete, $idsFrases); 
            
            return $this->db->execute($sqlDelete, $idsFrases);
        }
        
        return false;
    }
    
    //Este es para obtener el numero de frases de un autor
    public function getAuthorAndCountFrases() {
        $sql = "SELECT a.id_autor, a.nombre, a.descripcion, a.url, COUNT(f.id_frase) AS Num
            FROM tbl_autores a
            LEFT JOIN tbl_frases f ON a.id_autor = f.id_autor
            GROUP BY a.id_autor";
        
        $result = $this->db->query($sql);
        
        $authors = [];
        foreach ($result as $row) {
            $authors[] = new Author(
                $row['id_autor'],
                $row['nombre'],
                $row['descripcion'],
                $row['url'],
                $row['Num'] 
                );
        }
        
        return $authors;
    }
    
    //obtener los autores entre unos parametros, es para la paginacion. Ademas hace la busqueda de un autor en concreto
    public function getFilteredAuthors($busqueda, $offset, $limit) {
        $busqueda = "%" . $busqueda . "%"; 
        
        $query = "SELECT a.id_autor, a.nombre, a.descripcion, a.url, COUNT(f.id_frase) AS numFrases
        FROM tbl_autores a
        LEFT JOIN tbl_frases f ON a.id_autor = f.id_autor
        WHERE a.nombre LIKE :search OR a.descripcion LIKE :search
        GROUP BY a.id_autor
        LIMIT $limit OFFSET $offset";
        
        $result = $this->db->query($query, [':search' => $busqueda]);
        $authors = [];
        
        foreach ($result as $row) {
            $author = new Author(
                $row['id_autor'],
                $row['nombre'],
                $row['descripcion'],
                $row['url'],
                $row['numFrases']
                );
            $authors[] = $author;
        }
        
        return $authors;
    }
    
    //Obtiene la cantidad de autores que cumplen con la busqueda, es para hacer paginacion
    public function getTotalAuthors($busqueda = '') {
        $busqueda = "%" . $busqueda . "%"; 
        
        $query = "SELECT COUNT(DISTINCT a.id_autor) AS total
              FROM tbl_autores a
              LEFT JOIN tbl_frases f ON a.id_autor = f.id_autor
              WHERE a.nombre LIKE :search OR a.descripcion LIKE :search";
        
        
        $result = $this->db->query($query, [':search' => $busqueda]);
        
        
        return $result[0]['total'];
    }
    
    
    
    
}

?>
