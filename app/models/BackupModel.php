<?php
class BackupModel extends Model
{

    public function getDatabaseStructure($dbName)
    {
        $stmt = $this->db->prepare("
            SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = :dbname
            ORDER BY TABLE_NAME, ORDINAL_POSITION
        ");
        $stmt->bindParam(':dbname', $dbName);
        $stmt->execute();

        $estructura = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $tabla = $row['TABLE_NAME'];
            $estructura[$tabla][$row['COLUMN_NAME']] = $row['COLUMN_TYPE'];
        }

        return $estructura; // array[tabla][columna] => tipo
    }


    public function importarSQL($contenido)
    {
        // Crear conexión mysqli para multi_query
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($mysqli->connect_error) {
            throw new Exception("Conexión fallida: " . $mysqli->connect_error);
        }

        // Desactivar checks
        $mysqli->query("SET foreign_key_checks = 0");

        // Ejecutar consultas múltiples
        if (!$mysqli->multi_query($contenido)) {
            throw new Exception("Error al importar: " . $mysqli->error);
        }

        // Limpiar resultados intermedios
        do {
            $mysqli->store_result();
        } while ($mysqli->more_results() && $mysqli->next_result());

        // Reactivar checks (opcional)
        $mysqli->query("SET foreign_key_checks = 1");

        $mysqli->close();

        return true;
    }

    public function resetSystem()
    {
        try {
            // Inicia transacción para eliminar datos y restaurar configuración
            $this->db->beginTransaction();
    
            // Eliminar en orden de dependencia (por claves foráneas)
            $this->db->query("DELETE FROM asistencia_estudiante");
            $this->db->query("DELETE FROM dia_asistencia");
            $this->db->query("DELETE FROM carnet_estudiante");
            $this->db->query("DELETE FROM estudiante");
    
            // Restaurar configuración
            $this->db->query("
                UPDATE system_config SET
                    name_school = 'Nombre del Colegio',
                    academic_year = YEAR(CURDATE()),
                    start_date = DATE_FORMAT(CURDATE(), '%Y-03-01'),
                    end_date = DATE_FORMAT(CURDATE(), '%Y-12-15'),
                    entry_time = '07:30:00',
                    exit_time = '13:00:00',
                    time_tolerance = 10,
                    time_zone = 'America/Lima',
                    updated_at = NOW()
                WHERE id = 1
            ");
    
            // Confirmar cambios (commit antes de ejecutar ALTER TABLE)
            $this->db->commit();
    
            // ⚠️ Fuera de la transacción: reiniciar AUTO_INCREMENT
            $this->db->query("ALTER TABLE asistencia_estudiante AUTO_INCREMENT = 1");
            $this->db->query("ALTER TABLE dia_asistencia AUTO_INCREMENT = 1");
            $this->db->query("ALTER TABLE carnet_estudiante AUTO_INCREMENT = 1");
            $this->db->query("ALTER TABLE estudiante AUTO_INCREMENT = 1");
    
            return true;
    
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new Exception("Error al reiniciar el sistema: " . $e->getMessage());
        }
    }
    
    
    



}
