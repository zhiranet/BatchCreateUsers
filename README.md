
# Typecho 批量创建用户插件
## 作者信息
- 作者：栀染
- 版本：1.0
- 主页：http://www.zhiranet.cn
## 功能演示图 
![演示图](https://pic.imgdb.cn/item/67436c3188c538a9b5bb7672.jpg)
## 插件介绍
一个简单易用的 Typecho 批量创建用户插件，支持自定义用户名和密码生成规则。
## 功能特点
- 批量创建多个用户
- 自定义用户名前缀
- 灵活的用户名生成规则：
  - 纯英文
  - 纯数字
  - 英文+数字混合
- 多样的密码生成方式：
  - 指定固定密码
  - 随机英文密码
  - 随机数字密码
  - 英文数字混合密码
- 可选择用户组权限
- 支持导出创建结果
## 使用方法
1. 下载插件并解压到 `/usr/plugins/BatchCreateUsers` 目录
2. 后台启用插件
3. 在"控制台"菜单中找到"批量创建用户"
4. 设置创建参数：
   - 用户名前缀（可选）
   - 创建数量（1-100）
   - 用户名长度（4-16位）
   - 密码长度（6-32位）
   - 用户组权限
## 注意事项
- 用户名长度：4-16 位
- 密码长度：6-32 位
- 单次最多创建 100 个用户
- 创建完成后可导出用户列表
## 开源协议

MIT License

Copyright (c) 2024 栀染

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
