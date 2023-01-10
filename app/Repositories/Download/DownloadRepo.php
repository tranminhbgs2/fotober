<?php

namespace App\Repositories\Download;

use App\Helpers\Constants;
use App\Models\Input;
use App\Repositories\BaseRepo;
use App\Repositories\Order\InputRepo;
use App\Repositories\Order\OutputRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class DownloadRepo extends BaseRepo
{
    private $inputRepo;
    private $outputRepo;

    public function __construct(InputRepo $inputRepo, OutputRepo $outputRepo)
    {
        parent::__construct();

        $this->inputRepo = $inputRepo;
        $this->outputRepo = $outputRepo;
    }

    /**
     * Hàm xử lý upload và cập nhật đường dẫn avatar
     *
     * @param $params
     * @param null $request
     * @param null $customer
     * @return false|mixed
     */
    public function downloadZip($params)
    {
        $order_id = $params['order_id'];
        $user_id = $params['user_id'];
        if($order_id && $user_id){
            $inputs = $this->inputRepo->getListing(['order_id' => $order_id, 'customer_id' => $user_id]);
            $zip = new ZipArchive();
            $zip_name = 'fotober_'.date('d_m_Y_His').".zip"; // Zip name
            // Tạo thư mục lưu lại nếu chưa tồn tại
            //$dir_save_path = 'storage/uploads/order/' . date('Y/m'). '/zip';
            $dir_save_path = 'storage/uploads/order/zip';
            if (!file_exists($dir_save_path)) {
                mkdir($dir_save_path, 0777, true);
            }

            if($zip->open($dir_save_path . '/'.$zip_name,  ZipArchive::CREATE) === TRUE){
                // return $zip_name;
                foreach ($inputs as $file) {
                    //if(file_exists('storage/'.$file->file) && $file->type == 'UPLOAD'){
                    if($file->file && file_exists('storage/'.$file->file)){
                        $zip->addFile(('storage/'.$file->file), basename($file->name));
                    } else{
                        echo"file does not exist";
                    }
                }
                $zip->close();
            }

            return public_path($dir_save_path.'/'.$zip_name);

        } else{
            return false;
        }
    }

    /**
     * Hàm xử lý down load output
     *
     * @param $params
     * @param null $request
     * @param null $customer
     * @return false|mixed
     */
    public function downloadOutputZip($params)
    {
        $order_id = $params['order_id'];
        if($order_id){
            $outputs = $this->outputRepo->getListing(['order_id' => $order_id, 'page_size' => 100]);
            $zip = new ZipArchive();
            $zip_name = 'fotober_output_'.date('d_m_Y_His').".zip"; // Zip name
            // Tạo thư mục lưu lại nếu chưa tồn tại
            //$dir_save_path = 'storage/uploads/order/' . date('Y/m'). '/zip';
            $dir_save_path = 'storage/uploads/order/zip';
            if (!file_exists($dir_save_path)) {
                mkdir($dir_save_path, 0777, true);
            }

            if($zip->open($dir_save_path . '/'.$zip_name,  ZipArchive::CREATE) === TRUE){
                // return $zip_name;
                foreach ($outputs as $key => $file) {
                    //if(file_exists('storage/'.$file->file) && $file->type == 'UPLOAD'){
                    if($file->file && file_exists('storage/'.$file->file)){
                        $arr_name = explode('/', $file->file);
                        $zip->addFile(('storage/'.$file->file), basename($arr_name[2]));
                    } else{
                        echo"file does not exist";
                    }
                }
                $zip->close();
            }

            return public_path($dir_save_path.'/'.$zip_name);

        } else{
            return false;
        }
    }
}
