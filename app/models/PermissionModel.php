<?php
class PermissionModel extends Model
{
    public function getAllPermissions()
    {
        $sql = "SELECT * FROM permisos";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionByRoleId($role_id)
    {
        $sql = "SELECT * FROM role_permisos
        WHERE role_id = :role_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
