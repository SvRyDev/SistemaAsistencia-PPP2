<?php
class AttendanceModel extends Model
{
    public function getAllAttendance()
    {
        $stmt = $this->db->prepare("SELECT * FROM asistencia_estudiante");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllAttendanceByDate($dia_fecha_id){
        $sql = "
            SELECT 
                e.codigo,
                e.nombres,
                e.apellidos,
                g.nombre_completo AS grado,
                s.nombre_seccion AS seccion,
                ae.hora_entrada AS hora_actual,
                ea.id_estado,
                ea.nombre_estado,
                -- Mapeo manual de clase Bootstrap (puedes ajustar según tus clases)
                CASE ea.id_estado
                    WHEN 1 THEN 'success'
                    WHEN 2 THEN 'warning'
                    WHEN 3 THEN 'danger'
                    ELSE 'secondary'
                END AS clase_boostrap
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
    



    public function registerAttendance($estudiante_id, $dia_fecha_id, $hora_entrada, $estado_asistencia_id, $observacion = null)
    {
        $stmt = $this->db->prepare("
        INSERT INTO asistencia_estudiante 
        (estudiante_id, dia_fecha_id, hora_entrada, estado_asistencia_id, observacion)
        VALUES 
        (:estudiante_id, :dia_fecha_id, :hora_entrada, :estado_asistencia_id, :observacion)
    ");

        $stmt->bindParam(':estudiante_id', $estudiante_id);
        $stmt->bindParam(':dia_fecha_id', $dia_fecha_id);
        $stmt->bindParam(':hora_entrada', $hora_entrada); // Debe ser formato 'Y-m-d H:i:s'
        $stmt->bindParam(':estado_asistencia_id', $estado_asistencia_id);
        $stmt->bindParam(':observacion', $observacion);

        return $stmt->execute();
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
        $stmt->bindParam(':hora_entrada', $hora_entrada); // formato 'H:i:s'
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
}
