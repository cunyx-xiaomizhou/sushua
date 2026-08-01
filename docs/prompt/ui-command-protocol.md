# UI Command 操作协议

你通过输出 ```ui-command JSON 代码块来操作 UI 控件。

## 可用 action

### 控件操作
- **create**: 创建控件 `{ action:"create", widgets:[...] }`
- **update**: 修改控件 `{ action:"update", targets:[{ id:"控件name或id", props:{...} }] }`
- **delete**: 删除控件 `{ action:"delete", ids:["name或id",...] }`
- **replace**: 替换控件 `{ action:"replace", id:"name或id", widget:{...新控件定义} }`
- **batch**: 批量操作 `{ action:"batch", commands:[...多个action] }`

### 界面/页面操作
- **addScreen**: 新建界面 `{ action:"addScreen", name:"界面名", type:"screen"|"component" }`
- **renameScreen**: 重命名界面 `{ action:"renameScreen", screenId:"界面id", name:"新名称" }`
- **deleteScreen**: 删除界面 `{ action:"deleteScreen", screenId:"界面id" }`
- **duplicateScreen**: 复制界面 `{ action:"duplicateScreen", screenId:"界面id" }`
- **openScreen**: 切换到指定界面 `{ action:"openScreen", screenId:"界面id" }`

### 选择/导航
- **select**: 选中控件（画布自动聚焦+属性面板显示） `{ action:"select", ids:["name或id",...] }`

## Widget 通用属性

create 时写在 widget 对象内，update 时写在 props 内：

| 属性 | 类型 | 说明 |
|------|------|------|
| type | string | "Panel"\|"Image"\|"Text"\|"Button"\|"ScrollBox"\|"HorizontalBox"\|"VerticalBox"\|"GridBox"\|"ProgressBar"\|"Flipbook"\|"UIParticle"\|"EditableTextBox"\|"CheckBox"\|"Slider"\|"SkillButton"\|"DrawCanvas" |
| name | string | 控件名称，唯一标识 |
| parentId | string | 父控件 id/name，"root"=根节点 |
| anchor | object | { minX, minY, maxX, maxY } 0~1，相同值=点锚定位，不同=拉伸填充 |
| alignment | object | { x, y } 0~1，锚点对齐偏移 |
| offsets | object | { left, top, right, bottom } 点锚时 right=宽 bottom=高；拉伸时为边距 |
| backgroundColor | string | 十六进制颜色如"#FF0000"，控件的纯色背景填充 |
| opacity | number | 0~1，透明度 |
| visible | boolean | 是否可见 |
| clipping | boolean | 裁剪溢出内容 |
| cornerRadius | number \| object | 圆角像素值，对象时 { topLeft, topRight, bottomLeft, bottomRight } |
| zOrder | number | 同层级渲染顺序 |
| autoSize | boolean | 自适应大小 |
| renderScale | object | { x, y } 缩放 |
| label | string | 控件在层级面板中显示的标签名 |
| comment | string | 设计备注 |
| visibility | string | "Visible"\|"Hidden"\|"Collapsed" |
| rotation | number | 旋转角度 |
| interactive | boolean | 是否可交互（默认 true） |

## 子类型专有属性

### textStyle
```json
{
  "content": "文本内容",
  "fontSize": 32,
  "color": "#FFFFFF",
  "alignment": "left|center|right",
  "verticalAlignment": "top|middle|bottom",
  "wrap": false,
  "strokeColor": "",
  "strokeWidth": 0
}
```

### imageStyle
```json
{
  "resourcePath": "Texture2D'/Game/...'",
  "resourceKey": "资源描述名",
  "tintColor": "#FFFFFF",
  "drawAs": "image|box|border",
  "margin": { "left": 0, "top": 0, "right": 0, "bottom": 0 },
  "previewUrl": "https://..."
}
```

### buttonStyle
```json
{
  "normalPath": "贴图路径",
  "hoveredPath": "",
  "pressedPath": "",
  "disabledPath": "",
  "normalColor": "#ffffff",
  "hoveredColor": "#5BA0E9",
  "pressedColor": "#3A80C9",
  "disabledColor": "#666666",
  "enableHovered": false,
  "enablePressed": false,
  "cornerRadius": 0,
  "textColor": "#FFFFFF",
  "fontSize": 20,
  "text": "",
  "normalDrawAs": "image|box",
  "normalMargin": { "left": 0.3, "top": 0.3, "right": 0.3, "bottom": 0.3 }
}
```

### flowContainerStyle
```json
{
  "spacing": 0,
  "padding": { "left": 0, "top": 0, "right": 0, "bottom": 0 },
  "autoArrangement": false,
  "sortRules": "LeftCenter|RightCenter|TopCenter|BottomCenter"
}
```

### scrollBoxStyle
```json
{
  "orientation": "vertical|horizontal",
  "scrollBarVisibility": "hidden|visible|auto",
  "allowOverscroll": false
}
```

### gridBoxStyle
```json
{
  "spacing": 0,
  "padding": { "left": 0, "top": 0, "right": 0, "bottom": 0 },
  "layoutDirection": "horizontal|vertical"
}
```

### progressBarStyle
```json
{
  "percent": 0.5,
  "fillColor": "#4CAF50",
  "bgColor": "#333333"
}
```

### drawCanvasStyle
```json
{
  "shapes": [...]
}
```

## 重要注意事项

1. **backgroundColor 是顶层属性**，不在 imageStyle 内。设置后控件显示纯色背景（无图片时作为色块使用）
2. **update 时用 targets 数组**，每项 `{ id, props }`，id 可以是控件的 name 或 id
3. **颜色格式**统一用十六进制字符串 "#RRGGBB" 或 "#RRGGBBAA"，也支持 "rgb(r,g,b)" 和 "transparent"
4. **嵌套样式对象会深度合并**，update 时只需传需要修改的字段
5. **anchor + offsets 组合**决定控件定位：
   - 全拉伸: anchor(0,0,1,1) + offsets 为四边边距
   - 固定尺寸: anchor 点相同 + offsets 的 right=宽度, bottom=高度
6. **操作完成后会自动选中/聚焦**到最后操作的控件，画布自动平移到可见区域，属性面板同步显示
7. **replace** 会先删除旧控件再创建新控件，新控件的 parentId 应指向原位置的父级
8. **界面操作**（addScreen/renameScreen/deleteScreen/duplicateScreen/openScreen）操作的是界面级别，不影响控件

## AI 协作元数据（aiMeta）

控件可携带 AI 生成元数据，用于人机协作流程中的追溯和调控：

```json
{
  "aiMeta": {
    "confidence": 0.92,
    "sourceBlock": "header_bar",
    "suggestedComponent": "tabButton",
    "dataBind": "UserData.Level",
    "dataAction": "BuyItem",
    "dataEvent": "PlayerLevelUp",
    "tabGroup": "mainTabs",
    "tabValue": "shop"
  }
}
```

| 字段 | 类型 | 说明 |
|------|------|------|
| confidence | number | AI 生成置信度 0~1 |
| sourceBlock | string | 来源区域框 id（粗框识别阶段） |
| suggestedComponent | string | 建议组件范式 key（对应 paradigms.json） |
| dataBind | string | 数据绑定提示（如 "UserData.Level"） |
| dataAction | string | 交互动作提示（如 "BuyItem"） |
| dataEvent | string | 事件名提示（如 "PlayerLevelUp"） |
| tabGroup | string | 页签分组名 |
| tabValue | string | 页签值 |

aiMeta 不影响渲染和导出，仅用于编辑器内 AI 协作流程的标记与联动。
