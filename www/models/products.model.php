<?php

class ProductsModel extends Model
{
    public function getList($pagination = false, $instock = false){
        
        $sql = "select * from products where 1";
        if(!$pagination) {
            if ($instock) {
                $sql .= " and stock = 1";
            }
            $sql.= " LIMIT 5 ";
        }
        else{
            if ($instock) {
                $sql .= " and stock = 1";
            }
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        }
        return $this->db->query($sql);
    }
    
    public function getCount(){
        $sql = "SELECT COUNT(*) as `count` FROM products";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }

    public function getCountSearch($string){
        $sql = "SELECT COUNT(*) as `count` FROM products WHERE description LIKE '%{$string}%'";
        $result = $this->db->query($sql);
        return $result[0]['count'];
    }

    public function save($data,$image, $id=null){
        $id = (int)$id;

        $id_categories = $this->db->escape(Validate::fixString($data['id_categories']));
        $brand = $this->db->escape(Validate::fixString($data['brand']));
        $name = $this->db->escape($data['name']);
        $weight = $this->db->escape(Validate::fixString($data['weight']));
        $sort_arabica = $this->db->escape(Validate::fixString($data['sort_arabica']));
        $sort_robusta = $this->db->escape(Validate::fixString($data['sort_robusta']));
        $roasting = $this->db->escape(Validate::fixString($data['roasting']));
        $price = $this->db->escape(Validate::fixString($data['price']));
        $country = $this->db->escape(Validate::fixString($data['country']));
        $description = $this->db->escape(Validate::fixString($data['description']));

        $stock = isset($data['stock']) ? 1 : 0;

        if(($id) && $image['image']['size'] > 0 )
        {
            $image_path = $this->imageProcessing($image['image'], $id);
            $image_sql = " , img_path = '{$image_path}'";
        }

        if(!$id){ // Add new record
            $sql = "
            INSERT INTO products
                SET id_categories = '{$id_categories}',
                    brand = '{$brand}',
                    name = '{$name}',
                    weight = '{$weight}',
                    sort_arabica = '{$sort_arabica}',
                    sort_robusta = '{$sort_robusta}',
                    roasting = '{$roasting}',
                    price = '{$price}',
                    country = '{$country}',
                    description = '{$description}',
                    stock = '{$stock}'
            ";
            $result = $this->db->query($sql);
            $id = $this->db->getLastId();
            if(($id) && $image['image']['size'] > 0 ) {
                $image_path = $this->imageProcessing($image['image'], $id);
                $image_sql = "img_path = '{$image_path}'";

                $sql = "
                UPDATE products
                    SET {$image_sql}
                    WHERE id = {$id}
                ";
                $result = $this->db->query($sql);
            }
        } else {// Update existing record
            $sql = "
            UPDATE products
                SET id_categories = '{$id_categories}',
                    brand = '{$brand}',
                    name = '{$name}',
                    weight = '{$weight}',
                    sort_arabica = '{$sort_arabica}',
                    sort_robusta = '{$sort_robusta}',
                    roasting = '{$roasting}',
                    price = '{$price}',
                    country = '{$country}',
                    description = '{$description}',
                    stock = '{$stock}'
                    {$image_sql}
                WHERE id = {$id}
            ";
            $result = $this->db->query($sql);
        }

        return $result;
    }
    
    public function getById($id){
        $id = (int)$id;
        $sql = "SELECT * FROM products WHERE id = '{$id}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }
    public function getByCategory($category_id){
        $category_id = (int)$category_id;
        $sql = "SELECT * FROM products WHERE id_categories = '{$category_id}' ";
        $result = $this->db->query($sql);
        return $result;
    }
    public function getBySort($sort_param,$id_category=''){
        $sql = "SELECT * FROM products";
        if ($id_category!=''){
            $sql .=  " WHERE id_categories = {$id_category}";
        }
        switch ($sort_param){
            case 'incr':
                $sql .= " ORDER BY price";
                break;
            case 'decr':
                $sql .= " ORDER BY price DESC";
                break;
            case 'az':
                $sql .= " ORDER BY brand";
                break;
            case 'za':
                $sql .= " ORDER BY brand DESC";
                break;
        }
        $result = $this->db->query($sql);
        return $result;

    }

    public function imageProcessing($image, $id)
    {
        $full_path_for_image = '';

        switch($image['type'])
        {
            case 'image/jpeg': $ext = 'jpg'; break;
            case 'image/jpg': $ext = 'jpg'; break;
            case 'image/gif':  $ext = 'gif'; break;
            case 'image/png':  $ext = 'png'; break;
            case 'image/tiff': $ext = 'tif'; break;
            default:           $ext = '';    break;
        }

        if ($ext)
        {
            $path = "./img/products/{$id}";
            if(!file_exists($path)){
                mkdir($path);
            }
            $full_path_for_image = $path."/main".".".$ext;
            move_uploaded_file($image['tmp_name'], $full_path_for_image);
        }

        return (substr($full_path_for_image, 1));
    }

    public function delete($id){
        $id = (int)$id;
        $sql = "delete from products where id = {$id}";
        return $this->db->query($sql);
    }

    public function getByString($string,$pagination = false){
        $string = $this->db->escape(Validate::fixString($string));
        $sql = "SELECT * FROM products WHERE description LIKE '%{$string}%'";

        if(!$pagination) {
            $sql.= " LIMIT 5 ";
        }
        else{
            $number = 5*($pagination-1);
            $sql.= " LIMIT {$number},5 ";
        }

        $result = $this->db->query($sql);
        //подсветка результатов поиска
        $formated = [];
        $i = -1;
        foreach($result as $page){
            $i++;
            foreach ($page as $key=>$value){
                preg_match_all('/'.$string.'/i', $value, $out_1);
                $value = preg_replace('/'.$string.'/i', '<span style="background-color: yellow">' .$string.'</span>',$value);
                preg_match_all('/'.$string.'/i', $value, $out_2);
                $value = str_replace($out_2[0], $out_1[0], $value);
                $formated[$i][$key] = $value;
            }
        }

        return $formated;

    }
}