<?php

namespace App\Libraries;

class Datethai
{

    protected $dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    protected $monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    protected $monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];


    function thai_date_and_time($time)
    {   // 19 ธันวาคม 2556 เวลา 10:10:43
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $this->monthTH[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        $thai_date_return .= " เวลา " . date("H:i:s", $time);
        return $thai_date_return;
    }
    function thai_date_and_time_short($time)
    {   // 19  ธ.ค. 2556 10:10:4
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $this->monthTH_brev[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        $thai_date_return .= " " . date("H:i:s", $time);
        return $thai_date_return;
    }
    function thai_date_short($time)
    {   // 19  ธ.ค. 2556a
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $this->monthTH_brev[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        return $thai_date_return;
    }
    function thai_date_fullmonth($time)
    {   // 19 ธันวาคม 2556
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $this->monthTH[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        return $thai_date_return;
    }
    function thai_date_short_number($time)
    {   // 19-12-56
        $thai_date_return = date("d", $time);
        $thai_date_return .= "-" . date("m", $time);
        $thai_date_return .= "-" . substr((date("Y", $time) + 543), -2);
        return $thai_date_return;
    }
}
