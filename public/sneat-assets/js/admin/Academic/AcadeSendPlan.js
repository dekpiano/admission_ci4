$('.SelectTeacher').select2({
    theme: "bootstrap-5",
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
});
$('.SelectSubject').select2({
    theme: "bootstrap-5",
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
});

$('#TbSendPlan').DataTable();

$(document).on('change','#onoff_year', function() {
    $(this).closest('form').submit(); // ส่งฟอร์มเมื่อมีการเลือกตัวเลือกใหม่
  });

$('#FormUpdateSendPlan').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '../../../admin/academic/ConAdminCourse/UpdateSendPlanTeacher',
        type: "post",
        data: new FormData(this), //this is formData
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        beforeSend: function() {
            $('.BtnUpdateSendPlan').attr("disabled", "disabled");
        },
        success: function(data) {
            console.log(data);
            if (data > 0) {
                $('#editteacher').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'บันทึกข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        //window.location.reload();
                    }
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'รายวิชานี้ ได้เพิ่มลงระบบแล้ว',
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.reload();
                    }
                })

            }
        }
    });
});

$('#FormSettingSendPlan').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '../../../admin/academic/ConAdminCourse/UpdateSettingSendPlan',
        type: "post",
        data: new FormData(this), //this is formData
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        beforeSend: function() {
            // $('.BtnUpdateSendPlan').attr("disabled", "disabled");
        },
        success: function(data) {
            console.log(data);
            if (data > 0) {
                $('#editteacher').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'อัพเดตข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.reload();
                    }
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'รายวิชานี้ ได้เพิ่มลงระบบแล้ว',
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.reload();
                    }
                })

            }
        }
    });
});

$(document).on("click", ".DeleteTeach", function() {
    //alert($(this).attr('delplancode'));
    Swal.fire({
            title: 'ต้องการลบข้อมูลหรือไม่?',
            text: "ข้อมูลจะถูกลบ พร้อมด้วยไฟล์งานทั้งหมด!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ลบข้อมูล!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("../../../admin/academic/ConAdminCourse/DeleteSettingSendPlan", {
                    PlanCode: $(this).attr('delplancode'),
                    PlanTerm: $(this).attr('delplanterm'),
                    PlanYear: $(this).attr('delplanyear'),
                    PlanName: $(this).attr('delplanname')
                }, function(data, status) {
                    console.log(data);
                    Swal.fire({
                        title: "ลบข้อมูลเรียบร้อย!",
                        text: "^_^",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                });
            }
        })
        // $.post("../../ConTeacherCourse/setting_teacher_eidt", { PlanCode: $(this).attr('PlanCode') }, function(data, status) {
        //     $('#up_seplan_coursecode').val(data[0].seplan_coursecode);
        //     $('#up_seplan_namesubject').val(data[0].seplan_namesubject);
        //     $('#up_seplan_gradelevel').val(data[0].seplan_gradelevel);
        //     $('#up_seplan_typesubject').val(data[0].seplan_typesubject);
        //     $('#up_seplan_usersend').val(data[0].seplan_usersend);
        //     $('#up_seplan_year').val(data[0].seplan_year);
        //     $('#up_seplan_term').val(data[0].seplan_term);
        // }, "json");
});

$(document).on('click', '.EditTeach', function() {
    console.log($(this).attr('PlanYear'));
    $('#editteacher').modal('show');

    $.post("../../../admin/academic/ConAdminCourse/EditSettingSendPlan", {
        PlanCode: $(this).attr('PlanCode'),
        PlanYear: $(this).attr('PlanYear'),
        PlanTerm: $(this).attr('PlanTerm')
    }, function(data, status) {
        $('#up_seplan_coursecode').val(data[0].seplan_coursecode);
        $('#up_seplan_namesubject').val(data[0].seplan_namesubject);
        $('#up_seplan_gradelevel').val(data[0].seplan_gradelevel);
        $('#up_seplan_typesubject').val(data[0].seplan_typesubject);
        $('#up_seplan_usersend').val(data[0].seplan_usersend);
        $('#up_seplan_year').val(data[0].seplan_year);
        $('#up_seplan_term').val(data[0].seplan_term);
    }, "json");

});

$(document).on('submit', '#FromUpdateTeacher', function(e) {
    e.preventDefault();
    $.ajax({
        url: '../../../admin/academic/ConAdminCourse/UpdateSettingSendPlanTeacher',
        type: "post",
        data: new FormData(this), //this is formData
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function(data) {
            console.log(data);
            if (data > 0) {
                $('#editteacher').modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'แก้ไขข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.reload();
                    }
                })
            } else {
                window.location.reload();
            }
        }
    });
});