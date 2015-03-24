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
        $upload->savePath = 'word/'; // 设置附件上传（子）目录
        //写入数据库基本信息
        // 上传文件
        $info = $upload->uploadOne( $_FILES['word'] );
        $write_doc = D( 'doc' );
        $write_doc->add( array( 'filename' => $info["name"] , 'uploadfile' => $info['savepath'] . $info['savename'] , 'time' => date( 'Y-m-d H:i:s' ) , 'state' => 0 ) );
        if ( !$info ) {// 上传错误提示错误信息
            $this->error( $upload->getError() );
        } else {// 上传成功
            $this->success( '上传成功！' );
        }
    }

    public function run() {
        $word = D('doc');
        $result = $word->where( 'state=0' )->select();
        if ( empty( $result ) ) {
            $this->show('无需要处理的数据' );
            exit(1);
        }
        $word_process = new \Think\Word();
        foreach ( $result as $key => $value ) {
            if ( !$word_process->runSwf( $value['uploadfile'] ) ) {
                $error = $word_process->getError();
                $updata['err'] = $error['message'];
                $updata['state'] = $error['code'];
                $word->where( 'id=' . $value['id'] )->save( $updata );
                continue;
            }
            $word->where('id=' . $value['id'] )->save( array ( 'flashname' => $word_process->getSwfName() , 'state' => 1 ) );
        }
        $this->show('执行完成');
    }

}
