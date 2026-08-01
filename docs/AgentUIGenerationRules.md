# Agent UI 代码生成规则

> 本文档定义 AI Agent 生成 UI Lua 文件时**必须遵守的格式约束**，确保产出可直接被 UI 编辑器 `luaImport` 解析并还原为可视化控件树。

---

## 一、文件结构（三件套）

Agent 为每个界面生成 **3 个文件**：

| 文件 | 路径 | 职责 |
|------|------|------|
| Layout | `LogicScripts/UI/WidgetLayout_{Screen}.lua` | 控件树创建 + 布局 |
| Logic | `LogicScripts/UI/WidgetLogic_{Screen}.lua` | 事件回调 + 业务逻辑 |
| UIConfigs | `GameDataConfigs/UI/UIConfigs.lua` | 资源路径字面量注册表 |

---

## 二、WidgetLayout 格式规范

### 2.1 文件骨架

```lua
-- {Screen} 界面布局文件（LightGameBox 引擎标准格式）
-- 由 UI Editor 自动生成，可直接在引擎中运行
-- 路径: Content/GameScript/LogicScripts/UI/WidgetLayout_{Screen}.lua

local Attach = require("UE_Package.LightGameLib.UI.Attach")
local PanelSlot = require("UE_Package.LightGameLib.UI.PanelSlot")
local Panel = require("UE_Package.LightGameLib.UI.Panel")
local Button = require("UE_Package.LightGameLib.UI.Button")
local Image = require("UE_Package.LightGameLib.UI.Image")
local Text = require("UE_Package.LightGameLib.UI.Text")
local ProgressBar = require("UE_Package.LightGameLib.UI.ProgressBar")
local ScrollBox = require("UE_Package.LightGameLib.UI.ScrollBox")
local FlipBook = require("UE_Package.LightGameLib.UI.FlipBook")
local Particle = require("UE_Package.LightGameLib.UI.Particle")
local Widget = require("UE_Package.LightGameLib.UI.Widget")
local Brush = require("UE_Package.LightGameLib.UI.Brush")
local DrawCanvas = require("UE_Package.LightGameLib.UI.DrawCanvas")
-- local Drag = require("UE_Package.LightGameLib.UI.Drag")  -- 仅需要拖拽时引入

local UIConfigs = require("GameDataConfigs.UI.UIConfigs")
local configs = UIConfigs["{Screen}"] or {}

local M = {}

--- 构建界面控件树
--- @param ui userdata OwnerWidget（由 WidgetLogic 的 self.OwnerWidget 传入）
--- @return table w 控件表（key=控件变量名, value=控件handle）
function M.Build(ui)
    local w = {}

    -- [控件创建区域]

    return w
end

return M
```

### 2.2 控件创建规则（关键！）

**必须** 遵循以下模式，否则编辑器无法解析：

#### 赋值目标

```lua
-- ✅ 正确：所有控件统一挂在 w 表上
w.varName = Type.Create(ui, "WidgetName", ...)

-- ❌ 错误：使用 local 变量（编辑器可解析但不推荐）
local varName = Type.Create(ui, "WidgetName", ...)

-- ❌ 禁止：动态生成变量名
w["item_" .. i] = ...
```

#### 变量名（代码引用名）与控件名（图层显示名）

编辑器区分两个名称概念：

| 概念 | 对应位置 | 用途 |
|------|----------|------|
| **varName**（变量名/代码引用名） | `w.varName = ...` 的 key | Lua 代码中引用控件、回调函数名拼接 |
| **WidgetName**（控件名/图层显示名） | `Type.Create(ui, "WidgetName", ...)` 第二参数 | 编辑器图层树中显示、可含中文 |

```lua
-- varName = BtnStart（代码引用），WidgetName = "开始按钮"（图层显示名，支持中文）
w.BtnStart = Button.Create(ui, "开始按钮", "开始游戏")

-- 两者也可以相同（推荐简单场景）
w.BtnStart = Button.Create(ui, "BtnStart", "开始游戏")
```

**规则**：
- **varName**：纯英文 + 数字 + 下划线，首字符必须是字母，同一文件内全局唯一
- **WidgetName**：可以使用中文，用于图层面板阅读友好显示
- 回调函数名基于 **WidgetName** 拼接：`On_{WidgetName}_{事件名}`（如 `On_开始按钮_OnClicked`）
- 当 WidgetName 含中文时，建议回调函数名使用 varName：`On_{varName}_OnClicked`

#### 创建调用签名（必须严格匹配）

