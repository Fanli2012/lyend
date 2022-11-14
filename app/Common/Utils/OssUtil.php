<?php

namespace App\Common\Utils;

use Illuminate\Http\Request;
use OSS\Core\OssException;
use OSS\OssClient;

class OssUtil
{
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $endpoint;
    protected $bucket;

    public function __construct()
    {
        $this->accessKeyId = config('aliyun.accessKeyId');
        $this->accessKeySecret = config('aliyun.accessKeySecret');
        $this->endpoint = config('aliyun.endpoint');
        // 存储空间名称
        $this->bucket = config('aliyun.ossBucket');
    }

    /**
     * @desc 添加文件
     * @param $filePath 上传的文件
     * @param $savePath 保存到oss的路径
     */
    public function uploadFile($filePath, $savePath)
    {
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $ossClient->uploadFile($this->bucket, $savePath, $filePath);
        } catch (OssException $e) {
            return ['code' => 1, 'msg' => $e->getMessage(), 'data' => ''];
        }
        return ['code' => 0, 'msg' => 'success', 'data' => ''];
    }

    /**
     * @desc 删除文件
     * @param deletePath oss的路径
     */
    public function deleteFile($deletePath)
    {
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $ossClient->deleteObject($this->bucket, $deletePath);
        } catch (OssException $e) {
            return ['code' => 1, 'msg' => $e->getMessage(), 'data' => ''];
        }
        return ['code' => 0, 'msg' => 'success', 'data' => ''];
    }

    /**
     * @desc 下载文件
     * @param string $downLoadFile 下载文件地址
     * @param string $saveFile 保存地址
     */
    public function downLoadFile($downLoadFile, $saveFile)
    {
        $localfile = $saveFile;
        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $localfile
        );
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $ossClient->getObject($this->bucket, $downLoadFile, $options);
        } catch (OssException $e) {
            return ['code' => 1, 'msg' => $e->getMessage(), 'data' => ''];
        }
        return ['code' => 0, 'msg' => 'success', 'data' => ''];
    }

}