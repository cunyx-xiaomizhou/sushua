# 引擎 UI 控件完整手册

> 本文档整理自 LightGameBox 引擎框架层的 UI 封装模块，列出所有控件的创建方式、属性接口、事件回调及使用说明。

---

## 通用基础（所有控件共享）

### Widget 通用操作模块
`local Widget = require("UE_Package.LightGameLib.UI.Widget")`

| 接口 | 说明 |
|------|------|
| `Widget.SetVisibility(w, Visibility)` | 设置可见性：Visible / Collapsed / Hidden / HitTestInvisible / SelfHitTestInvisible |
| `Widget.SetRenderOpacity(w, Opacity)` | 设置不透明度（0~1） |
| `Widget.SetRenderScale(w, SX, SY)` | 设置缩放 |
| `Widget.SetRenderTransformAngle(w, Angle)` | 设置旋转角度 |
| `Widget.SetRenderTransformPivot(w, PivotX, PivotY)` | 设置变换锚点 |
| `Widget.SetRenderTranslation(w, TX, TY)` | 设置渲染平移 |
| `Widget.SetRenderShear(w, ShearX, ShearY)` | 设置剪切变换 |
| `Widget.SetClipToBounds(w, bClip)` | 是否裁剪超出边界的子内容 |
| `Widget.SetIsEnabled(w, bEnabled)` | 设置是否启用（禁用时不响应输入） |
| `Widget.GetIsEnabled(w) → bool` | 获取启用状态 |
| `Widget.GetRenderOpacity(w) → number` | 获取不透明度 |
| `Widget.GetRenderTransformAngle(w) → number` | 获取旋转角度 |
| `Widget.FindChild(UI, Name) → UWidget` | 按名称查找子控件 |
| `Widget.GetChildrenCount(w) → int` | 获取子控件数量 |
| `Widget.ClearChildren(w)` | 清除所有子控件 |
| `Widget.RemoveFromParent(w)` | 从父容器移除 |
| `Widget.Destroy(w)` | 销毁控件 |

### PanelSlot 布局属性模块
`local PanelSlot = require("UE_Package.LightGameLib.UI.PanelSlot")`

| 接口 | 说明 |
|------|------|
| `PanelSlot.SetAnchors(w, MinX, MinY, MaxX, MaxY)` | 设置锚点。Min==Max 为单点模式（Position+Size），Min!=Max 为拉伸模式（边距） |
| `PanelSlot.SetAlignment(w, X, Y)` | 设置对齐点 Pivot（0=左/上, 0.5=中, 1=右/下） |
| `PanelSlot.SetOffsets(w, L, T, R, B)` | 单点模式: L=X, T=Y, R=Width, B=Height；拉伸模式: 距锚点区域各边的像素边距 |
| `PanelSlot.SetPosition(w, X, Y)` | 设置位置 |
| `PanelSlot.SetSize(w, W, H)` | 设置尺寸 |
| `PanelSlot.SetAutoSize(w, bAutoSize)` | 自动尺寸（由内容撑开） |
| `PanelSlot.SetZOrder(w, ZOrder)` | 设置层级（数值越大越靠前） |

### Attach 挂接模块
`local Attach = require("UE_Package.LightGameLib.UI.Attach")`

| 接口 | 说明 |
|------|------|
| `Attach.AttachToPanel(Parent, Child)` | 将子控件挂接到 Panel 或 OwnerWidget |
| `Attach.AttachToScrollBox(Parent, Child)` | 将子控件挂接到 ScrollBox |

### Brush 画刷构造模块
`local Brush = require("UE_Package.LightGameLib.UI.Brush")`

| 接口 | 说明 |
|------|------|
| `Brush.CreateColorImage(R,G,B,A)` | 纯色填充（普通拉伸） |
| `Brush.CreateColorBox(R,G,B,A, ML,MT,MR,MB)` | 纯色填充（九宫格拉伸，Margin 定义边框区域） |
| `Brush.CreateColorRoundedBox(R,G,B,A, TL,TR,BR,BL, OutW, OR,OG,OB,OA)` | 纯色圆角矩形（带描边） |
| `Brush.CreateTextureImage(Tex, R,G,B,A)` | 纹理填充（普通拉伸） |
| `Brush.CreateTextureBox(Tex, R,G,B,A, ML,MT,MR,MB)` | 纹理填充（九宫格拉伸） |
| `Brush.CreateTextureRoundedBox(Tex, R,G,B,A, TL,TR,BR,BL, OutW, OR,OG,OB,OA)` | 纹理圆角矩形 |

