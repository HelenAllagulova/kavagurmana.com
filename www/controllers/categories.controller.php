<?php
class CategoriesController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new CategoriesModel();
    }

    public function admin_index()
    {
        $this->data['page'] = $this->model->getList();
    }

    public function admin_add(){
        if(!empty($_POST['name']) && !empty($_POST['show_method']))
        {
            $result = $this->model->save($_POST);
            if($result)
            {
                Session::setFlash('Page was saved.');
            } else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/categories');
    }

    public function admin_delete(){
        if(isset($this->params[0]))
        {
            $result = $this->model->delete($this->params[0]);
            if($result)
            {
                Session::setFlash('Page was deleted.');
            } else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/categories');
        }
    }

    public function admin_edit(){
        if((!empty($_POST['name']) && !empty($_POST['id'])))
        {
            $id = $_POST['id'];
            $result = $this->model->save($_POST, $id);
            if($result)
            {
                Session::setFlash('Page was saved.');
            } else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/categories');
        }

        if (isset($this->params[0]))
        {
            $this->data['categories'] = $this->model->getById($this->params[0]);
            if(!$this->data['categories'])
            {
                Session::setFlash('Wrong page id.');
                Router::redirect('/admin/categories/');
            }
        }else
        {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/categories/');
        }
    }
}