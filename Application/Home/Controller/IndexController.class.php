<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {

    public function index() {
        $this->display( 'index' );
    }

    public function upload() {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = array( 'doc' , 'docx' ); // 设置附件上传类型
        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        $upload->savePath = 'word'; // 设置附件上传（子）目录
        //写入数据库基本信息
        // 上传文件
        $info = $upload->upload();
        $write_doc = D('doc');
        $write_doc->add( array( 'filename' => $info['word']["name"] , 'uploadfile' => $info['word']['savename'] , 'time' => date('Y-m-d H:i:s') , 'state' => 0) );
        if ( !$info ) {// 上传错误提示错误信息
            $this->error( $upload->getError() );
        } else {// 上传成功
            $this->success( '上传成功！' );
        }
    }

}
