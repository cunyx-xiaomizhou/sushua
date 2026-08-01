<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="__SITE_DESCRIPTION__">
<meta name="keywords" content="__SITE_KEYWORDS__">
<title>__SITE_NAME__</title>
__SITE_FAVICON_TAG__
<link rel="stylesheet" href="__PUBLIC_URL__/assets/app-shell.css">
__CUSTOM_HEAD__
<script src="__PUBLIC_URL__/assets/vendor/vue.global.prod.js"></script>
<script src="__PUBLIC_URL__/assets/vendor/qrcode-generator.min.js"></script>
<script src="__PUBLIC_URL__/assets/vendor/qqapi.js"></script>
<style>
.hero-card.side .hero-summary,.section-stack,.landing-quick-links,.home-product-stats{display:grid;gap:14px}
.hero-side-item,.home-link-card,.theme-card{padding:14px 16px;border-radius:18px;background:var(--surface);border:1px solid var(--line)}
.hero-side-item strong,.home-kpi strong,.side-profile-card strong{display:block;font-size:22px;margin-top:6px}
.home-actions,.landing-group-actions,.theme-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:22px}
.section-gap{margin-top:18px}
.card-title{display:flex;justify-content:space-between;gap:12px;align-items:flex-start}
.card-title h3,.card-title h4{margin:0}
.product-meta{display:grid;gap:8px;margin-top:10px}
.product-meta .subtle{display:flex;justify-content:space-between;gap:12px;align-items:center}
.price-line span{color:var(--muted)}
.order-summary-box{padding:18px;border-radius:20px;background:var(--surface-soft);border:1px solid var(--line)}
.order-summary-box h4{margin:0 0 10px}
.order-summary-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.order-summary-grid .subtle strong{display:block;margin-top:4px;font-size:18px}
.side-profile{display:grid;gap:14px}
.side-profile-card{padding:14px;border-radius:18px;background:var(--surface-soft);border:1px solid var(--line)}
.search-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.search-row .field{flex:1 1 220px}
.link-btn{border:0;background:transparent;color:var(--primary);font-weight:800;padding:0}
.table .actions-cell{display:flex;gap:8px;flex-wrap:wrap}
.code-box{padding:12px 14px;border-radius:16px;background:color-mix(in srgb,var(--text) 92%, var(--surface));color:color-mix(in srgb,var(--surface) 92%, var(--text));font-family:ui-monospace,SFMono-Regular,Consolas,monospace;word-break:break-all}
.code-inline{font-family:ui-monospace,SFMono-Regular,Consolas,monospace;background:var(--surface-soft);color:var(--primary);padding:3px 8px;border-radius:999px;word-break:break-all;overflow-wrap:anywhere;display:inline-block;max-width:100%}
.split-equal,.info-grid,.landing-metrics,.theme-grid,.home-hero-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
.placeholder-card{padding:24px;border-radius:22px;border:1px dashed var(--line);background:var(--surface-soft);color:var(--muted)}
.pay-actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-top:14px}
.feed-head{display:flex;justify-content:space-between;gap:10px;align-items:flex-start}
.feed-content{white-space:pre-wrap;word-break:break-word}
.feed-note{margin-top:10px;font-size:12px;color:var(--warning);background:color-mix(in srgb, var(--warning) 12%, var(--surface));border:1px solid color-mix(in srgb, var(--warning) 28%, var(--line));padding:10px 12px;border-radius:14px}
.feed-modal-grid{display:grid;gap:14px;max-height:min(70vh,820px);overflow:auto;padding-right:4px}
.modal-body-scroll{max-height:min(72vh,860px);overflow:auto;padding-right:4px}
.info-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
.small-stat-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.small-stat,.home-kpi{padding:16px;border-radius:18px;background:var(--surface);border:1px solid var(--line)}
.small-stat small,.home-kpi small{color:var(--muted)}
.pill-nav{display:flex;gap:10px;flex-wrap:wrap}
.kv-box{padding:14px 16px;border-radius:18px;background:var(--surface-soft);border:1px solid var(--line)}
.pre-wrap,.text-break{white-space:pre-wrap;word-break:break-word;overflow-wrap:anywhere}
.inline-avatar{display:inline-flex;align-items:center;gap:10px}
.inline-avatar img{width:32px;height:32px;border-radius:50%;border:1px solid var(--line)}
.auth-footnote{margin-top:12px;color:var(--muted);font-size:12px}
.table .compact{font-size:12px;color:var(--muted)}
.desktop-only-hint{font-size:12px;color:var(--muted);text-align:center;margin-top:10px}
.switch-inline{display:flex;gap:16px;flex-wrap:wrap;align-items:center}
.switch-inline label{display:inline-flex;align-items:center;gap:8px;font-weight:700;color:var(--muted)}
.field .qq-preview{margin-top:8px}
.order-qq-row{display:flex;align-items:center;gap:12px}
.order-qq-row input{flex:1;min-width:0}
.order-qq-avatar{display:flex;align-items:center;gap:8px;flex:0 0 auto}
.order-qq-avatar img{width:46px;height:46px;border-radius:50%;border:1px solid var(--line);background:var(--avatar-bg);object-fit:cover}
.order-qq-avatar .tiny{max-width:150px}
.note-strong{font-weight:900;color:var(--tip-text);font-size:14px;line-height:1.8}
.landing-intro{display:grid;gap:16px}
.landing-quick-links{grid-template-columns:repeat(2,minmax(0,1fr))}
.landing-group-card{padding:18px;border-radius:22px;background:var(--surface-soft);border:1px solid var(--line)}
.landing-group-code{font-size:24px;font-weight:900;color:var(--primary)}
.record-links{display:flex;flex-direction:column;gap:8px;align-items:center;text-align:center;margin-top:10px}
.record-links a{color:var(--primary);font-weight:800;text-decoration:none}
.record-links a:hover{text-decoration:underline}
.home-metric-table .table{min-width:640px}
.login-shell{max-width:720px;margin:0 auto}
.login-dual-actions{display:flex;gap:12px;flex-wrap:wrap}
.product-summary-card{padding:18px;border-radius:20px;background:var(--surface-soft);border:1px solid var(--line)}
.product-summary-card h4{margin:0 0 10px}
.theme-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
.theme-card input[type="color"]{width:100%;height:42px;padding:0;border:1px solid var(--line);border-radius:12px;background:var(--input-bg)}
.theme-card .tiny{margin-top:8px}
.mono-wrap{word-break:break-all;overflow-wrap:anywhere}
.home-link-card h4{margin:0 0 8px}
.home-link-card p{margin:0;color:var(--muted)}
@media (max-width:860px){.split-equal,.info-grid,.small-stat-grid,.order-summary-grid,.landing-metrics,.landing-quick-links,.home-hero-grid,.theme-grid{grid-template-columns:1fr}.home-actions,.landing-group-actions,.login-dual-actions,.theme-actions{flex-direction:column;align-items:stretch}.pay-actions{justify-content:stretch}}
@media (max-width:560px){.order-qq-avatar .tiny{display:none}}
</style>
</head>
<body>
<div id="app" class="app-shell" v-cloak>
  <header class="main-header">
    <div class="inner">
      <div class="brand">__SITE_BRAND__</div>
      <div class="header-actions">
        <template v-if="user">
          <div class="top-user">
            <img class="avatar" :src="qqAvatar(user.qq)" alt="avatar">
            <div>
              <div><strong>{{ displayName(user) }}</strong></div>
              <div class="tiny">{{ roleLabel(user) }}</div>
            </div>
          </div>
          <a v-if="routeMode !== 'user'" class="btn ghost" :href="routeUrl('/user')">鐢ㄦ埛鍚庡彴</a>
          <a v-if="canAccessAdmin && routeMode !== 'admin'" class="btn ghost" :href="routeUrl(adminUrl)">绠＄悊鍚庡彴</a>
          <button class="btn" @click="logout">閫€鍑虹櫥褰?/button>
        </template>
        <template v-else>
          <a v-if="routeMode !== 'home'" class="btn ghost" :href="routeUrl('/')">杩斿洖棣栭〉</a>
          <a v-if="routeMode !== 'login'" class="btn ghost" :href="routeUrl('/login')">鐧诲綍</a>
          <a v-if="routeMode !== 'register'" class="btn primary" :href="routeUrl('/register')">娉ㄥ唽</a>
        </template>
      </div>
    </div>
  </header>

  <main class="shell">
锘?section v-if="routeMode === 'home'">

<!-- Template: default -->
<template v-if="(settings.home_template || 'default') === 'default'">
<div class="hero">
  <div class="hero-card primary">
    <h1>{{ site.name }}</h1>
    <p>涓撴敞浜庣ǔ瀹氫笅鍗曘€佸疄鏃舵煡鍗曘€佷唬鐞嗗敭鍗栦笌棰濆害鍏呭€间綋楠屻€傛棤璁轰綘鏄嚜宸变娇鐢紝杩樻槸鍑嗗鎼缓鍞崠娓犻亾锛岃繖閲岄兘鑳界洿鎺ュ紑宸ャ€?/p>
    <div class="home-actions">
      <a v-if="!user" class="btn primary" :href="routeUrl('/login')">绔嬪嵆鐧诲綍</a>
      <a v-if="!user" class="btn ghost" :href="routeUrl('/register')">娉ㄥ唽璐﹀彿</a>
      <a v-if="user" class="btn primary" :href="routeUrl('/user')">杩涘叆鐢ㄦ埛鍚庡彴</a>
      <a v-if="canAccessAdmin" class="btn ghost" :href="routeUrl(adminUrl)">杩涘叆绠＄悊鍚庡彴</a>
      <a class="btn ghost" href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener">鎺ュ彛鏂囨。</a>
    </div>
  </div>
  <div class="hero-card side">
    <div class="hero-summary">
      <div class="hero-side-item"><small class="muted">鍟嗗搧鎬绘暟</small><strong>{{ homeStats.product_count || 0 }}</strong></div>
      <div class="hero-side-item"><small class="muted">璁㈠崟鎬绘暟</small><strong>{{ homeStats.order_count || 0 }}</strong></div>
      <div class="hero-side-item"><small class="muted">鎬讳笅鍗曟暟</small><strong>{{ money(homeStats.total_quantity || 0) }}</strong></div>
    </div>
  </div>
</div>

<div class="grid-2 section-gap">
  <div class="panel landing-intro">
    <div class="page-head" style="margin-bottom:0"><div><h2>绯荤粺浠嬬粛</h2><p>闈㈠悜鐢ㄦ埛鐨勯€熷埛鏈嶅姟闈㈡澘锛屾敮鎸佸湪绾夸笅鍗曘€侀搴﹀厖鍊笺€侀個璇锋帹骞裤€佷唬鐞嗗崌绾т笌鎺ュ彛鎺ュ叆銆?/p></div></div>
    <div class="landing-quick-links">
      <a class="home-link-card" :href="routeUrl('/user')"><h4>涓嬪崟鎺у埗鍙?/h4><p>鐧诲綍鍚庡嵆鍙繘鍏ョ敤鎴峰悗鍙帮紝杩涜鍦ㄧ嚎涓嬪崟銆佹煡鍗曘€佸厖鍊间笌閭€璇风爜绠＄悊銆?/p></a>
      <a class="home-link-card" :href="routeUrl('/login')"><h4>缁熶竴鐧诲綍</h4><p>鏅€氱敤鎴枫€佺鐞嗗憳銆佺珯闀跨粺涓€浣跨敤鍚屼竴鐧诲綍椤碉紝鐧诲綍鏃跺己鍒舵牎楠屽浘鐗囬獙璇佺爜銆?/p></a>
      <a class="home-link-card" :href="routeUrl('/register')"><h4>蹇€熸敞鍐?/h4><p>娉ㄥ唽榛樿鏀堕泦鐢ㄦ埛鍚嶃€佹樀绉般€丵Q 涓庡瘑鐮侊紝鍙寜鍚庡彴绛栫暐鎵╁睍閭鎴栨墜鏈哄彿銆?/p></a>
      <a class="home-link-card" href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener"><h4>鎺ュ彛鏂囨。</h4><p>瀵规帴鍓嶈鍏堥槄璇绘帴鍙ｆ枃妗ｏ紝纭鎵€闇€鍙傛暟銆佽繑鍥炴牸寮忎笌涓氬姟瑙勫垯銆?/p></a>
    </div>
  </div>
  <div class="panel">
    <div class="page-head" style="margin-bottom:0"><div><h2>{{ canShowSupportGroup ? '浜ゆ祦缇や笌鍞悗缇? : '鐢ㄦ埛浜ゆ祦缇? }}</h2><p>缇ゅ彿鍙湪鍚庡彴瀹炴椂閰嶇疆銆傝嫢浣犲湪 QQ 鍐呮墦寮€锛屽皢浼樺厛灏濊瘯璋冭捣 QQ 缇ゅ悕鐗囥€?/p></div></div>
    <div class="landing-metrics section-gap">
      <div class="landing-group-card"><small class="muted">鐢ㄦ埛浜ゆ祦缇?/small><div class="landing-group-code">{{ settings.community_group_qq || '鏈厤缃? }}</div><div class="landing-group-actions"><button class="btn primary" @click="openGroup('community')">鍔犲叆浜ゆ祦缇?/button></div></div>
      <div v-if="canShowSupportGroup" class="landing-group-card"><small class="muted">鍞悗 / 鏀寔缇?/small><div class="landing-group-code">{{ settings.support_group_qq || '鏈厤缃? }}</div><div class="landing-group-actions"><button class="btn primary" @click="openGroup('support')">鍔犲叆鍞悗缇?/button></div></div>
    </div>
    <div class="auth-footnote">濡傛灉褰撳墠璁惧鏃犳硶鐩存帴鎷夎捣 QQ锛屽彲澶嶅埗缇ゅ彿鍒?QQ 鍐呮悳绱㈠姞鍏ャ€?/div>
  </div>
</div>

<div class="record-links section-gap" v-if="settings.icp_beian_no || settings.public_security_beian_no">
  <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
  <div v-if="settings.public_security_beian_no" class="muted">缃戝畨澶囨锛歿{ settings.public_security_beian_no }}</div>
</div>

<div class="stats-grid section-gap">
  <div class="stat"><small>鍟嗗搧鎬绘暟</small><strong>{{ homeStats.product_count || 0 }}</strong></div>
  <div class="stat"><small>璁㈠崟鎬绘暟</small><strong>{{ homeStats.order_count || 0 }}</strong></div>
  <div class="stat"><small>鎬讳笅鍗曟暟</small><strong>{{ money(homeStats.total_quantity || 0) }}</strong></div>
  <div class="stat"><small>鎺ュ彛瀵规帴</small><strong>{{ boolText(settings.api_order_enabled) }}</strong></div>
</div>

<div class="panel section-gap">
  <div class="page-head"><div><h2>鍟嗗搧璁㈠崟鏁版嵁</h2><p>浠呭睍绀哄凡鏈夎鍗曡褰曠殑鍟嗗搧锛屽府鍔╀綘蹇€熷垽鏂綋鍓嶇儹闂ㄥ晢鍝佸拰澶勭悊鏁堢巼銆?/p></div></div>
  <div v-if="homeStats.items && homeStats.items.length" class="table-wrap home-metric-table">
    <table class="table"><thead><tr><th>鍟嗗搧鍚嶇О</th><th>璁㈠崟鎬绘暟</th><th>涓嬪崟鎬绘暟</th><th>骞冲潎澶勭悊閫熷害锛堟瘡灏忔椂锛?/th></tr></thead>
    <tbody><tr v-for="item in homeStats.items" :key="item.id"><td>{{ item.name }}</td><td>{{ item.order_count }}</td><td>{{ money(item.total_quantity) }}</td><td>{{ item.avg_speed_per_hour === null ? '-' : item.avg_speed_per_hour }}</td></tr></tbody></table>
  </div>
  <div v-else class="empty">褰撳墠杩樻病鏈変骇鐢熻鍗曠殑鍟嗗搧鏁版嵁銆?/div>
</div>

<div class="panel section-gap">
  <div class="page-head"><div><h2>涓轰粈涔堥€夋嫨鎴戜滑鐨勬湇鍔?/h2><p>涓嬮潰杩欏叚鐐癸紝灏辨槸杩欎釜绯荤粺鏈€鐩磋銆佹渶瀹规槗琚劅鍙楀埌鐨勪綋楠屼紭鍔裤€?/p></div></div>
  <div class="feature-grid">
    <div class="feature-card"><h3>绉掗€熷埌璐?/h3><p>涓嬪崟鍚庣珛鍗宠繘鍏ラ槦鍒楋紝鏃犻渶婕暱绛夊緟锛屼笅鍗曠鍒枫€?/p></div>
    <div class="feature-card"><h3>瀹夊叏绋冲畾</h3><p>鎵€鏈夋暟鎹潎鏉ユ簮浜庣湡瀹炵敤鎴风殑鍑瘉锛岄潪鏈哄櫒鍒烽噺锛屽彲闇稿崰浜烘皵鎺掕姒溿€?/p></div>
    <div class="feature-card"><h3>渚垮疁瀹炴儬</h3><p>褰撳墠浠锋牸杩滆繙浣庝簬鍏ㄧ綉鍚岃锛岀粰浣犳渶绋冲畾鏈€鑸掑績鐨勪綋楠屻€?/p></div>
    <div class="feature-card"><h3>绀剧兢鏀寔</h3><p>鐩稿簲绀剧兢 7脳24 灏忔椂鍏ㄥぉ鍊欏紑鏀撅紝闅忔椂瑙ｅ喅浣犵殑闂鍜岄渶姹傘€?/p></div>
    <div class="feature-card"><h3>鍗″瘑瀵规帴</h3><p>鎴戜滑寮€鏀惧崱瀵嗕笅鍗曞姛鑳斤紝璁╀綘鍙互鍦ㄥ悇绫诲彂鍗″钩鍙颁笂杩涜鍞崠鍟嗗搧锛屼笖鏀寔鐢ㄦ埛鑷姪鍏戞崲涓嬪崟銆?/p></div>
    <div class="feature-card"><h3>API瀵规帴</h3><p>鎴戜滑寮€鏀?API 涓嬪崟鎺ュ彛锛岃浣犲鎺ヤ綘鑷繁鐨勬湇鍔¤繘琛屽敭鍗栬禋閽便€?/p></div>
  </div>
</div>
</template>

<!-- Template: modern -->
<template v-if="settings.home_template === 'modern'">
<div class="tpl-modern">
  <div class="modern-hero">
    <div class="modern-hero-content">
      <div class="modern-badge">涓撲笟閫熷埛骞冲彴</div>
      <h1 class="modern-title">{{ site.name }}</h1>
      <p class="modern-desc">楂樻晥绋冲畾鐨勫湪绾夸笅鍗曞钩鍙帮紝鏀寔澶氱鍟嗗搧绫诲瀷锛屽疄鏃舵煡鍗曪紝绉掗€熷埌璐︺€?/p>
      <div class="modern-actions">
        <a v-if="!user" class="btn modern-btn-primary" :href="routeUrl('/login')">绔嬪嵆寮€濮?/a>
        <a v-if="!user" class="btn modern-btn-outline" :href="routeUrl('/register')">鍏嶈垂娉ㄥ唽</a>
        <a v-if="user" class="btn modern-btn-primary" :href="routeUrl('/user')">鐢ㄦ埛鍚庡彴</a>
        <a v-if="canAccessAdmin" class="btn modern-btn-outline" :href="routeUrl(adminUrl)">绠＄悊鍚庡彴</a>
      </div>
    </div>
    <div class="modern-hero-visual">
      <div class="modern-stats-card">
        <div class="modern-stat-item"><span class="modern-stat-num">{{ homeStats.product_count || 0 }}</span><span class="modern-stat-label">鍟嗗搧鎬绘暟</span></div>
        <div class="modern-stat-item"><span class="modern-stat-num">{{ homeStats.order_count || 0 }}</span><span class="modern-stat-label">璁㈠崟鎬绘暟</span></div>
        <div class="modern-stat-item"><span class="modern-stat-num">{{ money(homeStats.total_quantity || 0) }}</span><span class="modern-stat-label">鎬讳笅鍗曟暟</span></div>
      </div>
    </div>
  </div>

  <div class="modern-features section-gap">
    <h2 class="modern-section-title">鏍稿績浼樺娍</h2>
    <div class="modern-feature-grid">
      <div class="modern-feature-card"><div class="modern-feature-icon">01</div><h3>绉掗€熷埌璐?/h3><p>涓嬪崟鍚庣珛鍗宠繘鍏ラ槦鍒楋紝鏃犻渶婕暱绛夊緟銆?/p></div>
      <div class="modern-feature-card"><div class="modern-feature-icon">02</div><h3>瀹夊叏绋冲畾</h3><p>鐪熷疄鐢ㄦ埛鏁版嵁锛岄潪鏈哄櫒鍒烽噺锛岀ǔ瀹氬彲闈犮€?/p></div>
      <div class="modern-feature-card"><div class="modern-feature-icon">03</div><h3>浠锋牸浼樻儬</h3><p>浣庝簬鍏ㄧ綉鍚岃浠锋牸锛岀粰浣犳渶鑸掑績鐨勪綋楠屻€?/p></div>
      <div class="modern-feature-card"><div class="modern-feature-icon">04</div><h3>API瀵规帴</h3><p>寮€鏀炬帴鍙ｏ紝杞绘澗瀵规帴浣犵殑鏈嶅姟骞冲彴銆?/p></div>
    </div>
  </div>

  <div class="modern-cta section-gap">
    <div class="modern-cta-card">
      <h2>鍑嗗濂藉紑濮嬩簡鍚楋紵</h2>
      <p>娉ㄥ唽鍗冲埢浣撻獙楂樻晥绋冲畾鐨勯€熷埛鏈嶅姟</p>
      <div class="modern-cta-actions">
        <a v-if="!user" class="btn modern-btn-primary" :href="routeUrl('/register')">绔嬪嵆娉ㄥ唽</a>
        <a v-if="user" class="btn modern-btn-primary" :href="routeUrl('/user')">杩涘叆鍚庡彴</a>
      </div>
    </div>
  </div>

  <div class="modern-footer section-gap">
    <div v-if="settings.community_group_qq" class="modern-group"><span>鐢ㄦ埛浜ゆ祦缇わ細</span><strong>{{ settings.community_group_qq }}</strong><button class="btn sm" @click="openGroup('community')">鍔犲叆</button></div>
    <div v-if="canShowSupportGroup && settings.support_group_qq" class="modern-group"><span>鍞悗缇わ細</span><strong>{{ settings.support_group_qq }}</strong><button class="btn sm" @click="openGroup('support')">鍔犲叆</button></div>
    <div class="modern-beian" v-if="settings.icp_beian_no"><a href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a></div>
  </div>
</div>
</template>

<!-- Template: minimal -->
<template v-if="settings.home_template === 'minimal'">
<div class="tpl-minimal">
  <div class="minimal-header">
    <h1>{{ site.name }}</h1>
    <p class="minimal-tagline">楂樻晥 路 绋冲畾 路 瀹炴儬</p>
  </div>

  <div class="minimal-stats section-gap">
    <div class="minimal-stat"><span class="minimal-num">{{ homeStats.product_count || 0 }}</span><span class="minimal-label">鍟嗗搧</span></div>
    <div class="minimal-stat"><span class="minimal-num">{{ homeStats.order_count || 0 }}</span><span class="minimal-label">璁㈠崟</span></div>
    <div class="minimal-stat"><span class="minimal-num">{{ money(homeStats.total_quantity || 0) }}</span><span class="minimal-label">鎬讳笅鍗?/span></div>
  </div>

  <div class="minimal-actions section-gap">
    <a v-if="!user" class="btn minimal-btn-primary" :href="routeUrl('/login')">鐧诲綍</a>
    <a v-if="!user" class="btn minimal-btn-ghost" :href="routeUrl('/register')">娉ㄥ唽</a>
    <a v-if="user" class="btn minimal-btn-primary" :href="routeUrl('/user')">鐢ㄦ埛鍚庡彴</a>
    <a v-if="canAccessAdmin" class="btn minimal-btn-ghost" :href="routeUrl(adminUrl)">绠＄悊鍚庡彴</a>
    <a class="btn minimal-btn-ghost" href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener">鎺ュ彛鏂囨。</a>
  </div>

  <div class="minimal-features section-gap">
    <div class="minimal-feature"><strong>绉掗€熷埌璐?/strong><span>涓嬪崟鍚庣珛鍗冲鐞?/span></div>
    <div class="minimal-feature"><strong>瀹夊叏绋冲畾</strong><span>鐪熷疄鐢ㄦ埛鏁版嵁</span></div>
    <div class="minimal-feature"><strong>浠锋牸浼樻儬</strong><span>浣庝簬鍚岃浠锋牸</span></div>
    <div class="minimal-feature"><strong>API瀵规帴</strong><span>寮€鏀炬帴鍙ｆ敮鎸?/span></div>
  </div>

  <div class="minimal-groups section-gap" v-if="settings.community_group_qq || (canShowSupportGroup && settings.support_group_qq)">
    <div v-if="settings.community_group_qq" class="minimal-group-item"><span>浜ゆ祦缇?/span><strong>{{ settings.community_group_qq }}</strong><button class="btn sm" @click="openGroup('community')">鍔犲叆</button></div>
    <div v-if="canShowSupportGroup && settings.support_group_qq" class="minimal-group-item"><span>鍞悗缇?/span><strong>{{ settings.support_group_qq }}</strong><button class="btn sm" @click="openGroup('support')">鍔犲叆</button></div>
  </div>

  <div class="minimal-footer section-gap">
    <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
    <span v-if="settings.public_security_beian_no">缃戝畨澶囨锛歿{ settings.public_security_beian_no }}</span>
  </div>
</div>
</template>

<!-- Template: business -->
<template v-if="settings.home_template === 'business'">
<div class="tpl-business">
  <div class="business-hero">
    <div class="business-hero-bg"></div>
    <div class="business-hero-content">
      <h1>{{ site.name }}</h1>
      <p class="business-subtitle">涓撲笟鐨勪紒涓氱骇閫熷埛瑙ｅ喅鏂规</p>
      <div class="business-hero-actions">
        <a v-if="!user" class="btn business-btn-primary" :href="routeUrl('/login')">绔嬪嵆鐧诲綍</a>
        <a v-if="!user" class="btn business-btn-secondary" :href="routeUrl('/register')">娉ㄥ唽璐﹀彿</a>
        <a v-if="user" class="btn business-btn-primary" :href="routeUrl('/user')">杩涘叆鎺у埗鍙?/a>
        <a v-if="canAccessAdmin" class="btn business-btn-secondary" :href="routeUrl(adminUrl)">绠＄悊鍚庡彴</a>
      </div>
    </div>
  </div>

  <div class="business-metrics section-gap">
    <div class="business-metric-card">
      <div class="business-metric-icon">01</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ homeStats.product_count || 0 }}</span><span class="business-metric-label">鍟嗗搧鎬绘暟</span></div>
    </div>
    <div class="business-metric-card">
      <div class="business-metric-icon">02</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ homeStats.order_count || 0 }}</span><span class="business-metric-label">璁㈠崟鎬绘暟</span></div>
    </div>
    <div class="business-metric-card">
      <div class="business-metric-icon">03</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ money(homeStats.total_quantity || 0) }}</span><span class="business-metric-label">鎬讳笅鍗曟暟</span></div>
    </div>
    <div class="business-metric-card">
      <div class="business-metric-icon">04</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ boolText(settings.api_order_enabled) }}</span><span class="business-metric-label">API瀵规帴</span></div>
    </div>
  </div>

  <div class="business-services section-gap">
    <h2 class="business-section-title">鎴戜滑鐨勬湇鍔?/h2>
    <div class="business-service-grid">
      <div class="business-service-card">
        <div class="business-service-icon">A</div>
        <h3>鍦ㄧ嚎涓嬪崟</h3>
        <p>鏀寔澶氱鍟嗗搧绫诲瀷锛屽疄鏃朵笅鍗曪紝绉掗€熷鐞嗐€?/p>
        <a :href="routeUrl(user ? '/user' : '/login')" class="btn business-btn-sm">绔嬪嵆浣撻獙</a>
      </div>
      <div class="business-service-card">
        <div class="business-service-icon">B</div>
        <h3>瀹炴椂鏌ュ崟</h3>
        <p>璁㈠崟鐘舵€佸疄鏃舵洿鏂帮紝澶勭悊杩涘害涓€鐩簡鐒躲€?/p>
        <a :href="routeUrl(user ? '/user/orders' : '/login')" class="btn business-btn-sm">鏌ョ湅璁㈠崟</a>
      </div>
      <div class="business-service-card">
        <div class="business-service-icon">C</div>
        <h3>API瀵规帴</h3>
        <p>寮€鏀炬爣鍑嗘帴鍙ｏ紝杞绘澗闆嗘垚鍒颁綘鐨勫钩鍙般€?/p>
        <a href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener" class="btn business-btn-sm">鏌ョ湅鏂囨。</a>
      </div>
    </div>
  </div>

  <div class="business-advantages section-gap">
    <h2 class="business-section-title">涓轰粈涔堥€夋嫨鎴戜滑</h2>
    <div class="business-advantage-grid">
      <div class="business-advantage-item"><span class="business-advantage-check">&bull;</span><span>绉掗€熷埌璐︼紝鏃犻渶绛夊緟</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">&bull;</span><span>鐪熷疄鏁版嵁锛屽畨鍏ㄧǔ瀹?/span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">&bull;</span><span>浠锋牸浼樻儬锛屾€т环姣旈珮</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">&bull;</span><span>7脳24 灏忔椂绀剧兢鏀寔</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">&bull;</span><span>鏀寔鍗″瘑鍏戞崲鍔熻兘</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">&bull;</span><span>瀹屽杽鐨凙PI鏂囨。</span></div>
    </div>
  </div>

  <div class="business-contact section-gap">
    <div class="business-contact-card">
      <h3>鍔犲叆鎴戜滑鐨勭ぞ缇?/h3>
      <div class="business-contact-items">
        <div v-if="settings.community_group_qq" class="business-contact-item"><span>鐢ㄦ埛浜ゆ祦缇?/span><strong>{{ settings.community_group_qq }}</strong><button class="btn business-btn-sm" @click="openGroup('community')">鍔犲叆</button></div>
        <div v-if="canShowSupportGroup && settings.support_group_qq" class="business-contact-item"><span>鍞悗鏀寔缇?/span><strong>{{ settings.support_group_qq }}</strong><button class="btn business-btn-sm" @click="openGroup('support')">鍔犲叆</button></div>
      </div>
    </div>
  </div>

  <div class="business-footer section-gap">
    <div class="business-beian">
      <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
      <span v-if="settings.public_security_beian_no" class="business-psb">缃戝畨澶囨锛歿{ settings.public_security_beian_no }}</span>
    </div>
    <div class="business-copyright">漏 {{ new Date().getFullYear() }} {{ site.name }} All Rights Reserved</div>
  </div>
</div>
</template>