| 控件类型 | Create 调用 |
|----------|-------------|
| Panel | `Panel.Create(ui, "Name")` |
| Image | `Image.Create(ui, "Name", texture)` |
| Image(圆角) | `Image.CreateRoundedBox(ui, "Name", R,G,B,A, TL,TR,BR,BL)` |
| Text | `Text.Create(ui, "Name", "内容", FontSize, R,G,B,A)` |
| Button | `Button.Create(ui, "Name", "文字")` |
| ProgressBar | `ProgressBar.Create(ui, "Name", Percent, FR,FG,FB,FA, BR,BG,BB,BA)` |
| ScrollBox | `ScrollBox.Create(ui, "Name", Direction)` |
| FlipBook | `FlipBook.Create(ui, "Name", W, H, FlipBookRes, FPS, bLoop, bAutoPlay)` |
| Particle | `Particle.Create(ui, "Name", Template, bAutoActivate)` |
| EditableTextBox | `EditableTextBox.Create(ui, "Name", "提示文字")` |
| CheckBox | `CheckBox.Create(ui, "Name", bInitChecked)` |
| Slider | `Slider.Create(ui, "Name", InitValue)` |
| SkillButton | `SkillButton.Create(ui, "Name", SkillSlot)` |
| DrawCanvas | `DrawCanvas.Create(ui, "Name", Width, Height)` |

### 2.3 父子关系（Attach）

```lua
-- 挂接到容器
Attach.AttachToPanel(w.parentVar, w.childVar)
-- 挂接到 ScrollBox
Attach.AttachToScrollBox(w.scrollBoxVar, w.childVar)
-- 挂接到根
Attach.AttachToPanel(ui, w.childVar)
```

**规则**：
- 第一个参数必须是 `ui`（根）、`w.xxx`（父控件变量）
- 第二个参数必须是 `w.xxx`
- **每个控件必须有且仅有一个 Attach 调用**
- Attach 调用顺序决定视觉层级（后调用 = 更上层）

### 2.4 布局设置（PanelSlot）

**创建后立即设置**，每个控件紧跟一组 PanelSlot 调用：

```lua
w.BtnStart = Button.Create(ui, "BtnStart", "开始")
Attach.AttachToPanel(ui, w.BtnStart)
PanelSlot.SetAnchors(w.BtnStart, 0.5, 0.5, 0.5, 0.5)     -- 居中锚点
PanelSlot.SetAlignment(w.BtnStart, 0.5, 0.5)               -- Pivot 中心
PanelSlot.SetOffsets(w.BtnStart, 0, 0, 300, 80)            -- X, Y, Width, Height
PanelSlot.SetZOrder(w.BtnStart, 10)                        -- 可选
```

**参数必须是数字字面量**，禁止使用变量：
```lua
-- ✅ 正确
PanelSlot.SetOffsets(w.title, 0, -50, 400, 60)

-- ❌ 禁止
local offsetY = -50
PanelSlot.SetOffsets(w.title, 0, offsetY, 400, 60)
```

### 2.5 视觉属性设置（Widget）

```lua
Widget.SetRenderOpacity(w.varName, 0.8)                      -- 不透明度
Widget.SetVisibility(w.varName, UE.ESlateVisibility.Collapsed) -- 隐藏
Widget.SetVisibility(w.varName, UE.ESlateVisibility.Hidden)    -- 隐藏但占位
Widget.SetVisibility(w.varName, UE.ESlateVisibility.HitTestInvisible)      -- 可见但不可点击
Widget.SetVisibility(w.varName, UE.ESlateVisibility.SelfHitTestInvisible)  -- 自身不可点
Widget.SetRenderTransformAngle(w.varName, 45)                -- 旋转
Widget.SetRenderTransformPivot(w.varName, 0.5, 0.5)         -- 旋转锚点
Widget.SetRenderScale(w.varName, 1.5, 1.5)                  -- 缩放
Widget.SetClipToBounds(w.varName, true)                      -- 裁剪
Widget.SetIsEnabled(w.varName, false)                        -- 禁用
```

### 2.5b 槽位自适应尺寸（PanelSlot）

```lua
-- 让子控件尺寸由内容决定（文本/图片自适应），替代 SetOffsets 的 Width/Height
PanelSlot.SetAutoSize(w.varName, true)
```

### 2.6 类型特定属性（完整 API 对照）

#### Text

```lua
Text.SetJustification(w.title, UE.ETextHAlignType.Center)      -- Left / Center / Right
Text.SetVerticalAlignment(w.title, UE.ETextVAlignType.Center)  -- Top / Center / Bottom
Text.SetAutoWrap(w.desc, true)                                  -- 自动换行
Text.SetOutline(w.title, 2, 0, 0, 0, 1)                       -- OutlineSize, R, G, B, A
Text.SetFontSize(w.title, 24)                                   -- 运行时改字号
Text.SetText(w.title, "新文字")                                  -- 运行时改文字
Text.SetTextColor(w.title, 1, 0, 0, 1)                         -- 运行时改颜色
```

#### Panel 自动排布（HBox/VBox/Panel）

```lua
Panel.SetAutoArrangement(w.container, true)       -- 启用自动排列
Panel.SetLayoutType(w.container, 0)               -- 0=Horizontal, 1=Vertical
Panel.SetChildGap(w.container, 10)                -- 子控件间距
Panel.SetSortRules(w.container, 4)                -- 0~8 对齐规则（见下表）
Panel.SetSlotPadding(w.container, 10, 10, 10, 10) -- Top, Bottom, Left, Right
Panel.SetHorizontalArrangement(w.container, 0)    -- 0=LeftToRight, 1=RightToLeft
Panel.SetVerticalArrangement(w.container, 0)      -- 0=TopToBottom, 1=BottomToTop
```

