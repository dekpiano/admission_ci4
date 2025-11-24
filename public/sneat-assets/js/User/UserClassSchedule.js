 // โหลดข้อมูลรูปภาพจากฐานข้อมูล
 $.ajax({
    url: 'user/ConStudents/SearchClassSchedule',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
        console.log(data);
        
        $.each(data, function(index, image) {
            $('#SearchClassSchedule').append('<option value="' + image.schestu_filename + '"> ม.' + image.schestu_classname +' </option>');
        });
    },
    error: function(xhr, status, error) {
        console.error("Status:", status);
        console.error("Error:", error);
        console.error("Response Text:", xhr.responseText);
    }
});

// แสดงรูปภาพเมื่อมีการเปลี่ยนแปลงการเลือก
$('#SearchClassSchedule').change(function() {
    var selectedImage = 'uploads/academic/class_schedule/'+$(this).val();
    if (selectedImage) {
        $('#image').attr('src', selectedImage).show();
    } else {
        $('#image').hide();
    }
});

new SlimSelect({
    select: '#SearchClassSchedule'
  })