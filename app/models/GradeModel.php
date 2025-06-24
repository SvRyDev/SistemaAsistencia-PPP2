<?php
class GradeModel extends Model
{
    public function getAllGrades()
    {
        $stmt = $this->db->prepare("SELECT * FROM grados");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