**SortRules 对齐规则映射表**：
| 值 | 含义 |
|----|------|
| 0 | LeftTop |
| 1 | LeftCenter |
| 2 | LeftBottom |
| 3 | CenterTop |
| 4 | CenterCenter |
| 5 | CenterBottom |
| 6 | RightTop |
| 7 | RightCenter |
| 8 | RightBottom |

#### Panel 网格模式（GridBox）

```lua
Panel.SetAutoArrangement(w.grid, true)           -- 启用自动排列
Panel.SetGridSlot(w.grid, true)                  -- 启用网格模式（自动换行）
Panel.SetLayoutType(w.grid, 0)                   -- 0=Horizontal, 1=Vertical
Panel.SetChildGap(w.grid, 10)                    -- 子控件间距
Panel.SetSlotPadding(w.grid, 10, 10, 10, 10)    -- 容器内边距 Top, Bottom, Left, Right
Panel.SetSortRules(w.grid, 0)                    -- 对齐规则
Panel.SetHorizontalArrangement(w.grid, 0)        -- 水平排序方向
Panel.SetVerticalArrangement(w.grid, 0)          -- 垂直排序方向
```

**注意**：GridBox 无 "列数" API，列数由父容器宽度和子控件尺寸自动计算（引擎自动换行）。

#### ScrollBox

```lua
ScrollBox.SetScrollBarVisibility(w.scroll, UE.ESlateVisibility.Collapsed)  -- 隐藏滚动条
ScrollBox.SetScrollBarVisibility(w.scroll, UE.ESlateVisibility.Visible)    -- 显示滚动条
ScrollBox.SetAllowOverscroll(w.scroll, true)     -- 允许过度滚动（弹性效果）
ScrollBox.SetOrientation(w.scroll, UE.EOrientation.Orient_Horizontal)      -- 运行时改方向
-- 运行时滚动控制（Logic 中使用）：
ScrollBox.ScrollToStart(w.scroll)
ScrollBox.ScrollToEnd(w.scroll)
ScrollBox.SetScrollOffset(w.scroll, 100)
ScrollBox.GetScrollOffset(w.scroll)              -- → number
ScrollBox.GetScrollOffsetOfEnd(w.scroll)         -- → number
```

#### ProgressBar

```lua
-- Create 已包含初始百分比和颜色，运行时使用：
ProgressBar.SetProgress(w.bar, 0.75)             -- 设置进度 0~1
ProgressBar.SetFillColor(w.bar, 0, 1, 0, 1)     -- 运行时改填充色
```

**注意**：ProgressBar 仅支持纯色，无贴图/纹理 API。

#### Button

```lua
-- 画刷（四种 Brush 类型，按需选用）
-- 简单贴图:
local btn_normal_tex = LoadObjectByPath(configs["btn_normal"])
local normalBrush = Brush.CreateTextureImage(btn_normal_tex, 1, 1, 1, 1)
-- 九宫格拉伸:
local normalBrush = Brush.CreateTextureBox(btn_normal_tex, 1, 1, 1, 1, 0.2, 0.2, 0.2, 0.2)
-- 圆角贴图:
local normalBrush = Brush.CreateTextureRoundedBox(btn_normal_tex, 1, 1, 1, 1, 8, 8, 8, 8, 0, 0, 0, 0, 0)
-- 纯色圆角（无贴图）:
local normalBrush = Brush.CreateColorRoundedBox(0.2, 0.5, 1, 1, 8, 8, 8, 8, 0, 0, 0, 0, 0)

-- 四态 Brush
Button.SetNormalBrush(w.btn, normalBrush)
Button.SetHoveredBrush(w.btn, hoveredBrush)
Button.SetPressedBrush(w.btn, pressedBrush)
Button.SetDisabledBrush(w.btn, disabledBrush)

-- 文字属性
Button.SetTextColor(w.btn, 1, 1, 1, 1)          -- R, G, B, A
Button.SetFontSize(w.btn, 20)
Button.SetText(w.btn, "新文字")                   -- 运行时改文字
Button.SetTextOutline(w.btn, 2, 0, 0, 0, 1)     -- OutlineSize, R, G, B, A

-- 点击/触摸方式
Button.SetClickMethod(w.btn, UE.EButtonClickMethod.PreciseClick)
Button.SetTouchMethod(w.btn, UE.EButtonTouchMethod.PreciseTap)  -- ScrollBox 内 Button 必设

-- ScrollBox 内 Button 需设置精确点击模式（否则阻挡滚动）
Button.SetTouchMethod(w.btn, UE.EButtonTouchMethod.PreciseTap)
```

#### EditableTextBox

```lua
-- Create 已包含提示文字，运行时使用：
EditableTextBox.SetHintText(w.input, "新提示")
EditableTextBox.SetInputText(w.input, "初始值")
EditableTextBox.GetInputText(w.input)            -- → string
```

**注意**：EditableTextBox 无字号/颜色 API，样式由引擎主题决定。

#### CheckBox

```lua
-- Create 已包含初始选中状态，运行时使用：
CheckBox.SetChecked(w.chk, true)
CheckBox.IsChecked(w.chk)                       -- → boolean
-- 事件回调：On_<Name>_OnCheckStateChanged
```

