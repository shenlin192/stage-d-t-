<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>professional web edit </title>
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/editpage.css"/>
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/sweetalert.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/jquery.ui.rotatable.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/jquery.contextMenu.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/fonts/font-awesome.min.css">


    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/bootstrap.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery-ui.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.ui.rotatable.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/ckeditor/ckeditor.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.contextMenu.min.js"></script>

</head>

<body>
<!--最左侧菜单栏-->
<section id="firstTierMenu">
    <div id="firstTierdiv">
        <ul>
        </ul>
    </div>
</section>
<!--二级菜单显示栏-->
<article id="secondTierMenu">
    <div class="secondTierDiv">
        <p>添加文字</p>
        <span class="close" >X</span>
    </div>
    <div class="secondTierContent" style="display: flex;"></div>
    <!--三级菜单显示栏-->
    <article id="thirdTierMenu">
        <div class="thirdTierContent"></div>
    </article>
</article>


<!--正文内容-->
<!--section 里面有图片、文字（也可以新增图片和文字） section 有背景图-->
<section id="root">

</section>

<!--保存页脚-->
<footer>
    <div>
        <span id="save-page" class="save-btn">保存页面</span>
        <span id="preview-page" class="preview-page">预览页面</span>
    </div>
</footer>

<!--编辑菜单-->
<article id="elementToolbar1" class="elementToolbar">

    <div class="elemBtnBox setting" title="设置当前元素">
        <span class="elemBtn"></span>
        <span class="elemText">设置</span>
    </div>

    <div class="elemBtnBox zindex" title="修改当前元素层级">
        <span class="elemBtn"></span>
        <span class="elemText">层级</span>
        <ul class="elenentToolbarContext">
            <li class="etbcli up">向上一层</li>
            <li class="etbcli down">向下一层</li>
            <li class="etbcli top">顶层</li>
            <li class="etbcli bottom">底层</li>
        </ul>
    </div>

    <div class="elemBtnBox delete" title="删除元素">
        <span class="elemBtn"></span>
        <span class="elemText">删除</span>
    </div>

    <!--<div class="elemBtnBox animate" title="设置动画"><span class="elemBtn"></span><span class="elemText">动画</span></div>-->
    <div class="elemBtnBox copy" title="复制当前元素到剪切板">
        <span class="elemBtn"></span>
        <span class="elemText">复制</span>
    </div>

    <div class="elemBtnBox help" title="">
        <span class="elemBtn">
        </span><span class="elemText">帮助</span>
    </div>
</article>

<!--以后可以尝试增加拖拽上传功能-->
<artical id="imageEditor" class="edit-box">
    <div class="notice">
        <p class="notice-text">上传本机图片</p>
        <span class="close">X</span>
    </div>

    <div class="item-group">
        <div class="input-section">
            <label class="custom-file-upload">
                <img id="previewImg" src=""/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"/>
            </label>
        </div>
    </div>
</artical>
<!--section 背景编辑框-->

<!--
<artical id="sectionEditor" class="edit-box">
    <div class="notice">
        <p class="notice-text">这里可以改变section的背景图片</p>
        <span class="cancel-edit-box">X</span>
    </div>

    <div class="item-group">
        <div class="input-section">
            <label class="custom-file-upload">
                <img id="sectionEditorImg" src=""/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"/>
            </label>
        </div>
    </div>
</artical>
-->

<!--多媒体编辑框-->
<!--
<artical id="multimediaEditor" class="edit-box">
    <div class="notice">
        <p class="notice-text">这里可以改变多媒体的链接地址</p>
        <span class="cancel-edit-box">X</span>
    </div>

    <div class="input-section">
        <div>
            <input type="text">
            <span class="save-btn">确定</span>
        </div>
    </div>
</artical>
-->

<!-----------RulersGuides------------>
<!--<script type="text/javascript" src="https://rawgithub.com/mark-rolich/Event.js/master/Event.js"></script>-->
<!--<script type="text/javascript" src="https://rawgithub.com/mark-rolich/Dragdrop.js/master/Dragdrop.js"></script>-->
<!--<script type="text/javascript" src="/<?php echo $this->_var['project_path']; ?>/js/RulersGuides.js"></script>-->
<!--<script type="text/javascript">-->
    <!--var     evt        = new Event(),-->
            <!--dragdrop   = new Dragdrop(evt),-->
            <!--rg         = new RulersGuides(evt, dragdrop);-->
