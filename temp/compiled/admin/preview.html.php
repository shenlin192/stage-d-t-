<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
	<title>Preview Page</title>

	<link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/bootstrap.min.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/preview.css">
    
	<script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.min.js"></script>
	<script src="/<?php echo $this->_var['project_path']; ?>/js/bootstrap.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/vue.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.fakecrop.js"></script>
    <style>
        ::-webkit-scrollbar{
            width:0;
        }
    </style>

    <style type="text/css">
        /* 左侧导航栏style */

        body {
            position: relative;
            /* required */
        }

        ul.nav-tabs {
            display:none;
            width: 140px;
            margin-top: 20px;
            border-radius: 4px;
            background: #fff;
            z-index: 999;
            border: 1px solid #ddd;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067);
        }

        ul.nav-tabs li {
            margin: 0;
            border-top: 1px solid #ddd;
        }

        ul.nav-tabs li:first-child {
            border-top: none;
        }

        ul.nav-tabs li a {
            margin: 0;
            padding: 8px 16px;
            border-radius: 0;
        }

        ul.nav-tabs li.active a,
        ul.nav-tabs li.active a:hover {
            color: #fff;
            background: #0088cc;
            border: 1px solid #0088cc;
        }

        ul.nav-tabs li:first-child a {
            border-radius: 4px 4px 0 0;
        }

        ul.nav-tabs li:last-child a {
            border-radius: 0 0 4px 4px;
        }

        ul.nav-tabs.affix {
            top: 0px;
            /* set the top position of pinned element */
        }

        @media screen and (min-width: 768px) and (max-width: 992px) {
            ul.nav-tabs {
                display:block;
                width: 140px;
                /* set nav width on medium devices */
            }
        }

        @media screen and (min-width: 992px) and (max-width: 1199px) {
            ul.nav-tabs {
                display:block;
                width: 180px;
                /* set nav width on medium devices */
            }
        }

        @media screen and (min-width: 1200px) {
            ul.nav-tabs {
                display:block;
                width: 220px;
                /* set nav width on large devices */
            }
        }

        /* 为了让下侧导航栏右下角icon的span可以看见 */
        .navbar-toggle .icon-bar{
            background-color:#337ab7;
        }

    </style>

    <!--搜索框模版-->
    <template id="t11">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib11">
                    <div class="input-group">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                        </span>
                        <input type="text" class="form-control" placeholder="Search for...">
                    </div><!-- /input-group -->
                </div>
            </div>
        </div>
    </template>

    <!--进入店铺模板-->
    <template id="t12" >
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                    <div class="lib12">
                        <img  v-bind:src="imgs[0].imgUrl" alt="标题栏图片" >
                        <span>{{libTitle}}</span>
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </div>
            </div>
        </div>
    </template>

    <!--辅助线模板-->
    <template id="t13">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib13">
                    <div class="auxiliary-line-container">
                        <div class="auxiliary-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!--辅助空白模板-->
    <template id="t14">
        <div class="row" id="count{{count}}">
            <div style="height: 5vh"></div>
        </div>
    </template>

    <!--地图模板-->
    <template id="t15">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib15">
                    <div id="allmap"></div>
                </div>
            </div>
        </div>
    </template>

    <!--文本导航模板-->
    <template id="t21">
        <div class="row" id="count{{count}}" >
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib21">
                    <a v-bind:href="itemUrl1" target="_blank">
                        <span> {{libTitle}}</span>
                        <span class="glyphicon glyphicon-chevron-right" style="float: right"></span>
                    </a>
                </div>
            </div>
        </div>
    </template>

    <!--公众号标题-->
    <template id="t23">
        <div class="row" id="count{{count}}" >
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib23">
                    <div class="title">{{libText1}}</div>
                    <div class="footer">
                        <span class="date">{{libText2}}</span>
                        <span class="author">{{libText3}}</span>
                        <span class="linkTitle">{{libText4}}</span>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!--图片导航模板-->
    <template id="t22" >
        <div class="row" id="count{{count}}" >
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib22 fakecrop-fill" id="fakecrop-fill" >
                    <div class="show-box" v-for="item in imgs" track-by="id">
                        <a v-bind:href="item.itemUrl">
                        <img v-bind:src="item.imgUrl"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!--文字模块模板-->
    <template id="t24">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib24">
                    <div>
                        {{{libDetail}}}
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!--单列大图模板-->
    <template id="t25">
            <div class="row" id="count{{count}}">
                <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                    <div class="lib25">
                        <div class="show-box"  transition="staggered"
                             stagger="100" v-for="item in soloColImgs" track-by="$index" v-bind:class="{'showPadding':showPadding}">
                            <a v-bind:href="item.itemUrl">
                                <img v-bind:src="item.imgUrl"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </template>

    <!--双列大图模板-->
    <template id="t26">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib26">
                    <div  class="img-group"  v-for="item in doubleColImgs"  v-bind:class="{'showPadding':showPadding}">
                        <div class="item">
                            <a v-bind:href="item[0].itemUrl">
                            <img v-bind:src="item[0].imgUrl"/>
                            </a>
                        </div>
                        <div class="item">
                            <a v-bind:href="item[0].itemUrl">
                            <img v-bind:src="item[1].imgUrl"/>
                           </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!--单列轮播模板-->
    <template id="t29">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div class="lib29">
                     <div id="carousel-{{count}}" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-{{count}}" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-{{count}}" data-slide-to="1"></li>
                        <li data-target="#carousel-{{count}}" data-slide-to="2"></li>
                        <li data-target="#carousel-{{count}}" data-slide-to="3"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img v-bind:src="soloColImgs[0].imgUrl" alt="...">
                            <div class="carousel-caption">
                                ...
                            </div>
                        </div>
                        <div class="item">
                            <img v-bind:src="soloColImgs[1].imgUrl"  alt="...">
                            <div class="carousel-caption">
                                ...
                            </div>
                        </div>
                        <div class="item">
                            <img v-bind:src="soloColImgs[2].imgUrl"  alt="...">
                            <div class="carousel-caption">
                                ...
                            </div>
                        </div>
                        <div class="item">
                            <img v-bind:src="soloColImgs[3].imgUrl"  alt="...">
                            <div class="carousel-caption">
                                ...
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-{{count}}" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-{{count}}" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                </div>
            </div>
        </div>
    </template>

    <!--商品模块大图-->
    <template id="t31">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div  class="lib31"  v-bind:class="{'no-border': style1}">
                    <div class="item-image">
                        <img v-bind:src="imgs[0].imgUrl">
                        <p v-if="!style2&&showSale"  class="item-sold">销量{{libText1}}</p>
                    </div>
                    <p  v-if="!style2&&showName" class="item-title">{{libText2}}</p>
                    <p  class="item-price"   v-if="showPrice" >¥ {{price1}}</p>
                </div>
            </div>

            </div>
    </template>

    <!--商品模块两列-->
    <template id="t32">
        <div class="row" id="count{{count}}">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
                <div  class="lib32"  v-bind:class="{'no-border': style1_32}">

                        <div class="double-col-group">
                            <div class="item-image">
                                <img v-bind:src="imgs[0].imgUrl">
                                <p v-if="!style2_32&&showSale32"  class="item-sold">销量{{libText1}}</p>
                            </div>
                            <p  v-if="!style2_32&&showName32" class="item-title">{{libText2}}</p>
                            <p  class="item-price" v-if="showPrice32" >¥ {{price1}}</p>
                        </div>

                        <div class="double-col-group">
                            <div class="item-image">
                                <img v-bind:src="imgs[1].imgUrl">
                                <p v-if="!style2_32&&showSale32"  class="item-sold">销量{{libText3}}</p>
                            </div>
                            <p  v-if="!style2_32&&showName32" class="item-title">{{libText4}}</p>
                            <p  class="item-price" v-if="showPrice32" >¥ {{price2}}</p>
                        </div>

                    </div>
                </div>
            </div>
    </template>

