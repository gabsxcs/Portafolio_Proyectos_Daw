<?php

class ThemesModel {
    private $db;
    
    public function __construct() {
        $this->db = PDODatabase::getInstance();
        $result = $this->db->query("SHOW DATABASES LIKE 'frases_Sandoval_Gabriela'");
        
        if ($result && $result->rowCount() > 0) { 
            $this->db->query("USE frases_Sandoval_Gabriela");
        }
    }
    
    
    //obtener el tema por su nombre
    public function getThemeByName($nombre) {
        $sql = "SELECT * FROM tbl_temas WHERE nombre = :nombre";
        
        $params = [':nombre' => $nombre];
        
        $result = $this->db->query($sql, $params);
        
        return $result ? $result[0] : null;
    }
    
    
    // Obtener un tema por su ID
    public function getThemeById($id_tema) {
        $sql = "SELECT * FROM tbl_temas WHERE id_tema = ?";
        
        $result = $this->db->query($sql, [$id_tema]);
        
        if (!empty($result)) {
            $themeData = $result[0]; 
            return new Theme($themeData["nombre"], $themeData["id_tema"]);
        } else {
            return null;
        }
    }
    
    
    ///insertar un tema
    public function createTheme(Theme $theme) {
        $nombre = $theme->__get("nom");
        
        if (empty($nombre)) {
            throw new Exception("El nombre del tema no puede estar vacío.");
        }
        
        $sql = "INSERT INTO tbl_temas (nombre) VALUES (:nombre)";
        
        $params = [':nombre' => $nombre];
        
        $this->db->execute($sql, $params);
        
        // Esto obtiene el último ID insertado para ingresar el tema
        return $this->db->lastInsertId();
    }
    
    
    //Elimina el tema junto con sus relaciones en tbl_frases_temas
    public function deleteTheme($id) {
        $sqlDeleteRelations = "DELETE FROM tbl_frases_temas WHERE id_tema = ?";
        $this->db->execute($sqlDeleteRelations, [(int)$id]);
        
        $sqlDeleteTheme = "DELETE FROM tbl_temas WHERE id_tema = ?";
        return $this->db->execute($sqlDeleteTheme, [(int)$id]);
    }
    
    
    // Obtener todos los temas con la cantidad de frases que tiene cada uno
    public function getThemesWithPhraseCount() {
        $sql = "SELECT t.id_tema, t.nombre, COUNT(ft.id_frase) AS Num
            FROM tbl_temas t
            LEFT JOIN tbl_frases_temas ft ON t.id_tema = ft.id_tema
            GROUP BY t.id_tema, t.nombre";
        
        $result = $this->db->query($sql);
        
        $themes = [];
        foreach ($result as $row) {
            $themes[] = new Theme(
                $row['nombre'],
                $row['id_tema'],
                $row['Num'] 
                );
        }
        
        return $themes;
    }
    
    //Obtiene todos los temas
    public function getAllThemes() {
        $sql = "SELECT id_tema, nombre FROM tbl_temas";
        $result = $this->db->query($sql);
        
        $temas = [];
        foreach ($result as $row) {
            $temas[] = new Theme($row['nombre'], $row['id_tema']);
        }
        
        return $temas;
    }
    
    //Actualiza un tema
    public function updateTheme(Theme $theme) {
        $nombre = $theme->__get('nom');
        $id_tema = $theme->__get('id_tema');
        
        $sql = "UPDATE tbl_temas SET nombre = ? WHERE id_tema = ?";
        
        return $this->db->execute($sql, [$nombre, $id_tema]);
    }
    
   
    
    //Obtiene los temas dentro de unos paramentros, esto es para la paginacion. Ya no la uso porue ahora uso una que ademas filtra
    public function getThemesWithLimit($offset, $limit) {
        $offset = (int)$offset;
        $limit = (int)$limit;
        
        $query = "SELECT t.id_tema, t.nombre AS nom, COUNT(ft.id_frase) AS numFrases
            FROM tbl_temas t
            LEFT JOIN tbl_frases_temas ft ON t.id_tema = ft.id_tema
            GROUP BY t.id_tema
            LIMIT $limit OFFSET $offset
        ";
            
        $result = $this->db->query($query);
        $themes = [];
        
        foreach ($result as $row) {
            $themes[] = new Theme($row['nom'], $row['id_tema'], $row['numFrases']);
        }
        
        return $themes;
    }
    
    //Obtiene los temas dentro de unos parametros para la paginacion, y si hay criterio de busqueda tambien los obtiene con esos criterios
    public function getThemesWithLimitAndSearch($busqueda, $offset, $limit) {
        $busqueda = "%" . $busqueda . "%";
        
        $query = "SELECT t.id_tema, t.nombre AS nom, COUNT(ft.id_frase) AS numFrases
              FROM tbl_temas t
              LEFT JOIN tbl_frases_temas ft ON t.id_tema = ft.id_tema
              WHERE t.nombre LIKE :search
              GROUP BY t.id_tema
              LIMIT $limit OFFSET $offset";
        
        $result = $this->db->query($query, [':search' => $busqueda]);
        
        $themes = [];
        foreach ($result as $row) {
            $themes[] = new Theme($row['nom'], $row['id_tema'], $row['numFrases']);
        }
        
        return $themes;
    }
    
    
    //Obtiene la cantidad total de temas para que el controller haga la operacion para hacer la paginacion
    public function getTotalThemes($busqueda = '') {
        $busqueda = "%" . $busqueda . "%"; 
        
        $query = "SELECT COUNT(DISTINCT t.id_tema) AS total
              FROM tbl_temas t
              LEFT JOIN tbl_frases_temas ft ON t.id_tema = ft.id_tema
              WHERE t.nombre LIKE :search";
        
        
        $result = $this->db->query($query, [':search' => $busqueda]);
        
        
        return $result[0]['total'];
    }
    
    
    
    
    
    
    
}
?>
