<?php
class AuthModel extends Model
{
    public function getUserByNombre($nombre_usuario)
    {
        $sql = "
            SELECT 
                u.*, 
                r.role_id AS rol_id, 
                r.nombre AS rol_nombre, 
                r.icono AS rol_icono, 
                r.color_badge AS rol_color_badge
            FROM usuarios u
            LEFT JOIN roles r ON u.role_id = r.role_id
            WHERE u.nombre = :nombre
            LIMIT 1
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPermissionsDetalisByRoleId($role_id)
    {
        $sql = "
            SELECT 
                p.*
            FROM role_permisos rp
            INNER JOIN permisos p ON rp.permiso_id = p.permiso_id
            WHERE rp.role_id = :role_id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
