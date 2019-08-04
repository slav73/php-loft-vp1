<?php

class Order 
{
    private $_userId;
    private $_id;
    private $_street;
    private $_home;
    private $_part;
    private $_appt;
    private $_floor;
    private $_comment;
    private $_payment;
    private $_callback;


    public function __construct(string $street, string $home, string $part, string $appt, string $floor,
            string $comment, string $need_change, string $payment, string $callback) 
    {
        $this->_street = $street;
        $this->_home = $home;
        $this->_part = $part;
        $this->_appt = $appt;
        $this->_floor = $floor;
        $this->_comment = $comment;
        $this->_need_change = $need_change;
        $this->_payment = $payment;
        $this->_callback = $callback;
    }

    public function checkOrder(string &$error = ''): bool
    {
        if (!$this->_street) {
            $error = 'Введите название улицы';
            return false;
        }

        if (!$this->_home) {
            $error = 'Введите номер дома';
            return false;
        }

        return true;
    }

    public function addToDb(int $user_id) 
    {
        $db = DB::instance();
        $insert = "INSERT INTO orders (user_id, street, home, part, appt, floor, comment, need_change, payment, callback) 
                    VALUES (
                        :user_id, :street, :home, :part, :appt, :floor, :comment, :need_change, :payment, :callback
                    )";

        $db->exec($insert, __METHOD__, [
            'user_id' => $user_id,
            'street' => $this->_street,
            'home' => $this->_home,
            'part' => $this->_part,
            'appt' => $this->_appt,
            'floor' => $this->_floor,
            'comment' => $this->_comment,
            'need_change' => $this->_need_change,
            'payment' => $this->_payment,
            'callback' => $this->_callback
            ]);

        $id = $db->getLastInsertId();
        $this->_id = $id;
        return $id;
    }

    public function getOrderId() 
    {
        return $this->_id;  
    }

    public function getOrdersQuantity(int $user_id) 
    {
        $db = DB::instance();
        $select = "SELECT * FROM orders WHERE user_id = :user_id";
        $data = $db->fetchAll($select, __METHOD__, ['user_id' => $user_id]);
        return count($data);  
    }

    public function createOrderFile(int $user_id) 
    {
        $dir = __DIR__. "/orders/";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $filename = ($dir . date('Y-m-d-H-i-s'));

        $data = "Заказ № " . $this->_id.
        "\nВаш заказ будет доставлен по адресу:\n".
        $this->_street . $this->_home .
        "\nDarkBeefBurger за 500 рублей, 1 шт\n\n";

        if ($this->getOrdersQuantity($user_id) == 1) {
            $data .= "Это ваш первый заказ";
        } else {
            $data .= "Это ваш ". $this->getOrdersQuantity($user_id) . " заказ";
        }

        file_put_contents($filename, $data);
    }
}