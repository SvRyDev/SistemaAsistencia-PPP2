<?php
class StudentModel extends Model
{
    public function getAllStudents()
    {
        $stmt = $this->db->prepare("SELECT * FROM vista_estudiantes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByDniOrName($query)
    {
        $sql = "SELECT estudiante_id AS id, codigo, nombres, apellidos, dni, grado_nombre, seccion
            FROM vista_estudiantes
            WHERE nombres LIKE :q OR apellidos LIKE :q OR dni LIKE :q
            ORDER BY apellidos ASC, nombres ASC
            LIMIT 10";

        $stmt = $this->db->prepare($sql);
        $likeQuery = '%' . $query . '%';
        $stmt->bindParam(':q', $likeQuery, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getStudentByCode($codigo)
    {
        $stmt = $this->db->prepare("SELECT * FROM vista_estudiantes WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getStudentByDNI($dni)
    {
        $stmt = $this->db->prepare("SELECT * FROM vista_estudiantes WHERE dni = :dni");
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function getTotalStudents()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM estudiante");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMultipleStudents($alumnos)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO estudiante (dni, nombres, apellidos, grado, seccion) VALUES (?, ?, ?, ?, ?)");

            foreach ($alumnos as $alumno) {
                $stmt->execute([
                    $alumno['dni'],
                    $alumno['nombres'],
                    $alumno['apellidos'],
                    $alumno['grado'],
                    $alumno['seccion']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }


    public function getStudentsByGradeAndSection($gradoId, $seccionId)
    {
        $stmt = $this->db->prepare("SELECT * FROM vista_estudiantes WHERE id_grado = :grado_id AND id_seccion = :seccion_id");
        $stmt->bindParam(':grado_id', $gradoId, PDO::PARAM_INT);
        $stmt->bindParam(':seccion_id', $seccionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStudentByGrade()
    {
        $stmt = $this->db->prepare("
        SELECT
            id_grado,
            grado_nombre AS Grado,
            seccion AS Sección,
            COUNT(estudiante_id) AS Total_Estudiantes
            FROM
            vista_estudiantes
            GROUP BY
            id_grado,
            grado_nombre,
            id_seccion,
            seccion
            ORDER BY
            id_grado,
            id_seccion;
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
};
