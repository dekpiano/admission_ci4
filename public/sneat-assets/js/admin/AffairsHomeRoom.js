$('#ShowDashborad').DataTable({
    paging: false,
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5', 'excelHtml5', 'print'
    ],
    "autoWidth": true,
    "footerCallback": function(row, data, start, end, display) {
        var api = this.api();
        nb_cols = api.columns().nodes().length;
        var j = 1;
        while (j < nb_cols) {
            var pageTotal = api
                .column(j)
                .data()
                .reduce(function(a, b) {
                    return Number(a) + Number(b);
                }, 0);
            // Update footer
            $(api.column(j).footer()).html(pageTotal);
            j++;
        }
    }
});

$(document).on("change", "#homevisit_set_manager", function() {
    $.post("../../../../admin/Affairs/ConAdminStudentSupport/HomeVisitSettingManager", { TeachID: $(this).val() }, function(data, status) {
        if (data == 1) {
            alertify.success('เลือกหัวหน้างานสำเร็จ');
        } else {
            alertify.error('เปลี่ยนแปลงข้อมูลไม่สำเร็จ');
        }
    });
});

$(document).on("change", "#set_homeroom_time", function() {
    console.log($(this).val());
    $.post("../../../admin/Affairs/ConAdminStudentHomeRoom/UpdateTimeHomeRoom", { set_homeroom_time: $(this).val() }, function(data, status) {
        if (data == 1) {
            alertify.success('เปลี่ยนเวลาสำเร็จ');
        } else {
            alertify.error('เปลี่ยนเวลาไม่สำเร็จ');
        }
    });
});

var d = new Date();
var strDate = d.getFullYear() + "/" + (d.getMonth() + 1) + "/" + d.getDate();
ChartHomeRoomAll(strDate)
$(document).on("change", "#show_date", function() {
    //window.location.href = $(this).val();
    // alert();
    $('#chart-doughnut').remove();
    $('#graph-container').append('<canvas id="chart-doughnut" width="314" height="157" style="display: block; width: 314px; height: 157px;" class="chartjs-render-monitor"></canvas>');

    ChartHomeRoomAll($(this).val())
});

function ChartHomeRoomAll(TDate) {
    $.post('../../../../admin/Affairs/ConAdminStudentHomeRoom/ChartHomeRoomAll', { key: TDate }, function(show) {
            //console.log(show);
            var BARCHARTEXMPLE = $('#chart-doughnut');
            var barChartExample = new Chart(BARCHARTEXMPLE, {
                type: 'bar',
                data: {
                    labels: ["มา", "ขาด", "สาย", "ลา", "กิจกรรม", "ไม่เข้าแถว"],
                    datasets: [{
                        label: 'จำนวน',
                        data: show,
                        backgroundColor: [
                            'rgba(121, 106, 238, 1)',
                            'rgba(255, 118, 118, 1)',
                            'rgba(84, 230, 157, 1)',
                            'rgba(255, 195, 109, 1)',
                            'rgba(109, 242, 255, 1)',
                            'rgba(255, 109, 244, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            });

        }, "json")
        .fail(function(xhr, textStatus, errorThrown) {
            alert(xhr.responseText);
        });
}




$(document).on("click", ".ShowStudentOfficer", function() {
    $('#ShowStudent').modal('show');
    $('.DelTableRow').remove();
    $.post('../../../../teacher/ConTeacherTeaching/CHR_CheckStudent', {
            id: $(this).attr('homeroom-id'),
            keyword: $(this).attr('homeroom-keyword')
        }, function(data) {
            //console.log(data);

            $.each(data, function(key, val) {
                //console.log(val[0].StudentFirstName);
                $('#TB_showstudent').append('<tr class="DelTableRow"><td>' + val[0].StudentNumber + '</td><td>' + val[0].StudentCode + '</td><td>' + val[0].StudentPrefix + val[0].StudentFirstName + ' ' + val[0].StudentLastName + '</td></tr>');
            });
        }, 'json')
        .fail(function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        });
});