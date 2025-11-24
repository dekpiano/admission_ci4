//  ผลการเรียน
Tb_Repeat = $('#Tb_Repeat').DataTable({
    "order": [
        [1, "asc"]
    ],
});
$(document).on("change", "#checkOnOffDoGrade", function () {
    //alert($(this).prop('checked'));
    $.post("../../../admin/academic/ConAdminAcademinResult/CheckOnOffDoGrade", {
        check: $(this).prop('checked')
    },
        function (data, status) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'เปลี่ยนข้อมูลแสดงผลการเรียนของนักเรียนสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            })
            //alert("Data: " + data + "\nStatus: " + status);
        });
})

$(document).on("change", ".checkOnOffLevel", function () {    
    
    
    let selectedLevels = [];
        $(".checkOnOffLevel:checked").each(function() {
            selectedLevels.push($(this).val());  // เก็บเฉพาะค่าที่ checked
        });
        let dataString = selectedLevels.join("|");
        console.log(dataString);

    $.post("../../../admin/academic/ConAdminAcademinResult/OnOffLevel", {
        data: dataString
    },
        function (data, status) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'กำหนดการแสดงผลการเรียนในระดับชั้นเรียบร้อยแล้ว',
                showConfirmButton: false,
                timer: 2000
            })
            //alert("Data: " + data + "\nStatus: " + status);
        });
})

$(document).on("change", "#OpenYear", function () {
    //alert($(this).val());
    $.post("../../../admin/academic/ConAdminAcademinResult/CheckOnOffOpenYear", {
        check: $(this).val()
    },
        function (data, status) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'เปลี่ยนปีที่แสดงข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            })
            //alert("Data: " + data + "\nStatus: " + status);
        });
})

$(document).on('submit', '#AddClassRoom', function (e) {
    e.preventDefault();
    var formadd = $('#AddClassRoom').serialize();
    $.ajax({
        type: 'post',
        url: "../../../admin/academic/ConAdminClassRoom/AddClassRoom",
        data: formadd,
        beforeSend: function () {
            console.log("กำลังโหลด");
        },
        complete: function () {
            //console.log("คือไรว่ะ");
        },
        success: function (result) {
            $('#myModal').modal('hide');
            console.log(result);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'บันทึกข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location.reload();
                }
            })

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }
    });

});

