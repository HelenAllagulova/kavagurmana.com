<?php


    /**
     * Class Cart
     */

class Cart {
    /**
     * Products array
     *
     * @var array|mixed
     */
    private $products;


    /**
     *  Constructor
     */
    function __construct()
    {
        $this->products = Cookie::get('item') == null ? array(): json_decode(Cookie::get('item'));
    }


    /**
     * products getter
     *
     * @return mixed
     */
    public function getProducts($for_sql = false)
    {
        if ($for_sql) {
            return implode(',', $this->products);
        }

        return $this->products;
    }


    /**
     * adding product
     *
     * @param $id
     */
    public function addProduct()
    {

        $id = $this->params[0];

        if (!in_array($id, $this->products)) {
            array_push($this->products, $id);
        }
        var_dump($this->products);
        die();
        Cookie::set('item', json_encode($this->products));

    }


    /**
     * deleting product
     *
     * @param $id
     */
    public function deleteProduct($id)
    {
        $id = (int)$id;

        $key = array_search($id, $this->products);
        if ($key !== false){
            unset($this->products[$key]);
        }

        Cookie::set('books', serialize($this->products));
    }


    /**
     *  clear cart
     */
    public function clear()
    {
        Cookie::delete('books');
    }



    /**
     * check if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->products;
    }
}