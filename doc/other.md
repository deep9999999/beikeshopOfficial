**Eloquent ORM**
- 用途: 以对象的方式操作数据库，负责实体关系、查询与持久化，减少手写 SQL
- 在项目中的使用
  - 模型定义与关系映射，如商品模型 `beike/Models/Product.php:24–71`（`categories()`、`description()`、`skus()`、`relations()` 等）
  - 软删除与类型转换，`SoftDeletes`、`$casts`、`$fillable`（`beike/Models/Product.php:7–21`）
  - 订单模型封装状态/通知与关系 `beike/Models/Order.php:41–70, 139–164`
  - 统一仓库层封装复杂查询与分页 `beike/Repositories/*`，控制器只调用仓库和服务
-价值: 统一数据访问层，保障字段安全（`$fillable`），提升可维护性与复用性

**Blade SSR**
- 用途: 服务端渲染视图，首屏快、SEO友好，模板内嵌动态数据与局部插槽
- 在项目中的使用
  - 前台主题模板入口 `themes/default/layout/master.blade.php:37–45` 引入构建产物与脚本
  - 内容插槽与布局 `themes/default/layout/master.blade.php:50–52, 60–68`（`@hook`、`@yield`、`@include`）
  - 页面变量注入 `themes/default/layout/master.blade.php:73–79`（`config` 注入登录状态、图片尺寸等）
  - 业务页与组件如 `themes/default/product/product.blade.php`、分页模板 `themes/default/shared/pagination/*`
- 价值: 页面结构清晰，数据直出减少前端复杂度，兼容局部前端增强

**jQuery/Bootstrap/TinyMCE**
- 用途: 后台界面与交互基础库
  - jQuery: DOM 事件、AJAX、UI 操作（全局工具 `bk` 配合）
  - Bootstrap: 样式与栅格、弹窗/表单等组件
  - TinyMCE: 富文本编辑器，支持图片/视频插入、国际化
- 在项目中的使用
  - 后台 JS 入口 `resources/beike/admin/js/app.js:9–16` 挂载 `$http` 与 `bk`
  - TinyMCE 初始化与工具栏按钮 `resources/beike/admin/js/app.js:107–151`
  - 文件管理器图片选择 `resources/beike/admin/js/app.js:21–33`
  - 全局 AJAX 设置与错误提示 `resources/beike/admin/js/app.js:61–70`
  - 基础库引入在主题模板 `themes/default/layout/master.blade.php:39–44`
- 价值: 快速构建后台管理界面，富文本编辑能力完善，学习与维护成本低

**Laravel Mix**
- 用途: 前端构建工具，编译打包 JS/SCSS、指纹管理与热更新，配合 `mix()` 在 Blade 中加载
- 在项目中的使用
  - 构建脚本 `package.json:3–11`（`development/watch/hot/production`）
  - 主题模板通过 `mix()` 引入打包产物 `themes/default/layout/master.blade.php:37–45`
  - 源码位置：后台样式 `resources/beike/admin/css/app.scss`，后台 JS `resources/beike/admin/js/*`，通用 JS `resources/js/*`
- 价值: 统一前端资源打包流程，便于缓存与版本管理，提高加载性能

**Horizon**
- 用途: Laravel 队列的 Redis 监控与管理面板，提供任务运行指标、失败任务、Supervisor 管控
- 在项目中的使用
  - 面板与路由中间件 `config/horizon.php:31–33, 73`
  - Supervisor 默认与环境配置 `config/horizon.php:167–196`
  - 等待阈值与修剪策略 `config/horizon.php:86–108, 121–126`
  - 队列默认连接当前为 `database`（`config/queue.php:16`），接入 Horizon 需切换到 Redis（`config/queue.php:65–72`）
- 价值: 生产环境下对异步任务的可视化与稳定性管理，支持扩容与性能调优

**配合关系与落地建议**
- SSR + 前端构建: Blade 输出结构与数据，Mix 负责静态资源版本与加载；在模板中 `mix()` 引入产物（`themes/default/layout/master.blade.php:37–45`）
- 交互增强: jQuery/Bootstrap/TinyMCE 构成后台交互基础，复杂表单与富文本处理由 TinyMCE 接管（`resources/beike/admin/js/app.js:107–151`）
- 数据层: Eloquent 模型搭配仓库/服务分层，控制器聚合业务并返回 JSON 或视图（如 `beike/AdminAPI/Controllers/ProductController.php:30–40, 68–84, 93–109, 118–124`）
- 异步任务: 初期可用 `database` 队列；高并发时切 Redis 并启用 Horizon 面板与 Supervisor 管理（`config/horizon.php:167–196`）

需要我把当前队列从 `database` 切换到 `redis` 并启用 Horizon 的最小化配置清单吗？我可以列出 `.env`、`config/queue.php` 与常用命令，帮你快速接入监控面板。