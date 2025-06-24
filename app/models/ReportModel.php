<?php

class ReportModel extends Model
{
    public function getRecordByStudent($StudentId, $mes)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.asistencia_estudiante_id,
                a.hora_entrada,
                a.hora_salida,
                a.observacion,
                
                f.dia_fecha_id,
                f.fecha AS fecha_asistencia,
                f.nombre AS nombre_dia,
                
                ea.id_estado,
                ea.nombre_estado AS estado_asistencia,
                ea.abreviatura,
                ea.color_hex
                
            FROM asistencia_estudiante a
            INNER JOIN dia_asistencia f ON a.dia_fecha_id = f.dia_fecha_id
            LEFT JOIN estados_asistencia ea ON a.estado_asistencia_id = ea.id_estado
            WHERE a.estudiante_id = :estudiante_id 
              AND MONTH(f.fecha) = :mes
            ORDER BY f.fecha DESC
        ");

        $stmt->bindParam(':estudiante_id', $StudentId, PDO::PARAM_INT);
        $stmt->bindParam(':mes', $mes, PDO::PARAM_INT); // mes = 1 a 12
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getRecordByGroup($gradoId, $seccionId, $mes)
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.estudiante_id,
                e.codigo,
                CONCAT(e.nombres, ' ', e.apellidos) AS nombre_estudiante,
                g.nombre_completo,
                s.nombre_seccion,
                
                a.asistencia_estudiante_id,
                a.hora_entrada,
                a.hora_salida,
                a.observacion,
                
                f.dia_fecha_id,
                f.fecha AS fecha_asistencia,
                f.nombre AS nombre_dia,
                
                ea.id_estado,
                ea.nombre_estado AS estado_asistencia,
                ea.abreviatura,
                ea.color_hex
    
            FROM asistencia_estudiante a
            INNER JOIN estudiante e ON e.estudiante_id = a.estudiante_id
            LEFT JOIN dia_asistencia f ON f.dia_fecha_id = a.dia_fecha_id
            LEFT JOIN estados_asistencia ea ON ea.id_estado = a.estado_asistencia_id
            LEFT JOIN grados g ON g.id_grado = e.grado_id
            LEFT JOIN secciones s ON s.id_seccion = e.seccion_id
    
            WHERE e.grado_id = :grado_id
            AND e.seccion_id = :seccion_id
            AND MONTH(f.fecha) = :mes
            ORDER BY e.apellidos ASC, f.fecha ASC
        ");
    
        $stmt->bindParam(':grado_id', $gradoId, PDO::PARAM_INT);
        $stmt->bindParam(':seccion_id', $seccionId, PDO::PARAM_INT);
        $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    

}