</head>
<!--style="background-image: url(/<?php echo $this->_var['project_path']; ?>/images/seawall2.jpg); background-size:cover"-->
<body data-spy="scroll" data-target="#myScrollspy"  >

<div id="background-image" >
    <img src="/<?php echo $this->_var['project_path']; ?>/images/nuage.svg" alt="background" style="position: fixed"/>
</div>


<!--左侧导航栏-->
<div class="col-sm-3 hidden-xm" id="myScrollspy" >
    <nav >
        <ul class="nav nav-tabs nav-stacked" data-offset-top="120" data-spy="affix" style="position:fixed;top:0vh;max-height: 80vh; overflow:auto;">
            <li><a href="#header">header</a></li>
        </ul>
    </nav>
</div>

<!--main contain-->
<article id="main" >
    <!--页头行-->
    <div class="row" id="header">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-3">
            <div class="lib0">
                <div id="head-bg"></div>
                <h3 class="avatar"><img src="http://wd.geilicdn.com/vshop-shop-logo-default.jpg?w=250&amp;h=250&amp;cp=1">
                <span>沈麟</span>
                </h3>
            </div>
        </div>
    </div>

    <!--各个子组件-->
</article>

<!--下方导航栏-->
<footer class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 hidden-lg hidden-md hidden-sm">
    <nav class="navbar navbar-successful" id="bottomNavbar" style="max-height: 80vh;overflow: auto;">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Brand</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">

                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
</footer>

