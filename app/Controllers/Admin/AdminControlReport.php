<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlReport extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = 'รายงานและการพิมพ์';
        
        // Fetch Years
        $data['years'] = $this->db->table('tb_recruitstudent')
            ->select('recruit_year')
            ->groupBy('recruit_year')
            ->orderBy('recruit_year', 'DESC')
            ->get()->getResult();

        // Get default selected year
        $data['selected_year'] = !empty($data['years']) ? $data['years'][0]->recruit_year : date('Y');

        // Fetch Courses with gradelevel
        $data['courses'] = $this->db->table('tb_course')
            ->select('MAX(course_id) as course_id, course_initials, course_fullname, course_gradelevel')
            ->orderBy('course_gradelevel', 'ASC')
            ->groupBy('course_fullname, course_initials, course_gradelevel')
            ->orderBy('course_fullname', 'ASC')
            ->get()->getResult();

        return view('Admin/PageAdminReport/PageAdminReportIndex', $data);
    }

    /**
     * Get courses by level (AJAX)
     */
    public function getCourses()
    {
        $level = $this->request->getPost('level');
        
        $builder = $this->db->table('tb_course')
            ->select('MAX(course_id) as course_id, course_initials, course_fullname, course_gradelevel');
        
        if (!empty($level)) {
            $builder->where('course_gradelevel', $level);
        }
        
        $builder->groupBy('course_fullname, course_initials, course_gradelevel');
        $builder->orderBy('course_fullname', 'ASC');
        $courses = $builder->get()->getResult();
        
        return $this->response->setJSON(['success' => true, 'data' => $courses]);
    }

    /**
     * Get students for printing (AJAX)
     */
    public function getStudents()
    {
        $year = $this->request->getPost('year');
        $level = $this->request->getPost('level');
        $course = $this->request->getPost('course');
        $type = $this->request->getPost('type'); // 'application' or 'confirmation'

        if (empty($year)) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณาเลือกปีการศึกษา']);
        }

        $builder = $this->db->table('tb_recruitstudent');
        $builder->select('tb_recruitstudent.recruit_id, tb_recruitstudent.recruit_prefix, tb_recruitstudent.recruit_firstName, tb_recruitstudent.recruit_lastName, tb_recruitstudent.recruit_regLevel, tb_recruitstudent.recruit_img, tb_recruitstudent.recruit_status, tb_recruitstudent.recruit_tpyeRoom, tb_course.course_initials');
        $builder->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left');
        
        // For confirmation type, also join with tb_students to check if confirmed
        if ($type === 'confirmation') {
            $builder->select('skjacth_personnel.tb_students.stu_UpdateConfirm');
            $builder->join('skjacth_personnel.tb_students', 'tb_recruitstudent.recruit_idCard = skjacth_personnel.tb_students.stu_iden', 'left');
        }
        
        $builder->where('recruit_year', $year);
        
        if (!empty($level)) {
            $builder->where('recruit_regLevel', $level);
        }
        
        if (!empty($course)) {
            // Filter strictly by ID as requested
            $builder->where('recruit_tpyeRoom_id', $course);
        }
        
        $builder->orderBy('recruit_id', 'ASC');
        $students = $builder->get()->getResult();

        $data = [];
        foreach ($students as $student) {
            $imgSrc = get_recruit_file_url($student->recruit_img ?? 'default.png', $student->recruit_regLevel ?? '1', 'img');
            
            // Determine if can print based on type
            $canPrint = true;
            $statusText = '';
            
            if ($type === 'application') {
                // For application, always can print
                $canPrint = true;
                $statusText = $student->recruit_status ?? 'รอตรวจสอบ';
            } else {
                // For confirmation, check if student has confirmed FOR THIS YEAR
                $canPrint = (!empty($student->stu_UpdateConfirm) && $student->stu_UpdateConfirm == $year);
                $statusText = $canPrint ? 'รายงานตัวแล้ว' : 'ยังไม่รายงานตัว';
            }
            $data[] = [
                'id' => $student->recruit_id,
                'recruit_id' => sprintf('%04d', $student->recruit_id),
                'name' => $student->recruit_prefix . $student->recruit_firstName,
                'lastname' => $student->recruit_lastName,
                'avatar' => $imgSrc,
                'course' => $student->course_initials ?? $student->recruit_tpyeRoom,
                'can_print' => $canPrint,
                'status_text' => $statusText
            ];
        }

        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    /**
     * Step 1: Initialize Batch Process
     */
    public function initBatch()
    {
        $batchId = 'batch_' . uniqid() . '_' . time();
        $tempDir = WRITEPATH . 'temp/' . $batchId;
        
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        return $this->response->setJSON([
            'success' => true, 
            'batch_id' => $batchId
        ]);
    }

    /**
     * Step 2: Process a chunk of IDs
     */
    public function processBatch()
    {
        $batchId = $this->request->getVar('batch_id');
        $ids = $this->request->getVar('ids'); // Array of IDs
        $type = $this->request->getVar('type');
        
        if (empty($batchId) || empty($ids)) {
             // Debug info
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Missing parameters: ' . json_encode($this->request->getVar())
            ]);
        }
        
        $tempDir = WRITEPATH . 'temp/' . $batchId . '/';
        if (!is_dir($tempDir)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Batch session expired']);
        }

        // Load mPDF FIRST
        if (file_exists(SHARED_LIB_PATH . '/mpdf/vendor/autoload.php')) {
            require_once SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        } else {
             return $this->response->setJSON(['success' => false, 'message' => 'mPDF library not found']);
        }

        // Font Config
        $customFontDir = SHARED_LIB_PATH . '/vendor/mpdf/mpdf/ttfonts';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $TH_Month = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];

        $processed = 0;
        foreach ($ids as $id) {
            $recruit = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getRow();
            if (!$recruit) continue;
            
            $prefix = ($type === 'application') ? 'application' : 'confirmation';
            $filename = sprintf('%s_%04d.pdf', $prefix, $recruit->recruit_id);
            
            if ($type === 'application') {
                $pdfContent = $this->generateApplicationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData);
            } else {
                $pdfContent = $this->generateConfirmationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData);
            }
            
            if (!empty($pdfContent)) {
                file_put_contents($tempDir . $filename, $pdfContent);
                $processed++;
            }
            
            // Clean temp SVG per loop to keep lean
            $this->cleanupMpdfTempFiles();
        }

        return $this->response->setJSON([
            'success' => true,
            'processed' => $processed
        ]);
    }

    /**
     * Step 3: Zip and Download
     */
    public function finishBatch()
    {
        $batchId = $this->request->getGet('batch_id');
        $type = $this->request->getGet('type');
        
        if (empty($batchId)) {
            return "Batch ID missing";
        }
        
        $tempDir = WRITEPATH . 'temp/' . $batchId . '/';
        if (!is_dir($tempDir)) {
            return "Batch session not found or expired";
        }

        $zipFilename = ($type === 'application' ? 'applications' : 'confirmations') . '_' . date('Ymd_His') . '.zip';
        
        // Use system temp file to avoid permission issues in writable path
        $tempZipFile = tempnam(sys_get_temp_dir(), 'batch_zip');
        
        $zip = new \ZipArchive();
        if ($zip->open($tempZipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $files = glob($tempDir . '*.pdf');
            foreach ($files as $file) {
                // Add file with base name to avoid folder structure in zip
                $zip->addFile($file, basename($file));
            }
            
            try {
                $zip->close();
            } catch (\Exception $e) {
                return "ZIP Close Error: " . $e->getMessage();
            }
            
            // Read ZIP content to memory
            if (file_exists($tempZipFile)) {
                $zipContent = file_get_contents($tempZipFile);
                @unlink($tempZipFile); // Delete temp file
                
                // Clean up source PDF folder recursively
                $this->deleteDir($tempDir);
                
                // Send Output
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
                header('Content-Length: ' . strlen($zipContent));
                header('Pragma: no-cache');
                header('Expires: 0');
                echo $zipContent;
                exit;
            } else {
                return "ZIP file was not created properly";
            }

        } else {
            return "Could not create ZIP archive";
        }
    }

    /**
     * Cancel Batch - Delete temp files
     */
    public function cancelBatch() {
        $batchId = $this->request->getVar('batch_id');
        if($batchId) {
             $tempDir = WRITEPATH . 'temp/' . $batchId . '/';
             $this->deleteDir($tempDir);
             return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false]);
    }

    /**
     * Recursive delete directory
     */
    private function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /**
     * Print batch PDF - creates ZIP in memory (Legacy / Single Request)
     */
    public function printBatch()
    {
        $type = $this->request->getGet('type'); // 'application' or 'confirmation'
        $year = $this->request->getGet('year');
        $ids = $this->request->getGet('ids');
        
        if (empty($type) || empty($ids)) {
            return "กรุณาระบุประเภทและรายการที่ต้องการพิมพ์";
        }
        
        $idArray = explode(',', $ids);
        $idArray = array_values(array_filter(array_map('trim', $idArray))); // Reset keys
        
        if (empty($idArray)) {
            return "ไม่พบรายการที่ต้องการพิมพ์";
        }

        // Load mPDF
        if (file_exists(SHARED_LIB_PATH . '/mpdf/vendor/autoload.php')) {
            require_once SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        } else {
            return "mPDF library not found at: " . SHARED_LIB_PATH . '/mpdf/vendor/autoload.php';
        }

        $customFontDir = SHARED_LIB_PATH . '/vendor/mpdf/mpdf/ttfonts';
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $TH_Month = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        
        $count = count($idArray);
        
        // If only 1 file, output directly
        if ($count === 1) {
            return $this->printSinglePDF($idArray[0], $type, $TH_Month, $fontDirs, $customFontDir, $fontData);
        }
        
        // Multiple files - create ZIP in memory using php://temp
        $pdfData = []; // Store PDF content in memory
        
        foreach ($idArray as $id) {
            $recruit = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getRow();
            if (!$recruit) continue;
            
            // Use simple filename with number to avoid encoding issues
            $prefix = ($type === 'application') ? 'application' : 'confirmation';
            $filename = sprintf('%s_%04d.pdf', $prefix, $recruit->recruit_id);
            
            if ($type === 'application') {
                $pdfContent = $this->generateApplicationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData);
            } else {
                $pdfContent = $this->generateConfirmationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData);
            }
            
            if (!empty($pdfContent)) {
                $pdfData[$filename] = $pdfContent;
            }
        }
        
        if (empty($pdfData)) {
            return "ไม่สามารถสร้างไฟล์ PDF ได้";
        }
        
        // Create ZIP in memory using php://temp
        $zipFilename = ($type === 'application' ? 'applications' : 'confirmations') . '_' . date('Ymd_His') . '.zip';
        $tempStream = fopen('php://temp', 'r+');
        
        $zip = new \ZipArchive();
        $tempZipPath = 'php://temp';
        
        // Use a temporary file path for ZipArchive (required by ZipArchive)
        $tempFile = tempnam(sys_get_temp_dir(), 'zip');
        $zipResult = $zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        
        if ($zipResult === TRUE) {
            foreach ($pdfData as $filename => $content) {
                $zip->addFromString($filename, $content);
            }
            $zip->close();
            
            // Read ZIP content into memory
            $zipContent = file_get_contents($tempFile);
            
            // Delete temp file immediately
            @unlink($tempFile);
            
            // Send ZIP directly to browser
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
            header('Content-Length: ' . strlen($zipContent));
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            
            // Clean up mPDF temp files
            $this->cleanupMpdfTempFiles();
            
            echo $zipContent;
            exit;
        } else {
            @unlink($tempFile);
            $this->cleanupMpdfTempFiles();
            return "ไม่สามารถสร้างไฟล์ ZIP ได้ (Error code: " . $zipResult . ")";
        }
    }

    /**
     * Print single PDF directly
     */
    private function printSinglePDF($id, $type, $TH_Month, $fontDirs, $customFontDir, $fontData)
    {
        if ($type === 'application') {
            $content = $this->generateApplicationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData);
            $filename = 'application_' . sprintf('%04d', $id) . '.pdf';
        } else {
            $content = $this->generateConfirmationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData);
            $filename = 'confirmation_' . sprintf('%04d', $id) . '.pdf';
        }
        
        if (empty($content)) {
            return "ไม่สามารถสร้างไฟล์ PDF ได้";
        }
        
        // Clean up mPDF temp files
        $this->cleanupMpdfTempFiles();
        
        // Use header() directly to avoid encoding issues
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        
        echo $content;
        exit;
    }

    /**
     * Clean up mPDF temporary files (SVG, etc)
     */
    private function cleanupMpdfTempFiles()
    {
        $files = glob(WRITEPATH . 'temp/_tempSVG*.svg');
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        
        // Also clean other mmpdf temp files if needed
        $files = glob(WRITEPATH . 'temp/tmp_*'); 
        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Generate Application PDF File (returns PDF content as string)
     */
    private function generateApplicationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData)
    {
        $recruit = $this->db->table('tb_recruitstudent')
            ->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->where('recruit_id', $id)
            ->get()->getRow();

        if (!$recruit) {
            return '';
        }

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [$customFontDir]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabun.ttf',
                    'I' => 'THSarabun-Italic.ttf',
                    'B' => 'THSarabun-Bold.ttf',
                    'BI' => 'THSarabun-BoldItalic.ttf',
                ]
            ],
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);

        $mpdf->SetDocTemplate('uploads/recruitstudent/registerSKJ.pdf', true);

        $html = $this->generateApplicationPDF($id, $TH_Month);
        $mpdf->WriteHTML($html);
        
        return $mpdf->Output('', 'S');
    }

    /**
     * Generate Confirmation PDF File using AdminControlSurrender logic
     */
    private function generateConfirmationPDFFile($id, $TH_Month, $fontDirs, $customFontDir, $fontData)
    {
        $recruit = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getRow();
        if (!$recruit) {
            return '';
        }
        
        $studentId = $recruit->recruit_idCard;
        $Year = $recruit->recruit_year;

        $confrim = $this->db->table('skjacth_personnel.tb_students')
            ->where('stu_iden', $studentId)
            ->get()->getRow();

        if (!$confrim) {
            return ''; // Student hasn't confirmed yet
        }

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [$customFontDir]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'THSarabun.ttf',
                    'I' => 'THSarabun-Italic.ttf',
                    'B' => 'THSarabun-Bold.ttf',
                    'BI' => 'THSarabun-BoldItalic.ttf',
                ]
            ],
            'default_font_size' => 16,
            'default_font' => 'sarabun',
            'tempDir' => WRITEPATH . 'temp'
        ]);

        $mpdf->SetTitle($confrim->stu_prefix . $confrim->stu_fristName . ' ' . $confrim->stu_lastName);
        
        // Set template based on level
        if ($recruit->recruit_regLevel >= 4) {
            $mpdf->SetDocTemplate('uploads/confirm/confirmM4All.pdf', true);
        } else {
            $mpdf->SetDocTemplate('uploads/confirm/confirmM1All.pdf', true);
        }

        // Generate Page 1 HTML (same as AdminControlSurrender)
        $html = $this->generateConfirmationPage1($recruit, $confrim, $TH_Month);
        $mpdf->WriteHTML($html);

        // Add Page 2 (Parent info)
        $mpdf->AddPage();
        $html2 = $this->generateConfirmationPage2($studentId, $confrim, $TH_Month);
        $mpdf->WriteHTML($html2);

        return $mpdf->Output('', 'S');
    }

    /**
     * Helper to get Checkmark Symbol (Text)
     * Use dejavusans font which is built-in to mPDF for symbols
     */
    private function getCheckmarkImage($width = 24, $height = 24)
    {
        // Use symbol instead of image for best performance and no temp files
        $fontSize = $width - 2; // Slightly smaller than container
        return '<span style="font-family: dejavusans; font-size: '.$fontSize.'px; line-height: 1;">✔</span>';
    }

    /**
     * Generate Confirmation Page 1 HTML
     */
    private function generateConfirmationPage1($recruit, $confrim, $TH_Month)
    {
        $idstu = str_replace('-', '', $confrim->stu_iden);
        $date_Y = date('Y') + 543;
        $date_D = (int)date('d');
        $date_M = date('n');

        $date_Y_birt = date('Y', strtotime($confrim->stu_birthDay)) + 543;
        $date_D_birt = (int)date('d', strtotime($confrim->stu_birthDay));
        $date_M_birt = date('n', strtotime($confrim->stu_birthDay));

        $imgUrl = get_recruit_file_url($recruit->recruit_img, $recruit->recruit_regLevel, 'img');

        $html = '<div style="position:absolute;top:577px;left:263px; width:100%; font-size:1.5rem">' . $idstu[0] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:305px; width:100%; font-size:1.5rem">' . $idstu[1] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:343px; width:100%; font-size:1.5rem">' . $idstu[2] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:380px; width:100%; font-size:1.5rem">' . $idstu[3] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:415px; width:100%; font-size:1.5rem">' . $idstu[4] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:455px; width:100%; font-size:1.5rem">' . $idstu[5] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:490px; width:100%; font-size:1.5rem">' . $idstu[6] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:530px; width:100%; font-size:1.5rem">' . $idstu[7] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:565px; width:100%; font-size:1.5rem">' . $idstu[8] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:600px; width:100%; font-size:1.5rem">' . $idstu[9] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:645px; width:100%; font-size:1.5rem">' . $idstu[10] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:680px; width:100%; font-size:1.5rem">' . $idstu[11] . '</div>';
        $html .= '<div style="position:absolute;top:577px;left:722px; width:100%; font-size:1.5rem">' . $idstu[12] . '</div>';

        $html .= '<div style="position:absolute;top:30px;left:50px; width:100%">เลขที่สมัคร ' . $recruit->recruit_id . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:420px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:475px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:550px; width:100%">' . $date_Y . '</div>';
        $html .= '<div style="position:absolute;top:75px;left:663px; width:100%"><img style="width: 100px;height:130px;" src="' . $imgUrl . '"></div>';
        
        $regLevel = $confrim->stu_regLevel ?? $recruit->recruit_regLevel;
        
        $html .= '<div style="position:absolute;top:105px;left:230px; width:100%">' . $regLevel . '</div>';
        $html .= '<div style="position:absolute;top:105px;left:470px; width:100%">' . $recruit->recruit_year . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:140px; width:100%">' . $confrim->stu_prefix . $confrim->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:400px; width:100%">' . $confrim->stu_lastName . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:120px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:250px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:158px;left:470px; width:100%">' . $date_Y . '</div>';

        $html .= '<div style="position:absolute;top:243px;left:340px; width:100%">' . $confrim->stu_prefix . $confrim->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:243px;left:530px; width:100%">' . $confrim->stu_lastName . '</div>';

        $html .= '<div style="position:absolute;top:510px;left:250px; width:100%">' . $date_D_birt . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:420px; width:100%">' . $TH_Month[$date_M_birt - 1] . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:650px; width:100%">' . $date_Y_birt . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:235px; width:100%">' . ($confrim->stu_birthTambon ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:420px; width:100%">' . ($confrim->stu_birthDistrict ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:531px;left:620px; width:100%">' . ($confrim->stu_birthProvirce ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:553px;left:240px; width:100%">' . ($confrim->stu_birthHospital ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:618px;left:160px; width:100%">' . ($confrim->stu_nationality ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:330px; width:100%">' . ($confrim->stu_race ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:520px; width:100%">' . ($confrim->stu_religion ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:680px; width:100%">' . ($confrim->stu_bloodType ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:640px;left:180px; width:100%">' . ($confrim->stu_diseaes ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:668px;left:370px; width:100%">' . ($confrim->stu_numberSibling ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:668px;left:620px; width:100%">' . ($confrim->stu_firstChild ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:690px;left:530px; width:100%">' . ($confrim->stu_numberSiblingSkj ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:690px;left:700px; width:100%">' . ($confrim->stu_nickName ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:710px;left:120px; width:100%">' . ($confrim->stu_disablde ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:290px; width:100%">' . ($confrim->stu_wieght ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:410px; width:100%">' . ($confrim->stu_hieght ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:710px;left:640px; width:100%">' . ($confrim->stu_talent ?? '') . '</div>';

        // Checkmarks
        $checkMarkImg = $this->getCheckmarkImage(16, 16);
        $checkMark = '<div style="position:absolute;top:%dpx;left:%dpx; width:100%%">' . $checkMarkImg . '</div>';
        
        if (($confrim->stu_parenalStatus ?? '') == 'อยู่ด้วยกัน') $html .= sprintf($checkMark, 740, 178);
        else if (($confrim->stu_parenalStatus ?? '') == 'แยกกันอยู่') $html .= sprintf($checkMark, 740, 270);
        else if (($confrim->stu_parenalStatus ?? '') == 'หย่าร้าง') $html .= sprintf($checkMark, 740, 365);
        else if (($confrim->stu_parenalStatus ?? '') == 'บิดาถึงแก่กรรม') $html .= sprintf($checkMark, 740, 448);
        else if (($confrim->stu_parenalStatus ?? '') == 'มารดาถึงแก่กรรม') $html .= sprintf($checkMark, 740, 565);
        else if (($confrim->stu_parenalStatus ?? '') == 'บิดาหรือมารดาแต่งงานใหม่') $html .= sprintf($checkMark, 765, 178);

        if (($confrim->stu_presentLife ?? '') == 'อยู่กับบิดาและมารดา') $html .= sprintf($checkMark, 790, 225);
        else if (($confrim->stu_presentLife ?? '') == 'อยู่กับบิดาหรือมารดา') $html .= sprintf($checkMark, 790, 360);
        else if (($confrim->stu_presentLife ?? '') == 'บุคคลอื่น') $html .= sprintf($checkMark, 790, 510);
        
        $html .= '<div style="position:absolute;top:787px;left:620px; width:100%">' . ($confrim->stu_personOther ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:814px;left:310px; width:100%">' . ($confrim->stu_hCode ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:495px; width:100%">' . ($confrim->stu_hNumber ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:585px; width:100%">' . ($confrim->stu_hMoo ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:814px;left:665px; width:100%">' . ($confrim->stu_hRoad ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:120px; width:100%">' . ($confrim->stu_hTambon ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:300px; width:100%">' . ($confrim->stu_hDistrict ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:490px; width:100%">' . ($confrim->stu_hProvince ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:835px;left:675px; width:100%">' . ($confrim->stu_hPostCode ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:857px;left:190px; width:100%">' . ($confrim->stu_phone ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:857px;left:450px; width:100%">' . ($confrim->stu_email ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:883px;left:340px; width:100%">' . ($confrim->stu_cNumber ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:450px; width:100%">' . ($confrim->stu_cMoo ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:520px; width:100%">' . ($confrim->stu_cRoad ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:883px;left:660px; width:100%">' . ($confrim->stu_cTumbao ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:110px; width:100%">' . ($confrim->stu_cDistrict ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:300px; width:100%">' . ($confrim->stu_cProvince ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:490px; width:100%">' . ($confrim->stu_cPostcode ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:905px;left:640px; width:100%">' . ($confrim->stu_phone ?? '') . '</div>';

        if (($confrim->stu_natureRoom ?? '') == 'บ้านตนเอง') $html .= sprintf($checkMark, 935, 130);
        else if (($confrim->stu_natureRoom ?? '') == 'เช่าอยู่') $html .= sprintf($checkMark, 935, 227);
        else if (($confrim->stu_natureRoom ?? '') == 'อาศัยผู้อื่นอยู่') $html .= sprintf($checkMark, 935, 300);
        else if (($confrim->stu_natureRoom ?? '') == 'บ้านพักราชการ') $html .= sprintf($checkMark, 935, 405);
        else if (($confrim->stu_natureRoom ?? '') == 'วัด') $html .= sprintf($checkMark, 935, 525);
        else if (($confrim->stu_natureRoom ?? '') == 'หอพัก') $html .= sprintf($checkMark, 935, 570);

        $html .= '<div style="position:absolute;top:950px;left:250px; width:100%">' . ($confrim->stu_farSchool ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:950px;left:470px; width:100%">' . ($confrim->stu_travel ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:977px;left:153px; width:100%">' . ($confrim->stu_gradLevel ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:977px;left:260px; width:100%">' . ($confrim->stu_schoolfrom ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:230px; width:100%">' . ($confrim->stu_schoolTambao ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:450px; width:100%">' . ($confrim->stu_schoolDistrict ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:999px;left:630px; width:100%">' . ($confrim->stu_schoolProvince ?? '') . '</div>';

        if (($confrim->stu_usedStudent ?? '') == 'ไม่เคย') $html .= sprintf($checkMark, 1030, 487);
        else if (($confrim->stu_usedStudent ?? '') == 'เคย') $html .= sprintf($checkMark, 1030, 560);
        
        $html .= '<div style="position:absolute;top:1026px;left:690px; width:100%">' . ($confrim->stu_inputLevel ?? '') . '</div>';

        $html .= '<div style="position:absolute;top:1055px;left:200px; width:100%">' . ($confrim->stu_phoneUrgent ?? '') . '</div>';
        $html .= '<div style="position:absolute;top:1055px;left:550px; width:100%">' . ($confrim->stu_phoneFriend ?? '') . '</div>';

        return $html;
    }

    /**
     * Generate Confirmation Page 2 HTML (Parent info) - COMPLETE VERSION
     */
    private function generateConfirmationPage2($studentId, $confrim, $TH_Month)
    {
        $checkMarkImg = $this->getCheckmarkImage(16, 16);
        $checkMark = '<div style="position:absolute;top:%dpx;left:%dpx; width:100%%">' . $checkMarkImg . '</div>';

        $date_Y = date('Y') + 543;
        $date_D = (int)date('d');
        $date_M = date('n');

        $html2 = '';

        // Father Data
        $father = $this->db->table('skjacth_personnel.tb_parent')->where('par_stuID', $studentId)->where('par_relationKey', "พ่อ")->get()->getRow();
        
        if ($father) {
            $par_decease = ($father->par_decease ?? '0000-00-00') == '0000-00-00' ? "" : $father->par_decease;

            $html2 .= '<div style="position:absolute;top:129px;left:195px; width:100%">' . ($father->par_prefix ?? '') . ($father->par_firstName ?? '') . ' ' . ($father->par_lastName ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:150px;left:275px; width:100%">' . ($father->par_ago ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:180px;left:197px; width:100%">' . ($father->par_IdNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:205px;left:197px; width:100%">' . ($father->par_national ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:230px;left:197px; width:100%">' . ($father->par_race ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:255px;left:197px; width:100%">' . ($father->par_religion ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:280px;left:197px; width:100%">' . ($father->par_career ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:305px;left:197px; width:100%">' . ($father->par_education ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:330px;left:197px; width:100%">' . ($father->par_salary ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:355px;left:197px; width:100%">' . ($father->par_positionJob ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:380px;left:197px; width:100%">' . ($father->par_phone ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:405px;left:197px; width:100%">' . $par_decease . '</div>';

            // Father Home Address
            $html2 .= '<div style="position:absolute;top:430px;left:255px; width:100%">' . ($father->par_hNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:430px;left:335px; width:100%">' . ($father->par_hMoo ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:452px;left:245px; width:100%">' . ($father->par_hTambon ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:474px;left:245px; width:100%">' . ($father->par_hDistrict ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:496px;left:245px; width:100%">' . ($father->par_hProvince ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:518px;left:280px; width:100%">' . ($father->par_hPostcode ?? '') . '</div>';

            // Father Current Address
            $html2 .= '<div style="position:absolute;top:540px;left:255px; width:100%">' . ($father->par_cNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:540px;left:335px; width:100%">' . ($father->par_cMoo ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:562px;left:245px; width:100%">' . ($father->par_cTambon ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:584px;left:245px; width:100%">' . ($father->par_cDistrict ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:605px;left:245px; width:100%">' . ($father->par_cProvince ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:628px;left:280px; width:100%">' . ($father->par_cPostcode ?? '') . '</div>';

            // Father Housing
            if (($father->par_rest ?? '') == 'บ้านตนเอง') $html2 .= sprintf($checkMark, 655, 200);
            else if (($father->par_rest ?? '') == 'เช่าบ้าน') $html2 .= sprintf($checkMark, 680, 200);
            else if (($father->par_rest ?? '') == 'อาศัยผู้อื่น') $html2 .= sprintf($checkMark, 705, 200);
            else if (($father->par_rest ?? '') == 'บ้านพักสวัสดิการ') $html2 .= sprintf($checkMark, 730, 200);
            else if (($father->par_rest ?? '') == 'อื่นๆ') $html2 .= sprintf($checkMark, 755, 200);

            $html2 .= '<div style="position:absolute;top:750px;left:280px; width:100%">' . ($father->par_restOrthor ?? '') . '</div>';

            // Father Service
            if (($father->par_service ?? '') == 'กระทรวง') {
                $html2 .= sprintf($checkMark, 780, 200);
                $html2 .= '<div style="position:absolute;top:775px;left:280px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
            } else if (($father->par_service ?? '') == 'กรม') {
                $html2 .= sprintf($checkMark, 803, 200);
                $html2 .= '<div style="position:absolute;top:797px;left:250px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
            } else if (($father->par_service ?? '') == 'กอง') {
                $html2 .= sprintf($checkMark, 828, 200);
                $html2 .= '<div style="position:absolute;top:820px;left:250px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
            } else if (($father->par_service ?? '') == 'ฝ่าย/แผนก') {
                $html2 .= sprintf($checkMark, 850, 200);
                $html2 .= '<div style="position:absolute;top:845px;left:285px; width:100%">' . ($father->par_serviceName ?? '') . '</div>';
            }

            // Father Claim
            if (($father->par_claim ?? '') == 'เบิกได้') $html2 .= sprintf($checkMark, 875, 200);
            else if (($father->par_claim ?? '') == 'เบิกไม่ได้') $html2 .= sprintf($checkMark, 875, 270);
        }

        // Mother Data
        $mother = $this->db->table('skjacth_personnel.tb_parent')->where('par_stuID', $studentId)->where('par_relationKey', "แม่")->get()->getRow();
        
        if ($mother) {
            $par_decease_Ma = ($mother->par_decease ?? '0000-00-00') == '0000-00-00' ? "" : $mother->par_decease;

            $html2 .= '<div style="position:absolute;top:129px;left:400px; width:100%">' . ($mother->par_prefix ?? '') . ($mother->par_firstName ?? '') . ' ' . ($mother->par_lastName ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:150px;left:475px; width:100%">' . ($mother->par_ago ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:180px;left:400px; width:100%">' . ($mother->par_IdNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:205px;left:400px; width:100%">' . ($mother->par_national ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:230px;left:400px; width:100%">' . ($mother->par_race ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:255px;left:400px; width:100%">' . ($mother->par_religion ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:280px;left:400px; width:100%">' . ($mother->par_career ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:305px;left:400px; width:100%">' . ($mother->par_education ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:330px;left:400px; width:100%">' . ($mother->par_salary ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:355px;left:400px; width:100%">' . ($mother->par_positionJob ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:380px;left:400px; width:100%">' . ($mother->par_phone ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:405px;left:400px; width:100%">' . $par_decease_Ma . '</div>';

            // Mother Home Address
            $html2 .= '<div style="position:absolute;top:430px;left:455px; width:100%">' . ($mother->par_hNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:430px;left:530px; width:100%">' . ($mother->par_hMoo ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:452px;left:450px; width:100%">' . ($mother->par_hTambon ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:474px;left:450px; width:100%">' . ($mother->par_hDistrict ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:496px;left:450px; width:100%">' . ($mother->par_hProvince ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:518px;left:480px; width:100%">' . ($mother->par_hPostcode ?? '') . '</div>';

            // Mother Current Address
            $html2 .= '<div style="position:absolute;top:540px;left:455px; width:100%">' . ($mother->par_cNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:540px;left:530px; width:100%">' . ($mother->par_cMoo ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:562px;left:450px; width:100%">' . ($mother->par_cTambon ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:584px;left:450px; width:100%">' . ($mother->par_cDistrict ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:605px;left:450px; width:100%">' . ($mother->par_cProvince ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:628px;left:480px; width:100%">' . ($mother->par_cPostcode ?? '') . '</div>';

            // Mother Housing
            if (($mother->par_rest ?? '') == 'บ้านตนเอง') $html2 .= sprintf($checkMark, 655, 400);
            else if (($mother->par_rest ?? '') == 'เช่าบ้าน') $html2 .= sprintf($checkMark, 680, 400);
            else if (($mother->par_rest ?? '') == 'อาศัยผู้อื่น') $html2 .= sprintf($checkMark, 705, 400);
            else if (($mother->par_rest ?? '') == 'บ้านพักสวัสดิการ') $html2 .= sprintf($checkMark, 730, 400);
            else if (($mother->par_rest ?? '') == 'อื่นๆ') $html2 .= sprintf($checkMark, 755, 400);

            $html2 .= '<div style="position:absolute;top:750px;left:480px; width:100%">' . ($mother->par_restOrthor ?? '') . '</div>';

            // Mother Service
            if (($mother->par_service ?? '') == 'กระทรวง') {
                $html2 .= sprintf($checkMark, 780, 400);
                $html2 .= '<div style="position:absolute;top:775px;left:480px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
            } else if (($mother->par_service ?? '') == 'กรม') {
                $html2 .= sprintf($checkMark, 803, 400);
                $html2 .= '<div style="position:absolute;top:797px;left:450px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
            } else if (($mother->par_service ?? '') == 'กอง') {
                $html2 .= sprintf($checkMark, 828, 400);
                $html2 .= '<div style="position:absolute;top:820px;left:450px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
            } else if (($mother->par_service ?? '') == 'ฝ่าย/แผนก') {
                $html2 .= sprintf($checkMark, 850, 400);
                $html2 .= '<div style="position:absolute;top:845px;left:485px; width:100%">' . ($mother->par_serviceName ?? '') . '</div>';
            }

            // Mother Claim
            if (($mother->par_claim ?? '') == 'เบิกได้') $html2 .= sprintf($checkMark, 875, 400);
            else if (($mother->par_claim ?? '') == 'เบิกไม่ได้') $html2 .= sprintf($checkMark, 875, 470);
        }

        // Guardian Data
        $guardian = $this->db->table('skjacth_personnel.tb_parent')->where('par_stuID', $studentId)->where('par_relationKey', "ผู้ปกครอง")->get()->getRow();
        
        if ($guardian) {
            $par_decease_Pu = ($guardian->par_decease ?? '0000-00-00') == '0000-00-00' ? "" : $guardian->par_decease;

            $html2 .= '<div style="position:absolute;top:115px;left:600px; width:100%">' . ($guardian->par_prefix ?? '') . ($guardian->par_firstName ?? '') . ' ' . ($guardian->par_lastName ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:133px;left:710px; width:100%">' . ($guardian->par_relation ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:155px;left:675px; width:100%">' . ($guardian->par_ago ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:177px;left:600px; width:100%">' . ($guardian->par_IdNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:205px;left:600px; width:100%">' . ($guardian->par_national ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:230px;left:600px; width:100%">' . ($guardian->par_race ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:255px;left:600px; width:100%">' . ($guardian->par_religion ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:280px;left:600px; width:100%">' . ($guardian->par_career ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:305px;left:600px; width:100%">' . ($guardian->par_education ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:330px;left:600px; width:100%">' . ($guardian->par_salary ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:355px;left:600px; width:100%">' . ($guardian->par_positionJob ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:380px;left:600px; width:100%">' . ($guardian->par_phone ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:405px;left:600px; width:100%">' . $par_decease_Pu . '</div>';

            // Guardian Home Address
            $html2 .= '<div style="position:absolute;top:428px;left:655px; width:100%">' . ($guardian->par_hNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:428px;left:730px; width:100%">' . ($guardian->par_hMoo ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:450px;left:650px; width:100%">' . ($guardian->par_hTambon ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:472px;left:650px; width:100%">' . ($guardian->par_hDistrict ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:494px;left:650px; width:100%">' . ($guardian->par_hProvince ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:516px;left:680px; width:100%">' . ($guardian->par_hPostcode ?? '') . '</div>';

            // Guardian Current Address
            $html2 .= '<div style="position:absolute;top:537px;left:655px; width:100%">' . ($guardian->par_cNumber ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:537px;left:730px; width:100%">' . ($guardian->par_cMoo ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:559px;left:650px; width:100%">' . ($guardian->par_cTambon ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:581px;left:650px; width:100%">' . ($guardian->par_cDistrict ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:602px;left:650px; width:100%">' . ($guardian->par_cProvince ?? '') . '</div>';
            $html2 .= '<div style="position:absolute;top:625px;left:680px; width:100%">' . ($guardian->par_cPostcode ?? '') . '</div>';

            // Guardian Housing
            if (($guardian->par_rest ?? '') == 'บ้านตนเอง') $html2 .= sprintf($checkMark, 655, 600);
            else if (($guardian->par_rest ?? '') == 'เช่าบ้าน') $html2 .= sprintf($checkMark, 680, 600);
            else if (($guardian->par_rest ?? '') == 'อาศัยผู้อื่น') $html2 .= sprintf($checkMark, 705, 600);
            else if (($guardian->par_rest ?? '') == 'บ้านพักสวัสดิการ') $html2 .= sprintf($checkMark, 730, 600);
            else if (($guardian->par_rest ?? '') == 'อื่นๆ') $html2 .= sprintf($checkMark, 755, 600);

            $html2 .= '<div style="position:absolute;top:747px;left:680px; width:100%">' . ($guardian->par_restOrthor ?? '') . '</div>';

            // Guardian Service
            if (($guardian->par_service ?? '') == 'กระทรวง') {
                $html2 .= sprintf($checkMark, 777, 600);
                $html2 .= '<div style="position:absolute;top:773px;left:680px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
            } else if (($guardian->par_service ?? '') == 'กรม') {
                $html2 .= sprintf($checkMark, 803, 600);
                $html2 .= '<div style="position:absolute;top:797px;left:650px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
            } else if (($guardian->par_service ?? '') == 'กอง') {
                $html2 .= sprintf($checkMark, 828, 600);
                $html2 .= '<div style="position:absolute;top:820px;left:650px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
            } else if (($guardian->par_service ?? '') == 'ฝ่าย/แผนก') {
                $html2 .= sprintf($checkMark, 850, 600);
                $html2 .= '<div style="position:absolute;top:845px;left:685px; width:100%">' . ($guardian->par_serviceName ?? '') . '</div>';
            }
        }

        // Date at bottom
        $html2 .= '<div style="position:absolute;top:993px;left:230px; width:100%">' . $date_D . '</div>';
        $html2 .= '<div style="position:absolute;top:993px;left:350px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html2 .= '<div style="position:absolute;top:993px;left:540px; width:100%">' . $date_Y . '</div>';

        return $html2;
    }

    /**
     * Generate Application Form PDF HTML
     */
    private function generateApplicationPDF($id, $TH_Month)
    {
        $recruit = $this->db->table('tb_recruitstudent')
            ->select('tb_recruitstudent.*, tb_quota.quota_explain, tb_quota.quota_key, tb_course.course_fullname')
            ->join('tb_quota', 'tb_quota.quota_id = tb_recruitstudent.recruit_category', 'left')
            ->join('tb_course', 'tb_course.course_id = tb_recruitstudent.recruit_tpyeRoom_id', 'left')
            ->where('recruit_id', $id)
            ->get()->getRow();

        if (!$recruit) {
            return '';
        }

        $date_Y = date('Y', strtotime($recruit->recruit_birthday)) + 543;
        $date_D = date('d', strtotime($recruit->recruit_birthday));
        $date_M = date('n', strtotime($recruit->recruit_birthday));

        $date_Y_regis = date('Y', strtotime($recruit->recruit_date)) + 543;
        $date_D_regis = date('d', strtotime($recruit->recruit_date));
        $date_M_regis = date('n', strtotime($recruit->recruit_date));

        $birthDate = new \DateTime($recruit->recruit_birthday);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        $sch = explode("โรงเรียน", $recruit->recruit_oldSchool);
        $oldSchool = ($sch[0] == '' && isset($sch[1])) ? $sch[1] : $sch[0];

        $html = '';
        $imgUrl = get_recruit_file_url($recruit->recruit_img, $recruit->recruit_regLevel, 'img');
        
        if (!empty($recruit->recruit_img)) {
            $html .= '<div style="position:absolute;top:90px;left:635px; width:100%"><img style="width: 120px;height:100px;" src="'.$imgUrl.'"></div>';
        }
        
        $html .= '<div style="position:absolute;top:18px;left:100px; width:100%;font-size:16px;">'.$recruit->quota_explain.'</div>';
        $html .= '<div style="position:absolute;top:180px;left:555px; width:100%;font-size:24px;">'.$recruit->recruit_regLevel.'</div>';
        $html .= '<div style="position:absolute;top:63px;left:700px; width:100%">'.sprintf("%04d",$recruit->recruit_id).'</div>';
        $html .= '<div style="position:absolute;top:280px;left:180px; width:100%">'.$recruit->recruit_prefix.$recruit->recruit_firstName.'</div>';
        $html .= '<div style="position:absolute;top:280px;left:470px; width:100%">'.$recruit->recruit_lastName.'</div>';
        $html .= '<div style="position:absolute;top:307px;left:270px; width:100%">'.$oldSchool.'</div>';
        $html .= '<div style="position:absolute;top:335px;left:170px; width:100%">'.$recruit->recruit_district.'</div>';
        $html .= '<div style="position:absolute;top:335px;left:510px; width:100%">'.$recruit->recruit_province.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:160px; width:100%">'.$date_D.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:240px; width:100%">'.$TH_Month[$date_M-1].'</div>';
        $html .= '<div style="position:absolute;top:363px;left:370px; width:100%">'.$date_Y.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:470px; width:100%">'.$age.'</div>';
        $html .= '<div style="position:absolute;top:363px;left:600px; width:100%">'.$recruit->recruit_race.'</div>';
        $html .= '<div style="position:absolute;top:390px;left:162px; width:100%">'.$recruit->recruit_nationality.'</div>';
        $html .= '<div style="position:absolute;top:390px;left:300px; width:100%">'.$recruit->recruit_religion.'</div>';
        $html .= '<div style="position:absolute;top:390px;left:540px; width:100%">'.$recruit->recruit_idCard.'</div>';
        $html .= '<div style="position:absolute;top:418px;left:350px; width:100%">'.$recruit->recruit_phone.'</div>';
        $html .= '<div style="position:absolute;top:418px;left:600px; width:100%">'.$recruit->recruit_grade.'</div>';
        $html .= '<div style="position:absolute;top:445px;left:270px; width:100%">'.$recruit->recruit_homeNumber.'</div>';
        $html .= '<div style="position:absolute;top:445px;left:390px; width:100%">'.$recruit->recruit_homeGroup.'</div>';
        $html .= '<div style="position:absolute;top:445px;left:475px; width:100%">'.$recruit->recruit_homeRoad.'</div>';
        $html .= '<div style="position:absolute;top:445px;left:615px; width:100%">'.$recruit->recruit_homeSubdistrict.'</div>';
        $html .= '<div style="position:absolute;top:475px;left:180px; width:100%">'.$recruit->recruit_homedistrict.'</div>';
        $html .= '<div style="position:absolute;top:475px;left:400px; width:100%">'.$recruit->recruit_homeProvince.'</div>';
        $html .= '<div style="position:absolute;top:475px;left:620px; width:100%">'.$recruit->recruit_homePostcode.'</div>';
        $html .= '<div style="position:absolute;top:503px;left:695px; width:100%;font-size:22px;">'.$recruit->recruit_regLevel.'</div>';
        
        // Course Selection
        if ($recruit->quota_key == "normal") {
            $SubCourse = explode('|', $recruit->recruit_majorOrder);
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            foreach ($SubCourse as $key => $v_SubCourse) {
                $CheckCourse = $this->db->table('tb_course')->select('course_initials')->where('course_id', $v_SubCourse)->get()->getRow();
                if ($CheckCourse) {
                    $html .= "ลำดับที่ ".($key+1).' '.$CheckCourse->course_initials."<br>";
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div style="position:absolute;top:570px;left:200px; width:100%">';
            $courseDisplay = !empty($recruit->course_fullname) ? $recruit->course_fullname : $recruit->recruit_tpyeRoom;
            $html .= "ลำดับที่ 1 ".$courseDisplay. ' สาขา '.$recruit->recruit_major;
            $html .= '</div>';
        }

        $html .= '<div style="position:absolute;top:880px;left:340px; width:100%">'.$recruit->recruit_prefix.$recruit->recruit_firstName.' '.$recruit->recruit_lastName.'</div>';
        $html .= '<div style="position:absolute;top:905px;left:350px; width:100%">'.$date_D_regis.' '.$TH_Month[$date_M_regis-1].' '.$date_Y_regis.'</div>';

        // Documents Checkmarks
        $checkMarkImg = $this->getCheckmarkImage(32, 32);

        if (!empty($recruit->recruit_certificateEdu)) {
            $html .= '<div style="position:absolute;top:790px;left:110px; width:100%">'.$checkMarkImg.'</div>';
        }
        if (!empty($recruit->recruit_copyidCard)) {
            $html .= '<div style="position:absolute;top:790px;left:325x; width:100%">'.$checkMarkImg.'</div>';
        }
        if (!empty($recruit->recruit_img)) {
            $html .= '<div style="position:absolute;top:788px;left:560px; width:100%">'.$checkMarkImg.'</div>';
        }

        return $html;
    }

    /**
     * Generate Confirmation Form PDF HTML
     */
    private function generateConfirmationPDF($id, $TH_Month)
    {
        $recruit = $this->db->table('tb_recruitstudent')->where('recruit_id', $id)->get()->getRow();
        if (!$recruit) {
            return '';
        }
        
        $studentId = $recruit->recruit_idCard;
        $Year = $recruit->recruit_year;

        $confrim = $this->db->table('skjacth_personnel.tb_students')
            ->where('stu_iden', $studentId)
            ->get()->getRow();

        if (!$confrim) {
            return ''; // Student hasn't confirmed yet
        }

        $idstu = str_replace('-', '', $confrim->stu_iden);

        $date_Y = date('Y') + 543;
        $date_D = (int)date('d');
        $date_M = date('n');

        $date_Y_birt = date('Y', strtotime($confrim->stu_birthDay)) + 543;
        $date_D_birt = (int)date('d', strtotime($confrim->stu_birthDay));
        $date_M_birt = date('n', strtotime($confrim->stu_birthDay));

        $imgUrl = get_recruit_file_url($recruit->recruit_img, $recruit->recruit_regLevel, 'img');

        // Build HTML (simplified version - similar structure to AdminControlSurrender::print)
        $html = '';
        
        // ID Card digits
        for ($i = 0; $i < 13; $i++) {
            $leftPos = 263 + ($i * 37);
            if ($i > 0) $leftPos = 263 + 42 + (($i - 1) * 35);
            $html .= '<div style="position:absolute;top:577px;left:'.$leftPos.'px; width:100%; font-size:1.5rem">'.$idstu[$i].'</div>';
        }

        $html .= '<div style="position:absolute;top:30px;left:50px; width:100%">เลขที่สมัคร ' . $recruit->recruit_id . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:420px; width:100%">' . $date_D . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:475px; width:100%">' . $TH_Month[$date_M - 1] . '</div>';
        $html .= '<div style="position:absolute;top:463px;left:550px; width:100%">' . $date_Y . '</div>';

        $html .= '<div style="position:absolute;top:75px;left:663px; width:100%"><img style="width: 100px;height:130px;" src="' . $imgUrl . '"></div>';
        
        $regLevel = $confrim->stu_regLevel ?? $recruit->recruit_regLevel;
        
        $html .= '<div style="position:absolute;top:105px;left:230px; width:100%">' . $regLevel . '</div>';
        $html .= '<div style="position:absolute;top:105px;left:470px; width:100%">' . $Year . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:140px; width:100%">' . $confrim->stu_prefix . $confrim->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:130px;left:400px; width:100%">' . $confrim->stu_lastName . '</div>';

        $html .= '<div style="position:absolute;top:243px;left:340px; width:100%">' . $confrim->stu_prefix . $confrim->stu_fristName . '</div>';
        $html .= '<div style="position:absolute;top:243px;left:530px; width:100%">' . $confrim->stu_lastName . '</div>';

        $html .= '<div style="position:absolute;top:510px;left:250px; width:100%">' . $date_D_birt . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:420px; width:100%">' . $TH_Month[$date_M_birt - 1] . '</div>';
        $html .= '<div style="position:absolute;top:510px;left:650px; width:100%">' . $date_Y_birt . '</div>';

        $html .= '<div style="position:absolute;top:618px;left:160px; width:100%">' . $confrim->stu_nationality . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:330px; width:100%">' . $confrim->stu_race . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:520px; width:100%">' . $confrim->stu_religion . '</div>';
        $html .= '<div style="position:absolute;top:618px;left:680px; width:100%">' . $confrim->stu_bloodType . '</div>';

        return $html;
    }

    /**
     * Legacy print_all method (keep for backward compatibility)
     */
    public function print_all()
    {
        return redirect()->to(site_url('skjadmin/reports'));
    }
}
