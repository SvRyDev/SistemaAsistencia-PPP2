<?php
class AttendanceModel extends Model
{
    public function getAllAttendance()
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiante");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function registerAttendance($data) {}

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
