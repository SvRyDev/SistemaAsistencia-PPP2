<?php
class AuthModel extends Model
{
    public function getUserByNombre($nombre_usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
