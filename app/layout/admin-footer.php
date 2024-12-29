<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<!-- Bootstrap and other scripts -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../public/js/sb-admin-2.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('.table').DataTable({
            dom: 'Bfrtip',
            //scrollY: '50vh', // Enable vertical scrolling with a specific height
            scrollX: '100%', // Enable horizontal scrolling
            //scrollCollapse: true, // Allow the table to shrink when fewer rows exist
            responsive: true, // Enable responsive layout
            autoWidth: false, // Disable auto-width to prevent layout issues
            order: [], // Disable default sorting
            buttons: [
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(.exclude)',
                        modifier: {
                            page: 'all'
                        }
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(.exclude)',
                        modifier: {
                            page: 'all'
                        }
                    },
                    customize: function(doc) {
                        // Ensure all table columns expand to fit the entire page
                        doc.styles.tableHeader.alignment = 'left';
                        doc.content[1].table.widths = '*'.repeat(doc.content[1].table.body[0].length).split('');
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(.exclude)'
                    }
                }
            ],
            columnDefs: [
                { targets: "_all", className: "text-wrap" } // Enable text wrapping for all columns
            ]
        });
    });
</script>


</body>

</html>