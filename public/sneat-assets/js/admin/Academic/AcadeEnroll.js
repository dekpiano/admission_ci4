// Example starter JavaScript for disabling form submissions if there are invalid fields

let tbErollSubject;
TB_ErollSubject($('#schyear_year').val());
$(document).on('change', '#CheckYearEnroll', function() {
    //alert($(this).val());
    TB_ErollSubject($(this).val());
});

function TB_ErollSubject(Year) {
    tbErollSubject = $('#tbErollSubject').DataTable({
        destroy: true,
        "order": [
            [1, "asc"]
        ],
        'processing': true,
        "ajax": {
            url: "../../../admin/academic/ConAdminEnroll/AdminEnrollSubject",
            "type": "POST",
            data: { "keyYear": Year }
        },
        'columns': [
            { data: 'SubjectYear' },
            { data: 'SubjectCode' },
            { data: 'SubjectName' },
            { data: 'FirstGroup' },
            { data: 'SubjectClass' },
            {
                data: 'TeacherName',
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: 'SubjectID',
                render: function(data, type, row) {
                    return '<span class="badge bg-success rounded-pill ShowEnroll" data-bs-toggle="modal" data-bs-target="#staticBackdrop" sub-id="' + row.SubjectID + '" teach-id="' + row.TeacherID + '" year-id="' + row.SubjectYear + '">ลงทะเบียนแล้ว</span>';
                }
            },
            {
                data: 'SubjectID',
                render: function(data, type, row) {
                    return '<a href="../../../Admin/Acade/Registration/Enroll/Edit/' + row.SubjectID + '/' + row.TeacherID + '" class="btn btn-success btn-sm text-white">เพิ่มรายชื่อ</a><br>' +
                        ' <a href="../../../Admin/Acade/Registration/Enroll/Delete/' + row.SubjectID + '/' + row.TeacherID + '" class="btn btn-warning btn-sm">ถอนราชื่อ / เปลี่ยนครูสอน</a><br>' +
                        ' <a href="#" class="btn btn-danger btn-sm text-white CancelEnroll" key-subject="' + row.SubjectID + '" key-teacher="' + row.TeacherID + '">ลบลงทะเบียน</a>';
                }
            }
        ]
    });
}

$(document).on("change", "#subjectregis", function() {
    var IDsubjectregis = $(this).val();
    var IDSelectYearRegister = $('#SelectYearRegister').val();

    $.post('../../../../../../admin/academic/ConAdminEnroll/AdminEnrollChangeSubjectToTeacher',{
        Keysubjectregis:IDsubjectregis,
        KeySelectYearRegister:IDSelectYearRegister
    },function(data, status){
        //console.log(data);
        $('#teacherregis').val(data);
        $('#teacherregis').trigger('change');
    });
  
});


$('#SelectYearRegister').select2({
    theme: 'bootstrap-5',
    width: 300
});

$('#teacherregis').select2({
    theme: 'bootstrap-5',
    width: 300
});
$('#subjectregis').select2({
    theme: 'bootstrap-5',
    width: 300
});
$('#Room').select2({
    theme: 'bootstrap-5',
    width: 300
});
$('#RoomEdit').select2({
    theme: 'bootstrap-5',
    width: 300
});




$(document).on("change", "#SelectYearRegister", function() {
    window.location.href = '../' + $(this).val();
});


$(document).on("change", ".teacherregis", function() {
    //alert($(this).val());
    let teacherregis = $(this).val();
    let subjectregisupdate = $('#subjectregisupdate').val();
    let SubjectYear = $('#SubjectYearregisupdate').val();
    let SubjectCode = $('#SubjectCode').val();

    Swal.fire({
        title: "แจ้งเตือน?",
        text: "คุณต้องการเปลี่ยนครูผู้สอนหรือไม่!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "ใช่ ฉันต้องการ!"
      }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../../../../../admin/academic/ConAdminEnroll/AdminEnrollChangeTeacher", {
                KeyTeacher: teacherregis,
                KeySubjectYear: SubjectYear,
                KeySubjectID: subjectregisupdate
            }, function(data, status) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'เปลี่ยนครูผู้สอนใหม่แล้ว!',
                    showConfirmButton: false,
                    timer: 3000
                })
                setTimeout(
                    function() 
                    {
                        window.location.href = '../' + subjectregisupdate + '/' + teacherregis;
                    }, 2000);
                
            });
        }
      });

    
});

$(document).on("change", "#Room", function() {

    $('#multiselect option').remove();

    $.post("../../../../../../admin/academic/ConAdminEnroll/AdminEnrollSelect", { KeyRoom: $(this).val() }, function(data, status) {
        //console.log(data);
        $.each(data, function(index, value) {
            //console.log(index);
            // trHTML = '<tr><td></td><td>' + value.StudentCode + '</td><td>' + value.StudentPrefix+value.StudentFirstName+' '+value.StudentLastName + '</td></tr>';
            // trHTML = '<option value="' + value.StudentID + '">' + value.StudentClass + ' ' + value.StudentNumber.padStart(2, '0') + ' ' + value.StudentPrefix + value.StudentFirstName + ' ' + value.StudentLastName + '</option>';
            // $('#multiselect').append(trHTML);
        });
    }, 'json');

});

