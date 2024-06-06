// ============================== My Scripts ==============================
/*

1. Data Table Default
2. Data Table Export File

*/



// ============================== 1. Data Table Default ==============================
new DataTable('#MyTable', {
    responsive: true
})

// ============================== 2. Data Table Export File ==============================
new DataTable('#MyTableExport', {
    layout: {
        topStart: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        },
        responsive: true
    }
});