<!DOCTYPE html>
<html lang="en">
<head>
    <!--以下三行清除缓存-->
    <!--我遇到的所有问题最后发现100%都是因为程序逻辑不严谨，所以出错时不建议考虑浏览器缓存问题-->
    <!--META HTTP-EQUIV="pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
    <META HTTP-EQUIV="expires" CONTENT="0"-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <title>Edit Page</title>
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/bootstrap.min.css">

    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/sweetalert.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/google.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/unslider.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/unslider-dots.css">

    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/global/index3.css">

    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib12.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib13.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib15.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib21.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib22.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib23.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib24.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib25.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib26.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib29.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib31.css">
    <link rel="stylesheet" href="/<?php echo $this->_var['project_path']; ?>/styles/libraries/lib32.css">

    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">

    <style type="text/css">
        #allmap {width: 100%; height:300px; overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>

    <!--jquery 和 vuejs 的script必须放在body以前-->
    <script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/vue.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/unslider-min.js"></script>
    <script src="/<?php echo $this->_var['project_path']; ?>/js/velocity.min.js"></script>

</head>

<body>
<!----------------------------------------------子组件的子组件的模板--------------------------------------------------->
<!--辅助按钮模板-->
<template id="auxiliary">
    <div  class="auxiliary-area">
        <p class="move-up-btn"  v-on:click.stop="" v-on:mousedown.stop="moveUpLib">上移</p>
        <p class="move-down-btn"  v-on:mousedown.stop="moveDownLib">下移</p>
        <p class=" add-lib-btn"  v-on:mousedown.stop >增加</p>
        <p class=" delete-lib-btn" v-on:mousedown.stop="deleteLib">删除</p>
    </div>
</template>

<!--进入商店编辑框-->
<template id="edit-type-12">
    <div class="edit-box type-12"  >

        <div class="notice">
            <p class="notice-text">您可以在这里编辑店铺的名称和图标</p>
            <span class="cancel-edit-box">X</span>
        </div>
        <div class="input-section">
            <label class="custom-file-upload">
                <img v-bind:src="imgUrl"/>
                <div class="prompt-text">
                    <p>+</p>
                    <p>请上传图片</p>
                </div>
                <input type="file"  v-on:change="upload_img($event)"/>
            </label>
            <input class="form-control" type="text" placeholder="图片链接" v-model="itemUrl" v-on:keyUp="save">
            <input class="form-control" maxlength="10" type="text" placeholder="商店名称(10个字以内)" v-model="libTitle" v-on:keyUp="save">
        </div>
    </div>
</template>

<!--地图编辑框-->
<template id="edit-type-15">
    <div class="edit-box type-15 " >

        <div class="notice">
            <p class="notice-text">输入地址后请点击确定按钮</p>
            <span class="cancel-edit-box">X</span>
        </div>

        <div id="container"  style=" height: 300px; border: 1px solid gray;overflow:hidden;margin-bottom:20px;"></div>

        <div class="input-group">
            <span class="input-group-btn">
                 <button class="btn btn-default" type="button" v-on:click="searchByStationName()">Go!</button>
            </span>
            <input id="text_" type="text" class="form-control" placeholder="请输入地址..."  v-model="location" >
        </div>

    </div>
</template>

<!--文本导航编辑框-->
<template id="edit-type-21">
    <div class="edit-box type-21">
        <div class="notice">
            <p class="notice-text">文本导航可以作为目录使用，比如文章合集，分类目录 等，便于买家找到其他的店铺内容。</p>
            <span class="cancel-edit-box">X</span>
        </div>

        <div class="input-group">
            <div>
                <span>导航名称</span><input class="form-control"  maxlength="20" type="text" placeholder="请输入导航名称（20字以内）" v-model:value='libTitle' v-on:keyUp="save">
            </div>
            <div>
                <span>链接</span><input class="form-control" type="text" placeholder="请输入链接地址" v-model:value='itemUrl' v-on:keyUp="save">
            </div>
        </div>
    </div>
</template>

<!--图片导航编辑框-->
<template id="edit-type-22">
    <div class="edit-box type-22"  >

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">必须上传4张图片，比例为1：1，一般用来展示商品分类或者内容分类。</p>
        </div>

        <div class="item-group">
            <div class="input-section" v-for="item in imgs" track-by="id">
                <label class="custom-file-upload">
                    <img v-bind:src="item.imgUrl"/>
                    <div class="prompt-text">
                        <p>+</p>
                        <p>请上传图片</p>
                    </div>
                    <input type="file"  v-on:change="upload_img($index, $event)"/>
                </label>
                <input class="form-control" type="text" placeholder="图片链接" v-model="item.itemUrl" v-on:keyUp="save">
            </div>
        </div>
    </div>
</template>

<!--公众号标题编辑框-->
<template id="edit-type-23">
    <div class="edit-box type-23">

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">如果你想写一篇公众号样式的文章，可以使用该模块作为标题。</p>
        </div>

        <div class="input-section">
            <div>
                <span>标题名</span><input class="form-control" type="text" placeholder="请输入名称" v-model='title' v-on:keyUp="save">
            </div>
            <div>
                <span>日期</span><input  class="form-control" type="date" name="bday" min="2000-01-02" v-model="date" v-on:change="save">
            </div>
            <div>
                <span>作者</span><input class="form-control" type="text" placeholder="请输作者名称" v-model='author' v-on:keyUp="save">
            </div>
            <div>
                <span>链接标题</span><input class="form-control" type="text" placeholder="请输链接标题" v-model='linkTitle' v-on:keyUp="save">
            </div>
            <div>
                <input class="form-control" type="text" placeholder="请输入链接地址" v-model='itemUrl1' v-on:keyUp="save">
            </div>
        </div>

    </div>
</template>

<!--富文本编辑框-->
<template id="edit-type-24">
    <div class="edit-box type-24" >

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">文本模块支持文字，图片，视频，表格的简单排版</p>
        </div>

        <textarea class="mytextarea tinyMCE">{{libDetail}}</textarea>
        <button v-on:click="saveTest">save</button>
    </div>
</template>

<!--单列大图编辑框-->
<template id="edit-type-25">
    <div class="edit-box type-25" >

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">几乎所有内容形式都可以用大图的形式表现，唯一需要注意的是，图片宽度不要小于640像素，否则影响显示的效果。</p>
        </div>

        <div class="item-group">
            <div class="input-section"  transition="staggered"stagger="100" v-for="item in soloColImgs" track-by="$index">
                <p class="deleteItem"  v-on:click="deleteItem($index)">X</p>
                <label class="custom-file-upload">
                    <img v-bind:src="item.imgUrl"/>
                    <div class="prompt-text">
                        <p>+</p>
                        <p>请上传图片</p>
                    </div>
                    <input type="file"  v-on:change="upload_img($index, $event)"/>
                </label>
                <input class="form-control" type="text" placeholder="图片链接" v-model="item.itemUrl" v-on:keyUp="save">
            </div>

            <button class="btn-add" v-on:click="addItem">新增广告图片</button>
        </div>


        <div class="check-group">
            <div class="check-item">
                <input type="checkbox" id="checkbox{{count}}" v-model="toggle">
                <label for="checkbox{{count}}"> 显示图片间的间隙 </label>
            </div>
            <div class="class-invisible">  {{togglePadding}} </div>
        </div>

    </div>
</template>

<!--双列大图编辑框-->
<template id="edit-type-26">
    <div class="edit-box type-26" >

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">几乎所有内容形式都可以用大图的形式表现，唯一需要注意的是，图片宽度不要小于640像素，否则影响显示的效果。</p>
        </div>

        <div class="item-group">
            <div class="input-section"transition="staggered"stagger="100" v-for="item in doubleColImgs" track-by="$index">

                <p class="deleteItem"  v-on:click="deleteItem($index)">X</p>

                <div class="item0">
                    <label class="custom-file-upload">
                        <img v-bind:src="item[0].imgUrl"/>
                        <div class="prompt-text">
                            <p>+</p>
                            <p>请上传图片</p>
                        </div>
                        <input type="file"  v-on:change="upload_img($index, $event,0)"/>
                    </label>
                    <input class="form-control" type="text" placeholder="图片链接" v-model="item[0].itemUrl" v-on:keyUp="save">
                </div>

                <div class="item1">
                    <label class="custom-file-upload">
                        <img v-bind:src="item[1].imgUrl"/>
                        <div class="prompt-text">
                            <p>+</p>
                            <p>请上传图片</p>
                        </div>
                        <input type="file"  v-on:change="upload_img($index, $event,1)"/>
                    </label>
                    <input class="form-control" type="text" placeholder="图片链接" v-model="item[1].itemUrl" v-on:keyUp="save">
                </div>
            </div>

            <button class="btn-add" v-on:click="addItem">新增广告图片</button>
        </div>


        <div class="check-group">
            <div class="check-item">
                <input type="checkbox" id="checkbox{{count}}" v-model="toggle"  >
                <label for="checkbox{{count}}"> 显示图片间的间隙 </label>
            </div>
            <div  class="class-invisible">{{togglePadding}}</div>
        </div>

    </div>
</template>

<!--单列轮播编辑框-->
<template id="edit-type-29">
    <div class="edit-box type-29" >

        <!--提示部分--->
        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">请保证一共有四张图片，所有图片的尺寸比例相同，否则会影响展示效果。可以用来展示店铺当前的主推内容。</p>
        </div>

        <!--主体展示-->
        <div class="item-group">
            <div class="input-section" v-for="item in soloColImgs" track-by="$index">
                <label class="custom-file-upload">
                    <img v-bind:src="item.imgUrl"/>
                    <div class="prompt-text">
                        <p>+</p>
                        <p>请上传图片</p>
                    </div>
                    <input type="file"  v-on:change="upload_img($index, $event)"/>
                </label>
                <input class="form-control" type="text" placeholder="图片链接" v-model="item.itemUrl" v-on:keyUp="save">
            </div>
        </div>


    </div>
</template>

