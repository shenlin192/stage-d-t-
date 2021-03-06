<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title>web edit </title>
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/jquery.fullPage.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/style.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo $this->_var['project_path']; ?>/styles/yikaiweb.css">


    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/bootstrap.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.fullPage.js"></script>
</head>

<body>
<div class="yk-nav">
    <div class="center-wrap clearfix">
        <div class="center-wrap clearfix">
            <h2 class="yk-logo" id="nav1"></h2>
            <p class="yk-link">
                <a href="javascript:" id="nav2"></a>
                <a href="javascript:" id="nav3"></a>
                <a href="javascript:" id="nav4"></a>
                <a href="javascript:" id="nav5"></a>
            </p>
        </div>
    </div>
</div>

<div id="fullPage">

    <section id="header" class="section header fp-auto-height active">
        <div class="center-wrap clearfix">
            <h1 class="logo"><a id="logo1" class="editable-logo">yikai</a></h1>
            <ul class="nav">
                <li>
                    <span class="dropdown-toggle" id="headerText1"></span>
                    <ul class="dropdown-menu">
                        <li><span  id="headerLink1"></span></li>
                        <li><span  id="headerLink2"></span></li>
                    </ul>
                </li>

                <li>
                    <span class="dropdown-toggle" id="headerText2"></span>
                    <ul class="dropdown-menu">
                        <li><span id="headerLink3"></span></li>
                        <li><span  id="headerLink4"></span></li>
                        <li><span  id="headerLink5"></span></li>
                    </ul>
                </li>

                <li >
                    <span class="dropdown-toggle" id="headerText3"></span>
                    <ul class="dropdown-menu">
                        <li><span id="headerLink6"></span></li>
                        <li><span id="headerLink7"></span></li>
                        <li><span id="headerLink8"></span></li>
                    </ul>
                </li>

                <li >
                    <span class="dropdown-toggle" id="headerText4"></span>
                    <ul class="dropdown-menu">
                        <li><span id="headerLink9"></span></li>
                        <li><span id="headerLink10"></span></li>
                    </ul>
                </li>

                <li>
                    <span class="dropdown-toggle" id="headerText5"></span>
                    <ul class="dropdown-menu">
                        <li><span id="headerLink11"></span></li>
                    </ul>
                </li>
            </ul>
        </div>
    </section>

    <section id='section1' class="section page1">

        <div id = 'slide1' class="slide">
        </div>

        <div id = 'slide2' class="slide">
        </div>

        <div id = 'slide3' class="slide">
        </div>

    </section>

    <section id='section2' class="section page2">
		<div class="center-wrap">
			<div class="desc page2-desc">
				<h1 id="head2">14 万分，快得有点狠</h1>
				<p id="p3">CPU 性能翻倍的骁龙 820 处理器，提速 87% 的 UFS 闪存，快 40% 的 Adreno 530 图形引擎，快 100% 的双通道 LPDDR4，Antutu 跑分性能测试高达惊人的 14 万分。</p>
			</div>

            <div class="multimedia" id="video1">
                <iframe width="420" height="300"
                        src="">
                </iframe>
            </div>

			<ul class="side-text side-text-b">
                <li>
                    <span id="span1" class="side-title webfont">

                    </span>
                    <p id='p4' class="side-cont">旗舰处理器</p>
                </li>

                <li class="mid">
                    <span id="span2" class="side-title webfont">

                    </span>
                    <p id='p5' class="side-cont">可选最大内存</p>
                </li>
                <li>
                    <span id="span3" class="side-title webfont">

                    </span>
                    <p id='p6' class="side-cont">可选最大闪存</p>
                </li>
            </ul>
		</div>
	</section>

	<section id='section3' class="section page3">
		<div class="center-wrap">
			<div class="desc page3-desc">
				<h1 id="head3">129克，手感轻得不真实</h1>
				<p id="p7">5.15" 大屏幕，整机仅 129 克。<br>
				还有 3D 纤细侧腰，曲面玻璃的温润手感。握在手中的一刻，仿佛轻轻握住整个世界的倒影。</p>
			</div>
            <div class="multimedia" id="audio1">
                <audio class='audio' controls="controls" src="">
                </audio>
            </div>

        </div>
	</section>

	<section id='section4' class="section page4">
		<div class="center-wrap">
			<div class="desc page4-desc">
				<h1 id="head4">5.5 英寸 1080P 全贴合屏幕</h1>
				<p id="p8">5.5 英寸 1080P Full HD 屏幕，IGZO 显示技术可让每台手机都有高度统一的出色表现，也具备绝佳的低功耗特性。403PPI 带来细腻的视觉体验，对比度高达 1000：1。屏幕亮度则为 450cd/m²，户外强光下依旧清晰可见。GFF 全贴合，避免反光影响，屏显透彻。</p>
				<ul class="parameters-list">
					<li><h2 id="head5">403</h2><span>PPI</span></li>
					<li><h2 id="head6">1000 : 1</h2><span>对比度</span></li>
					<li><h2 id="head7">450cd/m²</h2><span>亮度</span></li>
				</ul>
			</div>
		</div>
	</section>

    <section class="section footer fp-auto-height">
        <div class="footer-link">
            <div class="center-wrap">
                <div class="clearfix">
                    <dl>
                        <dt id="footerTitle1"></dt>
                        <dd><span id="footerText1" ></span></dd>
                        <dd><span id="footerText2" ></span></dd>
                    </dl>

                    <dl>
                        <dt id="footerTitle2"></dt>
                        <dd><span id="footerText3" ></span></dd>
                        <dd><span id="footerText4" ></span></dd>
                        <dd><span id="footerText5" ></span></dd>
                    </dl>

                    <dl>
                        <dt id="footerTitle3"></dt>
                        <dd id="footerText6"></dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="copyright center-wrap">
            <p id='p9'></p>
        </div>

        <!--这段div 只用于占位-->
        <div style="height: 60px">

        </div>

    </section>