<!--</script>-->


<script>
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////1 获取数据与初始化////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /*********************************************从后台获取数据***********************************************/
    var sectionInfo=<?php echo $this->_var['section_info']; ?>;

    var componentInfo=<?php echo $this->_var['component_info']; ?>;

    var projectPath='/'+<?php echo $this->_var['project_path_js']; ?>;

    //守住入口的img Url
    for(let i=0;i<sectionInfo.length;i++){
        let r=/\/images\//;
        if(r.exec(sectionInfo[i]['content'])){
            sectionInfo[i]['content']=sectionInfo[i]['content'].replace(/\/images\//,`${projectPath}/images/`)
        }
    }

    for(let i=0;i<componentInfo.length;i++){
        let r=/\/images\//;
        if(r.exec(componentInfo[i]['content'])){
            componentInfo[i]['content']=componentInfo[i]['content'].replace(/\/images\//,`${projectPath}/images/`)
        }
    }

    /*********************************************对原始元素初始化*********************************************/
    {
        let root=$('#root');

        //对页面原有元素初始化
        for(let i=0;i<sectionInfo.length;i++) {
            //在dom 层面更新数据
            root.append(`<div id="${sectionInfo[i]['elementId']}" class="element type${sectionInfo[i]['elementTypeId']}">${sectionInfo[i]['content']}</div>`);
            elementAssembler(sectionInfo[i]);
        }

        let firstTierCategory=componentInfo.filter((item)=>item['componentIdfa']==0);

        for(let i=0;i<firstTierCategory.length;i++){
//            $('#firstTierdiv').find('ul').append(`<li class="firstTierCategory" id='firstTierCategory${firstTierCategory[i][0]}'>${firstTierCategory[i][1]}</li>`)
            $('#firstTierdiv').find('ul').append(`<li class="firstTierCategory" id='firstTierCategory${firstTierCategory[i]['componentId']}' onclick="clicked('#secondTierMenu',this,'secondTierDiv','secondTierContent',1)" >${firstTierCategory[i]['componentName']}</li>`)
        }
        deletrius();
    }

    //右键菜单
    $(function() {
        $.contextMenu({
            selector: '.element',
            events:{
                show:function(){
                    deletrius();
                }
            },
            callback: function(key, options) {

                if(key=='copy'){
                    $('#root').data({
                        elementCopied:$(this).attr('id'),
                        innerCount:1
                    });
                }
                else if(key=='paste'){
                    pasteElementHelper();
                }
                else if(key=='delete'){
                    deleteElementById($(this).attr('id'))
                }
                else{
                    zIndexhelper(key,$(this).attr('id'));
                }
            },
            items: {
                "copy": {name: "复制", icon: "copy"},
                "paste": {name: "粘贴", icon: "paste"},
                sep1: "---------",
                "up": {name: "向上一层", icon: "fa-arrow-up"},
                "down": {name: "向下一层", icon: "fa-arrow-down"},
                "top": {name: "顶层", icon: "fa-chevron-up"},
                "bottom": {name: "底层", icon: "fa-chevron-down"},
                sep2: "---------",
                "delete": {name: "删除", icon: "delete"}
            }
        })
    });

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////2 以下是事件定义//////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /*********************************************定义增加菜单事件***********************************************/
    $("#root").droppable({
        accept: ".component",
        drop: function(event,ui){

            //在sectionInfo层面更新数据
            let newObject = addElementHelper(event,ui);
            sectionInfo.push(newObject);

            //在dom层面更新数据
            $('#root').append(`<div id="${newObject['elementId']}" class="element type${newObject['elementTypeId']}">${newObject['content']}</div>`);
            elementAssembler(newObject);
        }
    });

    //关闭增加框
    $('.close').on('click',function(){
        deletrius();
    });

    /***********************************************定义编辑菜单事件*********************************************/
    //编辑菜单
    $('#elementToolbar1').on('click',' .setting',function(event){
        //点击了设置按钮
        event.stopPropagation();
        elementSettingHelper($('#elementToolbar1').data('storeId'));
    }).on('click','.delete',function(event){
        //点击了删除按钮
        event.stopPropagation();
        deleteElementById($('#elementToolbar1').data('storeId'));
        deletrius();
    }).on('click','.zindex',function(event){
        //点击了层级按钮
        event.stopPropagation();
        $(this).find('.elenentToolbarContext').toggle();
    }).on('click','.copy',function(event){
        //点击了复制按钮
        event.stopPropagation();
        $('#root').data({
            elementCopied:$('#elementToolbar1').data('storeId'),
            innerCount:1
        });
        $('.ui-resizable-handle').hide();
    }).on('click','.help',function(event){
        //点击了帮助按钮
        event.stopPropagation();
        //跳转到相应的帮助页面（以后再修改）
        window.open('https://www.baidu.com');
    });


    //选择层级
    $('.zindex').on('click','.up',function(event){
        event.stopPropagation();
        zIndexhelper('up',$('#elementToolbar1').data('storeId'));
    }).on('click','.down',function(event){
        event.stopPropagation();
        zIndexhelper('down',$('#elementToolbar1').data('storeId'));
    }).on('click','.top',function(event){
        event.stopPropagation();
        zIndexhelper('top',$('#elementToolbar1').data('storeId'));
    }).on('click','.bottom',function(event){
        event.stopPropagation();
        zIndexhelper('bottom',$('#elementToolbar1').data('storeId'));
    });

    //点击section 显示section编辑框
    /*  $('section').on('dblclick',function(e){

     if($(this).attr('id')!='section1'){
     e.stopPropagation();
     deletrius();
     $('#sectionEditor').show().data('storeId',$(this).attr('id'));
     let regex=/\("(.*)"\)/g;
     let result=regex.exec($(this)[0].style.backgroundImage);
     $('#sectionEditorImg').attr('src',result[1]);
     }
     });*/
    /***********************************************定义编辑框事件*****************************************/
    //改变section的背景图片
    $('#imageEditor').on('change','input', function(event){

        //previewImg
        upload_img(event).done(function(result) {
            //在dom层面更新数据
            $('#previewImg').attr("src",`${projectPath}${result}`);
            let id= $('#imageEditor').data('storeId');
            $(`#${id}`).find('img').attr('src',`${projectPath}${result}`);

            //在sectionInfo 层更新数据
            let index=getIndexOfSectionInfo(id);
            sectionInfo[index]['content']=$(`#${id}`)[0].childNodes[0].outerHTML;;
        }).fail(function() {
            // An error occurred
        });
    });
//
//    //点击显示multimedia编辑框
    $('.multimedia').on('dblclick',function(event){
        event.stopPropagation();
        deletrius();
        let pointer=$(this);
        //1: iframe ; 0: audio
        let type=pointer.find('iframe').length;
        let id=$(this).attr('id');
        $('#multimediaEditor').show().data({'mediaType':type,'pointer':pointer,'id':id});
        $('#multimediaEditor').find('input')[0].value="";
    });

    //改变multimedia链接地址
    $('#multimediaEditor').on('click','.save-btn',function(){

        let result=$(this).parents('.input-section').find('input')[0].value;

        //取出之前被保存到multimediaEditor的数据
        let storedData=$('#multimediaEditor').data();
        let [type,pointer,id]= [storedData.mediaType,storedData.pointer,storedData.id];
        let index=getIndexOfSectionInfo(id);

        if(type==1){
            //视频媒体
            //https://www.youtube.com/embed/PZ8h2L63D0Y
            pointer.find('iframe').attr('src',result);
            sectionInfo[index]['itemUrl1']=result
        }else if(type==0){
            //音频媒体
            //http://media.w3.org/2010/07/bunny/04-Death_Becomes_Fur.oga
            pointer.find('audio').attr('src',result);
            sectionInfo[index]['itemUrl1']=result
        }
    });

    $('#root').on('click',function(){
        deletrius();
    });

    /**********************************************定义页面全局事件********************************************/
    //save page
    $('#save-page').on('click',function(){

        for( let name in CKEDITOR.instances)
        {
            CKEDITOR.instances[name].destroy(true);
        }

        //Here I save the changes of Html into sectionInfo after the save button is clicked
        //We may perform this action after each edit event in alternative if you want.
//        for(let i=0;i<sectionInfo.length;i++){
//            sectionInfo[i]['content'] = $(`#${sectionInfo[i]['elementId']}`)[0].childNodes[0].outerHTML;
//        }

        //守住出口的img Url
        for(let i=0;i<sectionInfo.length;i++){
            sectionInfo[i]['content']=sectionInfo[i]['content'].replace(`${projectPath}`,'');
            $($(`#${sectionInfo[i]['elementId']}`)[0].childNodes[0]).data('hasEditor','false');
            }

       post_ajax(sectionInfo);

    });

    //preview-page
    $('#preview-page').on('click',function(){
        window.open('professionalyikaiweb.php?dwt_type=index&dwt_id=2');
    });

    //copy and delete need to be focused at first
    $('body').on('focus','.element',function(){
        $(this).on("keyup",function(event){
            //ctrl+c
            if(event.ctrlKey&&event.keyCode == 67){
                //#root 就是粘贴板
                $('#root').data({
                    elementCopied:$(this).attr('id'),
                    innerCount:1
                });
            }
            //delete
            else if(event.keyCode==46){
                event.keyCode = 0;
                deleteElementById($(this).attr('id'));
                deletrius();
            }
        });
    });//end of focus

    //paste doesn't need any focus event
    $(document).on("keyup",function(event){
         if(event.ctrlKey&&event.keyCode == 86){
            event.keyCode = 0;
            pasteElementHelper();
        }
    });

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////3 以下是公用函数/////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function clicked(Menu,submenu,TierDiv,TierContent,level) {

        //二级菜单中点了谁就显示谁的三级菜单
        let r = /\d+/;
        let componentId=r.exec($(submenu).attr('id'))[0];
        let TierCategory=componentInfo.filter((item)=>item['componentIdfa']==componentId);
        let addBox=$(Menu);

        addBox.show();

        if(level == 1){
            addBox.find('.'+TierDiv+' p').empty().append(`增加${TierCategory[0]['componentTypeName']}`);
        }
        addBox.find('.'+TierContent+'').empty();

        if(level == 1){
            let Tier='second';
            for(let i=0;i<TierCategory.length;i++){
                addBox.find('.'+TierContent+'').append(`<div class="${Tier}TierCategory"  id="component${TierCategory[i]['componentId']}" onclick="clicked('#thirdTierMenu',this,'thirdTierDiv','thirdTierContent',2) ">${TierCategory[i]['componentName']}</div>`)
            }
            let subcomponentId = "component" + TierCategory[0]['componentId'];
            clicked('#thirdTierMenu',$('#' +subcomponentId),'thirdTierDiv','thirdTierContent',2);
        }

        else{
            for(let i=0;i<TierCategory.length;i++){
                addBox.find('.'+TierContent+'').append(`<div id="component${TierCategory[i]['componentId']}" class="component type${TierCategory[i]['componentId']}">${TierCategory[i]['content']}</div>`)
            }
            $('.component').draggable({
                helper:'clone',
                start:function(event,ui){
                    //record all necessary information of the element being dragged so that we can use them when it's dropped
                    $(event.target).removeData().data({
                        boxX:event.offsetX,
                        boxY:event.offsetY,
                        width:$(this).width(),
                        height:$(this).height()
                    });
                }
            });
        }
    }

    /************************************************获取信息的函数******************************************/
    //get index by Id
    function getIndexOfSectionInfo(elementId){
        for(let i=0;i<sectionInfo.length;i++){
            if(sectionInfo[i]['elementId']==elementId){
                return i;
            }
        }
    }

    //取得sectionInfo最后一个元素的id
    function getLastElementId(){

        if(sectionInfo.length==0)
        {
            return 1;
        }
        //保证Id值最大的在最后面

        let r = /\d+/;
//
//        for(let i=0;i<sectionInfo.length;i++){
//            sectionInfo[i]['elementId']= parseInt(r.exec(sectionInfo[i]['elementId'])[0])
//        }
//
//        sectionInfo.sort(function(x, y){
//            x['elementId'] - y['elementId']
//        });
//
//        for(let i=0;i<sectionInfo.length;i++){
//            sectionInfo[i]['elementId']= "element"+sectionInfo[i]['elementId'];
//        }

        let str= sectionInfo[(sectionInfo.length-1)]['elementId'];
        return parseInt(r.exec(str)[0]);
    }

    //data transformer
    function transformer(componentInfo){

        //安全起见 先排序
        componentInfo.sort( function(x,y){
            return x['componentId'] - y['componentId'];
        });

        //1取得一级分类的id 和 name
        let [firstTierCategoryId,firstTierCategoryName]=[[],[]];

        for(let i=0;i<componentInfo.length;i++){
            firstTierCategoryId.push(parseInt(componentInfo[i]['componentTypeId']));
            firstTierCategoryName.push(componentInfo[i]['componentTypeName']);
        }

        let set = new Set(firstTierCategoryId);
        firstTierCategoryId = Array.from(set);

        set = new Set(firstTierCategoryName);
        firstTierCategoryName = Array.from(set);

        let firstTierCategory=[];

        for( let i=0;i<firstTierCategoryId.length;i++){
            firstTierCategory.push([firstTierCategoryId[i],firstTierCategoryName[i]]);
        }

        return firstTierCategory
    }

    /************************************************增删元素的函数******************************************/
    //assemble Element (此函数的输入是一个对象,根据此对象的数据对相应元素进行组装)
    function elementAssembler(elementInfo){
        //get the new created element's DOM (element is a jquery object)
        let element=$('#'+elementInfo['elementId']);

        element.css({
            left: `${elementInfo['positionX']}px`,
            top:  `${elementInfo['positionY']}px`,
            width:`${elementInfo['width']}px`,
            height:`${elementInfo['height']}px`,
            transform: `rotate(${elementInfo['degree']}rad)`,
            'z-index': `${elementInfo['zIndex']}`
        });

        //draggable
        if(elementInfo['draggable'] == 1) {

            element.draggable({
                start: function(event,ui) {
                    deletrius();
                },
                stop: function(event, ui) {
                    let index=getIndexOfSectionInfo($(this).attr('id'));
                    sectionInfo[index]['positionX']= parseInt($(this).css('left').replace('px',''));
                    sectionInfo[index]['positionY']= parseInt($(this).css('top').replace('px',''));
                }
            });
        }

        //resizable
        if(elementInfo['resizable']==1){
            //增加 resizable 功能
            element.addClass('resizable').append(
                    '<div class="ui-resizable-handle ui-resizable-nw"></div>' +'<div class="ui-resizable-handle ui-resizable-ne"></div>' +'<div class="ui-resizable-handle ui-resizable-sw"></div>' +
                    '<div class="ui-resizable-handle ui-resizable-se"></div>' + '<div class="ui-resizable-handle ui-resizable-n"></div>' + '<div class="ui-resizable-handle ui-resizable-s"></div>' +
                    '<div class="ui-resizable-handle ui-resizable-e"></div>' + '<div class="ui-resizable-handle ui-resizable-w"></div>'
            );

            element.resizable({
                handles: {
                    'nw': '.ui-resizable-nw',
                    'ne': '.ui-resizable-ne',
                    'sw': '.ui-resizable-sw',
                    'se': '.ui-resizable-se',
                    'n': '.ui-resizable-n',
                    'e': '.ui-resizable-e',
                    's': '.ui-resizable-s',
                    'w': '.ui-resizable-w'
                },
                stop:function(){
                    let index=getIndexOfSectionInfo($(this).attr('id'));
                    sectionInfo[index]['width']=$(this).width();
                    sectionInfo[index]['height']=$(this).height();
                }
            });

            element.on('click',function(event){
                event.stopPropagation();
                if($(element[0].childNodes[0].outerHTML).attr('contenteditable')!='true'){
                    deletrius();
                    if($(this).hasClass('rotatable')){
                        $(this).find('.ui-rotatable-handle').show();
                    }
                    $(this).find('.ui-resizable-handle').show();
                }
            });
        }

        //rotatable
        if(elementInfo["rotatable"]==1){

            element.addClass('rotatable');

            element.rotatable({
                wheelRotate:false,
                start:function(event,ui){
                    deletrius();
                    $(this).find('.ui-rotatable-handle').show();
                },
                rotate:function(event,ui){
                },
                stop:function(event,ui){
                    let index=getIndexOfSectionInfo($(this).attr('id'));
                    sectionInfo[index]['degree']=ui.angle.current.toFixed(2);
                }
            });

            element.on('click',function(event){
                event.stopPropagation();
                if($(element[0].childNodes[0].outerHTML).attr('contenteditable')!='true'){
                    deletrius();
                    if($(this).hasClass('resizable')){
                        $(this).find('.ui-resizable-handle').show();
                    }
                    $(this).find('.ui-rotatable-handle').show();
                }
            });
        }

        //copyable
        element.attr("tabindex","-1");

        element.on('click',function(event){
            event.stopPropagation();
            if($(element[0].childNodes[0].outerHTML).attr('contenteditable')!='true') {
                //1 强制触发 focus 事件
                $(this).trigger('focus');
                //以后这里要根据elementType来确定使用那个编辑框（现在暂定都使用elementToolbar1）
                //2 弹出对应的编辑菜单
                $('#elementToolbar1').show().offset({
                    left: event.pageX,
                    top: event.pageY - 40
                }).data('storeId', $(this).attr('id'));
            }
        });

    }

    //delete element by id
    function deleteElementById(elementId){

        //在sectionInfo层面删除元素
        let index=getIndexOfSectionInfo(elementId);
        sectionInfo.splice(index,1);

        //在dom层面删除元素
        $('#'+elementId).remove();
    }

    //create new Element in sectionInfo level
    function addElementHelper(event,ui) {
        let componentId = ui.draggable.attr('id').replace('component','');

        //1 找到被拖拽component的所有信息
        let index = 0;
        for (index; index < componentInfo.length; index++) {
            if (componentInfo[index]['componentId'] == componentId) {
                break;
            }
        }

        //2 为更新sectionInfo做准备(对newObject进行初始化)
        let newObject = {};
        newObject['elementId'] = "element" + (1 + getLastElementId());
        if (componentInfo[index]['draggable'] == 1) {
            newObject['draggable'] = 1;
        }
        if (componentInfo[index]['rotatable'] == 1) {
            newObject['rotatable'] = 1;
        }
        if (componentInfo[index]['resizable'] == 1) {
            newObject['resizable'] = 1;
        }

        [newObject['degree'],newObject['zIndex'], newObject['width'], newObject['height'],newObject['positionX'],newObject['positionY'],
            newObject['content'], newObject['elementTypeId'],newObject['elementTypeName']]
        = [0,5,ui.draggable.data('width'), ui.draggable.data('height'),event.pageX-ui.draggable.data('boxX'),event.pageY-ui.draggable.data('boxY'),
            ui.draggable.html(),componentId,componentInfo[index]['componentName']];

        //以下三个属性暂时没被使用 以后可能需要修改
        newObject['sectionId']='section1';
        newObject['imgUrl']='';
        newObject['itemUrl']='';

        return newObject;

    }

    //paste element that was privious copied
    function pasteElementHelper(){
        if($('#root').data('elementCopied')) {
            //1 在sectionInfo 层面上更新数据
            let index = getIndexOfSectionInfo($('#root').data('elementCopied'));
            let innerCount=$('#root').data('innerCount');
            let temp = sectionInfo[index];
            let newObject = jQuery.extend(true, {}, temp);//对象深拷贝

            //更新一下相应属性
            newObject['elementId'] = "element" + (1 + getLastElementId());
            newObject['positionX'] = parseInt(newObject['positionX']) + innerCount * 10;
            newObject['positionY'] = parseInt(newObject['positionY']) + innerCount * 10;

            sectionInfo.push(newObject);

            //2 在dom 层面上更新数据
            $('#root').append(`<div id="${newObject['elementId']}" class="element type${newObject['elementTypeId']}">${newObject['content']}</div>`);
            elementAssembler(newObject);
            deletrius();
            innerCount++;
            $('#root').data('innerCount',innerCount);
        }
    }

    /************************************************编辑元素的函数******************************************/
    function zIndexhelper(type,id) {

        //取得被选中element原本的z-index
        let index=getIndexOfSectionInfo(id);
        let zIndex=parseInt(sectionInfo[index]['zIndex']);

        //根据不同的type 进行不同操作
        if(type=='up'&& zIndex<10){
            sectionInfo[index]['zIndex'] = zIndex+1;
            $(`#${id}`).css("z-index",zIndex+1);
        }

        if(type=='down'&& zIndex>0){
            sectionInfo[index]['zIndex'] = zIndex-1;
            $(`#${id}`).css("z-index",zIndex-1);
        }

        if(type=='top'){
            sectionInfo[index]['zIndex'] = 10;
            $(`#${id}`).css("z-index",10);
        }

        if(type=='bottom'){
            sectionInfo[index]['zIndex'] = 0;
            $(`#${id}`).css("z-index",0);
        }
    }

    function elementSettingHelper(elementId){
        //找出被选中元素对应的类型
        let index=getIndexOfSectionInfo(elementId);
        let temp = componentInfo.filter((item)=>item['componentId']==sectionInfo[index]['elementTypeId']);

       let type = temp[0]['componentTypeId'];
        deletrius();

        //文字类
        if(type==1){
            //selected elements are p h1 h2 h3 ...
            let selectedElement=$($(`#${elementId}`)[0].childNodes[0]);

            selectedElement.attr('id',`textOf${elementId}`);

            //entre edit mode
            if(selectedElement.attr('contenteditable')!='true'){

                selectedElement.attr('contenteditable','true').trigger('focus');
                $(`#${elementId}`).draggable( 'disable' );

               if(selectedElement.data('hasEditor')!='true'){
                    CKEDITOR.inline(selectedElement.attr('id'),{
                        uiColor: '#9AB8F3'
                    });
                   selectedElement.data('hasEditor','true');
               }
            }

            //leave the edit mode
            selectedElement.on('blur',function(){
                selectedElement.attr('contenteditable','false');
                $(`#${elementId}`).draggable( 'enable' );
                //在sectionInfo层面更新数据
                let index=getIndexOfSectionInfo(elementId);
                sectionInfo[index]['content'] = $(`#${sectionInfo[index]['elementId']}`)[0].childNodes[0].outerHTML;
            });
        }
        //图片类
        if(type==2){
            $('#imageEditor').show().data('storeId',elementId);
            $('#previewImg').attr('src',$(`#${sectionInfo[index]['elementId']}`).find('img').attr('src'));
        }
    }

    //消隐无踪
    function deletrius(){
        $('#secondTierMenu').hide();
        $('#thirdTierMenu').hide();
        $('.edit-box').hide();
        $('.elementToolbar').hide();
        $('#elementToolbar1').find('.elenentToolbarContext').hide();
        $('.ui-resizable-handle').hide();
        $('.ui-rotatable-handle').hide();
    }

    /***********************************************与数据库交互的函数****************************************/
    //upload img
    function upload_img(event) {

        var target = $(event.target);

        //创建FormData对象
        var data = new FormData();

        //为FormData对象添加数据
        data.append('upload_img', target[0].files[0]);

        //we will return a promise object
        return $.ajax({
            url: 'upload_img.php',
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,        //不可缺参数
            processData: false        //不可缺参数
        })
    }

    //save page
    function post_ajax(sectionInfo){

        $.post('update_info.php', {
            page_info:sectionInfo
        }, function (result) {
            if (result) {
                swal({
                    title: "",
                    text: "页面保存成功成功",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                });
                //守住出口
                let r=/\/images\//;
                for(let i=0;i<sectionInfo.length;i++){
                    if(r.exec(sectionInfo[i]['content'])){
                        sectionInfo[i]['content']=sectionInfo[i]['content'].replace(/\/images\//,`${projectPath}/images/`)
                    }
                }
            }
        })
    }

</script>
<!------下面是sweetalert的js--------->
<script src="/<?php echo $this->_var['project_path']; ?>/js/sweetalert-dev.js"></script>

</body>
</html>