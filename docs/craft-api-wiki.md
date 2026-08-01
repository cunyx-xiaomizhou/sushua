# Craft API 接口使用 Wiki

> 本文档仅记录 craft.qq.com 的远程 API 接口（UIEditor 通过后端代理调用）。

---

## 一、环境与域名

| 环境 | 域名 | 说明 |
|------|------|------|
| dev | `https://dev.craft.qq.com` | 开发环境 |
| pre | `https://pre.craft.qq.com` | 预发布环境 |
| prod | `https://pre.craft.qq.com` | 正式环境（当前临时指向 pre） |

**网关前缀规则**：
- `/api/ms/...` — 微服务网关（dev 大部分接口走此路径）
- `/api/...` — 直连（pre/prod 大部分接口走此路径）

---

## 二、资源管理

| 方法 | 路径 | 说明 | 参数 | dev网关 |
|------|------|------|------|---------|
| GET | `/resource` | 资源列表（分页、筛选） | `source`, `engine`, `category`, `sub_category`, `page`, `page_size`, `completion_status`, `keyword` | ms |
| GET | `/resource/categories` | 资源分类树（旧版，dev已废弃） | `source`, `engine` | ms |
| GET | `/resource/sub-categories` | 指定主分类的子分类列表（新版） | `category`, `source` | ms |
| POST | `/resource/upload` | 上传资源文件 | multipart: `file`, `asset_type`, `engine`, `category`, `sub_category`, `tags`, `name`, `description` | ms |
| PUT | `/resource/:id/publish` | 发布资源（设置分类/标签） | body: 资源元数据 | ms |

**source 参数映射**（前端→API）：
- `'mine'` → `'aigc,upload'`
- `'community'` → `'community'`
- `'preset'` → `'preset'`

**完整请求示例**：
```
GET https://dev.craft.qq.com/api/ms/resource?source=preset&engine=ALL&category=图标&sub_category=功能&page=1&page_size=20&completion_status=completed
GET https://dev.craft.qq.com/api/ms/resource/sub-categories?category=界面&source=preset
GET https://pre.craft.qq.com/api/resource?source=aigc,upload&engine=ALL&page=1&page_size=20
```

**资源列表响应**：
```json
{
  "code": 0,
  "data": {
    "data": [
      {
        "id": 123,
        "source": "preset",
        "engine": "UE",
        "asset_type": "Texture2D",
        "category": "图标",
        "sub_category": "功能",
        "resource_name": "示例图标",
        "preview_image_url": "https://cdn.xxx/preview.png",
        "result_url": "https://cdn.xxx/result.png",
        "ue_asset_path": "/Game/Assets/Icons/xxx",
        "cook_value": "/Game/Assets/Icons/xxx",
        "status": "published",
        "audit_status": "approved",
        "completion_status": "completed",
        "published_at": "2025-01-01T00:00:00Z",
        "created_at": "2025-01-01T00:00:00Z"
      }
    ],
    "total": 100,
    "page": 1,
    "page_size": 20,
    "total_pages": 5
  }
}
```

**子分类响应**：
```json
{
  "code": 0,
  "message": "ok",
  "request_id": "2a1c5ce7-19a3-4b93-b7f8-aaf297e31782",
  "data": {
    "data": ["底板", "形状", "按钮", "摇杆", "结算", "艺术字", "装饰", "进度条"]
  }
}
```

---

## 三、GameMeta 版本化元数据（DEV 新版接口）

> 开关：`USE_GAMEMETA_API`（dev=true, pre/prod=false），一键切换

| 方法 | 路径 | 说明 | 参数 | 网关 |
|------|------|------|------|------|
| GET | `/gamemeta/file-hashes` | 获取所有元数据文件的版本哈希 | 无 | ms |
| GET | `/gamemeta/cloud-asset-tags` | 云资产标签列表（版本化） | `v=<hash>` | ms |
| GET | `/gamemeta/category-config` | 分类配置（版本化） | `v=<hash>` | ms |

**完整请求示例**：
```
GET https://dev.craft.qq.com/api/ms/gamemeta/file-hashes
GET https://dev.craft.qq.com/api/ms/gamemeta/cloud-asset-tags?v=07d27fc8
GET https://dev.craft.qq.com/api/ms/gamemeta/category-config?v=d36e10f3
```

**file-hashes 响应**：
```json
{ "code": 0, "data": { "cloud_asset_tags_version": "07d27fc8", "category_config_version": "d36e10f3" } }
```

**cloud-asset-tags 响应**：
```json
[
  { "ID": 1, "TagGroup": "主题", "TagName": "中世纪", "TagPriority": 1, "AssetType": ["All"] },
  { "ID": 100, "TagGroup": "风格", "TagName": "科幻", "TagPriority": 1, "AssetType": ["All"] },
  { "ID": 300, "TagGroup": "性能效果", "TagName": "lowpoly", "TagPriority": 1, "AssetType": ["StaticMesh", "SkeletalMesh"] }
]
```

**category-config 响应**：
```json
{
  "code": 0,
  "data": {
    "items": [
      { "category": "图标", "priority": 1, "aigc_engines": [], "display_engines": [], "asset_type": "Texture2D" },
      { "category": "界面", "priority": 2, "aigc_engines": [], "display_engines": [], "asset_type": "Texture2D" },
      { "category": "静态模型", "priority": 3, "aigc_engines": [], "display_engines": [], "asset_type": "StaticMesh" }
    ]
  }
}
```

