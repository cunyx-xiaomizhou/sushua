# UI Editor 问题诊断与解决方案 V2

---

## 问题一：同级控件渲染位置不准确

### 根因分析

`src/utils/layout.ts` 中 `computeWidgetRect` 函数第 50-51 行：

```typescript
// UE4 parity: alignment (pivot) applies in ALL modes
x -= alignment.x * width
y -= alignment.y * height
```

**这是错误的。** 在 UE4 中，Alignment（对齐/轴心）仅在**单点锚定模式**（minX === maxX）下影响位置。在**拉伸模式**（minX !== maxX）下，Alignment 被引擎忽略——因为控件位置完全由 Anchor 区间 + Offset 边距决定。

当前代码对所有模式无差别应用了 Alignment 偏移，导致：
- 拉伸模式的控件被多减了一个 `alignment * size` 的偏移量
- 同级控件中一个是单点锚定、一个是拉伸锚定时，相对位置出现偏差
- 嵌套层级越深，误差累积越严重

### 解决方案

```typescript
// 修正：仅在单点锚定模式下应用 Alignment
if (!isStretchX) {
  x -= alignment.x * width
}
if (!isStretchY) {
  y -= alignment.y * height
}
```

**影响范围**：
- `computeWidgetRect()` — 主要修复点
- `WidgetRenderer.tsx` 第 206-218 行的 Text autoSize 重算逻辑已经正确（只在 `!isStretchX` 时重算）
- `computeAbsoluteRect()` 使用 `computeWidgetRect`，修复后自动生效
- 拖拽逻辑（handleDragMove）使用同一函数，无需额外改动

### 验证方式
- 创建 CanvasPanel > Panel（拉伸铺满） > 两个同级 Image（一个单点居中，一个拉伸填充）
- 修复前：两者重叠或偏移严重；修复后：与引擎一致

---

## 问题二：编辑界面交互设计重构

### 现状问题

| 区域 | 问题 |
|------|------|
| 左侧面板 | WidgetPalette（控件面板）+ HierarchyTree + ProjectManager 三层堆叠，ProjectManager 用 Tabs 包裹单个 Tab 无意义 |
| 右侧面板 | Inspector 占满上部，Assets/Export/AI 挤在下方 max-height:40%，AI 对话空间严重不足 |
| 顶部工具栏 | 功能按钮过多且分类不清晰，预览/导出/设置等散落各处 |
| Menu Bar | 五个菜单重复了工具栏已有功能，增加认知负担但无实际效率提升 |
| 画布 HUD | 四角装饰 + CANVAS_AREA 标签是纯装饰，占用视觉注意力但无功能 |

### 重构方案

#### 布局重设计（三栏 + 可折叠）

```
+----------------------------------------------------------+
| Menu Bar (精简)                                           |
+----------------------------------------------------------+
| Toolbar (精简：模式切换 | 对齐 | 缩放 | 预览)              |
+--------+-------------------------------+-----------------+
| LEFT   |         CENTER                |     RIGHT       |
|        |                               |                 |
| [Tab]  |       Canvas                  | Inspector       |
| 控件库  |                               | (选中时显示)     |
| 层级树  |                               |                 |
|        |                               +-----------------+
|        |                               | [Tab]           |
|        |                               | 资源 | AI | 导出 |
+--------+-------------------------------+-----------------+
| Status Bar                                                |
+----------------------------------------------------------+
```

#### 具体改动

1. **左侧面板**
   - 移除 ProjectManager 的 Tabs 包裹，改为折叠面板（Collapse），默认收起
   - 控件库（WidgetPalette）默认收起为图标条，点击展开浮层
   - 层级树独占主空间，最大化可视层级

2. **右侧面板**
   - Inspector 和二级面板（资源/AI/导出）改为**上下可拖拽分割**
   - 未选中控件时 Inspector 显示画布/项目全局属性（分辨率、网格、主题）
   - AI 面板支持全屏弹窗模式（按钮展开为 Modal）

3. **工具栏精简**
   - 移除重复功能（已在 Menu 中的不重复放 Toolbar）
   - 保留高频操作：撤销/重做 | 对齐工具 | 网格开关 | 缩放 | 预览
   - 低频操作（导入PSD/模板市场/项目管理）仅保留在 Menu > 文件

4. **Menu Bar 精简**
   - 文件：新建/打开/保存/导入/导出/项目管理
   - 编辑：撤销/重做/复制/粘贴/删除/全选
   - 视图：缩放/网格/预览/深浅色主题切换
   - 帮助：快捷键/版本信息
   - 移除「控件」菜单（添加控件应从左侧拖入或右键画布）

5. **画布 HUD**
   - 移除四角装饰 SVG（CornerBracket/DecorDot）
   - 保留 canvas 尺寸信息但移入 StatusBar
   - StatusBar 统一展示：模式 | 分辨率 | 网格 | 缩放 | 版本

---

## 问题三：主题色统一管理

### 现状问题

- **HomePage**：100% 硬编码暗色（`#0d0f1a`, `#e4e4e6`, `#3a3d50`），不支持浅色模式
- **EditorPage**：CSS 变量双主题（`.editor-page` / `.theme-dark`），是唯一正确实现
- **MenuBar**：用 CSS 变量 + fallback 值，但 fallback 值是暗色（`#0d0f1a`）
- **其他页面**（Workspaces/PsdImport/ResourceManager/TemplateMarket）：各自硬编码颜色，风格不统一
- **Ant Design 组件**：仅在 EditorPage 做了覆盖，其他页面使用默认主题

