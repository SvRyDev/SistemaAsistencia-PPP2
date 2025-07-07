<?php
class StudentModel extends Model
{
    public function getAllStudents()
    {
        $stmt = $this->db->prepare("SELECT * FROM vista_estudiantes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiante WHERE estudiante_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getViewStudentById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM vista_estudiantes WHERE estudiante_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function addMultipleStudents($alumnos, $academicYear)
    {
        try {
            $this->db->beginTransaction();

            $insertStmt = $this->db->prepare("
                INSERT INTO estudiante (
                    nombres, apellidos, dni, grado_id, seccion_id, date_created
                ) VALUES (?, ?, ?, ?, ?, NOW())
            ");

            $updateStmt = $this->db->prepare("
                UPDATE estudiante SET codigo = ? WHERE estudiante_id = ?
            ");

            foreach ($alumnos as $alumno) {
                $gradoNombre = strtoupper(strtolower(trim($alumno['grado'])));
                $seccionNombre = strtoupper(trim($alumno['seccion']));

                $gradoId = $this->getGradoIdByNombre($gradoNombre);
                $seccionId = $this->getSeccionIdByNombre($seccionNombre);

                if (!$gradoId || !$seccionId) {
                    throw new Exception("Grado o sección no válidos para: {$alumno['nombres']} {$alumno['apellidos']}");
                }

                // ✅ Verificar si el DNI ya existe
                if ($this->dniExists($alumno['dni'])) {
                    throw new Exception("El DNI '{$alumno['dni']}' ya existe para el alumno: {$alumno['nombres']} {$alumno['apellidos']}");
                }

                $insertStmt->execute([
                    $alumno['nombres'],
                    $alumno['apellidos'],
                    $alumno['dni'],
                    $gradoId,
                    $seccionId
                ]);

                $estudianteId = $this->db->lastInsertId();
                $codigo = $this->generarCodigoEstudiante($estudianteId, $academicYear);
                $updateStmt->execute([$codigo, $estudianteId]);
            }

            $this->db->commit();
            return ["success" => true];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return [
                "success" => false,
                "error" => $e->getMessage()
            ];
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
    
    public function createStudent($nombres, $apellidos, $dni, $grado_id, $seccion_id, $academic_year)
    {
        // Paso 1: Insertar sin código
        $stmt = $this->db->prepare("
            INSERT INTO estudiante (
                nombres, apellidos, dni, grado_id, seccion_id, date_created
            ) VALUES (
                :nombres, :apellidos, :dni, :grado_id, :seccion_id, NOW()
            )
        ");

        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':grado_id', $grado_id);
        $stmt->bindParam(':seccion_id', $seccion_id);

        if ($stmt->execute()) {
            $estudiante_id = $this->db->lastInsertId();

            // Usar la función de generación
            $codigo = $this->generarCodigoEstudiante($estudiante_id, $academic_year);

            $update = $this->db->prepare("UPDATE estudiante SET codigo = :codigo WHERE estudiante_id = :id");
            $update->bindParam(':codigo', $codigo);
            $update->bindParam(':id', $estudiante_id);
            $update->execute();

            return $estudiante_id;
        }

        return false;
    }

    public function updateStudent($id, $dni, $nombres, $apellidos, $grado_id, $seccion_id)
    {
        $stmt = $this->db->prepare("
        UPDATE estudiante SET
            dni = :dni,
            nombres = :nombres,
            apellidos = :apellidos,
            grado_id = :grado_id,
            seccion_id = :seccion_id,
            date_update = NOW()
        WHERE estudiante_id = :id
    ");

        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':grado_id', $grado_id);
        $stmt->bindParam(':seccion_id', $seccion_id);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }



    public function deleteById($id)
    {
        $stmt = $this->db->prepare("DELETE FROM estudiante WHERE estudiante_id = :id");
        return $stmt->execute([':id' => $id]);
    }


    // Validación de DNI duplicado
    public function dniExists($dni, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM estudiante WHERE dni = :dni";
        if ($excludeId !== null) {
            $sql .= " AND estudiante_id != :excludeId";
        }
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        if ($excludeId !== null) {
            $stmt->bindParam(':excludeId', $excludeId, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    


    //---------------------------------------------------
    //FUNCIONES COMPLEMENTARIA PARA PROCESOS DEl MODELO -
    //---------------------------------------------------

    private function getGradoIdByNombre($nombre)
    {
        $stmt = $this->db->prepare("SELECT id_grado FROM grados WHERE nombre_completo = ?");
        $stmt->execute([$nombre]);
        $id = $stmt->fetchColumn();
        return $id ?: null;
    }

    private function getSeccionIdByNombre($nombre)
    {
        $stmt = $this->db->prepare("SELECT id_seccion FROM secciones WHERE nombre_seccion = ?");
        $stmt->execute([$nombre]);
        $id = $stmt->fetchColumn();
        return $id ?: null;
    }
    private function generarCodigoEstudiante($estudianteId, $academicYear)
    {
        $anio = substr($academicYear, -2); // "2025" → "25"
        return sprintf("STU-%s-%04d", $anio, $estudianteId);
    }

}
;
