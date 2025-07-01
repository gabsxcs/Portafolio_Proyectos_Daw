<?php

class EventoModel{
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($titulo, $descripcion, $fechaHoraInicio, $fechaHoraFin, $etiquetas) {
        $etiquetas = empty($etiquetas) ? null : $etiquetas;
        $sql = "INSERT INTO Agenda (titulo, descripcion, fechaHoraInicio, fechaHoraFin, etiqueta) VALUES (?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [$titulo, $descripcion, $fechaHoraInicio, $fechaHoraFin, $etiquetas]);
    }
    
    
    public function read($id) {
        $sql = "SELECT * FROM Agenda WHERE id = ?";
        $result = $this->db->query($sql, [$id]);
        return $result->fetch_assoc();
    }
    
    public function readAll() {
        $sql = "SELECT * FROM Agenda";
        $result = $this->db->query($sql);
        $eventos = [];
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
        return $eventos;
    }
    
    public function update($id, $titulo, $descripcion, $fechaHoraInicio, $fechaHoraFin, $etiquetas) {
        $etiquetas = empty($etiquetas) ? null : $etiquetas;
        $sql = "UPDATE Agenda SET titulo = ?, descripcion = ?, fechaHoraInicio = ?, fechaHoraFin = ?, etiqueta = ? WHERE id = ?";
        return $this->db->execute($sql, [$titulo, $descripcion, $fechaHoraInicio, $fechaHoraFin, $etiquetas, $id]);
    }
     
    public function delete($id) {
        $id = (int) $id;  
        $sql = "DELETE FROM Agenda WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    
    //esto hace que se muestren los eventos por orden de fecha.
    public function getAll() {
        $sql = "SELECT * FROM Agenda ORDER BY fechaHoraInicio ASC";
        $eventos = $this->db->query($sql);
        
        if (!is_array($eventos)) {
            return []; 
        }
        
        return $eventos;
    }
    
    public function getOneById($id) {
        $sql = "SELECT * FROM Agenda WHERE id = ?";
        $result = $this->db->query($sql, [$id]);
        
        if (!$result || !is_array($result) || empty($result)) {
            return null; 
        }
        
        return $result[0]; 
    }
    
    
    
    public function getBetweenDates($fechaInicio, $fechaFin) {
        $sql = "SELECT * FROM Agenda WHERE fechaHoraInicio >= ? AND fechaHoraFin <= ?";
        return $this->db->query($sql, [$fechaInicio, $fechaFin]); 
    }
    
    //BUscar un evento con el titulo o con la etiqueta
    public function buscar($query) {
        $sql = "SELECT * FROM Agenda WHERE titulo LIKE ? OR etiqueta LIKE ? ORDER BY fechaHoraInicio ASC";
        $aBuscar= $query ;
        return $this->db->query($sql, [$aBuscar, $aBuscar]);
    }
    
    
    
}


?>