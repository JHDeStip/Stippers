<?php

require_once __DIR__.'/../../config/DatabaseConfig.php';
require_once 'DatabaseException.php';

abstract class Database {
    const TIMEZONE = '+01:00';
    public static function getConnection()
    {
        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
            $conn = mysqli_init();
            $conn->real_connect(DatabaseConfig::HOST, DatabaseConfig::USER, DatabaseConfig::PASSWORD, DatabaseConfig::DATABASE, DatabaseConfig::PORT, NULL, MYSQLI_CLIENT_FOUND_ROWS);
            $conn->set_charset('utf8');
            return $conn;
        }
        catch (Exception $ex){
            throw $ex;
        }
    }

    public static function getTime(){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT now()';
            $stmt = $conn->prepare($commString);
            if (!$stmt->execute())
                throw new DatabaseException('Unknown error during statement execution while getting the database time.', 1);
            else {
                $stmt->bind_result($time);
                if ($stmt->fetch())
                    return $time;
                else
                    throw new DatabaseException('Unknown error during statement execution while getting the database time.', 1);
            }
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally
        {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
}