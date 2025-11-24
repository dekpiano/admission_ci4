let tablel_Subject;
TB_Subject($('#CheckYearNow').val());
$(document).on('change', '.SelectSubject', function() {
    //alert($(this).val());
    TB_Subject($(this).val());
});


function TB_Subject(Year) {
    tablel_Subject = $('#tbSubject').DataTable({
        destroy: true,
        "order": [
            [1, "asc"]
        ],
        'processing': true,
        "ajax": {
            url: "../../../admin/academic/ConAdminRegisterSubject/AdminRegisterSubjectSelect",
            "type": "POST",
            data: { "keyYear": Year }
        },
        'columns': [
            { data: 'SubjectYear' },
            { data: 'SubjectCode' },
            { data: 'SubjectName' },
            { data: 'FirstGroup' },
            { data: 'SubjectClass' },
            { data: 'SubjectYear' },
            {
                data: 'SubjectID',
                render: function(data, type, row) {
                    return '<a href="#" idSbuj="' + data + '" class="btn btn-warning btn-sm EditSubject">แก้ไข</a> | <a href="#" idSbuj="' + data + '" class="btn btn-danger btn-sm delete_subject text-white">ลบ</a>';

                }
            }
        ]
    });
}


$(document).on('click', '.EditSubject', function() {
    $('#ModalUpdateSubject').modal('show');
    $.ajax({
        url: '../../../admin/academic/ConAdminRegisterSubject/AdminRegisterSubjectEdit',
        type: 'post',
        data: { KeySubj: $(this).attr('idSbuj') },
        dataType: 'json',
        error: function() {
            alert('Something is wrong');
        },
        success: function(data) {
            //console.log(data[0]);
            $('#Up_SubjectYear').val(data[0].SubjectYear);
            $('#Up_SubjectClass').val(data[0].SubjectClass);
            $('#Up_SubjectCode').val(data[0].SubjectCode);
            $('#Up_SubjectName').val(data[0].SubjectName);
            $('#Up_SubjectUnit').val(data[0].SubjectUnit);
            $('#Up_SubjectHour').val(data[0].SubjectHour);
            $('#Up_SubjectType').val(data[0].SubjectType);
            $('#Up_FirstGroup').val(data[0].FirstGroup);
            $('#Up_SecondGroup').val(data[0].SecondGroup);
            $('#Up_SubjectID').val(data[0].SubjectID);

        }
    });
});


$(document).on('submit', '#form-subject', function(e) {
    e.preventDefault();
    // console.log($(this).serialize());

    $.ajax({
        url: '../../../admin/academic/ConAdminRegisterSubject/AdminRegisterSubjectInsert',
        type: 'post',
        data: $(this).serialize(),
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        },
        success: function(data) {
            console.log(data);
            if (data > 0) {
                $('#form-subject')[0].reset();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'บันทึกข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 3000
                })
                tablel_Subject.ajax.reload();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'ข้อมูลรายวิชานี้ ได้ลงทะเบียนในภาคเรียนนี้แล้ว',
                    showConfirmButton: false,
                    timer: 5000
                })
            }

        }
    });
});

$(document).on('submit', '#form-update-subject', function(e) {
    e.preventDefault();
    //console.log($(this).serialize());

    $.ajax({
        url: '../../../admin/academic/ConAdminRegisterSubject/AdminRegisterSubjectUpdate',
        type: 'post',
        data: $(this).serialize(),
        error: function() {
            alert('Something is wrong');
        },
        success: function(data) {
            $('#ModalUpdateSubject').modal('hide');
            if (data > 0) {
                $('#form-update-subject')[0].reset();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'แก้ไขข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 3000
                })
                tablel_Subject.ajax.reload();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'ข้อมูลรายวิชานี้ ได้ลงทะเบียนในภาคเรียนนี้แล้ว',
                    showConfirmButton: false,
                    timer: 5000
                })
            }

        }
    });
});


$(document).on('click', '.delete_subject', function() {
    var id = $(this).attr("idSbuj");

    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลลข้อมูลหรือไม่!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../../admin/academic/ConAdminRegisterSubject/AdminRegisterSubjectDelete/' + id,
                type: 'DELETE',
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                },
                success: function(data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'ลบข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        timer: 1000
                    })
                    tablel_Subject.ajax.reload();
                }
            });
        }
    })
});

$(document).on('change', '#SubjectUnit', function() {
    console.log($(this).val());
    $('#SubjectHour option').removeAttr('selected');
    if ($(this).val() == 0.5) {
        $('#SubjectHour option[value=20]').attr('selected', 'selected');
    } else if ($(this).val() == 1.0) {
        $('#SubjectHour option[value=40]').attr('selected', 'selected');
    } else if ($(this).val() == 1.0) {
        $('#SubjectHour option[value=40]').attr('selected', 'selected');
    } else if ($(this).val() == 1.5) {
        $('#SubjectHour option[value=60]').attr('selected', 'selected');
    } else if ($(this).val() == 2.0) {
        $('#SubjectHour option[value=80]').attr('selected', 'selected');
    }
});


let subjects = []; // เก็บข้อมูลที่โหลดมา
let api_url = "https://sheets.googleapis.com/v4/spreadsheets/1RbMq3N-4itgCJCnnc8TsZ8k4XZNlEz_kOLkKBvEsajQ/values/main1?key=AIzaSyATVgVTJM7ou3XdyBH-FsxVd9uj_A32tCc";

function loadSubjects() {
    $.getJSON(api_url, function (data) {
        let rows = data.values;
        rows.shift(); // ลบแถวหัวตาราง (Header)

        subjects = rows.map(row => ({
            id: row[0],   // รหัสวิชา
            text: row[0], // แสดง รหัสวิชา + ชื่อวิชา
            SubjectName: row[1], // ชื่อวิชา
            SubjectUnit: row[2], // หน่วยกิต
            SubjectHour: row[3], // ชั่วโมง
            SubjectType: row[4],  // ประเภทวิชา
            FirstGroup: row[5],
            SecondGroup: row[6],
            SubjectClass: row[7] 
        }));

       // console.log("โหลดข้อมูลสำเร็จ", subjects);
        
        // อัปเดต Select2
        $("#SubjectCode").select2({
            placeholder: "พิมพ์รหัสวิชาเพื่อค้นหา",
            allowClear: true,
            minimumInputLength: 2,
            data: subjects,
            matcher: function (params, data) {
                if (!params.term || !data.text) return null;
                if (data.text.toLowerCase().includes(params.term.toLowerCase())) {
                    return data;
                }
                return null;
            }
        });
    });
}

loadSubjects(); // โหลดข้อมูลตอนเริ่มต้น


// เมื่อเลือกค่า
$("#SubjectCode").on("select2:select", function (e) {
    let selected = e.params.data;
    $("#SubjectName").val(selected.SubjectName);
    $("#SubjectUnit").val(selected.SubjectUnit);
    $("#SubjectHour").val(selected.SubjectHour);
    $("#SubjectType").val(selected.SubjectType);
    $("#FirstGroup").val(selected.FirstGroup);
    $("#SecondGroup").val(selected.SecondGroup);
    $("#SubjectClass").val(selected.SubjectClass);
});