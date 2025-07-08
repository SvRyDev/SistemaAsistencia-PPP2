<?php
class SettingModel extends Model
{
    public function getConfig()
    {
        $stmt = $this->db->prepare("SELECT * FROM system_config");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateConfig(
        $id,
        $academic_year,
        $start_date,
        $end_date,
        $entry_time,
        $exit_time,
        $name_school,
        $time_zone,
        $time_tolerance
    ) {
        $stmt = $this->db->prepare("UPDATE system_config SET 
            academic_year   = :academic_year,
            start_date      = :start_date,
            end_date        = :end_date,
            entry_time      = :entry_time,
            exit_time      = :exit_time,
            updated_at      = NOW(),
            name_school     = :name_school,
            time_zone       = :time_zone,
            time_tolerance  = :time_tolerance
        WHERE id = :id");

        // Bind individual parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':academic_year', $academic_year);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':entry_time', $entry_time);
        $stmt->bindParam(':exit_time', $exit_time);
        $stmt->bindParam(':name_school', $name_school);
        $stmt->bindParam(':time_zone', $time_zone);
        $stmt->bindParam(':time_tolerance', $time_tolerance);

        return $stmt->execute();
    }


    public function updateDateImport()
    {
        $stmt = $this->db->prepare("UPDATE system_config SET last_date_import = NOW() WHERE id = 1");
        $stmt->execute();
    }

    public function updateDateExport()
    {
        $stmt = $this->db->prepare("UPDATE system_config SET last_date_export = NOW() WHERE id = 1");
        $stmt->execute();
    }

}
