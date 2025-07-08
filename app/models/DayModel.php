<?php
class DayModel extends Model
{
    public function getAllDays()
    {
        $stmt = $this->db->prepare("SELECT * FROM dia_asistencia");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function validDayActive($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM dia_asistencia WHERE fecha = :date");
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function closeDay($date)
    {
        // Suponiendo que "estado" 0 es cerrado (y 1 abierto)
        $sql = "UPDATE dia_asistencia 
            SET estado = 0 
            WHERE fecha = :fecha";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha', $date); // $date debe venir en formato 'Y-m-d'

        return $stmt->execute();
    }
}
