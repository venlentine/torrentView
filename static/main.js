kodReady.push(function(){
    //打开方式关联案例
    kodApp.add({
        name:"torrentView",
        title:"TorrentView",
        ext:"{{config.fileExt}}",
        sort:"{{config.fileSort}}",
        icon:'{{pluginHost}}static/images/icon.png',
        callback:function(path,ext){
            var url = '{{pluginApi}}&path='+core.pathCommon(path);
            if('window' == "{{config.openWith}}"){
                window.open(url);
            }else{
                core.openDialog(url,core.icon(ext),htmlEncode(core.pathThis(path)));
            }
        }
    });
});