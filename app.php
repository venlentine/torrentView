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
    public function info($path){
        $torrent = new Torrent($path);
        //var_dump($torrent->encoding());die;
        $htm = '<dl>';
        $htm .='<dt>private: </dt><dd>' . $torrent->is_private() ? 'yes' : 'no' . '</dd>';
        $htm .='<dt>name: </dt><dd>'. torrent_encoding($torrent->name(), $torrent->encoding()). '</dd>';
        $htm .='<dt>publisher: </dt><dd>'. torrent_encoding($torrent->publisher(), $torrent->encoding()). '</dd>';
        $htm .='<dt>date: </dt><dd>'. date('Y-m-d H:i:s', $torrent->creation_date()). '</dd>';
        $htm .='<dt>announce: </dt><dd>'. torrent_announce($torrent->announce()). '</dd>';
        $htm .='<dt>piece_length: </dt><dd>'. Torrent::format($torrent->piece_length()). '</dd>';
        $htm .='<dt>size: </dt><dd>'. $torrent->size( 2 ). '</dd>';
        $htm .='<dt>hash info: </dt><dd>'. $torrent->hash_info(). '</dd>';
        $htm .='<dt>comment: </dt><dd>'. torrent_encoding($torrent->comment(), $torrent->encoding()). '</dd>';
        $htm .='</dl>';
             //'<br>announce: '; var_dump( $torrent->announce() );
             //'<br>stats: '; var_dump( $torrent->scrape() );
             //echo '<br>source: ', $torrent;
        $files = torrent_files($torrent->name(), $torrent->content(), $torrent->encoding());
        $htm .= '<ul>';
        foreach ($files as $key => $value) {
            $htm .= '<li>'. $value['name']. ' ' . $value['size']. '</li>';
        }
        $htm .= '</ul>';
        return $htm;
    }
}