### 解决方案：全局 Design Token 系统

#### 1. 创建全局 CSS Token 文件

`src/styles/tokens.css`：

```css
:root {
  /* Base palette */
  --app-bg-primary: #0d0f1a;
  --app-bg-secondary: #1a1c2e;
  --app-bg-elevated: #22253a;
  --app-text-primary: #e4e4e6;
  --app-text-secondary: #6b6c6d;
  --app-text-disabled: #4a4c54;
  --app-border: #2a2d42;
  --app-border-accent: rgba(0, 68, 224, 0.3);
  --app-accent: #0044e0;
  --app-accent-hover: #0055ff;
  --app-accent-dim: rgba(0, 68, 224, 0.1);
  --app-emphasis: #F96704;
  --app-success: #4CAF50;
  --app-error: #ff4d4f;
}

[data-theme="light"] {
  --app-bg-primary: #f0f2f5;
  --app-bg-secondary: #ffffff;
  --app-bg-elevated: #ffffff;
  --app-text-primary: #1a1a1a;
  --app-text-secondary: #666666;
  --app-text-disabled: #b0b0b2;
  --app-border: rgba(0, 0, 0, 0.1);
  --app-border-accent: rgba(0, 68, 224, 0.2);
  --app-accent: #0044e0;
  --app-accent-hover: #0055ff;
  --app-accent-dim: rgba(0, 68, 224, 0.06);
  --app-emphasis: #F96704;
  --app-success: #4CAF50;
  --app-error: #ff4d4f;
}
```

#### 2. 迁移路径

| 优先级 | 页面/组件 | 工作量 | 方式 |
|--------|-----------|--------|------|
| P0 | tokens.css 创建 | 新文件 | 定义全局 token |
| P0 | EditorPage.css | 小 | `--ed-*` 映射到 `--app-*` |
| P1 | HomePage.css | 中 | 硬编码 → `var(--app-*)` |
| P1 | MenuBar.css | 小 | fallback 值 → 移除，统一用 token |
| P2 | 其他页面 CSS | 中 | 逐个替换硬编码色值 |
| P2 | Ant 全局覆盖 | 新文件 | `src/styles/antd-override.css` |

#### 3. 主题切换机制

```typescript
// 在 document.documentElement 设置 data-theme
// store 中的 theme 状态驱动：
useEffect(() => {
  document.documentElement.setAttribute('data-theme', theme)
}, [theme])
```

这样所有页面共享同一套 token，主题切换一处生效全局同步。

---

## 问题四：功能块内 UI 布局优化

### 现状问题

| 功能块 | 问题 |
|--------|------|
| WidgetPalette | 水平排列过密，图标+文字挤压，小屏溢出 |
| HierarchyTree | 无搜索/过滤，深层级难以定位 |
| InspectorPanel | 所有属性平铺展示，滚动距离长，常改属性与冷门属性混在一起 |
| AssetBrowser | 网格视图固定列数，无列表视图切换 |
| AIChat | 输入框位置不直观，首次使用无引导 |

### 优化方案

#### WidgetPalette
- 改为**两列网格 + 分组**：基础控件 | 容器 | 高级控件
- 每项显示为 40x40 图标块 + hover tooltip 显示名称
- 支持拖拽创建（当前仅点击）

#### HierarchyTree
- 顶部增加搜索框（按名称/类型过滤）
- 右键菜单增加：重命名 | 锁定 | 显示/隐藏 | 移至...
- 拖拽排序增加**插入指示线**（当前缺少视觉反馈）

#### InspectorPanel
- 属性分组折叠：Transform(位置/锚点/对齐) | Appearance(颜色/透明度/圆角) | Type-specific(类型专属)
- 「Transform」默认展开，其余默认收起
- 常用属性（位置/尺寸）始终可见（置顶 pinned section）

#### AssetBrowser
- 增加 列表/网格 视图切换
- 顶部增加搜索框（当前无）
- 资源类型标签过滤器改为多选 chips

#### AIChat
- 输入框固定底部（当前在滚动区内可能被推出视野）
- 增加"生成控件"/"修改选中"快捷按钮
- 空状态展示使用引导（3个快捷提示词）

---

## 实施优先级与排期建议

| 阶段 | 内容 | 风险 |
|------|------|------|
| **P0 - 立即修复** | layout.ts Alignment bug | 低（单函数改动，可验证） |
| **P1 - 短期** | 全局 Design Token + 主题迁移 | 中（CSS 改动量大但无逻辑风险） |
| **P2 - 中期** | 编辑器布局重构 + 交互优化 | 高（需整体重写 EditorPage 结构） |
| **P3 - 持续** | 各功能块内部优化 | 低（可逐块迭代） |

---

## 附：问题一的最小修复补丁

```diff
--- a/src/utils/layout.ts
+++ b/src/utils/layout.ts
@@ -47,9 +47,11 @@ export function computeWidgetRect(
     y = anchorY + offsets.top
   }

-  // UE4 parity: alignment (pivot) applies in ALL modes, not just single-point
-  // position -= size * alignment
-  x -= alignment.x * width
-  y -= alignment.y * height
+  // UE4 parity: alignment (pivot) only applies in single-point mode
+  // In stretch mode, position is fully determined by anchor+offsets (alignment is ignored)
+  if (!isStretchX) {
+    x -= alignment.x * width
+  }
+  if (!isStretchY) {
+    y -= alignment.y * height
+  }

   return {
```