> **Brush 类型说明**：
> - **Image**：整图拉伸到控件大小
> - **Box**：九宫格（Margin 值 0~1 定义各边不拉伸区域）
> - **RoundedBox**：圆角矩形（每角独立圆角 + 描边）
> - RGBA 为染色/着色（1,1,1,1 = 原色）

---

## 控件详细说明

---

### 1. Image（图片控件）

`local Image = require("UE_Package.LightGameLib.UI.Image")`

**用途**：显示纹理图片、纯色矩形、圆角矩形

| 接口 | 说明 |
|------|------|
| `Image.Create(OwnerUI, Name, Texture)` | 创建图片控件 |
| `Image.CreateRoundedBox(OwnerUI, Name, R,G,B,A, TL,TR,BR,BL)` | 创建圆角矩形图片（纯色） |
| `Image.SetBrush(w, Brush)` | 设置画刷（支持九宫格/圆角/纹理等所有 Brush 类型） |
| `Image.SetImage(w, Texture)` | 更换纹理 |
| `Image.SetImageColor(w, R,G,B,A)` | 设置着色 |

**属性总结**：
- 纹理贴图（Texture2D 资源路径）
- 着色 RGBA
- 画刷类型（Image/Box/RoundedBox）
- 九宫格边距（通过 Brush 设定）
- 圆角半径（TL/TR/BR/BL）

---

### 2. Button（按钮控件）

`local Button = require("UE_Package.LightGameLib.UI.Button")`

**用途**：可点击按钮，内嵌文字，支持多状态外观

| 接口 | 说明 |
|------|------|
| `Button.Create(OwnerUI, Name, Text)` | 创建按钮（初始文字） |
| `Button.SetText(w, Text)` | 修改按钮文字 |
| `Button.SetTextColor(w, R,G,B,A)` | 文字颜色 |
| `Button.SetFontSize(w, FontSize)` | 文字大小 |
| `Button.SetTextOutline(w, Size, R,G,B,A)` | 文字描边（Size=0 无描边） |
| `Button.SetNormalBrush(w, Brush)` | 正常状态画刷 |
| `Button.SetHoveredBrush(w, Brush)` | 悬停状态画刷 |
| `Button.SetPressedBrush(w, Brush)` | 按下状态画刷 |
| `Button.SetDisabledBrush(w, Brush)` | 禁用状态画刷 |
| `Button.SetClickMethod(w, Method)` | 点击触发方式 |
| `Button.SetTouchMethod(w, Method)` | 触摸方式（ScrollBox 内需设为 PreciseTap） |

**属性总结**：
- 文字内容、颜色、字号、描边
- 4 种状态画刷（Normal/Hovered/Pressed/Disabled），每种支持所有 Brush 类型
- 点击/触摸方式

**事件回调**：`On_<Name>_OnClicked`、`On_<Name>_OnPressed`、`On_<Name>_OnReleased`

---

### 3. Text（文本控件）

`local Text = require("UE_Package.LightGameLib.UI.Text")`

**用途**：显示文本，支持自动换行、描边、对齐

| 接口 | 说明 |
|------|------|
| `Text.Create(OwnerUI, Name, Text, FontSize, R,G,B,A)` | 创建文本 |
| `Text.SetText(w, Text)` | 修改文本内容 |
| `Text.GetText(w) → string` | 获取文本内容 |
| `Text.SetTextColor(w, R,G,B,A)` | 文字颜色 |
| `Text.SetFontSize(w, FontSize)` | 字号 |
| `Text.SetOutline(w, Size, R,G,B,A)` | 描边 |
| `Text.SetJustification(w, Align)` | 水平对齐：Left / Center / Right |
| `Text.SetVerticalAlignment(w, VAlign)` | 垂直对齐：Top / Center / Bottom |
| `Text.SetAutoWrap(w, bAutoWrap)` | 是否自动换行 |
| `Text.MeasureText(Text, FontSize) → W, H` | 测量文本像素尺寸（静态方法） |

**属性总结**：
- 文本内容
- 颜色 RGBA、字号
- 描边大小 + 颜色
- 水平对齐、垂直对齐
- 是否自动换行

---

### 4. EditableTextBox（可编辑文本框）

`local EditableTextBox = require("UE_Package.LightGameLib.UI.EditableTextBox")`

**用途**：用户可输入文本的控件

| 接口 | 说明 |
|------|------|
| `EditableTextBox.Create(OwnerUI, Name, Placeholder)` | 创建，Placeholder 为提示文字 |
| `EditableTextBox.SetInputText(w, Text)` | 设置输入内容 |
| `EditableTextBox.GetInputText(w) → string` | 获取输入内容 |
| `EditableTextBox.SetHintText(w, HintText)` | 修改提示文字 |

