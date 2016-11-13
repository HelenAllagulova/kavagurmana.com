function prod_format(param) {
    switch (param){
        case 'grid_format':
            var elem_grid = document.getElementById('grid_format');
            elem_grid.style.display = 'block';
            var elem_gl_grid = document.getElementById('gl-grid');
            elem_gl_grid.style.color = '#b87d3b';
            var elem_list = document.getElementById('list_format');
            elem_list.style.display = 'none';
            var elem_gl_list = document.getElementById('gl-list');
            elem_gl_list.style.color = '#783c1a';
            break;
        case 'list_format':
            var elem_grid = document.getElementById('grid_format');
            elem_grid.style.display = 'none';
            var elem_gl_grid = document.getElementById('gl-grid');
            elem_gl_grid.style.color = '#783c1a';
            var elem_list = document.getElementById('list_format');
            elem_list.style.display = 'block';
            var elem_gl_list = document.getElementById('gl-list');
            elem_gl_list.style.color = '#b87d3b';
            break;
    }
}
