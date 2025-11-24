$(document).on("change", ".onoff_savescore", function() {

    // console.log($(this).prop('checked'));
    //console.log($(this).val());
    // console.log($(this).attr('onoff-id'));

    $.post("../../../admin/academic/ConAdminSaveScore/CheckOnOffSaveScore", {
            check: $(this).prop('checked'),
            key: $(this).attr('onoff-id'),
            value: $(this).val()
        },
        function(data, status) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'เปลี่ยนแปลงข้อมูลสำเร็จ',
                showConfirmButton: false,
                timer: 3000
            })
        });
})

$(document).on('keyup', '.check_score', function() {
    var num = parseInt($(this).val());
    var key = parseInt($(this).attr('check-score-key'));
    // console.log($(this).val());
    //   console.log($(this).attr('check-score-key'));

    if (num > key) {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'คุณกรอกคะแนนเกินคะแนนเก็บ<br>กรุณากรอกคะแนนใหม่',
            showConfirmButton: false,
            timer: 3000
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                //window.location.reload();
                $(this).val("0");
            }
        })
    }
});

$(document).on('keyup', '.study_time', function() {
    var num = parseInt($(this).val());
    var key = parseInt($(this).attr('check-time'));

    if (num > key) {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'คุณกรอกเวลาเรียนเกินกำหนด ' + key + 'ชั่วโมง <br>กรุณากรอกเวลาเรียนใหม่',
            showConfirmButton: false,
            timer: 3000
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                //window.location.reload();
                $(this).val("0");
            }
        })
    }
});


function calculateRowSum() {
    var TimeNum = $('.study_time').attr('check-time');
    $('table tbody tr').each(function() {

        var sum = 0;
        var study_time;
        var Check_ro = 0;
        $(this).find('td').each(function() {

            if ($(this).find('.check_score').val() == "ร") {
                Check_ro += 1;
            } else {
                sum += parseInt($(this).find('.check_score').val()) || 0;
            }
        });

        study_time = $(this).find('.study_time').val()


        $(this).find('.subtot').html(sum);
        if (80 * TimeNum / 100 > study_time) {
            $(this).find('.grade').html('มส');
        } else if (Check_ro > 0) {
            $(this).find('.grade').html('ร');
        } else {
            $(this).find('.grade').html(check_grade(sum));
        }

        if (sum >= 50) {
            //    $(this).find('.check_score').attr('readonly','0');
            //    $(this).find('.study_time').attr('readonly','0');

        }
        //console.log(sum);
    });
}

function check_grade(sum) {

    if ((sum > 100) || (sum < 0)) {
        var grade = "ไม่สามารถคิดเกรดได้ คะแนนเกิน";
    } else if ((sum >= 79.5) && (sum <= 100)) {
        var grade = 4;
    } else if ((sum >= 74.5) && (sum <= 79.4)) {
        var grade = 3.5;
    } else if ((sum >= 69.5) && (sum <= 74.4)) {
        var grade = 3;
    } else if ((sum >= 64.5) && (sum <= 69.4)) {
        var grade = 2.5;
    } else if ((sum >= 59.5) && (sum <= 64.4)) {
        var grade = 2;
    } else if ((sum >= 54.5) && (sum <= 59.4)) {
        var grade = 1.5;
    } else if ((sum >= 49.5) && (sum <= 54.4)) {
        var grade = 1;
    } else if (sum <= 49.4) {
        var grade = 0;
    }
    return grade;
}
calculateRowSum();


$(".check_score").each(function() {
    $(this).keyup(function() {
        calculateTotal($(this).parent().index());
        //console.log($(this).parent().index());
    });
});

$(".study_time").each(function() {
    $(this).keyup(function() {
        calculateTotal($(this).parent().index());
        //console.log();
    });
});

function calculateTotal(index) {
    var total = 0;
    $('#tb_score tbody tr td').filter(function() {
        if ($(this).index() == index) {
            total += parseInt($(this).find('.check_score').val()) || 0;
        }
    });
    $('#tb_score tbody tr td.totalCol:eq(' + index + ')').html(total);
    // calculateSum();
    calculateRowSum();
}

$(document).on('submit', '.form_score_0W', function(e) {
    e.preventDefault();

    $.ajax({
        url: '../../../../../../admin/academic/ConAdminSaveScore/insert_score_0W',
        type: "post",
        data: $(this).serialize(), //this is formData
        success: function(data) {
            console.log(data);
            if (data > 0) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'บันทึกคะแนนสำเร็จ',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {

                    }
                })
            } else {
                // window.location.reload();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
    });
});

//ตั้งค่าเรียนซ้ำครั้งที่
$(document).on('submit', '.form_score', function(e) {
    e.preventDefault();

    $.ajax({
        url: '../../../../../../admin/academic/ConAdminAcademicRepeat/insert_score',
        type: "post",
        data: $(this).serialize(), //this is formData
        success: function(data) {
            console.log(data);
            if (data > 0) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'บันทึกคะแนนสำเร็จ',
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {

                    }
                })
            } else {
                // window.location.reload();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
    });
});

$(document).on("change", "#CheckTimeRepeat", function() {
    let CheckTimeRepeat = $(this).val();
    $.post("../../../../../admin/academic/ConAdminAcademicRepeat/CheckTimeRepeat", {
            value: CheckTimeRepeat
        },
        function(data, status) {
            //console.log(data);
            if (data == 1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'เปลี่ยนแปลงเป็น' + CheckTimeRepeat,
                    showConfirmButton: false,
                    timer: 3000
                })
            }


        });
});

$(document).on("change", "#CheckOnoffRepeat", function() {
    let CheckOnoffRepeat = $(this).val();
    $.post("../../../../../admin/academic/ConAdminAcademicRepeat/CheckOnoffRepeat", {
            value: CheckOnoffRepeat
        },
        function(data, status) {

            if (CheckOnoffRepeat === 'on') {
                // console.log(CheckOnoffRepeat);
                $('#CheckOnoffRepeat').removeClass('border-danger');
                $('#CheckOnoffRepeat').removeClass('text-danger');
                $('#CheckOnoffRepeat').addClass('border-success');
                $('#CheckOnoffRepeat').addClass('text-success');
            } else {
                $('#CheckOnoffRepeat').removeClass('border-success');
                $('#CheckOnoffRepeat').removeClass('text-success');
                $('#CheckOnoffRepeat').addClass('border-danger');
                $('#CheckOnoffRepeat').addClass('text-danger');

            }

            if (data == 1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'เปลี่ยนแปลงเป็น' + CheckOnoffRepeat,
                    showConfirmButton: false,
                    timer: 3000
                })
            }


        });
});

$(document).on("change", "#onoff_year", function() {
    let onoff_year = $(this).val();
    console.log(onoff_year);
    $.post("../../../../../admin/academic/ConAdminAcademicRepeat/CheckOnoffYear", {
            value: onoff_year
        },
        function(data, status) {
            window.location.href = '../' + onoff_year;
        });
});