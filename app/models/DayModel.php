<?php
class DayModel extends Model
{
    public function getAllDays()
    {
        $stmt = $this->db->prepare("SELECT * FROM dia_asistencia");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function validDayActive($date){
        $stmt = $this->db->prepare("SELECT * FROM dia_asistencia WHERE fecha = :date");
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




}
