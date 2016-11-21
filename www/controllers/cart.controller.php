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
     * cart getter
     *
     * @return mixed
     */
    public function getCart()
    {
        $ids_sql = $this->getProducts(true);
        $this->model = new ProductsModel();
        $this->data['cart'] = $this->model->getByIds($ids_sql);
        $sum=[];
        $all_sum = 0;
        foreach ($this->products as $key => $value){
            for ($i=0; $i< count($this->data['cart']); $i++){
                if((int)($this->data['cart'][$i]['id'])==$key){
                    $this->data['cart'][$i]['quantity'] = $value;
                    $sum[$key]= $this->data['cart'][$i]['price']*$value;
                    $all_sum += $sum[$key];
                }
                continue;
            }
        }
        $this->data['cart'][0]['total_sum'] = $all_sum;
        return $this->data['cart'];
    }

    public function indicator(){
        $count_products = array_sum($this->products);
        $this->data['cart'] = $this->getCart();
        $all_sum = $this->data['cart'][0]['total_sum'] ;
        $cart_str = "<b>{$count_products}</b> товаров на сумму <b>{$all_sum}</b> грн.";
        return $cart_str;
    }

    /**
     * products show
     */
    public function show()
    {
        if (!empty($this->products))
        $this->data['cart'] = $this->getCart();
        
    }


    /**
     * adding product
     *
     * @param $quantity
     */
    public function addProduct()
    {
        // if add new product into cart
        if(isset($this->params[0])) {
            $id = (int)($this->params[0]);
            $quantity = (int)($this->params[1]);
            if (!array_key_exists($id, $this->products)) {
                $this->products[$id] = (int)($quantity);
            }else{
                $this->products[$id] += (int)($quantity);
            }
        }
        // if empty cart
        if(empty($this->products)){
            echo '<b>0</b> товаров';
            die();
        }
        Cookie::set('cart', json_encode($this->products));

        echo $this->indicator();
        die();

    }


    public function editquantity(){
        if(isset($this->params[0])) {
            $id = (int)($this->params[0]);
            $quantity = (int)($this->params[1]);
            if (!array_key_exists($id, $this->products)) {
                $this->products[$id] = (int)($quantity);
            }else{
                $this->products[$id] = (int)($quantity);
            }
        }
        Cookie::set('cart', json_encode($this->products));

        echo $this->indicator();
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