<!--商品大图模块编辑框-->
<template id="edit-type-31">
    <div class="edit-box type-31">

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">几乎所有内容形式都可以用大图的形式表现，唯一需要注意的是，图片宽度不要小于640像素，否则影响显示的效果。</p>
        </div>

        <div >
            <div class="edit-zone">
                <div class="style-selector">

                    <input type="radio" id="cart{{count}}" value="0" v-model="typeOfLib31a">
                    <label for="cart{{count}}">卡片样式</label>

                    <input type="radio" id="noBorder{{count}}" value="1" v-model="typeOfLib31a">
                    <label for="noBorder{{count}}">无框样式</label>

                    <input type="radio" id="simply{{count}}" value="2" v-model="typeOfLib31a">
                    <label for="simply{{count}}">极简样式</label>

                    <span class="class-invisible">Picked: {{type}} {{style}}</span>
                </div>
                <div class="check-group">
                    <p class="check-item"  v-if="typeOfLib31a!=2" >
                        <input type="checkbox" id="checkShowName{{count}}" v-model="showName">
                        <label for="checkShowName{{count}}">显示商品名称</label>
                    </p>
                    <div  class="class-invisible">  {{toggleCheck}} </div>

                    <p class="check-item" v-if="typeOfLib31a!=2" >
                        <input type="checkbox" id="checkShowSold{{count}}" v-model="showSale">
                        <label for="checkShowSold{{count}}">显示销量</label>
                    </p>

                    <p class="check-item" >
                        <input type="checkbox" id="checkShowPrice{{count}}" v-model="showPrice">
                        <label for="checkShowPrice{{count}}">显示价格</label>
                    </p>

                </div>
            </div>

            <div class="input-section">

                <label class="custom-file-upload">
                    <img v-bind:src="imgs[0].imgUrl"/>
                    <div class="prompt-text">
                        <p>+</p>
                        <p>请上传图片</p>
                    </div>
                    <input type="file"  v-on:change="upload_img($event)"/>
                </label>

                <div class="input-items">
                    <div>
                        <span>销量</span><input class="form-control" type="number" placeholder="销量" v-model='sale' v-on:change="save">
                    </div>
                    <div>
                        <span>商品标题</span><input class="form-control" type="text" placeholder="商品标题" v-model='title' v-on:keyUp="save">
                    </div>
                    <div>
                        <span>价格</span><input class="form-control" type="number" step=0.01 placeholder="价格" v-model='price1' v-on:change="save">
                    </div>
                </div>

            </div>

        </div>

    </div>
</template>

<!--商品双列模块编辑框-->
<template id="edit-type-32">
    <div class="edit-box type-32">

        <div class="notice">
            <span class="cancel-edit-box">X</span>
            <p class="notice-text">几乎所有内容形式都可以用大图的形式表现，唯一需要注意的是，图片宽度不要小于640像素，否则影响显示的效果。</p>
        </div>

        <div>
            <div class="edit-zone">
                <div class="style-selector">

                    <input type="radio" id="cart{{count}}" value="0" v-model="typeOfLib32a">
                    <label for="cart{{count}}">卡片样式</label>

                    <input type="radio" id="noBorder{{count}}" value="1" v-model="typeOfLib32a">
                    <label for="noBorder{{count}}">无框样式</label>

                    <input type="radio" id="simply{{count}}" value="2" v-model="typeOfLib32a">
                    <label for="simply{{count}}">极简样式</label>

                    <span class="class-invisible">Picked: {{type}} {{style}}</span>
                </div>

                <div class="check-group">
                    <p class="check-item"  v-if="typeOfLib32a!=2" >
                        <input type="checkbox" id="checkShowName{{count}}" v-model="showName">
                        <label for="checkShowName{{count}}">显示商品名称</label>
                    </p>
                    <div  class="class-invisible">  {{toggleCheck}} </div>

                    <p class="check-item" v-if="typeOfLib32a!=2" >
                        <input type="checkbox" id="checkShowSold{{count}}" v-model="showSale">
                        <label for="checkShowSold{{count}}">显示销量</label>
                    </p>

                    <p class="check-item" >
                        <input type="checkbox" id="checkShowPrice{{count}}" v-model="showPrice">
                        <label for="checkShowPrice{{count}}">显示价格</label>
                    </p>

                </div>
            </div>
            <div class="input-zone">
                <div class="input-section">
                    <label class="custom-file-upload">
                        <img v-bind:src="imgs[0].imgUrl"/>
                        <div class="prompt-text">
                            <p>+</p>
                            <p>请上传图片</p>
                        </div>
                        <input type="file"  v-on:change="upload_img($event,1)"/>
                    </label>
                    <div class="input-items">
                        <div>
                            <span>销量</span><input class="form-control" type="number" placeholder="销量" v-model='sale1' v-on:change="save">
                        </div>
                        <div>
                            <span>商品标题</span><input class="form-control" type="text" placeholder="商品标题" v-model='name1' v-on:keyUp="save">
                        </div>
                        <div>
                            <span>价格</span><input class="form-control" type="number" step=0.01 placeholder="价格" v-model='price1' v-on:change="save">
                        </div>
                    </div>
                </div>

                <div class="input-section">
                    <label class="custom-file-upload">
                        <img v-bind:src="imgs[1].imgUrl"/>
                        <div class="prompt-text">
                            <p>+</p>
                            <p>请上传图片</p>
                        </div>
                        <input type="file"  v-on:change="upload_img($event,2)"/>
                    </label>
                    <div class="input-items">
                        <div>
                            <span>销量</span><input class="form-control" type="number" placeholder="销量" v-model='sale2' v-on:change="save">
                        </div>
                        <div>
                            <span>商品标题</span><input class="form-control" type="text" placeholder="商品标题" v-model='name2' v-on:keyUp="save">
                        </div>
                        <div>
                            <span>价格</span><input class="form-control" type="number" step=0.01 placeholder="价格" v-model='price2' v-on:change="save">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<!--增加框-->
<template id="add-box">
    <div class="add-box">
        <ul class="pagination">
            <!--根据一级分类生成页头-->
            <li v-for="firstTierCategory in templates" track-by="$index" v-on:click="switchPage($event,$index)">
                {{firstTierCategory[1]}}
            </li>
            <span class="cancel-add-box">X</span>
        </ul>
        <hr/>
        <!--根据一级分类确定img-grounp的个数-->
        <div class='img-group'  v-for="firstTierCategory in templates" track-by="$index">
            <hr class="class1"/>
            <div class="img-item" v-for="secondTierCategory in firstTierCategory[2]">
                <img v-bind:src="secondTierCategory[3]" alt="{{secondTierCategory[2]}}"  v-on:click="addLib(secondTierCategory[0],secondTierCategory[1])"  >
                <p>{{secondTierCategory[2]}}</p>
            </div>
        </div>
    </div>
</template>

<!--last-add-box-->
<template id="t0">
    <section  id="count{{count}}" class="lib unsortable" >
        <div id="last-add-box" class="display-box">
            <div class="lib0">
                <p>+</p>
                <p>选择想要添加的模块</p>
            </div>
        </div>
        <add-box></add-box>
    </section>
</template>

<!---------------------------------------------------子组件的模板------------------------------------------------------>
<!--搜索功能模板-->
<template id="t11">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box-------------------------------->
        <div class="display-box">
            <div class="lib11">
                <img src="/<?php echo $this->_var['project_path']; ?>/images/searchForm.PNG">
            </div>
            <auxiliary></auxiliary>
        </div>
        <!---------------------------------------此模块不可编辑---------------------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--进入商店模板-->
<template id="t12" >
    <section id="count{{count}}" class="lib" v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box-------------------------------->
        <div class="display-box ">
            <div class="lib12">
                <img  v-bind:src="imgs[0].imgUrl" alt="标题栏图片" >
                <span>{{libTitle}}</span>
                <span>进入店铺</span>
                <span class="glyphicon glyphicon-chevron-right"></span>
                <auxiliary></auxiliary>
            </div>
        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-12></edit-type-12>
        <!---------------------------------add box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--辅助线模板-->
<template id="t13">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box-------------------------------->
        <div class="display-box">
            <div class="lib13">
                <div class="auxiliary-line-container">
                    <div class="auxiliary-line"></div>
                </div>
            </div>
            <auxiliary></auxiliary>
        </div>
        <!---------------------------------------此模块不可编辑---------------------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--辅助空白模板-->
<template id="t14">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box-------------------------------->
        <div class="display-box">
            <div class="lib14">
                <div style="height: 20px"></div>
            </div>
            <auxiliary></auxiliary>
        </div>
        <!---------------------------------------此模块不可编辑---------------------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--地图模板-->
<template id="t15">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <div class="display-box">
            <div class="lib15">
                <div >
                    <img id="previewMap" src="/<?php echo $this->_var['project_path']; ?>/images/preview/module_505.png" v-on:click="showMap()">
                </div>
                <div id="allmap" style=" height: 160px;overflow:hidden;"></div>
            </div>
            <auxiliary></auxiliary>
        </div>

        <edit-type-15></edit-type-15>
        <add-box></add-box>
    </section>
</template>

<!--文本导航模板-->
<template id="t21">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib21">
                <a href="{{itemUrl1}}" target="_blank">
                    <span> {{libTitle}}</span>
                </a>
                <auxiliary></auxiliary>
            </div>
        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-21></edit-type-21>
        <!------------------------------add box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--图片导航模板-->
<template id="t22" >
    <section id="count{{count}}" class="lib"  v-bind:class="{'class-invisible': invisible}" >
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib22" >
                <div style="display:flex;" >
                    <div class="show-box" v-for="imgItem in imgs" track-by="id">
                        <img v-bind:src="imgItem.imgUrl"/>
                    </div>
                </div>
                <auxiliary></auxiliary>
            </div>
        </div>
        <!---------------------------------edit box--------------------------------->
        <edit-type-22></edit-type-22>
        <!---------------------------------add box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--公众号标题-->
<template id="t23">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib23">
                <div class="title">{{libText1}}</div>
                <div class="footer">
                    <span class="date">{{libText2}}</span>
                    <span class="author">{{libText3}}</span>
                    <span class="linkTitle">{{libText4}}</span>
                </div>
                <auxiliary></auxiliary>
            </div>
        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-23></edit-type-23>
        <!------------------------------edit box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--文字模块模板-->
<template id="t24">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib24">
                <div>
                    {{{libDetail}}}
                </div>
            </div>
            <auxiliary></auxiliary>
        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-24></edit-type-24>
        <!------------------------------edit box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--单列大图模板-->
<template id="t25">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}" >
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib25">
                <div class="show-box"  transition="staggered"
                     stagger="100" v-for="item in soloColImgs" track-by="$index" v-bind:class="{'showPadding':showPadding}">
                    <img v-bind:src="item.imgUrl"/>
                </div>
            </div>
            <auxiliary></auxiliary>
        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-25></edit-type-25>
        <!------------------------------edit box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--双列大图模板-->
<template id="t26">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}" >
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib26">

                <div  class="img-group"  transition="staggered" stagger="100"
                      v-for="item in doubleColImgs" track-by="$index" v-bind:class="{'showPadding':showPadding}">
                    <div class="item">
                        <img v-bind:src="item[0].imgUrl"/>
                    </div>
                    <div class="item">
                        <img v-bind:src="item[1].imgUrl"/>
                    </div>
                </div>

            </div>
            <auxiliary></auxiliary>
        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-26></edit-type-26>
        <!------------------------------edit box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--单列轮播模板-->
<template id="t29">
    <section id="count{{count}}" class="lib"   v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box--------------------------------->
        <div class="display-box">
            <div class="lib25">

                <div class="my-slider">
                    <ul>
                        <li>
                            <img v-bind:src="soloColImgs[0].imgUrl"/>

                        </li>
                        <li>
                            <img v-bind:src="soloColImgs[1].imgUrl"/>

                        </li>
                        <li>
                            <img v-bind:src="soloColImgs[2].imgUrl"/>

                        </li>
                        <li>
                            <img v-bind:src="soloColImgs[3].imgUrl"/>

                        </li>
                        <!--li class="show-box" v-for="item in soloColImgs" track-by="$index">
                            <img v-bind:src="item.imgUrl"/>
                            <a v-bind:href="item.itemUrl" target="_blank"></a>
                        </li-->
                    </ul>
                </div>

            </div>

            <auxiliary></auxiliary>

        </div>
        <!------------------------------edit box--------------------------------->
        <edit-type-29></edit-type-29>
        <!------------------------------edit box--------------------------------->
        <add-box></add-box>
    </section>
