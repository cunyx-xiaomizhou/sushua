# UI 组件设计规范

从实际 UI 实现中蒸馏的设计规范，指导 AI 生成符合规范的 UI 组件。

---

## 一、全局设计规范

### 1.1 层级结构标准
所有界面遵循统一的根层级:
```
CanvasPanel (Root, label="界面名")
├── 背景 (Panel, stretch全屏, label="背景")
│   └── 底图 (Image, stretch, label="底图")
└── 前景 (Panel, stretch, offsets 32px四边, label="前景")
    └── [内容区...]
```

### 1.2 间距体系
| 级别 | 像素值 | 用途 |
|------|--------|------|
| 页面边距 | 32px | 前景面板到屏幕边缘 |
| 区块间距 | 24px | 相邻区块之间 |
| 组件间距 | 12-16px | 同区块内相邻组件 |
| 紧凑间距 | 4-8px | 图标与文字、标签组内 |

### 1.3 字体大小体系
| 级别 | 像素值 | 用途 | 样式 |
|------|--------|------|------|
| 页面主标题 | 44px | 顶栏大标题 | color:#FFFFFF |
| 页面副标题 | 24px | 顶栏副标题 | color:#FFFFFF, opacity:0.32 |
| 区块标题 | 32-44px | 区块内标题 | color:#FFFFFF |
| 区块副标题 | 24-32px | 区块副标题 | color:#FFFFFF, opacity:0.64 |
| 正文/按钮文字 | 32px | 按钮、列表项 | color:#FFFFFF |
| 辅助文字 | 24px | 说明、数量、标签 | color:#FFFFFF, opacity:0.5-0.64 |
| 小标签 | 20px | 角标、极小提示 | - |

### 1.4 颜色约定
| 用途 | 颜色 |
|------|------|
| 主文字 | #FFFFFF |
| 弱文字 | #FFFFFF + opacity 0.32~0.64 |
| 按钮高亮 | #5BA0E9 |
| 按钮按下 | #3A80C9 |
| 按钮禁用 | #666666 |
| 强调色/装饰 | rgb(0,123,255) 或项目主题色 |
| 深色背景填充 | rgb(7,16,24) |
| 品质灰 | rgb(148,148,148) |

### 1.5 锚点定位模式
| 模式 | anchor | 说明 |
|------|--------|------|
| 全拉伸 | (0,0,1,1) | 填满父容器，用 offsets 控制边距 |
| 顶部固定高度 | (0,0,1,0) | 宽度拉伸，offsets.bottom=高度 |
| 底部固定高度 | (0,1,1,1) | alignment.y=1, offsets.bottom=高度 |
| 左侧固定宽度 | (0,0,0,1) | 高度拉伸，offsets.right=宽度 |
| 右侧固定宽度 | (1,0,1,1) | alignment.x=1, offsets.right=宽度 |
| 居中固定尺寸 | (0.5,0.5,0.5,0.5) | alignment(0.5,0.5), offsets(0,0,W,H) |
| 垂直居中固定高度 | (0,0.5,0,0.5) | alignment.y=0.5, offsets(0,0,W,H) |

---

## 二、标准组件模式

### 2.1 页面顶部信息栏
```
Panel "页面顶部信息栏" anchor(0,0,1,0) offsets(0,0,0,120)
├── HorizontalBox "左侧内容" anchor(0,0,0,1) offsets(0,0,1000,0) sortRules:"LeftCenter"
│   ├── Panel "页面图标" anchor(0,0,0,1) offsets(0,0,160,0) [可选,visibility:Collapsed]
│   │   └── Button "按钮底层" stretch
│   └── Panel "标题" anchor(0,0,1,1) offsets(leftPadding,0,0,0)
│       ├── Text "主标题" anchor(0,0,1,1) offsets(0,0,0,40) fontSize:44 vAlign:bottom
│       └── Text "副标题" anchor(0,0,1,1) offsets(0,80,0,0) fontSize:24 opacity:0.32 vAlign:top
└── Panel "关闭按钮" anchor(1,0,1,0) alignment(1,0) offsets(0,0,80,80)
    └── Button stretch
```