---

## 四、游戏标签

| 方法 | 路径 | 说明 | 参数 | 网关 |
|------|------|------|------|------|
| POST | `/restApi/Work/GetAllGameTagsGrouped` | 获取游戏类型/美术风格/玩法标签 | body: `{}` | 不走ms |

**完整请求示例**：
```
POST https://dev.craft.qq.com/restApi/Work/GetAllGameTagsGrouped
Content-Type: application/json
Body: {}
```

**响应**：
```json
{
  "ret": 1,
  "data": {
    "game_types": [{"id": 1, "name": "射击"}, {"id": 2, "name": "MOBA"}],
    "art_styles": [{"id": 1, "name": "写实"}, {"id": 2, "name": "卡通"}],
    "play_tags": [{"id": 1, "name": "PVP"}, {"id": 2, "name": "PVE"}]
  }
}
```

---

## 五、工程/会话管理

| 方法 | 路径 | 说明 | 参数 | dev网关 |
|------|------|------|------|---------|
| GET | `/projects/list/all` | 获取用户工程列表 | 无 | ms |
| GET | `/conversations/:id` | 获取工程/会话详情 | 无 | ms |

**完整请求示例**：
```
GET https://dev.craft.qq.com/api/ms/projects/list/all
GET https://pre.craft.qq.com/api/conversations/abc123
```

---

## 六、版本管理

| 方法 | 路径 | 说明 | 参数 | dev网关 |
|------|------|------|------|---------|
| GET | `/versions/:id/code-files` | 获取版本代码文件列表 | 无 | ms |
| GET | `/versions/:id/detail` | 获取版本详情/资源映射 | 无 | ms |

**完整请求示例**：
```
GET https://dev.craft.qq.com/api/ms/versions/v123/code-files
GET https://pre.craft.qq.com/api/versions/v123/detail
```

---

## 七、AI 对话

| 方法 | 路径 | 说明 | 参数 | dev网关 |
|------|------|------|------|---------|
| POST | `/chat-messages` | 发送对话消息（返回 SSE 流） | body: 消息内容 | 不走ms |
| POST | `/chatv2/send` | 发送对话（v2版本） | body: 消息内容 | 不走ms |
| GET | `/chatv2/stream` | SSE 流式接收回复 | `conversation_id`, `last_id` | 不走ms |

**完整请求示例**：
```
POST https://dev.craft.qq.com/api/chat-messages
POST https://dev.craft.qq.com/api/chatv2/send
GET https://dev.craft.qq.com/api/chatv2/stream?conversation_id=xxx&last_id=yyy
```

---

## 八、用户认证/信息

| 方法 | 路径 | 说明 | 认证方式 |
|------|------|------|----------|
| GET | `/auth/me` | 获取当前登录状态 | Cookie |
| GET | `/user/info` | 获取用户基本信息（头像/昵称等） | Cookie |

**完整请求示例**：
```
GET https://dev.craft.qq.com/api/ms/auth/me
GET https://pre.craft.qq.com/api/user/info
```

---

## 九、路由网关开关总览

| 路由 Key | dev | pre | prod | 接口说明 |
|----------|-----|-----|------|----------|
| `resource` | `/api/ms` | `/api` | `/api` | 资源列表 |
| `resource/categories` | `/api/ms` | `/api` | `/api` | 资源分类（旧） |
| `resource/sub-categories` | `/api/ms` | `/api` | `/api` | 子分类（新） |
| `resource/upload` | `/api/ms` | `/api` | `/api` | 资源上传 |
| `resource/publish` | `/api/ms` | `/api` | `/api` | 资源发布 |
| `projects` | `/api/ms` | `/api` | `/api` | 工程列表 |
| `conversations` | `/api/ms` | `/api` | `/api` | 会话详情 |
| `versions/code-files` | `/api/ms` | `/api` | `/api` | 版本代码 |
| `versions/detail` | `/api/ms` | `/api` | `/api` | 版本详情 |
| `gamemeta` | `/api/ms` | `/api/ms` | `/api/ms` | 元数据配置 |
| `chat-messages` | `/api` | `/api` | `/api` | 对话消息 |
| `chatv2` | `/api` | `/api` | `/api` | 对话v2 |
| `restApi` | 不带前缀 | 不带前缀 | 不带前缀 | REST接口 |

---

## 十、注意事项

1. **所有请求需携带 Cookie**：通过微信扫码登录获取，由后端代理自动附加
2. **401 表示登录过期**：需重新扫码登录
3. **dev 的 `/resource/categories` 已废弃**：改用 `/resource/sub-categories` + `gamemeta/category-config` 组合
4. **prod 临时指向 pre 域名**：三处配置统一（routeRegistry.js、sandbox.js、routeRegistry.ts）
5. **source=mine 映射为 aigc,upload**：前端显示"我的资源"，API 实际请求 `source=aigc,upload`
6. **gamemeta 接口带版本号**：先请求 `file-hashes` 获取版本哈希，再带 `?v=<hash>` 请求具体数据，支持 CDN 缓存
7. **restApi 接口不走 /api 或 /api/ms 前缀**：直接 `域名/restApi/...`
