$('#tbStudent').DataTable({
    "order": [
        [2, "asc"],
        [3, "asc"]
    ],
    lengthMenu: [45, 100],
    processing: true,
    "ajax": {
        url: "../../../../admin/academic/ConAdminStudents/AdminStudentsNormalShow/" + $('#KeyStatus').val(),
        "type": "POST",
        "data": function ( d ) {
            d.classFilter = $('#classFilter').val();
        }
    },
    'columns': [
        { data: 'StudentCode' },
        { data: 'Fullname' },
        { data: 'StudentClass' },
        { data: 'StudentNumber' },
        { data: 'StudentStudyLine' },
        {
            data: 'StudentStatus',
            render: function(data, type, row) {
                if (data != "1/ปกติ") {
                    return '<a class="btn-sm btn-danger EditStudentStatus" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" key-stu="' + row.StudentID + '">' + data + '</a>';
                } else {
                    return '<a class="btn-sm app-btn-primary EditStudentStatus" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" key-stu="' + row.StudentID + '">' + data + '</a>';
                }
            }
        },
        {
            data: 'StudentBehavior',
            render: function(data, type, row) {
                if (data != "ปกติ") {
                    return '<a class="btn-sm btn-danger" href="#">' + data + '</a>';
                } else {
                    return '<a class="btn-sm app-btn-primary" href="#">' + data + '</a>';
                }

            }
        },
        {
            data: null, // No data source for this column
            orderable: false,
            render: function(data, type, row) {
                return '<button class="btn btn-sm btn-primary view-details" data-student-id="' + row.StudentID + '">ดู/แก้ไข</button>';
            }
        }
    ],
    "dom":  '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 text-center"<"toolbar">><"col-sm-12 col-md-4"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    initComplete: function(){
        $("div.toolbar").html($('#classFilterWrapper').html());
        $('#classFilterWrapper').remove();
        $('.toolbar').show();
    }
});

// Reload DataTable when class filter changes
$(document).on('change', '#classFilter', function() {
    $('#tbStudent').DataTable().ajax.reload();
});

