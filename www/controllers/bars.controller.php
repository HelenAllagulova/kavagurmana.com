<?php
class BarsController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new BarsModel();
    }

    public function left_categories_bar()
    {
        $this->data['categories'] = $this->model->get_categories();
    }
    public function left_filter()
    {
        $this->data['left_filter_brands'] = $this->model->get_brands();
        $this->data['left_filter_weight'] = $this->model->get_weight();
        $this->data['left_filter_sort'] = $this->model->get_sort();
        $this->data['left_filter_country'] =$this->model->get_country();
    }
}