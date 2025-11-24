let TbDataAdmission;
//alert($('#SelLern').attr('key_year'));
TB_DataAdmission($('#SelLern').attr('key_year'));
$(document).on('change', '.SelTearEnoll', function() {
    //alert($(this).val());
    TB_DataAdmission($(this).val());
});

function TB_DataAdmission(Year) {
    TbDataAdmission = $('.TbDataAdmission').DataTable({
        destroy: true,
        "order": [
            [0, "asc"]
        ],
        'processing': true,
        "ajax": {
            url: "../../../../admin/academic/ConAdminReportResult/AdminReportEnrollData",
            "type": "POST",
            data: { "keyYear": Year }
        },
        'columnDefs': [
            { targets: [0, 1, 3, 4, 6, 7, 8], className: 'dt-center' },
            { width: '300px', targets: [2] }
        ],
        'columns': [{
                data: 'recruit_id',
                render: function(data, type, row) {
                    return '<a href="ID/' + data + '">ดูข้อมูล</a>';
                }
            },
            { data: 'recruit_id' },
            { data: 'recruit_Fullname' },
            {
                data: 'recruit_regLevel',
                render: function(data, type, row) {
                    return 'ม.' + data;
                }
            },
            { data: 'recruit_category' },
            { data: 'recruit_tpyeRoom' },
            {
                data: 'recruit_status',
                render: function(data, type, row) {
                    return (data == 'ผ่านการตรวจสอบ' ? '<span class="badge rounded-pill bg-success text-center">ผ่านการตรวจ</span>' : '<span class="badge rounded-pill bg-danger">ไม่ผ่านการตรวจ</span>');
                }
            },
            {
                data: 'stu_UpdateConfirm',
                render: function(data, type, row) {
                    return (data != '' ? '<span class="badge rounded-pill bg-success">รายงานตัวแล้ว</span>' : '<span class="badge rounded-pill bg-danger">รอรายงานตัว</span>');
                }
            },
            {
                data: 'recruit_statusSurrender',
                render: function(data, type, row) {
                    return (data == '' ? '<span class="badge rounded-pill bg-danger">ยังไม่มอบตัว</span>' : '<span class="badge rounded-pill bg-success">มอบตัวแล้ว</span>');
                }
            }
        ]
    });
}