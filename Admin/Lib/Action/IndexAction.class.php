<?php
require './vendor/autoload.php';
use QL\QueryList;
class IndexAction extends BaseAction {
    public function index(){
        $word = $_GET['kw'];
        if($word){
            $url = '/word/'.urlencode($word).'.html';
            redirect($url);
            die();
        }
        $this->display();
    }

    public function search(){
    	header("Content-type:text/html;charset=UTF-8");
    	$word = $_GET['kw'];
    	$page = $_GET['p'];
        $rules = array(
			"title"=>array(".h","text"),
	        "url"=>array(".link","href"),
	        'text'=>array('span>span',"text"),
			'img' => array(".r div img","src"),
	        'link' => array(".r div a","href"),
        	'info' => array(".r>a","href")
		);
		//获取页面源码
		$and = "";
		if ($page) {
			$and .= "&p=".$page;
		}
// 		http://www.btcherry.info/search?keyword=%E7%BE%8E%E5%A5%B3
		$wordlast = urlencode($word);
		$html = C("BTURL").$wordlast.$and;
		//采集
		$data = QueryList::Query($html,$rules);
        $list = $data->data;
        $y = 0;
        for ($x = 0;$x < count($list);$x++) {
        	$list[$x]['createtime'] = $list[$y]['text'];
        	$y++;
        	$list[$x]['size'] = $list[$y]['text'];
        	$y++;
        	$list[$x]['filenumber'] = $list[$y]['text'];
        	$y++;
        	$list[$x]['info'] = substr($list[$x]['info'],6);
        }
        $count = count($list);
        for ($i = 0;$i < $count;$i++) {
        	if ($i >= 20) {
        		unset($list[$i]);																																																																																																																																																																						
        	}
        }
        foreach ($list as $key => $val){
            $bt = $val['url'];
            $bt = explode("/hash/",$bt);
            $list[$key]['url'] = $bt[1];
        }
        $zz = "/totalPages:(.*?),/";
        $pagenum = preg_match($zz, $data->html, $find);
        $pagenum = trim($find[1]);
        if($pagenum > 1){
        	$page = $page == 0 ? 1 : $page;
        	$pagesize = 20;
        	$pagestr.= $page == 1 ? '<span class="current"> 首页 </span>' : '<a href="?s=Index/search/kw/'.$word.'"> 首页 </a>';
        	$link = ceil($page/5);
        	for($i=($link-1)*5;$i<=($link*5)+1;$i++){
        		if($i <= $pagenum && $i >0){
        			if($i == $page){
        				$pagestr.= "<span class=\"current\"> ".$i." </span>";
        			}else{
        				$pagestr.= '<a href="?s=Index/search/kw/'.$word.'/p/'.$i.'"> '.$i.' </a>';
        			}
        
        		}
        	}
        	$pagestr.= $page == $pagenum ? "<span class=\"current\"> 尾页 </span>" : '<a href="?s=Index/search/kw/'.$word.'/p/'.$pagenum.'"> 尾页 </a>';
        }
        $this->list = $list;
        $this->page = $pagestr;
        $this->title = urldecode($word);
		$this->p = $page;
		$this->pagenum = $pagenum;
		$this->display();
    }

    public function read(){
        $url = "http://www.btcherry.com/hash/".$_GET['hash'];
        $reg = array("title"=>array("h1","text"),
            'magnet'=>array('ul li a',"href"),
            'creadtime' => array('ul li span:eq(2)',"text"),
            'size' => array('ul li span:eq(4)',"text"),
            'filenum' => array('ul li span:eq(6)',"text"),
            'filelist' => array('#filelist li:element',"text")
            );
        $hj = QueryList::Query($url,$reg);
        $list = $hj->data;
		$html = $hj->html;
		preg_match("/html\(\"(.*)\"\)/", $html, $titleall);
        foreach ($list as $key => $val){
			$filelist = str_replace("BT樱桃 [www.btcherry.com]",C("web_name")."[".C('web_url')."]",$val['filelist']);
            $time = explode("：",$val['creadtime']);
            $list[$key]['creadtime'] = $time[1];
            $size = explode("：",$val['size']);
            $list[$key]['size'] = $size[1];
            $list[$key]['filenum'] = $filenum[1];
			$list[$key]['title'] = $val['title'];
			$filelist = preg_replace("/[\t\n\r]+/","<br/>",$filelist);
            $list[$key]['filelist'] = preg_replace("/[\t\n\r]+/","<br/>",$filelist);
        }
		
       // print_R($list);
        $this->assign($list[0]);
        $this->display();
    }
}