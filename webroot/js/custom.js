function paginator(swoole) {

    if ($(".sort-table").length > 0 && swoole === 0) {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            bInfo: false
        });

        $('.sort-table').DataTable({
            "bPaginate": true,
            "sPaginationType": "simple_numbers",
            "lengthMenu": [5, 10, 25],
            "pageLength": 10, // Set a default value for lengthMenu
            "iDisplayLength": 10,
        });
    }

    if ($(".sort-table-1").length > 0 && swoole === 1) {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            bInfo: false
        });

        $('.sort-table-1').DataTable({
            "bPaginate": true,
            "sPaginationType": "simple_numbers",
            "lengthMenu": [5, 10, 25],
            "pageLength": 10, // Set a default value for lengthMenu
            "iDisplayLength": 10,
        });
    }
}
