<html>
	<head>
		<meta charset="utf-8" />
		<title>鱼不咸文字识别网页版</title>
		<style>
			*{font-family: 微软雅黑;padding:0;margin:0;}
		#input{
			position: absolute;
			top: 10px;
			left: 783px;
			height: 188;
		  }
		.bigbox {
			display: flex;
		}
		.formbox{
			margin:10px;
		  }
		.soutu-state-normal{
			display: block;
			width: 500px;
			background-color: #fff;    
			border: 1px solid #3385ff;
			z-index: 101;
			padding: 23px 20px 27px;
		  }
		.soutu-drop{
			text-align: center;
			height: 75px;
			margin-bottom: 16px;
			background-color: #fafafa;
		  }
		.upload-wrap {
			position: relative;
			width: 158px;
			height: 43px;
			font-size: 16px;
			border: 1px solid #cacbcc;
			line-height: 43px;
			margin: 0 auto;
			color: #333;
			text-align: center;
		}
		.upload-pic {
			position: absolute;
			font-size: 0;
			width: 100%;
			height: 100%;
			outline: 0;
			opacity: 0;
			filter: alpha(opacity=0);
			margin-left: -31px;
			z-index: 1;
			cursor: pointer;
		}
		.input-up-box{
			position: absolute;
			top: 10;
			left: 550px;
			margin: 0 20px;
			width: 200px;
		  }
		.input-up-box a {
			display: block;
			width: 200px;
			height: 60px;
			text-decoration: none;
		}
		.input-up{
			height: 100px;
			width: 95px;
		  }
		.monitor{
			display: block;
			margin: 10px;
			width: 543px;  
			padding: 10px;
		  }
		.soutu-drop-tip{
			display: block;
			height: 40;
            line-height: 2;
		}
		</style>
    <link id='loadingCss'  href="" rel="stylesheet" type="text/css" />
	<link id='loadingCss2'  href="" rel="stylesheet" type="text/css" />	
	</head>

	<body>
		<div class="bigbox">
			<form id="myForm" action="" method='post' enctype='multipart/form-data'>
				<div class="formbox">
					<div class="soutu-state-normal">
						<div class="soutu-drop">
							<span class="soutu-drop-tip">请选择需要识别的图片上传</span>
							<input type="text" readonly="readonly" style="width: 100%;" id="route" />
						</div>
						<div class="upload-wrap">
							<input type="file" id="file_input" name="file_input" accept="image/*" class="upload-pic" value="上传图片" onchange="show();getFileContent();">
							<span class="upload-text">选择图片文件</span>
						</div>
					</div>
					
					<div class="input-up-box">
						<input type="submit" class="input-up" id="submit" name="submit" value="提交识别" onclick="loading();">	
						<input type="button" class="input-up" value="复制结果" onclick="copyText()">
						
						<a href="/pages/orc/index2.php">目前是<strong>主接口</strong>，</br>点击跳转到备用接口</a>
					</div>
				</div>
			</form>
			<textarea id="input" rows="10" cols="50">选择需要识别的图片上传；点击复制按钮会自动复制！</textarea>	
		</div>
    	
    	<img src="" id="Preview">
    	
<section class="Loading"></section>
<section class="Loading2"></section>
			
<script>
			//预览图
			function getFileContent(){
				//创建文件读取对象
				var $reader = new FileReader();
				
				//获取文件数据
				var $file = document.querySelector("#file_input").files;
				
				//读取文件获取一段以data开头的字符串
				$reader.readAsDataURL($file[0]);
				
				$reader.onload = function(){
					//文件读取完成后：展示图片
					document.querySelector('#Preview').src = $reader.result;
					document.querySelector('#Preview').style.width = "300px";
				}
			}

			//显示待上传文件路径名字
			function show() {
				document.getElementById("route").value = document.getElementById("file_input").value;
			};

			//自动复制识别内容
			function copyText() {
				var text = document.getElementById("text").innerText;
				var input = document.getElementById("input");
				input.value = text; // 修改文本框的内容
				input.select(); // 选中文本
				document.execCommand("copy"); // 执行浏览器复制命令
			};         
          	
          	//上传加载进度
			function loading() {
              document.getElementById("loadingCss").href='css_whir.css?vss=1';
              document.getElementById("loadingCss2").href='orc/css_whir.css?vss=1';
            };
		</script>
	</body>
</html>
<?php
// header("Content-type: text/html; charset=utf-8");

$sfile=$_FILES['file_input']['tmp_name'];
$dfile='uploads/'.$_FILES['file_input']['name'];

//文件上传移动操作
move_uploaded_file($sfile,$dfile);

// 文字识别链接
require_once 'baiducloud-sdk-php/AipOcr.php';

// 你的 APPID AK SK
const APP_ID = '';
const API_KEY = '';
const SECRET_KEY = '';

$client = new AipOcr(APP_ID, API_KEY, SECRET_KEY);

$options = array();
$options["language_type"] = "CHN_ENG";

// 调用通用文字识别, 图片参数为本地图片
$image = file_get_contents($dfile);
$orc = $client->basicAccurate($image,$options);

// 返回数据结果、读取数组个数
// if ($orc['words_result']) {
// 	$length = count($orc['words_result']);
// };
$length = $orc['words_result_num'];
	

echo '<div class="monitor">';
echo '<span id="text">';
for($row=0; $row<$length;$row++){  	
	print_r($orc['words_result'][$row]['words']);  	
	echo '</br>';
}
echo '</span>';
echo '</div>';

// echo "<pre>";print_r($orc['words_result'][0]['words']);echo "<pre>";


//  删除已识别的图片文件
if ($length>0){
	if(unlink($dfile)){
		return;
	}else {
		echo ("deleting Error");
	}
		
}
	


?>
