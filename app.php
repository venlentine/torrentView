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
        //var_dump($torrent->encoding());die;
        $data['private'] = $torrent->is_private() ? 'yes' : 'no';
        $data['name'] = $this->torrent_encoding($torrent->name(), $torrent->encoding());
        $data['publisher'] = $this->torrent_encoding($torrent->publisher(), $torrent->encoding());
        $data['date'] = date('Y-m-d H:i:s', $torrent->creation_date());
        $data['announce'] = $this->torrent_announce($torrent->announce());
        $data['piece_length'] = Torrent::format($torrent->piece_length());
        $data['size'] = $torrent->size( 2 );
        $data['hash_info'] = $torrent->hash_info();
        $data['comment'] = $this->torrent_encoding($torrent->comment(), $torrent->encoding());
        //$data['stats'] = $torrent->scrape();//耗时
        $data['files'] = [];
        $files = $this->torrent_files($torrent->name(), $torrent->content(), $torrent->encoding());
        foreach ($files as $key => $value) {
            $data['files'][] = ["name" =>$value['name'], "size" => $value['size']];
        }
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
                $key = str_replace($name."\\", "", $key);
                $key = $this->torrent_encoding($key, $encoding);
                $arr[] = ['name' => $key, 'size' => Torrent::format($value)];
            }
        }
        return $arr;
    }
}