规则:
- 固定高度 120px
- 左侧内容用 HorizontalBox 自动排列
- 主标题 44px, 副标题 24px + opacity 0.32
- 无页面图标时设置 visibility: Collapsed 不占位

---

### 2.2 区块标题组
```
Panel "标题" anchor(0,0,1,0) offsets(0,0,0,64~120)
├── Image "装饰" anchor(0,0,0,1) offsets(0,0,2~4,0) backgroundColor:主题色
├── Text "主标题" anchor(0,0,0,1) offsets(10,0,宽度,0) fontSize:32~44 vAlign:middle/bottom
└── Text "副标题" anchor(0,0,0,1) offsets(主标题右侧,0,宽度,0) fontSize:24~32 opacity:0.64
```

规则:
- 装饰条: Image 无图片 + backgroundColor 纯色, 宽度 2-4px, 高度stretch
- 主标题紧挨装饰条右侧(间距10px)
- 副标题 opacity 0.64，常以 "/ " 前缀或小写英文

---

### 2.3 按钮组件

**有底图按钮**:
```
Panel "筛选按钮" offsets(0,0,240,80)
├── Button "按钮底层" stretch
│   normalPath: "贴图路径"
│   normalDrawAs: "box"
│   normalMargin: {0.3, 0.3, 0.3, 0.3}
│   opacity: 0.64(未激活) / 1.0(激活)
└── Text "标题" stretch fontSize:32 align:center vAlign:middle
```

**透明按钮** (纯点击区域):
```
Panel "按钮容器"
├── Button "按钮底层" stretch normalColor:"transparent"
└── Text/Image "内容" stretch
```

规则:
- Button 始终作为底层, 上层叠加 Text/Image
- enableHovered: false, enablePressed: false (默认)
- 激活态: 底图可见; 未激活态: visibility:Hidden

---

### 2.4 物品卡片
```
Panel "物品卡片" offsets(0,0,210,230)
├── Panel "图标区" anchor(0,0,1,0) offsets(0,0,0,比例高度)
│   ├── Image "品质背景" stretch backgroundColor:品质色
│   └── Image "预览图" anchor(0.5,0.5,0.5,0.5) alignment(0.5,0.5) offsets(0,0,200,200)
└── Panel "信息区" anchor(0,下方,1,1)
    ├── Text "名称" fontSize:24 顶部
    └── Panel "底部" anchor(0,1,1,1) alignment(0,1) offsets(0,0,0,44)
        ├── Image "状态图标" 30x30
        └── Text "数量" 右对齐 fontSize:24
```

规则:
- 卡片固定尺寸 (~210x230)
- 品质用 backgroundColor 区分
- 预览图居中, 数量角标右下

---

### 2.5 筛选/Tab 栏
```
Panel "类型筛选" anchor(0,0,1,0) offsets(0,0,0,80)
└── [含多个筛选按钮]
    ├── Panel "筛选按钮" [激活态] — 底图可见 opacity:0.64
    ├── Panel "筛选按钮" [未激活态] — 底图 Hidden
    └── ...
```

规则:
- 每个 Tab 固定宽度和高度
- 垂直居中 anchor(0, 0.5, 0, 0.5)

---

### 2.6 下拉选择器
```
Panel "品质筛选" anchor(0,1,1,1) alignment(0,1) offsets(0,0,0,80)
├── Button "按钮底层" stretch normalColor:transparent
├── Text "标题" fontSize:32 opacity:0.5 center middle
└── Image "下拉图标" anchor(1,0.5) alignment(0.5,0.5) rotation:180
```

---

