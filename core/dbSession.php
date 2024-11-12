<?php
require_once(__DIR__ . '/./db.php');
class DBSession implements SessionHandlerInterface
{

    public static function start()
    {
        new DBSession;
    }

    protected function __construct()
    {
        session_set_save_handler(
            $this,
            true
        );
        session_start();
    }

    public function open(string $savePath, string $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        $db = DB::getInstance();
        $conn = $db->getConnection();
        $session = $conn->prepare('SELECT payload FROM session WHERE id = ? LIMIT 1');
        $session->execute([$id]);
        $result = $session->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['payload'];
        };
        return '';
    }

    public function write(string $id, string $data): bool
    {
        $access = time();
        $db = DB::getInstance();
        $conn = $db->getConnection();

        $session = $conn->prepare('INSERT INTO session(id,payload,last_access) VALUES (?,?,?) ON CONFLICT (id) DO UPDATE SET payload = ?, last_access = ?');
        if ($session->execute([$id, $data, $access, $data, $access])) {
            setcookie('session_id', $id, [
                'httponly' => true
            ]);

            return true;
        };
        return false;
    }

    public function gc(int $max_lifetime): false
    {
        $old = time() - max($max_lifetime);
        $db = DB::getInstance();
        $conn = $db->getConnection();
        $session =  $conn->prepare('DELETE FROM session WHERE last_access <= ?');
        if ($session->execute([$old])) {
            return true;
        }
        return false;
    }

    public function destroy($id): bool
    {
        $db = DB::getInstance();
        $conn = $db->getConnection();
        $session = $conn->prepare('DELETE FROM sessions WHERE id = ?');
        if ($session->execute([$id])) {
            return true;
        }
        return false;
    }
}