**事件回调**：
- `On_<Name>_OnTextChanged` — 文字改变时
- `On_<Name>_OnTextCommitted` — 提交（回车/失焦）时

---

### 5. CheckBox（复选框）

`local CheckBox = require("UE_Package.LightGameLib.UI.CheckBox")`

**用途**：勾选/取消勾选

| 接口 | 说明 |
|------|------|
| `CheckBox.Create(OwnerUI, Name, bInitChecked)` | 创建 |
| `CheckBox.SetChecked(w, bChecked)` | 设置勾选状态 |
| `CheckBox.IsChecked(w) → bool` | 获取勾选状态 |

**事件回调**：`On_<Name>_OnCheckStateChanged`

---

### 6. Slider（滑条）

`local Slider = require("UE_Package.LightGameLib.UI.Slider")`

**用途**：数值滑动选择

| 接口 | 说明 |
|------|------|
| `Slider.Create(OwnerUI, Name, InitValue)` | 创建 |
| `Slider.SetValue(w, Value)` | 设置当前值 |
| `Slider.GetValue(w) → number` | 获取当前值 |
| `Slider.GetNormalizedValue(w) → number` | 获取归一化值 (0~1) |
| `Slider.SetMinValue(w, Min)` | 设置最小值 |
| `Slider.SetMaxValue(w, Max)` | 设置最大值 |
| `Slider.SetBarColor(w, R,G,B,A)` | 滑轨颜色 |
| `Slider.SetHandleColor(w, R,G,B,A)` | 滑块颜色 |
| `Slider.SetLocked(w, bLocked)` | 锁定（不可拖动） |

**事件回调**：`On_<Name>_OnValueChanged`

---

### 7. ProgressBar（进度条）

`local ProgressBar = require("UE_Package.LightGameLib.UI.ProgressBar")`

**用途**：显示进度/比例

| 接口 | 说明 |
|------|------|
| `ProgressBar.Create(OwnerUI, Name, Percent, FillR,G,B,A, BgR,G,B,A)` | 创建（填充色 + 背景色） |
| `ProgressBar.SetProgress(w, Percent)` | 设置进度（0~1） |
| `ProgressBar.SetFillColor(w, R,G,B,A)` | 修改填充色 |

**属性总结**：
- 进度值 (0~1)
- 填充色 RGBA
- 背景色 RGBA

---

### 8. FlipBook（帧动画控件）

`local FlipBook = require("UE_Package.LightGameLib.UI.FlipBook")`

**用途**：播放 PaperFlipbook 序列帧动画

| 接口 | 说明 |
|------|------|
| `FlipBook.Create(OwnerUI, Name, W, H, FlipBook, FPS, bLoop, bAutoPlay)` | 创建 |
| `FlipBook.SetFPS(w, FPS)` | 修改播放帧率 |
| `FlipBook.SetLooper(w, bLoop)` | 是否循环 |
| `FlipBook.SetPlayState(w, State)` | Play / Pause / Stop |
| `FlipBook.GetNumFrames(w) → int` | 获取总帧数 |

**属性总结**：
- FlipBook 资源路径
- 尺寸 W × H
- FPS 帧率
- 是否循环
- 是否自动播放

---

### 9. Particle（UI 粒子控件）

`local Particle = require("UE_Package.LightGameLib.UI.Particle")`

**用途**：在 UI 层播放粒子特效

| 接口 | 说明 |
|------|------|
| `Particle.Create(OwnerUI, Name, Template, bAutoActivate)` | 创建 |
| `Particle.SetActivate(w, bActive, bReset)` | 激活/停止 |
| `Particle.Reactivate(w, bReset)` | 重新激活 |
| `Particle.SetTemplate(w, Template)` | 更换粒子模板 |

**属性总结**：
- ParticleSystem 资源路径
- 是否自动激活

---

### 10. DrawCanvas（自定义绘制画布）

`local DrawCanvas = require("UE_Package.LightGameLib.UI.DrawCanvas")`

**用途**：程序化绘制任意图形（线段、矩形、圆形、弧形、多边形、渐变、图片、文字等）

