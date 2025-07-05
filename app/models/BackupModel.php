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


}
