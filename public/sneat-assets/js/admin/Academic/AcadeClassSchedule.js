  // แสดงภาพตัวอย่างเมื่อเลือกไฟล์
  $('#schestu_filename').on('change', function (event) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $('#previewImage').attr('src', e.target.result).show();
    }
    reader.readAsDataURL(event.target.files[0]);
});

$(document).on('submit','.FormAddClassSchedule', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    var action = $(this).attr('action');

    $.ajax({
        url: action,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if(response == 1){
                Swal.fire({
                    title: "แจ้งเตือน?",
                    text: "คุณเพิ่มตารางเรียนเรียบร้อยแล้ว!",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "ตกลง!"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../ClassSchedule';
                    }
                  });
            }

        },
        error: function (xhr, status, error) {
            console.log(("Error: " + xhr.responseText));
        }
    });
});

$(document).on('change','#SelYearClassSchedule', function() {
    var selectedYear = $(this).val();

    // ส่ง AJAX ไปยังเซิร์ฟเวอร์เพื่อนำข้อมูลของปีที่เลือก
    $.ajax({
        url: "../../../admin/academic/ConAdminClassSchedule/getDataByYear",
        type: "POST",
        data: { year: selectedYear },
        success: function(response) {
            // เคลียร์ข้อมูลเก่าในตาราง
            $('#TbClassSchedule tbody').empty();
            $('#TbClassSchedule tbody').append('<tr class="loading"><td colspan="3">Loading data, please wait...</td></tr>');
            $('.loading').show();

            // เติมข้อมูลใหม่ที่ได้จากเซิร์ฟเวอร์ลงในตาราง
            $.each(response.data, function(index, item) {
                var row = '<tr>' +
                    '<td>' + item.schestu_name + '</td>' +
                    '<td>' + item.schestu_classname + '</td>' +
                    '<td>' + item.schestu_term+'/'+item.schestu_year + '</td>' +
                    '<td> <a target="_blank" href="../../../uploads/academic/class_schedule/' + item.schestu_filename + '">เปิดดู</a></td>' +
                    '<td>' + item.schestu_datetime + '</td>' +
                    '<td><a href="../../../admin/academic/ConAdminClassSchedule/delete_class_schedule/' + item.schestu_id + '/' + item.schestu_filename + '" class="btn btn-danger btn-sm" onClick="return confirm(\'ต้องการลบข้อมูลหรือไม่?\')"><i class="fas fa-trash-alt"></i> ลบ</a></td>' +
                    '</tr>';
                $('#TbClassSchedule tbody').append(row);
            });
        },
        complete: function() {
            // ซ่อน loading message เสมอไม่ว่าจะ success หรือ error
            $('.loading').hide();
        },        
        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    });
});

// โหลดข้อมูลเมื่อหน้าเพจเริ่มต้น
$('#SelYearClassSchedule').trigger('change');