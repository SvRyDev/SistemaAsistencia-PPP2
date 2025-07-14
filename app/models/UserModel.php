<?php
class UserModel extends Model
{
    public function getAllUsers()
    {
        $sql = "SELECT u.user_id, u.nombre, u.email, r.nombre AS nombre_rol, r.color_badge AS color_rol, u.estatus
                FROM usuarios u
                LEFT JOIN roles r ON u.role_id = r.role_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserById($user_id)
    {
        $sql = "SELECT * FROM usuarios
                WHERE user_id = :user_id
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function usernameExists($nombre_usuario)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre_usuario);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function usernameExistsForOther($nombre, $exclude_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre AND user_id != :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id', $exclude_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    public function createUser($nombre, $password, $role_id, $estatus)
    {
        $stmt = $this->db->prepare("
        INSERT INTO usuarios (nombre, password, role_id, estatus)
        VALUES (:nombre, :password, :role_id, :estatus)
    ");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':estatus', $estatus);
        return $stmt->execute();
    }


    public function updateUser($id, $nombre, $hashedPassword, $role_id, $estatus)
    {
        if ($hashedPassword) {
            $stmt = $this->db->prepare("
                UPDATE usuarios 
                SET nombre = :nombre, password = :password, role_id = :role_id, estatus = :estatus
                WHERE user_id = :id
            ");
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            $stmt = $this->db->prepare("
                UPDATE usuarios 
                SET nombre = :nombre, role_id = :role_id, estatus = :estatus
                WHERE user_id = :id
            ");
        }

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':estatus', $estatus);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function updateUserPassword($id, $hashedPassword)
    {
        $stmt = $this->db->prepare("
            UPDATE usuarios 
            SET password = :password
            WHERE user_id = :id
        ");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    public function deleteUserById($id)
{
    $stmt = $this->db->prepare("DELETE FROM usuarios WHERE user_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}


}
