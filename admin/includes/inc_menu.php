<?php

/**
 * ykshop 管理中心菜单数组
 * ============================================================================
 * * 版权所有 2015-2025 杭州一开网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.yikaiqiche.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: $
 * $Id: $.php  2015-02-07 06:29:08Z $
*/

if (!defined('IN_YKE'))
{
    die('Hacking attempt');
}

$modules['02_cat_and_goods']['01_goods_list']       = 'goods.php?sysessid='.$GLOBALS['sysessid'].'&act=list';         // 商品列表
$modules['02_cat_and_goods']['02_goods_add']        = 'goods.php?sysessid='.$GLOBALS['sysessid'].'&act=add';          // 添加商品
$modules['02_cat_and_goods']['03_category_list']    = 'category.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['02_cat_and_goods']['05_comment_manage']   = 'comment_manage.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['02_cat_and_goods']['06_goods_brand_list'] = 'brand.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['02_cat_and_goods']['08_goods_type']       = 'goods_type.php?sysessid='.$GLOBALS['sysessid'].'&act=manage';
$modules['02_cat_and_goods']['11_goods_trash']      = 'goods.php?sysessid='.$GLOBALS['sysessid'].'&act=trash';        // 商品回收站
$modules['02_cat_and_goods']['12_batch_pic']        = 'picture_batch.php';
$modules['02_cat_and_goods']['13_batch_add']        = 'goods_batch.php?sysessid='.$GLOBALS['sysessid'].'&act=add';    // 商品批量上传
$modules['02_cat_and_goods']['14_goods_export']     = 'goods_export.php?sysessid='.$GLOBALS['sysessid'].'&act=goods_export';
$modules['02_cat_and_goods']['15_batch_edit']       = 'goods_batch.php?sysessid='.$GLOBALS['sysessid'].'&act=select'; // 商品批量修改
$modules['02_cat_and_goods']['16_goods_script']     = 'gen_goods_script.php?sysessid='.$GLOBALS['sysessid'].'&act=setup';
$modules['02_cat_and_goods']['17_tag_manage']       = 'tag_manage.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['02_cat_and_goods']['50_virtual_card_list']   = 'goods.php?sysessid='.$GLOBALS['sysessid'].'&act=list&extension_code=virtual_card';
$modules['02_cat_and_goods']['51_virtual_card_add']    = 'goods.php?sysessid='.$GLOBALS['sysessid'].'&act=add&extension_code=virtual_card';
$modules['02_cat_and_goods']['52_virtual_card_change'] = 'virtual_card.php?sysessid='.$GLOBALS['sysessid'].'&act=change';
$modules['02_cat_and_goods']['goods_auto']             = 'goods_auto.php?sysessid='.$GLOBALS['sysessid'].'&act=list';


$modules['03_promotion']['02_snatch_list']          = 'snatch.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['04_bonustype_list']       = 'bonus.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['06_pack_list']            = 'pack.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['07_card_list']            = 'card.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['08_group_buy']            = 'group_buy.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['09_topic']                = 'topic.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['10_auction']              = 'auction.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['12_favourable']           = 'favourable.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['13_wholesale']            = 'wholesale.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['14_package_list']         = 'package.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
//$modules['03_promotion']['ebao_commend']            = 'ebao_commend.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['03_promotion']['15_exchange_goods']       = 'exchange_goods.php?sysessid='.$GLOBALS['sysessid'].'&act=list';


$modules['04_order']['02_order_list']               = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['04_order']['03_order_query']              = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=order_query';
$modules['04_order']['04_merge_order']              = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=merge';
$modules['04_order']['05_edit_order_print']         = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=templates';
$modules['04_order']['06_undispose_booking']        = 'goods_booking.php?sysessid='.$GLOBALS['sysessid'].'&act=list_all';
//$modules['04_order']['07_repay_application']        = 'repay.php?sysessid='.$GLOBALS['sysessid'].'&act=list_all';
$modules['04_order']['08_add_order']                = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=add';
$modules['04_order']['09_delivery_order']           = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=delivery_list';
$modules['04_order']['10_back_order']               = 'order.php?sysessid='.$GLOBALS['sysessid'].'&act=back_list';

$modules['05_banner']['ad_position']                = 'ad_position.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['05_banner']['ad_list']                    = 'adsnews.php?sysessid='.$GLOBALS['sysessid'].'&act=list';

$modules['06_stats']['flow_stats']                  = 'flow_stats.php?sysessid='.$GLOBALS['sysessid'].'&act=view';
$modules['06_stats']['searchengine_stats']          = 'searchengine_stats.php?sysessid='.$GLOBALS['sysessid'].'&act=view';
$modules['06_stats']['z_clicks_stats']              = 'adsense.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['06_stats']['report_guest']                = 'guest_stats.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['06_stats']['report_order']                = 'order_stats.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['06_stats']['report_sell']                 = 'sale_general.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['06_stats']['sale_list']                   = 'sale_list.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['06_stats']['sell_stats']                  = 'sale_order.php?sysessid='.$GLOBALS['sysessid'].'&act=goods_num';
$modules['06_stats']['report_users']                = 'users_order.php?sysessid='.$GLOBALS['sysessid'].'&act=order_num';
$modules['06_stats']['visit_buy_per']               = 'visit_sold.php?sysessid='.$GLOBALS['sysessid'].'&act=list';

