<?php
class SectionModel extends Model
{
    public function getAllSections()
    {
        $stmt = $this->db->prepare("SELECT * FROM secciones");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
