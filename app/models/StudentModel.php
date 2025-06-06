<?php
class StudentModel extends Model
{
    public function getAllStudents()
    {
        $stmt = $this->db->prepare("SELECT * FROM estudiante");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addMultipleStudents($alumnos)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO estudiante (dni, nombre, apellidos, grado, seccion) VALUES (?, ?, ?, ?, ?)");

            foreach ($alumnos as $alumno) {
                $stmt->execute([
                    $alumno['dni'],
                    $alumno['nombres'],
                    $alumno['apellidos'],
                    $alumno['grado'],
                    $alumno['seccion']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
};
