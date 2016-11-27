<?php

//    $('.firstTierCategory').on('click',function(){
//        clicked('#secondTierMenu',this,'secondTierDiv','secondTierContent',1);

//        //clicked('#secondTierMenu',this);
////        console.log(this);
//        //一级菜单中点了谁就显示谁的二级菜单
//        let r = /\d+/;
////        let componentTypeId=r.exec($(this).attr('id'))[0];
//        let componentId=r.exec($(this).attr('id'))[0];
////        let secondTierCategory=componentInfo.filter((item)=>item['componentTypeId']==componentTypeId&&item['componentIdfa']!=0);
//        console.log(componentId);
//        let secondTierCategory=componentInfo.filter((item)=>item['componentIdfa']==componentId);
////        console.log(secondTierCategory);
//        //在dom 层面显示二级信息
//        deletrius();
//        let addBox=$('#secondTierMenu');
//        addBox.show();
//        addBox.find('.secondTierDiv p').empty().append(`增加${secondTierCategory[0]['componentTypeName']}`);
//        addBox.find('.secondTierContent').empty();
//        for(let i=0;i<secondTierCategory.length;i++){
//            addBox.find('.secondTierContent').append(`<div class="secondTierCategory"  id="component${secondTierCategory[i]['componentId']}" onclick="clicked('#thirdTierMenu',this,'thirdTierDiv','thirdTierContent',2) ">${secondTierCategory[i]['componentName']}</div>`)
////         addBox.find('.secondTierContent').append(`<div id="component${secondTierCategory[i]['componentId']}" class="component type${secondTierCategory[i]['componentId']}">${secondTierCategory[i]['content']}</div>`)
//
//        }
//
//        let subcomponentId = "component" + secondTierCategory[0]['componentId'];
////            console.log(subcomponentId);
////        if(i==0){
//            clicked('#thirdTierMenu',$('#' +subcomponentId),'thirdTierDiv','thirdTierContent',2);
////        }
////
////        $('.component').draggable({
////            helper:'clone',
////            start:function(event,ui){
////                console.log($(ui.helper['context']).attr('id'));
////                //record all necessary information of the element being dragged so that we can use them when it's dropped
////                $(event.target).removeData().data({
////                    boxX:event.offsetX,
////                    boxY:event.offsetY,
////                    width:$(this).width(),
////                    height:$(this).height()
////                });
////            }
////        });
//    });
?>