<?php

class UserModelo extends sistema\nucleo\APModelo
{
    public function getUsers($email, $password)
    {
        $consult = $this->_bd->consulta('');
        $consult = $this->_bd->enlace('email', $email);
        $consult = $this->_bd->enlace('password', $password);

        $row = $consult = $this->_bd->single();
        return $row;
    }

    public function validatedUser($email, $password)
    {
        $query = $this->db->where('user', $email);
        $query = $this->db->where('password', $password);
        $query = $this->db->get('users');
        return $query->row();
    }
}