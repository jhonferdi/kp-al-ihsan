const datatable = (value) => { // Datatable
    if ($(value).length > 0) {
        $(value).DataTable({
            "bFilter": true,
            "paging": true,
            language: {
                search: '<i class="fas fa-search"></i>',
                searchPlaceholder: "Search"
            }
        });
    }
}

export default { datatable }