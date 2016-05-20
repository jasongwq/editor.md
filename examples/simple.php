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
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="../css/editormd.css" />
        <link rel="shortcut icon" href="https://pandao.github.io/editor.md/favicon.ico" type="image/x-icon" />
        <script type="text/javascript">
                       
        </script>
    </head>
    <body>
        <div id="layout">
            <header>
                <h1>example</h1>
            </header>
            <div class="btns">
                <button id="get-md-btn">Get Markdown</button>
                <button id="get-html-btn">Get HTML</button>
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
        alert("Data: " + data + "\nStatus: " + status);
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
        alert("Data: " + data + "\nStatus: " + status);
        if ("up"==data) {
            md_update_up(editormd);
        }else if ("down"==data) {
            md_update_down(editormd);
        }else{
            
        }
    });
}
        </script>
        <script type="text/javascript">
			var testEditor;
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
                                localStorage.<?php echo $mdname."_";?>mdtext=testEditor.getMarkdown();
                                var time=new Date();
                                localStorage.<?php echo $mdname."_";?>mdtime=parseInt(time.getTime()/1000);
                                localStorage.<?php echo $mdname."_";?>mdhtml=testEditor.getHTML();
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
                        this.addKeyMap(keyMap);
                        testEditor.setMarkdown(localStorage.<?php echo $mdname."_";?>mdtext);
                        md_update(testEditor);
                
                        // this.addKeyMap(keyMap2);
                        // this.removeKeyMap(keyMap2);  // remove signle key
                    }
                });
                $("#get-md-btn").bind('click', function(){
                    alert(testEditor.getMarkdown());
                    localStorage.mdtext=testEditor.getMarkdown();
                });
                
                $("#get-html-btn").bind('click', function() {
                    var w = window.open();
                    w.document.open();
                    w.document.write(testEditor.getHTML());
                    w.document.close();
                    var h=testEditor.getHTML();
                    alert(testEditor.getHTML());
                });    
            });
        </script>
    </body>
</html>