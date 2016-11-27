<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>professional web preview </title>
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/preview.css"/>


    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.min.js"></script>

</head>

<body>

<section id="root">
</section>


<script>

    /*********************************************从后台获取数据***********************************************/
    var sectionInfo=<?php echo $this->_var['section_info']; ?>;

    var projectPath='/'+<?php echo $this->_var['project_path_js']; ?>;

    //守住入口的img Url
    for(let i=0;i<sectionInfo.length;i++){
        let r=/\/images\//;
        if(r.exec(sectionInfo[i]['content'])){
            sectionInfo[i]['content']=sectionInfo[i]['content'].replace(/\/images\//,`${projectPath}/images/`)
        }
    }

    /*********************************************对原始元素初始化*********************************************/
    {
        let root=$('#root');

        //对页面原有元素初始化
        for(let i=0;i<sectionInfo.length;i++) {
            //在dom 层面更新数据
            root.append(`<div id="${sectionInfo[i]['elementId']}" class="element type${sectionInfo[i]['elementTypeId']}">${sectionInfo[i]['content']}</div>`);
            $(`#${sectionInfo[i]['elementId']}`).css({
                left: `${sectionInfo[i]['positionX']}px`,
                top: `${sectionInfo[i]['positionY']}px`,
                width: `${sectionInfo[i]['width']}px`,
                height: `${sectionInfo[i]['height']}px`,
                transform: `rotate(${sectionInfo[i]['degree']}rad)`,
                'z-index': `${sectionInfo[i]['zIndex']}`
            });
        }
    }

    $(document).ready(function(){
        for(let i=0;i<sectionInfo.length;i++) {

        }
    })


</script>


</body>
</html>