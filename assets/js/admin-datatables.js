// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 0, "desc" ]]
    });
    $('#dataTable2').DataTable({
        "order": [[ 0, "desc" ]]
    });

    $('table.display').DataTable();
});