<?php
class DayModel extends Model
{
    public function getAllDays()
    {
        $stmt = $this->db->prepare("SELECT * FROM dia_asistencia");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
