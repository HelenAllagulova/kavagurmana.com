<?php

    /**
     * Class CartController
     */

class CartController extends Controller{
    /**
     * Products array
     *
     * @var array|mixed
     */
    private $products;


    /**
     *  Constructor
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
        var_dump(Cookie::get('item'));
        if(Cookie::get('item') == false){
            $this->products = array();
        } else {
            $this->products = json_decode(Cookie::get('item'));
        }
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

        Cookie::set('item', json_encode($this->products));
        die;
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

        Cookie::set('item', serialize($this->products));
    }


    /**
     *  clear cart
     */
    public function clear()
    {
        Cookie::delete('item');
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