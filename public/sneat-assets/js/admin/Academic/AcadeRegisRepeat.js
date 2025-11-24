// Example starter JavaScript for disabling form submissions if there are invalid fields

let tbRegisRepeatSubject;
TB_RegisRepeatSubject($('#schyear_year').val());
$(document).on('change', '#CheckYearRegisRepeat', function() {
    //alert($(this).val());
    TB_RegisRepeatSubject($(this).val());
});

function TB_RegisRepeatSubject(Year) {
    tbRegisRepeatSubject = $('#tbRegisRepeatSubject').DataTable({
        destroy: true,
        "order": [
            [7, "desc"]
        ],
        'processing': true,
        "ajax": {
            url: "../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatShow",
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
                    return '<a class="btn-sm app-btn-primary" href="Repeat/Detail/' + row.SubjectYear + '/' + row.SubjectCode + '/' + row.TeacherID +'">ลงทะเบียนเรียนซ้ำ</a>';
                }
            },
            {
                data: 'SumRepeat',
                render: function(data, type, row) {
                    return '<span class="badge bg-warning text-black-50">' +data +' คน </span>';
                }
            }
        ]
    });
}


// $('#SelectYearRegister').select2({
//     width: 300
// });

// $('#teacherregis').select2({
//     width: 300
// });
// $('#subjectregis').select2({
//     width: 300
// });
// $('#Room').select2({
//     width: 300
// });
// $('#RoomEdit').select2({
//     width: 300
// });

// $('#RepeatTeacher').select2({

// });

$(document).on("change", "#SelectYearRegister", function() {
    window.location.href = '../' + $(this).val();
});


$(document).on("change", "#Room", function() {
    filterByStudyLine($(this).val(),'All');
});

$(document).on("click", 'input[name="btnradio"]', function() {
    var selectedStudyLine = $(this).next('label').text(); // ดึงค่าจาก label ที่อยู่ถัดจาก input
     // ทำให้ปุ่ม checked
     $('input[name="btnradio"]').prop('checked', false); // ยกเลิก checked ทุกปุ่ม
     $(this).prop('checked', true); // ตั้ง checked ให้ปุ่มที่เลือก
     $('label.btn').removeClass('active'); // ลบคลาส active จากทุกปุ่ม
    $(this).next('label').addClass('active'); // เพิ่มคลาส active ให้ปุ่มที่เลือก
    
    filterByStudyLine($('#Room').val(),selectedStudyLine);
});

function filterByStudyLine(KeyRoom,KeyStudyLines) {
    $('#multiselect option').remove();

    $.post("../../../../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatSelect", { KeyRoom: KeyRoom, KeyStudyLines:KeyStudyLines }, function(data, status) {
        console.log(data);
        var SplitStudyLines = data[0].StudyLines.split("|");

        var html = `
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="btnradio99" autocomplete="off" checked>
            <label class="btn btn-outline-primary" style="border: 1px solid;" for="btnradio99">All</label>
        `;
                $.each(SplitStudyLines, function(index, value){
        html +=  `
            <input type="radio" class="btn-check" name="btnradio" id="btnradio${index}" autocomplete="off" ${value === KeyStudyLines ?"checked":""}>
            <label class="btn btn-outline-primary" style="border: 1px solid;" for="btnradio${index}">${value}</label>
        `;
                });
        html +=  `</div>`;

        $('#StudyLines').html(html);
        
        $.each(data, function(index, value) {
            //console.log(value);
            // trHTML = '<tr><td></td><td>' + value.StudentCode + '</td><td>' + value.StudentPrefix+value.StudentFirstName+' '+value.StudentLastName + '</td></tr>';
            trHTML = '<option value="' + value.StudentID + '">' + value.StudentClass + ' ' + value.StudentNumber.padStart(2, '0') + ' ' + value.StudentPrefix + value.StudentFirstName + ' ' + value.StudentLastName + '</option>';
            $('#multiselect').append(trHTML);
        });
    }, 'json');
}