<script type="text/javascript">

	 var pageInfo = <?php echo $this->_var['page_info']; ?>;

     var projectPath='/'+<?php echo $this->_var['project_path_js']; ?>;

     //In order to avoid global variable, closure is used here
     (function(){
         pageInfo.sort(function(x,y){
             return (x['row']-y['row']);
         });
         for(var i=0;i<pageInfo.length;i++) {
             if (pageInfo[i]['lib_id'] == 0) {
                 pageInfo=pageInfo.slice(0,i);
                 break;
             }
         }
     })();

     //注册各个子组件
     //ECMA6的写法
	for(let i=0;i<pageInfo.length;i++) {

		pageInfo[i]['row'] = parseInt(pageInfo[i]['row']);

		$("#main").append("<row_" + pageInfo[i]['row'] + "></row_" + pageInfo[i]['row'] + ">");

		Vue.component('row_' + pageInfo[i]['row'], {
            template: '#t' + pageInfo[i]['lib_id'],

            data:function(){
                return {

                    count: i,

                    soloColImgsNum : parseInt(pageInfo[i]['lib_detail']),
                    doubleColImgsNum : parseInt(pageInfo[i]['lib_detail']),

                    typeOfLib31a : pageInfo[i]['type_of_lib31a'],
                    typeOfLib31b : pageInfo[i]['type_of_lib31b'],
                    typeOfLib32a : pageInfo[i]['type_of_lib32a'],
                    typeOfLib32b : pageInfo[i]['type_of_lib32b'],

                    //lib data
                    type : parseInt(pageInfo[i]['type']),
                    visibility : pageInfo[i]['visibility'],
                    libId : pageInfo[i]['lib_id'],
                    libTitle : pageInfo[i]['lib_title'],
                    row : pageInfo[i]['row'],
                    libNum : pageInfo[i]['lib_num'],
                    libNumMax : pageInfo[i]['lib_num_max'],
                    libDetail : pageInfo[i]['lib_detail'],
                    libText1 : pageInfo[i]['lib_text1'],
                    libText2 : pageInfo[i]['lib_text2'],
                    libText3 : pageInfo[i]['lib_text3'],
                    libText4 : pageInfo[i]['lib_text4'],
                    price1 : pageInfo[i]['price1'],
                    price2 : pageInfo[i]['price2'],

                    //external links
                    imgUrl1: `${projectPath}${pageInfo[i]['img_url1']}`,
                    itemUrl1: `${projectPath}${pageInfo[i]['item_url1']}`,
                    imgUrl2:  `${projectPath}${pageInfo[i]['img_url2']}`,
                    itemUrl2: `${projectPath}${pageInfo[i]['item_url2']}`,
                    imgUrl3:  `${projectPath}${pageInfo[i]['img_url3']}`,
                    itemUrl3: `${projectPath}${pageInfo[i]['item_url3']}`,
                    imgUrl4: `${projectPath}${pageInfo[i]['img_url4']}`,
                    itemUrl4: `${projectPath}${pageInfo[i]['item_url4']}`,

                    //external links, 写成数组形式是为了使用v-for从而实现代码模块化和参数化
                    imgs:[
                        { id : 1,  imgUrl: `${projectPath}${pageInfo[i]['img_url1']}`, itemUrl:`${projectPath}${pageInfo[i]['item_url1']}` },
                        { id : 2,  imgUrl: `${projectPath}${pageInfo[i]['img_url2']}`, itemUrl:`${projectPath}${pageInfo[i]['item_url1']}` },
                        { id : 3,  imgUrl: `${projectPath}${pageInfo[i]['img_url3']}`, itemUrl:`${projectPath}${pageInfo[i]['item_url1']}` },
                        { id : 4,  imgUrl: `${projectPath}${pageInfo[i]['img_url4']}`, itemUrl:`${projectPath}${pageInfo[i]['item_url1']}` }
                    ]
                }
            },

            computed: {
                // 只要visibility一变，isA就会实时改变
                invisible: function () {
                    return((this).visibility==0);
                },

                lastAddBox:function(){
                    return ((this).libId==0);
                },

                soloColImgs:function(){
                    var temp=[];
                    //对数组进行深拷贝
                    for(var i=0;i<(this).soloColImgsNum;i++){
                        temp.push((this).imgs[i]);
                    }
                    return temp;
                },

                doubleColImgs:function(){
                    var temp=[];

                    //对数组进行深拷贝
                    for(var i=0;i<(this).doubleColImgsNum;i=i+2){
                        var array=[];
                        array.push((this).imgs[i]);
                        array.push((this).imgs[i+1]);
                        temp.push(array);
                        array=[];
                    }
                    return temp;
                },

                //显示间隔
                showPadding:function(){
                    return ((this).type==1);
                },

                //无框样式
                style1:function(){
                    return ((this).typeOfLib31a==1);
                },

                //极简样式
                style2:function(){
                    return ((this).typeOfLib31a==2);
                },

                //显示名称
                showName:function(){
                    return((this).typeOfLib31b==4||(this).typeOfLib31b==5||(this).typeOfLib31b==6||(this).typeOfLib31b==7);
                },
                //显示销量
                showSale:function(){
                    return((this).typeOfLib31b==2||(this).typeOfLib31b==3||(this).typeOfLib31b==6||(this).typeOfLib31b==7);
                },
                //显示价格
                showPrice:function(){

                    return((this).typeOfLib31b==1||(this).typeOfLib31b==3||(this).typeOfLib31b==5||(this).typeOfLib31b==7);
                },

                //无框样式
                style1_32:function(){
                    return ((this).typeOfLib32a==1);
                },

                //极简样式
                style2_32:function(){
                    return ((this).typeOfLib32a==2);
                },

                //显示商品名称
                showName32:function(){
                    return((this).typeOfLib32b==4||(this).typeOfLib32b==5||(this).typeOfLib32b==6||(this).typeOfLib32b==7);
                },
                //显示商品销量
                showSale32:function(){
                    return((this).typeOfLib32b==2||(this).typeOfLib32b==3||(this).typeOfLib32b==6||(this).typeOfLib32b==7);
                },
                //显示商品价格
                showPrice32:function(){
                    return((this).typeOfLib32b==1||(this).typeOfLib32b==3||(this).typeOfLib32b==5||(this).typeOfLib32b==7);
                }

            }
		})
	}
     //以下是ECMA5的写作方法
     /*
      for(var i=0;i<pageInfo.length;i++) {
         //....
         (function(i){
            Vue.component(...)
          })(i);
      }
     */

	var root=new Vue({
		el:"#main"
	});

    //让header宽度动态变化（if you have any better idea, optimize it）
     (function(){
         var headerWidth = $('#head-bg').width();
         $('.head_nav').css('width', headerWidth);
         $('#bottomNavbar').css('width', headerWidth);

         $(window).resize(function(){
                 var headerWidth = $('#head-bg').width();
                 $('.head_nav').css('width', headerWidth);
                 $('#bottomNavbar').css('width', headerWidth);
             }
         );
     })();

     //利用fakecrop的API
     (function(){
         $('.fakecrop-fill img').fakecrop();
     })();

     //根据数据库动态增加左侧导航栏和下侧导航栏的内容
     //ECMAScript6的写法
     {
         for(let i=0;i<pageInfo.length;i++){
         $('#myScrollspy').find('ul').append("<li><a href='#count"+i+"'>"+pageInfo[i]['lib_title']+"</a></li>");
         $('#bottomNavbar').find('ul').append("<li><a href='#count"+i+"'>"+pageInfo[i]['lib_title']+"</a></li>");
        }

         $('[data-spy="scroll"]').each(function () {
             let $spy = $(this).scrollspy('refresh');
         })
     }

     //使下侧导航栏点击后能自动收回
     $(document).on('click','.navbar-collapse.in',function(e) {
         if( $(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle' ) {
             $(this).collapse('hide');
         }
     });
</script>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=YWdGplhYjUGQ3GtpKNeuTM2S"></script>
<script type="text/javascript">

    (function(){
        for(var i=0;i<pageInfo.length;i++) {
            if (pageInfo[i]['lib_id'] == 15) {
                var position=pageInfo[i]['lib_detail'].split(/[,]+/);
                // 创建Map实例
                var map = new BMap.Map("allmap");
                map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
                map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
                // 初始化地图,设置中心点坐标和地图级别
                map.centerAndZoom(new BMap.Point(Number(position[0]), Number(position[1])), 13);
                // 创建标注，为要查询的地方对应的经纬度
                var marker = new BMap.Marker(new BMap.Point( Number(position[0]), Number(position[1])));
                marker.setAnimation(BMAP_ANIMATION_BOUNCE);
                //把标注加到地图上
                map.addOverlay(marker);
                break;
            }
        }
    })();

/*
    var map = new BMap.Map("allmap");
    map.centerAndZoom("北京", 13);
    map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

    var marker = new BMap.Marker(new BMap.Point( 116.413554, 39.911013));  // 创建标注，为要查询的地方对应的经纬度
    map.addOverlay(marker);

    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
    // map.addControl(new BMap.OverviewMapControl()); //添加默认缩略地图控件
    // map.addControl(new BMap.OverviewMapControl({isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT}));   //右下角，打开

    var localSearch = new BMap.LocalSearch(map);

    localSearch.enableAutoViewport(); //允许自动调节窗体大小
*/

</script>

<script type="text/javascript" src="http://velocityjs.org/build/jquery.velocity.min.js"></script>
<script type="text/javascript" src="http://velocityjs.org/build/velocity.ui.js"></script>

</body>
</html>