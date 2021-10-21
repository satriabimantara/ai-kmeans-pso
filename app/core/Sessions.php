<?php
class Session
{
    public static function setSessionLogin($nama_session)
    {
        $_SESSION[$nama_session] = [
            'status' => 'active'
        ];
    }
    public static function unsetSessionLogin($nama_session)
    {
        unset($_SESSION[$nama_session]);
    }
    public static function checkSessionLogin($nama_session)
    {
        if (isset($_SESSION[$nama_session])) {
            return 1;
        } else {
            return 0;
        }
    }
}