$(document).on("change", "#RoomEdit", function() {

    $('#multiselect option').remove();

    $.post("../../../../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatSelect", { KeyRoom: $(this).val() }, function(data, status) {

        $.each(data, function(index, value) {
            //console.log(value);
            // trHTML = '<tr><td></td><td>' + value.StudentCode + '</td><td>' + value.StudentPrefix+value.StudentFirstName+' '+value.StudentLastName + '</td></tr>';
            trHTML = '<option value="' + value.StudentID + '">' + value.StudentClass + ' ' + value.StudentNumber.padStart(2, '0') + ' ' + value.StudentPrefix + value.StudentFirstName + ' ' + value.StudentLastName + '</option>';
            $('#multiselect').append(trHTML);
        });


    }, 'json');

});


    var lastCheckedCheckbox = null; // ตัวแปรเก็บ checkbox ล่าสุดที่ถูกติ๊ก
    // เมื่อ checkbox ถูกคลิก
    $('.SelRepeat').change(function() {
        var targetModal = $(this).data('bs-target'); // ได้ค่าจาก data-bs-target (เช่น .myModal)

        if ($(this).is(':checked')) {
            $('#StuID').val($(this).val()); // ได้ค่าจาก data-bs-target (เช่น .myModal)
           
            $(targetModal).modal('show'); // ใช้ jQuery เปิดโมเดล
            lastCheckedCheckbox = $(this); // เก็บ checkbox ที่ถูกติ๊ก
        } else {
            // ถ้า checkbox ไม่ถูกเลือกให้ปิดโมเดล
            lastCheckedCheckbox = $(this);
            Swal.fire({
                title: 'คำถาม?',
                text: "คุณต้องการถอนเรียนออกจากเรียนซ้ำหรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
              }).then((result) => {
                if (result.isConfirmed) {
              
                  $.ajax({
                    url: '../../../../../../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatAdd',
                    type: 'post',
                    data: {
                        DelStuID:$(this).val(),
                        YearRepeat: $('#YearRepeat').val(),
                        SubjectRepeat: $('#SubjectRepeat').val(),
                        DelStatus:"Del"
                    },
                    error: function() {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'ระบบผิดพลาด ลองใหม่อีกครั้ง!',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    },
                    success: function(data) {
                        console.log(data);
                        if (data) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'ถอนการลงทะเบียนเรียนซ้ำ สำเร็จ!',
                                showConfirmButton: false,
                                timer: 3000
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    location.reload(true);
                                }
                            });
            
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'warning',
                                title: 'คุณไม่ได้เลือกนักเรียนในการลงทะเบียนเรียนซ้ำ!',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
            
                    }
                });
                
                }else{
                    lastCheckedCheckbox.prop('checked', true);
                    lastCheckedCheckbox = null;
                }
              });

            $(targetModal).modal('hide'); // ใช้ jQuery ปิดโมเดล
        }
    });

     // เมื่อโมเดลถูกปิด (ทั้งจากปุ่มปิดหรือคลิกภายนอก)
     $('.myModal').on('hidden.bs.modal', function() {
        // รีเซ็ต checkbox ที่เกี่ยวข้องกับโมเดลนั้นๆ ที่ถูกเลือกเท่านั้น
        if (lastCheckedCheckbox) {
            lastCheckedCheckbox.prop('checked', false); // รีเซ็ต checkbox ที่ถูกติ๊ก
            lastCheckedCheckbox = null; // รีเซ็ตตัวแปรเพื่อไม่ให้เก็บค่าไว้
        }
        
    });



// $(document).on("submit", "#FormRegisRepeat", function(e) {
//     e.preventDefault();
//     $.ajax({
//         url: '../../../../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatInsert',
//         type: 'post',
//         data: $(this).serialize(),
//         error: function() {
//             Swal.fire({
//                 position: 'top-end',
//                 icon: 'error',
//                 title: 'นักเรียนในรายชื่อนี้ได้ลงทะเบียนวิชานี้ และปีนี้ไปแล้ว กรุณาเลือกและตรวจสอบใหม่',
//                 showConfirmButton: false,
//                 timer: 3000
//             })
//         },
//         success: function(data) {
//             Swal.fire({
//                 position: 'top-end',
//                 icon: 'success',
//                 title: 'บันทึกข้อมูลสำเร็จ',
//                 showConfirmButton: false,
//                 timer: 3000
//             })
//         }
//     });
// });

