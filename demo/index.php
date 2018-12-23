<?php

	$tags_arr=array(
		'volist'=>'volist',
		'/volist'=>'volist',
		'foreach'=>'foreach',
		'/foreach'=>'foreach',
		'for'=>'for',
		'/for'=>'for',
		'switch'=>'switch',
		'/switch'=>'switch',
		'case'=>'case',
		'/case'=>'case',
		'default/'=>'default',
		'eq'=>'eq',
		'/eq'=>'eq',
		'equal'=>'equal',
		'/equal'=>'equal',
		'neq'=>'neq',
		'/neq'=>'neq',
		'notequal'=>'notequal',
		'/notequal'=>'notequal',
		'else/'=>'else',
		'gt'=>'gt',
		'/gt'=>'gt',
		'egt'=>'egt',
		'/egt'=>'egt',
		'lt'=>'lt',
		'/lt'=>'lt',
		'elt'=>'elt',
		'/elt'=>'elt',
		'heq'=>'heq',
		'/heq'=>'heq',
		'nheq'=>'nheq',
		'/nheq'=>'nheq',
		'compare'=>'compare',
		'/compare'=>'compare',
		'in'=>'in',
		'/in'=>'in',
		'notin'=>'notin',
		'/notin'=>'notin',
		'between'=>'between',
		'/between'=>'between',
		'notbetween'=>'notbetween',
		'/notbetween'=>'notbetween',
		'range'=>'range',
		'/range'=>'range',
		'if'=>'if',
		'/if'=>'if',
		'elseif/'=>'elseif',
		'present'=>'present',
		'/present'=>'present',
		'notpresent'=>'notpresent',
		'/notpresent'=>'notpresent',
		'empty'=>'empty',
		'/empty'=>'empty',
		'notempty'=>'notempty',
		'/notempty'=>'notempty',
		'defined'=>'defined',
		'/defined'=>'defined',
		'notdefined'=>'notdefined',
		'/notdefined'=>'notdefined',
		'assign/'=>'assign',
		'define/'=>'define',
		'import/'=>'import',
		'load/'=>'load',
		'js/'=>'js',
		'css/'=>'css',
		'php'=>'php',
		'/php'=>'php',
		'literal'=>'literal',
		'/literal'=>'literal',
		'include/'=>'include',
		'layout/'=>'layout',
		'block'=>'block',
		'/block'=>'block',
		'extend/'=>'extend',
		'taglib/'=>'taglib'
	);
	$tags_50=array(
		'include'=>'{include file="public/header" /}'
	);
	$reg_arr=array(
		'<php\s+[^>]*>|<php>'=>array('<','\s+[^>]*>|<','php>'),
		'<\s*/\s*if\s*>'=>array('<\s*/\s*','\s*'),
		'<input\s+[^>]*/>'=>array('<','\s+[^>]*/>')
	);
	$req_arr=array(
		['ACTION_NAME','$Request.action'],
		['9j9hlBFbGbGVe49ceR70ysVVQKAFY0d5','{$Think.CONFIG.AMAPJS_AK}']
	);
//\$_SESSION\[["|'][_a-zA-Z0-9]*["|']\]   $_SESSION['agid']  4
//<php\s+[^>]*>|<php>  匹配标签  1
//<\s*/\s*if\s*>  匹配结束标签  2
//<input\s+[^>]*/> 匹配 <else / >  3
//{include file='roster/amap' /}    5
//ACTION_NAME   {extend name="admin@public/base" /} http://api.map.baidu.com/api?v=2.0&ak={$Think.CONFIG.AMAPJS_AK}9j9hlBFbGbGVe49ceR70ysVVQKAFY0d5 
	$dir= './t/';    
	$dirs=scandir($dir);
	foreach ($dirs as $key => $value) {
		if($value!='.' && $value!='..'){
			$file_old=file_get_contents($dir.$value);

			foreach ($tags_arr as $k => $v) {
				$matches=array();
				$pos=strpos($k,'/');
				if($pos===false){
					$pattern='/<'.$v.'\s+[^>]*>|<'.$v.'>/';//开始标签
				}else if($pos===0){
					//$pattern='/<\s*\/\s*'.$v.'\s*>/';//结束标签
				}else{
					$pattern='/<'.$v.'\s+[^>]*\/>/';//类else标签
				}
				
				// preg_match("/<(block?)[^>]*>.*?|<.*? \/>/",$file_old,$matches,PREG_OFFSET_CAPTURE);
				// var_dump($matches);
				// exit();
				if($pos===0){
					$file_old=str_replace('<'.$k.'>','{'.$k.'}',$file_old);
				}else{
					while (preg_match($pattern,$file_old,$matches,PREG_OFFSET_CAPTURE)) {
						$strstart='';
						$strend='';
						$old_str='';
						$new_str='';
						$tmp='';
						// var_dump($matches);
						// exit();
						if($v=='include'){
							$old_str=$matches[0][0];
							$tmp=ltrim($matches[0][0],'<');
							$new_str=rtrim($tmp,'>');
							$new_str='{'.$new_str.'}';
							$new_str=str_replace(':','/',$new_str);
							$new_str=strtolower($new_str);
							$file_old=str_replace($old_str,$new_str,$file_old);
						}else if($v=='extend' && $pos){
							$old_str=$matches[0][0];
							$new_str='{extend name="admin@public/base" /}';
							$file_old=str_replace($old_str,$new_str,$file_old);
						}else{
							$old_str=$matches[0][0];
							$tmp=ltrim($matches[0][0],'<');
							$new_str=rtrim($tmp,'>');
							$new_str='{'.$new_str.'}';
							$file_old=str_replace($old_str,$new_str,$file_old);
						}
					}					
				}


			}
			/////////////////
			foreach ($req_arr as $k1 => $v1) {
				$file_old=str_replace($v1[0],$v1[1],$file_old);			
			}
			preg_match('/<td colspan="[0-9]{1,2}" align="right" class="hidden-lg hidden-md hidden-sm">\{\$page_min\}<\/td>/',$file_old,$matches,PREG_OFFSET_CAPTURE);

			$file_new=str_replace($matches[0][0],'',$file_old);
			////////////////////
			file_put_contents($dir.$value.'.new',$file_new);
		}
		
	}
?>