$(document).ready(function () {

    var ta = $('#tb-classroom').DataTable({
        "order": [
            [0, "desc"],
            [1, "asc"]
        ]
    });



    var ta = $('#ReportExtraSubject').DataTable({
        "order": [
            [3, "asc"],
            [4, "asc"]
        ]
    });


    // บทบาทในวิชาการ
    $(document).on("change", "#set_executive", function () {

        $.post("../../../admin/academic/ConAdminSettingAdminRoles/AcademicSettingManager", { TeachID: $(this).val() }, function (data, status) {
            if (data == 1) {
                alertify.success('เลือก ผอ.โรงเรียน สำเร็จ');
            } else {
                alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
            }
        });
    });

    new SlimSelect({
        select: '#set_executive'
    })

    $(document).on("change", "#set_deputy", function () {
        $.post("../../../admin/academic/ConAdminSettingAdminRoles/AcademicSettingDeputy", { TeachID: $(this).val() }, function (data, status) {
            if (data == 1) {
                alertify.success('เลือก รองฯ วิชการ สำเร็จ');
            } else {
                alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
            }
        });
    });

    new SlimSelect({
        select: '#set_deputy'
    })

    $(document).on("change", "#set_leader", function () {
        $.post("../../../admin/academic/ConAdminSettingAdminRoles/AcademicSettingLeader", { TeachID: $(this).val() }, function (data, status) {
            if (data == 1) {
                alertify.success('เลือกหัวหน้างานสำเร็จ');
            } else {
                alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
            }
        });
    });

    new SlimSelect({
        select: '#set_leader'
    })

    $(document).on("change", ".set_admin", function () {
        //alert($(this).attr('admin-id'));
        $.post("../../../admin/academic/ConAdminSettingAdminRoles/AcademicSettingAdmin", {
            TeachID: $(this).val(),
            AdminID: $(this).attr('admin-id')
        }, function (data, status) {
            if (data == 1) {
                alertify.success('เลือก เจ้าหน้าที่วิชาการ สำเร็จ');
            } else {
                alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
            }
        });
    });

    // for (let index = 3; index <= 8; index++) {
    //     new SlimSelect({
    //         select: '#set_admin'+index
    //     })
    // }






    // File type validation
    // $("#seplan_file").change(function() {
    //     var file = this.files[0];
    //     var fileType = file.type;
    //     var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
    //     if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))){
    //         alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
    //         $("#file").val('');
    //         return false;
    //     }
    // });








    // ----------------------------วิชาเพิ่มติม-----------------------------------
    $('#ModalAddExtraSubject').on('click', function () {
        $('#myModal').modal('show');
        $("#UpdateExtraSubject").attr('id', "AddExtraSubject");
        $("#AddExtraSubject")[0].reset();
    });

    const slim = new SlimSelect({
        select: '.multiple'
    })
    const slimTeacher = new SlimSelect({
        select: '.single'
    })

    $('.ModalExtraSubject').on('click', function (e) {
        e.preventDefault();
        $('#myModal').modal('show');
        $('.extra_grade_level').prop('checked', false);
        $.ajax({
            type: 'POST',
            url: "../../admin/ConAdminExtraSubject/EditExtraSubject",
            data: { Extraid: $(this).attr('Extraid') },
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                //console.log(data[0].extra_year);
                $('#extra_id').val(data[0].extra_id);
                $('#extra_year').val(data[0].extra_year);
                $('#extra_term').val(data[0].extra_term);
                $('#extra_key_room').val(data[0].extra_key_room);
                ''
                $('#extra_course_code').val(data[0].extra_course_code);
                $('#extra_course_name').val(data[0].extra_course_name);
                $('#extra_course_teacher').val(data[0].extra_course_teacher);
                $('#extra_number_students').val(data[0].extra_number_students);
                $('#extra_comment').val(data[0].extra_comment);
                var n = data[0].extra_grade_level.split('|');
                slim.set(n);
                slimTeacher.set(data[0].extra_course_teacher);

                $("#AddExtraSubject").attr('id', "UpdateExtraSubject");
            },
            error: function (xhr) {
                alert("Error occured.please try again");
                console.log(xhr.statusText + xhr.responseText);
            }
        });
    });

    $(document).on("submit", "#AddExtraSubject", function (e) {
        e.preventDefault();
        var formadd = $('#AddExtraSubject').serialize();
        $.ajax({
            type: 'POST',
            url: "../../admin/ConAdminExtraSubject/AddExtraSubject",
            data: formadd,
            beforeSend: function () {

            },
            success: function (data) {
                if (data == 1) {
                    Swal.fire(
                        'แจ้งเตือน',
                        'คุณเพิ่มวิชาเพิ่มเติมเรียบร้อย',
                        'success'
                    )
                    Swal.fire({
                        title: 'แจ้งเตือน',
                        text: "คุณเพิ่มวิชาเพิ่มเติมเรียบร้อย",
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })
                }
            },
            error: function (xhr) {
                alert("Error occured.please try again");
                console.log(xhr.statusText + xhr.responseText);
            }
        });
    });

    $(document).on("submit", "#UpdateExtraSubject", function (e) {
        e.preventDefault();
        var formadd = $('#UpdateExtraSubject').serialize();

        $.ajax({
            type: 'POST',
            url: "../../admin/ConAdminExtraSubject/UpdateExtraSubject",
            data: formadd,
            beforeSend: function () {

            },
            success: function (data) {
                console.log(data);
                if (data == 1) {

                    Swal.fire(
                        'แจ้งเตือน',
                        'คุณเพิ่มวิชาเพิ่มเติมเรียบร้อย',
                        'success'
                    )
                    Swal.fire({
                        title: 'แจ้งเตือน',
                        text: "คุณแก้ไขวิชาเพิ่มเติมเรียบร้อย",
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })
                }
            },
            error: function (xhr) {
                alert("Error occured.please try again");
                console.log(xhr.statusText + xhr.responseText);
            }
        });
    });

});

$(document).on("change", "#extra_setting_onoff", function () {
    $.post("../../admin/ConAdminExtraSubject/ExtraSettingOnoff", { onoff: $(this).prop('checked') }, function (data, status) {
        if (data == 1) {
            alertify.success('เปลี่ยนแปลงข้อมูลเปิด - ปิดระบบสำเร็จ');
        } else {
            alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
        }
    });
});

$(document).on("change", "#extra_setting_term", function () {
    $.post("../../admin/ConAdminExtraSubject/ExtraSettingTerm", { Term: $(this).val() }, function (data, status) {
        if (data == 0) {
            alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
        } else {
            alertify.success('คุณเปลี่ยนภาคเรียน ' + data + ' เรียบร้อย');
        }
    });
});

