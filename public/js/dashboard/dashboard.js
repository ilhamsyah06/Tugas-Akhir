$(document).ready(function() {

    $('#dataTableMenguntungkan').DataTable({
        responsive: true,
        scrollX: true,
        'ajax': {
            'url': '/menguntungkan',
        },
        'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-1",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-danger' +
                        '">' + data + '</span>';
                }
            },{
                'targets': 1,
                'sClass': "col-md-4"
            },
            {
                'targets':2,
                'sClass': "col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }
        ]
    });

         $('#dataTableHabis').DataTable({
              responsive: true,
              scrollX: true,
              'ajax': {
                  'url': '/daftarhabis',
              },
              'columnDefs': [{
                      'targets': 0,
                      'sClass': "text-center col-md-1",
                      render: function (data, type, row, meta) {
                        return '<span style="font-size: 12px;" class="label label-danger' +
                            '">' + data + '</span>';
                    }
                  },{
                      'targets': 1,
                      'sClass': "col-md-4"
                  },
                  {
                      'targets': 2,
                      'sClass': "col-md-3",
                      render: function (data, type, row, meta) {
                        return '<span style="font-size: 12px;" class="label label-primary' +
                            '">' + data + '</span>';
                    }
                  }
              ]
          });
  
});

function reloadTableMenguntungkan() {
    var table = $('#dataTableMenguntungkan').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

function reloadTableHabis() {
    var table = $('#dataTableHabis').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}