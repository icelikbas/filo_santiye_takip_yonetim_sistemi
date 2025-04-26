<?php
namespace App\Core;

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->dbh = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function query(string $sql): bool {
        try {
            $this->stmt = $this->dbh->prepare($sql);
            return true;
        } catch (\PDOException $e) {
            error_log("Database::query - PDO Hatası: " . $e->getMessage());
            return false;
        }
    }

    public function bind($param, $value, $type = null): bool {
        try {
            if (is_null($type)) {
                switch (true) {
                    case is_int($value):
                        $type = \PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = \PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = \PDO::PARAM_NULL;
                        break;
                    default:
                        $type = \PDO::PARAM_STR;
                }
            }
            return $this->stmt->bindValue($param, $value, $type);
        } catch (\PDOException $e) {
            error_log("Database::bind - PDO Hatası: " . $e->getMessage());
            return false;
        }
    }

    public function execute(): bool {
        try {
            if ($this->stmt === null) {
                error_log("Database::execute - Hata: Statement null, sorgu çalıştırılmadan önce query() fonksiyonu çağrılmamış olabilir");
                return false;
            }
            return $this->stmt->execute();
        } catch (\PDOException $e) {
            error_log("Database::execute - PDO Hatası: " . $e->getMessage());
            return false;
        }
    }

    public function resultSet(): array|false {
        try {
            if ($this->execute()) {
                return $this->stmt->fetchAll();
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Database::resultSet - PDO Hatası: " . $e->getMessage());
            return false;
        }
    }

    public function single(): object|false {
        try {
            error_log("Database::single - Sorgu çalıştırılıyor: " . $this->stmt->queryString);
            if ($this->execute()) {
                $result = $this->stmt->fetch();
                
                if ($result === false) {
                    error_log("Database::single - Kayıt bulunamadı: " . $this->stmt->queryString);
                    // Sorgu detaylarını göster
                    ob_start();
                    $this->stmt->debugDumpParams();
                    $params = ob_get_clean();
                    error_log("Database::single - Sorgu parametreleri: " . $params);
                } else {
                    error_log("Database::single - Kayıt bulundu: " . json_encode($result, JSON_UNESCAPED_UNICODE));
                }
                
                return $result;
            }
            
            error_log("Database::single - Execute başarısız oldu");
            return false;
        } catch (\PDOException $e) {
            error_log("Database::single - PDO Hatası: " . $e->getMessage());
            error_log("Database::single - Hata detayı: " . $e->getTraceAsString());
            error_log("Database::single - Sorgu: " . ($this->stmt ? $this->stmt->queryString : 'Sorgu hazırlanmamış'));
            return false;
        } catch (\Exception $e) {
            error_log("Database::single - Genel Hata: " . $e->getMessage());
            error_log("Database::single - Hata detayı: " . $e->getTraceAsString());
            return false;
        }
    }

    public function rowCount(): int {
        return $this->stmt->rowCount();
    }

    public function lastInsertId(): string {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction(): bool {
        return $this->dbh->beginTransaction();
    }

    public function commit(): bool {
        return $this->dbh->commit();
    }

    public function rollback(): bool {
        return $this->dbh->rollBack();
    }
}