<?php
class AttendanceModel extends Model
{
    public function getAllAttendance()
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiante");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecordByStudent($StudentId) {
        $stmt = $this->db->prepare("SELECT * FROM asistencia_estudiante WHERE estudiante_id = :estudiante_id");
        $stmt->bindParam(':estudiante_id', $StudentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registerAttendance($data)
    {

    }
}