#### Slider

```lua
Slider.SetValue(w.slider, 0.5)
Slider.SetMinValue(w.slider, 0)
Slider.SetMaxValue(w.slider, 1)
Slider.SetBarColor(w.slider, 0.3, 0.3, 0.3, 1)    -- R, G, B, A
Slider.SetHandleColor(w.slider, 0.3, 0.6, 0.9, 1) -- R, G, B, A
Slider.SetLocked(w.slider, true)                     -- 锁定不可拖拽
Slider.GetValue(w.slider)                            -- → number
Slider.GetNormalizedValue(w.slider)                  -- → number (0~1)
-- 事件回调：On_<Name>_OnValueChanged
```

#### SkillButton

```lua
SkillButton.SetSkillSlot(w.skill, 0)            -- EMiniGame_ActiveSkillSlotType
SkillButton.SetShowCD(w.skill, true)             -- 显示冷却
SkillButton.SetShowName(w.skill, true)           -- 显示技能名
```

#### FlipBook

```lua
-- Create 参数: (OwnerUI, Name, W, H, FlipBookRes, FPS, bLoop, bAutoPlay)
FlipBook.SetFPS(w.fb, 24)                       -- 改帧率
FlipBook.SetLooper(w.fb, false)                  -- 改循环
FlipBook.SetPlayState(w.fb, UE.EFlipBookWidgetPlayState.Play)  -- Play / Pause / Stop
FlipBook.GetNumFrames(w.fb)                      -- → integer
```

#### UIParticle

```lua
-- Create 参数: (OwnerUI, Name, ParticleTemplate, bAutoActivate)
Particle.SetActivate(w.ps, true, false)          -- bActive, bReset
Particle.Reactivate(w.ps, true)                  -- 重新激活，bReset
Particle.SetTemplate(w.ps, newTemplate)          -- 换粒子模板
```

#### DrawCanvas

```lua
-- Create 参数: (OwnerUI, Name, W, H)
-- 绘制回调在 Logic 中定义：On_<CanvasName>_OnPaint(Widget, Payload)
DrawCanvas.DrawBox(Widget, X, Y, W, H, R, G, B, A, LayerOffset)
DrawCanvas.DrawRoundedBox(Widget, X, Y, W, H, R, G, B, A, TL, TR, BR, BL, OR, OG, OB, OA, OutlineW, LayerOffset)
DrawCanvas.DrawCircle(Widget, CX, CY, Radius, R, G, B, A, LayerOffset, Segments)
DrawCanvas.DrawLine(Widget, X1, Y1, X2, Y2, Thickness, R, G, B, A, LayerOffset)
DrawCanvas.DrawArc(Widget, CX, CY, Radius, Start, End, Thickness, R, G, B, A, LayerOffset, Segments)
DrawCanvas.DrawGradient(Widget, X, Y, W, H, SR, SG, SB, SA, ER, EG, EB, EA, bHorizontal, LayerOffset)
DrawCanvas.DrawText(Widget, X, Y, MaxW, MaxH, Text, FontSize, R, G, B, A, LayerOffset, HAlign, VAlign)
DrawCanvas.DrawRotatedBox(Widget, X, Y, W, H, Angle, R, G, B, A, LayerOffset)
DrawCanvas.DrawImage(Widget, X, Y, W, H, Texture, R, G, B, A, LayerOffset)
DrawCanvas.DrawSpline(Widget, SX, SY, SDX, SDY, EX, EY, EDX, EDY, Thickness, R, G, B, A, LayerOffset)
DrawCanvas.DrawPolygon(Widget, PointsX, PointsY, R, G, B, A, LayerOffset)
DrawCanvas.GetPaintSize(Widget)                  -- → Width, Height (多返回值)
DrawCanvas.GetDrawCommandCount(Widget)           -- → integer
```

#### Drag & Drop

```lua
Drag.SetWidgetDraggable(ui, w.item, true)
Drag.SetWidgetDropZone(ui, w.dropArea, true)
Drag.SetDragVisualMode(ui, "Translucent")        -- Exact / HoverOnly / Translucent
```

---

## 三、资源引用规则（最重要！）

### 3.1 核心原则

**所有资源路径必须通过 UIConfigs 字面量表引用**，禁止在 Layout 文件中直接写路径字符串。

```lua
-- ✅ 正确：通过 configs 引用
local icon_tex = LoadObjectByPath(configs["icon"])
w.icon = Image.Create(ui, "Icon", icon_tex)

-- ❌ 禁止：直接写路径
w.icon = Image.Create(ui, "Icon", LoadObjectByPath("Texture2D'/Game/UI/icon.icon'"))

-- ❌ 禁止：使用变量/数组间接引用
local paths = { "Texture2D'/Game/UI/a.a'", "Texture2D'/Game/UI/b.b'" }
w.icon = Image.Create(ui, "Icon", LoadObjectByPath(paths[1]))
```

### 3.2 UIConfigs 文件格式

