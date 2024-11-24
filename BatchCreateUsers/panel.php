<?php
include 'header.php';
include 'menu.php';
?>

<div class="main">
    <div class="body container">
        <div class="typecho-page-title">
            <h2>批量创建用户</h2>
        </div>
        <div class="row typecho-page-main" role="main">
            <div class="col-mb-12">
                <div class="el-card">
                    <div id="batch-users-app">
                        <el-form :model="formData" :rules="rules" ref="batchForm" label-width="120px">
                            <el-form-item label="用户名前缀" prop="prefix">
                                <el-input v-model="formData.prefix" placeholder="请输入用户名前缀（可选）"></el-input>
                                <span class="el-form-item__description">选填，如不填写将生成纯随机英文用户名</span>
                            </el-form-item>
                            
                            <el-form-item label="创建数量" prop="count">
                                <el-input-number v-model="formData.count" :min="1" :max="100"></el-input-number>
                            </el-form-item>
                            
                            <el-form-item label="用户名长度" prop="nameLength">
                                <el-input-number v-model="formData.nameLength" :min="4" :max="16" :step="1"></el-input-number>
                                <span class="el-form-item__description">随机英文字符的长度</span>
                            </el-form-item>
                            
                            <el-form-item label="用户名类型" prop="nameType">
                                <el-radio-group v-model="formData.nameType">
                                    <el-radio label="letters">纯英文</el-radio>
                                    <el-radio label="numbers">纯数字</el-radio>
                                    <el-radio label="mixed">英文+数字</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            
                            <el-form-item label="密码生成方式" prop="passwordType">
                                <el-radio-group v-model="formData.passwordType">
                                    <el-radio label="fixed">指定密码</el-radio>
                                    <el-radio label="letters">随机英文</el-radio>
                                    <el-radio label="numbers">随机数字</el-radio>
                                    <el-radio label="mixed">英文+数字</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            
                            <el-form-item label="密码" prop="password" v-if="formData.passwordType === 'fixed'">
                                <el-input v-model="formData.password" type="password" show-password></el-input>
                            </el-form-item>
                            
                            <el-form-item label="密码长度" prop="passwordLength" v-if="formData.passwordType !== 'fixed'">
                                <el-input-number v-model="formData.passwordLength" :min="6" :max="32" :step="1"></el-input-number>
                                <span class="el-form-item__description">{{passwordLengthTip}}</span>
                            </el-form-item>
                            
                            <el-form-item label="用户组" prop="group">
                                <el-select v-model="formData.group">
                                    <el-option label="订阅者" value="subscriber"></el-option>
                                    <el-option label="投稿者" value="contributor"></el-option>
                                    <el-option label="编辑" value="editor"></el-option>
                                    <el-option label="管理员" value="administrator"></el-option>
                                </el-select>
                            </el-form-item>
                            
                            <el-form-item>
                                <el-button type="primary" @click="submitForm" :loading="loading">开始创建</el-button>
                                <el-button @click="resetForm">重置</el-button>
                            </el-form-item>
                        </el-form>

                        <!-- 结果展示 -->
                        <div v-if="results.length" class="result-section">
                            <el-divider>创建结果</el-divider>
                            <el-table :data="results" style="width: 100%">
                                <el-table-column prop="username" label="用户名"></el-table-column>
                                <el-table-column prop="password" label="密码"></el-table-column>
                                <el-table-column prop="status" label="状态">
                                    <template slot-scope="scope">
                                        <el-tag :type="scope.row.status === 'success' ? 'success' : 'danger'">
                                            {{scope.row.status === 'success' ? '成功' : '失败'}}
                                        </el-tag>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <div class="export-section">
                                <el-button type="success" @click="exportResults">导出结果</el-button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 添加版权信息 -->
                    <div class="copyright-info">
                        <el-divider></el-divider>
                        <p>BatchCreateUsers v1.0 © 2024 <a href="http://www.zhiranet.cn" target="_blank">栀染博客-www.zhiranet.cn</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 引入 Element UI 的 CSS 和 JS -->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<script src="https://unpkg.com/vue@2.6.14/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>

