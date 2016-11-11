<?php

class BarsModel extends Model{

    public function get_categories(){
        $sql = "SELECT * FROM categories WHERE is_published = 1 ORDER BY show_method";
        return $this->db->query($sql);
    }
    public function get_brands(){
        $sql = "SELECT DISTINCT `brand` FROM `products` ORDER BY `brand`";
        return $this->db->query($sql);
    }
    public function get_weight(){
        $sql = "SELECT DISTINCT `weight` FROM `products` ORDER BY `weight` DESC";
        return $this->db->query($sql);
    }
    public function get_sort(){
        $sql = "SELECT DISTINCT `sort_arabica` FROM `products` ORDER BY `sort_arabica` DESC";
        return $this->db->query($sql);
    }
    public function get_country(){
        $sql = "SELECT DISTINCT `country` FROM `products` ORDER BY `country`";
        return $this->db->query($sql);
    }

}