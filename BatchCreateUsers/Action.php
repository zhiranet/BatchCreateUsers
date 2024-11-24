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

class BatchCreateUsers_Action extends Typecho_Widget implements Widget_Interface_Do
{
    private function generateRandomString($length, $type) {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        
        switch ($type) {
            case 'letters':
                $characters = $letters;
                break;
            case 'numbers':
                $characters = $numbers;
                break;
            case 'mixed':
                $characters = $letters . $numbers;
                break;
            default:
                $characters = $letters;
        }
        
        $randomString = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $max)];
        }
        
        return $randomString;
    }
    
    private function generatePassword($type, $length, $fixedPassword = '') {
        if ($type === 'fixed') {
            return $fixedPassword;
        }
        
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        
        switch ($type) {
            case 'letters':
                $chars = $letters;
                break;
            case 'numbers':
                $chars = $numbers;
                break;
            case 'mixed':
                $chars = $letters . $numbers;
                break;
            default:
                $chars = $letters . $numbers;
        }
        
        $password = '';
        $max = strlen($chars) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, $max)];
        }
        
        // 如果是混合模式，确保至少包含一个数字和一个字母
        if ($type === 'mixed') {
            if (!preg_match('/[0-9]/', $password)) {
                $pos = rand(0, $length - 1);
                $password[$pos] = $numbers[rand(0, strlen($numbers) - 1)];
            }
            if (!preg_match('/[a-zA-Z]/', $password)) {
                $pos = rand(0, $length - 1);
                $password[$pos] = $letters[rand(0, strlen($letters) - 1)];
            }
        }
        
        return $password;
    }
    
    public function action() {
        // 检查用户权限
        if (!$this->widget('Widget_User')->hasLogin()) {
            $this->response->setStatus(403);
            $this->response->throwJson(array('error' => '未登录'));
        }
        
        // 获取POST数据
        $request = file_get_contents('php://input');
        $data = json_decode($request, true);
        
        $prefix = isset($data['prefix']) ? $data['prefix'] : '';
        $count = intval($data['count']);
        $nameLength = intval($data['nameLength']);
        $nameType = $data['nameType'];
        $passwordType = $data['passwordType'];
        $passwordLength = isset($data['passwordLength']) ? intval($data['passwordLength']) : 8;
        // 添加密码长度验证
        if ($passwordLength > 32) {
            $passwordLength = 32;
        } elseif ($passwordLength < 6) {
            $passwordLength = 6;
        }
        $fixedPassword = isset($data['password']) ? $data['password'] : '';
        $group = $data['group'];
        
        $results = array();
        $db = Typecho_Db::get();
        
        for ($i = 0; $i < $count; $i++) {
            try {
                // 生成用户名
                $username = $prefix . $this->generateRandomString($nameLength, $nameType);
                
                // 生成密码
                $password = $this->generatePassword($passwordType, $passwordLength, $fixedPassword);
                
                // 创建用户
                $hasher = new PasswordHash(8, true);
                $hashPassword = $hasher->HashPassword($password);
                
                $db->query($db->insert('table.users')->rows(array(
                    'name' => $username,
                    'password' => $hashPassword,
                    'mail' => $username . '@example.com', // 临时邮箱
                    'url' => '',
                    'screenName' => $username,
                    'group' => $group,
                    'created' => time()
                )));
                
                $results[] = array(
                    'username' => $username,
                    'password' => $password,
                    'status' => 'success'
                );
                
            } catch (Exception $e) {
                $results[] = array(
                    'username' => $username,
                    'password' => '',
                    'status' => 'failed'
                );
            }
        }
        
        $this->response->throwJson(array('results' => $results));
    }
} 