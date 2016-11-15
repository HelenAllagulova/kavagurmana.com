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
    private $products = [];


    /**
     *  Constructor
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
        if(!Cookie::get('cart') == null){
            $this->products = json_decode(Cookie::get('cart'), true);
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

            return implode(',', array_keys($this->products));
        }

        return $this->products;
    }


    /**
     * adding product
     *
     * @param $id
     */
    public function addProduct($quantity=1)
    {
        $id = (int)($this->params[0]);
        if (!array_key_exists($id, $this->products)) {
            $this->products[$id] =(int)($quantity);
        }
        Cookie::set('cart', json_encode($this->products));
        $count_products = array_sum($this->products);
        $ids_sql = $this->getProducts(true);
        $this->model = new ProductsModel();
        $this->data['cart'] = $this->model->getByIds($ids_sql);
        $sum=[];
        $all_sum = 0;
             foreach ($this->products as $key => $value){
                for ($i=0; $i< count($this->data['cart']); $i++){
                    if((int)($this->data['cart'][$i]['id'])==$key){
                        $sum[$key]= $this->data['cart'][$i]['price']*$value;
                        $all_sum += $sum[$key];
                    }
                  continue;
                }
            }
        $cart_str = "<b>{$count_products}</b> товаров на сумму <b>{$all_sum}</b> грн.";
        echo $cart_str;
        die();
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

        Cookie::set('cart', serialize($this->products));
    }


    /**
     *  clear cart
     */
    public function clear()
    {
        Cookie::delete('cart');
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