$(document).on("submit", "#FormRegisRepeatUpdate", function(e) {
    e.preventDefault();
    //console.log($(this).serialize());
    $.ajax({
        url: '../../../../../../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatAdd',
        type: 'post',
        data: $(this).serialize(),
        error: function() {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'ระบบผิดพลาด ลองใหม่อีกครั้ง!',
                showConfirmButton: false,
                timer: 3000
            })
        },
        success: function(data) {
            console.log(data);
            if (data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'ลงทะเบียนเรียนซ้ำ สำเร็จ!',
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        location.reload(true);
                    }
                });

            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'คุณไม่ได้เลือกนักเรียนในการลงทะเบียนเรียนซ้ำ!',
                    showConfirmButton: false,
                    timer: 3000
                })
            }

        }
    });
});

// $(document).on("submit", "#FormRegisRepeatDelete", function(e) {
//     e.preventDefault();
//     Swal.fire({
//         title: 'ต้องการถอนรายชื่อในการลงทะเบียนหรือไม่?',
//         text: 'เมื่อถอนรายชื่อการลงทะเบียนวิชานี้แล้ว คะแนนและรายชื่อนักเรียนในวิชานี้ จะถูกลบทั้งหมด',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 url: '../../../../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatDel',
//                 type: 'post',
//                 data: $(this).serialize(),
//                 error: function() {
//                     Swal.fire({
//                         position: 'top-end',
//                         icon: 'error',
//                         title: 'นักเรียนในรายชื่อนี้ได้ลงทะเบียนวิชานี้ และปีนี้ไปแล้ว กรุณาเลือกและตรวจสอบใหม่',
//                         showConfirmButton: false,
//                         timer: 3000
//                     })
//                 },
//                 success: function(data) {
//                     // console.log(data);
//                     Swal.fire({
//                         position: 'top-end',
//                         icon: 'success',
//                         title: 'ถอนการลงทะเบียนรายวิชาสำเร็จ',
//                         showConfirmButton: false,
//                         timer: 3000
//                     })
//                 }
//             });
//         }
//     })
// });


// $(document).on("click", ".ShowRegisRepeat", function() {

//     $('#tb_ShowRegisRepeat tbody tr').remove();

//     $.post("../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatShow", {
//         subid: $(this).attr('sub-id'),
//         teachid: $(this).attr('teach-id')
//     }, function(data, status) {
//         //console.log(data);
//         $('.ShowSubjectName').html("วิชา " + data[0].SubjectName + "<br>ครูผู้สอน " + data[0].pers_prefix + data[0].pers_firstname + ' ' + data[0].pers_lastname);
//         $.each(data, function(index, value) {
//             $('#tb_ShowRegisRepeat tbody').append('<tr class="DelTableRow"><td>' + value.StudentClass + '</td><td>' + value.StudentNumber + '</td><td>' + value.StudentCode + '</td><td>' + value.StudentPrefix + value.StudentFirstName + ' ' + value.StudentLastName + '</td></tr>');
//         });
//     }, 'json');

// });

// $(document).on("click", ".CancelRegisRepeat", function() {
//     console.log($(this).attr('key-teacher'));
//     Swal.fire({
//         title: 'ต้องการลบการลงทะเบียนหรือไม่?',
//         text: 'เมื่อลบการลงทะเบียนวิชานี้แล้ว คะแนนและรายชื่อนักเรียนในวิชานี้ จะถูกลบทั้งหมด',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $(this).parents('tr').remove();

//             $.post("../../../admin/academic/ConAdminRegisRepeat/AdminRegisRepeatCancel", {
//                 KeyTeacher: $(this).attr('key-teacher'),
//                 KeySubject: $(this).attr('key-subject')
//             }, function(data, status) {
//                 console.log(data);

//             });

//             Swal.fire(
//                 'ลบข้อมูลเรียบร้อย!',
//                 'Your data has been deleted.',
//                 'success'
//             )
//         }
//     })
// });