$modules['07_content']['03_article_list']           = 'article.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['07_content']['02_articlecat_list']        = 'articlecat.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['07_content']['vote_list']                 = 'vote.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['07_content']['article_auto']              = 'article_auto.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
//$modules['07_content']['shop_help']                 = 'shophelp.php?sysessid='.$GLOBALS['sysessid'].'&act=list_cat';
//$modules['07_content']['shop_info']                 = 'shopinfo.php?sysessid='.$GLOBALS['sysessid'].'&act=list';


$modules['08_members']['03_users_list']             = 'users.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['08_members']['04_users_add']              = 'users.php?sysessid='.$GLOBALS['sysessid'].'&act=add';
$modules['08_members']['05_user_rank_list']         = 'user_rank.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['08_members']['06_list_integrate']         = 'integrate.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['08_members']['08_unreply_msg']            = 'user_msg.php?sysessid='.$GLOBALS['sysessid'].'&act=list_all';
$modules['08_members']['09_user_account']           = 'user_account.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['08_members']['10_user_account_manage']    = 'user_account_manage.php?sysessid='.$GLOBALS['sysessid'].'&act=list';

$modules['10_priv_admin']['admin_logs']             = 'admin_logs.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['10_priv_admin']['admin_list']             = 'privilege.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['10_priv_admin']['admin_role']             = 'role.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['10_priv_admin']['agency_list']            = 'agency.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['10_priv_admin']['suppliers_list']         = 'suppliers.php?sysessid='.$GLOBALS['sysessid'].'&act=list'; // 供货商

$modules['11_system']['01_shop_config']             = 'shop_config.php?sysessid='.$GLOBALS['sysessid'].'&act=list_edit';
$modules['11_system']['shop_authorized']             = 'license.php?sysessid='.$GLOBALS['sysessid'].'&act=list_edit';
$modules['11_system']['02_payment_list']            = 'payment.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['03_shipping_list']           = 'shipping.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['04_mail_settings']           = 'shop_config.php?sysessid='.$GLOBALS['sysessid'].'&act=mail_settings';
$modules['11_system']['05_area_list']               = 'area_manage.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
//$modules['11_system']['06_plugins']                 = 'plugins.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['07_cron_schcron']            = 'cron.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['08_friendlink_list']         = 'friend_link.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['sitemap']                    = 'sitemap.php';
$modules['11_system']['check_file_priv']            = 'check_file_priv.php?sysessid='.$GLOBALS['sysessid'].'&act=check';
$modules['11_system']['captcha_manage']             = 'captcha_manage.php?sysessid='.$GLOBALS['sysessid'].'&act=main';
$modules['11_system']['ucenter_setup']              = 'integrate.php?sysessid='.$GLOBALS['sysessid'].'&act=setup&code=ucenter';
$modules['11_system']['flashplay']                  = 'flashplay.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['navigator']                  = 'navigator.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['file_check']                 = 'filecheck.php';
//$modules['11_system']['fckfile_manage']             = 'fckfile_manage.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['021_reg_fields']             = 'reg_fields.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['11_system']['franchisee_list']		= 'franchisee.php?sysessid='.$GLOBALS['sysessid'].'&act=list';


$modules['12_template']['02_template_select']       = 'template.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['12_template']['03_template_setup']        = 'template.php?sysessid='.$GLOBALS['sysessid'].'&act=setup';
$modules['12_template']['04_template_library']      = 'template.php?sysessid='.$GLOBALS['sysessid'].'&act=library';
$modules['12_template']['05_edit_languages']        = 'edit_languages.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['12_template']['06_template_backup']       = 'template.php?sysessid='.$GLOBALS['sysessid'].'&act=backup_setting';
$modules['12_template']['mail_template_manage']     = 'mail_template.php?sysessid='.$GLOBALS['sysessid'].'&act=list';


$modules['13_backup']['02_db_manage']               = 'database.php?sysessid='.$GLOBALS['sysessid'].'&act=backup';
$modules['13_backup']['03_db_optimize']             = 'database.php?sysessid='.$GLOBALS['sysessid'].'&act=optimize';
$modules['13_backup']['04_sql_query']               = 'sql.php?sysessid='.$GLOBALS['sysessid'].'&act=main';
$modules['13_backup']['convert']                    = 'convert.php?sysessid='.$GLOBALS['sysessid'].'&act=main';

$modules['14_sms']['03_sms_send']                   = 'sms.php?sysessid='.$GLOBALS['sysessid'].'&act=display_send_ui';
$modules['14_sms']['04_sms_sign']                   = 'sms.php?sysessid='.$GLOBALS['sysessid'].'&act=sms_sign';


$modules['15_rec']['affiliate']                     = 'affiliate.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['15_rec']['affiliate_ck']                  = 'affiliate_ck.php?sysessid='.$GLOBALS['sysessid'].'&act=list';

$modules['16_email_manage']['email_list']           = 'email_list.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['16_email_manage']['magazine_list']        = 'magazine_list.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['16_email_manage']['attention_list']       = 'attention_list.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
$modules['16_email_manage']['view_sendlist']        = 'view_sendlist.php?sysessid='.$GLOBALS['sysessid'].'&act=list';
?>