</template>

<!--商品模块大图-->
<template id="t31">
    <section id="count{{count}}" class="lib"  v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box-------------------------------->
        <div class="display-box">

            <div  class="lib31"  v-bind:class="{'no-border': style1}">
                <div class="item-image">
                    <img v-bind:src="imgs[0].imgUrl">
                    <p v-if="!style2&&showSale"  class="item-sold">销量{{libText1}}</p>
                </div>
                <p  v-if="!style2&&showName" class="item-title">{{libText2}}</p>
                <p  class="item-price"   v-if="showPrice" >¥ {{price1}}</p>
            </div>

            <auxiliary></auxiliary>

        </div>
        <!-------------------------edit box-------------------------->
        <edit-type-31></edit-type-31>
        <!--------------------------add box-------------------------->
        <add-box></add-box>
    </section>
</template>

<!--商品模块两列-->
<template id="t32">
    <section id="count{{count}}" class="lib"  v-bind:class="{'class-invisible': invisible}">
        <!------------------------------display box-------------------------------->
        <div class="display-box">

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

            <auxiliary></auxiliary>

        </div>
        <!-------------------------edit box-------------------------->
        <edit-type-32></edit-type-32>
        <!--------------------------add box-------------------------->
        <add-box></add-box>
    </section>
</template>

<!--main content-->
<article id="main">
    <div id="hook">
        <div>
            <img src="/<?php echo $this->_var['project_path']; ?>/images/head.PNG" style="width: 100%; margin:0 0 10px 0">
        </div>
    </div>
</article>

<!--footer-->
<footer>
    <div>
        <span class="save-page">
        保存页面
        </span>
         <span class="preview-page">
        预览页面
        </span>
    </div>
</footer>