```lua
-- Content/GameScript/GameDataConfigs/UI/UIConfigs.lua
local Configs = {}

Configs["ScreenName"] = {
    icon = "Texture2D'/Game/UI/Icons/icon_start.icon_start'",
    bg = "Texture2D'/Game/UI/Backgrounds/bg_main.bg_main'",
    btn_normal = "Texture2D'/Game/UI/Buttons/btn_blue.btn_blue'",
    flipbook_fire = "PaperFlipbook'/Game/Effects/fire_seq.fire_seq'",
    particle_glow = "UIParticleSystem'/Game/Particles/glow.glow'",
}

return Configs
```

**Key 命名规则**：
- 使用 `varName` 或 `varName_tex/fb/ps` 作为 key
- 一个控件一个 key，一对一关系
- **值必须是字面量字符串**，不能是变量或表达式

### 3.3 资源路径格式

| 资源类型 | 路径格式 |
|----------|----------|
| 贴图 | `Texture2D'/Game/路径/名称.名称'` |
| 序列帧 | `PaperFlipbook'/Game/路径/名称.名称'` |
| UI粒子 | `UIParticleSystem'/Game/路径/名称.名称'` |
| 音频 | `/Game/路径/名称.名称`（无类型前缀） |

### 3.4 Brush 构造中的资源引用

```lua
-- 先 load 再构造 brush（4 种 Brush 类型）
local btn_tex = LoadObjectByPath(configs["btn_normal"])

-- 1. 简单贴图 Brush（默认）
local brush = Brush.CreateTextureImage(btn_tex, R, G, B, A)

-- 2. 九宫格 Brush（可拉伸边框，MarginL/T/R/B 取值 0~1）
local brush = Brush.CreateTextureBox(btn_tex, R, G, B, A, MarginL, MarginT, MarginR, MarginB)

-- 3. 圆角贴图 Brush
local brush = Brush.CreateTextureRoundedBox(btn_tex, R, G, B, A, TL, TR, BR, BL, OutW, OR, OG, OB, OA)

-- 4. 纯色圆角 Brush（无贴图）
local brush = Brush.CreateColorRoundedBox(R, G, B, A, TL, TR, BR, BL, OutW, OR, OG, OB, OA)

-- Image 控件也可用 Brush 设置九宫格/圆角
Image.SetBrush(w.icon, brush)

-- Button 四态 Brush
Button.SetNormalBrush(w.BtnStart, normalBrush)
Button.SetHoveredBrush(w.BtnStart, hoveredBrush)
Button.SetPressedBrush(w.BtnStart, pressedBrush)
Button.SetDisabledBrush(w.BtnStart, disabledBrush)
```

---

## 四、WidgetLogic 格式规范

```lua
-- {Screen} 界面逻辑脚本
-- 创建方式: UIView.Create("{Screen}", "LogicScripts.UI.WidgetLogic_{Screen}"[, zorder])

local Text = require("UE_Package.LightGameLib.UI.Text")       -- 按需引入
local Widget = require("UE_Package.LightGameLib.UI.Widget")
local UIView = require("UE_Package.LightGameLib.UI.UIView")
local Layout = require("LogicScripts.UI.WidgetLayout_{Screen}")

local M = class("WidgetLogic_{Screen}")

function M:Start()
    self.w = Layout.Build(self.OwnerWidget)
    -- 注册事件、设置初始状态
end

-- 按钮回调：函数名 = On_{控件Name}_OnClicked
function M:On_BtnStart_OnClicked(Widget)
    -- 逻辑
end

-- DrawCanvas 绘制回调
function M:On_MyCanvas_OnPaint(Widget, Payload)
    local DrawCanvas = require("UE_Package.LightGameLib.UI.DrawCanvas")
    -- 绘制命令
end

-- EditableTextBox 文字变化回调
function M:On_InputName_OnTextChanged(Widget)
    local EditableTextBox = require("UE_Package.LightGameLib.UI.EditableTextBox")
    local text = EditableTextBox.GetInputText(Widget)
end

-- CheckBox 状态变化回调
function M:On_MyCheck_OnCheckStateChanged(Widget)
    local CheckBox = require("UE_Package.LightGameLib.UI.CheckBox")
    local checked = CheckBox.IsChecked(Widget)
end

-- Slider 值变化回调
function M:On_MySlider_OnValueChanged(Widget)
    local Slider = require("UE_Package.LightGameLib.UI.Slider")
    local val = Slider.GetValue(Widget)
end

function M:Update(DeltaTime)
end

function M:End()
end

return M
```

---

## 五、绝对禁止事项

| # | 禁止行为 | 原因 |
|---|----------|------|
| 1 | **循环创建控件** | 编辑器逐行正则解析，循环内的 Create 无法确定实例数量 |
| 2 | **条件分支创建** | `if/else` 内的 Create 导致控件树不确定 |
| 3 | **变量间接引用资源** | 无法静态追踪值来源 |
| 4 | **动态拼接变量名** | `w["btn_"..i]` 无法解析 |
| 5 | **省略 Attach 调用** | 编辑器靠 Attach 建立父子关系 |
| 6 | **参数使用变量而非字面量** | SetAnchors/SetOffsets 等必须是数字直接量 |
| 7 | **多个 Create 写同一行** | 正则按行匹配 |
| 8 | **在 Logic 文件中创建控件** | 控件创建只能在 Layout.Build() 内 |
| 9 | **资源路径写在 Layout 中** | 必须通过 UIConfigs 注册 |
| 10 | **使用 require 返回值作为资源** | `LoadObjectByPath(configs["key"])` 是唯一合法方式 |

