function ajax(path, loyout_flag ='0', result){
    loyout_flag='loyout_flag='+loyout_flag;
    $.post(path, {loyout_flag:loyout_flag}, function(data){
        $("#"+result).text(data);
    });
}