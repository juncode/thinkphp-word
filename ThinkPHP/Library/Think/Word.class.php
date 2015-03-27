<?php

/**
 * 
 * @author jun_huang <j@wonhsi.com>
 * @since Expression datetime is undefined on line 7, column 13 in Templates/Scripting/EmptyPHP.php.
 * @version $id 1.0
 * @link http://antiy.com
 * @copyright Copyright (c) 2014, Antiy.Com All rights reserved
 */

namespace Think;
class Word{
    /*
     * 默认文档处理配置
     * @var array
     */
    private $config = array(
        'wordRoot' => './Uploads/',
        'pdfPath' => './PDFFile/',
        'swfPath' => './SWFFile/',
        'libreoffice' => 'libreoffice4.4 --invisible --convert-to pdf:writer_pdf_Export --outdir ',
        'pdf2swf' => 'pdf2swf  -T -z -t -s languagedir=/usr/local/share/xpdf/chinese-simplified -s flashversion=9 ' , // -o -f 附加指定
        'logPath' => '',
    );
    
    /*
     * 转换错误数据
     * @var array
     */
    private $error = array( 'code' => '' , 'message' =>'');
    
    /*
     * 文件名称
     * @var object
     */
    private $file ;
    
    /*
     * pdf 中间件变量
     * @var object
     */
    private $pdf;
    
    /*
     * swf 中间件变量
     * @var object
     */
    private $swf;
    
    /*
     * 构造函数，加载配置
     * @param array $config  配置
     */
    public function __construct( $config = array() ) {
        /* 获取配置 */
        $this->config = array_merge( $this->config , $config );
    }
    
    /*
     * 一次性跑完
     * @var boolean
     */
    public function runSwf( $filename ) {
        if ( !$this->setFile( $filename ) ) {
            return false;
        }
        if ( !$this->getSwf() ) {
            return false;
        }
        return true;
    }
    
    /*
     * 设置 $file 实例~ 
     * @return BOOL
     */
    public function setFile( $filename ) {
        if ( !file_exists( $filename ) ) {
            $this->error['code'] = 10;
            $this->error['message'] = '原文件不存在';
            return false;
        }
        $this->file = $filename;
        return true;
    }
    
    public function getSwf() {
        if ( !$this->_toPdf() ) {
            return false;
        }
        if ( !$this->_toSwf() ) {
            return false;
        }
        return true;
    }
    
    public function getSwfName() {
        return $this->config['swfPath'] . $this->swf;
    }
    
    public function _toSwf() {
        if ( empty( $this->config['pdf2swf'] ) ) {
            $this->error['message'] = '命令配置有误，请重新配置';
            $this->error['code'] = __LINE__;
            return false;
        }
        if ( !$this->_checkPath( $this->config['swfPath'] ) ) {
            return false;
        }
        $this->swf = basename( $this->file , end ( explode( '.' , $this->file ) ) );
        $cmd = $this->config['pdf2swf'] . ' -o ' . $this->config['swfPath'] . $this->swf . ' -f ' . $this->config['pdfPath'] . $this->pdf . ' 2>&1 > /dev/null';
        exec( escapeshellcmd( $cmd )  );
        if ( !file_exists( $this->config['swfPath'] . $this->swf ) ) {
            $this->error['message'] = '未能成功创建swf文件';
            $this->error['code'] = __LINE__;
            return false;
        } else {
            return true;
        }        
    }
    
    public function _toPdf() {
        if ( empty( $this->config['libreoffice'] ) ) {
            $this->error['message'] = '命令配置有误,请重新配置';
            $this->error['code'] = __LINE__;
            return false;
        }
        if ( !$this->_checkPath( $this->config['pdfPath'] ) ) {
            return false;
        }
        $cmd = $this->config['libreoffice'] . '"' . $this->config['pdfPath'] . '" "' . $this->file . '" 2>&1 > /dev/null';
        exec ( escapeshellcmd( $cmd ) );
        $this->pdf = basename( $this->file , end ( explode( $this->file, '.' ) ) );
        if ( !file_exists( $this->config['pdfPath'] ) . $this->pdf ) {
            $this->error['message'] = '转换成pdf中失败，请重新操作';
            $this->error['code'] = __LINE__;
            return false;
        } else {
            return true;
        }
    }
    
    /*
     * 检测 pdf 目录是否存在
     * @return BOOL 
     */
    public function _checkPath( $path ) {
        if (!$this->_mkdir( $path ) ) {
            return false;
        } else {
            /* 检测 目录是否可写 */
            if ( !is_writable( $path ) ) {
                $this->error['message'] = "保存目录 {$path} 不可写";
                $this->error['code'] = __LINE__;
                return false;
            } else {
                return true;
            }
        }
    }
    
    /**
     * 创建目录
     * @param string $path 检测目录
     * @return boolean  创建结果    true -- 通过  ,false -- 失败
     */
    public function _mkdir( $path ) {
        if ( is_dir( $path ) ) {
            return true;
        }
        
        if ( mkdir(  $path , 0777 , true ) ) {
            return true;
        } else {
            $this->error['message'] = "目录 {$path} 创建失败!";
            $this->error['code'] = __LINE__;
        }
    }
    
    /*
     * 返回错误信息
     * @var array()
     */
    public function getError() {
        return $this->error;
    }
}