---

## 六、编辑器解析能力参考

编辑器 `luaImport.ts` 使用正则逐模式匹配，以下是它能解析的所有模式：

### 可解析的 Create 模式
```
w.varName = Type.Create(ui, "Name", ...)
w.varName = Type.Create("Name", ...)
local varName = Type.Create(ui, "Name", ...)
w.varName = Type.CreateXxx(ui, "Name", ...)
```

### 可解析的属性调用
```
PanelSlot.SetAnchors(w.varName, N, N, N, N)
PanelSlot.SetAlignment(w.varName, N, N)
PanelSlot.SetOffsets(w.varName, N, N, N, N)
PanelSlot.SetZOrder(w.varName, N)
PanelSlot.SetAutoSize(w.varName, true)
Widget.SetVisibility(w.varName, UE.ESlateVisibility.XXX)    -- Collapsed/Hidden/HitTestInvisible/SelfHitTestInvisible
Widget.SetRenderOpacity(w.varName, N)
Widget.SetClipToBounds(w.varName, true)
Widget.SetRenderTransformAngle(w.varName, N)
Widget.SetRenderTransformPivot(w.varName, N, N)
Widget.SetRenderScale(w.varName, N, N)
Widget.SetIsEnabled(w.varName, false)
Text.SetJustification(w.varName, UE.ETextHAlignType.XXX)       -- Left/Center/Right
Text.SetVerticalAlignment(w.varName, UE.ETextVAlignType.XXX)   -- Top/Center/Bottom
Text.SetAutoWrap(w.varName, true)
Text.SetOutline(w.varName, N, N, N, N, N)
Panel.SetAutoArrangement(w.varName, true/false)
Panel.SetLayoutType(w.varName, N)
Panel.SetChildGap(w.varName, N)
Panel.SetGridSlot(w.varName, true)
Panel.SetSortRules(w.varName, N)
Panel.SetSlotPadding(w.varName, N, N, N, N)
Panel.SetHorizontalArrangement(w.varName, N)
Panel.SetVerticalArrangement(w.varName, N)
ScrollBox.SetScrollBarVisibility(w.varName, UE.ESlateVisibility.XXX)
ScrollBox.SetAllowOverscroll(w.varName, true)
Image.SetBrush(w.varName, brushVar)
Image.SetImageColor(w.varName, N, N, N, N)
Button.SetNormalBrush(w.varName, brushVar)
Button.SetHoveredBrush(w.varName, brushVar)
Button.SetPressedBrush(w.varName, brushVar)
Button.SetDisabledBrush(w.varName, brushVar)
Button.SetTextColor(w.varName, N, N, N, N)
Button.SetFontSize(w.varName, N)
Button.SetTextOutline(w.varName, N, N, N, N, N)
Button.SetTouchMethod(w.varName, UE.EButtonTouchMethod.PreciseTap)
Slider.SetMinValue(w.varName, N)
Slider.SetMaxValue(w.varName, N)
Slider.SetBarColor(w.varName, N, N, N, N)
Slider.SetHandleColor(w.varName, N, N, N, N)
Slider.SetLocked(w.varName, true)
SkillButton.SetShowCD(w.varName, false)
SkillButton.SetShowName(w.varName, false)
Drag.SetWidgetDraggable(xx, w.varName, true)
Drag.SetWidgetDropZone(xx, w.varName, true)
Drag.SetDragVisualMode(xx, "Mode")
```

### 可解析的资源加载模式
```
local varName_tex = LoadObjectByPath(configs["key"])
local varName_tex = LoadObjectByPath(configs.key)
```

### 可解析的 Brush 构造模式
```
local varName_brush = Brush.CreateTextureImage(varName_tex, R, G, B, A)
local varName_brush = Brush.CreateTextureBox(varName_tex, R, G, B, A, ML, MT, MR, MB)
local varName_brush = Brush.CreateTextureRoundedBox(varName_tex, R, G, B, A, TL, TR, BR, BL, OW, OR, OG, OB, OA)
local varName_brush = Brush.CreateColorRoundedBox(R, G, B, A, TL, TR, BR, BL, OW, OR, OG, OB, OA)
-- Button 四态 Brush（变量名格式：varName_normal_brush / varName_hovered_brush / varName_pressed_brush / varName_disabled_brush）
local varName_normal_brush = Brush.CreateTextureBox(varName_normal_tex, ...)
local varName_hovered_brush = Brush.CreateTextureImage(varName_hovered_tex, ...)
local varName_pressed_brush = Brush.CreateColorRoundedBox(...)
local varName_disabled_brush = Brush.CreateColorRoundedBox(...)
```

### 可解析的组件引用模式
```
-- require（头部）
local Layout_ComponentName = require("LogicScripts.UI.WidgetLayout_Component_ComponentName")

-- 引用调用（Build 区域内）
w.varName = Layout_ComponentName.Build(ui, parentVar)
```

---

## 七、代码顺序规范