| 接口 | 说明 |
|------|------|
| `DrawCanvas.Create(OwnerUI, Name, W, H)` | 创建画布 |
| `DrawCanvas.DrawLine(c, X1,Y1, X2,Y2, Thick, R,G,B,A, Layer)` | 画线 |
| `DrawCanvas.DrawBox(c, X,Y, W,H, R,G,B,A, Layer)` | 画矩形 |
| `DrawCanvas.DrawRoundedBox(c, X,Y,W,H, R,G,B,A, TL,TR,BR,BL, OR,OG,OB,OA, OutW, Layer)` | 画圆角矩形 |
| `DrawCanvas.DrawCircle(c, CX,CY, Radius, R,G,B,A, Layer, Segments)` | 画圆 |
| `DrawCanvas.DrawArc(c, CX,CY, Radius, Start,End, Thick, R,G,B,A, Layer, Segments)` | 画弧 |
| `DrawCanvas.DrawPolygon(c, PointsX, PointsY, R,G,B,A, Layer)` | 画多边形 |
| `DrawCanvas.DrawImage(c, X,Y, W,H, Tex, R,G,B,A, Layer)` | 画图片 |
| `DrawCanvas.DrawRotatedBox(c, X,Y, W,H, Angle, R,G,B,A, Layer)` | 旋转矩形 |
| `DrawCanvas.DrawRotatedImage(c, X,Y, W,H, Angle, Tex, R,G,B,A, Layer)` | 旋转图片 |
| `DrawCanvas.DrawGradient(c, X,Y, W,H, SR,SG,SB,SA, ER,EG,EB,EA, bH, Layer)` | 渐变 |
| `DrawCanvas.DrawSpline(c, SX,SY, SDX,SDY, EX,EY, EDX,EDY, Thick, R,G,B,A, Layer)` | 样条曲线 |
| `DrawCanvas.DrawText(c, X,Y, MaxW,MaxH, Text, Size, R,G,B,A, Layer, HAlign, VAlign)` | 画文字 |
| `DrawCanvas.DrawPostProcess(c, X,Y, W,H, Blur, Downsample, Layer)` | 后处理模糊 |
| `DrawCanvas.GetPaintSize(c) → W, H` | 获取画布尺寸 |
| `DrawCanvas.GetDrawCommandCount(c) → int` | 获取绘制命令数 |

**绘制回调**：逻辑脚本中定义 `On_<CanvasName>_OnPaint(Widget, Payload)` 方法

---

### 11. ScrollBox（滚动容器）

`local ScrollBox = require("UE_Package.LightGameLib.UI.ScrollBox")`

**用途**：可滚动的子控件容器

| 接口 | 说明 |
|------|------|
| `ScrollBox.Create(OwnerUI, Name, Direction)` | 创建（Orient_Horizontal / Orient_Vertical） |
| `ScrollBox.SetOrientation(w, Direction)` | 修改滚动方向 |
| `ScrollBox.SetScrollOffset(w, Offset)` | 设置滚动偏移 |
| `ScrollBox.GetScrollOffset(w) → number` | 获取当前偏移 |
| `ScrollBox.GetScrollOffsetOfEnd(w) → number` | 获取末尾偏移 |
| `ScrollBox.ScrollToStart(w)` | 滚到顶部/左侧 |
| `ScrollBox.ScrollToEnd(w)` | 滚到底部/右侧 |
| `ScrollBox.EndInertialScrolling(w)` | 停止惯性滚动 |
| `ScrollBox.SetAllowOverscroll(w, bAllow)` | 是否允许过度滚动 |
| `ScrollBox.SetScrollBarVisibility(w, Vis)` | 滚动条可见性 |

**属性总结**：
- 滚动方向（水平/垂直）
- 是否允许过度滚动
- 滚动条可见性

---

### 12. Panel（面板容器）

`local Panel = require("UE_Package.LightGameLib.UI.Panel")`

**用途**：子控件容器，支持自动排列/网格布局/对齐规则

| 接口 | 说明 |
|------|------|
| `Panel.Create(OwnerUI, Name)` | 创建面板 |
| `Panel.SetAutoArrangement(w, bAuto)` | 启用自动排布 |
| `Panel.SetLayoutType(w, Type)` | 排布方向：Horizontal / Vertical |
| `Panel.SetChildGap(w, Gap)` | 子控件间距 |
| `Panel.SetGridSlot(w, bGrid)` | 网格模式 |
| `Panel.SetHorizontalArrangement(w, Dir)` | 水平排列方向：LeftToRight / RightToLeft |
| `Panel.SetVerticalArrangement(w, Dir)` | 垂直排列方向：TopToBottom / BottomToTop |
| `Panel.SetSortRules(w, SortType)` | 对齐规则：LeftTop/LeftCenter/LeftBottom/CenterTop/CenterCenter/CenterBottom/RightTop/RightCenter/RightBottom |
| `Panel.SetSlotPadding(w, T,B,L,R)` | 内边距 |

**属性总结**：
- 是否自动排布
- 排布方向（水平/垂直）
- 子控件间距
- 是否网格模式
- 排列方向
- 对齐规则（9种）
- 内边距