### 2.7 大型预览区
```
Panel "主体" anchor(0,0,1,1) offsets(0,topOffset,0,0)
├── Panel "预览图" anchor(0.5,0.5,0.5,0.5) alignment(0.5,0.5) offsets(0,0,W,H)
│   └── Image stretch
└── Panel "卡槽" anchor(0.5,0.5,0.5,0.5) [绝对偏移定位]
    ├── Panel "标题"
    └── Panel "预览" clipping:true
```

规则:
- 预览区居中, 使用 anchor(0.5,0.5) + alignment(0.5,0.5)
- 溢出裁剪: clipping:true

---

### 2.8 属性列表
```
Panel "属性项" anchor(0,0,1,0) offsets(0,Y,0,40~48)
├── Text "属性名" anchor(0,0,0.5,1) left middle fontSize:28
└── Text "属性值" anchor(0.5,0,1,1) right middle fontSize:28
```

---

### 2.9 关闭/返回按钮
```
Panel "关闭按钮" anchor(1,0,1,0) alignment(1,0) offsets(0,0,80,80)
└── Button stretch normalPath:"关闭图标"
```

---

## 三、布局组合模式

### 3.1 左右结构
```
Panel "主内容" anchor(0,0,1,1) offsets(0,顶栏高,0,0)
├── Panel "左侧" anchor(0,0,0,1) offsets(0,0,左宽,0)
└── Panel "右侧" anchor(0,0,1,1) offsets(左宽+间距,0,0,0)
```

### 3.2 左中右结构
```
Panel "主内容" anchor(0,0,1,1) offsets(0,顶栏高,0,0)
├── Panel "左侧" anchor(0,0,0,1) offsets(0,0,W1,0)
├── Panel "中间" anchor(0,0,1,1) offsets(W1,0,W2,0)
└── Panel "右侧" anchor(1,0,1,1) alignment(1,0) offsets(0,0,W2,0)
```

---

## 四、命名约定

| 组件类型 | 命名方式 | 示例 |
|----------|----------|------|
| 区域容器 | 功能描述 | "页面顶部信息栏", "主内容", "左侧" |
| 功能容器 | 具体功能 | "筛选按钮", "物品卡片", "品质筛选" |
| 标题组 | "标题" | "标题" |
| 文本 | 内容角色 | "主标题", "副标题", "属性名", "数量" |
| 装饰 | "装饰" | "装饰" |
| 按钮底层 | "按钮底层" | "按钮底层" |
| 图片 | 内容描述 | "底图", "品质背景", "预览图" |

---

## 五、设计示例

### 合成系统（左右结构）
```
CanvasPanel "合成界面"
├── Panel "背景" stretch
│   └── Image "底图" stretch backgroundColor:"rgb(7,16,24)"
└── Panel "前景" stretch offsets(32,32,32,32)
    ├── Panel "页面顶部信息栏" anchor(0,0,1,0) offsets(0,0,0,120)
    │   ├── HBox "左侧内容"
    │   │   └── Panel "标题"
    │   │       ├── Text "主标题" "合成系统" 44px
    │   │       └── Text "副标题" "Crafting" 24px opacity:0.32
    │   └── Panel "关闭按钮" 右上角80x80
    └── Panel "主内容" anchor(0,0,1,1) offsets(0,160,0,0)
        ├── Panel "左侧-配方列表" anchor(0,0,0,1) offsets(0,0,480,0)
        │   ├── Panel "标题" — 装饰+主标题+副标题
        │   ├── Panel "分类Tab" — 多个筛选按钮
        │   └── ScrollBox "配方列表" — 物品卡片网格
        └── Panel "右侧-配方详情" anchor(0,0,1,1) offsets(504,0,0,0)
            ├── Panel "标题" — 物品名+品质
            ├── Panel "预览区" — 居中大图
            ├── Panel "材料需求" — 属性列表
            └── Panel "合成按钮" anchor(0.5,1) alignment(0.5,1) offsets(0,0,320,80)
```