一个控件的完整代码块，严格按以下顺序排列：

```lua
-- 1. 资源加载（如有）
local icon_tex = LoadObjectByPath(configs["icon"])

-- 2. 创建控件
w.icon = Image.Create(ui, "Icon", icon_tex)

-- 3. 挂接父子
Attach.AttachToPanel(ui, w.icon)

-- 4. 布局设置
PanelSlot.SetAnchors(w.icon, 0.5, 0, 0.5, 0)
PanelSlot.SetAlignment(w.icon, 0.5, 0)
PanelSlot.SetOffsets(w.icon, 0, 20, 64, 64)

-- 5. 视觉属性（可选）
Widget.SetRenderOpacity(w.icon, 0.9)

-- 6. 类型特定设置（可选）
-- 如 Text.SetAutoWrap, Panel.SetLayoutType 等
```

控件之间用空行分隔，保持清晰的块结构。

---

## 八、控件引擎 API 属性完整对照表

| 控件类型 | 引擎支持的属性 | 说明 |
|----------|---------------|------|
| **Panel/HBox/VBox** | AutoArrangement, LayoutType, ChildGap, GridSlot, SortRules, SlotPadding, HorizontalArrangement, VerticalArrangement | 所有面板容器共享 Panel API |
| **GridBox** | 同 Panel + GridSlot=true | 本质是 Panel + SetGridSlot(true)，无 "列数" 概念 |
| **Button** | NormalBrush, HoveredBrush, PressedBrush, DisabledBrush, TextColor, FontSize, Text, TextOutline, ClickMethod, TouchMethod | 四态完整支持 |
| **Image** | Create(texture) / CreateRoundedBox(color), SetBrush, SetImage, SetImageColor | 通过 Brush 支持九宫格/圆角 |
| **Text** | FontSize, TextColor, Justification(HAlign), VerticalAlignment, AutoWrap, Outline, SetText, MeasureText | 无行高 API |
| **ProgressBar** | Percent, FillColor | **仅颜色，无贴图 API** |
| **ScrollBox** | Orientation, ScrollBarVisibility, AllowOverscroll, ScrollOffset, ScrollToStart/End | 无滚动条粗细 API |
| **EditableTextBox** | HintText, InputText, GetInputText | **无字号/颜色 API** |
| **CheckBox** | Checked, IsChecked | 事件: OnCheckStateChanged |
| **Slider** | Value, MinValue, MaxValue, BarColor, HandleColor, Locked | 事件: OnValueChanged |
| **SkillButton** | SkillSlot, ShowCD, ShowName | 技能槽位绑定 |
| **FlipBook** | FPS, Loop, AutoPlay, PlayState, NumFrames | 序列帧动画控件 |
| **UIParticle** | AutoActivate, Activate, Reactivate, Template | UI 粒子控件 |
| **DrawCanvas** | 所有 Draw* 方法 | 绘制回调 OnPaint |
| **Widget(通用)** | Visibility, RenderOpacity, RenderTransformAngle, RenderScale, ClipToBounds, IsEnabled, RenderShear, RenderTranslation | 所有控件共享 |

### 属性单位参考

| 单位类型 | 属性 |
|----------|------|
| **像素 (px)** | CornerRadius TL/TR/BR/BL、PanelSlot.SetOffsets、Panel.SetChildGap、Text FontSize、Widget.SetRenderTransformAngle（度） |
| **归一化 (0~1)** | PanelSlot.SetAnchors、PanelSlot.SetAlignment、颜色 R/G/B/A、Widget.SetRenderTransformPivot、Widget.SetRenderOpacity |

> CornerRadius 在 `Image.CreateRoundedBox` / `Brush.CreateColorRoundedBox` / `Brush.CreateTextureRoundedBox` 中均为**像素单位**，不是归一化值。

---

## 九、完整最小示例