<style>
.el-card {
    margin: 20px;
    padding: 20px;
}
.result-section {
    margin-top: 30px;
}
.export-section {
    margin-top: 20px;
    text-align: right;
}
.el-form {
    max-width: 600px;
    margin: 0 auto;
}
.el-form-item__description {
    font-size: 12px;
    color: #909399;
    line-height: 1.4;
    padding-top: 4px;
    display: block;
}
.copyright-info {
    text-align: center;
    margin-top: 30px;
    color: #909399;
    font-size: 12px;
}
.copyright-info a {
    color: #409EFF;
    text-decoration: none;
}
.copyright-info a:hover {
    color: #66b1ff;
}
</style>

<script>
new Vue({
    el: '#batch-users-app',
    data() {
        return {
            loading: false,
            formData: {
                prefix: '',
                count: 1,
                nameLength: 8,
                nameType: 'letters',
                passwordType: 'fixed',
                password: '',
                passwordLength: 8,
                group: 'subscriber'
            },
            rules: {
                prefix: [
                    { max: 20, message: '前缀长度不能超过20个字符', trigger: 'blur' }
                ],
                nameLength: [
                    { required: true, message: '请设置用户名长度', trigger: 'blur' }
                ],
                nameType: [
                    { required: true, message: '请选择用户名生成类型', trigger: 'change' }
                ],
                password: [
                    { 
                        required: true, 
                        message: '请输入密码', 
                        trigger: 'blur',
                        validator: (rule, value, callback) => {
                            if (this.formData.passwordType === 'fixed' && !value) {
                                callback(new Error('请输入密码'));
                            } else {
                                callback();
                            }
                        }
                    },
                    { 
                        min: 6, 
                        message: '密码长度至少 6 个字符', 
                        trigger: 'blur',
                        validator: (rule, value, callback) => {
                            if (this.formData.passwordType === 'fixed' && value.length < 6) {
                                callback(new Error('密码长度至少 6 个字符'));
                            } else {
                                callback();
                            }
                        }
                    }
                ]
            },
            results: []
        }
    },
    computed: {
        nameLengthTip() {
            const tips = {
                letters: '将生成指定长度的随机英文字母',
                numbers: '将生成指定长度的随机数字',
                mixed: '将生成指定长度的随机英文和数字组合'
            };
            return tips[this.formData.nameType] || '';
        },
        passwordLengthTip() {
            const tips = {
                letters: '将生成指定长度的随机英文字母密码',
                numbers: '将生成指定长度的随机数字密码',
                mixed: '将生成指定长度的随机英文和数字组合密码'
            };
            return tips[this.formData.passwordType] || '';
        }
    },
    watch: {
        'formData.passwordType': function(newVal) {
            if (newVal !== 'fixed') {
                this.formData.password = ''; // 清空密码
                this.$refs.batchForm.clearValidate('password'); // 清除密码验证
            }
        }
    },
    methods: {
        submitForm() {
            this.$refs.batchForm.validate((valid) => {
                if (valid) {
                    this.loading = true;
                    // 发送 AJAX 请求到后端
                    fetch('<?php echo Typecho_Common::url('action/users-batch-create', Helper::options()->index); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(this.formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.results = data.results;
                        this.$message({
                            message: '批量创建完成！',
                            type: 'success'
                        });
                    })
                    .catch(error => {
                        this.$message.error('创建失败：' + error.message);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            });
        },
        resetForm() {
            this.$refs.batchForm.resetFields();
            this.results = [];
        },
        exportResults() {
            // 创建 txt 内容
            let content = '';
            this.results.forEach(item => {
                content += `用户名：${item.username} 密码：${item.password}\n`;
            });
            
            // 创建下载链接
            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'users_export.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    }
});
</script>

<?php
include 'footer.php';
?> 