     // ใช้ querySelectorAll เพื่อเลือกทุก select ที่มี class .my-select
document.querySelectorAll('.set_admin').forEach(function(selectElement) {
    new SlimSelect({
        select: selectElement
    });
});
 
 // ฟังก์ชันเพื่อส่งข้อมูลไปยังเซิร์ฟเวอร์
 function sendData() {
    // สร้างอาเรย์เก็บค่าที่เลือกสำหรับทุกบุคคล
    var selectedOptions = {};
    
    // ตรวจสอบ checkbox ที่ถูกติ๊กสำหรับแต่ละกลุ่ม
    $('div.person').each(function(index) {
        var options = [];
        var mainKey = $(this).find('.set_admin').val();
        // วนลูปตรวจสอบทุก checkbox ภายใน div.person แต่ละกลุ่ม
        $(this).find('input[type="checkbox"]').each(function() {
            if ($(this).is(':checked')) {
                // ถ้า checkbox ถูกติ๊ก เก็บค่าปกติ
                options.push($(this).val());
            } else {
                // ถ้า checkbox ไม่ถูกติ๊ก ให้เก็บค่าเป็น 0
               // options.push('x');
            }
        });

        selectedOptions[index] = {
            mainKey: mainKey, // เก็บ key หลัก
            options: options // เก็บข้อมูลสำหรับ checkbox
        };
    });
    
    
    // ส่งค่าผ่าน AJAX
    $.ajax({
        url: '../../../admin/academic/ConAdminSettingAdminRoles/SelectWork', // แทนที่ด้วย URL ของเซิร์ฟเวอร์ที่คุณต้องการส่งข้อมูล
        type: 'POST',
        data: {option: selectedOptions},
        success: function(response) {
            // ทำการจัดการกับการตอบกลับจากเซิร์ฟเวอร์
            console.log('Response from server:', response);
        },
        error: function(xhr, status, error) {
            // จัดการกับข้อผิดพลาด
            console.error('AJAX Error:', xhr.responseText);
        }
    });
}

// ตรวจสอบ checkbox เมื่อมีการเปลี่ยนแปลง
$('input[type="checkbox"],.set_admin').change(function() {
    sendData(); // เรียกใช้ฟังก์ชันเพื่อส่งข้อมูล
});