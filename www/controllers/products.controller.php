<?php

class ProductsController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new ProductsModel();
    }

    public function item(){
        if (isset($this->params[0])) {
            $this->data['product'] = $this->model->getById($this->params[0]);
            
        }
    }
    public function category (){
        if(!empty($this->params[0]))
        {
            $this->category_model = new CategoriesModel();
            $this->data['category'] = $this->category_model->getById($this->params[0]);
            $this->data['pages'] = $this->model->getByCategory($this->params[0]);
        }
        if(!empty($this->params[1])&&!empty($this->params[0]))
        {
            $this->category_model = new CategoriesModel();
            $this->data['category'] = $this->category_model->getById($this->params[1]);
            $this->data['pages'] = $this->model->getBySort($this->params[0],$this->params[1]);
        }
    }
    public function sort(){
        if(!empty($this->params[0]))
        {
            $this->data['pages'] = $this->model->getBySort($this->params[0]);
        }

    }

    public function search(){
        if(!empty($this->params[1])){
            $this->data['pagination'] = $this->params[1];
        } else {
            $this->data['pagination'] = '';
        }
        
        if((!empty($_POST['search']) && (strlen($_POST['search']) >= 3)) ||
            (!empty($this->params[0]) &&  strlen($this->params[0]))>=3)
        {
            $search = !empty($_POST['search']) ? $_POST['search']: $this->params[0];
            $this->data['pages'] = $this->model->getByString($search, $this->data['pagination']);
            $this->data['count'] = $this->model->getCountSearch($search);
            $this->data['count_for_paginatior'] = (is_int($this->data['count']/5))? $this->data['count']/5 : floor($this->data['count']/5 + 1);
            $this->data['search'] = $search;
        } else {
            $this->data['msg'] = 'Ваш запрос должен содержать не менее 3-х символов';
        }
    }
    
    public function widesearch(){
        if(!empty($this->params[0])){
            $this->data['pagination'] = $this->params[0];
        } else {
            $this->data['pagination'] = '';
        }
        $this->data['widesearch'] = $this->model->wide_search($_POST, $this->data['pagination']);
        $this->data['count']= $this->data['widesearch'][0]['count'];
        $this->data['count_for_paginatior'] = (is_int($this->data['count']/5))? $this->data['count']/5 : floor($this->data['count']/5 + 1);

        $this->data['search'] = $this->data['widesearch'][0]['search'];

    }

    public function admin_index(){
        if(!empty($this->params[0]))
        {
            $this->data['pagination'] = $this->params[0];
        } else
        {
            $this->data['pagination'] = '';
        }

        $this->data['pages'] = $this->model->getList($this->data['pagination']);
        $this->data['count'] = $this->model->getCount();
        $this->data['count_for_paginatior'] = (is_int($this->data['count']/5))? $this->data['count']/5 : floor($this->data['count']/5 + 1);
        $category_object = new CategoriesModel;
        $this->data['categories'] = $category_object->getList();
    }
    
    public function admin_add(){

        if(!empty($_POST['id_categories']) && !empty($_POST['brand'])
            && !empty($_POST['name']) && !empty($_POST['price'])&& !empty($_POST['weight']))
        {

            $result = $this->model->save($_POST,$_FILES);
            if($result)
            {
                Session::setFlash('Page was saved.');
            } else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/products');
        } else
        {
            $category_object = new CategoriesModel;
            $this->data['page'] = $category_object->getList();
        }
    }

    public function admin_edit(){
        $rand = rand(1,1000);
        if(!empty($_POST['id_categories']) && !empty($_POST['brand'])
            && !empty($_POST['name']) && !empty($_POST['weight'])&& !empty($_POST['price']))
        {
            $id = $_POST['id'];
            $result = $this->model->save($_POST,$_FILES, $id);
            if($result)
            {
                Session::setFlash('New product was saved.');
            } else
            {
                Session::setFlash('Error.');
            }
            Router::redirect("/admin/products?{$rand}");
        }

        if (isset($this->params[0]))
        {
            $this->data['product'] = $this->model->getById($this->params[0]);
            $category_object = new CategoriesModel;
            $this->data['page'] = $category_object->getList();
            
            if(!$this->data['product'])
            {
                Session::setFlash('Wrong news id.');
                Router::redirect('/admin/products/');
            }
        }else
        {
            Session::setFlash('Wrong news id.');
            Router::redirect('/admin/products/');
        }
    }

    public function admin_delete()
    {
        if(isset($this->params[0]))
        {
            $result = $this->model->delete($this->params[0]);
            if($result)
            {
                Session::setFlash('News was deleted.');
            } else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/products');
        }
    }

}