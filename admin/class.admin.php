<?php

class Admin 
{
    private $_userId;
    private $_orderId;
    private $_street;

    public function __construct () 
    {}

    public function createUsersTable() 
    {
        $db = DB::instance();
        $select = "SELECT name FROM users LIMIT 20";
        $data = $db->fetchAll($select, __METHOD__, []);

        echo "Список пользователей:<br><br>";
        foreach ($data as $key => $value) {
            echo $value['name'], '<br>';
        }

        $select = "SELECT street, home FROM orders LIMIT 20";
        $data = $db->fetchAll($select, __METHOD__, []);

        echo "<br>Список заказов:<br><br>";
        foreach ($data as $key => $value) {
            echo $value['street'], '  ', $value['home'], '<br>';
        }
    }
}