$(document).on('click', '.view-details', function(event) {
    var studentId = $(this).data('student-id');
    if (studentId) {
        $.ajax({
            url: '../../../../admin/academic/ConAdminStudents/get_student_details/' + studentId,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#studentDetailContent').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            success: function(response) {
                if (response && response.student_data) {
                    var formHtml = buildStudentEditForm(response.student_data, response.class_list, response.study_line_list);
                    $('#studentDetailContent').html(formHtml);
                    
                    initAddressDropdowns(response.student_data);

                    var studentDetailModal = new bootstrap.Modal(document.getElementById('studentDetailModal'));
                    studentDetailModal.show();
                } else {
                    $('#studentDetailContent').html('<p class="text-danger">Could not find student details.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
                $('#studentDetailContent').html('<p class="text-danger">An error occurred while fetching the data.</p>');
            }
        });
    }
});

function buildStudentEditForm(data, classList, studyLineList) {
    const val = (d) => d || '';

    const prefixOptions = ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว'];
    const studentStatusOptions = [
        'เลือกสถานะ', '1/ปกติ', '2/ย้ายสถานศึกษา', '3/ขาดประจำ', '4/พักการเรียน', '5/จบการศึกษา'
    ];
    const studentBehaviorOptions = [
        'ปกติ', 'ขาดเรียนนาน', 'ย้ายสถานศึกษา', 'พักการเรียน', 'จบการศึกษา'
    ];
    const bloodTypeOptions = [
        'เลือกกรุ๊ปเลือด', 'A', 'B', 'AB', 'O'
    ];
    const nationalityOptions = [
        'เลือกเชื้อชาติ', 'ไทย', 'จีน', 'มาเลเซีย', 'พม่า', 'ลาว', 'กัมพูชา', 'อื่นๆ'
    ];
    const raceOptions = [
        'เลือกสัญชาติ', 'ไทย', 'จีน', 'มาเลเซีย', 'พม่า', 'ลาว', 'กัมพูชา', 'อื่นๆ'
    ];
    const religionOptions = [
        'เลือกศาสนา', 'พุทธ', 'คริสต์', 'อิสลาม', 'ฮินดู', 'ซิกข์', 'อื่นๆ'
    ];
    const parenalStatusOptions = ['อยู่ด้วยกัน', 'แยกกันอยู่', 'บิดาถึงแก่กรรม', 'มารดาถึงแก่กรรม', 'ถึงแก่กรรมทั้งคู่'];
    const usedStudentOptions = ['ใช่', 'ไม่ใช่'];

    const generateSelectOptions = (optionsArray, selectedValue) => {
        return optionsArray.map(option => 
            `<option value="${option}" ${option === selectedValue ? 'selected' : ''}>${option}</option>`
        ).join('');
    };

    return `
        <input type="hidden" name="StudentID" value="${val(data.StudentID)}">
        <input type="hidden" name="stu_idStu" value="${val(data.stu_idStu)}"> 
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="studentTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-info-tab" data-bs-toggle="tab" data-bs-target="#personal-info" type="button" role="tab" aria-controls="personal-info" aria-selected="true">ข้อมูลนักเรียน</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="address-info-tab" data-bs-toggle="tab" data-bs-target="#address-info" type="button" role="tab" aria-controls="address-info" aria-selected="false">ที่อยู่</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="general-info-tab" data-bs-toggle="tab" data-bs-target="#general-info" type="button" role="tab" aria-controls="general-info" aria-selected="false">ข้อมูลทั่วไป</button>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" id="studentTabContent">
            <!-- Personal Info Tab -->
            <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
                
                <h5 class="mt-3">ข้อมูลส่วนตัว</h5>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="StudentPrefix" name="StudentPrefix">
                                ${generateSelectOptions(prefixOptions, val(data.StudentPrefix))}
                            </select>
                            <label for="StudentPrefix">คำนำหน้า</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="StudentFirstName" name="StudentFirstName" value="${val(data.StudentFirstName)}">
                            <label for="StudentFirstName">ชื่อ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="StudentLastName" name="StudentLastName" value="${val(data.StudentLastName)}">
                            <label for="StudentLastName">นามสกุล</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_nickName" name="stu_nickName" value="${val(data.stu_nickName)}">
                            <label for="stu_nickName">ชื่อเล่น</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" id="StudentDateBirth" name="StudentDateBirth" value="${val(data.StudentDateBirth)}">
                            <label for="StudentDateBirth">วันเกิด</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="StudentIDNumber" name="StudentIDNumber" value="${val(data.StudentIDNumber)}">
                            <label for="StudentIDNumber">เลขประจำตัวประชาชน</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">ข้อมูลการศึกษา</h5>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="StudentCode" name="StudentCode" value="${val(data.StudentCode)}" readonly>
                            <label for="StudentCode">รหัสนักเรียน</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="StudentClass" name="StudentClass">
                                ${generateSelectOptions(classList, val(data.StudentClass))}
                            </select>
                            <label for="StudentClass">ชั้นปี</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="StudentNumber" name="StudentNumber" value="${val(data.StudentNumber)}">
                            <label for="StudentNumber">เลขที่</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="StudentStudyLine" name="StudentStudyLine">
                                ${generateSelectOptions(studyLineList, val(data.StudentStudyLine))}
                            </select>
                            <label for="StudentStudyLine">สายการเรียน</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="StudentStatus" name="StudentStatus">
                                ${generateSelectOptions(studentStatusOptions, val(data.StudentStatus))}
                            </select>
                            <label for="StudentStatus">สถานะนักเรียน</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="StudentBehavior" name="StudentBehavior">
                                ${generateSelectOptions(studentBehaviorOptions, val(data.StudentBehavior))}
                            </select>
                            <label for="StudentBehavior">สถานะพฤติกรรม</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">ข้อมูลติดต่อและสุขภาพ</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_phone" name="stu_phone" value="${val(data.stu_phone)}">
                            <label for="stu_phone">เบอร์โทรศัพท์</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_email" name="stu_email" value="${val(data.stu_email)}">
                            <label for="stu_email">อีเมล</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_bloodType" name="stu_bloodType">
                                ${generateSelectOptions(bloodTypeOptions, val(data.stu_bloodType))}
                            </select>
                            <label for="stu_bloodType">กรุ๊ปเลือด</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_diseaes" name="stu_diseaes" value="${val(data.stu_diseaes)}">
                            <label for="stu_diseaes">โรคประจำตัว</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_wieght" name="stu_wieght" value="${val(data.stu_wieght)}">
                            <label for="stu_wieght">น้ำหนัก</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_hieght" name="stu_hieght" value="${val(data.stu_hieght)}">
                            <label for="stu_hieght">ส่วนสูง</label>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">ข้อมูลอื่นๆ</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_nationality" name="stu_nationality">
                                ${generateSelectOptions(nationalityOptions, val(data.stu_nationality))}
                            </select>
                            <label for="stu_nationality">เชื้อชาติ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_race" name="stu_race">
                                ${generateSelectOptions(raceOptions, val(data.stu_race))}
                            </select>
                            <label for="stu_race">สัญชาติ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_religion" name="stu_religion">
                                ${generateSelectOptions(religionOptions, val(data.stu_religion))}
                            </select>
                            <label for="stu_religion">ศาสนา</label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Address Info Tab -->
            <div class="tab-pane fade" id="address-info" role="tabpanel" aria-labelledby="address-info-tab">
                 <h5 class="mt-3">ที่อยู่ตามทะเบียนบ้าน</h5>
                 <div class="row">
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_hCode" name="stu_hCode" value="${val(data.stu_hCode)}">
                            <label for="stu_hCode">รหัสประจำบ้าน</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_hNumber" name="stu_hNumber" value="${val(data.stu_hNumber)}">
                            <label for="stu_hNumber">บ้านเลขที่</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_hMoo" name="stu_hMoo" value="${val(data.stu_hMoo)}">
                            <label for="stu_hMoo">หมู่</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_hRoad" name="stu_hRoad" value="${val(data.stu_hRoad)}">
                            <label for="stu_hRoad">ถนน</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_hProvince" name="stu_hProvince"></select>
                            <label for="stu_hProvince">จังหวัด</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_hDistrict" name="stu_hDistrict"></select>
                            <label for="stu_hDistrict">อำเภอ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_hTambon" name="stu_hTambon"></select>
                            <label for="stu_hTambon">ตำบล</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_hPostCode" name="stu_hPostCode" value="${val(data.stu_hPostCode)}">
                            <label for="stu_hPostCode">รหัสไปรษณีย์</label>
                        </div>
                    </div>
                 </div>
                 <h5 class="mt-3">ที่อยู่ปัจจุบัน</h5>
                 <div class="row">
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_cNumber" name="stu_cNumber" value="${val(data.stu_cNumber)}">
                            <label for="stu_cNumber">บ้านเลขที่</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_cMoo" name="stu_cMoo" value="${val(data.stu_cMoo)}">
                            <label for="stu_cMoo">หมู่</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_cRoad" name="stu_cRoad" value="${val(data.stu_cRoad)}">
                            <label for="stu_cRoad">ถนน</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                           <select class="form-select" id="stu_cProvince" name="stu_cProvince"></select>
                           <label for="stu_cProvince">จังหวัด</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_cDistrict" name="stu_cDistrict"></select>
                            <label for="stu_cDistrict">อำเภอ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_cTumbao" name="stu_cTumbao"></select>
                            <label for="stu_cTumbao">ตำบล</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="stu_cPostcode" name="stu_cPostcode" value="${val(data.stu_cPostcode)}">
                            <label for="stu_cPostcode">รหัสไปรษณีย์</label>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- General Info Tab -->
            <div class="tab-pane fade" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
                <h5 class="mt-3">ข้อมูลการเกิด</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                           <select class="form-select" id="stu_birthProvince" name="stu_birthProvirce"></select>
                           <label for="stu_birthProvince">จังหวัดเกิด</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_birthDistrict" name="stu_birthDistrict"></select>
                            <label for="stu_birthDistrict">อำเภอเกิด</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="stu_birthTambon" name="stu_birthTambon"></select>
                            <label for="stu_birthTambon">ตำบลเกิด</label>
                        </div>
                    </div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_birthHospital" name="stu_birthHospital" value="${val(data.stu_birthHospital)}"><label for="stu_birthHospital">โรงพยาบาลที่เกิด</label></div></div>
                </div>

                <h5 class="mt-3">ข้อมูลครอบครัว</h5>
                <div class="row">
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_numberSibling" name="stu_numberSibling" value="${val(data.stu_numberSibling)}"><label for="stu_numberSibling">จำนวนพี่น้อง</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_firstChild" name="stu_firstChild" value="${val(data.stu_firstChild)}"><label for="stu_firstChild">เป็นลูกคนที่</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_numberSiblingSkj" name="stu_numberSiblingSkj" value="${val(data.stu_numberSiblingSkj)}"><label for="stu_numberSiblingSkj">จำนวนพี่น้องที่เรียนที่นี่</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><select class="form-select" id="stu_parenalStatus" name="stu_parenalStatus">${generateSelectOptions(parenalStatusOptions, val(data.stu_parenalStatus))}</select><label for="stu_parenalStatus">สถานภาพบิดามารดา</label></div></div>
                    <div class="col-md-6"><div class="form-floating mb-3">
                                <select class="form-select" id="stu_presentLife" name="stu_presentLife">
                                    ${generateSelectOptions(['อยู่กับบิดกและมารดา', 'อยู่กับบิดาหรือมารดา', 'บุคคลอื่น'], val(data.stu_presentLife))}
                                </select>
                                <label for="stu_presentLife">สภาพความเป็นอยู่</label>
                            </div>
                        </div>
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_personOther" name="stu_personOther" value="${val(data.stu_personOther)}"><label for="stu_personOther">กรณีไม่ได้อยู่กับบิดามารดา อยู่กับใคร</label></div></div>
                </div>

                <h5 class="mt-3">ข้อมูลเพิ่มเติม</h5>
                <div class="row">
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_disablde" name="stu_disablde" value="${val(data.stu_disablde)}"><label for="stu_disablde">ความพิการ</label></div></div>
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_talent" name="stu_talent" value="${val(data.stu_talent)}"><label for="stu_talent">ความสามารถพิเศษ</label></div></div>
                </div>

                <h5 class="mt-3">ข้อมูลการเดินทาง</h5>
                <div class="row">
                    <div class="col-md-4"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_natureRoom" name="stu_natureRoom" value="${val(data.stu_natureRoom)}"><label for="stu_natureRoom">ลักษณะที่พัก</label></div></div>
                    <div class="col-md-4"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_farSchool" name="stu_farSchool" value="${val(data.stu_farSchool)}"><label for="stu_farSchool">ระยะห่างจากโรงเรียน (ก.ม.)</label></div></div>
                    <div class="col-md-4"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_travel" name="stu_travel" value="${val(data.stu_travel)}"><label for="stu_travel">เดินทางมาโรงเรียนโดย</label></div></div>
                </div>

                <h5 class="mt-3">ข้อมูลการศึกษาเดิม</h5>
                <div class="row">
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_gradLevel" name="stu_gradLevel" value="${val(data.stu_gradLevel)}"><label for="stu_gradLevel">จบการศึกษาชั้น</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_schoolfrom" name="stu_schoolfrom" value="${val(data.stu_schoolfrom)}"><label for="stu_schoolfrom">จากโรงเรียน</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_schoolTambao" name="stu_schoolTambao" value="${val(data.stu_schoolTambao)}"><label for="stu_schoolTambao">ตำบล</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_schoolDistrict" name="stu_schoolDistrict" value="${val(data.stu_schoolDistrict)}"><label for="stu_schoolDistrict">อำเภอ</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_schoolProvince" name="stu_schoolProvince" value="${val(data.stu_schoolProvince)}"><label for="stu_schoolProvince">จังหวัด</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><select class="form-select" id="stu_usedStudent" name="stu_usedStudent">${generateSelectOptions(usedStudentOptions, val(data.stu_usedStudent))}</select><label for="stu_usedStudent">เคยเป็นนักเรียนที่นี่</label></div></div>
                    <div class="col-md-3"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_inputLevel" name="stu_inputLevel" value="${val(data.stu_inputLevel)}"><label for="stu_inputLevel">ประสงค์จะเข้าศึกษาต่อชั้น</label></div></div>
                </div>

                 <h5 class="mt-3">ข้อมูลติดต่อ</h5>
                 <div class="row">
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_phoneUrgent" name="stu_phoneUrgent" value="${val(data.stu_phoneUrgent)}"><label for="stu_phoneUrgent">เบอร์โทรฉุกเฉิน</label></div></div>
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_phoneFriend" name="stu_phoneFriend" value="${val(data.stu_phoneFriend)}"><label for="stu_phoneFriend">เบอร์เพื่อนสนิท</label></div></div>
                 </div>

                 <h5 class="mt-3">ข้อมูลความสนใจ</h5>
                 <div class="row">
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_future_education" name="stu_future_education" value="${val(data.stu_future_education)}"><label for="stu_future_education">สนใจการศึกษาต่อ</label></div></div>
                    <div class="col-md-6"><div class="form-floating mb-3"><input type="text" class="form-control" id="stu_career_interest" name="stu_career_interest" value="${val(data.stu_career_interest)}"><label for="stu_career_interest">ความสนใจอาชีพอนาคต</label></div></div>
                 </div>

            </div>

        </div>
    `;
}

function initAddressDropdowns(studentData) {
    const url = 'https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_province_with_amphure_tambon.json';
    
    $.getJSON(url, function(data) {
        // Permanent Address
        const provinceSelect = $('#stu_hProvince');
        const amphoeSelect = $('#stu_hDistrict');
        const tambonSelect = $('#stu_hTambon');
        const postcode = $('#stu_hPostCode');

        // Populate provinces
        provinceSelect.empty().append('<option value="">เลือกจังหวัด</option>');
        $.each(data, function(index, province) {
            provinceSelect.append(`<option value="${province.name_th}">${province.name_th}</option>`);
        });

        // Event listeners
        provinceSelect.on('change', function() {
            const selectedProvince = $(this).val();
            amphoeSelect.empty().append('<option value="">เลือกอำเภอ</option>');
            tambonSelect.empty().append('<option value="">เลือกตำบล</option>');
            postcode.val('');

            if (selectedProvince) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                $.each(provinceData.amphure, function(index, amphure) {
                    amphoeSelect.append(`<option value="${amphure.name_th}">${amphure.name_th}</option>`);
                });
            }
        });

        amphoeSelect.on('change', function() {
            const selectedProvince = provinceSelect.val();
            const selectedAmphoe = $(this).val();
            tambonSelect.empty().append('<option value="">เลือกตำบล</option>');
            postcode.val('');

            if (selectedProvince && selectedAmphoe) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                const amphureData = provinceData.amphure.find(a => a.name_th === selectedAmphoe);
                $.each(amphureData.tambon, function(index, tambon) {
                    tambonSelect.append(`<option value="${tambon.name_th}">${tambon.name_th}</option>`);
                });
            }
        });

        tambonSelect.on('change', function() {
            const selectedProvince = provinceSelect.val();
            const selectedAmphoe = amphoeSelect.val();
            const selectedTambon = $(this).val();
            postcode.val('');

            if (selectedProvince && selectedAmphoe && selectedTambon) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                const amphureData = provinceData.amphure.find(a => a.name_th === selectedAmphoe);
                const tambonData = amphureData.tambon.find(t => t.name_th === selectedTambon);
                postcode.val(tambonData.zip_code);
            }
        });

        // Set initial values if studentData exists
        if (studentData.stu_hProvince) {
            provinceSelect.val(studentData.stu_hProvince).trigger('change');
            if (studentData.stu_hDistrict) {
                amphoeSelect.val(studentData.stu_hDistrict).trigger('change');
                 if (studentData.stu_hTambon) {
                    tambonSelect.val(studentData.stu_hTambon).trigger('change');
                }
            }
        }

        // Current Address (similar logic)
        const provinceSelect2 = $('#stu_cProvince');
        const amphoeSelect2 = $('#stu_cDistrict');
        const tambonSelect2 = $('#stu_cTumbao');
        const postcode2 = $('#stu_cPostcode');

        provinceSelect2.empty().append('<option value="">เลือกจังหวัด</option>');
        $.each(data, function(index, province) {
            provinceSelect2.append(`<option value="${province.name_th}">${province.name_th}</option>`);
        });

        provinceSelect2.on('change', function() {
            const selectedProvince = $(this).val();
            amphoeSelect2.empty().append('<option value="">เลือกอำเภอ</option>');
            tambonSelect2.empty().append('<option value="">เลือกตำบล</option>');
            postcode2.val('');

            if (selectedProvince) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                $.each(provinceData.amphure, function(index, amphure) {
                    amphoeSelect2.append(`<option value="${amphure.name_th}">${amphure.name_th}</option>`);
                });
            }
        });

        amphoeSelect2.on('change', function() {
            const selectedProvince = provinceSelect2.val();
            const selectedAmphoe = $(this).val();
            tambonSelect2.empty().append('<option value="">เลือกตำบล</option>');
            postcode2.val('');

            if (selectedProvince && selectedAmphoe) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                const amphureData = provinceData.amphure.find(a => a.name_th === selectedAmphoe);
                $.each(amphureData.tambon, function(index, tambon) {
                    tambonSelect2.append(`<option value="${tambon.name_th}">${tambon.name_th}</option>`);
                });
            }
        });

        tambonSelect2.on('change', function() {
            const selectedProvince = provinceSelect2.val();
            const selectedAmphoe = amphoeSelect2.val();
            const selectedTambon = $(this).val();
            postcode2.val('');

            if (selectedProvince && selectedAmphoe && selectedTambon) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                const amphureData = provinceData.amphure.find(a => a.name_th === selectedAmphoe);
                const tambonData = amphureData.tambon.find(t => t.name_th === selectedTambon);
                postcode2.val(tambonData.zip_code);
            }
        });
        
        if (studentData.stu_cProvince) {
            provinceSelect2.val(studentData.stu_cProvince).trigger('change');
            if (studentData.stu_cDistrict) {
                amphoeSelect2.val(studentData.stu_cDistrict).trigger('change');
                if (studentData.stu_cTumbao) {
                    tambonSelect2.val(studentData.stu_cTumbao).trigger('change');
                }
            }
        }

        // Birth Address
        const provinceSelect3 = $('#stu_birthProvince');
        const amphoeSelect3 = $('#stu_birthDistrict');
        const tambonSelect3 = $('#stu_birthTambon');

        provinceSelect3.empty().append('<option value="">เลือกจังหวัด</option>');
        $.each(data, function(index, province) {
            provinceSelect3.append(`<option value="${province.name_th}">${province.name_th}</option>`);
        });

        provinceSelect3.on('change', function() {
            const selectedProvince = $(this).val();
            amphoeSelect3.empty().append('<option value="">เลือกอำเภอ</option>');
            tambonSelect3.empty().append('<option value="">เลือกตำบล</option>');

            if (selectedProvince) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                $.each(provinceData.amphure, function(index, amphure) {
                    amphoeSelect3.append(`<option value="${amphure.name_th}">${amphure.name_th}</option>`);
                });
            }
        });

        amphoeSelect3.on('change', function() {
            const selectedProvince = provinceSelect3.val();
            const selectedAmphoe = $(this).val();
            tambonSelect3.empty().append('<option value="">เลือกตำบล</option>');

            if (selectedProvince && selectedAmphoe) {
                const provinceData = data.find(p => p.name_th === selectedProvince);
                const amphureData = provinceData.amphure.find(a => a.name_th === selectedAmphoe);
                $.each(amphureData.tambon, function(index, tambon) {
                    tambonSelect3.append(`<option value="${tambon.name_th}">${tambon.name_th}</option>`);
                });
            }
        });

        if (studentData.stu_birthProvirce) {
            provinceSelect3.val(studentData.stu_birthProvirce).trigger('change');
            if (studentData.stu_birthDistrict) {
                amphoeSelect3.val(studentData.stu_birthDistrict).trigger('change');
                if (studentData.stu_birthTambon) {
                    tambonSelect3.val(studentData.stu_birthTambon).trigger('change');
                }
            }
        }

    });
}

