<?php
/**
 * ownCloud - Files Copy plugin
 *
 * @author Caro Huang
 * @copyright 2013 www.u-sync.com
 *
 * 檔案列表中，檔案複製操作
 *
 */

class OC_FilesCopy {
    /**
     * 取得指定目錄的 tree 結構
     */
    static function getDirTree($dir) {
        $dirTree = OC_Files::getDirectoryContent($dir, 2, 'FilesCopy');
        return $dirTree;
    }

    /**
     * 複製檔案
     * @param 來源檔案所在的目錄,目標目錄,來源檔名array or string
     * @return 失敗的來源檔名array
     */
    static function copyToTarget($dir, $destDir, $files) {
        $files = OC_Helper::strToArr($files, ';');
        $successedFiles = array();
        $failedFiles = array();
        foreach ($files as $fileName) {
            $fromPath = OC_Helper::pathForbiddenChar($dir . '/' . $fileName);
            $destPath = OC_Helper::pathForbiddenChar($destDir . '/' . $fileName);
            # 如果目的地是在來源路徑底下
            if (stripos($destPath, $fromPath) === 0 && strlen($fromPath) <= strlen($destDir)) {
                return;
            }
            if (OC_Filesystem::copy($fromPath, $destPath)) {
                $successedFiles[] = $fileName;
            } else {
                $failedFiles[] = $fileName;

            }
        }
        return array(
            'successedFiles' => $successedFiles,
            'failedFiles' => $failedFiles
        );
    }

}
?>