```lua
-- GameDataConfigs/UI/UIConfigs.lua 中追加:
Configs["MyMenu"] = {
    bg = "Texture2D'/Game/UI/Bg/menu_bg.menu_bg'",
    btn_start = "Texture2D'/Game/UI/Btn/btn_green.btn_green'",
}

-- LogicScripts/UI/WidgetLayout_MyMenu.lua:
local Attach = require("UE_Package.LightGameLib.UI.Attach")
local PanelSlot = require("UE_Package.LightGameLib.UI.PanelSlot")
local Panel = require("UE_Package.LightGameLib.UI.Panel")
local Button = require("UE_Package.LightGameLib.UI.Button")
local Image = require("UE_Package.LightGameLib.UI.Image")
local Text = require("UE_Package.LightGameLib.UI.Text")
local ProgressBar = require("UE_Package.LightGameLib.UI.ProgressBar")
local ScrollBox = require("UE_Package.LightGameLib.UI.ScrollBox")
local FlipBook = require("UE_Package.LightGameLib.UI.FlipBook")
local Particle = require("UE_Package.LightGameLib.UI.Particle")
local Widget = require("UE_Package.LightGameLib.UI.Widget")
local Brush = require("UE_Package.LightGameLib.UI.Brush")
local DrawCanvas = require("UE_Package.LightGameLib.UI.DrawCanvas")

local UIConfigs = require("GameDataConfigs.UI.UIConfigs")
local configs = UIConfigs["MyMenu"] or {}

local M = {}

function M.Build(ui)
    local w = {}

    -- 背景图
    local bg_tex = LoadObjectByPath(configs["bg"])
    w.Bg = Image.Create(ui, "Bg", bg_tex)
    Attach.AttachToPanel(ui, w.Bg)
    PanelSlot.SetAnchors(w.Bg, 0, 0, 1, 1)
    PanelSlot.SetOffsets(w.Bg, 0, 0, 0, 0)

    -- 标题
    w.Title = Text.Create(ui, "Title", "我的菜单", 36, 1, 1, 1, 1)
    Attach.AttachToPanel(ui, w.Title)
    PanelSlot.SetAnchors(w.Title, 0.5, 0, 0.5, 0)
    PanelSlot.SetAlignment(w.Title, 0.5, 0)
    PanelSlot.SetOffsets(w.Title, 0, 60, 400, 50)

    -- 开始按钮
    local btn_start_tex = LoadObjectByPath(configs["btn_start"])
    local btn_start_brush = Brush.CreateTextureImage(btn_start_tex, 1, 1, 1, 1)
    w.BtnStart = Button.Create(ui, "BtnStart", "开始游戏")
    Attach.AttachToPanel(ui, w.BtnStart)
    PanelSlot.SetAnchors(w.BtnStart, 0.5, 0.5, 0.5, 0.5)
    PanelSlot.SetAlignment(w.BtnStart, 0.5, 0.5)
    PanelSlot.SetOffsets(w.BtnStart, 0, 0, 300, 80)
    Button.SetNormalBrush(w.BtnStart, btn_start_brush)
    Button.SetHoveredBrush(w.BtnStart, btn_start_brush)
    Button.SetPressedBrush(w.BtnStart, btn_start_brush)

    return w
end

return M
```

---

## 十、自检清单

Agent 完成 UI 代码后，对照以下清单检查：

- [ ] 所有控件使用 `w.varName = Type.Create(...)` 格式
- [ ] 每个控件有且仅有一个 `Attach.AttachToPanel/ScrollBox` 调用
- [ ] 所有 PanelSlot 参数为数字字面量
- [ ] 所有资源路径在 UIConfigs 中注册，Layout 通过 `configs["key"]` 引用
- [ ] 无循环、无条件分支创建控件
- [ ] 无变量间接引用（资源/位置/尺寸）
- [ ] Layout 文件末尾 `return M`
- [ ] UIConfigs 文件末尾 `return Configs`
- [ ] Logic 文件使用 `class()` 风格，末尾 `return M`
- [ ] 回调函数名匹配 `On_{控件Name}_{事件名}` 格式
- [ ] Button 三态 Brush 均已设置（未启用 Hovered/Pressed 时继承 Normal）
- [ ] ScrollBox 内 Button 设置了 `SetTouchMethod(PreciseTap)`
- [ ] ProgressBar 仅使用颜色，未引用贴图路径
- [ ] 组件引用使用 `Layout_{Name}.Build(ui, parentVar)` 格式
- [ ] 引用的组件已有对应的 `WidgetLayout_Component_{Name}.lua` 文件

---

## 十一、UI 解耦与组件化

### 11.1 编辑器项目层级

编辑器采用三层管理结构：

| 层级 | 概念 | 文件对应 |
|------|------|----------|
| **工程** | 顶层容器，首页创建 | — |
| **界面** | 完整 UI 视图（三件套） | `WidgetLayout_XXX.lua` + `WidgetLogic_XXX.lua` + UIConfigs |
| **组件** | 可复用的 UI 片段 | 独立的 `WidgetLayout_Component_XXX.lua` |

### 11.2 组件定义

当界面复杂度较高时，可将子树拆为独立组件：

```lua
-- 组件 Layout：WidgetLayout_Component_ItemCard.lua
local Attach = require("UE_Package.LightGameLib.UI.Attach")
local PanelSlot = require("UE_Package.LightGameLib.UI.PanelSlot")
local Panel = require("UE_Package.LightGameLib.UI.Panel")

local UIConfigs = require("GameDataConfigs.UI.UIConfigs")
local configs = UIConfigs["ItemCard"] or {}

local M = {}

function M.Build(ui, parent)
    local w = {}

    w.Root = Panel.Create(ui, "ItemCard_Root")
    Attach.AttachToPanel(parent, w.Root)
    PanelSlot.SetAnchors(w.Root, 0, 0, 1, 1)
    PanelSlot.SetOffsets(w.Root, 0, 0, 0, 0)

    -- 组件内部控件...

    return w
end

return M
```

### 11.3 界面中引用组件

```lua
-- 头部 require
local Layout_ItemCard = require("LogicScripts.UI.WidgetLayout_Component_ItemCard")

-- Build 区域内
w.Card1 = Layout_ItemCard.Build(ui, w.Container)
PanelSlot.SetAnchors(w.Card1, 0, 0, 0, 0)
PanelSlot.SetAlignment(w.Card1, 0, 0)
PanelSlot.SetOffsets(w.Card1, 20, 20, 200, 150)
```
