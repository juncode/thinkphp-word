<ins datetime="2015-04-21T06:27:41+00:00">也许有多家企业提供word在线处理系统~但他们收费，也许你的需求仅仅是将用户提交的word文件实现线上预览，也许你会让用户改用pdf，因为pdf在浏览器中预览已成熟了。现在基于thinkphp开发了一个在线word上传预览程序，供大家使用。</ins>

<a title="https://github.com/wonhsi/mythinkphp" href="https://github.com/wonhsi/mythinkphp" target="_blank">https://github.com/wonhsi/mythinkphp</a>

word.class.php 文件在 mythinkphp/ThinkPHP/Library/Think/目录下，具体使用说明如下：

在CentOS上安装相关libreoffice,pdf2swf组件，具体安装方法参考<a href="http://www.wonhsi.com/xiaoan/?p=72">centos 下 word 转pdf 实现【 补 pdf 转swf 实现 】</a>

1、实例化对像：
<pre class="lang:php decode:true">$word_process = new \Think\Word( $config ); // array $config 可选参数，初始化配置项</pre>
1.1、配置说明：
<pre class="lang:php decode:true " title="配置项说明">$config = array(
        'wordRoot' =&gt; '',      // word文档存放的目录
        'pdfPath' =&gt; '',       // pdf 处理目录
        'swfPath' =&gt; '',       //swf 文件保存目录
        'libreoffice' =&gt; '',   //libreoffice 执行命令
        'pdf2swf' =&gt; '',       // pdf2swf 执行命令
        'logPath' =&gt; '',       //日志文件保存目录
    );</pre>
2、转换方法：
<pre class="lang:php decode:true " title="执行转换">$word_process-&gt;runSwf( $filename )  // string $filename word文档文件名 return false/true</pre>
3、成功，获取转换后的swf文件名：
<pre class="lang:php decode:true " title="执行成功">$word_process-&gt;getSwfName()   // 获取转换后的swf文件名 return string</pre>
4、失败，获取失败信息：
<pre class="lang:php decode:true " title="执行失败，获取错误信息">$word_process-&gt;getError()  // 获取转换后的swf文件名 返回信息 array(message=&gt; , code=&gt; )</pre>
