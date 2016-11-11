<?php



function mvc_autoloader($class_name){
    $lib_path = ROOT.DS.'lib'.DS.strtolower($class_name).'.class.php';
    $controllers_path = ROOT.DS.'controllers'.DS.str_replace('controller', '', strtolower($class_name)).'.controller.php';
    $model_path = ROOT.DS.'models'.DS.str_replace('model', '', strtolower($class_name)).'.model.php';

    if(file_exists($lib_path)){
        require_once($lib_path);
    } elseif (file_exists($controllers_path)){
        require_once($controllers_path);
    } elseif (file_exists($model_path)){
        require_once($model_path);
    } else {
        throw new Exception('Failed to include class: '.$class_name);
    }


}

spl_autoload_register('mvc_autoloader');

require_once (ROOT.DS.'config'.DS.'config.php');


function __($key, $default_value=''){
    return Lang::get($key, $default_value);
}


function paginator($count_pages, $active, $count_show_pages, $url, $url_page)
{

    if ($count_pages > 1) {

        for($i = 1; $i <= $count_pages; $i++)
        {
            $arr_ajax[] = $i;
        }


        // Для первой = 0
        $left = $active - 1; // первая выводимая страница
        // Для самой правой = 10 - 0 = 10
        $right = $count_pages - $active; // последняя выводимая страница
        // Если первая меньше (у нас 0 ), чем 10/2 = 5
        if ($left < floor($count_show_pages / 2))
        {
            // Наш случай
            $start = 1;
        }
        else {
            $start = $active - floor($count_show_pages / 2);
        }
        // 1 + 5 - 1 = 5
        $end = $start + $count_show_pages - 1;
        // Если 5 > 10
        if ($end > $count_pages) {
            $start -= ($end - $count_pages);
            $end = $count_pages;
            if ($start < 1) $start = 1;
        }
        ?>
        <div style="text-align: center" id="pagination">
            <!--            <span>Страницы: </span>-->

            <?php if ($active != 1) { ?>
                <a class="btn btn-xs btn-default" href="<?=$url?>" title="Первая страница">&lt;&lt;&lt;</a>
                <a class="btn btn-xs btn-default" href="<?php if ($active == 2) { ?><?=$url?><?php } else { ?><?=$url_page.($active - 1)?><?php } ?>" title="Предыдущая страница">&lt;</a>
            <?php } ?>

            <?php for ($i = $start; $i <= $end; $i++) { ?>
                <?php if ($i == $active) { unset($arr_ajax[$i-1]);?>
                    <span  class="btn btn-xs search-btn"><?=$i?></span>
                <?php } elseif(($i == ($end-1) && $end>=5 && $active != $count_pages && $active==1) ||
                    ($i == ($end-1) && $end>=5 && $active != $count_pages && $active!=1) ||
                    ($i == ($start+1) && $end>=5 && $active!=1)) { ?>
                    <span id="more_button" onClick="show_other_pages()" class="btn btn-xs btn-default">...</span>
                <?php } else { ?>
                    <a class="btn btn-xs btn-default" href="<?php if ($i == 1 || $i == $start) { ?>
                        <?=$url?>
                                          <?php } elseif($i == $end) { ?>
                         <?=$url_page.$count_pages?>
                                          <?php } else { ?>
                        <?=$url_page.$i?>
                                                  <?php } ?>">
                        <?php if($i == ($end)){ echo $count_pages; unset($arr_ajax[$count_pages-1]);}
                        elseif(($i == ($end-1) && $end>=5 && $active != $count_pages && $active==1) ||
                            ($i == ($end-1) && $end>=5 && $active != $count_pages && $active!=1)||
                            ($i == ($start+1) && $end>=5 && $active!=1)){echo '<b onClick="show_other_pages()" id="more_button">...</b>';}
                        elseif($i == $start){
                            echo 1;
                            unset($arr_ajax[0]);
                        } else { echo $i; unset($arr_ajax[$i-1]);}?></a>
                <?php } ?>
            <?php } ?>
            <?php if ($active != $count_pages) { ?>
                <a class="btn btn-xs btn-default" href="<?=$url_page.($active + 1)?>" title="Следующая страница">&gt;</a>
                <a class="btn btn-xs btn-default" href="<?=$url_page.$count_pages?>" title="Последняя страница">&gt;&gt;&gt;</a>
            <?php } ?>
        </div>
    <?php }
    $fl = 0;

    $other_pages = '<div id="other_pages" style="text-align: center; display: none">';
    if(isset($arr_ajax)){
        foreach($arr_ajax as $key => $value){
            $other_pages.="<a class='btn btn-xs btn-default' href='{$url_page}{$value}'>{$value}</a>";
        }
    }
    $other_pages.= '</div>';

    ?>
    <p><?=isset($other_pages)? $other_pages : ''?></p>
    <?php

}