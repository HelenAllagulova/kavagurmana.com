<?php
class SigninModel extends Model
{

    public function getByLogin($email)
    {
        $email = $this->db->escape($email);
        $sql = "select * from users where email = '{$email}' limit 1";
        $result = $this->db->query($sql);
        if( isset($result[0]))
        {
            return $result[0];
        }
        return false;
    }

    public function getAdminByLogin($email)
    {
        $email = $this->db->escape($email);
        $sql = "select * from users where email = '{$email}' AND role = 'admin'";
        $result = $this->db->query($sql);
        if( isset($result[0]))
        {
            return $result[0];
        }
        return false;
    }

}