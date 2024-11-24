<?php
/**
 * 批量创建用户
 * 
 * @package BatchCreateUsers
 * @author 栀染
 * @version 1.0
 * @link http://www.zhiranet.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class BatchCreateUsers_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法
     */
    public static function activate()
    {
        Helper::addPanel(1, 'BatchCreateUsers/panel.php', '批量创建用户', '批量创建用户', 'administrator');
        Helper::addAction('users-batch-create', 'BatchCreateUsers_Action');
        return '插件已经激活，<a href="' . Helper::options()->adminUrl . 'extending.php?panel=BatchCreateUsers/panel.php">点击这里</a>开始使用';
    }
    
    /**
     * 禁用插件方法
     */
    public static function deactivate()
    {
        Helper::removePanel(1, 'BatchCreateUsers/panel.php');
        Helper::removeAction('users-batch-create');
    }
    
    /**
     * 获取插件配置面板
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 创建一个隐藏的表单元素，用于保存配置
        $form->addInput(new Typecho_Widget_Helper_Form_Element_Hidden('dummy'));
        
        // 添加跳转按钮
        $button = new Typecho_Widget_Helper_Form_Element_Submit(
            'submit',
            null,
            '进入批量创建用户页面',
            '点击进入批量创建用户页面'
        );
        $form->addItem($button);
        
        // 添加跳转脚本
        ?>
        <script>
            window.location.href = '<?php echo Helper::options()->adminUrl; ?>extending.php?panel=BatchCreateUsers/panel.php';
        </script>
        <?php
    }
    
    /**
     * 个人用户的配置面板
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
} 