$(document).on('submit', '#editStudentForm', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: '../../../../admin/academic/ConAdminStudents/update_student_details',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                var studentDetailModal = bootstrap.Modal.getInstance(document.getElementById('studentDetailModal'));
                studentDetailModal.hide();
                
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#tbStudent').DataTable().ajax.reload(null, false);
            } else {
                 Swal.fire({
                    icon: 'error',
                    title: 'ผิดพลาด',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง'
            });
        }
    });
});


$(document).on('click', '.EditStudentStatus', function() {
    $('#keystu').val($(this).attr('key-stu'));
});

$(document).on('change', '.StudentStatus', function() {
    let StudentStatus = $(this).val();
    let KeyStuId = $('#keystu').val();
    $.post("../../../../admin/academic/ConAdminStudents/AdminUpdateStudentStatus", {
            KeyStuId: KeyStuId,
            ValueStudentStatus: StudentStatus
        },
        function(data, status) {
            if (data == 1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'เปลี่ยนแปลงสถานะเป็น' + StudentStatus,
                    showConfirmButton: false,
                    timer: 3000
                })
                document.getElementById("StudentStatus").selectedIndex = 0;
                 $('#tbStudent').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'ผิดพลาด',
                    showConfirmButton: false,
                    timer: 3000
                })
                document.getElementById("StudentStatus").selectedIndex = 0;
            }
        });
});
