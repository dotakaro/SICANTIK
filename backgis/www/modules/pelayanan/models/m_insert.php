<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class M_Insert extends Model
{
    public function Construct()
    {
        parent::Construct();
    }
    function inserData($no,$pesan)
    {
        return $this->db->query("insert into outbox(DestinationNumber,TextDecoded,DeliveryReport) values(?,?,'yes')",array($no,$pesan));
    }
}


