<?php
class StatusAttendanceModel extends Model
{
    public function getAllStatus()
    {
        $stmt = $this->db->prepare("SELECT * FROM estados_asistencia");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
