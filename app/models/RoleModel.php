<?php
class RoleModel extends Model
{
    public function getAllRoles()
    {
        $sql = "SELECT * FROM roles";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($role_id)
    {
        $sql = "SELECT * FROM roles
        WHERE role_id = :role_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function createRole($nombre, $descripcion, $icono, $color_badge, $protegido)
    {
        $stmt = $this->db->prepare("
            INSERT INTO roles (nombre, descripcion, icono, color_badge, protegido)
            VALUES (:nombre, :descripcion, :icono, :color_badge, :protegido)
        ");

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':icono', $icono);
        $stmt->bindParam(':color_badge', $color_badge);
        $stmt->bindParam(':protegido', $protegido);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }


    public function assignPermissions($roleId, $permisos = [])
    {
        $stmt = $this->db->prepare("
            INSERT INTO role_permisos (role_id, permiso_id)
            VALUES (:role_id, :permiso_id)
        ");

        foreach ($permisos as $permisoId) {
            $stmt->bindParam(':role_id', $roleId);
            $stmt->bindParam(':permiso_id', $permisoId);
            $stmt->execute();
        }
    }




    public function updateRole($roleId, $nombre, $descripcion, $icono, $color)
    {
        $stmt = $this->db->prepare("
        UPDATE roles 
        SET nombre = :nombre, descripcion = :descripcion, icono = :icono, color_badge = :color 
        WHERE role_id = :role_id
    ");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':icono', $icono);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':role_id', $roleId);
        return $stmt->execute();
    }

    public function clearPermissions($roleId)
    {
        $stmt = $this->db->prepare("DELETE FROM role_permisos WHERE role_id = :role_id");
        $stmt->bindParam(':role_id', $roleId);
        return $stmt->execute();
    }

    public function roleNameExists($nombre, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM roles WHERE nombre = :nombre";
        if ($excludeId !== null) {
            $sql .= " AND role_id != :exclude_id";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        if ($excludeId !== null) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function deleteRoleById($role_id)
    {
        // Elimina los permisos primero (si aplica)
        $this->db->prepare("DELETE FROM role_permisos WHERE role_id = :id")
            ->execute([':id' => $role_id]);

        $stmt = $this->db->prepare("DELETE FROM roles WHERE role_id = :id");
        $stmt->bindParam(':id', $role_id);
        return $stmt->execute();
    }
    public function roleHasUsers($role_id)
{
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE role_id = :role_id");
    $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

}