</section>


    <section v-else-if="routeMode === 'login'">
      <div class="login-shell">
        <div v-if="user" class="panel">
          <div class="page-head">
            <div>
              <h2>浣犲凡缁忕櫥褰?/h2>
              <p>褰撳墠璐﹀彿鍙洿鎺ヨ繘鍏ュ搴斿悗鍙帮紝鏃犻渶閲嶅鐧诲綍銆?/p>
            </div>
          </div>
          <div class="login-dual-actions">
            <a class="btn primary" :href="routeUrl('/user')">杩涘叆鐢ㄦ埛鍚庡彴</a>
            <a v-if="canAccessAdmin" class="btn ghost" :href="routeUrl(adminUrl)">杩涘叆绠＄悊鍚庡彴</a>
          </div>
        </div>
        <div v-else class="auth-box">
          <h3>缁熶竴鐧诲綍</h3>
          <div class="form-grid">
            <div class="field full">
              <label>鐢ㄦ埛鍚?/label>
              <input v-model.trim="home.login.username" placeholder="璇疯緭鍏ョ敤鎴峰悕">
            </div>
            <div class="field full">
              <label>瀵嗙爜</label>
              <input v-model="home.login.password" type="password" placeholder="璇疯緭鍏ュ瘑鐮?>
            </div>
            <div class="field">
              <label>鍥剧墖楠岃瘉鐮?/label>
              <input v-model.trim="home.login.captcha" placeholder="璇疯緭鍏ラ獙璇佺爜">
            </div>
            <div class="field">
              <label>楠岃瘉鐮佸浘鐗?/label>
              <div class="inline-actions">
                <img :src="captchaUrl" alt="captcha" style="height:46px;border:1px solid var(--line);border-radius:12px;background:var(--input-bg)">
                <button class="btn sm" @click="refreshCaptcha">鍒锋柊</button>
              </div>
            </div>
          </div>
          <div class="login-dual-actions">
            <button class="btn primary" @click="submitLogin(false)">杩涘叆鐢ㄦ埛鍚庡彴</button>
            <button class="btn ghost" @click="submitLogin(true)">杩涘叆绠＄悊鍚庡彴</button>
            <a class="btn ghost" :href="routeUrl('/register')">娌℃湁璐﹀彿锛熷幓娉ㄥ唽</a>
          </div>
        </div>
      </div>
    </section>

    <section v-else-if="routeMode === 'register'">
      <div class="login-shell">
        <div v-if="user" class="panel">
          <div class="page-head">
            <div>
              <h2>浣犲凡鐧诲綍</h2>
              <p>濡傞渶缁х画浣跨敤锛岃鐩存帴杩涘叆鐢ㄦ埛鍚庡彴锛涘闇€鎹㈠彿锛岃鍏堥€€鍑哄綋鍓嶈处鍙枫€?/p>
            </div>
          </div>
          <div class="login-dual-actions">
            <a class="btn primary" :href="routeUrl('/user')">杩涘叆鐢ㄦ埛鍚庡彴</a>
            <button class="btn ghost" @click="logout">閫€鍑哄綋鍓嶈处鍙?/button>
          </div>
        </div>
        <div v-else class="auth-box">
          <h3>娉ㄥ唽璐﹀彿</h3>
          <div class="form-grid">
            <div class="field">
              <label>鐢ㄦ埛鍚?/label>
              <input v-model.trim="home.register.username" placeholder="4-32浣嶈嫳鏂囨暟瀛?>
            </div>
            <div class="field">
              <label>鏄电О</label>
              <input v-model.trim="home.register.nickname" placeholder="鏄电О">
            </div>
            <div class="field">
              <label>QQ鍙?/label>
              <input v-model.trim="home.register.qq" placeholder="QQ鍙?>
            </div>
            <div class="field">
              <label>瀵嗙爜</label>
              <input v-model="home.register.password" type="password" placeholder="鑷冲皯8浣嶆洿瀹夊叏">
            </div>
            <div v-if="needRegisterEmail" class="field">
              <label>閭</label>
              <input v-model.trim="home.register.email" placeholder="閭鍦板潃">
            </div>
            <div v-if="needRegisterMobile" class="field">
              <label>鎵嬫満鍙?/label>
              <input v-model.trim="home.register.mobile" placeholder="鎵嬫満鍙?>
            </div>
            <div class="field full">
              <label>閭€璇风爜锛堝彲閫夛級</label>
              <input v-model.trim="home.register.invite_code" placeholder="娌℃湁鍙暀绌?>
            </div>
            <div v-if="registerNeedCaptcha" class="field">
              <label>鍥剧墖楠岃瘉鐮?/label>
              <input v-model.trim="home.register.captcha" placeholder="璇疯緭鍏ラ獙璇佺爜">
            </div>
            <div v-if="registerNeedCaptcha" class="field">
              <label>楠岃瘉鐮佸浘鐗?/label>
              <div class="inline-actions">
                <img :src="captchaUrl" alt="captcha" style="height:46px;border:1px solid var(--line);border-radius:12px;background:var(--input-bg)">
                <button class="btn sm" @click="refreshCaptcha">鍒锋柊</button>
              </div>
            </div>
          </div>
          <div class="login-dual-actions">
            <button class="btn primary" @click="submitRegister">娉ㄥ唽骞惰繘鍏ョ敤鎴峰悗鍙?/button>
            <a class="btn ghost" :href="routeUrl('/login')">宸叉湁璐﹀彿锛熷幓鐧诲綍</a>
          </div>
        </div>
      </div>
    </section>

    <section v-else-if="routeMode === 'exchange'">
      <div class="home-hero-grid">
        <div class="panel">
          <div class="page-head">
            <div><h2>鍟嗗搧鍏戞崲鐮佸厬鎹?/h2></div>
            <div class="inline-actions"><button class="btn ghost" @click="loadExchangeOrders(true)">鍒锋柊鍘嗗彶璁㈠崟</button></div>
          </div>
          <div class="form-grid">
            <div class="field full"><label>鍏戞崲鐮?/label><input v-model.trim="exchangePublic.code" placeholder="璇疯緭鍏?48 浣嶄互涓婂晢鍝佸厬鎹㈢爜"></div>
          </div>
          <div class="inline-actions"><button class="btn primary" @click="previewExchangeCode">鏌ヨ鍏戞崲鐮?/button></div>
          <div v-if="exchangePublic.preview" class="section-gap section-stack">
            <div class="order-summary-box">
              <h4>{{ exchangePublic.preview.product_name }}</h4>
              <div class="order-summary-grid">
                <div class="subtle"><span>鍏戞崲鐮?/span><strong class="text-break">{{ exchangePublic.preview.display_code }}</strong></div>
                <div class="subtle"><span>鏁伴噺</span><strong>{{ exchangePublic.preview.quantity }}</strong></div>
                <div class="subtle"><span>璁′环鍗曚綅</span><strong>姣?{{ exchangePublic.preview.step_num }} 鏁伴噺</strong></div>
                <div class="subtle"><span>鍒涘缓鏃朵环鏍煎揩鐓?/span><strong>{{ money(exchangePublic.preview.price_snapshot) }} {{ currency }}</strong></div>
              </div>
              <div v-if="exchangePublic.preview.product_desc && exchangePublic.preview.product_desc.length" class="desc-list section-gap"><div class="desc-item" v-for="(desc,idx) in exchangePublic.preview.product_desc" :key="idx">{{ desc }}</div></div>
            </div>
            <div class="panel">
              <h3>濉啓鍏戞崲淇℃伅</h3>
              <div class="form-grid">
                <div class="field full"><label>QQ鍙?/label><input v-model.trim="exchangePublic.form.qq" placeholder="璇疯緭鍏ヤ笅鍗?QQ"><div class="qq-preview" v-if="exchangePublic.form.qq"><img :src="qqAvatar(exchangePublic.form.qq)" alt="qq"><div class="tiny">澶村儚鐢?QQ 鎻愪緵锛岀敤浜庤緟鍔╂牳瀵广€?/div></div></div>
                <div v-for="field in exchangeInputFields" :key="field.key" class="field" :class="{full: field.key === 'feed_id'}"><label>{{ field.label }}</label><input v-model.trim="exchangePublic.form.extra[field.key]" :placeholder="field.placeholder || ('璇疯緭鍏? + field.label)"></div>
              </div>
              <div class="auth-footnote">鑻ュ晢鍝侀渶瑕?QQ 鍙枫€佽璇?ID 绛夊弬鏁帮紝璇风敱鍏戞崲鑰呰嚜琛屽～鍐欙紱鍏戞崲鎴愬姛鍚庝細鑷姩鐢熸垚鏈郴缁熻鍗曞彿銆?/div>
              <div class="inline-actions section-gap"><button class="btn success" @click="redeemExchangeCode">纭鍏戞崲骞朵笅鍗?/button></div>
            </div>
          </div>
        </div>
        <div class="section-stack">
          <div class="panel">
            <h3>璁㈠崟鏌ュ崟</h3>
            <div class="search-row exchange-order-search"><input v-model.trim="exchangePublic.orderSearch" placeholder="杈撳叆鏈祻瑙堝櫒宸插厬鎹㈢殑璁㈠崟鍙?><button class="btn primary" @click="queryExchangeOrder(exchangePublic.orderSearch)">鏌ヨ杩涘害</button></div>
            <div v-if="exchangePublic.orderDetail" class="order-summary-box section-gap"><div class="action-row"><strong class="mono text-break">{{ exchangePublic.orderDetail.display_order_no || exchangePublic.orderDetail.order_no }}</strong><span class="badge" :class="badgeTone(exchangePublic.orderDetail.state)">{{ exchangePublic.orderDetail.state || '-' }}</span></div><div class="tiny">{{ exchangePublic.orderDetail.product_name || '-' }} 路 {{ formatDate(exchangePublic.orderDetail.created_at) }}</div><div class="pre-wrap section-gap">{{ exchangePublic.orderDetail.latest_message || exchangePublic.orderDetail.message || '鏃? }}</div></div>
          </div>
          <div class="panel">
            <h3>鍘嗗彶鍏戞崲璁㈠崟</h3>
            <div v-if="!exchangePublic.orders.length" class="placeholder-card">褰撳墠娴忚鍣ㄨ繕娌℃湁鍏戞崲璁板綍锛屽厬鎹㈡垚鍔熷悗浼氳嚜鍔ㄥ嚭鐜板湪杩欓噷銆?/div>
            <div v-else class="code-list"><div v-for="order in exchangePublic.orders" :key="order.order_no" class="code-item"><div style="min-width:0"><div class="mono text-break">{{ order.display_order_no || order.order_no }}</div><div class="tiny">{{ order.product_name || '-' }} 路 {{ formatDate(order.created_at) }}</div></div><div class="inline-actions"><span class="badge" :class="badgeTone(order.state)">{{ order.state || '-' }}</span><button class="btn sm ghost" @click="queryExchangeOrder(order.order_no)">鏌ョ湅杩涘害</button></div></div></div>
          </div>
          <div class="panel"><h3>璇存槑</h3><div class="desc-list"><div class="desc-item">鍦ㄥ厬鎹㈠墠锛岃纭畾宸茬粡寮€鍚簡鐩稿簲鏉冮檺</div><div class="desc-item">鑻ュ晢鍝侀渶瑕侀澶栧弬鏁帮紝璇锋寜椤甸潰鎻愮ず濉啓锛涚郴缁熶細鎸変笂娓稿晢鍝佽緭鍏ラ」杩涜鏍￠獙銆?/div></div></div>
        </div>
      </div>
    </section>

    <section v-else-if="routeMode === 'user'">
      <div v-if="!user" class="auth-box" style="max-width:560px;margin:0 auto;">
        <h3>璇峰厛鐧诲綍</h3>
        <p class="panel-sub">鐢ㄦ埛鍚庡彴宸蹭笌棣栭〉鍒嗙锛岃鍏堝墠寰€缁熶竴鐧诲綍椤靛畬鎴愮櫥褰曘€?/p>
        <div class="inline-actions">
          <a class="btn primary" :href="routeUrl('/login')">鍓嶅線鐧诲綍</a>
          <a class="btn ghost" :href="routeUrl('/register')">娌℃湁璐﹀彿锛熷幓娉ㄥ唽</a>
        </div>
      </div>

      <div v-else class="layout">
        <aside class="sidebar">
          <div class="side-profile">
            <div class="side-profile-card">
              <div class="inline-avatar">
                <img :src="qqAvatar(user.qq)" alt="avatar">
                <div>
                  <strong style="font-size:16px;margin:0">{{ displayName(user) }}</strong>
                  <div class="tiny">UID锛歿{ user.uid || '-' }}</div>
                </div>
              </div>
            </div>
            <div class="side-profile-card">
              <small class="muted">褰撳墠浣欓</small>
              <strong>{{ money(profile.user ? profile.user.balance : 0) }} {{ currency }}</strong>
            </div>
            <div class="side-profile-card">
              <small class="muted">鐢ㄦ埛缁?/small>
              <strong>{{ profile.group ? profile.group.name : '鏈姞杞? }}</strong>
            </div>
          </div>
          <div class="side-title">鐢ㄦ埛鍚庡彴</div>
          <div class="nav-list">
            <button v-for="item in userNav" :key="item.key" class="nav-item" :class="{active:userTab===item.key}" @click="switchUserTab(item.key)">{{ item.label }}</button>
          </div>
        </aside>

        <div class="content-area">
          <div v-if="userTab === 'dashboard'">
            <div class="page-head">
              <div>
                <h2>鐢ㄦ埛棣栭〉</h2>
                <p>鏌ョ湅浣欓銆佺敤鎴风粍銆佺疮璁℃秷璐逛笌浠ｇ悊鎺ュ彛鐘舵€併€?/p>
              </div>
            </div>
            <div class="stats-grid">
              <div class="stat"><small>鐢ㄦ埛缁?/small><strong>{{ profile.group ? profile.group.name : '-' }}</strong></div>
              <div class="stat"><small>褰撳墠浣欓</small><strong>{{ money(profile.user ? profile.user.balance : 0) }}</strong></div>
              <div class="stat"><small>绱娑堣垂</small><strong>{{ money(profile.user ? profile.user.total_consume : 0) }}</strong></div>
              <div class="stat"><small>绱鍏呭€?/small><strong>{{ money(profile.user ? profile.user.total_recharge : 0) }}</strong></div>
            </div>
            <div class="grid-2 section-gap">
              <div class="panel">
                <h3>瀵规帴鐘舵€?/h3>
                <div class="kv">
                  <div>鏄惁鍏佽瀵规帴</div><div><span class="badge" :class="profile.api_access && profile.api_access.allow ? 'success' : 'warning'">{{ profile.api_access && profile.api_access.allow ? '鍏佽瀵规帴' : '鏆備笉鍏佽瀵规帴' }}</span></div>
                  <div>绯荤粺 UID</div><div class="code-inline">{{ profile.user ? profile.user.uid : '-' }}</div>
                  <div>API Key</div><div><template v-if="profile.user && profile.user.api_key"><span class="code-inline">{{ profile.user.api_key }}</span></template><template v-else>褰撳墠璐﹀彿鏆傛棤 API Key</template></div>
                  <template v-if="!(profile.api_access && profile.api_access.allow)">
                    <div>褰撳墠鎻愮ず</div><div>{{ apiAccessHint(profile.api_access) }}</div>
                  </template>
                </div>
                <div class="inline-actions section-gap">
                  <button class="btn primary" @click="resetOwnApiKey" :disabled="!(profile.api_access && profile.api_access.can_generate_key)">{{ profile.user && profile.user.api_key ? '閲嶇疆 API Key' : '鐢熸垚 API Key' }}</button>
                </div>
              </div>
              <div class="panel">
                <h3>蹇嵎鍏ュ彛</h3>
                <div class="quick-grid">
                  <button class="quick-card" @click="switchUserTab('order')"><h3>鍦ㄧ嚎涓嬪崟</h3></button>
                  <button class="quick-card" @click="switchUserTab('orders')"><h3>鏌ュ崟绯荤粺</h3></button>
                  <button class="quick-card" @click="switchUserTab('recharge')"><h3>棰濆害鍏呭€?/h3></button>
                  <button class="quick-card" @click="switchUserTab('invites')"><h3>閭€璇风鐞?/h3></button>
                  <button class="quick-card" @click="switchUserTab('groups')"><h3>浠ｇ悊绛夌骇</h3></button>
                  <button class="quick-card" @click="switchUserTab('profile')"><h3>涓汉璧勬枡</h3></button>
                </div>
              </div>
            </div>
            <div v-if="canShowSupportGroup" class="panel section-gap">
              <div class="card-title">
                <div>
                  <h3>鍞悗 / 鏀寔缇?/h3>
                  <div class="landing-group-code">{{ settings.support_group_qq }}</div>
                </div>
                <button class="btn primary" @click="openGroup('support')">鍔犲叆鍞悗缇?/button>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'order'">
            <div class="page-head">
              <div>
                <h2>鍦ㄧ嚎涓嬪崟</h2>
              </div>
            </div>
            <div class="section-stack">
              <div class="panel">
                <h3>涓嬪崟淇℃伅</h3>
                <div class="form-grid">
                  <div class="field">
                    <label>绛涢€夊晢鍝?/label>
                    <input v-model.trim="userState.productKeyword" placeholder="杈撳叆鍟嗗搧鍚嶇瓫閫?>
                  </div>
                  <div class="field">
                    <label>閫夋嫨鍟嗗搧</label>
                    <select v-model="orderForm.sign">
                      <option value="">璇烽€夋嫨鍟嗗搧</option>
                      <option v-for="product in filteredProducts" :key="product.id" :value="product.upstream_sign">
                        {{ product.name }}
                      </option>
                    </select>
                  </div>
                  <div class="field">
                    <label>QQ鍙?/label>
                    <div class="order-qq-row">
                      <input v-model.trim="orderForm.qq" placeholder="璇疯緭鍏ヤ笅鍗?QQ" @input="clearFeedSelection">
                      <div v-if="orderForm.qq" class="order-qq-avatar">
                        <img :src="qqAvatar(orderForm.qq)" alt="QQ 澶村儚">
                        <div class="tiny">鐢ㄤ簬杈呭姪鏍稿 QQ銆?/div>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>鏁伴噺</label>
                    <input v-model.number="orderForm.num" type="number" :min="selectedProduct ? selectedProduct.min_num : 1" :max="selectedProduct ? selectedProduct.max_num : 999999999" :step="selectedProduct ? selectedProduct.step_num : 1" @change="scheduleQuote">
                  </div>
                  <div v-if="selectedProduct" class="field full">
                    <label>浠锋牸璁＄畻</label>
                    <div v-if="quote" class="order-summary-box">
                      <h4>{{ selectedProduct.name }} 路 鏈涓嬪崟棰勪及</h4>
                      <div class="order-summary-grid">
                        <div class="subtle"><span>涓嬪崟鏁伴噺</span><strong>{{ quote.quantity }}</strong></div>
                        <div class="subtle"><span>鏈€缁堜环鏍?/span><strong>{{ money(quote.price) }} {{ currency }}</strong></div>
                        <div class="subtle"><span>鎶樻墸鍊嶇巼</span><strong>{{ Number(quote.discount_rate || 1).toFixed(2) }}</strong></div>
                        <div class="subtle"><span>璁′环鍗曚綅</span><strong>姣?{{ selectedProduct.step_num }} 鏁伴噺</strong></div>
                        <div class="subtle"><span>褰撳墠鍗曚环</span><strong>{{ money(selectedProduct.preview_price) }} {{ currency }}</strong></div>
                        <div class="subtle"><span>鏁伴噺鑼冨洿</span><strong>{{ selectedProduct.min_num }} - {{ selectedProduct.max_num }}</strong></div>
                      </div>
                    </div>
                    <div v-else class="placeholder-card">濉啓涓嬪崟鏁伴噺鍚庯紝灏嗗湪杩欓噷鏄剧ず鏈€缁堜环鏍艰绠楃粨鏋溿€?/div>
                  </div>
                  <div v-if="selectedProduct && selectedProduct.desc && selectedProduct.desc.length" class="field full">
                    <label>鍟嗗搧鎻忚堪</label>
                    <div class="desc-list">
                      <div v-for="(desc,idx) in selectedProduct.desc" :key="idx" class="desc-item">{{ desc }}</div>
                    </div>
                  </div>
                  <div v-if="showDelayedOption" class="field full">
                    <label>鎱㈠埛妯″紡</label>
                    <div class="switch-inline">
                      <label><input type="checkbox" v-model="orderForm.is_delayed" @change="scheduleQuote"> 鍚敤鎱㈠埛</label>
                    </div>
                    <div class="auth-footnote">鎱㈠埛铏界劧閫熷害鍙樻參锛屼絾鏄环鏍兼洿鍔犲疄鎯犮€備笖閮ㄥ垎鎱㈠埛鏈夋渶浣庝笅鍗曡姹傦紝璇︽儏璇风湅涓婃父鏂囨。銆?/div>
                  </div>
                  <div v-for="field in dynamicInputFields" :key="field.key" class="field" :class="{full:field.key==='feed_id'}">
                    <label>{{ field.label }}</label>
                    <template v-if="field.key === 'feed_id'">
                      <input v-model.trim="orderForm.feed_id" placeholder="璇烽€夋嫨鎴栨墜鍔ㄥ～鍐欒璇?ID">
                      <div class="inline-actions">
                        <button class="btn sm" @click="loadFeedList">鑾峰彇璇磋鍒楄〃</button>
                        <span class="tiny">鑻ュ浘鐗囧拰鍐呭閮戒负绌猴紝鍙兘鏄浆鍙戝唴瀹癸紝骞堕潪绌鸿璇淬€?/span>
                      </div>
                    </template>
                    <template v-else>
                      <input v-model.trim="orderForm.extra[field.key]" :placeholder="field.placeholder || ('璇疯緭鍏? + field.label)">
                    </template>
                  </div>
                </div>
                <div class="inline-actions section-gap">
                  <button class="btn primary" @click="createOrder">鎻愪氦璁㈠崟</button>
                  <button class="btn ghost" @click="scheduleQuote">閲嶆柊璁＄畻浠锋牸</button>
                </div>
                <div v-if="!filteredProducts.length" class="empty section-gap">褰撳墠娌℃湁绗﹀悎绛涢€夋潯浠剁殑鍟嗗搧銆?/div>
              </div>
            </div>

            <div v-if="userState.feedModalVisible" class="modal-mask" @click.self="closeFeedModal">
              <div class="modal">
                <div class="modal-head">
                  <div>
                    <h3>璇磋鍒楄〃</h3>
                    <div class="tiny">鑻ュ晢鍝侀渶瑕?feed_id锛岃鍦ㄥ脊绐椾腑閫夋嫨瀵瑰簲璇磋锛涜嫢鍥剧墖鍜屽唴瀹归兘涓虹┖锛屽彲鑳芥槸杞彂鍐呭锛屽苟闈炵┖璇磋銆?/div>
                  </div>
                  <button class="btn ghost" @click="closeFeedModal">鍏抽棴</button>
                </div>
                <div class="feed-modal-grid">
                  <div v-for="item in userState.feedItems" :key="item.id || item.feed_id || item.fid" class="feed-card" :class="{active: selectedFeedId === resolveFeedId(item)}" @click="selectFeed(item)">
                    <div class="feed-head">
                      <div>
                        <strong>璇磋 ID锛歿{ resolveFeedId(item) || '-' }}</strong>
                        <div class="tiny">鍙戝竷鏃堕棿锛歿{ formatFeedTime(item) }}</div>
                      </div>
                      <span class="badge info">鐐归€夊嵆濉叆</span>
                    </div>
                    <div class="feed-content" style="margin-top:10px">{{ item.content || '锛堟棤姝ｆ枃锛? }}</div>
                    <div class="feed-images" v-if="feedImageList(item).length">
                      <img v-for="(image,idx) in feedImageList(item)" :key="idx" :src="image.display || image.proxy || image.original || image.url" alt="feed">
                    </div>
                    <div v-if="item.is_possible_repost" class="feed-note">鑻ュ浘鐗囧拰鍐呭閮戒负绌猴紝姝よ璇村彲鑳芥槸杞彂鍐呭锛屽苟闈炵┖璇磋銆?/div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'orders'">
            <div class="page-head">
              <div>
                <h2>鏌ュ崟绯荤粺</h2>
                <p>闈炵粓鎬佽鍗曞湪鏌ョ湅璇︽儏鏃朵細瀹炴椂鍚屾涓婃父骞跺睍绀烘渶鏂版墽琛屼俊鎭紝涓嶆樉绀哄師濮嬭姹備笌鍝嶅簲浣撱€?/p>
              </div>
            </div>
            <div class="panel">
              <div class="search-row">
                <div class="field" style="min-width:260px;flex:1 1 260px">
                  <label>绯荤粺璁㈠崟鍙?/label>
                  <input v-model.trim="userState.orderSearch" placeholder="杈撳叆绯荤粺璁㈠崟鍙峰悗鏌ヨ璇︽儏">
                </div>
                <div class="inline-actions" style="padding-top:26px">
                  <button class="btn primary" @click="searchOrderDetail">鏌ヨ璁㈠崟</button>
                  <button class="btn ghost" @click="loadUserOrders(true)">鍒锋柊鍒楄〃</button>
                </div>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>鏈€杩戣鍗?/h3>
                <div class="table-wrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>绯荤粺璁㈠崟鍙?/th>
                        <th>鍟嗗搧</th>
                        <th>鐘舵€?/th>
                        <th>鏁伴噺</th>
                        <th>閲戦</th>
                        <th>鎿嶄綔</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="row in userState.orders" :key="row.id">
                        <td class="mono mono-wrap">{{ row.display_order_no || row.order_no }}</td>
                        <td>{{ row.product_name }}</td>
                        <td><span class="badge" :class="badgeTone(row.state)">{{ row.state }}</span></td>
                        <td>{{ row.quantity }}</td>
                        <td>{{ money(row.user_price) }}<div class="amount-yuan">{{ yuanApprox(row.user_price) }}</div></td>
                        <td class="actions-cell">
                          <button class="btn sm ghost" @click="showOrderDetail(row.display_order_no || row.order_no)">鏌ョ湅</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="panel">
                <h3>璁㈠崟璇︽儏</h3>
                <div v-if="!userState.orderDetail" class="empty">鐐瑰嚮宸︿晶璁㈠崟鎴栬緭鍏ョ郴缁熻鍗曞彿鍚庯紝鍗冲彲鏌ョ湅璇︽儏銆?/div>
                <template v-else>
                  <div class="order-metrics">
                    <div class="order-metric"><small>绯荤粺璁㈠崟鍙?/small><strong class="mono mono-wrap">{{ userState.orderDetail.display_order_no || userState.orderDetail.order_no }}</strong></div>
                    <div class="order-metric"><small>璁㈠崟鐘舵€?/small><strong>{{ userState.orderDetail.state }}</strong></div>
                    <div class="order-metric"><small>鏈€鏂板娉?/small><strong>{{ userState.orderDetail.latest_message || '鏃? }}</strong></div>
                  </div>
                  <div class="kv">
                    <div>鍟嗗搧</div><div>{{ userState.orderDetail.product_name }}</div>
                    <div>QQ鍙?/div><div>{{ userState.orderDetail.target_qq }}</div>
                    <div>鏁伴噺</div><div>{{ userState.orderDetail.quantity }}</div>
                    <div>璇磋ID</div><div>{{ userState.orderDetail.feed_id || '-' }}</div>
                    <div>寮€濮嬫暟閲?/div><div>{{ userState.orderDetail.start_num ?? '-' }}</div>
                    <div>褰撳墠鏁伴噺</div><div>{{ userState.orderDetail.current_num ?? '-' }}</div>
                    <div>缁撴潫鏁伴噺</div><div>{{ userState.orderDetail.finish_num ?? '-' }}</div>
                    <div>寮€濮嬫椂闂?/div><div>{{ formatDate(userState.orderDetail.started_at) }}</div>
                    <div>鏈€鍚庢洿鏂版椂闂?/div><div>{{ formatDate(userState.orderDetail.last_sync_at || userState.orderDetail.updated_at) }}</div>
                    <div>缁撴潫鏃堕棿</div><div>{{ formatDate(userState.orderDetail.finished_at) }}</div>
                    <div>涓嬪崟鏃堕棿</div><div>{{ formatDate(userState.orderDetail.created_at) }}</div>
                  </div>
                  <div class="tip-box section-gap" v-if="userState.orderDetail.can_retry || userState.orderDetail.state === '澶辫触'">
                    <div class="note-strong">鍥犲繕璁板紑鏉冮檺鎴栬€呭叾浠栧師鍥犲鑷村け璐ョ殑锛屽彲鐢宠琛ュ崟涓€娆★紝琛ュ崟鍚庤繕澶辫触鐨勫皢涓嶅啀鏀寔鍐嶆琛ュ崟銆?/div>
                  </div>
                  <div class="inline-actions">
                    <button class="btn warning" :disabled="!userState.orderDetail.can_retry" @click="userRetryOrder(userState.orderDetail)">鐢宠琛ュ崟</button>
                    <button class="btn danger" :disabled="!userState.orderDetail.can_refund" @click="userRefundOrder(userState.orderDetail)">鐢宠閫€娆?/button>
                    <button class="btn ghost" @click="showOrderDetail(userState.orderDetail.display_order_no || userState.orderDetail.order_no)">閲嶆柊鍚屾</button>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'exchange_codes'">
            <div class="page-head">
              <div>
                <h2>鍟嗗搧鍏戞崲鐮?/h2>
                <p>鐢熸垚鍙垎浜殑鍟嗗搧鍏戞崲鐮併€傚厬鎹㈣€呮棤闇€鐧诲綍鍗冲彲濉啓 QQ / 璇磋 ID 骞剁洿鎺ヤ笅鍗曘€?/p>
              </div>
              <div class="inline-actions">
                <button class="btn ghost" @click="loadUserExchangeCodes(true)">鍒锋柊鍏戞崲鐮?/button>
              </div>
            </div>
            <div v-if="userState.exchangeSettings && !userState.exchangeSettings.enabled" class="tip-box">鍟嗗搧鍏戞崲鐮佸姛鑳藉綋鍓嶅凡鍏抽棴锛岃鑱旂郴绠＄悊鍛樸€?/div>
            <div class="grid-2">
              <div class="panel">
                <h3>鎵归噺鐢熸垚鍏戞崲鐮?/h3>
                <div class="form-grid">
                  <div class="field full"><label>閫夋嫨鍟嗗搧</label><select v-model="exchangeCodeForm.sign" @change="exchangeCodeForm.quantity = selectedExchangeProduct ? Number(selectedExchangeProduct.min_num || selectedExchangeProduct.step_num || 1) : 0"><option value="">璇烽€夋嫨鍟嗗搧</option><option v-for="product in userState.products" :key="product.upstream_sign" :value="product.upstream_sign">{{ product.name }}</option></select></div>
                  <div class="field"><label>姣忎釜鍏戞崲鐮佺殑涓嬪崟鏁伴噺</label><input v-model.number="exchangeCodeForm.quantity" type="number" min="1" :max="selectedExchangeProduct ? selectedExchangeProduct.max_num : null" :step="selectedExchangeProduct ? (selectedExchangeProduct.step_num || 1) : 1"></div>
                  <div class="field"><label>鐢熸垚鏁伴噺</label><input v-model.number="exchangeCodeForm.count" type="number" min="1" max="1000"></div>
                  <div class="field"><label>鐢熸垚鎵嬬画璐癸紙姣忓紶锛?/label><input :value="money(userState.exchangeSettings ? userState.exchangeSettings.generation_fee : 0) + ' ' + currency" readonly></div>
                </div>
                <div v-if="selectedExchangeProduct" class="order-summary-box section-gap"><div class="tiny">褰撳墠鐢ㄦ埛浠锋牸</div><strong>{{ money(selectedExchangeProduct.sell_price || selectedExchangeProduct.price || 0) }} {{ currency }} / {{ selectedExchangeProduct.step_num || 1 }} 涓?/strong><div v-if="selectedExchangeProduct.desc && selectedExchangeProduct.desc.length" class="desc-list section-gap"><div v-for="(desc,idx) in selectedExchangeProduct.desc" :key="idx" class="desc-item">{{ desc }}</div></div></div>
                <div class="admin-note section-gap" v-if="userState.exchangeSettings"><div>鍏戞崲鐮佹牸寮忥細<span class="code-inline">{{ userState.exchangeSettings.format }}</span></div><div class="tiny">{{ userState.exchangeSettings.format_help }}</div></div>
                <div class="inline-actions section-gap"><button class="btn primary" @click="createExchangeCode" :disabled="!selectedExchangeProduct || !exchangeCodeForm.quantity || !exchangeCodeForm.count">鎵归噺鐢熸垚鍏戞崲鐮?/button></div>
                <div v-if="exchangeCodeForm.generatedCodes.length" class="section-gap"><div class="action-row"><strong>鏈鐢熸垚鐨勫厬鎹㈢爜</strong><button class="btn sm ghost" @click="copyGeneratedExchangeCodes">涓€閿鍒跺叏閮?/button></div><textarea class="code-output" readonly :value="exchangeCodeForm.generatedCodes.join('\n')"></textarea></div>
              </div>
              <div class="panel"><h3>浣跨敤璇存槑</h3><div class="desc-list"><div class="desc-item">鍏戞崲鑰呮墦寮€ <a :href="exchangePageUrl" target="_blank" rel="noopener">{{ exchangePageUrl }}</a> 鍗冲彲鍏戞崲</div><div class="desc-item">鐢熸垚鍏戞崲鐮佸彧鏀跺彇鍚庡彴璁剧疆鐨勭敓鎴愭墜缁垂锛涘厬鎹㈠悗璁㈠崟璐圭敤鎸夊厬鎹㈢爜鍒涘缓鏃剁殑鍟嗗搧浠锋牸蹇収浠庣敓鎴愯€呰处鎴锋墸闄ゃ€?/div><div class="desc-item">鍏戞崲鐮侀暱搴﹁嚦灏?48 浣嶏紝鏀寔绯荤粺鍓嶇紑銆侀殢鏈哄瓧绗︿覆鍜岀敤鎴?UID 缁勫悎銆?/div></div></div>
            </div>
            <div class="panel section-gap"><div class="action-row"><h3>鎴戠殑鍏戞崲鐮?/h3><span class="tiny">灞曠ず瀹屾暣鍏戞崲鐮侊紱宸蹭娇鐢ㄦ垨宸查攢姣佺殑鍏戞崲鐮佷笉鍙啀娆＄紪杈戙€?/span></div><div v-if="!userState.exchangeCodes.length" class="empty">鏆傛棤鍏戞崲鐮併€?/div><div v-else class="code-list"><div v-for="row in userState.exchangeCodes" :key="row.id" class="code-item"><div style="min-width:0"><strong class="mono text-break">{{ row.code || row.display_code }}</strong><div class="tiny">{{ row.product_name_snapshot }} 路 {{ row.quantity }} 涓?路 {{ formatDate(row.created_at) }}<span v-if="row.redeemer_qq"> 路 鍏戞崲鑰匭Q {{ row.redeemer_qq }}</span></div></div><div class="inline-actions"><span class="badge" :class="row.status === 'used' ? 'success' : (row.status === 'destroyed' ? 'danger' : 'info')">{{ row.status === 'used' ? '宸插厬鎹? : (row.status === 'destroyed' ? '宸查攢姣? : '鏈娇鐢?) }}</span><button v-if="row.status === 'unused'" class="btn sm ghost" @click="editExchangeCode(row)">缂栬緫</button><button v-if="row.status === 'unused'" class="btn sm danger" @click="destroyExchangeCode(row)">閿€姣?/button></div></div></div></div>
          </div>

          <div v-else-if="userTab === 'recharge'">
            <div class="page-head">
              <div>
                <h2>棰濆害鍏呭€?/h2>
                <p>鍦ㄧ嚎鏀粯濉啓浜烘皯甯侀噾棰濓紝绯荤粺灞曠ず棰勮鍒拌处棰濆害锛涘悓鏃舵敮鎸佸崱瀵嗗厖鍊笺€?/p>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>鍦ㄧ嚎鍏呭€?/h3>
                <div class="form-grid">
                  <div class="field full">
                    <label>鏀粯閫氶亾</label>
                    <select v-model.number="rechargeForm.channel_id">
                      <option value="0">璇烽€夋嫨鏀粯閫氶亾</option>
                      <option v-for="channel in userState.payments.channels" :key="channel.id" :value="Number(channel.id)">{{ channel.name }}锛坽{ channel.pay_type }}锛?/option>
                    </select>
                  </div>
                  <div class="field full">
                    <label>鍏呭€奸噾棰濓紙浜烘皯甯侊級</label>
                    <input v-model.trim="rechargeForm.money" type="number" min="0" step="0.01" placeholder="渚嬪 10.00">
                    <div class="tiny">棰勮鍒拌处锛歿{ money(rechargePreview.credit_amount) }} {{ currency }} + 璧犻€?{{ money(rechargePreview.bonus_amount) }} {{ currency }} = {{ money(rechargePreview.expected_amount) }} {{ currency }}</div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="createRecharge">鍒涘缓鍏呭€艰鍗?/button>
                  <button class="btn ghost" @click="loadUserPayments(true)">鍒锋柊鏀粯閫氶亾</button>
                </div>
              </div>
              <div class="panel">
                <h3>鏀粯淇℃伅</h3>
                <div v-if="!userState.paymentResult" class="placeholder-card">鍒涘缓鍏呭€艰鍗曞悗锛岃繖閲屼細灞曠ず鏀粯閾炬帴銆佷簩缁寸爜涓庨璁″埌璐﹂搴︺€?/div>
                <template v-else>
                  <div class="order-summary-box">
                    <div class="kv">
                      <div>鍏呭€艰鍗曞彿</div><div class="mono">{{ userState.paymentResult.order_no }}</div>
                      <div>鏀粯閲戦</div><div>{{ userState.paymentResult.money_yuan }} 鍏?/div>
                      <div>鍒拌处棰濆害</div><div>{{ money(userState.paymentResult.credit_amount) }} {{ currency }}</div>
                      <div>璧犻€侀搴?/div><div>{{ money(userState.paymentResult.bonus_amount) }} {{ currency }}</div>
                      <div>棰勮鎬诲埌璐?/div><div><strong>{{ money(userState.paymentResult.expected_amount) }} {{ currency }}</strong></div>
                    </div>
                  </div>
                  <div class="qr-box section-gap">
                    <canvas ref="payCanvas"></canvas>
                  </div>
                  <div class="desktop-only-hint">qrcode 瀛楁杩斿洖鐨勬槸瑙ｇ爜鏂囨湰閾炬帴锛岀數鑴戠宸茶浆涓轰簩缁寸爜鏄剧ず锛涙棤璁鸿澶囩被鍨嬶紝涓嬮潰閮芥彁渚涚洿鎺ユ墦寮€鎸夐挳銆?/div>
                  <div class="pay-actions">
                    <a class="btn primary" :href="paymentJumpLink" target="_blank" rel="noopener">鎵撳紑鏀粯閾炬帴</a>
                    <button class="btn ghost" @click="copyPaymentLink">澶嶅埗鏀粯閾炬帴</button>
                  </div>
                </template>
              </div>
            </div>

            <div class="grid-2 section-gap">
              <div class="panel">
                <h3>鍗″瘑鍏呭€?/h3>
                <div class="form-grid">
                  <div class="field full">
                    <label>鍗″瘑</label>
                    <input v-model.trim="cardRedeemCode" placeholder="璇疯緭鍏ュ崱瀵?>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn success" @click="redeemCard">绔嬪嵆鍏戞崲</button>
                </div>
              </div>
              <div class="panel">
                <h3>鏈€杩戝厖鍊艰鍗?/h3>
                <div class="table-wrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>璁㈠崟鍙?/th>
                        <th>閫氶亾</th>
                        <th>閲戦</th>
                        <th>棰勮鍒拌处</th>
                        <th>鐘舵€?/th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="row in userState.payments.orders" :key="row.id">
                        <td class="mono">{{ row.order_no }}</td>
                        <td>{{ row.channel_name || '-' }}</td>
                        <td>{{ row.money_yuan }} 鍏?/td>
                        <td>{{ money(row.expected_amount) }} {{ currency }}</td>
                        <td><span class="badge" :class="badgeTone(row.status)">{{ row.status }}</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'invites'">
            <div class="page-head">
              <div>
                <h2>閭€璇风鐞?/h2>
                <p>涓€涓敤鎴峰彲鎷ユ湁澶氫釜閭€璇风爜锛岄粯璁ゅ彧鐢熸垚涓€涓?20 浣嶉個璇风爜銆傝嚜瀹氫箟閭€璇风爜浼氭寜闀垮害瑙勫垯鎵ｉ櫎棰濆害銆?/p>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>閭€璇风爜鍒楄〃</h3>
                <div v-if="!userState.invites.codes.length" class="empty">鏆傛棤閭€璇风爜銆?/div>
                <div v-else class="code-list">
                  <div class="code-item" v-for="code in userState.invites.codes" :key="code.id">
                    <div>
                      <div class="mono">{{ code.code }}</div>
                      <div class="tiny">闀垮害 {{ code.length }} 路 宸蹭娇鐢?{{ code.used_count }} 娆?/div>
                    </div>
                    <div class="inline-actions">
                      <span class="badge" :class="code.is_default ? 'success' : 'info'">{{ code.is_default ? '榛樿閭€璇风爜' : '鑷畾涔夐個璇风爜' }}</span>
                      <span class="badge">鏀粯 {{ money(code.price_paid || 0) }} {{ currency }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel">
                <h3>鍒涘缓閭€璇风爜</h3>
                <div class="form-grid">
                  <div class="field">
                    <label>闅忔満閭€璇风爜鐨勯暱搴?/label>
                    <input v-model.number="inviteForm.length" type="number" min="6" max="48">
                  </div>
                  <div class="field">
                    <label>棰勮浠锋牸</label>
                    <input :value="money(invitePricePreview) + ' ' + currency" readonly>
                  </div>
                  <div class="field full">
                    <label>鑷畾涔夐個璇风爜锛堝彲閫夛級</label>
                    <input v-model.trim="inviteForm.code" placeholder="6-48浣嶈嫳鏂囨垨鏁板瓧锛屽尯鍒嗗ぇ灏忓啓锛涚暀绌哄垯闅忔満鐢熸垚">
                  </div>
                </div>
                <div class="auth-footnote">榛樿閭€璇风爜涓嶆墸璐癸紱鑷畾涔夐個璇风爜浠锋牸閬靛惊鍚庡彴閰嶇疆鐨勫浐瀹氫环鎴栨寜闀垮害璁′环瑙勫垯銆?/div>
                <div class="inline-actions">
                  <button class="btn primary" @click="createInviteCode">鍒涘缓閭€璇风爜</button>
                </div>
              </div>
            </div>
            <div class="panel section-gap">
              <h3>閭€璇疯褰?/h3>
              <div class="table-wrap">
                <table class="table">
                  <thead>
                    <tr>
                      <th>琚個璇风敤鎴?/th>
                      <th>鏄惁鏈夋晥閭€璇?/th>
                      <th>鏈夋晥鏃堕棿</th>
                      <th>璁板綍鏃堕棿</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="row in userState.invites.records" :key="row.id">
                      <td>{{ row.invitee_nickname || row.invitee_username || ('#' + row.invitee_id) }}</td>
                      <td><span class="badge" :class="row.became_valid ? 'success' : 'warning'">{{ row.became_valid ? '鏈夋晥閭€璇? : '寰呰揪鎴? }}</span></td>
                      <td>{{ formatDate(row.valid_at) }}</td>
                      <td>{{ formatDate(row.created_at) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'groups'">
            <div class="page-head">
              <div>
                <h2>浠ｇ悊绛夌骇</h2>
                <p>鍒楀嚭鍏ㄩ儴鐢ㄦ埛缁勩€佸崌绾ф潯浠跺拰浠嬬粛锛涙弧瓒虫潯浠跺悗鍙墜鍔ㄨЕ鍙戝崌绾ф鏌ャ€?/p>
              </div>
              <div class="inline-actions">
                <button class="btn primary" @click="claimUserGroup">妫€娴嬪苟灏濊瘯鍗囩骇</button>
              </div>
            </div>
            <div class="grid-2">
              <div v-for="group in userState.groups" :key="group.id" class="level-card" :class="{active: profile.group && profile.group.id===group.id}">
                <div class="card-title">
                  <div>
                    <h4>{{ group.name }}</h4>
                    <div class="tiny mono">缁処D锛歿{ group.group_code }}</div>
                  </div>
                  <span class="badge" :class="profile.group && profile.group.id===group.id ? 'success' : 'info'">{{ profile.group && profile.group.id===group.id ? '褰撳墠绛夌骇' : '鍙崌绾х瓑绾? }}</span>
                </div>
                  <div class="desc-list" style="margin-top:12px">
                    <div class="desc-item">闂ㄦ妯″紡锛歿{ thresholdModeLabel(group.threshold_mode) }}</div>
                    <div class="desc-item">闂ㄦ鏁板€硷細{{ money(group.threshold_value) }}</div>
                    <div class="desc-item">鍏呭€艰禒閫侊細{{ Number(group.recharge_bonus_rate || 1).toFixed(2) }} 鍊?/div>
                    <div class="desc-item">瀵规帴榛樿锛歿{ group.allow_api_default ? '鍏佽' : '涓嶅厑璁? }}</div>
                  </div>
                <div v-if="group.description" class="feed-note" style="margin-top:12px">{{ group.description }}</div>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'profile'">
            <div class="page-head">
              <div>
                <h2>涓汉璧勬枡涓庡瘑鐮?/h2>
                <p>浣犲彲浠ラ殢鏃朵慨鏀?QQ銆佹樀绉般€侀偖绠便€佹墜鏈哄彿绛夎祫鏂欙紝骞跺湪姝ら噸缃瘑鐮併€傚ご鍍忓浐瀹氫娇鐢?QQ 澶村儚銆?/p>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>缂栬緫涓汉璧勬枡</h3>
                <div class="form-grid">
                  <div class="field">
                    <label>鐢ㄦ埛鍚?/label>
                    <input :value="profile.user ? profile.user.username : ''" readonly>
                  </div>
                  <div class="field">
                    <label>鏄电О</label>
                    <input v-model.trim="profileForm.nickname" placeholder="鏄电О">
                  </div>
                  <div class="field">
                    <label>QQ鍙?/label>
                    <input v-model.trim="profileForm.qq" placeholder="QQ鍙?>
                    <div class="qq-preview" v-if="profileForm.qq"><img :src="qqAvatar(profileForm.qq)" alt="qq"><div class="tiny">QQ 澶村儚棰勮</div></div>
                  </div>
                  <div class="field">
                    <label>閭</label>
                    <input v-model.trim="profileForm.email" placeholder="閭">
                  </div>
                  <div class="field">
                    <label>鎵嬫満鍙?/label>
                    <input v-model.trim="profileForm.mobile" placeholder="鎵嬫満鍙?>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveProfile">淇濆瓨璧勬枡</button>
                </div>
              </div>
              <div class="panel">
                <h3>淇敼瀵嗙爜</h3>
                <div class="form-grid">
                  <div class="field full">
                    <label>鏃у瘑鐮?/label>
                    <input v-model="passwordForm.old_password" type="password" placeholder="璇疯緭鍏ユ棫瀵嗙爜">
                  </div>
                  <div class="field full">
                    <label>鏂板瘑鐮?/label>
                    <input v-model="passwordForm.new_password" type="password" placeholder="璇疯緭鍏ユ柊瀵嗙爜">
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn warning" @click="changePassword">鏇存柊瀵嗙爜</button>
                </div>
                <div class="auth-footnote">淇敼瀵嗙爜鎴愬姛鍚庯紝涓嬫鐧诲綍璇蜂娇鐢ㄦ柊瀵嗙爜銆?/div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section v-else>
      <div v-if="!canAccessAdmin" class="auth-box" style="max-width:620px;margin:0 auto;">
        <h3>鏃犲悗鍙拌闂潈闄?/h3>
        <p class="panel-sub">绠＄悊鍚庡彴涓庣敤鎴峰悗鍙板畬鍏ㄥ垎绂汇€傝鍏堝墠寰€缁熶竴鐧诲綍椤碉紝浣跨敤绠＄悊鍛樻垨绔欓暱璐﹀彿鐧诲綍鍚庡啀杩涘叆鍚庡彴銆?/p>
        <div class="inline-actions">
          <a class="btn primary" :href="routeUrl('/login')">鍓嶅線缁熶竴鐧诲綍</a>
          <a class="btn ghost" :href="routeUrl('/')">杩斿洖棣栭〉</a>
        </div>
      </div>

      <div v-else class="layout admin-layout" :class="{collapsed:adminSidebarCollapsed}">
        <aside class="sidebar admin-sidebar" :class="{collapsed:adminSidebarCollapsed}">
          <div class="side-profile">
            <div class="side-profile-card admin-side-profile-card">
              <div class="inline-avatar">
                <img :src="qqAvatar(user.qq)" alt="avatar">
                <div v-if="!adminSidebarCollapsed">
                  <strong style="font-size:16px;margin:0">{{ displayName(user) }}</strong>
                  <div class="tiny">{{ roleLabel(user) }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="side-title" v-if="!adminSidebarCollapsed">绠＄悊鍚庡彴</div>
          <div class="admin-menu">
            <template v-for="item in adminNav" :key="item.key">
              <button v-if="!item.children || !item.children.length" class="nav-item admin-menu-toggle" :class="{active:adminTab===item.key}" @click="switchAdminTab(item.key)">
                <span class="nav-item-label">{{ item.label }}</span>
              </button>
              <div v-else class="admin-menu-group">
                <button class="nav-item admin-menu-toggle" :class="{active:adminCurrentMeta.parent===item.key}" @click="toggleAdminMenu(item.key)">
                  <span class="nav-item-label">{{ item.label }}</span>
                  <span v-if="!adminSidebarCollapsed" class="tiny">{{ adminMenuOpenKeys[item.key] ? '鈭? : '+' }}</span>
                </button>
                <div v-if="!adminSidebarCollapsed && adminMenuOpenKeys[item.key]" class="admin-submenu">
                  <button v-for="child in item.children" :key="child.key" class="admin-submenu-item" :class="{active:adminTab===child.key}" @click="switchAdminTab(child.key)">
                    <span>{{ child.label }}</span>
                  </button>
                </div>
              </div>
            </template>
          </div>
        </aside>

        <div class="content-area admin-main">
          <div class="panel admin-topbar">
            <div>
              <h2>{{ adminCurrentMeta.label }}</h2>
              <p>{{ adminCurrentMeta.description }}</p>
            </div>
            <div class="inline-actions">
              <button class="btn ghost admin-sidebar-toggle" @click="setAdminSidebarCollapsed()">{{ adminSidebarCollapsed ? '灞曞紑鑿滃崟' : '鏀惰捣鑿滃崟' }}</button>
            </div>
          </div>
          <div v-if="adminTab === 'dashboard'">
            <div class="page-head">
              <div>
                <h2>绠＄悊棣栭〉</h2>
                <p>姒傝浠婃棩璁㈠崟銆佹€荤敤鎴枫€佸埄娑︽帓琛屻€佷綑棰濇帓琛屼笌涓婃父浣欓銆?/p>
              </div>
              <div class="inline-actions">
                <button class="btn ghost" @click="loadAdminDashboard(true)">鍒锋柊鏁版嵁</button>
              </div>
            </div>
            <div class="stats-grid" v-if="adminState.dashboard">
              <div class="stat"><small>浠婃棩璁㈠崟鏁?/small><strong>{{ adminState.dashboard.orders_today }}</strong></div>
              <div class="stat"><small>鎬荤敤鎴锋暟</small><strong>{{ adminState.dashboard.users_total }}</strong></div>
              <div class="stat"><small>浠婃棩鍒╂鼎</small><strong>{{ money(adminState.dashboard.profit_today) }}</strong><span class="amount-yuan">{{ yuanApprox(adminState.dashboard.profit_today) }}</span></div>
              <div class="stat"><small>鐢ㄦ埛鎬讳綑棰?/small><strong>{{ money(adminState.dashboard.balance_total) }}</strong><span class="amount-yuan">{{ yuanApprox(adminState.dashboard.balance_total) }}</span></div>
            </div>
            <div class="grid-2 section-gap" v-if="adminState.dashboard">
              <div class="panel">
                <h3>鎺掕姒?/h3>
                <div class="pill-nav">
                  <span class="badge info">涓婃父浣欓锛歿{ adminState.dashboard.upstream_balance === null ? '鑾峰彇澶辫触' : money(adminState.dashboard.upstream_balance) }}<small v-if="adminState.dashboard.upstream_balance !== null" class="amount-yuan inline">{{ yuanApprox(adminState.dashboard.upstream_balance) }}</small></span>
                </div>
                <div class="table-wrap section-gap">
                  <table class="table">
                    <thead><tr><th>浠婃棩娑堣垂鎺掕</th><th>鎬绘秷璐规帓琛?/th><th>浣欓鎺掕</th><th>浠婃棩鍏呭€兼帓琛?/th></tr></thead>
                    <tbody>
                      <tr v-for="i in maxDashboardRankLength" :key="i">
                        <td>{{ rankText(adminState.dashboard.today_consume_rank, i-1, 'total') }}</td>
                        <td>{{ rankText(adminState.dashboard.total_consume_rank, i-1, 'total_consume') }}</td>
                        <td>{{ rankText(adminState.dashboard.balance_rank, i-1, 'balance') }}</td>
                        <td>{{ rankText(adminState.dashboard.today_recharge_rank, i-1, 'total') }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="panel">
                <h3>蹇嵎鍏ュ彛</h3>
                <div class="quick-grid">
                  <button class="quick-card" @click="switchAdminTab('products-list')"><h3>鍟嗗搧绠＄悊</h3></button>
                  <button class="quick-card" @click="switchAdminTab('users-list')"><h3>鐢ㄦ埛绠＄悊</h3></button>
                  <button class="quick-card" @click="switchAdminTab('orders-list')"><h3>璁㈠崟绠＄悊</h3></button>
                  <button class="quick-card" @click="switchAdminTab('api-conditions')"><h3>瀵规帴璁剧疆</h3></button>
                  <button class="quick-card" @click="switchAdminTab('cards-generate')"><h3>鍏呭€艰缃?/h3></button>
                  <button class="quick-card" @click="switchAdminTab('settings-basic')"><h3>绯荤粺璁剧疆</h3></button>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['products-sync','products-list'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'products-sync' ? '鏇存柊鍟嗗搧鏁版嵁' : '鍟嗗搧绠＄悊' }}</h2>
                <p>{{ adminTab === 'products-sync' ? '浠庝笂娓搁噸鏂版媺鍙栧晢鍝侊紝骞舵洿鏂版湰鍦板晢鍝佸熀纭€淇℃伅銆? : '鎺у埗鍟嗗搧鏄惁鍏佽鍓嶅彴涓嬪崟銆佹帴鍙ｅ鎺ュ強鏁伴噺鎶樻墸銆? }}</p>
              </div>
              <div class="inline-actions">
                <button v-if="adminTab === 'products-sync'" class="btn primary" @click="syncProducts">绔嬪嵆鍚屾鍟嗗搧</button>
                <button v-if="adminTab === 'products-list'" class="btn ghost" @click="loadAdminProducts(true)">鍒锋柊鍒楄〃</button>
              </div>
            </div>
            <div v-if="adminTab === 'products-sync'" class="panel">
              <h3>鍚屾涓婃父鍟嗗搧</h3>
              <p class="panel-sub">鍚屾浼氭洿鏂板晢鍝佸悕绉般€佷环鏍笺€佹暟閲忚寖鍥村拰杈撳叆瀛楁锛涙湰鍦拌缃殑鍓嶅彴寮€鍏炽€佸鎺ュ紑鍏充笌鎶樻墸瑙勫垯浠嶅彲鍦ㄢ€滅鐞嗗晢鍝佲€濅腑缁存姢銆?/p>
              <div class="inline-actions section-gap"><button class="btn primary" @click="syncProducts">鏇存柊鍟嗗搧鏁版嵁</button></div>
            </div>
            <div v-if="adminTab === 'products-list'" class="product-grid">
              <div class="product-card" v-for="product in adminState.products" :key="product.id">
                <div class="card-title">
                  <div>
                    <h3>{{ product.name }}</h3>
                    <div class="tiny mono">{{ product.upstream_sign }}</div>
                  </div>
                  <span class="badge" :class="product.enabled_bool ? 'success' : 'danger'">{{ product.enabled_bool ? '宸插惎鐢? : '宸插仠鐢? }}</span>
                </div>
                <div class="product-meta">
                  <div class="subtle"><span>鍓嶅彴涓嬪崟</span><label><input type="checkbox" v-model="product.allow_frontend_bool"> 鍏佽</label></div>
                  <div class="subtle"><span>鍏佽瀵规帴</span><label><input type="checkbox" v-model="product.allow_api_bool"> 鍏佽</label></div>
                  <div class="subtle"><span>鍟嗗搧鐘舵€?/span><label><input type="checkbox" v-model="product.enabled_bool"> 鍚敤</label></div>
                  <div class="subtle product-sort-field"><span>鎺掑簭浼樺厛绾?/span><div class="sort-input-wrap"><input v-model.number="product.sort_order" class="sort-priority-input" type="number" step="1" inputmode="numeric" aria-label="鍟嗗搧鎺掑簭浼樺厛绾?><span class="sort-hint">鏁板瓧瓒婂皬瓒婇潬鍓?/span></div></div>
                  <div class="subtle"><span>鑼冨洿</span><strong>{{ product.min_num }} - {{ product.max_num }} / 姝ラ暱 {{ product.step_num }}</strong></div>
                </div>
                <div class="section-gap">
                  <div class="card-title"><h4>鏁伴噺鎶樻墸</h4><button class="btn sm ghost" @click="addProductDiscount(product)">鏂板鎶樻墸</button></div>
                  <div v-if="!product.discounts.length" class="placeholder-card" style="padding:14px">鏆傛棤鎶樻墸瑙勫垯銆?/div>
                  <div v-else class="editor-list">
                    <div class="editor-row" v-for="(discount,index) in product.discounts" :key="index">
                      <div class="field"><label>杈惧埌鏁伴噺</label><input v-model.number="discount.min_quantity" type="number" min="1"></div>
                      <div class="field"><label>鎶樻墸鍊嶇巼</label><input v-model.number="discount.discount_rate" type="number" min="0.01" max="1" step="0.01"></div>
                      <button class="btn sm danger" @click="removeProductDiscount(product, index)">鍒犻櫎</button>
                    </div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveProduct(product)">淇濆瓨鍟嗗搧璁剧疆</button>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['groups-list','groups-default'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'groups-default' ? '榛樿鐢ㄦ埛缁? : '鐢ㄦ埛缁勭鐞? }}</h2>
                <p>{{ adminTab === 'groups-default' ? '閫夋嫨鏂扮敤鎴锋敞鍐屽悗榛樿鍔犲叆鐨勭敤鎴风粍銆? : '鏂板鎴栫紪杈戠敤鎴风粍锛岄厤缃棬妲涖€佸姞浠枫€佸厖鍊艰禒閫佷笌瀵规帴榛樿鍊笺€? }}</p>
              </div>
            </div>
            <div v-if="adminTab === 'groups-list'" class="grid-2">
              <div class="panel">
                <h3>{{ groupForm.id ? '缂栬緫鐢ㄦ埛缁? : '鏂板鐢ㄦ埛缁? }}</h3>
                <div class="form-grid">
                  <div class="field"><label>鐢ㄦ埛缁処D</label><input v-model.trim="groupForm.group_code" placeholder="渚嬪 VIP"></div>
                  <div class="field"><label>鐢ㄦ埛缁勫悕绉?/label><input v-model.trim="groupForm.name" placeholder="鍚嶇О"></div>
                  <div class="field full"><label>浠嬬粛璇存槑</label><textarea v-model.trim="groupForm.description" placeholder="鐢ㄦ埛鍓嶅彴鍙鐨勪粙缁?></textarea></div>
                  <div class="field"><label>闂ㄦ妯″紡</label><select v-model="groupForm.threshold_mode"><option value="none">鏃犻棬妲?/option><option value="total_recharge">绱鍏呭€?/option><option value="total_consume">绱娑堣垂</option><option value="invite_count">閭€璇风敤鎴锋暟</option><option value="balance">浣欓澶т簬绛変簬</option></select></div>
                  <div class="field"><label>闂ㄦ鏁伴</label><input v-model.number="groupForm.threshold_value" type="number" min="0"><div v-if="['total_recharge','total_consume','balance'].includes(groupForm.threshold_mode)" class="amount-yuan">{{ yuanApprox(groupForm.threshold_value) }}</div></div>
                  <div class="field"><label>鍔犱环妯″紡</label><select v-model="groupForm.markup_mode"><option value="fixed">鍥哄畾鍔犱环</option><option value="percent">鐧惧垎姣斿姞浠?/option></select></div>
                  <div class="field"><label>鍔犱环鏁伴</label><input v-model.number="groupForm.markup_value" type="number" step="0.01"><div class="tiny">鍥哄畾鍔犱环锛氫笂娓镐环 1000銆佸～ 200锛岀敤鎴蜂环涓?1200锛涚櫨鍒嗘瘮鍔犱环锛氫笂娓镐环 1000銆佸～ 20锛岀敤鎴蜂环涓?1200銆傝鍕挎妸 20% 鍐欐垚 0.2銆?/div></div>
                  <div class="field"><label>鍏呭€艰禒閫佸€嶇巼</label><input v-model.number="groupForm.recharge_bonus_rate" type="number" min="0.01" step="0.01"><div class="tiny">鍊嶇巼 1 琛ㄧず涓嶈禒閫侊紱1.1 琛ㄧず鍏呭€煎埌璐﹂搴﹂澶栧鍔?10%銆傚€嶇巼瓒婇珮锛屽钩鍙板疄闄呮壙鎷呯殑璧犻€佹垚鏈秺楂樸€?/div></div>
                  <div class="field"><label>鎺掑簭鏉冮噸</label><input v-model.number="groupForm.sort_order" type="number"></div>
                  <div class="field"><label>浣欓涓嶈冻鏃跺彲闄嶇骇</label><select v-model.number="groupForm.downgrade_on_balance"><option :value="0">鍚?/option><option :value="1">鏄?/option></select></div>
                  <div class="field"><label>榛樿鍏佽瀵规帴</label><select v-model.number="groupForm.allow_api_default"><option :value="0">鍚?/option><option :value="1">鏄?/option></select></div>
                </div>
                <div class="section-gap">
                  <h4>鍟嗗搧鍥哄畾浠锋牸</h4>
                  <p class="panel-sub">鍥哄畾浠锋牸鎸夊晢鍝佽浠峰崟浣嶅～鍐欙紝灏嗘浛浠ｆ湰鐢ㄦ埛缁勭殑鍔犱环瑙勫垯锛涙湭濉啓鐨勫晢鍝佺户缁寜涓婃柟鍔犱环瑙勫垯璁＄畻锛屽晢鍝佹暟閲忔姌鎵ｄ粛浼氱敓鏁堛€?/p>
                  <div v-if="!adminState.products.length" class="placeholder-card" style="padding:14px">鏆傛棤鍟嗗搧锛岃鍏堝悓姝ュ晢鍝佹暟鎹€?/div>
                  <div v-else class="editor-list">
                    <div class="code-item" v-for="product in adminState.products" :key="product.id">
                      <div>
                        <strong>{{ product.name }}</strong>
                        <div class="tiny">姣?{{ product.step_num }} 鏁伴噺涓轰竴涓浠峰崟浣?路 涓婃父鎴愭湰 {{ money(product.price_cost) }} {{ currency }}</div>
                      </div>
                      <div class="field">
                        <label>鍥哄畾浠锋牸锛堢暀绌哄垯璺熼殢鍔犱环锛?/label>
                        <input v-model="groupForm.product_prices[String(product.id)]" type="number" min="0" step="1" placeholder="鐣欑┖">
                        <div v-if="groupForm.product_prices[String(product.id)] !== '' && groupForm.product_prices[String(product.id)] != null" class="amount-yuan">{{ yuanApprox(groupForm.product_prices[String(product.id)]) }}</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveGroup">淇濆瓨鐢ㄦ埛缁?/button>
                  <button class="btn ghost" @click="resetGroupForm">娓呯┖琛ㄥ崟</button>
                </div>
              </div>
              <div class="panel">
                <h3>鐢ㄦ埛缁勫垪琛?/h3>
                <div class="code-list">
                  <div class="code-item" v-for="group in adminState.groups" :key="group.id">
                    <div>
                      <strong>{{ group.name }}</strong>
                      <div class="tiny mono">{{ group.group_code }} 路 {{ thresholdModeLabel(group.threshold_mode) }} / {{ money(group.threshold_value) }} <span v-if="['total_recharge','total_consume','balance'].includes(group.threshold_mode)" class="amount-yuan inline">{{ yuanApprox(group.threshold_value) }}</span></div>
                    </div>
                    <div class="inline-actions">
                      <span class="badge" :class="group.is_default_register ? 'success' : 'info'">{{ group.is_default_register ? '榛樿娉ㄥ唽缁? : '鏅€氱粍' }}</span>
                      <button class="btn sm ghost" @click="editGroup(group)">缂栬緫</button>
                      <button class="btn sm primary" @click="setDefaultGroup(group)">璁句负榛樿</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="adminTab === 'groups-default'" class="panel">
              <h3>榛樿娉ㄥ唽鐢ㄦ埛缁?/h3>
              <p class="panel-sub">鏂版敞鍐岀敤鎴蜂細鑷姩鍔犲叆鎵€閫夌敤鎴风粍銆備慨鏀瑰悗鍙奖鍝嶅悗缁敞鍐岀敤鎴凤紝涓嶄細鎵归噺鍙樻洿宸叉湁鐢ㄦ埛銆?/p>
              <div class="code-list section-gap">
                <div class="code-item" v-for="group in adminState.groups" :key="group.id">
                  <div><strong>{{ group.name }}</strong><div class="tiny mono">{{ group.group_code }}</div></div>
                  <div class="inline-actions"><span v-if="group.is_default_register" class="badge success">褰撳墠榛樿</span><button v-else class="btn sm primary" @click="setDefaultGroup(group)">璁句负榛樿</button></div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['users-list','users-create','api-keys'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'users-create' ? (userForm.id ? '缂栬緫鐢ㄦ埛' : '鏂板鐢ㄦ埛') : (adminTab === 'api-keys' ? 'API Key 绠＄悊' : '鐢ㄦ埛鍒楄〃') }}</h2>
                <p>{{ adminTab === 'users-create' ? '鍒涘缓鎴栦慨鏀圭敤鎴疯祫鏂欍€佷綑棰濄€佺敤鎴风粍鍜岃处鍙锋潈闄愩€? : (adminTab === 'api-keys' ? '鏌ョ湅鎵€鏈夊凡鐢熸垚 API Key 鐨勭敤鎴峰苟涓哄叾閲嶇疆瀵嗛挜銆? : '鎼滅储銆佹煡鐪嬨€佸皝绂佹垨鍒犻櫎绯荤粺鐢ㄦ埛銆?) }}</p>
              </div>
              <div v-if="adminTab === 'users-list'" class="search-row" style="max-width:320px;width:100%">
                <div class="field full" style="margin:0"><label>鎼滅储鐢ㄦ埛</label><input v-model.trim="adminState.userKeyword" placeholder="鐢ㄦ埛鍚?/ QQ / UID"></div>
              </div>
            </div>
            <div class="section-stack">
              <div v-if="adminTab === 'users-create'" class="panel">
                <h3>{{ userForm.id ? '缂栬緫鐢ㄦ埛' : '鏂板鐢ㄦ埛' }}</h3>
                <div class="form-grid">
                  <div class="field"><label>鐢ㄦ埛鍚?/label><input v-model.trim="userForm.username" placeholder="鑻辨枃鏁板瓧"></div>
                  <div class="field"><label>鏄电О</label><input v-model.trim="userForm.nickname" placeholder="鏄电О"></div>
                  <div class="field"><label>QQ鍙?/label><input v-model.trim="userForm.qq" placeholder="QQ鍙?></div>
                  <div class="field"><label>閭</label><input v-model.trim="userForm.email" placeholder="閭"></div>
                  <div class="field"><label>鎵嬫満鍙?/label><input v-model.trim="userForm.mobile" placeholder="鎵嬫満鍙?></div>
                  <div class="field"><label>瀵嗙爜</label><input v-model="userForm.password" type="password" :placeholder="userForm.id ? '鐣欑┖鍒欎笉淇敼瀵嗙爜' : '鏂板鐢ㄦ埛蹇呴』濉啓' "></div>
                  <div class="field"><label>浣欓</label><input v-model.number="userForm.balance" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(userForm.balance) }}</div></div>
                  <div class="field"><label>鎵€灞炵敤鎴风粍</label><select v-model.number="userForm.user_group_id"><option v-for="group in adminState.groups" :key="group.id" :value="Number(group.id)">{{ group.name }}</option></select></div>
                  <div class="field"><label>璐﹀彿鐘舵€?/label><select v-model="userForm.status"><option value="active">姝ｅ父</option><option value="banned">灏佺</option></select></div>
                  <div class="field"><label>瑙掕壊</label><select v-model="userForm.account_role" :disabled="userForm.account_role === 'owner'"><option v-if="userForm.account_role === 'owner'" value="owner">Owner锛堥攣瀹氾級</option><option value="member">User</option><option value="agent">Agent</option><option v-if="isOwner" value="admin">Admin</option></select><div v-if="userForm.account_role === 'owner'" class="tiny">绔欓暱韬唤宸查攣瀹氾紝鍓嶅彴涓庡悗鍙伴兘涓嶈兘鍦ㄨ繖閲屾敼鎴愬叾浠栬韩浠斤紝涔熶笉鑳芥妸鍏朵粬鐢ㄦ埛鏀规垚绔欓暱銆?/div></div>
                  <div class="field"><label>瀵规帴绛栫暐</label><select v-model="userForm.connect_policy"><option value="default">璺熼殢鐢ㄦ埛缁?/option><option value="agent">鍏佽瀵规帴</option><option value="user">绂佹瀵规帴</option></select><div class="tiny">閫夋嫨鈥滆窡闅忕敤鎴风粍鈥濇椂锛屾槸鍚﹀厑璁稿鎺ョ敱鎵€灞炵敤鎴风粍鐨勮缃喅瀹氥€?/div></div>
                  <div class="field full" v-if="userForm.id">
                    <label>鍙淇℃伅</label>
                    <div class="kv-box">
                      <div class="tiny">娉ㄥ唽鏃堕棿锛歿{ formatDate(userForm.created_at) }} 锝?涓婃鐧诲綍锛歿{ formatDate(userForm.last_login_at) }} 锝?涓婃鐧诲綍IP锛歿{ userForm.last_login_ip || '-' }} 锝?閭€璇风敤鎴锋暟锛歿{ userForm.invite_count || 0 }}</div>
                    </div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveAdminUser">淇濆瓨鐢ㄦ埛</button>
                  <button class="btn ghost" @click="resetUserForm">娓呯┖琛ㄥ崟</button>
                </div>
              </div>
              <div v-if="adminTab === 'users-list'" class="panel">
                <div class="action-row">
                  <div><h3>鐢ㄦ埛鍒楄〃</h3><p class="panel-sub">姝ゅ垪琛ㄥ寘鍚櫘閫氱敤鎴枫€佷唬鐞嗐€佺鐞嗗憳鍜岀珯闀裤€傜珯闀胯韩浠藉浐瀹氫笉鍙慨鏀癸紝浠讳綍浜洪兘涓嶈兘鎶婄敤鎴锋敼涓虹珯闀裤€?/p></div>
                  <div class="inline-actions">
                    <button class="btn ghost" @click="loadAdminUsers(true)">鍒锋柊鍒楄〃</button>
                  </div>
                </div>
                <div class="table-wrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>UID</th><th>鐢ㄦ埛</th><th>瑙掕壊</th><th>浣欓</th><th>鐢ㄦ埛缁?/th><th>鐘舵€?/th><th>鎿嶄綔</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="row in filteredAdminUsers" :key="row.id">
                        <td class="mono">{{ row.uid }}</td>
                        <td>
                          <div>{{ row.nickname || row.username }}</div>
                          <div class="compact">{{ row.username }} 路 QQ {{ row.qq || '-' }}</div>
                        </td>
                        <td>{{ row.role_label }}</td>
                        <td>{{ money(row.balance) }}<div class="amount-yuan">{{ yuanApprox(row.balance) }}</div></td>
                        <td>{{ row.group_name || '-' }}</td>
                        <td><span class="badge" :class="row.status==='active' ? 'success' : 'danger'">{{ row.status }}</span></td>
                        <td class="actions-cell">
                          <button class="btn sm ghost" @click="editUser(row)" :disabled="!isOwner && ['owner','admin'].includes(String(row.account_role || ''))">缂栬緫</button>
                          <button class="btn sm warning" @click="resetUserApiKey(row)" :disabled="connectPolicyOf(row) !== 'agent'">閲嶇疆Key</button>
                          <button class="btn sm danger" @click="softDeleteUser(row)" :disabled="String(row.account_role || '') === 'owner' || (!isOwner && String(row.account_role || '') === 'admin')">鍒犻櫎</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-if="adminTab === 'api-keys'" class="panel">
                <div class="action-row"><div><h3>宸茬敓鎴?API Key 鐨勭敤鎴?/h3><p class="panel-sub">杩欓噷鏄剧ず鎵€鏈夋嫢鏈?API Key 鐨勭敤鎴凤紝鍖呮嫭鏅€氱敤鎴枫€佷唬鐞嗐€佺鐞嗗憳鍜岀珯闀匡紝涓嶅彈褰撳墠瀵规帴绛栫暐褰卞搷銆?/p></div><button class="btn ghost" @click="loadAdminUsers(true, true)">鍒锋柊鍒楄〃</button></div>
                <div class="table-wrap section-gap"><table class="table"><thead><tr><th>鐢ㄦ埛</th><th>韬唤</th><th>绛栫暐</th><th>UID</th><th>API Key</th><th>鎿嶄綔</th></tr></thead><tbody><tr v-for="row in apiKeyUsers" :key="row.id"><td>{{ row.nickname || row.username }}</td><td>{{ row.role_label || row.account_role }}</td><td>{{ connectPolicyLabel(connectPolicyOf(row)) }}</td><td class="mono">{{ row.uid }}</td><td class="wrap mono">{{ row.api_key }}</td><td><button class="btn sm warning" @click="resetUserApiKey(row)">閲嶇疆 Key</button></td></tr><tr v-if="!apiKeyUsers.length"><td colspan="6" class="muted">鏆傛棤宸茬敓鎴?API Key 鐨勭敤鎴?/td></tr></tbody></table></div>
              </div>
            </div>
          </div>

          <div v-else-if="adminTab === 'orders-list'">
            <div class="page-head">
              <div>
                <h2>璁㈠崟绠＄悊</h2>
                <p>鍙悓姝ユ墍鏈夋湭瀹屾垚璁㈠崟鐘舵€侊紝骞舵墽琛屽悜涓婃父琛ュ崟銆侀€€鍗曟垨缁欑敤鎴蜂粎閫€娆俱€?/p>
              </div>
              <div class="inline-actions">
                <button class="btn primary" @click="syncAdminOrders">鏇存柊閫熷埛璁㈠崟</button>
                <button class="btn ghost" @click="loadAdminOrders(true)">鍒锋柊鍒楄〃</button>
              </div>
            </div>
            <div class="panel">
              <div class="action-row">
                <div><h3>璁㈠崟鏌ュ崟</h3><p class="panel-sub">杈撳叆绯荤粺璁㈠崟鍙锋垨涓婃父璁㈠崟鍙凤紝绯荤粺浼氭煡璇㈣鍗曞苟鍚屾鍙洿鏂扮殑涓婃父鐘舵€併€?/p></div>
              </div>
              <div class="search-row section-gap">
                <div class="field" style="margin:0"><label>璁㈠崟鍙?/label><input v-model.trim="adminState.orderSearch" placeholder="绯荤粺璁㈠崟鍙?/ 涓婃父璁㈠崟鍙? @keyup.enter="searchAdminOrder()"></div>
                <button class="btn primary" @click="searchAdminOrder()">鏌ュ崟</button>
                <button v-if="adminState.orderDetail" class="btn ghost" @click="clearAdminOrderDetail">娓呴櫎缁撴灉</button>
              </div>
              <div v-if="adminState.orderDetail" class="order-summary-box section-gap">
                <div class="order-summary-grid">
                  <div class="subtle"><span>绯荤粺璁㈠崟鍙?/span><strong class="mono wrap">{{ adminState.orderDetail.display_order_no || adminState.orderDetail.order_no }}</strong></div>
                  <div class="subtle"><span>涓婃父璁㈠崟鍙?/span><strong class="mono wrap">{{ adminState.orderDetail.upstream_order_no || '-' }}</strong></div>
                  <div class="subtle"><span>鐢ㄦ埛</span><strong>{{ adminState.orderDetail.nickname || adminState.orderDetail.username || ('#' + adminState.orderDetail.user_id) }}</strong></div>
                  <div class="subtle"><span>鐘舵€?/span><strong><span class="badge" :class="badgeTone(adminState.orderDetail.state)">{{ adminState.orderDetail.state }}</span></strong></div>
                  <div class="subtle"><span>鍟嗗搧</span><strong>{{ adminState.orderDetail.product_name || '-' }}</strong></div>
                  <div class="subtle"><span>涓嬪崟 QQ</span><strong>{{ adminState.orderDetail.target_qq || '-' }}</strong></div>
                  <div class="subtle"><span>鏁伴噺</span><strong>{{ adminState.orderDetail.quantity }}</strong></div>
                  <div class="subtle"><span>杩涘害锛堝紑濮?/ 褰撳墠 / 缁撴潫锛?/span><strong>{{ adminState.orderDetail.start_num ?? '-' }} / {{ adminState.orderDetail.current_num ?? '-' }} / {{ adminState.orderDetail.finish_num ?? '-' }}</strong></div>
                  <div class="subtle"><span>鐢ㄦ埛鑺辫垂</span><strong>{{ money(adminState.orderDetail.user_price) }} <small class="amount-yuan">{{ yuanApprox(adminState.orderDetail.user_price) }}</small></strong></div>
                  <div class="subtle"><span>鎴愭湰 / 鍒╂鼎</span><strong>{{ money(adminState.orderDetail.cost_price) }} / {{ money(adminState.orderDetail.profit) }}</strong></div>
                  <div class="subtle"><span>鍒涘缓鏃堕棿</span><strong>{{ formatDate(adminState.orderDetail.created_at) }}</strong></div>
                  <div class="subtle"><span>鏈€鍚庡悓姝?/span><strong>{{ formatDate(adminState.orderDetail.last_sync_at || adminState.orderDetail.updated_at) }}</strong></div>
                </div>
                <div class="tip-box section-gap"><div class="note-strong">澶囨敞锛歿{ adminState.orderDetail.latest_message || '鏃? }}</div></div>
              </div>
            </div>
            <div class="panel">
              <div class="table-wrap">
                <table class="table">
                  <thead>
                    <tr>
                      <th>绯荤粺璁㈠崟鍙?/th>
                      <th>涓婃父璁㈠崟鍙?/th>
                      <th>鐢ㄦ埛</th>
                      <th>鍟嗗搧</th>
                      <th>鐘舵€?/th>
                      <th>鐢ㄦ埛鑺辫垂</th>
                      <th>鎴愭湰</th>
                      <th>鍒╂鼎</th>
                      <th>澶囨敞</th>
                      <th>鎿嶄綔</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="row in adminState.orders" :key="row.id">
                      <td class="mono">{{ row.display_order_no || row.order_no }}</td>
                      <td class="mono wrap">{{ row.upstream_order_no || '-' }}</td>
                      <td>{{ row.nickname || row.username || ('#' + row.user_id) }}</td>
                      <td>{{ row.product_name }}</td>
                      <td><span class="badge" :class="badgeTone(row.state)">{{ row.state }}</span></td>
                      <td>{{ money(row.user_price) }}<div class="amount-yuan">{{ yuanApprox(row.user_price) }}</div></td>
                      <td>{{ money(row.cost_price) }}<div class="amount-yuan">{{ yuanApprox(row.cost_price) }}</div></td>
                      <td>{{ money(row.profit) }}<div class="amount-yuan">{{ yuanApprox(row.profit) }}</div></td>
                      <td class="wrap">{{ row.latest_message || '-' }}</td>
                      <td class="actions-cell">
                        <button class="btn sm primary" @click="showAdminOrderDetail(row)">鏌ュ崟</button>
                        <button class="btn sm warning" :disabled="!row.can_retry" @click="adminRetryOrder(row)">琛ュ崟</button>
                        <button class="btn sm danger" :disabled="!row.can_refund" @click="adminRefundOrder(row,false)">閫€鍗?/button>
                        <button class="btn sm ghost" @click="adminRefundOrder(row,true)">浠呴€€娆?/button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div v-else-if="['api-conditions','upstream-manage'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'api-conditions' ? 'API Key 鐢熸垚鏉′欢' : '涓婃父绠＄悊' }}</h2>
                <p>{{ adminTab === 'api-conditions' ? '璁剧疆鐢ㄦ埛鑷鐢熸垚 API Key 鍓嶅繀椤绘弧瓒崇殑鏉′欢锛涙槸鍚﹀厑璁稿鎺ヤ粛鐢辩敤鎴风粍鍜屽悗鍙扮瓥鐣ョ患鍚堝喅瀹氥€? : '閰嶇疆涓婃父璐﹀彿銆佹煡鐪嬪綋鍓嶄笂娓镐綑棰濆苟妫€鏌ヤ笂娓?allow 鐘舵€併€? }}</p>
              </div>
            </div>
            <div v-if="adminTab === 'api-conditions'" class="panel">
              <h3>API Key 鐢熸垚鏉′欢</h3>
              <div class="form-grid section-gap">
                <div class="field"><label>鍒ゅ畾瀛楁</label><select v-model="apiSettings.api_condition_mode"><option value="total_consume">绱娑堣垂</option><option value="total_recharge">绱鍏呭€?/option><option value="balance">浣欓</option><option value="invite_count">閭€璇风敤鎴锋暟</option></select></div>
                <div class="field"><label>杩愮畻绗?/label><select v-model="apiSettings.api_condition_operator"><option value=">=">澶т簬绛変簬</option><option value=">">澶т簬</option><option value="<=">灏忎簬绛変簬</option><option value="<">灏忎簬</option><option value="=">绛変簬</option></select></div>
                <div class="field full"><label>闃堝€?/label><input v-model.trim="apiSettings.api_condition_value" type="number" min="0"><div v-if="['total_recharge','total_consume','balance'].includes(apiSettings.api_condition_mode)" class="amount-yuan">{{ yuanApprox(apiSettings.api_condition_value) }}</div></div>
              </div>
              <div class="auth-footnote">榛樿鏉′欢涓虹疮璁″厖鍊煎ぇ浜庣瓑浜?0 棰濆害銆傛弧瓒宠繖閲屽彧浠ｈ〃鍙互鐢熸垚 API Key锛屼笉浠ｈ〃涓€瀹氬厑璁告帴鍙ｄ笅鍗曘€?/div>
              <div class="inline-actions"><button class="btn primary" @click="saveApiCondition">淇濆瓨鏉′欢璁剧疆</button></div>
            </div>
            <div v-if="adminTab === 'upstream-manage'" class="panel">
              <div class="action-row">
                <div><h3>涓婃父鐘舵€佷笌閰嶇疆</h3></div>
                <button class="btn ghost" @click="refreshUpstreamBalance(false)">鍒锋柊涓婃父浣欓</button>
              </div>
              <div class="stats-grid compact-stats section-gap">
                <div class="stat"><small>褰撳墠涓婃父浣欓</small><strong>{{ adminState.upstreamBalance === null ? '鑾峰彇澶辫触' : money(adminState.upstreamBalance) }}</strong><span v-if="adminState.upstreamBalance !== null" class="amount-yuan">{{ yuanApprox(adminState.upstreamBalance) }}</span></div>
              </div>
              <div v-if="adminState.upstreamBalanceError" class="auth-footnote danger-note section-gap">{{ adminState.upstreamBalanceError }}</div>
              <div class="divider"></div>
              <h4>{{ upstreamForm.id ? '缂栬緫涓婃父' : '鏂板涓婃父' }}</h4>
              <div class="form-grid section-gap">
                <div class="field"><label>鍚嶇О</label><input v-model.trim="upstreamForm.name" placeholder="榛樿涓婃父"></div>
                <div class="field"><label>鍩虹鍦板潃</label><input v-model.trim="upstreamForm.base_url" placeholder="https://example.com"></div>
                <div class="field"><label>涓婃父 UID</label><input v-model.number="upstreamForm.upstream_uid" type="number" min="1"></div>
                <div class="field"><label>涓婃父 API Key</label><input v-model.trim="upstreamForm.upstream_api_key" placeholder="缂栬緫宸叉湁涓婃父鏃剁暀绌哄垯涓嶄慨鏀?></div>
                <div class="field"><label>鍚敤</label><select v-model.number="upstreamForm.enabled"><option :value="1">鏄?/option><option :value="0">鍚?/option></select></div>
                <div class="field"><label>璁句负榛樿</label><select v-model.number="upstreamForm.is_default"><option :value="1">鏄?/option><option :value="0">鍚?/option></select></div>
              </div>
              <div class="inline-actions"><button class="btn primary" @click="saveUpstream">淇濆瓨涓婃父閰嶇疆</button><button class="btn ghost" @click="resetUpstreamForm">娓呯┖琛ㄥ崟</button></div>
              <div class="divider"></div>
              <div class="action-row"><h4>涓婃父鍒楄〃</h4><button class="btn ghost" @click="loadAdminUpstream(true)">鍒锋柊鍒楄〃</button></div>
              <div class="code-list section-gap">
                <div class="code-item" v-for="row in adminState.upstream" :key="row.id">
                  <div><strong>{{ row.name }}</strong><div class="tiny mono">{{ row.base_url }} 路 UID {{ row.upstream_uid }}</div></div>
                  <div class="inline-actions"><span class="badge" :class="row.enabled ? 'success' : 'danger'">{{ row.enabled ? '鍚敤' : '鍋滅敤' }}</span><span class="badge" :class="row.is_default ? 'info' : 'warning'">{{ row.is_default ? '榛樿涓婃父' : '鏅€氫笂娓? }}</span><button class="btn sm ghost" @click="editUpstream(row)">缂栬緫</button></div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['cards-generate','cards-list','payments-merchants','payments-channels','recharge-orders'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ {'cards-generate':'鍗″瘑鐢熸垚','cards-list':'鍗″瘑鍒楄〃','payments-merchants':'鏄撴敮浠樺晢鎴?,'payments-channels':'鏀粯閫氶亾','recharge-orders':'鍏呭€艰鍗?}[adminTab] }}</h2>
              </div>
            </div>
            <div v-if="adminTab === 'cards-generate'" class="panel">
              <h3>鐢熸垚鍏呭€煎崱瀵?/h3>
              <div class="form-grid section-gap">
                <div class="field"><label>鐢熸垚鏁伴噺</label><input v-model.number="cardGenForm.count" type="number" min="1" :disabled="!!cardGenForm.custom_code"></div>
                <div class="field"><label>鍏呭€奸搴?/label><input v-model.number="cardGenForm.amount" type="number" min="1"><div class="amount-yuan">{{ yuanApprox(cardGenForm.amount) }}</div></div>
                <div class="field"><label>鍙敤娆℃暟</label><input v-model.number="cardGenForm.uses" type="number" min="-1"></div>
                <div class="field"><label>闅忔満鍓嶇紑</label><input v-model.trim="cardGenForm.prefix" placeholder="鍙€? :disabled="!!cardGenForm.custom_code"></div>
                <div class="field full"><label>鑷畾涔夊崱瀵嗗唴瀹癸紙鍙€夛級</label><input v-model.trim="cardGenForm.custom_code" placeholder="濉啓鍚庡皢鎸夎鍐呭鐢熸垚鍗曞紶鍗″瘑"></div>
                <div class="field full"><label>澶囨敞</label><input v-model.trim="cardGenForm.note" placeholder="澶囨敞"></div>
              </div>
              <div class="auth-footnote">涓€娆″崱鍙缃负 1 娆★紝澶氭閫氬厬鍗″彲璁剧疆鍥哄畾娆℃暟锛?1 琛ㄧず鏃犻檺鍒讹紝0 琛ㄧず宸蹭笉鍙厬鎹€傝嚜瀹氫箟鍗″瘑鍐呭淇濇寔澶у皬鍐欐晱鎰熴€?/div>
              <div class="inline-actions"><button class="btn primary" @click="generateCards">鎵归噺鐢熸垚鍗″瘑</button></div>
            </div>
            <div v-if="adminTab === 'cards-list'" class="panel">
              <div class="action-row"><h3>鍗″瘑鍒楄〃</h3><button class="btn ghost" @click="loadAdminCards(true)">鍒锋柊鍒楄〃</button></div>
              <div class="table-wrap section-gap"><table class="table"><thead><tr><th>鍗″瘑</th><th>棰濆害</th><th>鎬绘鏁?/th><th>鍓╀綑娆℃暟</th><th>鍚敤</th><th>鎿嶄綔</th></tr></thead><tbody><tr v-for="row in adminState.cards" :key="row.id"><td class="mono wrap">{{ row.code }}</td><td>{{ money(row.amount) }}<div class="amount-yuan">{{ yuanApprox(row.amount) }}</div></td><td>{{ row.total_uses }}</td><td>{{ row.remaining_uses }}</td><td>{{ row.enabled ? '鏄? : '鍚? }}</td><td class="actions-cell"><button class="btn sm ghost" @click="editCardInline(row)">缂栬緫</button><button class="btn sm danger" @click="destroyCard(row)">閿€姣?/button></td></tr></tbody></table></div>
              <div v-if="cardEditForm.id" class="section-gap"><div class="divider"></div><h4>缂栬緫鍗″瘑</h4><div class="form-grid"><div class="field full"><label>鍗″瘑鍐呭</label><input v-model.trim="cardEditForm.code"></div><div class="field"><label>棰濆害</label><input v-model.number="cardEditForm.amount" type="number"><div class="amount-yuan">{{ yuanApprox(cardEditForm.amount) }}</div></div><div class="field"><label>鎬绘鏁?/label><input v-model.number="cardEditForm.total_uses" type="number"></div><div class="field"><label>鍓╀綑娆℃暟</label><input v-model.number="cardEditForm.remaining_uses" type="number"></div><div class="field"><label>鍚敤</label><select v-model.number="cardEditForm.enabled"><option :value="1">鏄?/option><option :value="0">鍚?/option></select></div><div class="field full"><label>澶囨敞</label><input v-model.trim="cardEditForm.note"></div></div><div class="inline-actions"><button class="btn primary" @click="saveCard">淇濆瓨鍗″瘑</button><button class="btn ghost" @click="resetCardEditForm">鍙栨秷缂栬緫</button></div></div>
            </div>
            <div v-if="adminTab === 'payments-merchants'" class="panel">
              <h3>{{ merchantForm.id ? '缂栬緫鏄撴敮浠樺晢鎴? : '鏂板鏄撴敮浠樺晢鎴? }}</h3>
              <div class="form-grid section-gap"><div class="field"><label>鍚嶇О</label><input v-model.trim="merchantForm.name"></div><div class="field"><label>鏄撴敮浠樺湴鍧€</label><input v-model.trim="merchantForm.endpoint" placeholder="https://pay.example.com"></div><div class="field"><label>鍟嗘埛ID</label><input v-model.trim="merchantForm.pid"></div><div class="field"><label>鍟嗘埛瀵嗛挜</label><input v-model.trim="merchantForm.merchant_key" placeholder="缂栬緫宸叉湁鍟嗘埛鏃跺彲鐣欑┖涓嶆敼"></div><div class="field"><label>鍚敤</label><select v-model.number="merchantForm.enabled"><option :value="1">鏄?/option><option :value="0">鍚?/option></select></div></div>
              <div class="inline-actions"><button class="btn primary" @click="saveMerchant">淇濆瓨鍟嗘埛</button><button class="btn ghost" @click="resetMerchantForm">娓呯┖琛ㄥ崟</button></div><div class="divider"></div>
              <h4>鍟嗘埛鍒楄〃</h4><div class="code-list section-gap"><div class="code-item" v-for="row in adminState.payments.merchants" :key="row.id"><div><strong>{{ row.name }}</strong><div class="tiny mono">{{ row.endpoint }} 路 PID {{ row.pid }}</div></div><div class="inline-actions"><span class="badge" :class="row.enabled ? 'success' : 'danger'">{{ row.enabled ? '鍚敤' : '鍋滅敤' }}</span><button class="btn sm ghost" @click="editMerchant(row)">缂栬緫</button></div></div></div>
            </div>
            <div v-if="adminTab === 'payments-channels'" class="panel">
              <h3>{{ channelForm.id ? '缂栬緫鏀粯閫氶亾' : '鏂板鏀粯閫氶亾' }}</h3>
              <div class="form-grid section-gap"><div class="field"><label>閫氶亾缂栫爜</label><input v-model.trim="channelForm.code" placeholder="wechat"></div><div class="field"><label>閫氶亾鍚嶇О</label><input v-model.trim="channelForm.name" placeholder="寰俊鏀粯"></div><div class="field"><label>pay_type</label><input v-model.trim="channelForm.pay_type" placeholder="wxpay / alipay"></div><div class="field"><label>鏄撴敮浠樺晢鎴?/label><select v-model.number="channelForm.merchant_id"><option v-for="m in adminState.payments.merchants" :key="m.id" :value="Number(m.id)">{{ m.name }}</option></select></div><div class="field"><label>鎺掑簭</label><input v-model.number="channelForm.sort_order" type="number"></div><div class="field"><label>鍚敤</label><select v-model.number="channelForm.enabled"><option :value="1">鏄?/option><option :value="0">鍚?/option></select></div></div>
              <div class="inline-actions"><button class="btn primary" @click="saveChannel">淇濆瓨閫氶亾</button><button class="btn ghost" @click="resetChannelForm">娓呯┖琛ㄥ崟</button></div><div class="divider"></div>
              <div class="table-wrap"><table class="table"><thead><tr><th>缂栫爜</th><th>鍚嶇О</th><th>pay_type</th><th>鍟嗘埛ID</th><th>鐘舵€?/th><th>鎿嶄綔</th></tr></thead><tbody><tr v-for="row in adminState.payments.channels" :key="row.id"><td class="mono">{{ row.code }}</td><td>{{ row.name }}</td><td>{{ row.pay_type }}</td><td>{{ row.merchant_id }}</td><td>{{ row.enabled ? '鍚敤' : '鍋滅敤' }}</td><td><button class="btn sm ghost" @click="editChannel(row)">缂栬緫</button></td></tr></tbody></table></div>
            </div>
            <div v-if="adminTab === 'recharge-orders'" class="panel">
              <div class="action-row"><h3>鍏呭€艰鍗曞垪琛?/h3><button class="btn ghost" @click="loadAdminRecharge(true)">鍒锋柊鍒楄〃</button></div>
              <div class="table-wrap section-gap"><table class="table"><thead><tr><th>璁㈠崟鍙?/th><th>鐢ㄦ埛ID</th><th>閫氶亾</th><th>鏀粯閲戦</th><th>鍒拌处棰濆害</th><th>璧犻€?/th><th>鐘舵€?/th><th>鏃堕棿</th></tr></thead><tbody><tr v-for="row in adminState.payments.recharge_orders" :key="row.id"><td class="mono">{{ row.order_no }}</td><td>{{ row.user_id }}</td><td>{{ row.channel_id }}</td><td>{{ row.money_yuan }} 鍏?/td><td>{{ money(row.credit_amount) }}<div class="amount-yuan">{{ yuanApprox(row.credit_amount) }}</div></td><td>{{ money(row.bonus_amount) }}<div class="amount-yuan">{{ yuanApprox(row.bonus_amount) }}</div></td><td><span class="badge" :class="badgeTone(row.status)">{{ row.status }}</span></td><td>{{ formatDate(row.created_at) }}</td></tr></tbody></table></div>
            </div>
          </div>

          <div v-else-if="['settings-basic','settings-theme','settings-sms','settings-security','settings-custom','settings-version','scheduled-tasks','exchange-rules'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ {'settings-basic':'鍩虹璁剧疆','settings-theme':'鐣岄潰涓婚','settings-sms':'鐭俊 / 閭欢 / 楠岃瘉','settings-security':'鐧诲綍 / 娉ㄥ唽 / 閭€璇?,'settings-custom':'鑷畾涔?CSS / JS','settings-version':'鐗堟湰涓庢洿鏂?,'scheduled-tasks':'瀹氭椂浠诲姟 API','exchange-rules':'鍟嗗搧鍏戞崲鐮佽鍒?}[adminTab] }}</h2>
                <p>褰撳墠椤甸潰浠呮樉绀烘墍閫夎缃垎绫伙紝淇濆瓨鏃朵細淇濈暀鍏朵粬鍒嗙被鐨勭幇鏈夐厤缃€?/p>
              </div>
              <div class="inline-actions">
                <button v-if="!['scheduled-tasks','settings-version'].includes(adminTab)" class="btn primary" @click="saveSettings">淇濆瓨绯荤粺璁剧疆</button>
                <button class="btn ghost" @click="reloadCurrentSettingsPage">{{ adminTab === 'settings-version' ? '閲嶆柊妫€娴? : '閲嶆柊鍔犺浇' }}</button>
              </div>
            </div>
            <div class="section-stack">
              <div v-if="adminTab === 'settings-basic'" class="panel">
                <h3>绔欑偣涓?SEO</h3>
                <div class="form-grid">
                  <div class="field"><label>绔欑偣鍚嶇О</label><input v-model.trim="settingsForm.site_name"></div>
                  <div class="field"><label>鍚庡彴璺緞</label><input v-model.trim="settingsForm.admin_path" placeholder="/admin"></div>
                  <div class="field"><label>缃戠珯鍏抽敭瀛?/label><input v-model.trim="settingsForm.site_keywords"></div>
                  <div class="field"><label>绔欑偣鎻忚堪</label><input v-model.trim="settingsForm.site_description"></div>
                  <div class="field"><label>缃戠珯鍥炬爣</label><input v-model.trim="settingsForm.site_favicon" placeholder="favicon URL"></div>
                  <div class="field"><label>绔欑偣 Logo</label><input v-model.trim="settingsForm.site_logo" placeholder="logo URL"></div>
                  <div class="field"><label>鐢ㄦ埛浜ゆ祦缇?QQ</label><input v-model.trim="settingsForm.community_group_qq" placeholder="渚嬪 1081888821"></div>
                  <div class="field"><label>鍞悗缇?QQ</label><input v-model.trim="settingsForm.support_group_qq" placeholder="渚嬪 1081888821"></div>
                  <div class="field full">
                    <label>绯荤粺绔欓暱浜ゆ祦缇?/label>
                    <div class="inline-actions">
                      <span class="code-inline">{{ ownerFeedbackGroupQq }}</span>
                      <button type="button" class="btn ghost" @click="openGroup('owner_feedback')">鍔犲叆绔欓暱浜ゆ祦缇?/button>
                    </div>
                  </div>
                  <div class="field"><label>ICP澶囨鍙?/label><input v-model.trim="settingsForm.icp_beian_no" placeholder="渚嬪 绮CP澶?2345678鍙?></div>
                  <div class="field"><label>缃戝畨澶囨鍙?/label><input v-model.trim="settingsForm.public_security_beian_no" placeholder="渚嬪 绮ゅ叕缃戝畨澶?44000000000000鍙?></div>
                  <div class="field full"><label>SEO 椤佃剼</label><textarea v-model.trim="settingsForm.seo_footer"></textarea></div>
                  <div class="field"><label>棰濆害鍚嶇О</label><input v-model.trim="settingsForm.currency_name" placeholder="濡?閫熷埛甯?></div>
<div class="field"><label>棣栭〉妯℃澘</label><select v-model="settingsForm.home_template"><option value="default">榛樿妯℃澘</option><option value="modern">鐜颁唬椋庢牸</option><option value="minimal">鏋佺畝椋庢牸</option><option value="business">鍟嗗姟椋庢牸</option></select><div class="tiny">鍒囨崲鍚庡埛鏂伴椤垫煡鐪嬫晥鏋溿€?/div></div>
                  <div class="field"><label>璇磋鍥剧墖鏉ユ簮</label><select v-model="settingsForm.feed_image_mode"><option value="self_proxy">鑷繁鏈嶅姟鍣ㄤ唬鐞?/option><option value="upstream_proxy">涓婃父浠ｇ悊閾炬帴</option></select></div>
                </div>
                <div class="auth-footnote section-gap">澶囨鍙蜂細鏄剧ず鍦ㄧ郴缁熼椤靛簳閮ㄤ腑澶€侷CP澶囨鍙蜂細閾炬帴鍒?<span class="code-inline">https://beian.miit.gov.cn</span>锛岃鍔″繀濉啓宸ヤ俊閮ㄥ疄闄呮牳鍙戠紪鍙凤紱鏈偓鎸傘€佸～鍐欓敊璇垨閾炬帴涓嶆纭彲鑳介潰涓磋矗浠ゆ暣鏀广€佺綒娆炬垨澶囨娉ㄩ攢椋庨櫓銆傜綉瀹夊妗堝彿璇峰～鍐欏疄闄呮牳鍙戝唴瀹广€?/div>
              </div>

              <div v-if="adminTab === 'settings-sms'" class="panel">
                <h3>閭璁剧疆锛圫MTP锛?/h3>
                <div class="form-grid">
                  <div class="field"><label>SMTP Host</label><input v-model.trim="settingsForm.smtp_config.host"></div>
                  <div class="field"><label>绔彛</label><input v-model.number="settingsForm.smtp_config.port" type="number"></div>
                  <div class="field"><label>鐢ㄦ埛鍚?/label><input v-model.trim="settingsForm.smtp_config.username"></div>
                  <div class="field"><label>瀵嗙爜</label><input v-model.trim="settingsForm.smtp_config.password"></div>
                  <div class="field"><label>鍔犲瘑鏂瑰紡</label><select v-model="settingsForm.smtp_config.encryption"><option value="ssl">SSL</option><option value="tls">TLS</option><option value="">鏃?/option></select></div>
                  <div class="field"><label>鍙戜欢閭</label><input v-model.trim="settingsForm.smtp_config.from"></div>
                  <div class="field full"><label>鍙戜欢浜哄悕绉?/label><input v-model.trim="settingsForm.smtp_config.from_name"></div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-sms'" class="panel">
                <h3>鐭俊璁剧疆</h3>
                <div class="form-grid">
                  <div class="field full"><label>鐭俊鏈嶅姟鍟?/label><select v-model="settingsForm.sms_provider"><option value="tencent">鑵捐浜?/option><option value="aliyun">闃块噷浜?/option><option value="custom_http">鑷畾涔?HTTP</option></select></div>
                </div>
                <div v-if="settingsForm.sms_provider === 'tencent'" class="form-grid section-gap">
                  <div class="field"><label>SecretId</label><input v-model.trim="settingsForm.sms_config.secret_id"></div>
                  <div class="field"><label>SecretKey</label><input v-model.trim="settingsForm.sms_config.secret_key"></div>
                  <div class="field"><label>SDK App ID</label><input v-model.trim="settingsForm.sms_config.sdk_app_id"></div>
                  <div class="field"><label>妯℃澘 ID</label><input v-model.trim="settingsForm.sms_config.template_id"></div>
                  <div class="field"><label>鍦板煙</label><input v-model.trim="settingsForm.sms_config.region"></div>
                  <div class="field full"><label>璇存槑</label><div class="tiny">鑵捐浜戠煭淇＄鍚嶇敱绋嬪簭鎸夋帴鍏ユ柟寮忚嚜鍔ㄥ鐞嗭紝杩欓噷鍙渶瑕佸～鍐欏瘑閽ャ€佹ā鏉垮拰鍦板煙鍗冲彲銆?/div></div>
                </div>
                <div v-else-if="settingsForm.sms_provider === 'aliyun'" class="form-grid section-gap">
                  <div class="field"><label>AccessKeyId</label><input v-model.trim="settingsForm.sms_config.access_key_id"></div>
                  <div class="field"><label>AccessKeySecret</label><input v-model.trim="settingsForm.sms_config.access_key_secret"></div>
                  <div class="field"><label>妯℃澘 Code</label><input v-model.trim="settingsForm.sms_config.template_code"></div>
                  <div class="field"><label>鍦板煙</label><input v-model.trim="settingsForm.sms_config.region"></div>
                  <div class="field"><label>Endpoint</label><input v-model.trim="settingsForm.sms_config.endpoint"></div>
                  <div class="field full"><label>璇存槑</label><div class="tiny">闃块噷浜戠煭淇＄鍚嶇敱绋嬪簭鎸夋帴鍏ユ柟寮忚嚜鍔ㄥ鐞嗭紝杩欓噷鍙渶瑕佸～鍐欏瘑閽ャ€佹ā鏉夸笌鍦板煙閰嶇疆鍗冲彲銆?/div></div>
                </div>
                <div v-else class="section-gap section-stack">
                  <div class="form-grid">
                    <div class="field"><label>璇锋眰鍦板潃</label><input v-model.trim="settingsForm.sms_config.url"></div>
                    <div class="field"><label>璇锋眰鏂规硶</label><select v-model="settingsForm.sms_config.method"><option value="POST">POST</option><option value="GET">GET</option></select></div>
                    <div class="field"><label>鎴愬姛瀛楁</label><input v-model.trim="settingsForm.sms_config.success_field"></div>
                    <div class="field"><label>鎴愬姛鍊?/label><input v-model.trim="settingsForm.sms_config.success_value"></div>
                  </div>
                  <div class="grid-3">
                    <div class="panel" style="margin:0;padding:18px">
                      <div class="card-title"><h4>Headers</h4><button class="btn sm ghost" @click="addPair(settingsForm.sms_headers_rows)">鏂板</button></div>
                      <div class="editor-list" v-if="settingsForm.sms_headers_rows.length">
                        <div class="editor-row" v-for="(row,index) in settingsForm.sms_headers_rows" :key="index"><div class="field"><label>Key</label><input v-model.trim="row.key"></div><div class="field"><label>Value</label><input v-model.trim="row.value"></div><button class="btn sm danger" @click="removePair(settingsForm.sms_headers_rows,index)">鍒?/button></div>
                      </div>
                      <div v-else class="placeholder-card" style="padding:12px">鏆傛棤 Header銆?/div>
                    </div>
                    <div class="panel" style="margin:0;padding:18px">
                      <div class="card-title"><h4>Query</h4><button class="btn sm ghost" @click="addPair(settingsForm.sms_query_rows)">鏂板</button></div>
                      <div class="editor-list" v-if="settingsForm.sms_query_rows.length">
                        <div class="editor-row" v-for="(row,index) in settingsForm.sms_query_rows" :key="index"><div class="field"><label>Key</label><input v-model.trim="row.key"></div><div class="field"><label>Value</label><input v-model.trim="row.value"></div><button class="btn sm danger" @click="removePair(settingsForm.sms_query_rows,index)">鍒?/button></div>
                      </div>
                      <div v-else class="placeholder-card" style="padding:12px">鏆傛棤 Query 鍙傛暟銆?/div>
                    </div>
                    <div class="panel" style="margin:0;padding:18px">
                      <div class="card-title"><h4>Body</h4><button class="btn sm ghost" @click="addPair(settingsForm.sms_body_rows)">鏂板</button></div>
                      <div class="editor-list" v-if="settingsForm.sms_body_rows.length">
                        <div class="editor-row" v-for="(row,index) in settingsForm.sms_body_rows" :key="index"><div class="field"><label>Key</label><input v-model.trim="row.key"></div><div class="field"><label>Value</label><input v-model.trim="row.value"></div><button class="btn sm danger" @click="removePair(settingsForm.sms_body_rows,index)">鍒?/button></div>
                      </div>
                      <div v-else class="placeholder-card" style="padding:12px">鏆傛棤 Body 鍙傛暟銆?/div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-sms'" class="panel">
                <h3>鏋侀獙璁剧疆</h3>
                <div class="form-grid">
                  <div class="field"><label>Captcha ID</label><input v-model.trim="settingsForm.geetest_config.captcha_id"></div>
                  <div class="field"><label>Captcha Key</label><input v-model.trim="settingsForm.geetest_config.captcha_key"></div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-security'" class="panel">
                <h3>鐧诲綍 / 娉ㄥ唽璁剧疆</h3>
                <div class="form-grid">
                  <div class="field"><label>鍓嶅彴涓嬪崟</label><select v-model.number="settingsForm.frontend_order_enabled"><option :value="1">寮€鍚?/option><option :value="0">鍏抽棴</option></select></div>
                  <div class="field"><label>鎺ュ彛涓嬪崟</label><select v-model.number="settingsForm.api_order_enabled"><option :value="1">寮€鍚?/option><option :value="0">鍏抽棴</option></select></div>
                  <div class="field"><label>娉ㄥ唽闇€閭</label><select v-model.number="settingsForm.register_need_email"><option :value="0">鍚?/option><option :value="1">鏄?/option></select></div>
                  <div class="field"><label>娉ㄥ唽闇€鎵嬫満鍙?/label><select v-model.number="settingsForm.register_need_mobile"><option :value="0">鍚?/option><option :value="1">鏄?/option></select></div>
                  <div class="field"><label>娉ㄥ唽鍥剧墖楠岃瘉鐮?/label><select v-model.number="settingsForm.register_need_image_captcha"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>娉ㄥ唽鏋侀獙</label><select v-model.number="settingsForm.register_need_geetest"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>娉ㄥ唽鐭俊楠岃瘉</label><select v-model.number="settingsForm.register_need_sms_code"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>娉ㄥ唽閭欢楠岃瘉</label><select v-model.number="settingsForm.register_need_email_code"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>鐧诲綍鐭俊楠岃瘉</label><select v-model.number="settingsForm.login_need_sms"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>鐧诲綍閭欢楠岃瘉</label><select v-model.number="settingsForm.login_need_email"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>鐧诲綍鏋侀獙</label><select v-model.number="settingsForm.login_need_geetest"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>鐧诲綍鍥剧墖楠岃瘉鐮?/label><input value="缁熶竴鐧诲綍寮哄埗寮€鍚浘鐗囬獙璇佺爜" readonly></div>
                  <div class="field"><label>榛樿娉ㄥ唽绛栫暐锛歎ser</label><select v-model.number="settingsForm.default_register_strategy_user"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                  <div class="field"><label>榛樿娉ㄥ唽绛栫暐锛欰gent</label><select v-model.number="settingsForm.default_register_strategy_agent"><option :value="0">鍏抽棴</option><option :value="1">寮€鍚?/option></select></div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-security'" class="panel">
                <h3>閭€璇疯缃笌閭€璇风爜浠锋牸</h3>
                <div class="form-grid">
                  <div class="field"><label>鏈夋晥閭€璇锋潯浠?/label><select v-model="settingsForm.invite_valid_mode"><option value="total_consume">琚個璇风敤鎴风疮璁℃秷璐?/option><option value="total_recharge">琚個璇风敤鎴风疮璁″厖鍊?/option><option value="invite_count">閭€璇风敤鎴锋暟</option><option value="balance">浣欓澶т簬绛変簬</option></select></div>
                  <div class="field"><label>鏉′欢鏁板€?/label><input v-model.trim="settingsForm.invite_valid_value" type="number" min="0"></div>
                  <div class="field"><label>浣欓杈炬爣涓嶈冻鏃跺彲闄嶇骇</label><select v-model.number="settingsForm.balance_downgrade_enabled"><option :value="0">鍚?/option><option :value="1">鏄?/option></select></div>
                </div>
                <div class="section-gap">
                  <div class="switch-inline"><label><input type="radio" value="fixed" v-model="settingsForm.invite_code_price_rules.mode"> 鍥哄畾浠锋牸</label><label><input type="radio" value="length" v-model="settingsForm.invite_code_price_rules.mode"> 鎸夐暱搴﹀畾浠?/label></div>
                </div>
                <div v-if="settingsForm.invite_code_price_rules.mode === 'fixed'" class="form-grid section-gap">
                  <div class="field full"><label>鍥哄畾浠锋牸</label><input v-model.number="settingsForm.invite_code_price_rules.fixed" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(settingsForm.invite_code_price_rules.fixed) }}</div></div>
                </div>
                <div v-else class="section-gap section-stack">
                  <div class="inline-actions"><button class="btn sm ghost" @click="addInviteRule">鏂板闀垮害瑙勫垯</button></div>
                  <div class="editor-list" v-if="settingsForm.invite_code_price_rules.length_rules.length">
                    <div class="editor-row" v-for="(rule,index) in settingsForm.invite_code_price_rules.length_rules" :key="index"><div class="field"><label>闀垮害 / 鍖洪棿</label><input v-model.trim="rule.length" placeholder="渚嬪 6銆?-12銆?~12"></div><div class="field"><label>浠锋牸</label><input v-model.number="rule.price" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(rule.price) }}</div></div><button class="btn sm danger" @click="removeInviteRule(index)">鍒犻櫎</button></div>
                  </div>
                  <div v-else class="placeholder-card">鏆傛棤闀垮害浠锋牸瑙勫垯銆?/div>
                  <div class="tiny">鏀寔杈撳叆 6銆?-12 鎴?6~12锛岄粯璁や负闂尯闂达紱鏈崟鐙厤缃殑闀垮害灏嗗洖閫€鍒板浐瀹氫环鏍煎瓧娈点€?/div>
                </div>
              </div>

              <div v-if="adminTab === 'exchange-rules'" class="panel">
                <h3>鍟嗗搧鍏戞崲鐮佽鍒?/h3>
                <div class="form-grid section-gap">
                  <div class="field"><label>鍚敤鍟嗗搧鍏戞崲鐮?/label><select v-model.number="settingsForm.exchange_code_enabled"><option :value="1">鍚敤</option><option :value="0">鍋滅敤</option></select></div>
                  <div class="field"><label>姣忓紶鐢熸垚鏈嶅姟璐?/label><input v-model.number="settingsForm.exchange_code_generation_fee" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(settingsForm.exchange_code_generation_fee) }}</div></div>
                  <div class="field"><label>绯荤粺鑷畾涔夊墠缂€</label><input v-model.trim="settingsForm.exchange_code_prefix" maxlength="64" placeholder="渚嬪 XM"></div>
                  <div class="field"><label>闅忔満瀛楃涓查暱搴?/label><input v-model.number="settingsForm.exchange_code_random_length" type="number" min="8" max="256"><div class="tiny">鍏佽 8锝?56 浣嶏紱鏈€缁堝厬鎹㈢爜涓嶈冻 48 浣嶆椂绯荤粺浼氳嚜鍔ㄨˉ榻愩€?/div></div>
                  <div class="field full"><label>鍏戞崲鐮佹牸寮?/label><input v-model.trim="settingsForm.exchange_code_format" placeholder="{prefix}{random}{uid}"><div class="tiny">鏀寔缁勪欢锛?span class="code-inline">{prefix}</span> 绯荤粺鍓嶇紑銆?span class="code-inline">{random}</span> 闅忔満瀛楃涓层€?span class="code-inline">{uid}</span> 鐢ㄦ埛 UID銆傚彲鑷敱璋冩暣椤哄簭鍜岀粍鍚堛€?/div></div>
                  <div class="field"><label>鍏戞崲璁㈠崟 Cookie 鏈夋晥鏈燂紙澶╋級</label><input v-model.number="settingsForm.exchange_code_cookie_days" type="number" min="7" max="3650"><div class="tiny">鍏佽 7锝?650 澶╋紝榛樿 60 澶┿€?/div></div>
                </div>
                <div class="auth-footnote">鐢熸垚鍏戞崲鐮佹椂鎸夆€滄瘡寮犵敓鎴愭湇鍔¤垂 脳 鏁伴噺鈥濇敹鍙栨湇鍔¤垂锛岄粯璁?0 棰濆害銆傚厬鎹㈡垚鍔熷悗锛屽晢鍝佽垂鐢ㄤ粠鍏戞崲鐮佸垱寤鸿€呰处鎴锋墸闄わ紝鍏紡涓猴細鏁伴噺 梅 璁′环鍗曚綅锛堟渶浣庢闀匡級 脳 鐢ㄦ埛浠锋牸銆傜郴缁熷唴閮ㄦ寜鏁版嵁搴撳敮涓€鐢ㄦ埛 ID 璁拌处锛屼笉渚濊禆鍙兘閲嶅鐨勫叕寮€ UID銆?/div>
              </div>

              <div v-if="adminTab === 'settings-custom'" class="panel">
                <h3>鑷畾涔夐〉闈㈡牱寮忎笌鑴氭湰</h3>
                <div class="placeholder-card section-gap"><strong>瀹夊叏鎻愮ず</strong><div class="tiny">鑷畾涔?JavaScript 鍜岀涓夋柟璧勬簮鍙互璁块棶褰撳墠椤甸潰鏁版嵁銆傝浠呬娇鐢ㄨ嚜宸辩紪鍐欐垨纭鍙俊鐨勪唬鐮佷笌璧勬簮閾炬帴锛涢敊璇唬鐮佸彲鑳藉鑷撮〉闈㈡棤娉曟甯镐娇鐢ㄣ€?/div></div>
                <div class="form-grid section-gap">
                  <div class="field full"><label>鑷畾涔?CSS</label><textarea v-model="settingsForm.custom_css" class="custom-code-editor" spellcheck="false" placeholder=".panel { border-radius: 20px; }"></textarea><div class="tiny">淇濆瓨鍚庡簲鐢ㄥ埌绯荤粺椤甸潰銆傝浼樺厛浣跨敤鐜版湁涓婚鍙橀噺锛岄伩鍏嶇‖缂栫爜棰滆壊銆?/div></div>
                  <div class="field full"><label>鑷畾涔?JavaScript</label><textarea v-model="settingsForm.custom_js" class="custom-code-editor" spellcheck="false" placeholder="document.documentElement.classList.add('my-effect');"></textarea><div class="tiny">鑴氭湰浼氬湪椤甸潰涓讳綋鍔犺浇瀹屾垚鍚庢墽琛岋紝鏈€澶?200000 瀛楄妭銆?/div></div>
                </div>
                <div class="section-gap"><div class="card-title"><div><h3>绗笁鏂硅祫婧愰摼鎺?/h3><div class="tiny">浠呭厑璁?HTTP/HTTPS 閾炬帴锛屾渶澶?20 鏉★紱鎸夊垪琛ㄩ『搴忓姞杞姐€?/div></div><button type="button" class="btn sm ghost" @click="addCustomResource">鏂板璧勬簮</button></div>
                  <div v-if="settingsForm.custom_resource_urls.length" class="editor-list section-gap"><div class="editor-row custom-resource-row" v-for="(resource,index) in settingsForm.custom_resource_urls" :key="index"><div class="field"><label>璧勬簮绫诲瀷</label><select v-model="resource.type"><option value="css">CSS</option><option value="js">JavaScript</option></select></div><div class="field custom-resource-url"><label>璧勬簮閾炬帴</label><input v-model.trim="resource.url" type="url" placeholder="https://cdn.example.com/library.min.css"></div><button type="button" class="btn sm danger" @click="removeCustomResource(index)">鍒犻櫎</button></div></div>
                  <div v-else class="placeholder-card section-gap">灏氭湭娣诲姞绗笁鏂硅祫婧愩€?/div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-version'" class="panel">
                <div class="card-title"><div><h3>鐗堟湰涓庡湪绾挎洿鏂?/h3><div class="tiny">绠＄悊鍛樻瘡娆¤繘鍏ュ悗鍙版椂鑷姩妫€娴嬩竴娆¤繙绋嬬増鏈€?/div></div><span class="badge" :class="adminState.version.has_update ? 'warning' : 'success'">{{ adminState.version.has_update ? '鍙戠幇鏂扮増鏈? : '褰撳墠鐗堟湰' }}</span></div>
                <div class="info-grid section-gap"><div class="kv-box"><span class="tiny">褰撳墠鐗堟湰</span><strong>{{ (adminState.version.current && adminState.version.current.version) || currentVersion.version || 'v1.0.0' }}</strong></div><div class="kv-box"><span class="tiny">杩滅▼鐗堟湰</span><strong>{{ (adminState.version.remote && adminState.version.remote.version) || '鏈幏鍙? }}</strong></div><div class="kv-box"><span class="tiny">Git 瀹夎鐘舵€?/span><strong>{{ adminState.version.git_available ? '鍙敤' : '涓嶅彲鐢? }}</strong></div><div class="kv-box"><span class="tiny">鏈€杩戞娴?/span><strong>{{ formatDate(adminState.version.checked_at) }}</strong></div></div>
                <div class="placeholder-card section-gap">{{ adminState.version.message || '灏氭湭鎵ц鐗堟湰妫€娴嬨€? }}</div>
                <div v-if="adminState.version.has_update && adminState.version.can_update" class="section-gap">
                  <button class="btn primary" @click="updateVersion" :disabled="adminState.version.updating">{{ adminState.version.updating ? '姝ｅ湪鏇存柊...' : '涓€閿洿鏂? }}</button>
                </div>
                <div class="section-gap"><h3>{{ adminState.version.has_update ? '鏂扮増鏈壒鎬? : '褰撳墠鐗堟湰鐗规€? }}</h3><ul class="feature-list"><li v-for="(feature,index) in versionFeatures" :key="index">{{ feature }}</li></ul></div>
                <div class="auth-footnote section-gap">鍦ㄧ嚎鐗堟湰鏇存柊浠呭湪椤圭洰鏍圭洰褰曞瓨鍦?<span class="code-inline">.git</span> 鏃跺彲鐢ㄣ€傛洿鏂板墠璇峰厛澶囦唤鏁版嵁搴撲笌閰嶇疆銆傜郴缁熷皢鑷姩鎵ц <span class="code-inline">git pull origin main</span> 鎷夊彇鏈€鏂颁唬鐮併€?/div>
              </div>

              <div v-if="adminTab === 'scheduled-tasks'" class="panel">
                <div class="action-row">
                  <div>
                    <h3>瀹氭椂浠诲姟 HTTP API</h3>
                    <p class="panel-sub">鍙敱瀹濆璁″垝浠诲姟銆佺洃鎺у钩鍙版垨鍏朵粬瀹氭椂鏈嶅姟璋冪敤銆?/p>
                  </div>
                  <button class="btn danger" @click="resetScheduledTaskKey">閲嶇疆绯荤粺瀵嗛挜</button>
                </div>
                <div class="auth-footnote danger-note section-gap">閲嶇疆鍚庢棫瀵嗛挜绔嬪嵆澶辨晥锛屾墍鏈夊凡閰嶇疆鐨勫畾鏃朵换鍔￠兘蹇呴』鍚屾鏇存柊銆?/div>
                <div class="form-grid section-gap">
                  <div class="field full">
                    <label>绯荤粺瀵嗛挜锛堜粎绠＄悊鍛樺彲鏌ョ湅鍜岄噸缃級</label>
                    <div class="search-row">
                      <input :value="adminState.scheduledTasks.system_key" readonly autocomplete="off" spellcheck="false" class="mono">
                      <button class="btn ghost" @click="copyScheduledTaskValue(adminState.scheduledTasks.system_key, '绯荤粺瀵嗛挜')">澶嶅埗</button>
                    </div>
                  </div>
                  <div class="field full">
                    <label>鏇存柊鍟嗗搧鏁版嵁 API锛圙ET / POST锛?/label>
                    <div class="search-row">
                      <input :value="scheduledTaskUrl(adminState.scheduledTasks.products_endpoint)" readonly class="mono">
                      <button class="btn ghost" @click="copyScheduledTaskValue(scheduledTaskUrl(adminState.scheduledTasks.products_endpoint), '鍟嗗搧鏇存柊 API')">澶嶅埗</button>
                    </div>
                  </div>
                  <div class="field full">
                    <label>鏇存柊璁㈠崟 API锛圙ET / POST锛?/label>
                    <div class="search-row">
                      <input :value="scheduledTaskUrl(adminState.scheduledTasks.orders_endpoint)" readonly class="mono">
                      <button class="btn ghost" @click="copyScheduledTaskValue(scheduledTaskUrl(adminState.scheduledTasks.orders_endpoint), '璁㈠崟鏇存柊 API')">澶嶅埗</button>
                    </div>
                  </div>
                </div>
                <div class="auth-footnote section-gap">椤甸潰涓殑涓€閿鍒跺湴鍧€浣跨敤 system_key 鏌ヨ鍙傛暟锛岄€傚悎鍙敮鎸?URL 鐨勫畾鏃跺钩鍙般€傛洿鎺ㄨ崘浣跨敤 Authorization: Bearer 鎴?X-System-Key 璇锋眰澶达紝閬垮厤瀵嗛挜鍑虹幇鍦?URL 璁块棶鏃ュ織涓€?/div>
              </div>

              <div v-if="adminTab === 'settings-theme'" class="panel">
                <div class="card-title">
                  <div>
                    <h3>鐣岄潰涓婚</h3>
                    <p class="panel-sub">鏀寔鍦ㄧ嚎璋冩暣骞跺鍏?/ 瀵煎嚭 JSON 涓婚鏂囦欢銆?/p>
                  </div>
                  <div class="theme-actions">
                    <button class="btn ghost" @click="exportTheme">瀵煎嚭涓婚</button>
                    <button class="btn primary" @click="triggerThemeImport">瀵煎叆涓婚</button><button class="btn ghost" @click="restoreDefaultTheme">鎭㈠榛樿</button>
                    <input ref="themeFileInput" type="file" accept="application/json,.json" @change="importThemeFile" style="display:none">
                  </div>
                </div>
                <div class="theme-groups section-gap">
                  <section class="theme-group" v-for="group in themeGroups" :key="group.title">
                    <h4>{{ group.title }}</h4>
                    <div class="theme-input-grid">
                      <label class="theme-input-row" v-for="item in group.items" :key="item.key">
                        <span>{{ item.label }}</span>
                        <div class="theme-input-control">
                          <input v-if="item.type === 'color'" class="theme-color-input" type="color" v-model="settingsForm.theme_config[item.key]" @input="applyTheme(settingsForm.theme_config)">
                          <input class="theme-value-input mono" v-model.trim="settingsForm.theme_config[item.key]" @input="applyTheme(settingsForm.theme_config)" :placeholder="item.type === 'color' ? '#rrggbb' : '鏀寔 rgba(...) 绛?CSS 棰滆壊鍊?">
                        </div>
                      </label>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="adminTab === 'exchange-list'">
            <div class="page-head"><div><h2>鍟嗗搧鍏戞崲鐮佸垪琛?/h2><p>鏀寔鎸夊晢鍝併€佺姸鎬併€佸厬鎹㈣€?QQ 鍜屾椂闂存帓搴忥紝鍏戞崲鐮佷笉鑴辨晱銆?/p></div><div class="inline-actions"><button class="btn ghost" @click="loadAdminExchangeCodes(true)">鍒锋柊鍒楄〃</button></div></div>
            <div class="filter-grid exchange-filters"><select v-model="adminState.exchange.filters.product_id"><option value="">鍏ㄩ儴鍟嗗搧</option><option v-for="product in adminState.products" :key="product.id" :value="product.id">{{ product.name }}</option></select><select v-model="adminState.exchange.filters.status"><option value="">鍏ㄩ儴鐘舵€?/option><option value="unused">鏈娇鐢?/option><option value="used">宸插厬鎹?/option><option value="destroyed">宸查攢姣?/option></select><input v-model.trim="adminState.exchange.filters.redeemer_qq" placeholder="鍏戞崲鑰匭Q"><select v-model="adminState.exchange.filters.sort"><option value="created_desc">鐢熸垚鏃堕棿鍊掑簭</option><option value="created_asc">鐢熸垚鏃堕棿姝ｅ簭</option><option value="used_desc">浣跨敤鏃堕棿鍊掑簭</option><option value="used_asc">浣跨敤鏃堕棿姝ｅ簭</option></select><button class="btn ghost" @click="loadAdminExchangeCodes(true)">绛涢€?/button></div>
            <div class="table-wrap" v-if="adminState.exchange.codes.length"><table class="table"><thead><tr><th>鍏戞崲鐮?/th><th>鍒涘缓鐢ㄦ埛</th><th>鍟嗗搧 / 鏁伴噺</th><th>浠锋牸 / 鏈嶅姟璐?/th><th>鐘舵€?/th><th>鍏戞崲鑰匭Q / 璁㈠崟</th><th>鐢熸垚/浣跨敤鏃堕棿</th><th>鎿嶄綔</th></tr></thead><tbody><tr v-for="row in adminState.exchange.codes" :key="row.id"><td><div class="mono text-break exchange-code-cell">{{ row.code || row.display_code }}</div><div class="tiny">鍐呴儴ID #{{ row.id }}</div></td><td>{{ row.creator_nickname || row.creator_username || row.creator_name_snapshot || '-' }}<div class="tiny">鐢ㄦ埛ID {{ row.creator_user_id }} 路 UID {{ row.creator_uid_snapshot }}</div></td><td>{{ row.product_name_snapshot }}<div class="tiny">{{ row.quantity }} 涓?/ 姣?{{ row.step_num_snapshot }} 涓浠?/div></td><td>{{ money(row.price_snapshot) }}<div class="amount-yuan">{{ yuanApprox(row.price_snapshot) }}</div><div class="tiny">鐢熸垚璐?{{ money(row.generation_fee) }} 路 {{ yuanApprox(row.generation_fee) }}</div></td><td><span class="badge" :class="row.status === 'used' ? 'success' : (row.status === 'destroyed' ? 'danger' : 'info')">{{ row.status === 'used' ? '宸插厬鎹? : (row.status === 'destroyed' ? '宸查攢姣? : '鏈娇鐢?) }}</span></td><td>{{ row.redeemer_qq || '-' }}<div class="tiny mono">{{ row.redeemer_order_no || '-' }}</div></td><td>鐢熸垚锛歿{ formatDate(row.created_at) }}<div class="tiny" v-if="row.used_at">浣跨敤锛歿{ formatDate(row.used_at) }}</div></td><td><div class="inline-actions"><button v-if="row.status === 'unused'" class="btn sm ghost" @click="editExchangeCode(row, true)">缂栬緫</button><button v-if="row.status === 'unused'" class="btn sm danger" @click="destroyExchangeCode(row, true)">閿€姣?/button></div></td></tr></tbody></table></div><div v-else class="placeholder-card">鏆傛棤鍟嗗搧鍏戞崲鐮併€?/div>
          </div>
          <div v-else-if="adminTab === 'exchange-logs'">
            <div class="page-head">
              <div><h2>鍟嗗搧鍏戞崲鐮佹棩蹇?/h2><p>鐢ㄤ簬瀹氫綅鐢熸垚銆佸厬鎹㈠拰寮傚父鎿嶄綔锛屽弽棣堥棶棰樻椂鍙彁渚涙棩蹇楃紪鍙枫€?/p></div>
              <div class="inline-actions"><button class="btn ghost" @click="loadAdminExchangeLogs(true)">鍒锋柊鏃ュ織</button></div>
            </div>
            <div class="log-list" v-if="adminState.exchange.logs.length">
              <div class="log-item" v-for="row in adminState.exchange.logs" :key="row.id">
                <div class="card-title">
                  <div><h3 style="margin:0">{{ exchangeActionText(row.action) }} 路 {{ row.product_name_snapshot || '鏈煡鍟嗗搧' }}</h3><div class="tiny">鏃ュ織 #{{ row.id }} 路 鍏戞崲鐮?#{{ row.exchange_code_id }} 路 {{ formatDate(row.created_at) }} 路 IP {{ row.ip || '-' }}</div></div>
                  <span class="badge info">鎿嶄綔鐢ㄦ埛 {{ row.operator_user_id || '-' }}</span>
                </div>
                <div class="mono text-break">{{ maskExchangeCode(row.code) }}</div>
                <pre>{{ prettyJson(row.context_json ? parseJson(row.context_json, {}) : {}) }}</pre>
              </div>
            </div>
            <div v-else class="placeholder-card">鏆傛棤鍏戞崲鐮佹棩蹇椼€?/div>
          </div>

          <div v-else-if="adminTab === 'logs-list'">
            <div class="page-head">
              <div>
                <h2>绯荤粺鏃ュ織</h2>
                <p>鍙寜绛夌骇鍜岄閬撶瓫閫夛紱鍙嶉闂鏃惰鍚屾椂鎻愪緵鏃堕棿銆侀閬撳拰鏃ュ織鍐呭銆?/p>
              </div>
              <div class="inline-actions"><button class="btn ghost" @click="loadAdminLogs(true)">鍒锋柊鏃ュ織</button></div>
            </div>
            <div class="form-grid section-gap">
              <div class="field"><label>鏃ュ織绛夌骇</label><select v-model="adminState.logLevel" @change="loadAdminLogs(true)"><option value="">鍏ㄩ儴</option><option value="debug">debug</option><option value="info">info</option><option value="warning">warning</option><option value="error">error</option><option value="critical">critical</option></select></div>
              <div class="field"><label>鏃ュ織棰戦亾</label><input v-model.trim="adminState.logChannel" placeholder="濡?admin / payment / order" @change="loadAdminLogs(true)"></div>
            </div>
            <div class="log-list" v-if="adminState.logs.length">
              <div class="log-item" v-for="row in adminState.logs" :key="row.id">
                <div class="card-title">
                  <div>
                    <h3 style="margin:0">{{ row.message }}</h3>
                    <div class="tiny">{{ formatDate(row.created_at) }} 路 {{ row.channel }} 路 {{ row.level }} 路 鐢ㄦ埛 {{ row.user_id || '-' }}</div>
                  </div>
                  <span class="badge" :class="badgeTone(row.level)">{{ row.level }}</span>
                </div>
                <pre>{{ prettyJson(row.context_json ? parseJson(row.context_json, {}) : {}) }}</pre>
              </div>
            </div>
            <div v-else class="placeholder-card">褰撳墠绛涢€夋潯浠朵笅鏆傛棤鏃ュ織銆?/div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer-block">
    <div v-if="routeMode === 'home' && (settings.icp_beian_no || settings.public_security_beian_no)" class="record-links" style="margin-bottom:12px">
      <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
      <div v-if="settings.public_security_beian_no" class="muted">缃戝畨澶囨锛歿{ settings.public_security_beian_no }}</div>
    </div>
    __SEO_FOOTER_BLOCK__
  </footer>

  <div v-if="exchangeEditState.visible" class="modal-mask" @click.self="closeExchangeEdit">
    <div class="modal" style="max-width:680px"><div class="modal-head"><div><h3>缂栬緫鍟嗗搧鍏戞崲鐮?/h3><div class="tiny">浠呮湭浣跨敤鍏戞崲鐮佸彲缂栬緫銆?/div></div><button class="btn ghost" @click="closeExchangeEdit">鍏抽棴</button></div><div class="modal-body-scroll"><div class="form-grid"><div class="field full"><label>鍏戞崲鐮侊紙鑷冲皯48浣嶏級</label><input v-model.trim="exchangeEditState.form.code"></div><div class="field full"><label>鍟嗗搧</label><select v-model="exchangeEditState.form.sign"><option v-for="product in (exchangeEditState.admin ? adminState.products : userState.products)" :key="product.id || product.upstream_sign" :value="product.upstream_sign">{{ product.name }}</option></select></div><div class="field"><label>涓嬪崟鏁伴噺</label><input v-model.number="exchangeEditState.form.quantity" type="number" min="1"></div></div></div><div class="inline-actions section-gap" style="justify-content:flex-end"><button class="btn ghost" @click="closeExchangeEdit">鍙栨秷</button><button class="btn primary" @click="saveExchangeCode">淇濆瓨淇敼</button></div></div>
  </div>

  <div v-if="confirmState.visible" class="modal-mask" @click.self="cancelConfirm">
    <div class="modal" style="max-width:560px">
      <div class="modal-head">
        <div>
          <h3>{{ confirmState.title || '鎿嶄綔纭' }}</h3>
          <div class="tiny">璇风‘璁ゅ悗缁х画鎵ц銆?/div>
        </div>
        <button class="btn ghost" @click="cancelConfirm">鍏抽棴</button>
      </div>
      <div class="modal-body-scroll">
        <p class="pre-wrap" style="margin:0">{{ confirmState.message }}</p>
      </div>
      <div class="inline-actions section-gap" style="justify-content:flex-end">
        <button class="btn ghost" @click="cancelConfirm">鍙栨秷</button>
        <button class="btn danger" @click="resolveConfirm(true)">{{ confirmState.confirmText || '纭' }}</button>
      </div>
    </div>
  </div>

  <div class="toast-stack">
    <div v-for="toast in toasts" :key="toast.id" class="toast" :class="toast.type">{{ toast.text }}</div>
  </div>

  <div v-if="loading" class="loading-mask">
    <div class="loading-card"><div class="spinner"></div><div>{{ loadingText }}</div></div>
  </div>
</div>

<script>
const BOOT = __BOOT__;

function boolish(value) {
  return value === 1 || value === '1' || value === true || value === 'true' || value === 'on';
}
function clone(value) {
  return JSON.parse(JSON.stringify(value));
}
function parseJson(text, fallback) {
  try {
    if (text === null || text === undefined || text === '') return clone(fallback);
    if (typeof text === 'object') return clone(text);
    return Object.assign(Array.isArray(fallback) ? [] : {}, fallback, JSON.parse(text));
  } catch (e) {
    return clone(fallback);
  }
}
function toRows(obj) {
  const rows = [];
  const source = obj && typeof obj === 'object' ? obj : {};
  Object.keys(source).forEach(function (key) {
    rows.push({ key: key, value: String(source[key] ?? '') });
  });
  return rows;
}
function rowsToObject(rows) {
  const result = {};
  (rows || []).forEach(function (row) {
    const key = String(row.key || '').trim();
    if (!key) return;
    result[key] = String(row.value ?? '');
  });
  return result;
}
function emptyProfileForm() {
  return { nickname: '', qq: '', email: '', mobile: '' };
}
function emptyPasswordForm() {
  return { old_password: '', new_password: '' };
}
function emptyInviteForm() {
  return { length: 20, code: '' };
}
function emptyGroupForm() {
  return { id: 0, group_code: '', name: '', description: '', threshold_mode: 'none', threshold_value: 0, downgrade_on_balance: 0, markup_mode: 'fixed', markup_value: 0, recharge_bonus_rate: 1, allow_api_default: 0, sort_order: 0, product_prices: {} };
}
function emptyUserForm() {
  return { id: 0, username: '', nickname: '', qq: '', email: '', mobile: '', password: '', balance: 0, user_group_id: 0, status: 'active', account_role: 'member', connect_policy: 'default', created_at: '', last_login_at: '', last_login_ip: '', invite_count: 0 };
}
function emptyUpstreamForm() {
  return { id: 0, name: '', base_url: '', upstream_uid: 0, upstream_api_key: '', enabled: 1, is_default: 0 };
}
function emptyMerchantForm() {
  return { id: 0, name: '', endpoint: '', pid: '', merchant_key: '', enabled: 1 };
}
function emptyChannelForm() {
  return { id: 0, code: '', name: '', pay_type: '', merchant_id: 0, enabled: 1, sort_order: 0 };
}
function emptyCardGenForm() {
  return { count: 10, amount: 10000, uses: 1, prefix: '', custom_code: '', note: '' };
}
function emptyCardEditForm() {
  return { id: 0, code: '', amount: 0, total_uses: 1, remaining_uses: 1, enabled: 1, note: '' };
}
function emptyRechargeForm() {
  return { channel_id: 0, money: '' };
}
function defaultThemeConfig() {
  return {
    bg: '#f3f7fc',
    surface: '#ffffff',
    surface_soft: '#f7fbff',
    text: '#172b4d',
    muted: '#6f7f95',
    line: '#d9e4f2',
    primary: '#1f6feb',
    success: '#169b62',
    warning: '#d78a18',
    danger: '#dc4c64',
    header_bg: 'rgba(255,255,255,0.88)',
    header_border: '#d9e4f2',
    logo_text: '#ffffff',
    avatar_bg: '#eef2ff',
    captcha_bg: '#eef2ff',
    captcha_line: '#8aa4d6',
    captcha_text: '#1f3f78',
    button_default_bg: '#edf1f8',
    button_default_text: '#334056',
    button_primary_text: '#ffffff',
    button_success_bg: '#e8f8f0',
    button_success_text: '#12794c',
    button_warning_bg: '#fff3de',
    button_warning_text: '#98610f',
    button_danger_bg: '#ffedf2',
    button_danger_text: '#c23c5a',
    input_bg: '#ffffff',
    input_border: '#dce4ef',
    input_focus_ring: 'rgba(31,111,235,0.16)',
    sidebar_bg: '#ffffff',
    sidebar_border: '#d9e4f2',
    sidebar_title_text: '#536177',
    nav_text: '#4a5870',
    nav_active_bg: '#edf4ff',
    nav_active_text: '#1f6feb',
    nav_hover_bg: '#f6f9fd',
    badge_info_bg: '#edf4ff',
    badge_info_text: '#1f6feb',
    badge_success_bg: '#e8f8f0',
    badge_success_text: '#12794c',
    badge_warning_bg: '#fff3de',
    badge_warning_text: '#98610f',
    badge_danger_bg: '#ffedf2',
    badge_danger_text: '#c23c5a',
    table_head_bg: '#f6f9fc',
    table_head_text: '#536177',
    table_bg: '#ffffff',
    desc_bg: '#f8faff',
    qr_bg: '#ffffff',
    qr_border: '#d8dff0',
    tip_bg: '#fff8e8',
    tip_border: '#f2ddb0',
    tip_text: '#8a5b0d',
    code_item_bg: '#fbfcff',
    subtle_bg: '#f8faff',
    editor_bg: '#fbfcff',
    admin_note_bg: '#eff6ff',
    admin_note_border: '#d5e5ff',
    admin_note_text: '#355481',
    modal_bg: '#ffffff',
    overlay_bg: 'rgba(16,20,31,0.54)',
    loading_bg: 'rgba(244,247,251,0.72)',
    loading_card_bg: '#ffffff',
    spinner_track: '#d6ddff',
    toast_info: '#4856e9',
    toast_success: '#1a9d67',
    toast_warning: '#d68e1d',
    toast_danger: '#df4d6c',
    mono_bg: '#0f172a',
    mono_text: '#d7e0ef',
    shadow_color: 'rgba(34,48,88,0.08)',
  };
}
function emptySettingsForm() {
  return {
    site_name: '', site_keywords: '', site_description: '', site_favicon: '', site_logo: '', seo_footer: '', custom_css: '', custom_js: '', custom_resource_urls: [], currency_name: '棰濆害', admin_path: '/admin', community_group_qq: '', support_group_qq: '', icp_beian_no: '', public_security_beian_no: '',
    frontend_order_enabled: 1, api_order_enabled: 1, feed_image_mode: 'self_proxy', home_template: 'default',
    register_need_email: 0, register_need_mobile: 0, register_need_image_captcha: 1, register_need_geetest: 0, register_need_sms_code: 0, register_need_email_code: 0,
    login_need_sms: 0, login_need_email: 0, login_need_geetest: 0, login_need_image_captcha: 1,
    default_register_strategy_user: 0, default_register_strategy_agent: 0,
    invite_valid_mode: 'total_consume', invite_valid_value: '100000', balance_downgrade_enabled: 0,
    exchange_code_enabled: 1, exchange_code_generation_fee: 0, exchange_code_prefix: 'XM', exchange_code_random_length: 36, exchange_code_format: '{prefix}{uid}{random}', exchange_code_cookie_days: 60,
    sms_provider: 'custom_http',
    smtp_config: { host: '', port: 465, username: '', password: '', encryption: 'ssl', from: '', from_name: '' },
    geetest_config: { captcha_id: '', captcha_key: '' },
    sms_config: { url: '', method: 'POST', headers: {}, query: {}, body: {}, success_field: '', success_value: '' },
    sms_headers_rows: [], sms_query_rows: [], sms_body_rows: [],
    invite_code_price_rules: { mode: 'fixed', fixed: 0, length_rules: [] },
    theme_config: defaultThemeConfig()
  };
}
function settingsToForm(raw) {
  const form = emptySettingsForm();
  Object.keys(form).forEach(function (key) {
    if (['smtp_config', 'geetest_config', 'sms_config', 'sms_headers_rows', 'sms_query_rows', 'sms_body_rows', 'invite_code_price_rules', 'theme_config', 'custom_resource_urls'].includes(key)) return;
    if (raw[key] !== undefined) form[key] = raw[key];
  });
  form.frontend_order_enabled = Number(raw.frontend_order_enabled ?? 1);
  form.api_order_enabled = Number(raw.api_order_enabled ?? 1);
  form.register_need_email = Number(raw.register_need_email ?? 0);
  form.register_need_mobile = Number(raw.register_need_mobile ?? 0);
  form.register_need_image_captcha = Number(raw.register_need_image_captcha ?? 1);
  form.register_need_geetest = Number(raw.register_need_geetest ?? 0);
  form.register_need_sms_code = Number(raw.register_need_sms_code ?? 0);
  form.register_need_email_code = Number(raw.register_need_email_code ?? 0);
  form.login_need_sms = Number(raw.login_need_sms ?? 0);
  form.login_need_email = Number(raw.login_need_email ?? 0);
  form.login_need_geetest = Number(raw.login_need_geetest ?? 0);
  form.login_need_image_captcha = Number(raw.login_need_image_captcha ?? 1);
  form.default_register_strategy_user = Number(raw.default_register_strategy_user ?? 0);
  form.default_register_strategy_agent = Number(raw.default_register_strategy_agent ?? 0);
  form.balance_downgrade_enabled = Number(raw.balance_downgrade_enabled ?? 0);
  form.exchange_code_enabled = Number(raw.exchange_code_enabled ?? 1);
  form.exchange_code_generation_fee = Number(raw.exchange_code_generation_fee ?? 0);
  form.exchange_code_random_length = Number(raw.exchange_code_random_length ?? 36);
  form.exchange_code_cookie_days = Number(raw.exchange_code_cookie_days ?? 60);
  form.smtp_config = Object.assign(form.smtp_config, parseJson(raw.smtp_config, form.smtp_config));
  form.geetest_config = Object.assign(form.geetest_config, parseJson(raw.geetest_config, form.geetest_config));
  form.sms_provider = raw.sms_provider || 'custom_http';
  form.sms_config = Object.assign(form.sms_config, parseJson(raw.sms_config, form.sms_config));
  form.sms_headers_rows = toRows(form.sms_config.headers || {});
  form.sms_query_rows = toRows(form.sms_config.query || {});
  form.sms_body_rows = toRows(form.sms_config.body || {});
  form.invite_code_price_rules = Object.assign({ mode: 'fixed', fixed: 0, length_rules: [] }, parseJson(raw.invite_code_price_rules, { mode: 'fixed', fixed: 0, length_rules: [] }));
  if (!Array.isArray(form.invite_code_price_rules.length_rules)) form.invite_code_price_rules.length_rules = [];
  form.invite_code_price_rules.length_rules = form.invite_code_price_rules.length_rules.map(function (item) {
    return { length: String(item.length || '6').trim(), price: Number(item.price || 0) };
  });
  form.theme_config = Object.assign(defaultThemeConfig(), parseJson(raw.theme_config, defaultThemeConfig()));
  const resources = parseJson(raw.custom_resource_urls, []);
  form.custom_resource_urls = Array.isArray(resources) ? resources.filter(function (item) { return item && typeof item === 'object'; }).map(function (item) { return { type: item.type === 'js' ? 'js' : 'css', url: String(item.url || '') }; }) : [];
  return form;
}
function formToSettingsPayload(form) {
  const payload = clone(form);
  payload.sms_config = payload.sms_provider === 'custom_http'
    ? {
        url: payload.sms_config.url || '',
        method: payload.sms_config.method || 'POST',
        headers: rowsToObject(payload.sms_headers_rows),
        query: rowsToObject(payload.sms_query_rows),
        body: rowsToObject(payload.sms_body_rows),
        success_field: payload.sms_config.success_field || '',
        success_value: payload.sms_config.success_value || ''
      }
    : clone(payload.sms_config);
  delete payload.sms_headers_rows;
  delete payload.sms_query_rows;
  delete payload.sms_body_rows;
  delete payload.owner_feedback_group_qq;
  payload.invite_code_price_rules.length_rules = (payload.invite_code_price_rules.length_rules || []).map(function (item) {
    return { length: String(item.length || '6').trim(), price: Number(item.price || 0) };
  });
  payload.theme_config = Object.assign({}, defaultThemeConfig(), payload.theme_config || {});
  payload.custom_resource_urls = (payload.custom_resource_urls || []).map(function (item) { return { type: item.type === 'js' ? 'js' : 'css', url: String(item.url || '').trim() }; }).filter(function (item) { return item.url !== ''; });
  return payload;
}
function normalizeAdminProduct(product) {
  product.allow_frontend_bool = boolish(product.allow_frontend);
  product.allow_api_bool = boolish(product.allow_api);
  product.enabled_bool = boolish(product.enabled);
  product.sort_order = Number(product.sort_order || 0);
  product.discounts = Array.isArray(product.discounts) ? product.discounts.map(function (item) {
    return { min_quantity: Number(item.min_quantity || 1), discount_rate: Number(item.discount_rate || 1) };
  }) : [];
  return product;
}

const app = Vue.createApp({
  data: function () {
    return {
      routeMode: BOOT.routeMode || 'home',
      site: BOOT.site || { name: '', description: '' },
      currentVersion: BOOT.version || { version: 'v1.0.0', features: [] },
      settings: BOOT.settings || {},
      homeStats: BOOT.homeStats || { product_count: 0, order_count: 0, total_quantity: 0, items: [] },
      currency: BOOT.currency || '棰濆害',
      adminUrl: BOOT.adminUrl || BOOT.adminPath || '/admin',
      baseUrl: BOOT.baseUrl || '',
      ownerFeedbackGroupQq: '143805881',
      currentPath: BOOT.currentPath || '/',
      user: BOOT.user || null,
      loading: false,
      loadingText: '澶勭悊涓?..',
      toasts: [],
      toastSeed: 1,
      captchaVersion: Date.now(),
      home: {
        login: { username: '', password: '', captcha: '' },
        register: { username: '', nickname: '', qq: '', password: '', invite_code: '', email: '', mobile: '', captcha: '' }
      },
      profile: { user: BOOT.user || null, group: null, api_access: null },
      profileForm: emptyProfileForm(),
      passwordForm: emptyPasswordForm(),
      inviteForm: emptyInviteForm(),
      userTab: 'dashboard',
      userState: {
        products: [], productKeyword: '', feedItems: [], feedModalVisible: false,
        orders: [], orderSearch: '', orderDetail: null,
        groups: [], invites: { codes: [], records: [] },
        payments: { channels: [], orders: [] }, paymentResult: null,
        exchangeCodes: [], exchangeSettings: null
      },
      orderForm: { sign: '', qq: (BOOT.user && BOOT.user.qq) ? BOOT.user.qq : '', num: 0, feed_id: '', is_delayed: false, extra: {} },
      exchangeCodeForm: { sign: '', quantity: 0, count: 1, generatedCodes: [] },
      exchangePublic: { code: '', preview: null, form: { qq: '', extra: {} }, orders: [], orderSearch: '', orderDetail: null },
      exchangeEditState: { visible: false, admin: false, form: { id: 0, code: '', sign: '', quantity: 0 } },
      quote: null,
      rechargeForm: emptyRechargeForm(),
      cardRedeemCode: '',
      adminTab: 'dashboard',
      adminSidebarCollapsed: false,
      adminMenuOpenKeys: { products: true, groups: true, users: true, orders: true, api: true, recharge: true, exchange: true, settings: true, logs: true },
      adminState: {
        dashboard: null, products: [], groups: [], users: [], userKeyword: '', orders: [], orderSearch: '', orderDetail: null, upstream: [], upstreamBalance: null, upstreamBalanceError: '', cards: [],
        payments: { merchants: [], channels: [], recharge_orders: [] }, settingsRaw: {}, scheduledTasks: { system_key: '', products_endpoint: '', orders_endpoint: '' }, version: { current: null, remote: null, has_update: false, git_available: false, can_update: false, checked_at: '', message: '' }, logs: [], logLevel: '', logChannel: '',
        exchange: { codes: [], logs: [], filters: { product_id: '', status: '', redeemer_qq: '', sort: 'created_desc' } }
      },
      groupForm: emptyGroupForm(),
      userForm: emptyUserForm(),
      upstreamForm: emptyUpstreamForm(),
      merchantForm: emptyMerchantForm(),
      channelForm: emptyChannelForm(),
      cardGenForm: emptyCardGenForm(),
      cardEditForm: emptyCardEditForm(),
      settingsForm: emptySettingsForm(),
      apiSettings: { api_condition_mode: 'total_recharge', api_condition_operator: '>=', api_condition_value: '0' },
      quoteTimer: null,
      confirmState: { visible: false, title: '', message: '', confirmText: '纭', resolver: null }
    };
  },
  computed: {
    captchaUrl: function () { return this.routeUrl('/captcha/image?_=' + this.captchaVersion); },
    exchangePageUrl: function () {
      const path = this.routeUrl('/exchange');
      try {
        return new URL(path, window.location.href).href;
      } catch (error) {
        return path;
      }
    },
    canAccessAdmin: function () {
      if (!this.user) return false;
      return ['owner', 'admin'].includes(String(this.user.account_role || ''));
    },
    isOwner: function () {
      return !!this.user && String(this.user.account_role || '') === 'owner';
    },
    canShowSupportGroup: function () {
      return boolish(this.settings.can_show_support_group) && String(this.settings.support_group_qq || '').trim() !== '';
    },
    versionFeatures: function () {
      const source = this.adminState.version.has_update && this.adminState.version.remote ? this.adminState.version.remote : (this.adminState.version.current || this.currentVersion || {});
      return Array.isArray(source.features) && source.features.length ? source.features : ['鏆傛棤鐗堟湰鐗规€ц鏄?];
    },
    userLoginNeedCaptcha: function () {
      return true;
    },
    registerNeedCaptcha: function () {
      return boolish(this.settings.register_need_image_captcha);
    },
    needRegisterEmail: function () { return boolish(this.settings.register_need_email); },
    needRegisterMobile: function () { return boolish(this.settings.register_need_mobile); },
    userNav: function () {
      return [
        { key: 'dashboard', label: '棣栭〉' },
        { key: 'order', label: '鍦ㄧ嚎涓嬪崟' },
        { key: 'orders', label: '鏌ュ崟绯荤粺' },
        { key: 'exchange_codes', label: '鍟嗗搧鍏戞崲鐮? },
        { key: 'recharge', label: '棰濆害鍏呭€? },
        { key: 'invites', label: '閭€璇风鐞? },
        { key: 'groups', label: '浠ｇ悊绛夌骇' },
        { key: 'profile', label: '涓汉璧勬枡' }
      ];
    },
    adminNav: function () {
      return [
        { key: 'dashboard', label: '绠＄悊棣栭〉' },
        { key: 'products', label: '鍟嗗搧绠＄悊', children: [
          { key: 'products-sync', label: '鏇存柊鍟嗗搧鏁版嵁', description: '鎵嬪姩鍚屾涓婃父鍟嗗搧鍒版湰鍦般€? },
          { key: 'products-list', label: '绠＄悊鍟嗗搧', description: '鎺у埗鍓嶅彴涓婃灦銆佸鎺ュ紑鍏充笌鎶樻墸銆? }
        ] },
        { key: 'groups', label: '鐢ㄦ埛缁勭鐞?, children: [
          { key: 'groups-list', label: '鏂板 / 绠＄悊鐢ㄦ埛缁?, description: '缂栬緫鐢ㄦ埛缁勯棬妲涖€佸姞浠蜂笌浠嬬粛銆? },
          { key: 'groups-default', label: '娉ㄥ唽榛樿鐢ㄦ埛缁?, description: '璁剧疆鏂扮敤鎴锋敞鍐岄粯璁ゆ墍灞炵敤鎴风粍銆? }
        ] },
        { key: 'users', label: '鐢ㄦ埛绠＄悊', children: [
          { key: 'users-list', label: '鐢ㄦ埛鍒楄〃', description: '鏌ョ湅鐢ㄦ埛銆佹悳绱€佸皝绂併€佸垹闄ゃ€佹敼浣欓銆? },
          { key: 'users-create', label: '鏂板 / 缂栬緫鐢ㄦ埛', description: '绠＄悊鍛樻敞鍐岀敤鎴蜂笌淇敼鍩虹璧勬枡銆? }
        ] },
        { key: 'orders', label: '璁㈠崟绠＄悊', children: [
          { key: 'orders-list', label: '閫熷埛璁㈠崟鍒楄〃', description: '澶勭悊琛ュ崟銆侀€€鍗曚笌浠呴€€娆俱€? },
          { key: 'recharge-orders', label: '鍏呭€艰鍗曞垪琛?, description: '鏌ョ湅鐢ㄦ埛鍏呭€艰鍗曚笌鏀粯缁撴灉銆? }
        ] },
        { key: 'api', label: '瀵规帴璁剧疆', children: [
          { key: 'api-conditions', label: '鏉′欢璁剧疆', description: '閰嶇疆 API Key 鐢熸垚鏉′欢銆? },
          { key: 'upstream-manage', label: '涓婃父绠＄悊', description: '閰嶇疆涓婃父璐﹀彿骞舵鏌?allow 鐘舵€併€? },
          { key: 'api-keys', label: '瀵嗛挜绠＄悊', description: '鏌ョ湅骞堕噸缃敤鎴?API Key銆? }
        ] },
        { key: 'recharge', label: '鍏呭€艰缃?, children: [
          { key: 'cards-generate', label: '鍗″瘑鐢熸垚', description: '鎵归噺鐢熸垚闅忔満鎴栬嚜瀹氫箟鍗″瘑銆? },
          { key: 'cards-list', label: '鍗″瘑鍒楄〃', description: '缂栬緫銆侀攢姣佸拰妫€鏌ュ崱瀵嗕娇鐢ㄦ儏鍐点€? },
          { key: 'payments-merchants', label: '鏄撴敮浠橀厤缃?, description: '閰嶇疆澶氫釜鏄撴敮浠樺晢鎴枫€? },
          { key: 'payments-channels', label: '鏀粯閫氶亾閰嶇疆', description: '灏嗘敮浠樻柟寮忕粦瀹氬埌鍏蜂綋鍟嗘埛銆? }
        ] },
        { key: 'exchange', label: '鍟嗗搧鍏戞崲鐮?, children: [
          { key: 'exchange-rules', label: '鍏戞崲鐮佽鍒?, description: '閰嶇疆鍏戞崲鐮佹牸寮忋€佸墠缂€鍜?Cookie 鏃堕暱銆? },
          { key: 'exchange-list', label: '鍏戞崲鐮佸垪琛?, description: '鏌ョ湅鐢熸垚涓庡厬鎹㈡儏鍐点€? },
          { key: 'exchange-logs', label: '鍏戞崲鐮佹棩蹇?, description: '鏌ョ湅鍏戞崲鐮佹搷浣滄棩蹇椼€? }
        ] },
        { key: 'settings', label: '绯荤粺璁剧疆', children: [
          { key: 'settings-basic', label: 'SEO / 鍩虹璁剧疆', description: '绔欑偣鍚嶇О銆佸妗堛€佺兢鍙蜂笌鍚庡彴璺緞銆? },
          { key: 'settings-theme', label: '鐣岄潰涓婚', description: '鍚庡彴鍖栨墍鏈夐鑹插苟鏀寔瀵煎叆瀵煎嚭銆? },
          { key: 'settings-sms', label: '鐭俊 / 閭欢 / 鏋侀獙', description: '閰嶇疆鑵捐浜戙€侀樋閲屼簯銆佽嚜瀹氫箟 HTTP 涓?SMTP銆? },
          { key: 'settings-security', label: '鐧诲綍 / 閭€璇?/ 鍏朵粬', description: '鐧诲綍娉ㄥ唽绛栫暐銆侀個璇风爜瑙勫垯鍜岄椤靛紑鍏炽€? },
          { key: 'settings-custom', label: '鑷畾涔?CSS / JS', description: '閰嶇疆鑷畾涔夋牱寮忋€佽剼鏈拰绗笁鏂硅祫婧愩€? },
          { key: 'settings-version', label: '鐗堟湰涓庢洿鏂?, description: '妫€娴嬫柊鐗堟湰骞舵煡鐪嬬増鏈壒鎬с€? },
          { key: 'scheduled-tasks', label: '瀹氭椂浠诲姟 API', description: '绠＄悊澶栭儴瀹氭椂璋冪敤瀵嗛挜涓庢帴鍙ｃ€? }
        ] },
        { key: 'logs', label: '绯荤粺鏃ュ織', children: [
          { key: 'logs-list', label: '鏃ュ織鍒楄〃', description: '鎸夌瓑绾т笌棰戦亾绛涢€夌郴缁熸棩蹇椼€? }
        ] }
      ];
    },
    adminPageKeys: function () {
      const keys = [];
      this.adminNav.forEach(function (item) {
        if (item.children && item.children.length) {
          item.children.forEach(function (child) { keys.push(child.key); });
        } else {
          keys.push(item.key);
        }
      });
      return keys;
    },
    adminCurrentMeta: function () {
      let found = { label: '绠＄悊棣栭〉', description: '姒傝绯荤粺鍏抽敭鏁版嵁銆?, parent: 'dashboard' };
      this.adminNav.forEach(function (item) {
        if (item.key === this.adminTab && !item.children) {
          found = { label: item.label, description: item.description || '', parent: item.key };
          return;
        }
        (item.children || []).forEach(function (child) {
          if (child.key === this.adminTab) found = { label: child.label, description: child.description || '', parent: item.key };
        }, this);
      }, this);
      return found;
    },
    filteredAdminUsers: function () {
      const rows = Array.isArray(this.adminState.users) ? this.adminState.users : [];
      const keyword = String(this.adminState.userKeyword || '').trim().toLowerCase();
      if (!keyword) return rows;
      return rows.filter(function (row) {
        return [row.username, row.nickname, row.qq, row.uid, row.account_role, row.role_label]
          .some(function (value) { return String(value || '').toLowerCase().includes(keyword); });
      });
    },
    apiKeyUsers: function () {
      const rows = Array.isArray(this.adminState.users) ? this.adminState.users : [];
      return rows.filter(function (row) {
        return String(row.api_key || '').trim() !== '';
      });
    },
    selectedProduct: function () {
      const sign = this.orderForm.sign || '';
      return this.userState.products.find(function (item) { return item.upstream_sign === sign; }) || null;
    },
    selectedExchangeProduct: function () {
      const sign = this.exchangeCodeForm.sign || '';
      return this.userState.products.find(function (item) { return item.upstream_sign === sign; }) || null;
    },
    filteredProducts: function () {
      const keyword = String(this.userState.productKeyword || '').trim().toLowerCase();
      if (!keyword) return this.userState.products;
      return this.userState.products.filter(function (item) {
        return String(item.name || '').toLowerCase().includes(keyword) || String(item.upstream_sign || '').toLowerCase().includes(keyword);
      });
    },
    exchangeInputFields: function () {
      const rawFields = Array.isArray(this.exchangePublic.preview && this.exchangePublic.preview.inputs ? this.exchangePublic.preview.inputs : []) ? this.exchangePublic.preview.inputs : [];
      return rawFields.map(function (item, index) {
        if (typeof item === 'string') return { key: item, label: item, placeholder: '' };
        if (item && typeof item === 'object') {
          const key = item.name || item.key || item.field || ('field_' + index);
          return { key: key, label: item.label || item.title || key, placeholder: item.placeholder || '' };
        }
        return { key: 'field_' + index, label: '鍙傛暟 ' + (index + 1), placeholder: '' };
      }).filter(function (field) {
        return !['sign', 'num', 'uid', 'api_key', 'limit', 'qq'].includes(field.key);
      });
    },
    dynamicInputFields: function () {
      const product = this.selectedProduct;
      const rawFields = Array.isArray(product && product.input ? product.input : []) ? product.input : [];
      return rawFields.map(function (item, index) {
        if (typeof item === 'string') {
          return { key: item, label: item, placeholder: '' };
        }
        if (item && typeof item === 'object') {
          const key = item.key || item.name || item.field || item.input || ('field_' + index);
          return { key: key, label: item.label || item.title || key, placeholder: item.placeholder || '' };
        }
        return { key: 'field_' + index, label: '鍙傛暟 ' + (index + 1), placeholder: '' };
      }).filter(function (field) {
        return !['sign', 'num', 'qq', 'limit', 'uid', 'api_key', 'is_delayed'].includes(field.key);
      });
    },
    showDelayedOption: function () {
      const product = this.selectedProduct;
      if (!product) return false;
      return product.min_delayed !== null || product.price_cost_delayed !== null;
    },
    selectedFeedId: function () { return this.orderForm.feed_id || ''; },
    rechargePreview: function () {
      const money = Number(this.rechargeForm.money || 0);
      const cents = Math.round(money * 100);
      const creditAmount = cents > 0 ? cents * 100 : 0;
      const bonusRate = Number(this.profile.group && this.profile.group.recharge_bonus_rate ? this.profile.group.recharge_bonus_rate : 1);
      const bonusAmount = Math.max(0, Math.round(creditAmount * Math.max(0, bonusRate - 1)));
      return { credit_amount: creditAmount, bonus_amount: bonusAmount, expected_amount: creditAmount + bonusAmount };
    },
    paymentJumpLink: function () {
      const info = this.userState.paymentResult || {};
      return info.pay_link || info.pay_qrcode_text || info.pay_url || info.urlscheme || '#';
    },
    invitePricePreview: function () {
      const rules = this.settings.invite_code_price_rules || { mode: 'fixed', fixed: 0, length_rules: [] };
      const custom = String(this.inviteForm.code || '').trim();
      const length = custom ? custom.length : Number(this.inviteForm.length || 0);
      if (custom && length < 6) return 0;
      if (rules.mode === 'length') {
        const matched = (rules.length_rules || []).find((rule) => this.matchesInviteRuleLength(length, rule.length));
        if (matched) return Number(matched.price || 0);
      }
      return Number(rules.fixed || 0);
    },
    themeGroups: function () {
      return [
        { title: '鍩虹鑹?, items: [
          { key: 'bg', label: '椤甸潰鑳屾櫙', type: 'color' }, { key: 'surface', label: '鍗＄墖鑳屾櫙', type: 'color' }, { key: 'surface_soft', label: '鏌斿拰鑳屾櫙', type: 'color' },
          { key: 'text', label: '姝ｆ枃鏂囧瓧', type: 'color' }, { key: 'muted', label: '杈呭姪鏂囧瓧', type: 'color' }, { key: 'line', label: '杈规棰滆壊', type: 'color' },
          { key: 'primary', label: '涓婚涓昏壊', type: 'color' }, { key: 'success', label: '鎴愬姛鑹?, type: 'color' }, { key: 'warning', label: '璀﹀憡鑹?, type: 'color' }, { key: 'danger', label: '鍗遍櫓鑹?, type: 'color' }
        ] },
        { title: '椤靛ご / 瀵艰埅', items: [
          { key: 'header_bg', label: '椤靛ご鑳屾櫙', type: 'text' }, { key: 'header_border', label: '椤靛ご杈规', type: 'color' }, { key: 'logo_text', label: 'Logo鏂囧瓧', type: 'color' }, { key: 'avatar_bg', label: '澶村儚搴曡壊', type: 'color' },
          { key: 'sidebar_bg', label: '渚ф爮鑳屾櫙', type: 'color' }, { key: 'sidebar_border', label: '渚ф爮杈规', type: 'color' }, { key: 'sidebar_title_text', label: '渚ф爮鏍囬', type: 'color' },
          { key: 'nav_text', label: '瀵艰埅鏂囧瓧', type: 'color' }, { key: 'nav_active_bg', label: '瀵艰埅婵€娲昏儗鏅?, type: 'color' }, { key: 'nav_active_text', label: '瀵艰埅婵€娲绘枃瀛?, type: 'color' }, { key: 'nav_hover_bg', label: '瀵艰埅鎮仠鑳屾櫙', type: 'color' }
        ] },
        { title: '鎸夐挳 / 琛ㄥ崟', items: [
          { key: 'button_default_bg', label: '榛樿鎸夐挳鑳屾櫙', type: 'color' }, { key: 'button_default_text', label: '榛樿鎸夐挳鏂囧瓧', type: 'color' }, { key: 'button_primary_text', label: '涓绘寜閽枃瀛?, type: 'color' },
          { key: 'button_success_bg', label: '鎴愬姛鎸夐挳鑳屾櫙', type: 'color' }, { key: 'button_success_text', label: '鎴愬姛鎸夐挳鏂囧瓧', type: 'color' }, { key: 'button_warning_bg', label: '璀﹀憡鎸夐挳鑳屾櫙', type: 'color' },
          { key: 'button_warning_text', label: '璀﹀憡鎸夐挳鏂囧瓧', type: 'color' }, { key: 'button_danger_bg', label: '鍗遍櫓鎸夐挳鑳屾櫙', type: 'color' }, { key: 'button_danger_text', label: '鍗遍櫓鎸夐挳鏂囧瓧', type: 'color' },
          { key: 'input_bg', label: '杈撳叆妗嗚儗鏅?, type: 'color' }, { key: 'input_border', label: '杈撳叆妗嗚竟妗?, type: 'color' }, { key: 'input_focus_ring', label: '杈撳叆妗嗚仛鐒︾幆', type: 'text' }
        ] },
        { title: '淇℃伅鍧?/ 寰界珷 / 琛ㄦ牸', items: [
          { key: 'badge_info_bg', label: '淇℃伅寰界珷鑳屾櫙', type: 'color' }, { key: 'badge_info_text', label: '淇℃伅寰界珷鏂囧瓧', type: 'color' }, { key: 'badge_success_bg', label: '鎴愬姛寰界珷鑳屾櫙', type: 'color' }, { key: 'badge_success_text', label: '鎴愬姛寰界珷鏂囧瓧', type: 'color' },
          { key: 'badge_warning_bg', label: '璀﹀憡寰界珷鑳屾櫙', type: 'color' }, { key: 'badge_warning_text', label: '璀﹀憡寰界珷鏂囧瓧', type: 'color' }, { key: 'badge_danger_bg', label: '鍗遍櫓寰界珷鑳屾櫙', type: 'color' }, { key: 'badge_danger_text', label: '鍗遍櫓寰界珷鏂囧瓧', type: 'color' },
          { key: 'table_head_bg', label: '琛ㄥご鑳屾櫙', type: 'color' }, { key: 'table_head_text', label: '琛ㄥご鏂囧瓧', type: 'color' }, { key: 'table_bg', label: '琛ㄦ牸鑳屾櫙', type: 'color' },
          { key: 'desc_bg', label: '鎻忚堪鍧楄儗鏅?, type: 'color' }, { key: 'tip_bg', label: '鎻愮ず鑳屾櫙', type: 'color' }, { key: 'tip_border', label: '鎻愮ず杈规', type: 'color' }, { key: 'tip_text', label: '鎻愮ず鏂囧瓧', type: 'color' },
          { key: 'code_item_bg', label: '鍏戞崲鐮佸崱鐗囪儗鏅?, type: 'color' }, { key: 'subtle_bg', label: '娴呰壊淇℃伅鍧楄儗鏅?, type: 'color' }, { key: 'editor_bg', label: '缂栬緫鍣ㄥ簳鑹?, type: 'color' },
          { key: 'admin_note_bg', label: '鍚庡彴璇存槑鑳屾櫙', type: 'color' }, { key: 'admin_note_border', label: '鍚庡彴璇存槑杈规', type: 'color' }, { key: 'admin_note_text', label: '鍚庡彴璇存槑鏂囧瓧', type: 'color' }
        ] },
        { title: '寮瑰眰 / 浜岀淮鐮?/ 浠ｇ爜 / 鎻愮ず', items: [
          { key: 'qr_bg', label: '浜岀淮鐮佽儗鏅?, type: 'color' }, { key: 'qr_border', label: '浜岀淮鐮佽竟妗?, type: 'color' },
          { key: 'captcha_bg', label: '楠岃瘉鐮佽儗鏅?, type: 'color' }, { key: 'captcha_line', label: '楠岃瘉鐮佸共鎵扮嚎', type: 'color' }, { key: 'captcha_text', label: '楠岃瘉鐮佹枃瀛?, type: 'color' },
          { key: 'modal_bg', label: '寮圭獥鑳屾櫙', type: 'color' }, { key: 'overlay_bg', label: '閬僵鑳屾櫙', type: 'text' }, { key: 'loading_bg', label: '鍔犺浇閬僵', type: 'text' }, { key: 'loading_card_bg', label: '鍔犺浇鍗＄墖鑳屾櫙', type: 'color' },
          { key: 'spinner_track', label: '鍔犺浇鐜簳鑹?, type: 'color' }, { key: 'toast_info', label: '鏅€氭彁绀?, type: 'color' }, { key: 'toast_success', label: '鎴愬姛鎻愮ず', type: 'color' }, { key: 'toast_warning', label: '璀﹀憡鎻愮ず', type: 'color' }, { key: 'toast_danger', label: '鍗遍櫓鎻愮ず', type: 'color' },
          { key: 'mono_bg', label: '浠ｇ爜鍧楄儗鏅?, type: 'color' }, { key: 'mono_text', label: '浠ｇ爜鍧楁枃瀛?, type: 'color' }, { key: 'shadow_color', label: '闃村奖棰滆壊', type: 'text' }
        ] }
      ];
    },
    maxDashboardRankLength: function () {
      const dashboard = this.adminState.dashboard || {};
      const values = [dashboard.today_consume_rank || [], dashboard.total_consume_rank || [], dashboard.balance_rank || [], dashboard.today_recharge_rank || []];
      return Math.max(1, ...values.map(function (arr) { return arr.length || 0; }));
    }
  },
  watch: {
    'orderForm.sign': function () { this.resetOrderExtras(); this.scheduleQuote(); },
    'orderForm.num': function () { this.scheduleQuote(); },
    'orderForm.is_delayed': function () { this.scheduleQuote(); },
    'settingsForm.sms_provider': function (value) {
      if (value === 'tencent') {
        this.settingsForm.sms_config = Object.assign({ secret_id: '', secret_key: '', sdk_app_id: '', template_id: '', region: 'ap-guangzhou' }, this.settingsForm.sms_config || {});
      } else if (value === 'aliyun') {
        this.settingsForm.sms_config = Object.assign({ access_key_id: '', access_key_secret: '', template_code: '', region: 'cn-hangzhou', endpoint: '' }, this.settingsForm.sms_config || {});
      } else {
        this.settingsForm.sms_config = Object.assign({ url: '', method: 'POST', headers: {}, query: {}, body: {}, success_field: '', success_value: '' }, this.settingsForm.sms_config || {});
        this.settingsForm.sms_headers_rows = this.settingsForm.sms_headers_rows || [];
        this.settingsForm.sms_query_rows = this.settingsForm.sms_query_rows || [];
        this.settingsForm.sms_body_rows = this.settingsForm.sms_body_rows || [];
      }
    }
  },
  mounted: function () {
    this.applyTheme((this.settings && this.settings.theme_config) ? this.settings.theme_config : null);
    window.addEventListener('popstate', this.handlePopState);
    if (this.routeMode === 'exchange') {
      this.loadExchangeOrders(true);
    }
    if (this.routeMode === 'user') {
      this.userTab = this.userTabFromPath(this.currentPath);
      if (this.user) this.bootstrapUser();
    }
    if (this.routeMode === 'admin') {
      this.adminTab = this.adminTabFromPath(this.currentPath);
      const parent = this.adminParentKey(this.adminTab);
      if (parent) this.adminMenuOpenKeys[parent] = true;
      if (this.canAccessAdmin) this.bootstrapAdmin();
    }
  },
  beforeUnmount: function () {
    window.removeEventListener('popstate', this.handlePopState);
  },
  methods: {
    parseJson: parseJson,
    prettyJson: function (value) { return JSON.stringify(value || {}, null, 2); },
    displayName: function (user) { return (user && (user.nickname || user.username)) ? (user.nickname || user.username) : '鏈櫥褰?; },
    roleLabel: function (user) {
      const map = { owner: '绔欓暱 Owner', admin: '绠＄悊鍛?Admin', agent: '浠ｇ悊 Agent', member: '鏅€氱敤鎴?User' };
      return map[String(user && user.account_role ? user.account_role : 'member')] || '鐢ㄦ埛';
    },
    money: function (value) {
      return Number(value || 0).toLocaleString('zh-CN');
    },
    yuanApprox: function (value) {
      const amount = Number(value || 0);
      return '鈮? + (amount / 10000).toFixed(2) + '鍏?;
    },
    formatDate: function (value) {
      if (!value) return '-';
      try {
        const d = new Date(value);
        if (isNaN(d.getTime())) return String(value);
        const pad = function (n) { return String(n).padStart(2, '0'); };
        return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
      } catch (e) {
        return String(value);
      }
    },
    formatFeedTime: function (item) {
      const raw = item && typeof item === 'object' ? item : {};
      const ctime = Number(raw.ctime || 0);
      if (Number.isFinite(ctime) && ctime > 0) {
        const date = new Date(ctime * 1000);
        const pad = function (value) { return String(value).padStart(2, '0'); };
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + ' ' + pad(date.getHours()) + ':' + pad(date.getMinutes()) + ':' + pad(date.getSeconds());
      }
      return raw.time || raw.created_at || '-';
    },
    exchangeActionText: function (action) {
      return ({ create: '鐢熸垚鍏戞崲鐮?, redeem: '鍏戞崲骞朵笅鍗?, update: '缂栬緫鍏戞崲鐮?, destroy: '閿€姣佸厬鎹㈢爜' })[String(action || '')] || String(action || '鏈煡鎿嶄綔');
    },
    maskExchangeCode: function (code) {
      const text = String(code || '');
      return text.length <= 12 ? text : text.slice(0, 8) + '*'.repeat(Math.max(4, text.length - 12)) + text.slice(-4);
    },
    feedImageList: function (item) {
      const raw = item && typeof item === 'object' ? item : {};
      const pools = [raw.images, raw.pics, raw.picture_list, raw.photo_list, raw.imgs, raw.attachments];
      const result = [];
      pools.forEach(function (pool) {
        (Array.isArray(pool) ? pool : []).forEach(function (entry) {
          if (!entry) return;
          if (typeof entry === 'string') {
            result.push({ url: entry, original: entry, proxy: entry, display: entry });
            return;
          }
          if (typeof entry === 'object') {
            const original = entry.original || entry.origin || entry.src || entry.url || entry.pic || entry.raw || '';
            const proxy = entry.proxy || entry.proxy_url || entry.agent || entry.display || original;
            const display = entry.display || proxy || original;
            if (display || proxy || original) result.push({ display: display, proxy: proxy, original: original, url: entry.url || original });
          }
        });
      });
      if (!result.length) {
        const original = raw.image || raw.image_url || raw.pic_url || raw.original || '';
        const proxy = raw.proxy || raw.proxy_url || raw.display || original;
        if (original || proxy) result.push({ display: proxy || original, proxy: proxy, original: original, url: original || proxy });
      }
      return result.filter(function (entry) { return !!(entry.display || entry.proxy || entry.original || entry.url); });
    },
    closeFeedModal: function () {
      this.userState.feedModalVisible = false;
    },
    matchesInviteRuleLength: function (length, expr) {
      const source = String(expr || '').trim().replace(/锝?g, '~');
      const current = Number(length || 0);
      if (!source || !current) return false;
      const single = source.match(/^(\d{1,2})$/);
      if (single) return current === Number(single[1]);
      const range = source.match(/^(\d{1,2})\s*(?:-|~)\s*(\d{1,2})$/);
      if (!range) return false;
      let min = Number(range[1]);
      let max = Number(range[2]);
      if (min > max) { const temp = min; min = max; max = temp; }
      return current >= min && current <= max;
    },
    apiAccessHint: function (apiAccess) {
      if (!apiAccess) return '鍙仈绯荤綉绔欑鐞嗗憳鏇存敼瀵规帴鏉冮檺銆?;
      if (!apiAccess.can_generate_key) return '褰撳墠灏氭湭婊¤冻 API Key 鐢熸垚鏉′欢锛? + this.apiConditionText(apiAccess);
      return '鍙仈绯荤綉绔欑鐞嗗憳鏇存敼锛屾垨绛夊緟鎵€灞炵敤鎴风粍 / 鍗曠嫭瀵规帴鏉冮檺寮€鏀俱€?;
    },
    openGroup: function (kind) {
      const groupMap = {
        support: this.settings.support_group_qq || '',
        owner_feedback: this.ownerFeedbackGroupQq,
        community: this.settings.community_group_qq || ''
      };
      const groupCode = String(groupMap[kind] || groupMap.community || '').trim();
      if (!groupCode) {
        this.notify('褰撳墠鏆傛湭閰嶇疆缇ゅ彿', 'warning');
        return;
      }
      const groupLink = 'mqqapi://card/show_pslcard?src_type=internal&version=1&card_type=group&uin=' + encodeURIComponent(groupCode) + '&source=qrcode';
      const ua = String(navigator.userAgent || '').toLowerCase();
      const isQQ = /qq\//.test(ua) || /qqbrowser/.test(ua) || /mqqbrowser/.test(ua);
      if (isQQ && typeof window.mqq !== 'undefined' && window.mqq.ui && typeof window.mqq.ui.openUrl === 'function') {
        window.mqq.ui.openUrl({ url: groupLink, target: 1 });
        return;
      }
      window.location.href = groupLink;
    },
    applyTheme: function (theme) {
      const source = Object.assign({}, defaultThemeConfig(), theme || {});
      const root = document.documentElement;
      Object.keys(source).forEach(function (key) {
        const value = source[key];
        if (value === undefined || value === null || value === '') return;
        root.style.setProperty('--' + key.replace(/_/g, '-'), String(value));
      });
      root.style.setProperty('--primary-2', String(source.primary || defaultThemeConfig().primary));
      if (this.settingsForm && this.settingsForm.theme_config) this.settingsForm.theme_config = Object.assign({}, defaultThemeConfig(), this.settingsForm.theme_config, source);
    },
    exportTheme: function () {
      const source = clone((this.settingsForm && this.settingsForm.theme_config) ? this.settingsForm.theme_config : ((this.settings && this.settings.theme_config) ? this.settings.theme_config : {}));
      const blob = new Blob([JSON.stringify(source, null, 2)], { type: 'application/json;charset=utf-8' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = 'theme-config.json';
      link.click();
      URL.revokeObjectURL(link.href);
    },
    triggerThemeImport: function () {
      if (this.$refs.themeFileInput) this.$refs.themeFileInput.click();
    },
    restoreDefaultTheme: function () {
      const defaults = emptySettingsForm().theme_config;
      this.settingsForm.theme_config = Object.assign({}, defaults);
      this.applyTheme(this.settingsForm.theme_config);
      this.notify('宸叉仮澶嶉粯璁や富棰橀厤鑹诧紝璇疯寰椾繚瀛樼郴缁熻缃€?, 'success');
    },
    importThemeFile: async function (event) {
      const file = event && event.target && event.target.files ? event.target.files[0] : null;
      if (!file) return;
      try {
        const raw = await file.text();
        const parsed = JSON.parse(raw);
        if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) throw new Error('涓婚鏂囦欢鏍煎紡涓嶆纭?);
        this.settingsForm.theme_config = Object.assign({}, this.settingsForm.theme_config || {}, parsed);
        this.applyTheme(this.settingsForm.theme_config);
        this.notify('涓婚閰嶇疆宸插鍏ュ苟棰勮', 'success');
      } catch (error) {
        this.notify(error.message || '涓婚鏂囦欢瀵煎叆澶辫触', 'danger');
      } finally {
        if (event && event.target) event.target.value = '';
      }
    },
    qqAvatar: function (qq) {
      return 'https://q1.qlogo.cn/g?b=qq&nk=' + encodeURIComponent(String(qq || '0')) + '&s=100';
    },
    boolText: function (value) { return boolish(value) ? '宸插紑鍚? : '宸插叧闂?; },
    badgeTone: function (value) {
      const text = String(value || '');
      if (['宸插畬鎴?, '瀹屾垚', 'paid', 'active', 'success', 'done', 'info'].includes(text)) return 'success';
      if (['澶辫触', '宸查€€娆?, 'error', 'danger', 'banned', 'deleted'].includes(text)) return 'danger';
      if (['pending', 'processing', '琛ュ崟涓?, '鏈紑濮?, '澶勭悊涓?, 'warning'].includes(text)) return 'warning';
      return 'info';
    },
    notify: function (text, type) {
      const item = { id: this.toastSeed++, text: text, type: type || 'info' };
      this.toasts.push(item);
      window.setTimeout(() => {
        this.toasts = this.toasts.filter(function (toast) { return toast.id !== item.id; });
      }, 2600);
    },
    confirmAction: function (message, options) {
      const opts = options || {};
      return new Promise((resolve) => {
        this.confirmState = {
          visible: true,
          title: opts.title || '鎿嶄綔纭',
          message: message,
          confirmText: opts.confirmText || '纭',
          resolver: resolve
        };
      });
    },
    resolveConfirm: function (flag) {
      const resolver = this.confirmState && this.confirmState.resolver ? this.confirmState.resolver : null;
      this.confirmState = { visible: false, title: '', message: '', confirmText: '纭', resolver: null };
      if (resolver) resolver(!!flag);
    },
    cancelConfirm: function () {
      this.resolveConfirm(false);
    },
    refreshCaptcha: function () {
      this.captchaVersion = Date.now();
      this.home.login.captcha = '';
      this.home.register.captcha = '';
    },
    setBusy: function (flag, text) {
      this.loading = flag;
      this.loadingText = text || '澶勭悊涓?..';
    },
    withQuery: function (url, query) {
      const params = new URLSearchParams();
      Object.keys(query || {}).forEach(function (key) {
        const value = query[key];
        if (value === undefined || value === null || value === '') return;
        params.append(key, value);
      });
      const suffix = params.toString();
      return suffix ? url + (url.includes('?') ? '&' : '?') + suffix : url;
    },
    routeUrl: function (url) {
      const source = String(url || '/');
      if (!source.startsWith('/') || source.startsWith('//')) return source;

      const hashIndex = source.indexOf('#');
      const hash = hashIndex >= 0 ? source.slice(hashIndex) : '';
      const withoutHash = hashIndex >= 0 ? source.slice(0, hashIndex) : source;
      const queryIndex = withoutHash.indexOf('?');
      const route = queryIndex >= 0 ? withoutHash.slice(0, queryIndex) : withoutHash;
      const query = queryIndex >= 0 ? withoutHash.slice(queryIndex) : '';
      const normalizedBase = String(this.baseUrl || '').replace(/\/$/, '');
      if (normalizedBase && (route === normalizedBase || route.startsWith(normalizedBase + '/'))) {
        return route + query + hash;
      }
      const normalizedRoute = route === '/' ? '/' : '/' + route.replace(/^\/+|\/+$/g, '');
      return normalizedBase + normalizedRoute + query + hash;
    },
    async fetchJson(url, options) {
      const opts = options || {};
      const method = opts.method || 'GET';
      const body = opts.body !== undefined ? JSON.stringify(opts.body) : undefined;
      const silent = !!opts.silent;
      if (!silent) this.setBusy(true, opts.loadingText || '澶勭悊涓?..');
      try {
        const response = await fetch(this.routeUrl(url), {
          method: method,
          credentials: 'same-origin',
          headers: Object.assign({ 'Content-Type': 'application/json', 'Accept': 'application/json' }, opts.headers || {}),
          body: body
        });
        const rawText = await response.text();
        let payload = null;
        try {
          payload = rawText ? JSON.parse(rawText) : null;
        } catch (parseError) {
          throw new Error(rawText || '鎺ュ彛杩斿洖浜嗘棤娉曡В鏋愮殑鍝嶅簲鍐呭');
        }
        const hasCode = payload && Object.prototype.hasOwnProperty.call(payload, 'code');
        const hasSuccess = payload && Object.prototype.hasOwnProperty.call(payload, 'success');
        const ok = hasCode
          ? Number(payload.code || 0) === 200
          : hasSuccess
            ? !!payload.success
            : response.ok;
        if (!ok) {
          throw new Error(
            payload && (payload.message || payload.msg)
              ? (payload.message || payload.msg)
              : ('璇锋眰澶辫触锛圚TTP ' + response.status + '锛?)
          );
        }
        return payload && Object.prototype.hasOwnProperty.call(payload, 'data') ? payload.data : payload;
      } catch (error) {
        if (!silent) this.notify(error.message || '璇锋眰澶辫触', 'danger');
        throw error;
      } finally {
        if (!silent) this.setBusy(false);
      }
    },
    userPathForTab: function (key) {
      const slugs = { dashboard: 'home', order: 'order', orders: 'orders', exchange_codes: 'exchange-codes', recharge: 'recharge', invites: 'invites', groups: 'groups', profile: 'settings' };
      return '/user/' + (slugs[key] || 'home');
    },
    userTabFromPath: function (path) {
      const tabs = { home: 'dashboard', order: 'order', orders: 'orders', 'exchange-codes': 'exchange_codes', recharge: 'recharge', invites: 'invites', groups: 'groups', settings: 'profile' };
      const normalized = String(path || '/user').replace(/\/+$/, '');
      if (normalized === '/user') return 'dashboard';
      const slug = normalized.startsWith('/user/') ? normalized.slice('/user/'.length).split('/')[0] : '';
      return tabs[slug] || 'dashboard';
    },
    adminPathForTab: function (key) {
      const base = '/' + String(this.adminUrl || '/admin').replace(/^\/+|\/+$/g, '');
      return base + '/' + (key === 'dashboard' ? 'home' : key);
    },
    adminTabFromPath: function (path) {
      const base = '/' + String(this.adminUrl || '/admin').replace(/^\/+|\/+$/g, '');
      const normalized = String(path || base).replace(/\/+$/, '');
      if (normalized === base) return 'dashboard';
      const slug = normalized.startsWith(base + '/') ? normalized.slice(base.length + 1).split('/')[0] : '';
      const key = slug === 'home' ? 'dashboard' : slug;
      return this.adminPageKeys.includes(key) ? key : 'dashboard';
    },
    pathFromLocation: function () {
      const pathname = String(window.location.pathname || '/');
      const normalizedBase = String(this.baseUrl || '').replace(/\/$/, '');
      let path = pathname;
      if (normalizedBase && pathname === normalizedBase) {
        path = '/';
      } else if (normalizedBase && pathname.startsWith(normalizedBase + '/')) {
        path = pathname.slice(normalizedBase.length) || '/';
      }
      return path === '/' ? '/' : '/' + path.replace(/^\/+|\/+$/g, '');
    },
    async handlePopState() {
      const path = this.pathFromLocation();
      this.currentPath = path;
      if (this.routeMode === 'user') {
        this.userTab = this.userTabFromPath(path);
        await this.ensureUserTab(this.userTab, false);
      } else if (this.routeMode === 'admin') {
        this.adminTab = this.adminTabFromPath(path);
        const parent = this.adminParentKey(this.adminTab);
        if (parent) this.adminMenuOpenKeys[parent] = true;
        await this.ensureAdminTab(this.adminTab, false);
      }
    },
    setTabPath: function (key, isAdmin) {
      const path = isAdmin ? this.adminPathForTab(key) : this.userPathForTab(key);
      this.currentPath = path;
      window.history.pushState({}, '', this.routeUrl(path));
    },
    async submitLogin(admin) {
      const form = this.home.login;
      const body = { username: form.username, password: form.password, captcha: form.captcha };
      if (admin) body.admin = 1;
      await this.fetchJson('/auth/login', { method: 'POST', body: body, loadingText: '姝ｅ湪鐧诲綍...' });
      this.notify('鐧诲綍鎴愬姛', 'success');
      window.location.href = this.routeUrl(admin ? this.adminUrl : '/user');
    },
    async submitRegister() {
      await this.fetchJson('/auth/register', { method: 'POST', body: this.home.register, loadingText: '姝ｅ湪娉ㄥ唽...' });
      this.notify('娉ㄥ唽鎴愬姛', 'success');
      window.location.href = this.routeUrl('/user');
    },
    async logout() {
      await this.fetchJson('/auth/logout', { method: 'GET', loadingText: '姝ｅ湪閫€鍑?..' });
      window.location.href = this.routeUrl('/');
    },
    async bootstrapUser() {
      await this.loadUserProfile(true);
      await this.ensureUserTab(this.userTab, true);
    },
    async ensureUserTab(tab, force) {
      if (tab === 'dashboard') return this.loadUserProfile(force);
      if (tab === 'order') return this.loadUserProducts(force);
      if (tab === 'orders') return this.loadUserOrders(force);
      if (tab === 'exchange_codes') {
        await this.loadUserProducts(force);
        await this.loadUserExchangeSettings(force);
        return this.loadUserExchangeCodes(force);
      }
      if (tab === 'recharge') return this.loadUserPayments(force);
      if (tab === 'invites') return this.loadUserInvites(force);
      if (tab === 'groups') {
        await this.loadUserProfile(force);
        return this.loadUserGroups(force);
      }
      if (tab === 'profile') return this.loadUserProfile(force);
    },
    async switchUserTab(tab) {
      this.userTab = tab;
      this.setTabPath(tab, false);
      await this.ensureUserTab(tab, false);
    },
    async loadUserProfile(force) {
      const data = await this.fetchJson('/user/api/profile', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇璧勬枡...', silent: !force });
      this.profile = data;
      this.user = data.user;
      this.profileForm = { nickname: data.user.nickname || '', qq: data.user.qq || '', email: data.user.email || '', mobile: data.user.mobile || '' };
      return data;
    },
    async loadUserProducts(force) {
      const rows = await this.fetchJson('/user/api/products', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍟嗗搧...', silent: !force });
      this.userState.products = rows || [];
      if (!this.orderForm.sign && this.userState.products.length) this.selectProduct(this.userState.products[0]);
      return rows;
    },
    selectProduct: function (product) {
      this.orderForm.sign = product.upstream_sign;
      this.orderForm.num = Number(product.min_num || product.step_num || 1);
      this.orderForm.feed_id = '';
      this.orderForm.is_delayed = false;
      this.resetOrderExtras();
      this.scheduleQuote();
    },
    resetOrderExtras: function () {
      const extra = {};
      this.dynamicInputFields.forEach(function (field) {
        if (field.key !== 'feed_id') extra[field.key] = '';
      });
      this.orderForm.extra = extra;
      this.userState.feedItems = [];
      this.userState.feedModalVisible = false;
    },
    scheduleQuote: function () {
      if (this.quoteTimer) window.clearTimeout(this.quoteTimer);
      this.quoteTimer = window.setTimeout(() => { this.quoteOrder(); }, 260);
    },
    async quoteOrder() {
      if (!this.orderForm.sign || !this.orderForm.num) return;
      try {
        this.quote = await this.fetchJson('/user/api/order/price', { method: 'POST', body: { sign: this.orderForm.sign, num: this.orderForm.num, is_delayed: this.orderForm.is_delayed ? 1 : 0 }, silent: true });
      } catch (e) {
        this.quote = null;
      }
    },
    clearFeedSelection: function () {
      this.orderForm.feed_id = '';
      this.userState.feedItems = [];
      this.userState.feedModalVisible = false;
    },
    async loadFeedList() {
      if (!this.orderForm.qq) {
        this.notify('璇峰厛杈撳叆 QQ 鍙?, 'warning');
        return;
      }
      const rows = await this.fetchJson(this.withQuery('/user/api/feed', { qq: this.orderForm.qq }), { method: 'GET', loadingText: '姝ｅ湪鑾峰彇璇磋鍒楄〃...' });
      this.userState.feedItems = Array.isArray(rows) ? rows : [];
      this.userState.feedModalVisible = this.userState.feedItems.length > 0;
      if (!this.userState.feedItems.length) this.notify('鏈幏鍙栧埌鍙€夎璇村垪琛?, 'warning');
    },
    resolveFeedId: function (item) {
      return String(item.feed_id || item.id || item.fid || item.tid || '');
    },
    selectFeed: function (item) {
      this.orderForm.feed_id = this.resolveFeedId(item);
      this.userState.feedModalVisible = false;
      this.notify('宸查€夋嫨璇磋 ID锛? + (this.orderForm.feed_id || '-'), 'success');
    },
    async createOrder() {
      if (!this.selectedProduct) {
        this.notify('璇峰厛閫夋嫨鍟嗗搧', 'warning');
        return;
      }
      const body = { sign: this.orderForm.sign, qq: this.orderForm.qq, num: this.orderForm.num, feed_id: this.orderForm.feed_id, is_delayed: this.orderForm.is_delayed ? 1 : 0 };
      this.dynamicInputFields.forEach((field) => {
        if (field.key === 'feed_id') return;
        body[field.key] = this.orderForm.extra[field.key] || '';
      });
      const data = await this.fetchJson('/user/api/order/create', { method: 'POST', body: body, loadingText: '姝ｅ湪鎻愪氦璁㈠崟...' });
      this.notify('涓嬪崟鎴愬姛锛岀郴缁熻鍗曞彿锛? + (data.display_order_no || data.order_no || '-'), 'success');
      await this.loadUserProfile(true);
      await this.loadUserOrders(true);
      this.userTab = 'orders';
      this.userState.orderSearch = data.display_order_no || data.order_no || '';
      await this.showOrderDetail(this.userState.orderSearch);
      this.setTabPath('orders', false);
    },
    async loadUserOrders(force) {
      const rows = await this.fetchJson('/user/api/orders', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇璁㈠崟...', silent: !force });
      this.userState.orders = rows || [];
      return rows;
    },
    async searchOrderDetail() {
      if (!this.userState.orderSearch) {
        this.notify('璇疯緭鍏ョ郴缁熻鍗曞彿', 'warning');
        return;
      }
      await this.showOrderDetail(this.userState.orderSearch);
    },
    async showOrderDetail(orderNo) {
      const detail = await this.fetchJson(this.withQuery('/user/api/order/detail', { bid: orderNo }), { method: 'GET', loadingText: '姝ｅ湪鍚屾璁㈠崟鐘舵€?..' });
      this.userState.orderDetail = detail;
      this.userState.orderSearch = detail.display_order_no || detail.order_no || orderNo;
      await this.loadUserOrders(true);
    },
    async userRetryOrder(order) {
      if (!await this.confirmAction('纭鍙戣捣琛ュ崟鐢宠鍚楋紵', { title: '琛ュ崟纭', confirmText: '纭琛ュ崟' })) return;
      const data = await this.fetchJson('/user/api/order/retry', { method: 'POST', body: { bid: order.display_order_no || order.order_no }, loadingText: '姝ｅ湪鎻愪氦琛ュ崟鐢宠...' });
      this.userState.orderDetail = data;
      this.notify('琛ュ崟鐢宠宸叉彁浜?, 'success');
      await this.loadUserOrders(true);
    },
    async userRefundOrder(order) {
      if (!await this.confirmAction('纭鍙戣捣閫€娆剧敵璇峰悧锛?, { title: '閫€娆剧‘璁?, confirmText: '纭閫€娆? })) return;
      const data = await this.fetchJson('/user/api/order/refund', { method: 'POST', body: { bid: order.display_order_no || order.order_no }, loadingText: '姝ｅ湪鎻愪氦閫€娆剧敵璇?..' });
      this.userState.orderDetail = data;
      this.notify('閫€娆剧敵璇峰凡鎻愪氦', 'success');
      await this.loadUserOrders(true);
      await this.loadUserProfile(true);
    },
    async loadUserPayments(force) {
      const data = await this.fetchJson('/user/api/payments', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鏀粯閰嶇疆...', silent: !force });
      this.userState.payments = { channels: data.channels || [], orders: data.orders || [] };
      if (!this.rechargeForm.channel_id && this.userState.payments.channels.length) {
        this.rechargeForm.channel_id = Number(this.userState.payments.channels[0].id);
      }
      return data;
    },
    isTouchLike: function () {
      return window.matchMedia('(pointer: coarse)').matches || navigator.maxTouchPoints > 0 || window.innerWidth < 860;
    },
    async createRecharge() {
      const result = await this.fetchJson('/user/api/recharge/create', { method: 'POST', body: { channel_id: this.rechargeForm.channel_id, money: this.rechargeForm.money }, loadingText: '姝ｅ湪鍒涘缓鍏呭€艰鍗?..' });
      this.userState.paymentResult = result;
      this.notify('鍏呭€艰鍗曞垱寤烘垚鍔?, 'success');
      this.$nextTick(() => this.renderPayQr());
      if (this.isTouchLike() && this.paymentJumpLink && this.paymentJumpLink !== '#') {
        window.open(this.paymentJumpLink, '_blank', 'noopener');
      }
      await this.loadUserPayments(true);
    },
    renderPayQr: function () {
      const canvas = this.$refs.payCanvas;
      const text = this.paymentJumpLink;
      if (!canvas || !text || text === '#') return;
      const wrap = canvas.parentElement;
      const context = canvas.getContext('2d');
      if (context) context.clearRect(0, 0, canvas.width || 240, canvas.height || 240);
      if (window.QRCode && typeof window.QRCode.toCanvas === 'function') {
        window.QRCode.toCanvas(canvas, text, { width: 220, margin: 1 }, function () {});
        return;
      }
      if (typeof window.qrcode === 'function') {
        const qr = window.qrcode(0, 'M');
        qr.addData(text);
        qr.make();
        const cellSize = 4;
        const margin = 8;
        const count = qr.getModuleCount();
        const size = count * cellSize + margin * 2;
        canvas.width = size;
        canvas.height = size;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;
        const styles = getComputedStyle(document.documentElement);
        const qrBg = styles.getPropertyValue('--qr-bg').trim() || '#ffffff';
        const qrFg = styles.getPropertyValue('--mono-bg').trim() || '#111111';
        ctx.fillStyle = qrBg;
        ctx.fillRect(0, 0, size, size);
        ctx.fillStyle = qrFg;
        for (let row = 0; row < count; row++) {
          for (let col = 0; col < count; col++) {
            if (qr.isDark(row, col)) {
              ctx.fillRect(margin + col * cellSize, margin + row * cellSize, cellSize, cellSize);
            }
          }
        }
        return;
      }
      if (wrap) {
        wrap.setAttribute('title', '浜岀淮鐮佽剼鏈湭鍔犺浇锛岃浣跨敤涓嬫柟鎸夐挳鐩存帴鎵撳紑鏀粯閾炬帴');
      }
    },
    async copyPaymentLink() {
      const text = this.paymentJumpLink;
      if (!text || text === '#') return;
      await navigator.clipboard.writeText(text);
      this.notify('鏀粯閾炬帴宸插鍒?, 'success');
    },
    async redeemCard() {
      const data = await this.fetchJson('/card/redeem', { method: 'POST', body: { code: this.cardRedeemCode }, loadingText: '姝ｅ湪鍏戞崲鍗″瘑...' });
      this.notify('鍏戞崲鎴愬姛锛屽埌璐?' + this.money(data.amount || 0) + ' ' + this.currency, 'success');
      this.cardRedeemCode = '';
      await this.loadUserProfile(true);
      await this.loadUserPayments(true);
    },
    async loadUserExchangeSettings(force) {
      const data = await this.fetchJson('/user/api/exchange-code/settings', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍏戞崲鐮佽鍒?..', silent: !force });
      this.userState.exchangeSettings = data || null;
      return data;
    },
    async loadUserExchangeCodes(force) {
      const rows = await this.fetchJson('/user/api/exchange-codes', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍟嗗搧鍏戞崲鐮?..', silent: !force });
      this.userState.exchangeCodes = rows || [];
      return rows;
    },
    async createExchangeCode() {
      if (!this.exchangeCodeForm.sign) { this.notify('璇峰厛閫夋嫨鍟嗗搧', 'warning'); return; }
      const count = Math.min(1000, Math.max(1, Number(this.exchangeCodeForm.count || 1)));
      const payload = { sign: this.exchangeCodeForm.sign, quantity: Number(this.exchangeCodeForm.quantity || 0), count: count };
      const data = await this.fetchJson('/user/api/exchange-code/create', { method: 'POST', body: payload, loadingText: '姝ｅ湪鎵归噺鐢熸垚鍟嗗搧鍏戞崲鐮?..' });
      const rows = Array.isArray(data.codes) ? data.codes : [];
      this.exchangeCodeForm.generatedCodes = rows.map(function (row) { return row.code || row.display_code || ''; }).filter(Boolean);
      this.notify('宸茬敓鎴?' + this.exchangeCodeForm.generatedCodes.length + ' 涓厬鎹㈢爜', 'success');
      await this.loadUserExchangeCodes(true); await this.loadUserProfile(true);
    },
    async copyGeneratedExchangeCodes() {
      const text = this.exchangeCodeForm.generatedCodes.join('\n');
      if (!text) return;
      try { await navigator.clipboard.writeText(text); this.notify('宸插鍒跺叏閮ㄥ厬鎹㈢爜', 'success'); } catch (e) { this.notify('澶嶅埗澶辫触锛岃鎵嬪姩澶嶅埗鏂囨湰妗嗗唴瀹?, 'warning'); }
    },
    editExchangeCode: function (row, admin) {
      this.exchangeEditState = { visible: true, admin: !!admin, form: { id: Number(row.id), code: row.code || row.display_code || '', sign: row.product_sign_snapshot || '', quantity: Number(row.quantity || 0) } };
    },
    closeExchangeEdit: function () { this.exchangeEditState.visible = false; },
    async saveExchangeCode() {
      const state = this.exchangeEditState; if (!state.form.code || state.form.code.length < 48) { this.notify('鍏戞崲鐮侀暱搴︿笉鑳藉皯浜?8浣?, 'warning'); return; }
      const url = state.admin ? this.adminUrl + '/api/exchange-code/save' : '/user/api/exchange-code/save';
      await this.fetchJson(url, { method: 'POST', body: state.form, loadingText: '姝ｅ湪淇濆瓨鍏戞崲鐮?..' });
      this.notify('鍏戞崲鐮佸凡淇濆瓨', 'success'); this.closeExchangeEdit();
      if (state.admin) await this.loadAdminExchangeCodes(true); else await this.loadUserExchangeCodes(true);
    },
    async destroyExchangeCode(row, admin) {
      if (!await this.confirmAction('閿€姣佸悗璇ュ厬鎹㈢爜灏嗕笉鍙厬鎹紝涓斿凡淇濈暀瀹¤璁板綍锛岀‘璁ょ户缁悧锛?, { title: '閿€姣佸厬鎹㈢爜', confirmText: '纭閿€姣? })) return;
      const url = admin ? this.adminUrl + '/api/exchange-code/destroy' : '/user/api/exchange-code/destroy';
      await this.fetchJson(url, { method: 'POST', body: { id: Number(row.id) }, loadingText: '姝ｅ湪閿€姣佸厬鎹㈢爜...' });
      this.notify('鍏戞崲鐮佸凡閿€姣?, 'success'); if (admin) await this.loadAdminExchangeCodes(true); else await this.loadUserExchangeCodes(true);
    },
    async previewExchangeCode() {
      if (!this.exchangePublic.code) {
        this.notify('璇疯緭鍏ュ厬鎹㈢爜', 'warning');
        return;
      }
      const data = await this.fetchJson('/exchange/api/preview', { method: 'POST', body: { code: this.exchangePublic.code }, loadingText: '姝ｅ湪鏌ヨ鍏戞崲鐮?..' });
      this.exchangePublic.preview = data;
      const extra = {};
      this.exchangeInputFields.forEach(function (field) { extra[field.key] = ''; });
      this.exchangePublic.form = { qq: '', extra: extra };
      this.notify('鍏戞崲鐮佹牎楠岄€氳繃锛岃缁х画濉啓鍏戞崲淇℃伅', 'success');
      return data;
    },
    async redeemExchangeCode() {
      if (!this.exchangePublic.preview) {
        this.notify('璇峰厛鏌ヨ鍏戞崲鐮?, 'warning');
        return;
      }
      const body = { code: this.exchangePublic.code, qq: this.exchangePublic.form.qq };
      this.exchangeInputFields.forEach((field) => {
        body[field.key] = (this.exchangePublic.form.extra || {})[field.key] || '';
      });
      const order = await this.fetchJson('/exchange/api/redeem', { method: 'POST', body: body, loadingText: '姝ｅ湪鍏戞崲骞跺垱寤鸿鍗?..' });
      this.notify('鍏戞崲鎴愬姛锛岀郴缁熻鍗曞彿锛? + (order.display_order_no || order.order_no || '-'), 'success');
      this.exchangePublic.preview = null;
      this.exchangePublic.form = { qq: '', extra: {} };
      await this.loadExchangeOrders(true);
      return order;
    },
    async loadExchangeOrders(force) {
      const rows = await this.fetchJson('/exchange/api/orders', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍘嗗彶鍏戞崲璁㈠崟...', silent: !force });
      this.exchangePublic.orders = rows || [];
      return rows;
    },
    async queryExchangeOrder(orderNo) {
      const value = String(orderNo || '').trim(); if (!value) { this.notify('璇疯緭鍏ヨ鍗曞彿', 'warning'); return; }
      const row = await this.fetchJson(this.withQuery('/exchange/api/order', { order_no: value }), { method: 'GET', loadingText: '姝ｅ湪鏌ヨ璁㈠崟杩涘害...' });
      this.exchangePublic.orderSearch = value; this.exchangePublic.orderDetail = row;
      const index = this.exchangePublic.orders.findIndex(function (item) { return String(item.order_no) === value; });
      if (index >= 0) this.exchangePublic.orders.splice(index, 1, row);
      return row;
    },
    async loadUserInvites(force) {
      const data = await this.fetchJson('/user/api/invites', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇閭€璇风爜...', silent: !force });
      this.userState.invites = data || { codes: [], records: [] };
      return data;
    },
    async createInviteCode() {
      const payload = { length: this.inviteForm.length, code: this.inviteForm.code || '' };
      const row = await this.fetchJson('/user/api/invite/create', { method: 'POST', body: payload, loadingText: '姝ｅ湪鍒涘缓閭€璇风爜...' });
      this.notify('閭€璇风爜鍒涘缓鎴愬姛锛? + row.code, 'success');
      this.inviteForm = emptyInviteForm();
      await this.loadUserInvites(true);
      await this.loadUserProfile(true);
    },
    async loadUserGroups(force) {
      const rows = await this.fetchJson('/user/api/groups', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鐢ㄦ埛缁?..', silent: !force });
      this.userState.groups = rows || [];
      return rows;
    },
    async claimUserGroup() {
      const data = await this.fetchJson('/user/api/group/claim', { method: 'POST', body: {}, loadingText: '姝ｅ湪妫€娴嬪崌绾ц祫鏍?..' });
      this.profile = data;
      this.user = data.user;
      this.notify('宸插埛鏂扮敤鎴风粍涓庝唬鐞嗙姸鎬?, 'success');
      await this.loadUserGroups(true);
    },
    async saveProfile() {
      const data = await this.fetchJson('/user/api/profile/save', { method: 'POST', body: this.profileForm, loadingText: '姝ｅ湪淇濆瓨璧勬枡...' });
      this.profile = data;
      this.user = data.user;
      this.notify('璧勬枡宸蹭繚瀛?, 'success');
    },
    async resetOwnApiKey() {
      if (!(this.profile.api_access && this.profile.api_access.can_generate_key)) {
        this.notify(this.apiAccessHint(this.profile.api_access), 'warning');
        return;
      }
      const ok = await this.confirmAction(this.profile.user && this.profile.user.api_key ? '纭閲嶇疆褰撳墠 API Key 鍚楋紵閲嶇疆鍚庢棫瀵嗛挜灏嗙珛鍗冲け鏁堛€? : '纭鐢熸垚 API Key 鍚楋紵', { title: 'API Key 鎿嶄綔纭', confirmText: this.profile.user && this.profile.user.api_key ? '纭閲嶇疆' : '纭鐢熸垚' });
      if (!ok) return;
      const data = await this.fetchJson('/user/api/api-key/reset', { method: 'POST', body: {}, loadingText: '姝ｅ湪澶勭悊 API Key...' });
      if (!this.profile.user) this.profile.user = {};
      this.profile.user.api_key = data.api_key || '';
      if (this.user) this.user.api_key = data.api_key || '';
      this.notify((this.profile.user.api_key ? '鏈€鏂?API Key锛? + this.profile.user.api_key : 'API Key 宸叉洿鏂?), 'success');
      await this.loadUserProfile(true);
    },
    async changePassword() {
      await this.fetchJson('/user/api/profile/password', { method: 'POST', body: this.passwordForm, loadingText: '姝ｅ湪淇敼瀵嗙爜...' });
      this.passwordForm = emptyPasswordForm();
      this.notify('瀵嗙爜淇敼鎴愬姛', 'success');
    },
    async bootstrapAdmin() {
      await Promise.all([this.loadAdminSettings(true), this.loadAdminGroups(true), this.checkVersion(false)]);
      await this.ensureAdminTab(this.adminTab, true);
    },
    adminParentKey: function (pageKey) {
      for (const item of this.adminNav) {
        if (item.key === pageKey && !item.children) return item.key;
        if ((item.children || []).some(function (child) { return child.key === pageKey; })) return item.key;
      }
      return 'dashboard';
    },
    toggleAdminMenu: function (key) {
      this.adminMenuOpenKeys[key] = !this.adminMenuOpenKeys[key];
    },
    setAdminSidebarCollapsed: function (flag) {
      this.adminSidebarCollapsed = typeof flag === 'boolean' ? flag : !this.adminSidebarCollapsed;
    },
    async ensureAdminTab(tab, force) {
      const loaders = {
        dashboard: () => this.loadAdminDashboard(force),
        'products-sync': () => this.loadAdminProducts(force),
        'products-list': () => this.loadAdminProducts(force),
        'groups-list': async () => { await Promise.all([this.loadAdminGroups(force), this.loadAdminProducts(force)]); },
        'groups-default': () => this.loadAdminGroups(force),
        'users-list': () => this.loadAdminUsers(force),
        'users-create': async () => { await this.loadAdminUsers(force); await this.loadAdminGroups(force); },
        'orders-list': () => this.loadAdminOrders(force),
        'recharge-orders': () => this.loadAdminRecharge(force),
        'api-conditions': () => this.loadAdminSettings(force),
        'upstream-manage': () => this.loadAdminUpstream(force),
        'api-keys': () => this.loadAdminUsers(force, true),
        'cards-generate': () => this.loadAdminCards(force),
        'cards-list': () => this.loadAdminCards(force),
        'payments-merchants': () => this.loadAdminRecharge(force),
        'payments-channels': () => this.loadAdminRecharge(force),
        'exchange-rules': () => this.loadAdminSettings(force),
        'exchange-list': async () => { await this.loadAdminProducts(force); await this.loadAdminExchangeCodes(force); },
        'exchange-logs': () => this.loadAdminExchangeLogs(force),
        'settings-basic': () => this.loadAdminSettings(force),
        'settings-theme': () => this.loadAdminSettings(force),
        'settings-sms': () => this.loadAdminSettings(force),
        'settings-security': () => this.loadAdminSettings(force),
        'settings-custom': () => this.loadAdminSettings(force),
        'settings-version': () => this.checkVersion(force),
        'scheduled-tasks': () => this.loadScheduledTaskConfig(force),
        'logs-list': () => this.loadAdminLogs(force)
      };
      const loader = loaders[tab] || loaders.dashboard;
      return loader();
    },
    async switchAdminTab(tab) {
      this.adminTab = this.adminPageKeys.includes(tab) ? tab : 'dashboard';
      const parent = this.adminParentKey(this.adminTab);
      if (parent) this.adminMenuOpenKeys[parent] = true;
      this.setTabPath(this.adminTab, true);
      await this.ensureAdminTab(this.adminTab, false);
    },
    async loadAdminDashboard(force) {
      const data = await this.fetchJson(this.adminUrl + '/api/dashboard', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇缁熻...', silent: !force });
      this.adminState.dashboard = data;
      return data;
    },
    rankText: function (list, index, field) {
      const row = (list || [])[index];
      if (!row) return '-';
      return (row.nickname || row.username || '-') + ' / ' + this.money(row[field] || 0) + '锛? + this.yuanApprox(row[field] || 0) + '锛?;
    },
    async loadAdminProducts(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/products', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍟嗗搧...', silent: !force });
      this.adminState.products = (rows || []).map(normalizeAdminProduct);
      return rows;
    },
    async syncProducts() {
      const data = await this.fetchJson(this.adminUrl + '/api/products/sync', { method: 'POST', body: {}, loadingText: '姝ｅ湪鍚屾鍟嗗搧...' });
      this.notify('宸插悓姝?' + (data.count || 0) + ' 涓晢鍝?, 'success');
      await this.loadAdminProducts(true);
    },
    addProductDiscount: function (product) {
      product.discounts.push({ min_quantity: Number(product.min_num || 1), discount_rate: 1 });
    },
    removeProductDiscount: function (product, index) {
      product.discounts.splice(index, 1);
    },
    async saveProduct(product) {
      const payload = {
        id: product.id,
        allow_frontend: product.allow_frontend_bool ? 1 : 0,
        allow_api: product.allow_api_bool ? 1 : 0,
        enabled: product.enabled_bool ? 1 : 0,
        sort_order: Number(product.sort_order || 0),
        discounts: product.discounts
      };
      await this.fetchJson(this.adminUrl + '/api/products/save', { method: 'POST', body: payload, loadingText: '姝ｅ湪淇濆瓨鍟嗗搧...' });
      this.notify('鍟嗗搧璁剧疆宸蹭繚瀛?, 'success');
      await this.loadAdminProducts(true);
    },
    async loadAdminGroups(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/groups', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鐢ㄦ埛缁?..', silent: !force });
      this.adminState.groups = rows || [];
      if (!this.groupForm.user_group_id && this.adminState.groups.length && !this.userForm.user_group_id) {
        this.userForm.user_group_id = Number(this.adminState.groups[0].id);
      }
      return rows;
    },
    editGroup: function (row) {
      this.groupForm = Object.assign(emptyGroupForm(), clone(row), { product_prices: Object.assign({}, row.product_prices || {}) });
    },
    resetGroupForm: function () {
      this.groupForm = emptyGroupForm();
    },
    async saveGroup() {
      await this.fetchJson(this.adminUrl + '/api/groups/save', { method: 'POST', body: this.groupForm, loadingText: '姝ｅ湪淇濆瓨鐢ㄦ埛缁?..' });
      this.notify('鐢ㄦ埛缁勫凡淇濆瓨', 'success');
      this.resetGroupForm();
      await this.loadAdminGroups(true);
    },
    async setDefaultGroup(group) {
      await this.fetchJson(this.adminUrl + '/api/groups/default', { method: 'POST', body: { id: group.id }, loadingText: '姝ｅ湪璁剧疆榛樿鐢ㄦ埛缁?..' });
      this.notify('榛樿娉ㄥ唽鐢ㄦ埛缁勫凡鏇存柊', 'success');
      await this.loadAdminGroups(true);
    },
    async loadAdminUsers(force, apiKeyOnly) {
      const keyOnly = apiKeyOnly === true || this.adminTab === 'api-keys';
      const rows = await this.fetchJson(this.adminUrl + '/api/users', { method: 'POST', body: { keyword: keyOnly ? '' : (this.adminState.userKeyword || ''), api_key_only: keyOnly ? 1 : 0 }, loadingText: '姝ｅ湪鍔犺浇鐢ㄦ埛...', silent: !force });
      this.adminState.users = rows || [];
      if (!this.userForm.user_group_id && this.adminState.groups.length) this.userForm.user_group_id = Number(this.adminState.groups[0].id);
      return rows;
    },
    editUser: function (row) {
      this.userForm = Object.assign(emptyUserForm(), clone(row), { password: '', user_group_id: Number(row.user_group_id || 0), connect_policy: this.connectPolicyOf(row) });
      this.switchAdminTab('users-create');
    },
    resetUserForm: function () {
      const base = emptyUserForm();
      if (this.adminState.groups.length) base.user_group_id = Number(this.adminState.groups[0].id);
      this.userForm = base;
    },
    connectPolicyOf: function (row) {
      if (String(row.connect_policy || '') !== '') return String(row.connect_policy);
      if (boolish(row.strategy_agent)) return 'agent';
      if (boolish(row.strategy_user)) return 'user';
      return 'default';
    },
    connectPolicyLabel: function (value) {
      return ({ default: '璺熼殢鐢ㄦ埛缁?, user: '绂佹瀵规帴', agent: '鍏佽瀵规帴' })[String(value || 'default')] || '璺熼殢鐢ㄦ埛缁?;
    },
    apiConditionText: function (apiAccess) {
      if (!apiAccess) return '-';
      return this.thresholdModeLabel(apiAccess.condition_mode) + ' ' + apiAccess.condition_operator + ' ' + this.money(apiAccess.condition_value) + '锛堝綋鍓?' + this.money(apiAccess.condition_current) + '锛?;
    },
    thresholdModeLabel: function (mode) {
      return ({ none: '鏃犻棬妲?, total_recharge: '绱鍏呭€?, total_consume: '绱娑堣垂', invite_count: '閭€璇风敤鎴锋暟', balance: '浣欓' })[String(mode || 'none')] || mode;
    },
    markupLabel: function (mode, value) {
      return mode === 'percent' ? ('鐧惧垎姣斿姞浠?' + value + '%') : ('鍥哄畾鍔犱环 ' + value + ' ' + this.currency);
    },
    async saveAdminUser() {
      const payload = clone(this.userForm);
      await this.fetchJson(this.adminUrl + '/api/users/save', { method: 'POST', body: payload, loadingText: '姝ｅ湪淇濆瓨鐢ㄦ埛...' });
      this.notify('鐢ㄦ埛淇℃伅宸蹭繚瀛?, 'success');
      this.resetUserForm();
      await this.loadAdminUsers(true);
      await this.loadAdminGroups(true);
    },
    async resetUserApiKey(row) {
      const data = await this.fetchJson(this.adminUrl + '/api/users/reset-key', { method: 'POST', body: { id: row.id }, loadingText: '姝ｅ湪閲嶇疆 API Key...' });
      this.notify('鏂扮殑 API Key锛? + (data.api_key || ''), 'success');
      await this.loadAdminUsers(true);
    },
    async softDeleteUser(row) {
      if (!await this.confirmAction('纭鍒犻櫎璇ョ敤鎴峰悧锛熻鎿嶄綔浼氭ā鎷熺湡瀹炲垹闄わ紝浣嗘暟鎹粛淇濈暀銆?, { title: '鍒犻櫎鐢ㄦ埛纭', confirmText: '纭鍒犻櫎' })) return;
      await this.fetchJson(this.adminUrl + '/api/users/delete', { method: 'POST', body: { id: row.id }, loadingText: '姝ｅ湪鍒犻櫎鐢ㄦ埛...' });
      this.notify('鐢ㄦ埛宸插垹闄わ紙杞垹闄わ級', 'success');
      await this.loadAdminUsers(true);
    },
    async loadAdminOrders(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/orders', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇璁㈠崟...', silent: !force });
      this.adminState.orders = rows || [];
      return rows;
    },
    async searchAdminOrder(row) {
      const rowOrderNo = row ? (row.display_order_no || row.order_no || row.upstream_order_no || '') : '';
      const bid = String(rowOrderNo || this.adminState.orderSearch || '').trim();
      if (!bid) {
        this.notify('璇疯緭鍏ョ郴缁熻鍗曞彿鎴栦笂娓歌鍗曞彿', 'warning');
        return;
      }
      const detail = await this.fetchJson(this.withQuery(this.adminUrl + '/api/orders/detail', { bid: bid }), { method: 'GET', loadingText: '姝ｅ湪鏌ヨ骞跺悓姝ヨ鍗?..' });
      this.adminState.orderSearch = detail.display_order_no || detail.order_no || bid;
      this.adminState.orderDetail = Object.assign({}, row || {}, detail || {});
      await this.loadAdminOrders(false);
    },
    showAdminOrderDetail: function (row) {
      return this.searchAdminOrder(row);
    },
    clearAdminOrderDetail: function () {
      this.adminState.orderDetail = null;
      this.adminState.orderSearch = '';
    },
    async syncAdminOrders() {
      const data = await this.fetchJson(this.adminUrl + '/api/orders/sync', { method: 'POST', body: {}, loadingText: '姝ｅ湪鍚屾璁㈠崟鐘舵€?..' });
      this.notify('宸插悓姝?' + (data.count || 0) + ' 涓繘琛屼腑璁㈠崟', 'success');
      await this.loadAdminOrders(true);
    },
    async adminRetryOrder(row) {
      if (!await this.confirmAction('纭鍚戜笂娓稿彂璧疯ˉ鍗曞悧锛?, { title: '鍚庡彴琛ュ崟纭', confirmText: '纭琛ュ崟' })) return;
      await this.fetchJson(this.adminUrl + '/api/orders/retry', { method: 'POST', body: { id: row.id }, loadingText: '姝ｅ湪鍚戜笂娓告彁浜よˉ鍗?..' });
      this.notify('琛ュ崟鐢宠宸叉彁浜?, 'success');
      await this.loadAdminOrders(true);
    },
    async adminRefundOrder(row, manualOnly) {
      const text = manualOnly ? '纭缁欑敤鎴锋墽琛屼粎閫€娆惧悧锛熸鎿嶄綔鍙細缁欑敤鎴烽€€娆撅紝涓婃柟璐ф簮涓嶄細缁欎綘閫€娆俱€? : '纭鍚戜笂娓哥敵璇烽€€鍗曞悧锛?;
      if (!await this.confirmAction(text, { title: manualOnly ? '浠呴€€娆剧‘璁? : '閫€鍗曠‘璁?, confirmText: manualOnly ? '纭浠呴€€娆? : '纭閫€鍗? })) return;
      await this.fetchJson(this.adminUrl + (manualOnly ? '/api/orders/manual-refund' : '/api/orders/refund'), { method: 'POST', body: { id: row.id }, loadingText: '姝ｅ湪澶勭悊閫€娆?..' });
      this.notify(manualOnly ? '浠呴€€娆惧凡瀹屾垚' : '閫€鍗曠敵璇峰凡鎻愪氦', 'success');
      await this.loadAdminOrders(true);
    },
    parseUpstreamBalance: function (result) {
      const data = result && typeof result === 'object' ? result.data : null;
      const candidates = [
        data && typeof data === 'object' ? data.amount : null,
        data && typeof data === 'object' ? data.balance : null,
        data && typeof data === 'object' ? data.money : null,
        (typeof data === 'number' || (typeof data === 'string' && data.trim() !== '')) ? data : null,
        result && typeof result === 'object' ? result.amount : null,
        result && typeof result === 'object' ? result.balance : null
      ];
      for (const value of candidates) {
        if (value !== null && value !== undefined && value !== '' && Number.isFinite(Number(value))) return Number(value);
      }
      return null;
    },
    async refreshUpstreamBalance(silent) {
      try {
        const result = await this.fetchJson(this.adminUrl + '/api/upstream/balance', { method: 'GET', loadingText: '姝ｅ湪鑾峰彇涓婃父浣欓...', silent: !!silent });
        const balance = this.parseUpstreamBalance(result);
        if (balance === null) throw new Error('涓婃父杩斿洖鎴愬姛锛屼絾鍝嶅簲涓病鏈夊彲璇嗗埆鐨勪綑棰濆瓧娈?);
        this.adminState.upstreamBalance = balance;
        this.adminState.upstreamBalanceError = '';
        if (!silent) this.notify('涓婃父浣欓宸插埛鏂?, 'success');
        return balance;
      } catch (error) {
        this.adminState.upstreamBalance = null;
        this.adminState.upstreamBalanceError = '鏃犳硶鑾峰彇涓婃父浣欓锛? + (error && error.message ? error.message : '鏈煡閿欒');
        return null;
      }
    },
    async loadAdminUpstream(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/upstream', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇涓婃父閰嶇疆...', silent: !force });
      this.adminState.upstream = (rows || []).map(function (row) {
        row.enabled = Number(row.enabled || 0);
        row.is_default = Number(row.is_default || 0);
        return row;
      });
      await this.refreshUpstreamBalance(true);
      return rows;
    },
    editUpstream: function (row) {
      this.upstreamForm = Object.assign(emptyUpstreamForm(), clone(row), { upstream_api_key: '' });
    },
    resetUpstreamForm: function () { this.upstreamForm = emptyUpstreamForm(); },
    async saveUpstream() {
      await this.fetchJson(this.adminUrl + '/api/upstream/save', { method: 'POST', body: this.upstreamForm, loadingText: '姝ｅ湪鏍￠獙骞朵繚瀛樹笂娓?..' });
      this.notify('涓婃父閰嶇疆淇濆瓨鎴愬姛', 'success');
      this.resetUpstreamForm();
      await this.loadAdminUpstream(true);
    },
    async loadAdminRecharge(force) {
      const data = await this.fetchJson(this.adminUrl + '/api/payments', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍏呭€奸厤缃?..', silent: !force });
      this.adminState.payments = data || { merchants: [], channels: [], recharge_orders: [] };
      if (!this.channelForm.merchant_id && this.adminState.payments.merchants.length) this.channelForm.merchant_id = Number(this.adminState.payments.merchants[0].id);
      return data;
    },
    async generateCards() {
      await this.fetchJson(this.adminUrl + '/api/cards/generate', { method: 'POST', body: this.cardGenForm, loadingText: '姝ｅ湪鐢熸垚鍗″瘑...' });
      this.notify('鍗″瘑宸茬敓鎴?, 'success');
      await this.loadAdminCards(true);
    },
    async loadAdminCards(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/cards', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍗″瘑...', silent: !force });
      this.adminState.cards = rows || [];
      return rows;
    },
    editCardInline: function (row) {
      this.cardEditForm = Object.assign(emptyCardEditForm(), clone(row));
    },
    resetCardEditForm: function () {
      this.cardEditForm = emptyCardEditForm();
    },
    async saveCard() {
      await this.fetchJson(this.adminUrl + '/api/cards/save', { method: 'POST', body: this.cardEditForm, loadingText: '姝ｅ湪淇濆瓨鍗″瘑...' });
      this.notify('鍗″瘑宸蹭繚瀛?, 'success');
      this.resetCardEditForm();
      await this.loadAdminCards(true);
    },
    async destroyCard(row) {
      if (!await this.confirmAction('纭閿€姣佽鍗″瘑鍚楋紵', { title: '閿€姣佸崱瀵嗙‘璁?, confirmText: '纭閿€姣? })) return;
      await this.fetchJson(this.adminUrl + '/api/cards/destroy', { method: 'POST', body: { id: row.id }, loadingText: '姝ｅ湪閿€姣佸崱瀵?..' });
      this.notify('鍗″瘑宸查攢姣?, 'success');
      await this.loadAdminCards(true);
    },
    editMerchant: function (row) {
      this.merchantForm = Object.assign(emptyMerchantForm(), clone(row), { merchant_key: '' });
    },
    resetMerchantForm: function () { this.merchantForm = emptyMerchantForm(); },
    async saveMerchant() {
      await this.fetchJson(this.adminUrl + '/api/payments/merchant', { method: 'POST', body: this.merchantForm, loadingText: '姝ｅ湪淇濆瓨鏄撴敮浠樺晢鎴?..' });
      this.notify('鏄撴敮浠樺晢鎴峰凡淇濆瓨', 'success');
      this.resetMerchantForm();
      await this.loadAdminRecharge(true);
    },
    editChannel: function (row) {
      this.channelForm = Object.assign(emptyChannelForm(), clone(row), { merchant_id: Number(row.merchant_id || 0) });
    },
    resetChannelForm: function () {
      const base = emptyChannelForm();
      if (this.adminState.payments.merchants.length) base.merchant_id = Number(this.adminState.payments.merchants[0].id);
      this.channelForm = base;
    },
    async saveChannel() {
      await this.fetchJson(this.adminUrl + '/api/payments/channel', { method: 'POST', body: this.channelForm, loadingText: '姝ｅ湪淇濆瓨鏀粯閫氶亾...' });
      this.notify('鏀粯閫氶亾宸蹭繚瀛?, 'success');
      this.resetChannelForm();
      await this.loadAdminRecharge(true);
    },
    async loadScheduledTaskConfig(force) {
      const data = await this.fetchJson(this.adminUrl + '/api/scheduled-tasks/key', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇瀹氭椂浠诲姟閰嶇疆...', silent: !force });
      this.adminState.scheduledTasks = Object.assign({ system_key: '', products_endpoint: '', orders_endpoint: '' }, data || {});
      return data;
    },
    scheduledTaskUrl: function (endpoint) {
      const path = String(endpoint || '');
      if (!path) return '';
      const absolute = new URL(path, window.location.origin).toString();
      const separator = absolute.includes('?') ? '&' : '?';
      return absolute + separator + 'system_key=' + encodeURIComponent(String(this.adminState.scheduledTasks.system_key || ''));
    },
    async copyScheduledTaskValue(value, label) {
      const text = String(value || '');
      if (!text) {
        this.notify('鏆傛棤鍙鍒跺唴瀹?, 'warning');
        return;
      }
      try {
        await navigator.clipboard.writeText(text);
      } catch (error) {
        const input = document.createElement('textarea');
        input.value = text;
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
      }
      this.notify((label || '鍐呭') + '宸插鍒?, 'success');
    },
    async resetScheduledTaskKey() {
      if (!await this.confirmAction('閲嶇疆鍚庢棫绯荤粺瀵嗛挜浼氱珛鍗冲け鏁堬紝宸查厤缃殑鍟嗗搧鍜岃鍗曞畾鏃朵换鍔″皢鏃犳硶缁х画璋冪敤锛岀‘璁ら噸缃悧锛?, { title: '閲嶇疆绯荤粺瀵嗛挜', confirmText: '纭閲嶇疆' })) return;
      const data = await this.fetchJson(this.adminUrl + '/api/scheduled-tasks/key/reset', { method: 'POST', body: {}, loadingText: '姝ｅ湪閲嶇疆绯荤粺瀵嗛挜...' });
      this.adminState.scheduledTasks = Object.assign({ system_key: '', products_endpoint: '', orders_endpoint: '' }, data || {});
      this.notify('绯荤粺瀵嗛挜宸查噸缃紝璇风珛鍗虫洿鏂版墍鏈夊畾鏃朵换鍔?, 'success');
    },
    async checkVersion(force) {
      if (!force && this.adminState.version.checked_at) return this.adminState.version;
      try {
        const data = await this.fetchJson(this.adminUrl + '/api/version/check', { method: 'GET', loadingText: '姝ｅ湪妫€娴嬫柊鐗堟湰...', silent: !force });
        this.adminState.version = Object.assign({ current: this.currentVersion, remote: null, has_update: false, git_available: false, can_update: false, checked_at: '', message: '', updating: false }, data || {});
        return data;
      } catch (error) {
        this.adminState.version = Object.assign({}, this.adminState.version, { current: this.currentVersion, checked_at: new Date().toISOString(), message: error.message || '鐗堟湰妫€娴嬪け璐? });
        return null;
      }
    },
    async updateVersion() {
      if (!await this.confirmAction('纭畾瑕佹洿鏂板埌鏈€鏂扮増鏈悧锛熷缓璁厛澶囦唤鏁版嵁搴撳拰閰嶇疆銆?)) return;
      this.adminState.version.updating = true;
      try {
        const data = await this.fetchJson(this.adminUrl + '/api/version/update', { method: 'POST', loadingText: '姝ｅ湪鎵ц鏇存柊...' });
        if (data && data.updated) {
          this.notify('鏇存柊鎴愬姛锛侀〉闈㈠皢鍒锋柊浠ュ姞杞芥柊鐗堟湰銆?, 'success');
          setTimeout(() => location.reload(), 2000);
        } else {
          this.notify(data && data.message || '褰撳墠宸叉槸鏈€鏂扮増鏈€?, 'info');
          this.adminState.version.updating = false;
        }
      } catch (error) {
        this.notify(error.message || '鏇存柊澶辫触锛岃鏌ョ湅绯荤粺鏃ュ織銆?, 'error');
        this.adminState.version.updating = false;
      }
    },
    async reloadCurrentSettingsPage() {
      if (this.adminTab === 'scheduled-tasks') return this.loadScheduledTaskConfig(true);
      if (this.adminTab === 'settings-version') return this.checkVersion(true);
      return this.loadAdminSettings(true);
    },
    addCustomResource() {
      if (this.settingsForm.custom_resource_urls.length >= 20) return this.notify('澶栭儴璧勬簮閾炬帴鏈€澶氬厑璁?20 鏉?, 'warning');
      this.settingsForm.custom_resource_urls.push({ type: 'css', url: '' });
    },
    removeCustomResource(index) { this.settingsForm.custom_resource_urls.splice(index, 1); },
    async loadAdminSettings(force) {
      const raw = await this.fetchJson(this.adminUrl + '/api/settings', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇绯荤粺璁剧疆...', silent: !force });
      this.adminState.settingsRaw = raw || {};
      this.settingsForm = settingsToForm(raw || {});
      this.settingsForm.login_need_image_captcha = 1;
      this.applyTheme(this.settingsForm.theme_config || (this.settings && this.settings.theme_config) || null);
      this.apiSettings = {
        api_condition_mode: raw.api_condition_mode || 'total_recharge',
        api_condition_operator: raw.api_condition_operator || '>=',
        api_condition_value: raw.api_condition_value || '0'
      };
      return raw;
    },
    async saveApiCondition() {
      await this.fetchJson(this.adminUrl + '/api/settings/save', { method: 'POST', body: this.apiSettings, loadingText: '姝ｅ湪淇濆瓨瀵规帴鏉′欢...' });
      this.notify('瀵规帴鏉′欢宸蹭繚瀛?, 'success');
      await this.loadAdminSettings(true);
    },
    addPair: function (rows) { rows.push({ key: '', value: '' }); },
    removePair: function (rows, index) { rows.splice(index, 1); },
    addInviteRule: function () { this.settingsForm.invite_code_price_rules.length_rules.push({ length: '6', price: 0 }); },
    removeInviteRule: function (index) { this.settingsForm.invite_code_price_rules.length_rules.splice(index, 1); },
    async saveSettings() {
      const payload = formToSettingsPayload(this.settingsForm);
      await this.fetchJson(this.adminUrl + '/api/settings/save', { method: 'POST', body: payload, loadingText: '姝ｅ湪淇濆瓨绯荤粺璁剧疆...' });
      this.notify('绯荤粺璁剧疆宸蹭繚瀛?, 'success');
      await this.loadAdminSettings(true);
    },
    async loadAdminExchangeCodes(force) {
      const rows = await this.fetchJson(this.withQuery(this.adminUrl + '/api/exchange-codes', this.adminState.exchange.filters), { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍏戞崲鐮佸垪琛?..', silent: !force });
      this.adminState.exchange.codes = rows || [];
      return rows;
    },
    async loadAdminExchangeLogs(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/exchange-codes/logs', { method: 'GET', loadingText: '姝ｅ湪鍔犺浇鍏戞崲鐮佹棩蹇?..', silent: !force });
      this.adminState.exchange.logs = rows || [];
      return rows;
    },
    async loadAdminLogs(force) {
      const rows = await this.fetchJson(this.withQuery(this.adminUrl + '/api/logs', { level: this.adminState.logLevel, channel: this.adminState.logChannel }), { method: 'GET', loadingText: '姝ｅ湪鍔犺浇绯荤粺鏃ュ織...', silent: !force });
      this.adminState.logs = rows || [];
      return rows;
    }
  }
});
app.mount('#app');
</script>
__CUSTOM_SCRIPT__
</body>
</html>
