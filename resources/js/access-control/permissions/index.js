let table;
let currentModal;
$(document).ready(function () {
    getDataTable()
})
function getDataTable() {
    DataTable.types().forEach(type => {
        DataTable.type(type, 'detect', () => false);
    });
    table = new DataTable('#dataList', {
        iDisplayLength: 25,
        bServerSide: true,
        ajax: {
            url: $('#dataList').data('url'),
            data: function (d) {
                // console.log(d)
                // d.custom = $('#myInput').val();
                // etc
            },
        },
        // sAjaxDataProp: 'data',
        sServerMethod: "POST",
        processing: true,
        fixedHeader: true,
        language: {
            emptyTable: "Permissions not available.",
        },
        fnDrawCallback: function (oSettings) {
            $('[data-confirm]').each(function () {
                var me = $(this),
                    me_data = me.data('confirm');

                me_data = me_data.split("|");
                me.fireModal({
                    title: me_data[0],
                    body: me_data[1],
                    buttons: [{
                        text: me.data('confirm-text-yes') || 'Yes',
                        class: 'btn btn-danger btn-shadow',
                        handler: function (modal) {
                            currentModal = modal;
                            eval(me.data('confirm-yes'));
                        }
                    },
                    {
                        text: me.data('confirm-text-cancel') || 'Cancel',
                        class: 'btn btn-secondary',
                        handler: function (modal) {
                            $.destroyModal(modal);
                            eval(me.data('confirm-no'));
                        }
                    }
                    ]
                })
            });
        },
        initComplete: function (settings, data) {

        },
        responsive: {
            details: {
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.hidden ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td class="align-middle" style="border: none">' +
                            col.title +
                            ":" +
                            "</td> " +
                            '<td class="align-middle" style="border: none">' +
                            col.data +
                            "</td>" +
                            "</tr>" :
                            "";
                    }).join("");

                    return data ? $("<table />").append(data) : false;
                },
            },
        },
        columns: [{
            data: "DT_RowIndex",
            orderable: false,
            searchable: false,
            responsivePriority: 1,
            className: "align-middle",
        },
        {
            data: "display_name",
            name: "name",
            responsivePriority: 2,
            className: "align-middle",
        },
        {
            data: "created_at",
            responsivePriority: 2,
            className: "align-middle",
        },
        {
            data: "updated_at",
            responsivePriority: 2,
            className: "align-middle",
        },

        {
            data: "action",
            defaultContent: "-",
            responsivePriority: 6,
            className: "align-middle",
            // 'width': '250px'
        },
        ],
        order: [
            [1, "desc"]
        ], // sort order by modified date
    });

    $("div.dataTables_filter input").off("keyup.DT input.DT");

    var searchDelay = null;

    $("div.dataTables_filter input").on("keyup", function () {
        var search = $("div.dataTables_filter input").val();

        clearTimeout(searchDelay);

        searchDelay = setTimeout(function () {
            if (search != null) {
                table.search(search).draw();
            }
        }, 1000);
    });

    $.fn.dataTable.ext.errMode = "none";

    table.on("error.dt", function (e, settings, techNote, message) {
        // errorHandling(e, settings, techNote, message);
        return true;
    });
}

function deleteData(url) {
    $.ajax({
        url: url,
        cache: false,
        method: 'DELETE',
        headers: {
            Accept: 'application/json',
        },
        // data: data,
        beforeSend: () => {
            $('.btn-action').prop('disabled', true)
        },
        success: (data) => {
            try {
                $.destroyModal(currentModal);
                $('.btn-action').prop('disabled', false)
                if (data.success) {
                    iziToast.success({
                        title: 'Yeay 🤩!',
                        message: data.message,
                        position: 'topRight'
                    });
                    table.ajax.reload();
                }
                else {
                    iziToast.error({
                        title: 'Sorry 😔!',
                        message: data.message,
                        position: 'topRight'
                    });
                }
            }
            catch (error) {
                iziToast.error({
                    title: 'Sorry 😔!',
                    message: error,
                    position: 'topRight'
                });
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            var response = xhr.responseJSON;
            //response.message
            iziToast.error({
                title: 'Sorry 😔!',
                message: response.message,
                position: 'topRight'
            });
        }
    });
}