</div>

<footer>
    <div>
        <span id="save-page" class="save-btn">保存页面</span>
        <span id="preview-page" class="preview-page">预览页面</span>
    </div>
</footer>

<!--section 背景编辑框-->
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
        <button type="button" class="btn-edit-box save-btn">确定</button>
</artical>

<!--轮播编辑框-->
<artical id="slideEditor" class="edit-box">
    <div class="notice">
        <p class="notice-text">这里可以改变轮播的背景图片对应的链接</p>
        <span class="cancel-edit-box">X</span>
    </div>

    <div id = 'slideGroup'>
        <div id="slideInputSection1" class="input-section">
            <label class="custom-file-upload">
                <img id="slideEditorImg1" src=""/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"/>
            </label>
            <input class="form-control" type="text" placeholder="图片链接">
        </div>
        <div id="slideInputSection2" class="input-section">
            <label class="custom-file-upload">
                <img id="slideEditorImg2" src=""/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"/>
            </label>
            <input class="form-control" type="text" placeholder="图片链接">
        </div>
        <div id="slideInputSection3"  class="input-section">
            <label class="custom-file-upload">
                <img id="slideEditorImg3" src=""/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"/>
            </label>
            <input class="form-control" type="text" placeholder="图片链接">
        </div>
    </div>
    <button type="button" class="btn-edit-box save-btn">确定</button>
</artical>

<!--多媒体编辑框-->
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

<!--商标编辑框-->
<artical id="logoEditor" class="edit-box">
    <div class="notice">
        <p class="notice-text">这里可以改变logo图片,请使用80*80像素的图片</p>
        <span class="cancel-edit-box">X</span>
    </div>

    <div class="item-group">
        <div class="input-section">
            <label class="custom-file-upload">
                <img id="logoEditorImg" src=""/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"/>
            </label>
        </div>
    </div>
    <button type="button" class="btn-edit-box save-btn">确定</button>
</artical>

