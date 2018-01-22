<?php
require_once(dirname(__FILE__).'/php/Torrent.php');

class torrentViewPlugin extends PluginBase{
    function __construct(){
        parent::__construct();
    }
    public function regiest(){
        $this->hookRegiest(array(
            'user.commonJs.insert' => 'torrentViewPlugin.echoJs',
        ));
    }
    public function echoJs($st,$act){
        if($this->isFileExtence($st,$act)){
            $this->echoFile('static/main.js');
        }
    }
    public function index(){
        $path = $this->filePath($this->in['path']);
        $fileUrl  = _make_file_proxy($path);
        $fileName = get_path_this(rawurldecode($this->in['path']));
        $data = $this->torrent_info($path);
        include(dirname(__FILE__).'/php/template.php');
    }
    //封面图片:解压获取并输出(首次缓存)
    private function torrent_info($path){
        $data = [];
        $torrent = new Torrent($path);
        //文件
        $file_arr = [];
        $files = $this->torrent_files($torrent->name(), $torrent->content(), $torrent->encoding());
        foreach ($files as $key => $value) {
            $file_arr[] = ["name" =>$value['name'], "size" => $value['size']];
        }
        //var_dump($torrent->encoding());die;
        $data[] = ["id"=>"private", "title"=>"Private", "value"=>$torrent->is_private() ? 'yes' : 'no'];
        $data[] = ["id"=>"name", "title"=>"种子名称", "value"=>$this->torrent_encoding($torrent->name(), $torrent->encoding())];
        $data[] = ["id"=>"hash", "title"=>"种子哈希", "value"=>$torrent->hash_info()];

        $magnet = $torrent->magnet( false );
        $magnet = substr($magnet, 0, stripos($magnet, "xl="));
        $magnet = '<a href="javascript:;" data-url="'.$magnet.'" class="btnCopy">点击复制</a>';
        $data[] = ["id"=>"magnet", "title"=>"磁力链接", "value"=>$magnet];
        $data[] = ["id"=>"number", "title"=>"文件数目", "value"=>count($file_arr)];
        $data[] = ["id"=>"size", "title"=>"文件大小", "value"=>$torrent->size( 2 )];
        $data[] = ["id"=>"piece", "title"=>"分块大小", "value"=>Torrent::format($torrent->piece_length())];
        $data[] = ["id"=>"date", "title"=>"发布时间", "value"=>date('Y-m-d H:i:s', $torrent->creation_date())];
        $data[] = ["id"=>"publisher", "title"=>"发布人员", "value"=>$this->torrent_encoding($torrent->publisher(), $torrent->encoding())];
        $data[] = ["id"=>"comment", "title"=>"描述内容", "value"=>$this->torrent_encoding($torrent->comment(), $torrent->encoding())];
        $data['announce'] = ["id"=>"date", "title"=>"服务器链", "value"=>$this->torrent_announce($torrent->announce())];
        $data[] = ["id"=>"files", "title"=>"文件列表", "value"=>$file_arr];
        //$data['stats'] = $torrent->scrape();//耗时
        //var_dump($data);die;
        return $data;
    }

   private function torrent_encoding($content, $encoding = "UTF-8"){
        if(empty($encoding)){
            return $content;
        }
        if(strtoupper($encoding) == "UTF-8"){
            return $content;
        }else{
            return iconv($encoding, "UTF-8", $content);
        }
    }
    private function torrent_announce($content){
        if(is_array($content)){
            if(is_array($content[0])){
                return $content[0][0];
            }else{
                return $content[0];
            }
        }else{
            return $content;
        }
    }
    private function torrent_files($name, $content, $encoding = "UTF-8"){
        $arr = [];
        foreach ($content as $key => $value) {
            if(!stristr($key, 'BitComet')){
                $key = str_replace($name.DIRECTORY_SEPARATOR, "", $key);
                $key = $this->torrent_encoding($key, $encoding);
                $arr[] = ['name' => $key, 'size' => Torrent::format($value)];
            }
        }
        return $arr;
    }
}