    $(document).ready(function() {
        var table = $('.datatable-key-events').DataTable({
            keys: true
        });
     
        // Events
        var events = $('#key-events');
        table
            .on('key', function (e, datatable, key, cell, originalEvent) {
                events.append(JSON.stringify('Key press: '+key+' for cell '+cell.data()), '\n');
            })
            .on('key-focus', function (e, datatable, cell) {
                events.append(JSON.stringify('Cell focus: '+cell.data()), '\n');
            })
            .on('key-blur', function (e, datatable, cell) {
                events.append(JSON.stringify('Cell blur: '+cell.data()), '\n');
            });
} );