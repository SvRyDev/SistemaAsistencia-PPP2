<?php
class AttendanceModel extends Model
{
    public function getAllAttendance()
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiante");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecordByStudent($StudentId)
    {
        $stmt = $this->db->prepare("  SELECT a.*, f.fecha AS fecha_asistencia, f.nombre AS nombre_dia
  FROM asistencia_estudiante a
  INNER JOIN dia_asistencia f ON a.dia_fecha_id = f.dia_fecha_id
  WHERE a.estudiante_id = :estudiante_id");
        $stmt->bindParam(':estudiante_id', $StudentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registerAttendance($data) {}
}
