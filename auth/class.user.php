<?php

class User 
{
    private $_id;
    private $_name;
    private $_email;
    private $_phone;

    public function __construct(string $name, string $email, string $phone) 
    {
        $this->_name = $name;
        $this->_email = $email;
        $this->_phone = $phone;
    }

    public function checkRegister(string &$error = ''): bool
    {
        if (!$this->checkNewUser() && !$this->_name) {
            $error = 'Имя не может быть пустым';
            return false;
        }

        if (strpos($this->_email, '@') === false) {
            $error = 'Email некорректный';
            return false;
        }

        if (!$this->checkNewUser() && !$this->_phone) {
            $error = 'Телефон не может быть пустым';
            return false;
        }

        return true;
    }

    public function checkNewUser(): bool
    {
        if ($user = self::getByEmail($this->_email)) {
            return true;
        }

        return false;
    }


    public static function getByEmail(string $email)
    {
        $db = DB::instance();
        $select = "SELECT * FROM users WHERE email = :email";
        $data = $db->fetchOne($select, __METHOD__, ['email' => $email]);
        if (!$data) {
            return false;
        }

        $user = new self($data['name'], $data['email'], $data['phone']);

        return $user;
    }

    public function addToDb() 
    {
        $db = DB::instance();
        $insert = "INSERT INTO users (`name`, email, phone) 
                    VALUES (
                    :name, :email, :phone
                    )";

        $db->exec($insert, __METHOD__, [
            'name' => $this->_name,
            'email' => $this->_email,
            'phone' => $this->_phone
            ]);

        $id = $db->getLastInsertId();
        $this->_id = $id;
        return $id;
    }

    public function getId() 
    {
        $db = DB::instance();
        $select = "SELECT id FROM users WHERE email = :email";
        $userId = $db->fetchOne($select, __METHOD__, ['email' => $this->_email]);
        if (!$userId) {
            return false;
        }

        $this->_id = $userId['id']; 
        return $this->_id;  
    }

    public function getName() 
    {
        return $this->_name;  
    }

}