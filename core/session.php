<?php
require_once(__DIR__ . '/./dbSession.php');
class Session
{
    public static function regenerate()
    {
        $_SESSION['OBSOLETE'] = time();
        $newSession = session_create_id();
        $_SESSION['new_session_id'] = $newSession;
        session_write_close();
        session_id($newSession);
        DBSession::start();
        static::regenerateCSRF();
        unset($_SESSION['new_session_id']);
        unset($_SESSION['OBSOLETE']);
    }

    public static function checkSession()
    {
        $second = 60;
        //left session for some period of time
        //See the reason https://wiki.php.net/rfc/session_regenerate_id#:~:text=old%20session%20is%20left%20active%20for%20reliable%20session%20id%20regeneration.%20there%20are%20many%20reasons%20why%20old%20session%20is%20left.%20examples%20are%3A
        if (isset($_SESSION['OBSOLETE'])) {
            if ($_SESSION['OBSOLETE'] <= time() - $second) {
                return false;
            }
            if (isset($_SESSION['new_session_id'])) {
                // Not fully expired yet. Could be lost cookie by unstable network.
                // Try again to set proper session ID cookie.
                session_write_close();
                session_id($_SESSION['new_session_id']);
                DBSession::start();
            }
        }

        return true;
    }

    public static function getCSRF()
    {
        if (!isset($_SESSION['csrf'])) {
            static::regenerateCSRF();
        }

        return $_SESSION['csrf'];
    }

    public static function regenerateCSRF()
    {
        $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
