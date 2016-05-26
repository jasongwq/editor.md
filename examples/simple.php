<?php
if (isset($_GET['mdname'])) {
    $mdname=$_GET['mdname'];
}
else{
    $mdname="";
}
?>
<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8" />
        <title>Simple example - Editor.md examples</title>
        <script type="text/javascript">
            //实现不带参数时自动载入上次页面
            if (""==<?php echo '"'.$mdname.'"'; ?>) {
                var hrl='./Simple.php?mdname=';
                if (""!=localStorage.mdname) {
                    window.location=hrl.concat(localStorage.mdname);
                }else{
                    window.location=hrl.concat('undefined');
                }
                // alert("s");
                // window.location='http://www.baidu.com';
            }
        </script>
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="../css/editormd.css" />
        <!-- <link rel="shortcut icon" href="https://pandao.github.io/editor.md/favicon.ico" type="image/x-icon" /> -->
    </head>
    <body>
        <div id="layout">
            <header>
                <h1>example</h1>
            </header>
            <div class="btns">
                <button id="update-btn">update</button>
                <button id="getpdf-btn">update</button>

                <!-- <button id="get-html-btn">Get HTML</button> -->
            </div>
            <div id="test-editormd">
                <textarea style="display:none;"></textarea>
            </div>
        </div>
        <script src="js/jquery.min.js"></script>
        <script src="../editormd.min.js"></script>
        <script type="text/javascript">
function md_update_up(editormd) {
    $.post("./php/post.php",
    {
        tpye:"md_update_up",
        name:<?php echo '"'.$mdname.'",';?>
        text:editormd.getMarkdown()
    }
    )
}
function md_update_down(editormd) {
    $.post("./php/post.php",{
        tpye:"md_update_down",
        name:<?php echo '"'.$mdname.'"';?>
    },
    function (data,status) {
        // alert("Data: " + data + "\nStatus: " + status);
        editormd.setMarkdown(data);
    }
    )
}
function md_update(editormd) {
    $.post("./php/post.php",
    {
        tpye:"md_update_status",
        name:<?php echo '"'.$mdname.'",';?>
        time:<?php echo "localStorage.".$mdname."_mdtime";?>
    },
    function(data,status){
        console.log("Data: " + data + "\nStatus: " + status);
        if ("up"==data) {
            md_update_up(editormd);
        }else if ("down"==data) {
            md_update_down(editormd);
        }else{
            editormd.setMarkdown(<?php echo "localStorage.".$mdname."_mdtext";?>)
        }
    });
}
function readText(editormd) {
    editormd.setMarkdown(localStorage.<?php echo $mdname."_";?>mdtext);
}
function saveName() {
    localStorage.mdname=<?php echo '"'.$mdname.'";'; ?>
}
function saveDate() {
    localStorage.<?php echo $mdname."_";?>mdtext=testEditor.getMarkdown();
    var time=new Date();
    localStorage.<?php echo $mdname."_";?>mdtime=parseInt(time.getTime()/1000);
}
function localSave(infunc){
    try{
        infunc();
    }catch(e)
    {
        localStorage.clear();
        console.log(e);
    }
}
function autoSave() {
    // 为了取消初次加载时触发的onchange导致的保存 使用>1
    if (isChange>1) {
        isChange=1;
        localSave(saveDate);
    }
    setTimeout("autoSave()",1000);
}
        </script>
        <script type="text/javascript">
			var testEditor;
            var isChange=0;
            $(function() {
                testEditor = editormd("test-editormd", {
                    width   : "90%",
                    height  : 640,
                    syncScrolling : "single",
                    path    : "../lib/",
                    saveHTMLToTextarea: true,
                    // disabledKeyMaps:[],
                    onload : function() {
                        var keyMap = {
                            "Ctrl-S": function(cm) {
                                localSave(saveDate);
                            },
                            "Esc": function(cm) {
                                alert("Esc");
                            },
                            "Ctrl-A": function(cm) { // default Ctrl-A selectAll
                                cm.execCommand("selectAll");
                            }
                        };
                        // setting signle key
                        var keyMap2 = {
                              "Ctrl-T": function(cm) {
                                alert("Ctrl+T");
                              }
                        };
                        localSave(saveName);
                        readText(this);
                        this.addKeyMap(keyMap);
                        md_update(testEditor);
                        // alert('s');
                        autoSave(testEditor)
                        
                        // this.addKeyMap(keyMap2);
                        // this.removeKeyMap(keyMap2);  // remove signle key
                    },
                    onchange : function(){
                        // console.log("onchange =>", this, this.id, this.settings, this.state);
                        isChange++;
                        // alert('k');
                    }
                });
                $("#update-btn").bind('click', function(){
                    //alert(testEditor.getMarkdown());
                    localSave(saveDate);
                    md_update(testEditor);
                });
                $("getpdf-btn").bind(click,function(){
                    $.post("./php/post.php",
                    {
                        tpye:"md_htmltopdf",
                        name:<?php echo '"'.$mdname.'",';?>
                    },
                    function(data,status){
                        console.log("Data: " + data + "\nStatus: " + status);
                        window.open(data);
                    });
                })
            });
        </script>
    </body>
</html>

<<?php 
/**
20160521 :实现不带参数时自动载入上次页面
20160521 :autoSave
*/
 ?>