$(document).on("change", "#RoomEdit", function() {

    $('#multiselect option').remove();

    $.post("../../../../../../admin/academic/ConAdminEnroll/AdminEnrollSelect", { KeyRoom: $(this).val() }, function(data, status) {

        $.each(data, function(index, value) {
            //console.log(value);
            // trHTML = '<tr><td></td><td>' + value.StudentCode + '</td><td>' + value.StudentPrefix+value.StudentFirstName+' '+value.StudentLastName + '</td></tr>';
            // trHTML = '<option value="' + value.StudentID + '">' + value.StudentClass + ' ' + value.StudentNumber.padStart(2, '0') + ' ' + value.StudentPrefix + value.StudentFirstName + ' ' + value.StudentLastName + '</option>';
            // $('#multiselect').append(trHTML);
        });


    }, 'json');

});


$(document).on("submit", "#FormEnroll", function(e) {
    e.preventDefault();
    $.ajax({
        url: '../../../../../../admin/academic/ConAdminEnroll/AdminEnrollInsert',
        type: 'post',
        data: $(this).serialize(),
        error: function() {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'นักเรียนในรายชื่อนี้ได้ลงทะเบียนวิชานี้ และปีนี้ไปแล้ว กรุณาเลือกและตรวจสอบใหม่',
                showConfirmButton: false,
                timer: 3000
            })
        },
        success: function(data) {
      
              Swal.fire({
                title: "แจ้งเตือน?",
                text: "บันทึกข้อมูลสำเร็จ!",
                icon: "success",
                showCancelButton: true,
              }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
              });

        }
    });
});

$(document).on("submit", "#FormEnrollUpdate", function(e) {
    e.preventDefault();
    $.ajax({
        url: '../../../../../../admin/academic/ConAdminEnroll/AdminEnrollUpdate',
        type: 'post',
        data: $(this).serialize(),
        error: function() {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'นักเรียนในรายชื่อนี้ได้ลงทะเบียนวิชานี้ และปีนี้ไปแล้ว กรุณาเลือกและตรวจสอบใหม่',
                showConfirmButton: false,
                timer: 3000
            })
        },
        success: function(data) {
            // console.log(data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'เพิ่งรายชื่อนักเรียนสำเร็จ',
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
});

$(document).on("submit", "#FormEnrollDelete", function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'ต้องการถอนรายชื่อในการลงทะเบียนหรือไม่?',
        text: 'เมื่อถอนรายชื่อการลงทะเบียนวิชานี้แล้ว คะแนนและรายชื่อนักเรียนในวิชานี้ จะถูกลบทั้งหมด',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../../../../../admin/academic/ConAdminEnroll/AdminEnrollDel',
                type: 'post',
                data: $(this).serialize(),
                error: function() {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'นักเรียนในรายชื่อนี้ได้ลงทะเบียนวิชานี้ และปีนี้ไปแล้ว กรุณาเลือกและตรวจสอบใหม่',
                        showConfirmButton: false,
                        timer: 3000
                    })
                },
                success: function(data) {
                    // console.log(data);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'ถอนการลงทะเบียนรายวิชาสำเร็จ',
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            });
        }
    })
});


$(document).on("click", ".ShowEnroll", function() {

    $('#tb_ShowEnroll tbody tr').remove();

    $.post("../../../admin/academic/ConAdminEnroll/AdminEnrollShow", {
        subid: $(this).attr('sub-id'),
        teachid: $(this).attr('teach-id'),
        yearid: $(this).attr('year-id')
    }, function(data, status) {
        //console.log(data);
        $('.ShowSubjectName').html("วิชา " + data[0].SubjectName + "<br>ครูผู้สอน " + data[0].pers_prefix + data[0].pers_firstname + ' ' + data[0].pers_lastname);
        $.each(data, function(index, value) {
            $('#tb_ShowEnroll tbody').append('<tr class="DelTableRow"><td>' + value.StudentClass + '</td><td>' + value.StudentNumber + '</td><td>' + value.StudentCode + '</td><td>' + value.StudentPrefix + value.StudentFirstName + ' ' + value.StudentLastName + '</td></tr>');
        });
    }, 'json');

});

$(document).on("click", ".CancelEnroll", function() {
    console.log($(this).attr('key-teacher'));
    Swal.fire({
        title: 'ต้องการลบการลงทะเบียนหรือไม่?',
        text: 'เมื่อลบการลงทะเบียนวิชานี้แล้ว คะแนนและรายชื่อนักเรียนในวิชานี้ จะถูกลบทั้งหมด',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.isConfirmed) {
            $(this).parents('tr').remove();

            $.post("../../../admin/academic/ConAdminEnroll/AdminEnrollCancel", {
                KeyTeacher: $(this).attr('key-teacher'),
                KeySubject: $(this).attr('key-subject')
            }, function(data, status) {
                console.log(data);

            });

            Swal.fire(
                'ลบข้อมูลเรียบร้อย!',
                'Your data has been deleted.',
                'success'
            )
        }
    })
});