---

### 13. SkillButton（技能按钮）

`local SkillButton = require("UE_Package.LightGameLib.UI.SkillButton")`

**用途**：绑定技能槽位的按钮，自动处理 CD 显示

| 接口 | 说明 |
|------|------|
| `SkillButton.Create(OwnerUI, Name, SkillSlot)` | 创建并绑定技能槽 |
| `SkillButton.SetSkillSlot(w, Slot)` | 修改绑定槽位 |
| `SkillButton.SetShowCD(w, bShow)` | 是否显示 CD 倒计时 |
| `SkillButton.SetShowName(w, bShow)` | 是否显示技能名称 |

---

### 14. Drag（拖拽模块）

`local Drag = require("UE_Package.LightGameLib.UI.Drag")`

**用途**：为控件添加拖拽和放置区能力

| 接口 | 说明 |
|------|------|
| `Drag.SetDragVisualMode(UI, Mode)` | 设置拖拽视觉模式（Exact/HoverOnly/Translucent） |
| `Drag.SetUIDraggable(UI, bDraggable)` | 设置整个 UI 可拖拽 |
| `Drag.SetUIDropZone(UI, bDropZone)` | 设置整个 UI 为放置区 |
| `Drag.SetWidgetDraggable(OwnerUI, Widget, bDraggable)` | 设置单个控件可拖拽 |
| `Drag.SetWidgetDropZone(OwnerUI, Widget, bDropZone)` | 设置单个控件为放置区 |

**视觉模式说明**：
- **Exact**：拖拽时完全复制原控件外观
- **HoverOnly**：仅在悬停时显示拖拽提示
- **Translucent**：拖拽时显示半透明副本

**事件回调**：
- `On_<Name>_OnDragDetected` — 开始拖拽
- `On_<Name>_OnDrop` — 放置到目标
- `On_<Name>_OnDragCancelled` — 拖拽取消

---

### 15. UIView（视图管理模块）

`local UIView = require("UE_Package.LightGameLib.UI.UIView")`

**用途**：UI 视图的创建、销毁、显示/隐藏及区域管理

| 接口 | 说明 |
|------|------|
| `UIView.Create(Name, LuaScriptPath[, ZOrder])` | 创建 UI 视图，返回 UI 句柄 |
| `UIView.Destroy(UI)` | 销毁视图 |
| `UIView.Show(UI[, ZOrder])` | 显示视图（可选指定层级） |
| `UIView.Hide(UI)` | 隐藏视图 |
| `UIView.SetRect(UI, X, Y, W, H)` | 设置视图显示区域（像素坐标） |

**属性总结**：
- 视图名称（唯一标识）
- Lua 脚本路径（逻辑脚本 class）
- ZOrder 层级
- 显示区域 (X, Y, W, H)

**典型用法**：
```lua
-- 创建并显示
local ui = UIView.Create("MyScreen", "LogicScripts.UI.WidgetLogic_MyScreen", 10)

-- 隐藏/显示
UIView.Hide(ui)
UIView.Show(ui, 20)

-- 设置区域
UIView.SetRect(ui, 100, 50, 800, 600)

-- 销毁
UIView.Destroy(ui)
```

---

## 编辑器对照差异总结

| 引擎有 / 编辑器缺 | 说明 |
|-------------------|------|
| **EditableTextBox** | 可编辑输入框（编辑器未实现） |
| **CheckBox** | 复选框（编辑器未实现） |
| **Slider** | 滑条（编辑器未实现） |
| **SkillButton** | 技能按钮（编辑器未实现） |
| **DrawCanvas** | 自定义绘制画布（编辑器未实现） |
| **Brush 体系** | 引擎的 Brush 是 Image/Box/RoundedBox 三种绘制模式 + 纹理/纯色，编辑器用 drawAs 近似但缺 RoundedBox |
| **Button 4 状态** | 引擎支持 Normal/Hovered/Pressed/Disabled 四种画刷，编辑器只做了 Normal/Hovered/Pressed |
| **Panel 自动排布** | 引擎的 Panel 支持 AutoArrangement + Grid + SortRules，编辑器的 HorizontalBox/VerticalBox/GridBox 是分开的控件 |

| 编辑器有 / 引擎无 | 说明 |
|-------------------|------|
| **ShapeRect / ShapeCircle / ShapeRoundedRect** | 引擎没有独立形状控件（通过 Image + Brush 实现等效） |
| **ScaleBox** | 引擎未提供独立 ScaleBox 模块（通过 Widget.SetRenderScale 实现） |
| **Overlay** | 引擎未提供独立 Overlay 模块（通过 Panel + ZOrder 实现） |
