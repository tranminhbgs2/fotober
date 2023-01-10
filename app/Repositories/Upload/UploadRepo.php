<?php

namespace App\Repositories\Upload;

use App\Helpers\Constants;
use App\Models\Customer;
use App\Repositories\BaseRepo;
use Carbon\Carbon;

class UploadRepo extends BaseRepo
{
    private $customer_model;

    public function __construct(Customer $customer)
    {
        parent::__construct();

        $this->customer_model = $customer;
    }

    /**
     * Hàm xử lý upload và cập nhật đường dẫn avatar
     *
     * @param $params
     * @param null $request
     * @param null $customer
     * @return false|mixed
     */
    public function updateFile($params)
    {
        $id = $params['id'];
        $file_avatar = $params['file'];
        if($file_avatar && is_file($file_avatar)){
            $filename_avatar =  time() . '_' . $id . '.' . $file_avatar->getClientOriginalExtension();
            $db_path_save = $this->_processUpload($file_avatar, $filename_avatar, $params['type']);
            if ($db_path_save) {
                if(isset($params['name_file'])){
                    return ['path' => $db_path_save, 'name_file' => $filename_avatar];
                }
                return $db_path_save;
            }
        }
        return false;
    }

    /**
     * Hàm xử lý upload ảnh vào storage --------------------------------------------------------------------------------
     *
     * @param $file
     * @param $filename
     * @return false|string|string[]
     */
    private function _processUpload($file, $filename, $type)
    {
        if (is_file($file) && $filename) {

            switch($type){
                case 'ORDER':
                    $base_path = Constants::UPLOAD_IMAGE_ORDER;
                break;
                case 'AVATAR':
                    $base_path = Constants::UPLOAD_IMAGE_AVATAR;
                break;
                case 'OUTPUT':
                    $base_path = Constants::UPLOAD_IMAGE_OUTPUT;
                break;
            }
            // Tạo thư mục lưu lại nếu chưa tồn tại
            //$dir_save_path = $base_path . '/' . date('Y/m');
            $dir_save_path = $base_path;
            if (!file_exists($dir_save_path)) {
                mkdir($dir_save_path, 0775, true);
                chown($dir_save_path, Constants::SSH_USER);
                chgrp($dir_save_path, Constants::SSH_GROUP);
            }

            try {
                $db_path = $file->storeAs($dir_save_path, $filename);
                return str_replace('public/', '', $db_path);
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}
