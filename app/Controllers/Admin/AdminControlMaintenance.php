<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminControlMaintenance extends BaseController
{
    protected $junkFiles = [
        'check_ambiguous.php',
        'check_column.php',
        'check_data.php',
        'fix_column_and_update.php',
        'list_columns.php',
        'update_category_db.php',
        'update_category_db_retry.php',
        'upload.php',
        'delete.php',
        'Control_admin_confirm.php',
        'Control_admin_print.php',
        'Control_students.php',
        'UserAuth.php',
        'auth-login-basic.html',
        'index1.html'
    ];

    protected $junkPatterns = [
        '/^skjacth_admission.*\.sql$/',
        '/^tb_personnel.*\.sql$/',
        '/^Screenshot.*\.png$/',
        '/^line_oa_logo\.png$/'
    ];

    protected $junkDirectories = [
        'FormData'
    ];

    public function index()
    {
        $this->checkAuth();

        $data['title'] = 'บำรุงรักษาหน้าต่างระบบ';
        $data['root_junks'] = $this->scanRootJunk();
        $data['writable_stats'] = $this->getWritableStats();

        return view('Admin/PageAdminMaintenance/PageAdminMaintenanceIndex', $data);
    }

    protected function checkAuth()
    {
        if (!session()->get('login_id') && !session()->get('pers_id')) {
            header('Location: ' . site_url('admin/login'));
            exit;
        }
    }

    protected function scanRootJunk()
    {
        $junks = [];
        $rootPath = ROOTPATH;
        $files = scandir($rootPath);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..')
                continue;

            $isJunk = false;
            if (in_array($file, $this->junkFiles)) {
                $isJunk = true;
            } else {
                foreach ($this->junkPatterns as $pattern) {
                    if (preg_match($pattern, $file)) {
                        $isJunk = true;
                        break;
                    }
                }
            }

            if ($isJunk && is_file($rootPath . $file)) {
                $junks[] = [
                    'name' => $file,
                    'path' => $rootPath . $file,
                    'size' => filesize($rootPath . $file),
                    'type' => 'file'
                ];
            }

            if (in_array($file, $this->junkDirectories) && is_dir($rootPath . $file)) {
                $junks[] = [
                    'name' => $file,
                    'path' => $rootPath . $file,
                    'size' => $this->getDirSize($rootPath . $file),
                    'type' => 'directory'
                ];
            }
        }

        return $junks;
    }

    protected function getWritableStats()
    {
        $stats = [
            'sessions' => ['count' => 0, 'size' => 0],
            'logs' => ['count' => 0, 'size' => 0],
            'cache' => ['count' => 0, 'size' => 0],
            'debugbar' => ['count' => 0, 'size' => 0],
        ];

        $stats['sessions'] = $this->getDirDetails(WRITEPATH . 'session');
        $stats['logs'] = $this->getDirDetails(WRITEPATH . 'logs');
        $stats['cache'] = $this->getDirDetails(WRITEPATH . 'cache');
        $stats['debugbar'] = $this->getDirDetails(WRITEPATH . 'debugbar');

        return $stats;
    }

    protected function getDirDetails($dir)
    {
        $count = 0;
        $size = 0;
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..', '.gitignore', 'index.html', '.htaccess']);
            foreach ($files as $file) {
                if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                    $count++;
                    $size += filesize($dir . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        return ['count' => $count, 'size' => $size];
    }

    protected function getDirSize($dir)
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    public function cleanRoot()
    {
        $this->checkAuth();
        $junks = $this->scanRootJunk();
        $deletedCount = 0;

        foreach ($junks as $junk) {
            if ($junk['type'] === 'file') {
                if (@unlink($junk['path']))
                    $deletedCount++;
            } else {
                if ($this->deleteDir($junk['path']))
                    $deletedCount++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "ลบไฟล์และโฟลเดอร์ขยะสำเร็จทั้งหมด $deletedCount รายการ"
        ]);
    }

    public function cleanWritable($type)
    {
        $this->checkAuth();
        $path = '';
        switch ($type) {
            case 'sessions':
                $path = WRITEPATH . 'session';
                break;
            case 'logs':
                $path = WRITEPATH . 'logs';
                break;
            case 'cache':
                $path = WRITEPATH . 'cache';
                break;
            case 'debugbar':
                $path = WRITEPATH . 'debugbar';
                break;
        }

        if (empty($path) || !is_dir($path)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Path ไม่ถูกต้อง']);
        }

        $files = array_diff(scandir($path), ['.', '..', '.gitignore', 'index.html', '.htaccess']);
        $count = 0;
        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            // Don't delete active session file if possible, but CI handling is usually fine.
            if (is_file($filePath)) {
                if (@unlink($filePath))
                    $count++;
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => "ล้าง $type สำเร็จ $count รายการ"]);
    }

    protected function deleteDir($dirPath)
    {
        if (!is_dir($dirPath))
            return false;
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
        return rmdir($dirPath);
    }
}
