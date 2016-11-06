<?php

class CategoriesModel extends Model
{
    public function getList($only_published = false){
        $sql = "select * from categories";
        if($only_published){
            $sql.= " and is_published = 1";
        }
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id=(int)$id;
        $sql = "select * from categories where id = '{$id}' limit 1" ;
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $id=null){
        $id = (int)$id;
        $name = Validate::fixString($data['name']);
        $name = $this->db->escape($name);
        $show_method = Validate::fixString($data['show_method']);
        $show_method = $this->db->escape($show_method);
        $is_published = isset($data['is_published']) ? 1 : 0;
        if(!$id){ // Add new record
            $sql = "
            INSERT INTO categories
                SET name = '{$name}',
                    show_method = {$show_method},
                    is_published = {$is_published}
            ";
        } else {// Update existing record
            $sql = "
            UPDATE categories
                SET name = '{$name}',
                    show_method = {$show_method},
                    is_published = {$is_published}
                WHERE id = {$id}
            ";
        }
        return $this->db->query($sql);
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "delete from categories where id = {$id}";
        return $this->db->query($sql);
    }

}