<script>

    //从后台获取模板数据，在注册add-box时需要使用
    var templateInfo = <?php echo $this->_var['page_template']; ?>;

    templateInfo.sort(function(x, y){
        return (x['lib_id'] - y['lib_id']);
    });

    var projectPath='/'+<?php echo $this->_var['project_path_js']; ?>;

    templateInfo=transformer(templateInfo);

    //从后台获取数据，根据后台数据动态调用html模板
    var pageInfo = <?php echo $this->_var['page_info']; ?>;

    for(let i=0;i<pageInfo.length;i++){
        pageInfo[i]['img_url1']=projectPath+pageInfo[i]['img_url1'];
        pageInfo[i]['img_url2']=projectPath+pageInfo[i]['img_url2'];
        pageInfo[i]['img_url3']=projectPath+pageInfo[i]['img_url3'];
        pageInfo[i]['img_url4']=projectPath+pageInfo[i]['img_url4'];
    }

    //这里假设我们不需要用到dwt_id
    //每一次展示之前都要将pageInfo根据row的大小排序(确保浏览器渲染组件的顺序由row的值来决定)
    pageInfo.sort(function(x, y){
        return (x['row'] - y['row']);
    });

    //注册各个子组件的子组件

    //注册右侧按钮栏组件
    Vue.component('auxiliary',{
        template:'#auxiliary',
        methods:{
            moveUpLib:function(){
                this.$parent.moveUpLib();
            },
            moveDownLib:function(){
                (this).$parent.moveDownLib();
            },
            selectLib:function(){
                (this).$parent.selectLib();
            },
            deleteLib:function(){
                //弹出确认删除的对话框
                var ref=this;
                swal({
                            title: "提示",
                            text: "真的要删除这个组件吗?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "确认",
                            cancelButtonText:"取消",
                            closeOnConfirm: false,
                            closeOnCancel:true
                        },
                        function(isConfirm){
                            if(isConfirm){
                                (ref).$parent.deleteLib();
                                swal({
                                    title: "",
                                    text: "删除成功",
                                    type: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }else{

                            }
                        }
                );
            }
        }
    });

    //注册进入商店的edit-box（一张图 一条连接 一个标题）
    Vue.component('edit-type-12',{
        template:'#edit-type-12',
        data: function(){
            return {
                imgUrl:(this).$parent.imgs[0].imgUrl,
                itemUrl:(this).$parent.imgs[0].itemUrl,
                libTitle:(this).$parent.libTitle
            }
        },

        methods:{
            save:function(){
                (this).$parent.libTitle=(this).libTitle;
                (this).$parent.imgs[0].imgUrl = (this).imgUrl;
                (this).$parent.imgs[0].itemUrl = (this).itemUrl;
                (this).$parent.save();

            },
            upload_img:function(event){
                //因为数组从0开始计数，而自定义imgs的Id从 1 开始
                var target = $(event.target);
                (this).$parent.upload_img(target,1);
            }
        }
    });

    // 注册地图模块的edit-box （一个手动输入的地址）
    Vue.component('edit-type-15',{
        template:'#edit-type-15',
        data:function(){
            return{
                location:(this).$parent.lib_detail
            }
        },

        methods:{
            //按地址名称搜寻
            searchByStationName:function() {

                map.clearOverlays();//清空原来的标注

                var keyword = document.getElementById("text_").value;

                localSearch.search(keyword);

                var ref=this;

                //search完成后的回调函数，searchResult是默认的输入参数
                localSearch.setSearchCompleteCallback(function (searchResult) {

                    var poi = searchResult.getPoi(0);
                    // document.getElementById("result_").value = poi.point.lng + "," + poi.point.lat;
                    map.centerAndZoom(poi.point, 13);

                    // 创建标注，为要查询的地方对应的经纬度
                    var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));
                    map.addOverlay(marker);

                    var content = document.getElementById("text_").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
                    var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
                    marker.addEventListener("click", function () {this.openInfoWindow(infoWindow);});
                    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

                    // 新建display-box中的地图
                    var marker2 = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));
                    // 创建第二个Map实例
                    var map2 = new BMap.Map("allmap");
                    map2.centerAndZoom(new BMap.Point(poi.point.lng, poi.point.lat), 13);
                    map2.addOverlay(marker2);
                    marker2.setAnimation(BMAP_ANIMATION_BOUNCE);

                    $('#previewMap').hide();//隐藏初始化时的预览图

                    //以字符串形式保存地理位置
                    var location=poi.point.lng + "," + poi.point.lat;
                    (ref).$parent.uploadHtml(location);
                });

            }
        }
    });

    //注册文字导航edit-box （文字和链接）
    Vue.component('edit-type-21',{
        template:'#edit-type-21',
        data: function(){
            return {
                libTitle:(this).$parent.libTitle,
                itemUrl:(this).$parent.imgs[0].itemUrl
            }
        },

        computed: {
            libTitle: {
                get: function () {
                    return (this).$parent.libTitle;
                },
                set: function (newValue) {
                    (this).$parent.libTitle=newValue;
                }
            }
        },

        methods:{
            save:function(){
                (this).$parent.imgs[0].itemUrl=(this).itemUrl;
                console.log((this).itemUrl);
                (this).$parent.save();
            }
        }
    });

    //注册图片导航edit-box（有4张以图片和4个链接）
    Vue.component('edit-type-22',{
        template:'#edit-type-22',
        data: function(){
            return {
                imgs:(this).$parent.imgs
            }
        },

        methods:{
            save:function(){
                (this).$parent.libTitle=(this).libTitle;
                (this).$parent.imgs = (this).imgs;
                (this).$parent.save();
            },
            upload_img:function(index,event){
                //因为数组从0开始计数，而自定义的id 从1 开始
                index=index+1;
                var target = $(event.target);
                (this).$parent.upload_img(target,index);
            }
        }
    });

    //注册公众号标题edit-box
    Vue.component('edit-type-23',{
        template:'#edit-type-23',
        data: function(){
            return {
                title:(this).$parent.libText1,
                date:(this).$parent.libText2,
                author:(this).$parent.libText3,
                linkTitle:(this).$parent.libText4,
                itemUrl1:(this).$parent.itemUrl1
            }
        },

        methods:{
            save:function(){
                (this).$parent.libText1=(this).title;
                (this).$parent.libText2=(this).date;
                (this).$parent.libText3=(this).author;
                (this).$parent.libText4=(this).linkTitle;
                (this).$parent.itemUrl1=(this).itemUrl1;

                (this).$parent.save();
            }
        }
    });

    //注册富文本编辑器的edit-box （带富文本编辑器）
    Vue.component('edit-type-24',{
        template:'#edit-type-24',
        data:function(){
            return {
                libDetail:(this).$parent.libDetail
            }
        },
        methods:{
            saveTest:function(){
                //下面这个函数堪称完美，它直接触发当前tinyMCE的保存
                tinyMCE.triggerSave();
                //
                var testContent=(this).$el.children[2].value;
                (this).$parent.uploadHtml(testContent);
            }
        }
    });

    //单列大图的edit-box （带四张图和四个链接）
    Vue.component('edit-type-25',{
        template:'#edit-type-25',
        data: function(){
            return {
                imgs:(this).$parent.imgs,
                soloColImgsNum:(this).$parent.soloColImgsNum,
                soloColImgs:(this).$parent.soloColImgs,

                //初始化toggle
                toggle:(this).$parent.showPadding,
            }
        },

        computed:{
            togglePadding:function(){

                //toggle　一定不能在computed methods events 选项中被赋值，不然就不能再外部改变它
                //父组件初始化根据pageInfo 数据，而后进行的任何操作（被删除后重新增加时初始化）都由toggle 来间接完成
                if((this).toggle){
                    //更新pageInfo层数据
                    pageInfo[(this).$parent.count]['type'] = 1;
                    //更新实例层display-box数据
                    (this).$parent.type =1;
                }else{
                    pageInfo[(this).$parent.count]['type'] = 0;
                    (this).$parent.type = 0;
                }
                return true;
            },

            count:function(){
                return (this).$parent.count;
            }
        },

        methods: {

            addItem: function () {
                if ((this).soloColImgs.length < (this).imgs.length) {
                    (this).soloColImgsNum++;
                    //更新pageInfo数据
                    pageInfo[(this).$parent.count]['lib_detail'] = (this).soloColImgsNum;

                    var num = (this).soloColImgs.length + 1;
                    (this).soloColImgs.push({id: num, imgUrl: "/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg", itemUrl: ""});

                    for (var i = 0; i < (this).soloColImgs.length; i++) {
                        (this).imgs[i] = (this).soloColImgs[i];
                    }

                    //更新父组件
                    (this).$parent.soloColImgsNum++;
                    (this).$parent.imgs = (this).imgs;
                    (this).$parent.save();

                } else {
                    console.log("not enough items");
                }
            },

            deleteItem: function (index) {

                if ((this).soloColImgs.length > 1) {

                    (this).soloColImgsNum--;
                    //更新pageInfo数据
                    pageInfo[(this).$parent.count]['lib_detail'] = (this).soloColImgsNum;

                    (this).soloColImgs.splice(index, 1);

                    //按soloColImgs结构更新soloColImgs的id值
                    for (var i = 0; i < (this).soloColImgs.length; i++) {
                        (this).soloColImgs[i]["id"] = i + 1;
                    }

                    //根据soloColImgs更新imgs
                    for (i = 0; i < (this).soloColImgs.length; i++) {
                        (this).imgs[i] = (this).soloColImgs[i];
                    }

                    //更新父组件
                    (this).$parent.soloColImgsNum--;
                    (this).$parent.imgs = (this).imgs;
                    (this).$parent.save();

                }
                else {
                    console.log("at least one img")
                }
            },

            save: function () {
                (this).$parent.imgs = (this).imgs;
                // 这里主要是将vue实例的数据更新到pageInfo中
                (this).$parent.save();
            },

            upload_img: function (index, event) {

                index = index + 1;
                var target = $(event.target);

                //创建FormData对象
                var data = new FormData();

                //为FormData对象添加数据
                $.each(target[0].files, function (i, file) {
                    data.append('upload_img', file);
                });

                var ref = this;

                $.ajax({
                    url: 'upload_img.php',
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,        //不可缺参数
                    processData: false,        //不可缺参数
                    success: function (result) {
                        if (result) {
                            result=projectPath+result;
                            //更新pageInfo信息
                            pageInfo[(ref).$parent.count]['img_url' + index] = result;

                            //更新本组件soloColImgs数据
                            for (var i = 0; i < (ref).soloColImgs.length; i++) {
                                if ((ref).soloColImgs[i].id == index) {
                                    (ref).soloColImgs[i].imgUrl = result;
                                }
                            }

                            //更新本组件imgs数据
                            for (i = 0; i < (ref).soloColImgs.length; i++) {
                                (ref).imgs[i] = (ref).soloColImgs[i];
                            }

                            //更新父组件数据
                            (ref).$parent.imgs = (ref).imgs;

                        } else {
                            alert('图片不符要求');
                        }
                    },
                    error: function () {
                        alert('上传失败');
                    }
                });

                (this).$parent.imgs = (this).imgs;
                // 这里主要是将vue实例的数据更新到pageInfo中
                (this).$parent.save();
            }
        }

    });

    //双列大图的edit-box （带四张图和四个链接）
    Vue.component('edit-type-26',{
        template:'#edit-type-26',

        data: function(){
            return {
                imgs:(this).$parent.imgs,

                //doubleColImgsNum只有1和3两个值
                doubleColImgsNum:(this).$parent.doubleColImgsNum,
                doubleColImgs:(this).$parent.doubleColImgs,

                //初始化toggle
                toggle:(this).$parent.showPadding,
            }
        },

        computed:{
            togglePadding:function(){

                //toggle　一定不能在computed methods events 选项中被赋值，不然就不能再外部改变它
                //父组件初始化根据pageInfo 数据，而后进行的任何操作（被删除后重新增加时初始化）都由toggle 来间接完成
                if((this).toggle){
                    //更新pageInfo层数据
                    pageInfo[(this).$parent.count]['type'] = 1;
                    //更新实例层display-box数据
                    (this).$parent.type =1;
                }else{
                    pageInfo[(this).$parent.count]['type'] = 0;
                    (this).$parent.type = 0;
                }
                return true;

            },

            count:function(){
                return (this).$parent.count;
            }
        },

        methods:{

            addItem:function(){
                if( (this).doubleColImgs.length<2)
                {
                    (this).doubleColImgsNum+=2;
                    //更新pageInfo数据
                    pageInfo[(this).$parent.count]['lib_detail']= (this).doubleColImgsNum;

                    var num = ((this).doubleColImgs.length)*2+1;

                    var temp=[{id:num, imgUrl: "/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg" , itemUrl: ""},{id:num+1, imgUrl: "/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg" , itemUrl: ""}];

                    (this).doubleColImgs.push(temp);

                    for(var i=0;i<(this).doubleColImgs.length;i++){
                        var j=i*2;
                        (this).imgs[j]=(this).doubleColImgs[i][0];
                        (this).imgs[j+1]=(this).doubleColImgs[i][1];
                    }

                    //更新父组件
                    (this).$parent.doubleColImgsNum+=2;
                    (this).$parent.imgs = (this).imgs;
                    (this).$parent.save();

                }else{
                    console.log("not enough items");
                }
            },

            deleteItem:function(index){

                if((this).doubleColImgs.length>1){

                    (this).doubleColImgsNum-=2;
                    //更新pageInfo数据
                    pageInfo[(this).$parent.count]['lib_detail']= (this).doubleColImgsNum;

                    (this).doubleColImgs.splice(index,1);


                    //按doubleColImgs结构更新doubleColImgs的id值
                    for(var i=0;i<(this).doubleColImgs.length;i++){

                        var j = i*2+1;
                        (this).doubleColImgs[i][0]["id"]=j;
                        (this).doubleColImgs[i][1]["id"]=j+1;
                        console.log( (this).doubleColImgs[i]);
                    }

                    //根据doubleColImgs更新imgs
                    for( i=0;i<(this).doubleColImgs.length;i++){
                        var j=i*2;
                        (this).imgs[j] = (this).doubleColImgs[i][0];
                        (this).imgs[j+1] = (this).doubleColImgs[i][1];
                    }

                    //更新父组件
                    (this).$parent.doubleColImgsNum-=2;
                    (this).$parent.imgs = (this).imgs;
                    (this).$parent.save();

                }
                else{
                    console.log("at least one img")
                }
            },

            save:function(){
                (this).$parent.imgs = (this).imgs;
                // 这里主要是将vue实例的数据更新到pageInfo中
                (this).$parent.save();
            },

            upload_img:function(index,event,sequence){

                index=(index)*2+1+sequence;
                var target = $(event.target);

                //创建FormData对象
                var data = new FormData();

                //为FormData对象添加数据
                $.each(target[0].files, function (i, file) {
                    data.append('upload_img', file);
                });

                var ref=this;

                $.ajax({
                    url: 'upload_img.php',
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,        //不可缺参数
                    processData: false,        //不可缺参数
                    success: function (result) {
                        if (result) {
                            result=projectPath+result;
                            //更新pageInfo信息
                            pageInfo[(ref).$parent.count]['img_url'+index]=result;

                            //更新本组件doubleColImgs数据
                            for(var i=0;i<(ref).doubleColImgs.length;i++){
                                if((ref).doubleColImgs[i][0].id==index){
                                    (ref).doubleColImgs[i][0].imgUrl=result;
                                }
                                if((ref).doubleColImgs[i][1].id==index){
                                    (ref).doubleColImgs[i][1].imgUrl=result;
                                }
                            }

                            //更新本组件imgs数据
                            for( i=0;i<(ref).doubleColImgs.length;i++){
                                var j=i*2;
                                (ref).imgs[j] = (ref).doubleColImgs[i][0];
                                (ref).imgs[j+1] = (ref).doubleColImgs[i][1];
                            }

                            //更新父组件数据
                            (ref).$parent.imgs=(ref).imgs;

                        } else {
                            alert('图片不符要求');
                        }
                    },
                    error: function () {
                        alert('上传失败');
                    }
                });

                (this).$parent.imgs = (this).imgs;
                // 这里主要是将vue实例的数据更新到pageInfo中
                (this).$parent.save();
            }
        }
    });

    //单列轮播的edit-box （带四张图和四个链接）
    Vue.component('edit-type-29',{
        template:'#edit-type-29',
        data: function(){
            return {
                imgs:(this).$parent.imgs,
                soloColImgsNum:(this).$parent.soloColImgsNum,
                soloColImgs:(this).$parent.soloColImgs
            }
        },

        methods:{

            save:function(){

                (this).$parent.imgs = (this).imgs;
                // 这里主要是将vue实例的数据更新到pageInfo中
                (this).$parent.save();
            },

            upload_img:function(index,event){

                index=index+1;
                var target = $(event.target);

                //创建FormData对象
                var data = new FormData();

                //为FormData对象添加数据
                $.each(target[0].files, function (i, file) {
                    data.append('upload_img', file);
                });

                var ref=this;

                $.ajax({
                    url: 'upload_img.php',
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,        //不可缺参数
                    processData: false,        //不可缺参数
                    success: function (result) {
                        if (result) {
                            result=projectPath+result;
                            //更新pageInfo信息
                            pageInfo[(ref).$parent.count]['img_url'+index]=result;

                            //更新本组件soloColImgs数据
                            for(var i=0;i<(ref).soloColImgs.length;i++){
                                if((ref).soloColImgs[i].id==index){
                                    (ref).soloColImgs[i].imgUrl=result;
                                }
                            }

                            //更新本组件imgs数据
                            for( i=0;i<(ref).soloColImgs.length;i++){
                                (ref).imgs[i]=(ref).soloColImgs[i];
                            }

                            //更新父组件数据
                            (ref).$parent.imgs=(ref).imgs;

                        } else {
                            alert('图片不符要求');
                        }
                    },
                    error: function () {
                        alert('上传失败');
                    }
                });

                (this).$parent.imgs = (this).imgs;
                // 这里主要是将vue实例的数据更新到pageInfo中
                (this).$parent.save();
            }


        }
    });

    //商品大图edit-box
    Vue.component('edit-type-31',{
        template:'#edit-type-31',
        data: function(){
            return {
                sale:(this).$parent.libText1,
                title:(this).$parent.libText2,
                price1:(this).$parent.price1,

                showSale:(this).$parent.showSale,
                showName:(this).$parent.showName,
                showPrice:(this).$parent.showPrice,

                typeOfLib31a:(this).$parent.typeOfLib31a,
                imgs:(this).$parent.imgs,

            }
        },

        computed:{
            //下面这段代码有没有return都没关系，重要的是让style动态的跟踪三个select option
            style:function(){
                if((this).typeOfLib31a==1){
                    //思路都是先
                    pageInfo[(this).$parent.count]['type_of_lib31a'] = 1;
                    (this).$parent.typeOfLib31a = 1;
                }
                else if((this).typeOfLib31a==0){
                    pageInfo[(this).$parent.count]['type_of_lib31a'] = 0;
                    (this).$parent.typeOfLib31a = 0;
                }else if((this).typeOfLib31a==2) {
                    pageInfo[(this).$parent.count]['type_of_lib31a'] = 2;
                    (this).$parent.typeOfLib31a = 2;
                }
            },

            /********
             * if you have any better idea, optimize it!
             * showName showSale showPrice  typeOfLib31b
             0	    0   	0		0
             0	    0	    1		1
             0	    1	    0		2
             0	    1	    1		3
             1	    0	    0		4
             1	    0	    1		5
             1	    1	    0		6
             1	    1	    1		7
             *************************/
            //下面这段代码有没有return都没关系，重要的是让type_of_lib31b动态的跟踪showName showSale showPrice三个checkbox
            toggleCheck:function(){
                if((this).showName&&(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib31b=7;
                    pageInfo[(this).$parent.count]['type_of_lib31b']=7;
                    return 7;
                }else if((this).showName&&(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib31b=6;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=6;
                    return 6;
                }else if((this).showName&&!(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib31b=5;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=5;
                    return 5;
                }else if((this).showName&&!(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib31b=4;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=4;
                    return 4;
                }else if(!(this).showName&&(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib31b=3;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=3;
                    return 3;
                }else if(!(this).showName&&(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib31b=2;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=2;
                    return 2;
                }else if(!(this).showName&&!(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib31b=1;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=1;
                    return 1;
                }else if(!(this).showName&&!(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib31b=0;
                    pageInfo[ (this).$parent.count]['type_of_lib31b']=0;
                    return 0;
                }
            },

            count:function(){
                return (this).$parent.count
            }

        },

        methods:{

            upload_img:function(event){

                var target = $(event.target);
                (this).$parent.upload_img(target,1);

            },

            save:function(){
                (this).$parent.libText1=(this).sale;
                (this).$parent.libText2=(this).title;
                (this).$parent.price1=(this).price1;
                (this).$parent.imgs=(this).imgs;
                (this).$parent.save();
            }

        }
    });

    //商品双列edit-box
    Vue.component('edit-type-32',{
        template:'#edit-type-32',
        data: function(){
            return {
                sale1:(this).$parent.libText1,
                name1:(this).$parent.libText2,
                sale2:(this).$parent.libText3,
                name2:(this).$parent.libText4,
                price1:(this).$parent.price1,
                price2:(this).$parent.price2,

                showSale:(this).$parent.showSale32,
                showName:(this).$parent.showName32,
                showPrice:(this).$parent.showPrice32,

                typeOfLib32a:(this).$parent.typeOfLib32a,
                typeOfLib32b:(this).$parent.typeOfLib32b,

                imgs:(this).$parent.imgs

            }
        },

        computed:{
            //下面这段代码有没有return都没关系，重要的是让style动态的跟踪三个select option
            style:function(){
                if((this).typeOfLib32a==1){
                    //思路都是先
                    pageInfo[(this).$parent.count]['type_of_lib32a'] = 1;
                    (this).$parent.typeOfLib32a = 1;
                }
                else if((this).typeOfLib32a==0){
                    pageInfo[(this).$parent.count]['type_of_lib32a'] = 0;
                    (this).$parent.typeOfLib32a = 0;
                }else if((this).typeOfLib32a==2) {
                    pageInfo[(this).$parent.count]['type_of_lib32a'] = 2;
                    (this).$parent.typeOfLib32a = 2;
                }
            },

            /************************
             * if you have any better idea, optimize it!
             * showName showSale showPrice  typeOfLib31b
             0	    0   	0		0
             0	    0	    1		1
             0	    1	    0		2
             0	    1	    1		3
             1	    0	    0		4
             1	    0	    1		5
             1	    1	    0		6
             1	    1	    1		7
             *************************/
            //下面这段代码有没有return都没关系，重要的是让type_of_lib31b动态的跟踪showName showSale showPrice三个checkbox
            toggleCheck:function(){
                if((this).showName&&(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib32b=7;
                    pageInfo[(this).$parent.count]['type_of_lib32b']=7;
                    return 7;
                }else if((this).showName&&(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib32b=6;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=6;
                    return 6;
                }else if((this).showName&&!(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib32b=5;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=5;
                    return 5;
                }else if((this).showName&&!(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib32b=4;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=4;
                    return 4;
                }else if(!(this).showName&&(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib32b=3;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=3;
                    return 3;
                }else if(!(this).showName&&(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib32b=2;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=2;
                    return 2;
                }else if(!(this).showName&&!(this).showSale &&(this).showPrice){
                    (this).$parent.typeOfLib32b=1;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=1;
                    return 1;
                }else if(!(this).showName&&!(this).showSale &&!(this).showPrice){
                    (this).$parent.typeOfLib32b=0;
                    pageInfo[ (this).$parent.count]['type_of_lib32b']=0;
                    return 0;
                }
            },

            count:function(){
                return (this).$parent.count;
            }

        },

        methods:{

            upload_img:function(event,index){

                var target = $(event.target);
                (this).$parent.upload_img(target,index);

            },

            save:function(){
                (this).$parent.libText1=(this).sale1;
                (this).$parent.libText2=(this).name1;
                (this).$parent.libText3=(this).sale2;
                (this).$parent.libText4=(this).name2;

                (this).$parent.price1=(this).price1;
                (this).$parent.price2=(this).price2;
                (this).$parent.save();
            }

        }
    });

    //注册add-box
    Vue.component('add-box',{
        template:'#add-box',
        data:function(){
            return{
                templates:templateInfo
            }
        },

        methods:{
            //把libId传进来了，稍后要把对应的隐藏组件显示，然后排序
            addLib:function(libId,info){
                //以下的lib比较特别，一个libId可能有多种展示形式
                var type;

                if(libId==25){
                    if(info=="single_img0"){
                        type = 0;
                    }else{
                        type = 1;
                    }
                }

                if(libId==26){
                    if(info=="double_img0"){
                        type = 0;
                    }else{
                        type = 1;
                    }
                }

                if(libId==31){
                    if(info=="product_solo0"){
                        type = 0;
                    }else if(info=="product_solo1"){
                        type = 1;
                    }else{
                        type = 2;
                    }
                }

                if(libId==32){
                    if(info=="products_double0"){
                        type = 0;
                    }else if(info=="products_double1"){
                        type = 1;
                    }else{
                        type = 2;
                    }
                }
                //...................................
                (this).$parent.addLib(libId,info,type);
            },

            switchPage:function(event,index){
                switchPage(event.target,index);
            }

        }
    });

    //注册各个子组件
    for(let i=0;i<pageInfo.length;i++) {

        pageInfo[i]['row']=parseInt(pageInfo[i]['row']);

        //在html中动态生成需要钩子
        $("#hook").append("<row_"+pageInfo[i]['row']+"></row_" +pageInfo[i]['row']+">");

        Vue.component('row_'+pageInfo[i]['row'], {
            template: '#t' + pageInfo[i]['lib_id'],

            data: function () {
                return {
                    // control data
                    count: i,//count是确定当前组件位置的唯一可靠的标识，Vue.component名称中的row值只有在每次刷新页面后才能保持同步
                    invisible: false,
                    lastAddBox:false,

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
                    imgUrl1: pageInfo[i]['img_url1'],
                    itemUrl1: pageInfo[i]['item_url1'],
                    imgUrl2:  pageInfo[i]['img_url2'],
                    itemUrl2: pageInfo[i]['item_url2'],
                    imgUrl3:  pageInfo[i]['img_url3'],
                    itemUrl3: pageInfo[i]['item_url3'],
                    imgUrl4: pageInfo[i]['img_url4'],
                    itemUrl4: pageInfo[i]['item_url4'],

                    //external links, 写成数组形式是为了使用v-for从而实现代码模块化和参数化
                    imgs:[
                        {id : 1,  imgUrl: pageInfo[i]['img_url1'], itemUrl:pageInfo[i]['item_url1']},
                        {id : 2,  imgUrl: pageInfo[i]['img_url2'], itemUrl:pageInfo[i]['item_url1']},
                        {id : 3,  imgUrl: pageInfo[i]['img_url3'], itemUrl:pageInfo[i]['item_url1']},
                        {id : 4,  imgUrl: pageInfo[i]['img_url4'], itemUrl:pageInfo[i]['item_url1']}
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

                showName32:function(){
                    return((this).typeOfLib32b==4||(this).typeOfLib32b==5||(this).typeOfLib32b==6||(this).typeOfLib32b==7);
                },
                //显示销量
                showSale32:function(){
                    return((this).typeOfLib32b==2||(this).typeOfLib32b==3||(this).typeOfLib32b==6||(this).typeOfLib32b==7);
                },
                //显示价格
                showPrice32:function(){

                    return((this).typeOfLib32b==1||(this).typeOfLib32b==3||(this).typeOfLib32b==5||(this).typeOfLib32b==7);
                }

            },

            methods:{

                moveUpLib : function(){

                    //在pageInfo层面更新数据
                    if((this).count>0){
                        var index=(this).count;
                        //本行与上一行的row值对调
                        pageInfo[index]['row']=(this).row-1;
                        pageInfo[index-1]['row']=(this).row;
                        //保证pageInfo按row值大小排序
                        pageInfo.sort(function(x, y){
                            return (x['row'] - y['row']);
                        });


                        //在vue实例层面更新数据
                        (this).$dispatch('moveUpLib',(this).count);


                    }else{
                        swal({
                            title: "提示",
                            text: "不能上移",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },

                moveDownLib:function(){
                    //在pageInfo层面更新数据
                    var limit=visibilityNum();
                    if((this).count<(limit-1)){
                        var index=(this).count;
                        //本行与下一行的row值对调
                        pageInfo[index]['row']=(this).row+1;
                        pageInfo[index+1]['row']=(this).row;

                        //保证pageInfo按row值大小排序
                        pageInfo.sort(function(x, y){
                            return (x['row'] - y['row']);
                        });

                        //在vue实例层面更新数据
                        (this).$dispatch('moveDownLib',(this).count);
                    }else{
                        swal({
                            title: "提示",
                            text: "不能下移",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },

                deleteLib : function(){
                    //将被删除的组件设置为隐藏
                    pageInfo[(this).count]['visibility']=0;

                    //其用户自定义的数据将被归为默认值
                    pageInfo[(this).count]['lib_title']='';

                    if((this).libId==25||(this).libId==26){
                        pageInfo[(this).count]['lib_detail']=1;
                    }else if((this).libId==29){
                        pageInfo[(this).count]['lib_detail']=4;
                    }else{
                        pageInfo[(this).count]['lib_detail']='';
                    }

                    pageInfo[(this).count]['img_url1']='/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg';
                    pageInfo[(this).count]['item_url1']='';
                    pageInfo[(this).count]['img_url2']='/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg';
                    pageInfo[(this).count]['item_url2']='';
                    pageInfo[(this).count]['img_url3']='/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg';
                    pageInfo[(this).count]['item_url3']='';
                    pageInfo[(this).count]['img_url4']='/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg';
                    pageInfo[(this).count]['item_url4']='';

                    //将被删除的组件放到数组的最后
                    var temp=pageInfo[(this).count];
                    pageInfo.splice((this).count,1);
                    pageInfo.push(temp);

                    //更新同类组件的libNum以及更新整个pageInfo的row值
                    for(var index=0;index<pageInfo.length;index++){
                        pageInfo[index]['row']=index+1;
                        if(pageInfo[index]['lib_id']==(this).libId){
                            pageInfo[index]['lib_num'] =  pageInfo[index]['lib_num']-1;
                        }
                    }

                    //在vue组件层面更新数据
                    (this).$dispatch('deleteLib',(this).count);

                },

                addLib:function(libId,info,type){

                    //首先在pageInfo层面更新数据
                    var flag=addLibHelper((this).lastAddBox,(this).count,libId,info,type);
                    //flag用于标记组件数目是否足够   当组件数目足够时才更新vue实例
                    if(flag){
                        //在vue实例层面更新数据
                        (this).$dispatch('addLib',(this).lastAddBox,(this).count,libId,type);
                    }
                },

                save:function(){

                    //以后还要加上其他修改的属性，这是自动调用的
                    var index=(this).count;
                    pageInfo[index]['lib_title']=(this).libTitle;
                    pageInfo[index]['lib_text1']=(this).libText1;
                    pageInfo[index]['lib_text2']=(this).libText2;
                    pageInfo[index]['lib_text3']=(this).libText3;
                    pageInfo[index]['lib_text4']=(this).libText4;
                    pageInfo[index]['price1']=(this).price1;
                    pageInfo[index]['price2']=(this).price2;


                    //更新external links
                    for (var i=1;i<=(this).imgs.length;i++){
                        pageInfo[index]['item_url'+i]=(this).imgs[i-1]['itemUrl'];
                        pageInfo[index]['img_url'+i]=(this).imgs[i-1]['imgUrl'];
                    }
                },

                uploadHtml:function(testContent){

                    pageInfo[(this).count]['lib_detail']=testContent;

                    (this).libDetail=testContent;

                },

                upload_img: function (target,index) {
                    //index从1开始数
                    //创建FormData对象
                    var data = new FormData();

                    //为FormData对象添加数据
                    $.each(target[0].files, function (i, file) {
                        data.append('upload_img', file);
                    });

                    var ref=this;
                    $.ajax({
                        url: 'upload_img.php',
                        type: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,        //不可缺参数
                        processData: false,        //不可缺参数
                        success: function (result) {
                            if (result) {
                                result=projectPath+result;
                                //更新pagaInfo层面的数据
                                pageInfo[(ref).count]['img_url'+index]=result;
                                //更新vue实例层面的数据
                                for(var i=0;i<(ref).imgs.length;i++){
                                    if((ref).imgs[i].id==index){
                                        (ref).imgs[i].imgUrl=result;
                                    }
                                }
                            } else {
                                alert('图片不符要求');
                            }
                        },
                        error: function () {
                            alert('上传失败');
                        }
                    });
                },

                showMap:function(){
                    var position=(this).libDetail.split(/[,]+/);
                    // 创建Map实例
                    var map = new BMap.Map("allmap");
                    // 初始化地图,设置中心点坐标和地图级别
                    map.centerAndZoom(new BMap.Point(Number(position[0]), Number(position[1])), 13);
                    // 创建标注，为要查询的地方对应的经纬度
                    var marker = new BMap.Marker(new BMap.Point( Number(position[0]), Number(position[1])));
                    marker.setAnimation(BMAP_ANIMATION_BOUNCE);
                    //把标注加到地图上
                    map.addOverlay(marker);
                }
            }

            /*       events:{
             'test':function(type){
             if(type==1){
             console.log(this.type);
             this.type=1;
             console.log(this.type);
             pageInfo[this.count]['type'] = 1;
             }else if(type==0){
             this.type=0;
             pageInfo[this.count]['type'] = 0;
             }
             console.log('hi');
             }
             }*/

        });
    }

    //把之前所有注册过的子组件和孙子组件统一实例化到一个 Vue中
    var root = new Vue({
        el: '#main',
        events: {
            'deleteLib': function (index) {

                //对(this).$children实例进行类似于pageInfo的操作
                //在dom层面更新数据
                (this).$children[index]['visibility']=0;

                if((this).$children[index]['libId']==25){

                    //   (this).$children[index].$children[1].toggle=false;

                    (this).$children[index]['soloColImgsNum']=1;

                    // 若是有超过一张大图，则将第一张图后面的大图都pop出来
                    for(var i=(this).$children[index].$children[1]['soloColImgsNum'];i>1;i--)
                    {
                        (this).$children[index].$children[1]['soloColImgs'].pop();//=[{id:1, imgUrl: "/<?php echo $this->_var['project_path']; ?>/images/dcr-default-bg504.jpg" , itemUrl: ""}];
                    }
                    (this).$children[index].$children[1]['soloColImgsNum']=1;
                }

                if((this).$children[index]['libId']==26){

                    //   (this).$children[index].$children[1].toggle=false;

                    (this).$children[index]['doubleColImgsNum']=1;

                    // 若是有超过一行双列大图，则将第一行双列大图后面的行都pop出来
                    for(var i=(this).$children[index].$children[1]['doubleColImgsNum'];i>1;)
                    {
                        (this).$children[index].$children[1]['doubleColImgs'].pop();
                        i=i-2;
                    }
                    (this).$children[index].$children[1]['doubleColImgsNum']=1;

                }

                var last=pageInfo.length-1;
                $('#count'+index).insertAfter($('#count'+last));

                //将被删除的组件放到数组的最后
                var temp=(this).$children[index];
                (this).$children.splice(index, 1);
                (this).$children.push(temp);


                //用pageInfo更新vue实例中的数据，从第一个元素到最后一个元素刷新一次
                for( i=0;i<(this).$children.length;i++){

                    (this).$children[i]['count']=pageInfo[i]['row']-1;
                    (this).$children[i]['row']=pageInfo[i]['row'];
                    (this).$children[i]['libDetail']=pageInfo[i]['lib_detail'];
                    (this).$children[i]['libTitle']=pageInfo[i]['lib_title'];
                    (this).$children[i]['imgUrl1']=pageInfo[i]['img_url1'];
                    (this).$children[i]['itemUrl1']=pageInfo[i]['item_url1'];
                    (this).$children[i]['libNum']=pageInfo[i]['lib_num'];
                    (this).$children[i]['type']=pageInfo[i]['type'];

                }

            },

            'moveUpLib': function (index) {

                /**********************这段代码没有人看得懂的，以后必须修改，以增加可读性*************************/
                //在视觉层面交换组件前首先尝试关闭tinyMce
                var prev = index - 1;

                //下面这句是寻根问底的去寻找tinymce实例的id啊!!!
                if((this).$children[index].libId==24) {
                    // 文字模块的component         EditType3      textarea
                    var tinymceId = (this).$children[index].$children[1].$el.children[2].id;
                    tinymce.execCommand('mceRemoveEditor', false, tinymceId);
                }

                //在视觉层面交换两个组件
                var t =  $('#count' + index);
                var th=t.outerHeight(true)+10;
                var tp= $('#count' + prev);
                var tph= tp.outerHeight(true)+10;

                t.velocity({top: -tph},300, function(){
                    //动画完成后的回调函数
                    t.css('top', '0px');
                });


                tp.velocity({top:th},300,function(){
                    tp.css('top','0px');
                    t.insertBefore(tp);
                    //在视觉层重启tinymce
                    tinymce.execCommand('mceAddEditor', true, tinymceId);
                });


                //在vue实例层面交换组件前的count值和row值
                (this).$children[prev]['count'] = (this).$children[prev]['count'] + 1;
                (this).$children[prev]['row'] = (this).$children[prev]['row'] + 1;

                (this).$children[index]['count'] = (this).$children[index]['count'] - 1;
                (this).$children[index]['row'] = (this).$children[index]['row'] - 1;


                //在vue实例层面交换上下两个组件
                var temp = (this).$children[prev];
                (this).$children[prev] = (this).$children[index];
                (this).$children[index] = temp;

                //保证vue实例中的组件按row的值排序
                (this).$children.sort(function (x, y) {
                    return (x['row'] - y['row']);
                });
            },

            'moveDownLib': function (index) {
                var next = index + 1;

                //在视觉曾关闭实例
                if((this).$children[index].libId==24) {

                    var tinymceId = (this).$children[index].$children[1].$el.children[2].id;
                    tinymce.execCommand('mceRemoveEditor', false, tinymceId);
                }
                //在视觉层面交换两个组件
                var t =  $('#count' + index);
                var th=t.outerHeight(true)+10;
                var tn= $('#count' + next);
                var tnh= tn.outerHeight(true)+10;

                t.velocity({top: tnh}, 300, function(){
                    t.css('top', '0px');
                });

                tn.velocity({top:-th},300,function(){
                    tn.css('top','0px');
                    t.insertAfter(tn);

                    //在视觉层重启tinymce
                    tinymce.execCommand('mceAddEditor', true, tinymceId);
                });

                //交换组件前先修改其对应的count值和row值
                (this).$children[next]['count'] = (this).$children[next]['count'] - 1;
                (this).$children[next]['row'] = (this).$children[next]['row'] - 1;

                (this).$children[index]['count'] = (this).$children[index]['count'] + 1;
                (this).$children[index]['row'] = (this).$children[index]['row'] + 1;

                //在vue实例层面交换上下两个组件
                var temp = (this).$children[next];
                (this).$children[next] = (this).$children[index];
                (this).$children[index] = temp;

                //保证vue实例中的组件按row的值排序
                (this).$children.sort(function (x, y) {
                    return (x['row'] - y['row']);
                });
            },

            'addLib': function (lastAddBox,count, libId,type) {

                //在这个函数的运行过程中count值是永远不变的
                //对(this).$children 做类似于pageInfo的操作

                //数一数有多少个组件是可见的
                var visCompNum = 0;
                for (var i = 0; i < (this).$children.length; i++) {
                    if ((this).$children[i]['visibility'] == 1) {
                        visCompNum++;
                    }
                }

                var  index=visCompNum;
                //找出第一个隐藏的同类Lib并把它显示出来
                for(index;index<(this).$children.length;index++){
                    if ((this).$children[index]['libId'] == libId){
                        (this).$children[index]['visibility']=1;
                        break;
                    }
                }

                //首先尝试关闭tinyMce实例
                if(libId==24) {
                    //下面这句是寻根问底的去寻找被选中的文字模块对应的tinymce实例的id啊!!!
                    //如果你想知道为什么要这样写请用console.log()
                    //若是HTMl结构改变， 最后一个children[]里面的参数也要相应改变
                    // 文字模块的component         EditType3      textarea
                    var tinymceId = (this).$children[index].$children[1].$el.children[2].id;
                    tinymce.get(tinymceId).setContent('');
                    tinymce.execCommand('mceRemoveEditor', false, tinymceId);
                }

                //下面这段代码很重要，如果不在这里改变toggle, 则无法改变computed 中的pageInfo[]['type']与vue实例中的type
                if(libId==25||26){
                    if(type==0) {
                        (this).$children[index].$children[1].toggle = false;
                    }
                    else {
                        (this).$children[index].$children[1].toggle =true;
                    }
                }

                //这里的重新初始化都是很有学问，computed选项中被直接赋值的项并不能在这里被赋值
                if(libId==31){
                    console.log(type);
                    if(type==0) {
                        (this).$children[index].$children[1].typeOfLib31a = 0;
                    }
                    else if(type==1){
                        (this).$children[index].$children[1].typeOfLib31a =1;
                    }else if(type==2){
                        (this).$children[index].$children[1].typeOfLib31a =2;
                    }
                    (this).$children[index].$children[1].showName=true;
                    (this).$children[index].$children[1].showSale=true;
                    (this).$children[index].$children[1].showPrice=true;
                }

                if(libId==32){
                    if(type==0) {
                        //不要问我为什么这里不是$children[1]，我也不知道，console出来的结果就是$children[2]才是edit-box
                        (this).$children[index].$children[2].typeOfLib32a = 0;
                    }
                    else if(type==1){
                        (this).$children[index].$children[2].typeOfLib32a =1;
                    }else if(type==2){
                        (this).$children[index].$children[2].typeOfLib32a =2;
                    }

                    (this).$children[index].$children[2].showName=true;
                    (this).$children[index].$children[2].showSale=true;
                    (this).$children[index].$children[2].showPrice=true;
                }

                //在视图层面更新组件（把显示出来的组件插入到count的后面）
                if(!lastAddBox) {
                    $('#count' + index).insertAfter($('#count' + count));
                    //重启tinymce实例
                    tinymce.execCommand('mceAddEditor', true, tinymceId);
                }
                else{
                    $('#count' + index).insertBefore($('#count' + count));
                    //重启tinymce实例
                    tinymce.execCommand('mceAddEditor', true, tinymceId);
                }

                //在vue实例层面更换两个组件

                //把隐藏给temp
                var temp = (this).$children[index];
                //把隐藏行删除
                (this).$children.splice(index, 1);

                if(!lastAddBox) {
                    //把temp插到count行后面
                    (this).$children.splice(count + 1, 0, temp);
                }else{
                    //把temp插到last edit box前面
                    (this).$children.splice(count, 0, temp);
                }

                //根据pageInfo的数据更新Vue组件
                for( i=0;i<(this).$children.length;i++){

                    (this).$children[i]['count']=pageInfo[i]['row']-1;
                    (this).$children[i]['row']=pageInfo[i]['row'];
                    (this).$children[i]['libTitle']=pageInfo[i]['lib_title'];
                    (this).$children[i]['libNum']=pageInfo[i]['lib_num'];
                    (this).$children[i]['libDetail']=pageInfo[i]['lib_detail'];

                    (this).$children[i]['type']=pageInfo[i]['type'];

                    (this).$children[i]['typeOfLib31a']=pageInfo[i]['type_of_lib31a'];
                    (this).$children[i]['typeOfLib31b']=pageInfo[i]['type_of_lib31b'];

                    (this).$children[i]['typeOfLib32a']=pageInfo[i]['type_of_lib32a'];
                    (this).$children[i]['typeOfLib32b']=pageInfo[i]['type_of_lib32b'];

                    (this).$children[i]['libText1']=pageInfo[i]['lib_text1'];
                    (this).$children[i]['libText2']=pageInfo[i]['lib_text2'];
                    (this).$children[i]['libText3']=pageInfo[i]['lib_text3'];
                    (this).$children[i]['libText4']=pageInfo[i]['lib_text4'];
                    (this).$children[i]['price1']=pageInfo[i]['price1'];
                    (this).$children[i]['price2']=pageInfo[i]['price2'];

                    for(var j=1;j<=4;j++) {
                        (this).$children[i]['imgUrl'+j] = pageInfo[i]['img_url'+j];
                        (this).$children[i]['itemUrl'+j]= pageInfo[i]['item_url'+j];
                    }

                    for(var j=1;j<=4;j++) {
                        (this).$children[i].imgs[j-1].imgUrl = pageInfo[i]['img_url'+j];
                        (this).$children[i].imgs[j-1].itemUrl= pageInfo[i]['item_url'+j];
                    }
                }
            }
        }
    });

    //下面这段代码一定要加上，保证Vue实例children数组的顺序与component的注册顺序（即pageInfo中row的顺序一致)
    //这是为了以防万一，暂时不知道为什么不这么写会出错，反正加上就没错
    root.$children.sort(function (x, y) {
        return (x['row'] - y['row']);
    });
    /*******************************************drug and drop************************************************/
    $(function() {
        // All necessary information can be found in the following website http://api.jqueryui.com/sortable/
        $( "#hook" ).sortable({
            cursor: "move",
            distance: 30,
            cursorAt: {top: 50, left: 200},
            items :"section:not(.unsortable)",
            opacity: 0.6,
            revert: true,
            revert: 200,
            containment: '#hook',
            helper: 'clone',
            appendTo: 'body',
            zIndex: 100,
            animation: 500,
            start: function (e, ui) {
                //开始移动前关闭所有ui的tinymce实例
                $(ui.item).find('textarea').each(function (){
                    tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
                });
            },

            stop: function (e, ui) {
                // 移动结束后重新打开所有ui的tinymce实例
                $(ui.item).find('textarea').each(function () {
                    tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
                });
            },

            //This event is triggered when the user stopped sorting and the DOM position has changed.
            update : function(event, ui){
                var array=$(this).sortable("toArray"),index;
                for (var i=0;i<visibilityNum();i++){
                    //用正则表达式取出拖拽后的组件的排序
                    index=parseInt(array[i].replace(/(count)/g, ''), 10);
                    //在pageInfo层面更新数据
                    pageInfo[index]['row']=i+1;
                    //vue实例层面更新数据
                    (root).$children[index]['count']=i;
                    (root).$children[index]['row']=i+1;
                }
                pageInfo.sort(function (x, y) {
                    return (x['row'] - y['row']);
                });
                (root).$children.sort(function(x,y){
                    return(x['row']-y['row']);
                });
            }
        });
    });

    /*************这个flag的作用是当按下“上移”和“下移”按钮时，屏蔽display-box的onclick效果***************/
    var flag=false;

    $('.move-up-btn').mousedown(function(){
        flag=true;
        $('.edit-box').hide();
    });

    $('.move-down-btn').mousedown(function(){
        flag=true;
        $('.edit-box').hide();
    });

    /****************************************对display-box 的处理****************************************/
    //首先隐藏所有auxiliary-area的内容
    $('.display-box').find('.auxiliary-area ').hide();

    //突出显示选中的display-box，打开edit-box
    $('.display-box').not('#last-add-box').on('click',function(){
        if(!flag){
            $('.edit-box').removeClass('class-invisible2');
            //显示当前组件对应的display-box中auxiliary-area的内容
            $('.display-box').find('.auxiliary-area ').hide();
            $(this).find('.auxiliary-area').show();
            //删除之前选中display-box时，通过js增加的样式=>   $(this).css("outline","0px");即取消之前的覆盖作用
            $('.display-box').removeAttr("style");
            //移除所有display-box的mouse-over-display-box样式
            $('.display-box').removeClass('mouse-over-display-box');
            //移除所有display-box的selected-box样式
            $('.selected-box').removeClass('selected-box');
            //为当前组件对应的display-box增加selected-box样式
            $(this).addClass('selected-box');
            //覆盖当前组件对应的display-box本身的mouse-over-display-box样式
            $(this).css("outline","0px");

            //打开edit-box;
            var thisEditBox= $(this).parents('section').find('.edit-box');
            //隐藏不是当前组件对应的edit-box
            $('.edit-box').not(thisEditBox).hide();

            //显示当前组件对应的edit-box
            thisEditBox.show();

            //隐藏add-box
            $('.add-box').hide();
        }
        else{
            flag=false;
        }

    });

    //这段功能是为了使drug and drop 不离开 #hook 而隐藏的
    $('.display-box').mousedown(function(){
        $('.auxiliary-area').hide();
        $('.edit-box').hide();
        $('.display-box').removeClass('selected-box');
    });

    //添加鼠标经过的动画
    $('.display-box').not('#last-add-box').hover(function(){
        $('.display-box').removeClass('mouse-over-display-box');
        $('.lib0').removeClass('mouse-over-display-box');
        $(this).addClass('mouse-over-display-box');
    });

    $('.lib0').hover(function(){
        $('.display-box').removeClass('mouse-over-display-box');
        $(this).addClass('mouse-over-display-box');
    });

    /********************************************对add-box的处理******************************************/
    //展开add-box 和突出当前选中的display-box
    $('.add-lib-btn').on('click',function (event) {
        event.stopPropagation();

        $('.edit-box').hide();
        $(this).parents('section').find('.add-box').show();

        //突出当前选中的display-box
        $('.selected-box').removeClass('selected-box');
        $(this).parents('section').find('.display-box').addClass('selected-box');
    });

    //按下关闭按钮，隐藏add-box
    $('.cancel-add-box').on('click',function (event) {
        event.stopPropagation();
        $('.add-box').hide();
    });

    //选中img-item，隐藏add-box
    $('.img-item').on('click',function (event) {
        event.stopPropagation();
        $('.add-box').hide();
    });

    //把所有add-box中所有不是第一个img-group的元素都隐藏起来,一下三种方法都可以实现功能
    //$('.add-box').find(".img-group:not(:first)").hide();
    //$('.add-box').find(".img-group:not(:eq(0))").hide();
    $('.add-box').find(".img-group:gt(0)").hide();

    /**************************************************对edit-box的处理*************************************/
    $('.cancel-edit-box').on('click',function () {
        $('.edit-box').hide();
    });

    /************************************************对最后一个特殊add-box的处理****************************/
    $("#last-add-box").on('click',function (event) {
        event.stopPropagation();
        //移除所有display-box的mouse-over-display-box样式
        //移除所有display-box的selected-box样式
        $('.selected-box').removeClass('selected-box');
        //隐藏所有的edt-box;
        $('.edit-box').hide();
        //隐藏所有的auxiliary-area;
        $('.auxiliary-area').hide();
        //展开add-box
        $(this).parents('section').find('.add-box').show();
    });

    /************************************************对footer的处理*****************************************/
    $('.save-page').on('click',function(){
        //保证保存到数据库的imgUrl 一定是相对路径
        for(let i=0;i<pageInfo.length;i++){
            pageInfo[i]['img_url1']= pageInfo[i]['img_url1'].replace(projectPath, "");
            pageInfo[i]['img_url2']= pageInfo[i]['img_url2'].replace(projectPath, "");
            pageInfo[i]['img_url3']= pageInfo[i]['img_url3'].replace(projectPath, "");
            pageInfo[i]['img_url4']= pageInfo[i]['img_url4'].replace(projectPath, "");
        }
        post_ajax(pageInfo);
    });

    $('.preview-page').on('click',function(){
        window.open('yikaimobile.php?dwt_type=index&dwt_id=2');

    });

    /************************************以下代码是此js页面的公用函数**************************************/
    //向后台发送数据
    function post_ajax(pageInfo) {
        //.post(url,parameters,callback)
        //url         (字符串)服务器端资源地址。
        //parameter   (对象)需要传递到服务器端的参数。 参数形式为“键/值”。
        //callback    (函数)在请求完成时被调用。该函数参数依次为响应体和状态。
        $.post('update_info.php', {
            page_info:pageInfo
        }, function (result) {
            if (result) {
                swal({
                    title: "",
                    text: "页面保存成功成功",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                });
                for(let i=0;i<pageInfo.length;i++){
                    pageInfo[i]['img_url1']=projectPath+pageInfo[i]['img_url1'];
                    pageInfo[i]['img_url2']=projectPath+pageInfo[i]['img_url2'];
                    pageInfo[i]['img_url3']=projectPath+pageInfo[i]['img_url3'];
                    pageInfo[i]['img_url4']=projectPath+pageInfo[i]['img_url4'];
                }
            }
        })
    }

    //count确定究竟是第几个组件按下了add按钮,此函数用于更新pageInfo层面的数据
    function addLibHelper(lastAddBox,count,libId,info){

        //取到此类lib可以用的最大数量和已经使用的数量
        var maxLibNum,currentLibNum=0;

        //取得maxLibNum的值
        for(var i=0;i<pageInfo.length;i++){
            if(pageInfo[i]['lib_id']==libId){
                maxLibNum=pageInfo[i]['lib_num_max'];
                //  currentLibNum=pageInfo[index]['lib_num'];
                //只要找到一个组件即可退出
                break;
            }
        }

        //为了以防万一，currentLibNum再数一次(启动容错机制)
        for(i=0;i<pageInfo.length;i++){
            if(pageInfo[i]['lib_id']==libId &&  pageInfo[i]['visibility']==1 ){
                currentLibNum++;
            }
        }

        if(currentLibNum<maxLibNum) {
            var index = visibilityNum(pageInfo);
            for (index; index < pageInfo.length; index++) {
                //找到的第index行要插到第count行后面
                if (pageInfo[index]['lib_id'] == libId) {

                    ///////////////////////////1 对数据重新进行初始化，对不同组件重新初始化的方式不同/////////////////
                    pageInfo[index]['visibility'] = 1;
                    pageInfo[index]['lib_title'] = info;

                    if(libId==23){
                        pageInfo[index]['lib_text1']="这是一篇公众号文章";
                        pageInfo[index]['lib_text2']="2016-07-29";
                        pageInfo[index]['lib_text3']="作者";
                        pageInfo[index]['lib_text4']="";
                    }
                    //若是文字模块，则加入初始化文本信息
                    if(libId==24){
                        pageInfo[index]['lib_detail']="<p><span style='color:#D6D6D6'>文本模块支持文字，图片，视频，表格的简单排版，你甚至可以在工具->源代码的选项中直接用HTML编写此模块</span></p>";
                    }

                    //若是单列大图模块，则初始化大图数目为1
                    if(libId==25){
                        pageInfo[index]['lib_title'] = "单列大图";
                        pageInfo[index]['lib_detail']=1;
                    }


                    //若是双列大图模块，则初始化大图数目为1
                    if(libId==26){
                        pageInfo[index]['lib_title'] = "双列大图";
                        pageInfo[index]['lib_detail']=1;
                    }

                    //若是轮播
                    if(libId==29){
                        pageInfo[index]['lib_detail']=4;
                    }

                    //商品模块双列
                    if(libId==32){
                        pageInfo[index]['lib_text1']=0;
                        pageInfo[index]['lib_text2']="商品名称";
                        pageInfo[index]['lib_text3']=0;
                        pageInfo[index]['lib_text4']="商品名称";
                        pageInfo[index]['price1']=0.00;
                        pageInfo[index]['price2']=0.00;
                    }

                    //默认图片数据
                    for(i=1;i<=4;i++) {
                        pageInfo[index]['img_url'+i] = "/<?php echo $this->_var['project_path']; ?>/images/preview/dcr-default-bg5.jpg";
                        pageInfo[index]['item_url'+i]='';
                    }

                    ////////////////////2 将组件插入到对应的行中(下面这段代码不要修改)/////////////////////////////

                    //把隐藏行给temp
                    var temp = pageInfo[index];

                    //把隐藏行删除
                    pageInfo.splice(index, 1);

                    if(!lastAddBox){
                        //把temp插到count行后面
                        pageInfo.splice(count + 1, 0, temp);
                    }else {
                        //把temp插到last-add-box前面
                        pageInfo.splice(count, 0, temp);
                    }

                    //对lib_num进行更新
                    for(index=0;index<pageInfo.length;index++){
                        if(pageInfo[index]['lib_id']==libId)
                        {
                            pageInfo[index]['lib_num'] = currentLibNum+1;
                        }
                    }

                    //根据pageInfo 的结构更新其row 值
                    for (var j = 0; j < pageInfo.length; j++) {
                        pageInfo[j]['row'] = j + 1;
                    }

                    //对pageInfo重新排序
                    pageInfo.sort(function (x, y) {
                        return (x['row'] - y['row']);
                    });

                    //找到一个就足够了
                    break;
                }
            }
            return true
        }

        else{
            swal({
                title: "提示",
                text:"抱歉，此类组件已经用完",
                showConfirmButton: true
            });
            return false
        }
    }

    //切换add-box页面
    //para : ref 用于确定哪个<li>标签被选中
    //para : index 用于确定要展示到那个页面
    function switchPage(ref,index){

        $(ref).parents(".add-box").find(".img-group").not(":eq(index)").hide();
        $(ref).parents(".add-box").find(".img-group").eq(index).show();

        //删除hr的初始化样式
        $(ref).parents(".add-box").find(".img-group").find('hr').removeClass();
        //根据index动态改变hr的位置
        $(ref).parents(".add-box").find(".img-group").find('hr').css({
            'position': 'absolute',
            'top': '55px',
            'height': '4px',
            'border': 'none',
            'color': '#333',
            'background-color': '#333',
            'width': '60px',
            left: function( index ) {
                return parseFloat( 20+index*85 );
            }
        });

        //判断是否为“商品推广”大类
        $(ref).parents(".add-box").find(".img-group").removeClass('group3');
        if(index==2){
            $(ref).parents(".add-box").find(".img-group").addClass('group3');
        }
    }

    //计算pageInfo可见组件个数
    function visibilityNum(){

        var result=0;

        for (var i=0;i<pageInfo.length;i++){
            if(pageInfo[i]['visibility']=='1'){
                result++;
            }
        }

        return result-1;
    }

    //将templateInfo转变成需要的形式
    function transformer(templateInfo) {

        var libTypeId=[],libTypeName=[];

        for(var i=0;i<templateInfo.length;i++){
            libTypeId.push(parseInt(templateInfo[i]['lib_type_id']));
        }

        //取得一级分类的id
        var a = [], b = [], prev;
        libTypeId.sort();
        for (  i = 0; i < libTypeId.length; i++ ) {
            if ( libTypeId[i] !== prev ) {
                a.push(libTypeId[i]);
                b.push(1);
            } else {
                b[b.length-1]++;
            }
            prev = libTypeId[i];
        }

        //取得一级分类各个id对应的名称
        var j = 0;
        for (  i = 0; i < templateInfo.length; i++ ) {
            if(templateInfo[i]['lib_type_id']==a[j]&&j<a.length){
                libTypeName.push(templateInfo[i]['lib_type_name']);
                j++;
            }
        }

        //取得二级分类各自对应信息
        var secondCategory=[];

        for( i = 0 ;i < a.length; i++ ){
            secondCategory.push([]);
        }

        i=0;
        for(j= 0;j<a.length;j++){
            for (  i ; i < templateInfo.length&&a[j]==templateInfo[i]['lib_type_id']; i++ ) {
                secondCategory[j].push([templateInfo[i]['lib_id'],templateInfo[i]['lib_name'],templateInfo[i]['lib_title'],`${projectPath}${templateInfo[i]['lib_preview_img']}`]);
            }
        }

        //return [a,b,libTypeName,secondCategory];
        var array=[];
        for(i=0;i<a.length;i++){
            array.push([a[i],libTypeName[i],secondCategory[i]]);
        }

        return array
    }


</script>

<script src="/<?php echo $this->_var['project_path']; ?>/js/bootstrap.min.js"></script>
<!------下面是sweetalert的js--------->
<script src="/<?php echo $this->_var['project_path']; ?>/js/sweetalert-dev.js"></script>

<!------下面是jquery拖动api的js----->
<script src="/<?php echo $this->_var['project_path']; ?>/js/jquery-ui.js"></script>
<script src="/<?php echo $this->_var['project_path']; ?>/js/jquery.ui.sortable-animation.js"></script>

<!------下面是tinymce的js----->
<script src='/<?php echo $this->_var['project_path']; ?>/js/tinymce_4.4.1/tinymce/js/tinymce/tinymce.min.js'></script>
<script src='/<?php echo $this->_var['project_path']; ?>/js/zh_CN.js'></script>
<script>
    //有什么不懂请浏览以下的documentation  https://www.tinymce.com/
    tinymce.init({
        selector: '.tinyMCE',
        height : "250",
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image| print preview media | forecolor backcolor emoticons | imageupload',
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: 'Test 1'},
            {title: 'Test template 2', content: 'Test 2'}
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],

        //the following code is from stackouveflow for uploading image from local.
        //http://stackoverflow.com/questions/3872009/upload-image-from-local-into-tinymce
        // Don't ask me why or how does it work. I just copy and paste it. The comments that I wrote were my assumptions
        setup: function(editor) {

            //1. 首先自己造一个上传文件（最终目的是上传本地图片）的按钮
            var inp = $('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');

            //2. 将此按钮插append到editor里面（此时html部分的工作已经完成）
            $(editor.getElement()).parent().append(inp);

            //3. 为此input添加事件
            inp.on("change",function(){

                //此时的input就是一开始inp那个$符号里面的input了
                var input = inp.get(0);
                //读取input的第一个文件，files是input的一个属性
                var file = input.files[0];
                //FileReader is used to read the contents of a Blob or File.
                var fr = new FileReader();

                fr.onload = function() {
                    //新建一个Image对象 Returns an HTMLImageElement instance just as document.createElement('img') would.
                    //此时的img 同时是一个html <img> 又是一个object
                    var img = new Image();
                    //fr.result其实就是一个Big Large Object(BLOB)
                    img.src = fr.result;
                    //将图片插入到编辑器的content中
                    editor.insertContent('<img src="'+img.src+'"/>');
                    //将inp的值重置为空
                    inp.val('');
                };

                //The readAsDataURL method is used to read the contents of the specified Blob or File.
                // When the read operation is finished, the readyState becomes DONE, and the loadend is triggered.
                // At that time, the result attribute contains  the data as a URL representing the file's data as a base64 encoded string.
                fr.readAsDataURL(file);
            });

            //4. 自定义一个按钮
            editor.addButton( 'imageupload', {
                text:"本地图片",
                icon: false,
                onclick: function(e) {
                    //将这个自定义的按钮绑定到input中
                    inp.trigger('click');
                }
            });
        }
    })
</script>

<!------下面是百度地图api的js需要使用时只需消除注释即可----->
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=YWdGplhYjUGQ3GtpKNeuTM2S"></script>
<script type="text/javascript">

    var map = new BMap.Map("container");
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

</script>

<!--轮播-->
<script>
    jQuery(document).ready(function($) {
        $('.my-slider').unslider({
            autoplay:true,
            infinite: true,
            arrows: false
        });
    });
</script>
</body>
</html>

<!----实在搞不定加我微信sl930223 或 Gmail: shenlin192@gmail.com---->
