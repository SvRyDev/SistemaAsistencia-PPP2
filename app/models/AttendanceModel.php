<?php
class AttendanceModel extends Model
{
    public function getAllAttendance()
    {
        $stmt = $this->db->prepare("SELECT * FROM asistencia_estudiante");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAttendanceByFilter($dia_id, $grado = null, $seccion = null)
    {
        // Construir base del SQL
        $sql = "
            SELECT 
                a.asistencia_estudiante_id,
                a.observacion,
                a.hora_entrada,
                d.fecha,
                d.nombre_dia,
                ve.nombres,
                ve.codigo,
                ve.apellidos,
                ve.grado_nombre AS grado,
                ve.seccion,
                e.nombre_estado,
                e.abreviatura,
                e.clase_boostrap,
                e.color_hex,
                e.icon
            FROM asistencia_estudiante a
            JOIN dia_asistencia d ON a.dia_fecha_id = d.dia_fecha_id
            JOIN estados_asistencia e ON a.estado_asistencia_id = e.id_estado
            JOIN vista_estudiantes ve ON a.estudiante_id = ve.estudiante_id
            WHERE d.dia_fecha_id = :dia_id
            ORDER BY a.asistencia_estudiante_id ASC
        ";
    
        // Agregar filtros dinámicos
        if (!is_null($grado)) {
            $sql .= " AND ve.id_grado = :grado";
        }
    
        if (!is_null($seccion)) {
            $sql .= " AND ve.id_seccion = :seccion";
        }
    
        // Ahora preparar el SQL ya construido
        $stmt = $this->db->prepare($sql);
    
        // Asignar valores
        $stmt->bindValue(':dia_id', $dia_id, PDO::PARAM_INT);
    
        if (!is_null($grado)) {
            $stmt->bindValue(':grado', $grado, PDO::PARAM_INT);
        }
    
        if (!is_null($seccion)) {
            $stmt->bindValue(':seccion', $seccion, PDO::PARAM_INT);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLastAttendanceByStudent($studentId, $limit = 15)
    {
        $stmt = $this->db->prepare("
            SELECT 
                d.fecha,
                d.nombre_dia,
                e.nombre_estado,
                e.abreviatura,
                e.clase_boostrap,
                e.color_hex,
                e.icon

            FROM asistencia_estudiante a
            JOIN dia_asistencia d ON a.dia_fecha_id = d.dia_fecha_id
            JOIN estados_asistencia e ON a.estado_asistencia_id = e.id_estado
            WHERE a.estudiante_id = :estudiante_id
            ORDER BY a.asistencia_estudiante_id ASC
            LIMIT :limit
        ");

        $stmt->bindValue(':estudiante_id', $studentId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllAttendanceByDate($dia_fecha_id)
    {
        $sql = "
            SELECT 
                e.codigo,
                e.nombres,
                e.apellidos,
                g.nombre_completo AS grado,
                g.orden_num AS grado_orden,
                s.nombre_seccion AS seccion,

                ae.hora_entrada AS hora_actual,
                ea.id_estado,
                ea.nombre_estado,
                ea.clase_boostrap
            FROM asistencia_estudiante ae
            INNER JOIN estudiante e ON ae.estudiante_id = e.estudiante_id
            LEFT JOIN grados g ON e.grado_id = g.id_grado
            LEFT JOIN secciones s ON e.seccion_id = s.id_seccion
            LEFT JOIN estados_asistencia ea ON ae.estado_asistencia_id = ea.id_estado
            WHERE ae.dia_fecha_id = :dia_fecha_id
            ORDER BY ae.hora_entrada ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':dia_fecha_id', $dia_fecha_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIDStudentByDate($dia_fecha_id)
{
    $stmt = $this->db->prepare("
        SELECT estudiante_id 
        FROM asistencia_estudiante 
        WHERE dia_fecha_id = :dia_fecha_id
    ");
    $stmt->bindParam(':dia_fecha_id', $dia_fecha_id, PDO::PARAM_INT);
    $stmt->execute();

    // Retorna solo los IDs en un array plano
    return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'estudiante_id');
}


    public function getRegisteredByStudentAndDate($estudianteId, $diaFechaId)
    {
        $sql = "SELECT *
            FROM asistencia_estudiante
            WHERE estudiante_id = :estudianteId AND dia_fecha_id = :diaFechaId
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estudianteId', $estudianteId);
        $stmt->bindParam(':diaFechaId', $diaFechaId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve null si no hay registro
    }

    public function createAttendance($estudiante_id, $dia_fecha_id, $hora_entrada, $estado_asistencia_id, $observacion = null)
    {
        $sql = "INSERT INTO asistencia_estudiante 
        (estudiante_id, dia_fecha_id, hora_entrada, estado_asistencia_id, observacion)
        VALUES 
        (:estudiante_id, :dia_fecha_id, :hora_entrada, :estado_asistencia_id, :observacion)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
        $stmt->bindParam(':dia_fecha_id', $dia_fecha_id, PDO::PARAM_INT);
        $stmt->bindParam(':hora_entrada', $hora_entrada);
        $stmt->bindParam(':estado_asistencia_id', $estado_asistencia_id, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion);

        return $stmt->execute();
    }

    public function updateAttendance($estudiante_id, $dia_fecha_id, $hora_entrada, $estado_asistencia_id, $observacion = null)
    {
        $sql = "UPDATE asistencia_estudiante 
            SET hora_entrada = :hora_entrada,
                estado_asistencia_id = :estado_asistencia_id,
                observacion = :observacion
            WHERE estudiante_id = :estudiante_id AND dia_fecha_id = :dia_fecha_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
        $stmt->bindParam(':dia_fecha_id', $dia_fecha_id, PDO::PARAM_INT);
        $stmt->bindParam(':hora_entrada', $hora_entrada);
        $stmt->bindParam(':estado_asistencia_id', $estado_asistencia_id, PDO::PARAM_INT);
        $stmt->bindParam(':observacion', $observacion);

        return $stmt->execute();
    }

    public function registerNewDay($fecha, $name_day, $entry_time, $exit_time, $tolerance)
    {
        $stmt = $this->db->prepare("INSERT INTO dia_asistencia (fecha, nombre_dia, hora_entrada, hora_salida, min_tolerancia)
        VALUES
        (
        :fecha,
        :nombre_dia,
        :hora_entrada,
        :hora_salida,
        :min_tolerancia
        )");

        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':nombre_dia', $name_day);
        $stmt->bindParam(':hora_entrada', $entry_time);
        $stmt->bindParam(':hora_salida', $exit_time);
        $stmt->bindParam(':min_tolerancia', $tolerance);

        return $stmt->execute();
    }


    public function deleteByEstudianteId($id)
    {
        $stmt = $this->db->prepare("DELETE FROM asistencia_estudiante WHERE estudiante_id = :id");
        return $stmt->execute([':id' => $id]);
    }

}
