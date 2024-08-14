<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/core/helpers/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

class Model {

    public $dbh;
    public $sitename;
    public $emailUsername;
    public $emailPassword;

    public function __construct() {
        // Load .env variables
        $this->loadEnv();

        // Assign the loaded environment variables
        $this->emailUsername = $_ENV['EMAIL_USERNAME'] ?? null;
        $this->emailPassword = $_ENV['EMAIL_PASSWORD'] ?? null;

        // Initialize database connection
        $this->dbh = $this->connectDb();
    }

    private function loadEnv() {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
    }
    
    public function connectDb() {
        try {
            $pdo = new PDO(
                "mysql:host=" . ($_ENV['DB_HOST'] ?? '') . ";dbname=" . ($_ENV['DB_NAME'] ?? ''), 
                $_ENV['DB_USERNAME'] ?? '', 
                $_ENV['DB_PASSWORD'] ?? ''
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            // Handle the exception as a PDOException
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function connect() {
        return $this->dbh;
    }

    // Other methods...

    public function sendMail($email, $subject, $message) {
        global $sitename;
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $_SERVER['SERVER_NAME'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->emailUsername;
            $mail->Password = $this->emailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($this->emailUsername, $sitename);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = strip_tags($message);

            $mail->send();
            return 0;
        } catch (Exception $e) {
            return 1;
        }
    }

    public function getConfigValue($list, $name) {
        foreach($list as $item) {
            if($item->name == $name) {
                return $item->value;
            }
        }
    }

    public function getApiConfiguration() {
        $dbh = $this->connect();
        $sql = "SELECT * FROM apiconfigs";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    public function getSiteConfiguration() {
        $dbh = $this->connect();
        $sql = "SELECT * FROM sitesettings WHERE sId = 1";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_OBJ);
        return $results;
    }

    public function __destruct() {
        $dbh = null;
    }
}

?>