$(document).on("change", "#extra_setting_year", function () {
    $.post("../../admin/ConAdminExtraSubject/ExtraSettingYear", { Year: $(this).val() }, function (data, status) {
        if (data == 0) {
            alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
        } else {
            alertify.success('คุณเปลี่ยนภาคเรียน ' + data + ' เรียบร้อย');
        }
    });
});

$(document).on("change", "#extra_setting_datestart", function () {
    $.post("../../admin/ConAdminExtraSubject/ExtraSettingDateStart", { DateStart: $(this).val() }, function (data, status) {
        console.log(data);
        if (data == 0) {
            alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
        } else {
            alertify.success('คุณเปลี่ยนวันเริ่มต้นเป็น ' + data + ' เรียบร้อย');
        }
    });
});

$(document).on("change", "#extra_setting_dateend", function () {
    $.post("../../admin/ConAdminExtraSubject/ExtraSettingDateEnd", { DateEnd: $(this).val() }, function (data, status) {
        console.log(data);
        if (data == 0) {
            alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
        } else {
            alertify.success('คุณเปลี่ยนวันสิ้นสุดเป็น ' + data + ' เรียบร้อย');
        }
    });
});


//----------------- ห้องเรียนออนไลน์ --------------------------------

$(document).on("click", ".ShowAddRoomOnline", function () {
    $('#AddRoomOnline').modal('show');
    $('#FormRoomOnline').addClass('Add_RoomOnline');
    $('#FormRoomOnline').removeClass('Update_RoomOnline');
    $('#FormRoomOnline')[0].reset();
});

var classlevel = new SlimSelect({
    select: '#roomon_classlevel'
})

$(document).on("click", ".ShowEditRoomOnline", function () {
    $('#AddRoomOnline').modal('show');
    $('#FormRoomOnline').addClass('Update_RoomOnline');
    $('#FormRoomOnline').removeClass('Add_RoomOnline');
    //alert($(this).attr('roomid'));
    $.post("../../admin/ConAdminRoomOnline/EditRoomOnline", { roomid: $(this).attr('roomid') }, function (data, status) {
        // console.log(data[0].roomon_id);
        $('#roomon_id').val(data[0].roomon_id);
        $('#roomon_coursecode').val(data[0].roomon_coursecode);
        $('#roomon_coursename').val(data[0].roomon_coursename);
        $('#roomon_classlevel').val(data[0].roomon_classlevel);
        $('#roomon_linkroom').val(data[0].roomon_linkroom);
        $('#roomon_liveroom').val(data[0].roomon_liveroom);
        $('#roomon_teachid').val(data[0].roomon_teachid);
        $('#roomon_note').val(data[0].roomon_note);
        $('#roomon_year').val(data[0].roomon_year);
        $('#roomon_term').val(data[0].roomon_term);

        var n = data[0].roomon_classlevel.split('|');
        classlevel.set(n);

    }, "json");
});
$(document).on("click", ".ShowDeleteRoomOnline", function () {
    $('#DeleteRoomOnline').modal('show');
    $('#del_roomon_id').val($(this).attr('roomid'));
});

$(document).on("click", ".btn-out", function () {
    window.location.reload();
});

$(document).on("submit", ".Add_RoomOnline", function (e) {
    e.preventDefault(e);
    $.ajax({
        url: '../../admin/ConAdminRoomOnline/AddRoomOnline',
        type: "post",
        data: new FormData(this), //this is formData
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            console.log(data);
            if (data != 2) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'บันทึกข้อมูลไว้แล้ว',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        //window.location.reload();
                    }
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'ข้อมูลซ้ำ มีในระบบแล้ว',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        //window.location.reload();
                    }
                })
            }
        }
    });
});

$(document).on("submit", ".Update_RoomOnline", function (e) {
    e.preventDefault(e);
    $.ajax({
        url: '../../admin/ConAdminRoomOnline/UpdateRoomOnline',
        type: "post",
        data: new FormData(this), //this is formData
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            //console.log(data);
            if (data > 0) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'บันทึกข้อมูลไว้แล้ว',
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.reload();
                    }
                })
            }
        }
    });
});

$(document).on("submit", ".FormDeleteRoomOnline", function (e) {
    e.preventDefault(e);
    $.post("../../admin/ConAdminRoomOnline/DeleteRoomOnline", { roomid: $("#del_roomon_id").val() }, function (data, status) {
        if (data == 1) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'ลบข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    window.location.reload();
                }
            })
        } else {
            alertify.error('ลบข้อมูลไม่สำเร็จ');
        }
    });
});