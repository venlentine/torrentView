<?php
class torrentViewPlugin extends PluginBase{
    function __construct(){
        parent::__construct();
    }
    public function regiest(){
        $this->hookRegiest(array(
            'user.commonJs.insert' => 'epubReaderPlugin.echoJs',
        ));
    }
    public function echoJs($st,$act){
        if($this->isFileExtence($st,$act)){
            $this->echoFile('static/app/main.js');
        }
    }
    public function index(){
        $path = $this->filePath($this->in['path']);
        $fileUrl  = _make_file_proxy($path);
        $fileName = get_path_this(rawurldecode($this->in['path']));
        include(dirname(__FILE__).'/php/template.html');
    }
    //封面图片:解压获取并输出(首次缓存)
    public function cover(){
    }
}