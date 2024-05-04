jQuery(document).ready(function ($) {
    $.ajax({
        url: ajaxobj.url,       // Fer una crida Ajax per obtenir les dades del servidor
        type: 'POST',
        data: {
            action: 'obtenir_dades',
            fn: 'obtenir_dades_callback'
        },
        dataType: 'JSON',
        success: function (dades) {
            // Les dades es retornen com a JSON, així que les parsegem
            dades = JSON.parse(JSON.stringify(dades));

            // Inicialitza la taula DataTables amb les dades obtingudes
            $('#llistaAlumnes').DataTable({
                data: dades,    // les dades rebudes
                columns: [      // les columnes que volem mostrar
                    { data: 'dni' },
                    { data: 'nom' },
                    { data: 'cognoms' },
                    { data: 'estudis' }
                ],

                language: { // traduccions
                    info: 'Mostrant pàgina _PAGE_ de _PAGES_',
                    infoEmpty: 'No hi ha dades',
                    infoFiltered: '(filtrat des de _MAX_ registres totals)',
                    lengthMenu: 'Mostrar _MENU_ registres per pàgina',
                    zeroRecords: 'No s\'ha trobat res',
                    paginate: {
                        previous: 'Pàgina anterior',
                        first: 'Primera pàgina',
                        next: 'Següent',
                        last: 'Darrera pàgina',
                        previous: 'Anterior'
                    },
                    search: 'Cercar'
                }
            });
        }
    });
});