<script>
    /*********************************************从后台获取数据*************************************************/
	var pageInfo=<?php echo $this->_var['simplified_page_info']; ?>;

    var projectPath='/'+<?php echo $this->_var['project_path_js']; ?>;

    var [sectionArray,paragraphArray,headerArray,
        slideArray,headerTextArray,navTextArray,
        logoArray,spanArray,footerTitleArray,
        footerTextArray,headerLinkArray,audioArray
        ,videoArray]=bumbleBee(pageInfo);

    /*********************************************对页面进行初始化*************************************************/
    $(document).ready(function(){
        for(let i=0;i<sectionArray.length;i++){
            $('#'+sectionArray[i]['elementId']).css('background-image', `url(${projectPath}${sectionArray[i]['imgUrl1']})`)
        }
        for(let i=0;i<paragraphArray.length;i++){
            $('#'+paragraphArray[i]['elementId']).html(paragraphArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<headerArray.length;i++){
            $('#'+headerArray[i]['elementId']).html(headerArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<slideArray.length;i++){
            $('#'+slideArray[i]['elementId']).css('background-image', `url(${projectPath}${slideArray[i]['imgUrl1']})`)
        }
        for(let i=0;i<headerTextArray.length;i++){
            $('#'+headerTextArray[i]['elementId']).html(headerTextArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<navTextArray.length;i++){
            $('#'+navTextArray[i]['elementId']).html(navTextArray[i]['text']).addClass('editableText-without-link');
        }
        for(let i=0;i<logoArray.length;i++){
            $('#'+logoArray[i]['elementId']).css('background-image', `url(${projectPath}${logoArray[i]['imgUrl1']})`);
        }
        for(let i=0;i<spanArray.length;i++){
            $('#'+spanArray[i]['elementId']).html(spanArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<footerTitleArray.length;i++){
            $('#'+footerTitleArray[i]['elementId']).html(footerTitleArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<footerTextArray.length;i++){
            $('#'+footerTextArray[i]['elementId']).html(footerTextArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<headerLinkArray.length;i++){
            $('#'+headerLinkArray[i]['elementId']).html(headerLinkArray[i]['text']).addClass('editableText');
        }
        for(let i=0;i<audioArray.length;i++){
            $('#'+audioArray[i]['elementId']).find('audio').attr('src',`${projectPath}${audioArray[i]['itemUrl1']}`);
        }
        for(let i=0;i<videoArray.length;i++){
            $('#'+videoArray[i]['elementId']).find('iframe').attr('src',videoArray[i]['itemUrl1']);
        }

        //首先隐藏所有编辑框
        $('.edit-box').hide();

        //点击关闭，隐藏编辑窗
        $('.cancel-edit-box').on('click',function(){
            $('.edit-box').hide();
        });
        $('.btn-edit-box').on('click',function(){
            $('.edit-box').hide();
        });

        //fullPage.js functionality
        $(function(){
            var $ykNav = $('.yk-nav');
            $('#fullPage').fullpage({
                verticalCentered: false,
                navigation: true,
                keyboardScrolling:false,
                onLeave: function(index, nextIndex, direction){
                    //隐藏编辑框
                    deletrius();
                    //离开的那一页对应的导航样式设置
                    $('.yk-link').children().eq(index-2).css({
                        'color':'#515151',
                        'border-bottom':'5px solid transparent'
                    });

                    if(index == 2 && direction == 'up'){
                        $ykNav.animate({  //当前页面是2，运动方向向上，执行以下代码，调整nav栏位置
                            top: -80
                        }, 680);
                        $(".right").css('display','none');
                    } else if(index == 1 && direction == 'down') {
                        $ykNav.animate({
                            top: 0
                        }, 400);
                        $(".right").css('display','block');
                    }
                    else {
                        $ykNav.animate({
                            top: 0
                        }, 400);
                    }
                },
                afterLoad: function(anchorLink, index){
                    //进入的那一页对应的导航的设置
                    if(index-2>=0){
                        $('.yk-link').children().eq(index-2).css({
                            'color':'#31a5e7',
                            'border-bottom':'5px solid #31a5e7'
                        });
                    }
                }
            });

            //slide 向右滑动6秒一次
            setInterval(function(){
                $.fn.fullpage.moveSlideRight();
            },6000);
            //点击事件
            $('.yk-link').children().click(function(event){
                event.stopPropagation();
                $.fn.fullpage.moveTo($(this).index()+2);
            });
        });//end of full page initialization

        //bootstrap下拉菜单改hover
        $(function(){
            $('.dropdown-toggle').dropdownHover();
        });
    });//end of document initialization

    //bootstrap下拉菜单改hover
    (function($, window, undefined) {
        // outside the scope of the jQuery plugin to
        // keep track of all dropdowns
        var $allDropdowns = $();

        // if instantlyCloseOthers is true, then it will instantly
        // shut other nav items when a new one is hovered over
        $.fn.dropdownHover = function(options) {

            // the element we really care about
            // is the dropdown-toggle's parent
            $allDropdowns = $allDropdowns.add(this.parent());

            return this.each(function() {
                var $this = $(this).parent(),
                        defaults = {
                            delay: 500,
                            instantlyCloseOthers: true
                        },
                        data = {
                            delay: $(this).data('delay'),
                            instantlyCloseOthers: $(this).data('close-others')
                        },
                        options = $.extend(true, {}, defaults, options, data),
                        timeout;

                $this.hover(function() {
                    if(options.instantlyCloseOthers === true)
                        $allDropdowns.removeClass('open');

                    window.clearTimeout(timeout);
                    $(this).addClass('open');
                }, function() {
                    timeout = window.setTimeout(function() {
                        $this.removeClass('open');
                    }, options.delay);
                });
            });
        };

       $('[data-hover="dropdown"]').dropdownHover();
    })(jQuery, this);

    /*********************************************定义各类编辑事件*************************************************/
    //点击slide 展示轮播编辑框
    var flag=0;
    $('.slide').on('click',function(event){
        event.stopPropagation();
        deletrius();
        $('#slideEditor').show();
        let slideSet=$(this).parents('section').find('.slide');

        //初始化编辑框图片(只运行一次)
        if(flag==0){
            $.each(slideSet, function(i,val){
                let regex=/\("(.*)"\)/g;
                let result=regex.exec(val.style.backgroundImage);
                $('#'+'slideEditorImg'+(i+1)).attr('src',result[1]);

                //初始化编辑框链接
                let input = $('#'+'slideInputSection'+(i+1))
                $(input.find('input[type="text"]')).val(pageInfo[getIndexOfPageInfo('slide' + (i+1))]['itemUrl1']);
            });
            flag=1;
        }
    });
    //改变slide的背景图片
    $('#slideEditor').on('change','input[type="file"]', function(event) {
        event.stopPropagation();
        //If no argument is passed to the .index() method,
        // the return value is an integer indicating the position
        // of the first element within the jQuery object relative to its sibling elements.
        //let num = $(this).parents('.input-section').index()+1;
        let num = $(this).parents('.input-section').attr('id').match(/\d+/)[0];
        let pointer=$(this);
        upload_img(event).done(function (result) {
            //更新编辑框图片
            pointer.parents('.input-section').find('img').attr('src',`${projectPath}${result}`);
            //更新轮播背景
            $('#slide' + num).css('background-image', `url(${projectPath}${result})`);
            //更新pageInfo
            pageInfo[getIndexOfPageInfo('slide' + num)]['imgUrl1'] = result;

        }).fail(function () {
            console.log('error');
            });
    });

    $('#slideEditor').on('blur','input[type="text"]', function(event) {
        event.stopPropagation();
        let num = $(this).parents('.input-section').attr('id').match(/\d+/)[0];
        let index=getIndexOfPageInfo('slide'+num);
        pageInfo[index]['itemUrl1']=$(this).val();
    });

    //点击section 显示section编辑框
	$('section:not(.header,.footer)').on('click',function(e){

        if($(this).attr('id')!='section1'){
		e.stopPropagation();
        deletrius();
        $('#sectionEditor').show().data('storeId',$(this).attr('id'));
        let regex=/\("(.*)"\)/g;
        let result=regex.exec($(this)[0].style.backgroundImage);
        $('#sectionEditorImg').attr('src',result[1]);
        }
	});
    //改变section的背景图片
    $('#sectionEditor').on('change','input', function(event){
        upload_img(event).done(function(result) {

            $('#sectionEditorImg').attr("src",`${projectPath}${result}`);
            let id= $('#sectionEditor').data('storeId');
            $('#'+id).css('background-image', `url(${projectPath}${result})`);
            let index=getIndexOfPageInfo(id);
            pageInfo[index]['imgUrl1']=result;
        }).fail(function() {
            // An error occurred
        });
    });


    //点击显示multimedia编辑框
    $('.multimedia').on('click',function(event){
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
        let index=getIndexOfPageInfo(id);

        if(type==1){
            //视频媒体
            //https://www.youtube.com/embed/PZ8h2L63D0Y
            pointer.find('iframe').attr('src',result);
            pageInfo[index]['itemUrl1']=result
        }else if(type==0){
            //音频媒体
            //http://media.w3.org/2010/07/bunny/04-Death_Becomes_Fur.oga
            pointer.find('audio').attr('src',result);
            pageInfo[index]['itemUrl1']=result
        }
        $('.edit-box').hide();
    });

    //点击显示logo编辑框
    $('.editable-logo').on('click',function(event){
        event.stopPropagation();
        deletrius();
        $('#logoEditor').show().data('storeId',$(this).attr('id'));
        let regex=/\("(.*)"\)/g;
        let result=regex.exec($(this)[0].style.backgroundImage);
        $('#logoEditorImg').attr('src',result[1]);
    });
    //改变logo的背景图片
    $('#logoEditor').on('change',function(event){
        event.stopPropagation();
        upload_img(event).done(function(result) {
            $('#logoEditorImg').attr("src",`${projectPath}${result}`);
            let id= $('#logoEditor').data('storeId');
            $('#'+id).css('background-image', `url(${projectPath}${result})`);
            let index=getIndexOfPageInfo(id);
            pageInfo[index]['imgUrl1']=result;
        }).fail(function() {
            // An error occurred
        });
    });


    //save page
    $('#save-page').on('click',function(){
        $('.editableText').trigger('blur');
        $('.editableText-without-link').trigger('blur');
        post_ajax(pageInfo)
    });

    $('#preview-page').on('click',function(){
       window.open('yikaiweb.php?dwt_type=index&dwt_id=2') 
    });

    /*********************************************以下是公用函数*************************************************/
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
    function post_ajax(pageInfo){

        $.post('updata_info.php', {
            page_info:pageInfo
        }, function (result) {
            if (result) {
                console.log('success');
            }
        })
    }

    //get index
    function getIndexOfPageInfo(id){
        for(let i=0;i<pageInfo.length;i++){
            if(pageInfo[i]['elementId']==id){
                return i;
            }
        }
    }

    //transformer
    function bumbleBee(pageInfo){
        return [
            pageInfo.filter((item)=>item['typeId']==1),
            pageInfo.filter((item)=>item['typeId']==2),
            pageInfo.filter((item)=>item['typeId']==3),
            pageInfo.filter((item)=>item['typeId']==4),
            pageInfo.filter((item)=>item['typeId']==5),
            pageInfo.filter((item)=>item['typeId']==6),
            pageInfo.filter((item)=>item['typeId']==7),
            pageInfo.filter((item)=>item['typeId']==8),
            pageInfo.filter((item)=>item['typeId']==9),
            pageInfo.filter((item)=>item['typeId']==10),
            pageInfo.filter((item)=>item['typeId']==11),
            pageInfo.filter((item)=>item['typeId']==12),
            pageInfo.filter((item)=>item['typeId']==13),
        ]
    }

    //消隐无踪
    function deletrius(){
        $('.edit-box').hide();
        $('.editableText').trigger( "blur" );
        $('.editableText-without-link').trigger( "blur" );
    }

</script>
<!------下面是tinymce的js----->
<script src='/<?php echo $this->_var['project_path']; ?>/js/tinymce_4.4.1/tinymce/js/tinymce/tinymce.min.js'></script>
<script src='/<?php echo $this->_var['project_path']; ?>/js/zh_CN.js'></script>
<script>
    //有什么不懂请浏览以下的documentation  https://www.tinymce.com/
    tinymce.init({
        selector: '.editableText',
        inline: true,
        plugins: "link textcolor colorpicker",
        toolbar: 'cut copy paste | undo redo | bold italic underline strikethrough | link  | fontsizeselect | forecolor backcolor',
        menubar: false,
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',

        setup: function (editor) {
            $('.editableText').on('click', function (event) {
                event.stopPropagation();
                $('.edit-box').hide();
            });

            editor.on('focus',function(event){
                let id=$(this)[0].targetElm.id;
                $('#'+id).parents('.dropdown-menu').parents('li').addClass('forceOpen');
            });

            editor.on('blur', function(event) {
                event.stopPropagation();
                let id=event.target.id;
                let index= getIndexOfPageInfo(id);
               //保存到pageInfo数组中
                $('#'+id).parents('.dropdown-menu').parents('li').removeClass('forceOpen');
                pageInfo[index]['text']=$('#'+id).html();
            });
        }
    });

    //limit the ability to change the default link
    tinymce.init({
        selector: '.editableText-without-link',
        inline: true,
        plugins: "textcolor colorpicker image",
        toolbar: ' undo redo | bold italic | forecolor backcolor | fontsizeselect ',
        menubar: false,
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        setup: function (editor) {
            $('.editableText-without-link').on('click', function (event) {
                event.stopPropagation();
                $('.edit-box').hide();
            });
            editor.on('blur', function(event) {
                event.stopPropagation();
                let id=event.target.id;
                let index= getIndexOfPageInfo(id);
                //保存到pageInfo数组中
                pageInfo[index]['text']=$('#'+id).html();
            });
        }
    });

    /*
    tinymce.init({
        selector: 'div.editableText',
        inline: true,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
    });
    */
</script>

</body>
</html>