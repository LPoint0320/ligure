# Ligure.cc

一个融合作品展示、个人博客与商业服务的综合摄影网站。

## 技术栈

- 纯静态 HTML + CSS + JavaScript
- Poppins Google Font
- 全响应式设计（参考 seandalt.com 极简风格）
- 部署于 GitHub Pages

## 项目结构

```
├── index.html          # 首页
├── portfolio.html      # 作品展示
├── blog.html           # 博客列表
├── services.html       # 商业服务
├── about.html          # 关于我
├── contact.html        # 联系页面
├── 404.html            # 404 页面
├── CNAME               # 自定义域名
├── post/               # 博客文章
├── css/style.css       # 全局样式
├── js/main.js          # 交互脚本
└── images/             # 图片资源
```

## 本地预览

直接用浏览器打开任意 `.html` 文件即可预览。

## 部署

推送到 GitHub Pages 仓库即可自动部署。

```bash
git push origin main
```

## TODO

- [ ] 替换占位图片为真实作品
- [ ] 配置社交媒体链接
- [ ] 补充更多博客文章
- [ ] 配置联系表单后端
- [ ] 添加 Google Analytics
