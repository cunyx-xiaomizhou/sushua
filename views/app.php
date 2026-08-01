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
          <a v-if="routeMode !== 'user'" class="btn ghost" :href="routeUrl('/user')">用户后台</a>
          <a v-if="canAccessAdmin && routeMode !== 'admin'" class="btn ghost" :href="routeUrl(adminUrl)">管理后台</a>
          <button class="btn" @click="logout">退出登录</button>
        </template>
        <template v-else>
          <a v-if="routeMode !== 'home'" class="btn ghost" :href="routeUrl('/')">返回首页</a>
          <a v-if="routeMode !== 'login'" class="btn ghost" :href="routeUrl('/login')">登录</a>
          <a v-if="routeMode !== 'register'" class="btn primary" :href="routeUrl('/register')">注册</a>
        </template>
      </div>
    </div>
  </header>

  <main class="shell">
﻿<section v-if="routeMode === 'home'">

<!-- Template: default -->
<template v-if="(settings.home_template || 'default') === 'default'">
<div class="hero">
  <div class="hero-card primary">
    <h1>{{ site.name }}</h1>
    <p>专注于稳定下单、实时查单、代理售卖与额度充值体验。无论你是自己使用，还是准备搭建售卖渠道，这里都能直接开工。</p>
    <div class="home-actions">
      <a v-if="!user" class="btn primary" :href="routeUrl('/login')">立即登录</a>
      <a v-if="!user" class="btn ghost" :href="routeUrl('/register')">注册账号</a>
      <a v-if="user" class="btn primary" :href="routeUrl('/user')">进入用户后台</a>
      <a v-if="canAccessAdmin" class="btn ghost" :href="routeUrl(adminUrl)">进入管理后台</a>
      <a class="btn ghost" href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener">接口文档</a>
    </div>
  </div>
  <div class="hero-card side">
    <div class="hero-summary">
      <div class="hero-side-item"><small class="muted">商品总数</small><strong>{{ homeStats.product_count || 0 }}</strong></div>
      <div class="hero-side-item"><small class="muted">订单总数</small><strong>{{ homeStats.order_count || 0 }}</strong></div>
      <div class="hero-side-item"><small class="muted">总下单数</small><strong>{{ money(homeStats.total_quantity || 0) }}</strong></div>
    </div>
  </div>
</div>

<div class="grid-2 section-gap">
  <div class="panel landing-intro">
    <div class="page-head" style="margin-bottom:0"><div><h2>系统介绍</h2><p>面向用户的速刷服务面板，支持在线下单、额度充值、邀请推广、代理升级与接口接入。</p></div></div>
    <div class="landing-quick-links">
      <a class="home-link-card" :href="routeUrl('/user')"><h4>下单控制台</h4><p>登录后即可进入用户后台，进行在线下单、查单、充值与邀请码管理。</p></a>
      <a class="home-link-card" :href="routeUrl('/login')"><h4>统一登录</h4><p>普通用户、管理员、站长统一使用同一登录页，登录时强制校验图片验证码。</p></a>
      <a class="home-link-card" :href="routeUrl('/register')"><h4>快速注册</h4><p>注册默认收集用户名、昵称、QQ 与密码，可按后台策略扩展邮箱或手机号。</p></a>
      <a class="home-link-card" href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener"><h4>接口文档</h4><p>对接前请先阅读接口文档，确认所需参数、返回格式与业务规则。</p></a>
    </div>
  </div>
  <div class="panel">
    <div class="page-head" style="margin-bottom:0"><div><h2>{{ canShowSupportGroup ? '交流群与售后群' : '用户交流群' }}</h2><p>群号可在后台实时配置。若你在 QQ 内打开，将优先尝试调起 QQ 群名片。</p></div></div>
    <div class="landing-metrics section-gap">
      <div class="landing-group-card"><small class="muted">用户交流群</small><div class="landing-group-code">{{ settings.community_group_qq || '未配置' }}</div><div class="landing-group-actions"><button class="btn primary" @click="openGroup('community')">加入交流群</button></div></div>
      <div v-if="canShowSupportGroup" class="landing-group-card"><small class="muted">售后 / 支持群</small><div class="landing-group-code">{{ settings.support_group_qq || '未配置' }}</div><div class="landing-group-actions"><button class="btn primary" @click="openGroup('support')">加入售后群</button></div></div>
    </div>
    <div class="auth-footnote">如果当前设备无法直接拉起 QQ，可复制群号到 QQ 内搜索加入。</div>
  </div>
</div>

<div class="record-links section-gap" v-if="settings.icp_beian_no || settings.public_security_beian_no">
  <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
  <div v-if="settings.public_security_beian_no" class="muted">网安备案：{{ settings.public_security_beian_no }}</div>
</div>

<div class="stats-grid section-gap">
  <div class="stat"><small>商品总数</small><strong>{{ homeStats.product_count || 0 }}</strong></div>
  <div class="stat"><small>订单总数</small><strong>{{ homeStats.order_count || 0 }}</strong></div>
  <div class="stat"><small>总下单数</small><strong>{{ money(homeStats.total_quantity || 0) }}</strong></div>
  <div class="stat"><small>接口对接</small><strong>{{ boolText(settings.api_order_enabled) }}</strong></div>
</div>

<div class="panel section-gap">
  <div class="page-head"><div><h2>商品订单数据</h2><p>仅展示已有订单记录的商品，帮助你快速判断当前热门商品和处理效率。</p></div></div>
  <div v-if="homeStats.items && homeStats.items.length" class="table-wrap home-metric-table">
    <table class="table"><thead><tr><th>商品名称</th><th>订单总数</th><th>下单总数</th><th>平均处理速度（每小时）</th></tr></thead>
    <tbody><tr v-for="item in homeStats.items" :key="item.id"><td>{{ item.name }}</td><td>{{ item.order_count }}</td><td>{{ money(item.total_quantity) }}</td><td>{{ item.avg_speed_per_hour === null ? '-' : item.avg_speed_per_hour }}</td></tr></tbody></table>
  </div>
  <div v-else class="empty">当前还没有产生订单的商品数据。</div>
</div>

<div class="panel section-gap">
  <div class="page-head"><div><h2>为什么选择我们的服务</h2><p>下面这六点，就是这个系统最直观、最容易被感受到的体验优势。</p></div></div>
  <div class="feature-grid">
    <div class="feature-card"><h3>秒速到账</h3><p>下单后立即进入队列，无需漫长等待，下单秒刷。</p></div>
    <div class="feature-card"><h3>安全稳定</h3><p>所有数据均来源于真实用户的凭证，非机器刷量，可霸占人气排行榜。</p></div>
    <div class="feature-card"><h3>便宜实惠</h3><p>当前价格远远低于全网同行，给你最稳定最舒心的体验。</p></div>
    <div class="feature-card"><h3>社群支持</h3><p>相应社群 7×24 小时全天候开放，随时解决你的问题和需求。</p></div>
    <div class="feature-card"><h3>卡密对接</h3><p>我们开放卡密下单功能，让你可以在各类发卡平台上进行售卖商品，且支持用户自助兑换下单。</p></div>
    <div class="feature-card"><h3>API对接</h3><p>我们开放 API 下单接口，让你对接你自己的服务进行售卖赚钱。</p></div>
  </div>
</div>
</template>

<!-- Template: modern -->
<template v-if="settings.home_template === 'modern'">
<div class="tpl-modern">
  <div class="modern-hero">
    <div class="modern-hero-content">
      <div class="modern-badge">🚀 专业速刷平台</div>
      <h1 class="modern-title">{{ site.name }}</h1>
      <p class="modern-desc">高效稳定的在线下单平台，支持多种商品类型，实时查单，秒速到账。</p>
      <div class="modern-actions">
        <a v-if="!user" class="btn modern-btn-primary" :href="routeUrl('/login')">立即开始</a>
        <a v-if="!user" class="btn modern-btn-outline" :href="routeUrl('/register')">免费注册</a>
        <a v-if="user" class="btn modern-btn-primary" :href="routeUrl('/user')">用户后台</a>
        <a v-if="canAccessAdmin" class="btn modern-btn-outline" :href="routeUrl(adminUrl)">管理后台</a>
      </div>
    </div>
    <div class="modern-hero-visual">
      <div class="modern-stats-card">
        <div class="modern-stat-item"><span class="modern-stat-num">{{ homeStats.product_count || 0 }}</span><span class="modern-stat-label">商品总数</span></div>
        <div class="modern-stat-item"><span class="modern-stat-num">{{ homeStats.order_count || 0 }}</span><span class="modern-stat-label">订单总数</span></div>
        <div class="modern-stat-item"><span class="modern-stat-num">{{ money(homeStats.total_quantity || 0) }}</span><span class="modern-stat-label">总下单数</span></div>
      </div>
    </div>
  </div>

  <div class="modern-features section-gap">
    <h2 class="modern-section-title">核心优势</h2>
    <div class="modern-feature-grid">
      <div class="modern-feature-card"><div class="modern-feature-icon">⚡</div><h3>秒速到账</h3><p>下单后立即进入队列，无需漫长等待。</p></div>
      <div class="modern-feature-card"><div class="modern-feature-icon">🛡️</div><h3>安全稳定</h3><p>真实用户数据，非机器刷量，稳定可靠。</p></div>
      <div class="modern-feature-card"><div class="modern-feature-icon">💰</div><h3>价格优惠</h3><p>低于全网同行价格，给你最舒心的体验。</p></div>
      <div class="modern-feature-card"><div class="modern-feature-icon">🔌</div><h3>API对接</h3><p>开放接口，轻松对接你的服务平台。</p></div>
    </div>
  </div>

  <div class="modern-cta section-gap">
    <div class="modern-cta-card">
      <h2>准备好开始了吗？</h2>
      <p>注册即刻体验高效稳定的速刷服务</p>
      <div class="modern-cta-actions">
        <a v-if="!user" class="btn modern-btn-primary" :href="routeUrl('/register')">立即注册</a>
        <a v-if="user" class="btn modern-btn-primary" :href="routeUrl('/user')">进入后台</a>
      </div>
    </div>
  </div>

  <div class="modern-footer section-gap">
    <div v-if="settings.community_group_qq" class="modern-group"><span>用户交流群：</span><strong>{{ settings.community_group_qq }}</strong><button class="btn sm" @click="openGroup('community')">加入</button></div>
    <div v-if="canShowSupportGroup && settings.support_group_qq" class="modern-group"><span>售后群：</span><strong>{{ settings.support_group_qq }}</strong><button class="btn sm" @click="openGroup('support')">加入</button></div>
    <div class="modern-beian" v-if="settings.icp_beian_no"><a href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a></div>
  </div>
</div>
</template>

<!-- Template: minimal -->
<template v-if="settings.home_template === 'minimal'">
<div class="tpl-minimal">
  <div class="minimal-header">
    <h1>{{ site.name }}</h1>
    <p class="minimal-tagline">高效 · 稳定 · 实惠</p>
  </div>

  <div class="minimal-stats section-gap">
    <div class="minimal-stat"><span class="minimal-num">{{ homeStats.product_count || 0 }}</span><span class="minimal-label">商品</span></div>
    <div class="minimal-stat"><span class="minimal-num">{{ homeStats.order_count || 0 }}</span><span class="minimal-label">订单</span></div>
    <div class="minimal-stat"><span class="minimal-num">{{ money(homeStats.total_quantity || 0) }}</span><span class="minimal-label">总下单</span></div>
  </div>

  <div class="minimal-actions section-gap">
    <a v-if="!user" class="btn minimal-btn-primary" :href="routeUrl('/login')">登录</a>
    <a v-if="!user" class="btn minimal-btn-ghost" :href="routeUrl('/register')">注册</a>
    <a v-if="user" class="btn minimal-btn-primary" :href="routeUrl('/user')">用户后台</a>
    <a v-if="canAccessAdmin" class="btn minimal-btn-ghost" :href="routeUrl(adminUrl)">管理后台</a>
    <a class="btn minimal-btn-ghost" href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener">接口文档</a>
  </div>

  <div class="minimal-features section-gap">
    <div class="minimal-feature"><strong>秒速到账</strong><span>下单后立即处理</span></div>
    <div class="minimal-feature"><strong>安全稳定</strong><span>真实用户数据</span></div>
    <div class="minimal-feature"><strong>价格优惠</strong><span>低于同行价格</span></div>
    <div class="minimal-feature"><strong>API对接</strong><span>开放接口支持</span></div>
  </div>

  <div class="minimal-groups section-gap" v-if="settings.community_group_qq || (canShowSupportGroup && settings.support_group_qq)">
    <div v-if="settings.community_group_qq" class="minimal-group-item"><span>交流群</span><strong>{{ settings.community_group_qq }}</strong><button class="btn sm" @click="openGroup('community')">加入</button></div>
    <div v-if="canShowSupportGroup && settings.support_group_qq" class="minimal-group-item"><span>售后群</span><strong>{{ settings.support_group_qq }}</strong><button class="btn sm" @click="openGroup('support')">加入</button></div>
  </div>

  <div class="minimal-footer section-gap">
    <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
    <span v-if="settings.public_security_beian_no">网安备案：{{ settings.public_security_beian_no }}</span>
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
      <p class="business-subtitle">专业的企业级速刷解决方案</p>
      <div class="business-hero-actions">
        <a v-if="!user" class="btn business-btn-primary" :href="routeUrl('/login')">立即登录</a>
        <a v-if="!user" class="btn business-btn-secondary" :href="routeUrl('/register')">注册账号</a>
        <a v-if="user" class="btn business-btn-primary" :href="routeUrl('/user')">进入控制台</a>
        <a v-if="canAccessAdmin" class="btn business-btn-secondary" :href="routeUrl(adminUrl)">管理后台</a>
      </div>
    </div>
  </div>

  <div class="business-metrics section-gap">
    <div class="business-metric-card">
      <div class="business-metric-icon">📦</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ homeStats.product_count || 0 }}</span><span class="business-metric-label">商品总数</span></div>
    </div>
    <div class="business-metric-card">
      <div class="business-metric-icon">📋</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ homeStats.order_count || 0 }}</span><span class="business-metric-label">订单总数</span></div>
    </div>
    <div class="business-metric-card">
      <div class="business-metric-icon">📊</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ money(homeStats.total_quantity || 0) }}</span><span class="business-metric-label">总下单数</span></div>
    </div>
    <div class="business-metric-card">
      <div class="business-metric-icon">🔗</div>
      <div class="business-metric-info"><span class="business-metric-num">{{ boolText(settings.api_order_enabled) }}</span><span class="business-metric-label">API对接</span></div>
    </div>
  </div>

  <div class="business-services section-gap">
    <h2 class="business-section-title">我们的服务</h2>
    <div class="business-service-grid">
      <div class="business-service-card">
        <div class="business-service-icon">⚡</div>
        <h3>在线下单</h3>
        <p>支持多种商品类型，实时下单，秒速处理。</p>
        <a :href="routeUrl(user ? '/user' : '/login')" class="btn business-btn-sm">立即体验</a>
      </div>
      <div class="business-service-card">
        <div class="business-service-icon">🔍</div>
        <h3>实时查单</h3>
        <p>订单状态实时更新，处理进度一目了然。</p>
        <a :href="routeUrl(user ? '/user/orders' : '/login')" class="btn business-btn-sm">查看订单</a>
      </div>
      <div class="business-service-card">
        <div class="business-service-icon">🔌</div>
        <h3>API对接</h3>
        <p>开放标准接口，轻松集成到你的平台。</p>
        <a href="https://shua-xmzkj.apifox.cn/" target="_blank" rel="noopener" class="btn business-btn-sm">查看文档</a>
      </div>
    </div>
  </div>

  <div class="business-advantages section-gap">
    <h2 class="business-section-title">为什么选择我们</h2>
    <div class="business-advantage-grid">
      <div class="business-advantage-item"><span class="business-advantage-check">✓</span><span>秒速到账，无需等待</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">✓</span><span>真实数据，安全稳定</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">✓</span><span>价格优惠，性价比高</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">✓</span><span>7×24 小时社群支持</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">✓</span><span>支持卡密兑换功能</span></div>
      <div class="business-advantage-item"><span class="business-advantage-check">✓</span><span>完善的API文档</span></div>
    </div>
  </div>

  <div class="business-contact section-gap">
    <div class="business-contact-card">
      <h3>加入我们的社群</h3>
      <div class="business-contact-items">
        <div v-if="settings.community_group_qq" class="business-contact-item"><span>用户交流群</span><strong>{{ settings.community_group_qq }}</strong><button class="btn business-btn-sm" @click="openGroup('community')">加入</button></div>
        <div v-if="canShowSupportGroup && settings.support_group_qq" class="business-contact-item"><span>售后支持群</span><strong>{{ settings.support_group_qq }}</strong><button class="btn business-btn-sm" @click="openGroup('support')">加入</button></div>
      </div>
    </div>
  </div>

  <div class="business-footer section-gap">
    <div class="business-beian">
      <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
      <span v-if="settings.public_security_beian_no" class="business-psb">网安备案：{{ settings.public_security_beian_no }}</span>
    </div>
    <div class="business-copyright">© {{ new Date().getFullYear() }} {{ site.name }} All Rights Reserved</div>
  </div>
</div>
</template>

</section>


    <section v-else-if="routeMode === 'login'">
      <div class="login-shell">
        <div v-if="user" class="panel">
          <div class="page-head">
            <div>
              <h2>你已经登录</h2>
              <p>当前账号可直接进入对应后台，无需重复登录。</p>
            </div>
          </div>
          <div class="login-dual-actions">
            <a class="btn primary" :href="routeUrl('/user')">进入用户后台</a>
            <a v-if="canAccessAdmin" class="btn ghost" :href="routeUrl(adminUrl)">进入管理后台</a>
          </div>
        </div>
        <div v-else class="auth-box">
          <h3>统一登录</h3>
          <div class="form-grid">
            <div class="field full">
              <label>用户名</label>
              <input v-model.trim="home.login.username" placeholder="请输入用户名">
            </div>
            <div class="field full">
              <label>密码</label>
              <input v-model="home.login.password" type="password" placeholder="请输入密码">
            </div>
            <div class="field">
              <label>图片验证码</label>
              <input v-model.trim="home.login.captcha" placeholder="请输入验证码">
            </div>
            <div class="field">
              <label>验证码图片</label>
              <div class="inline-actions">
                <img :src="captchaUrl" alt="captcha" style="height:46px;border:1px solid var(--line);border-radius:12px;background:var(--input-bg)">
                <button class="btn sm" @click="refreshCaptcha">刷新</button>
              </div>
            </div>
          </div>
          <div class="login-dual-actions">
            <button class="btn primary" @click="submitLogin(false)">进入用户后台</button>
            <button class="btn ghost" @click="submitLogin(true)">进入管理后台</button>
            <a class="btn ghost" :href="routeUrl('/register')">没有账号？去注册</a>
          </div>
        </div>
      </div>
    </section>

    <section v-else-if="routeMode === 'register'">
      <div class="login-shell">
        <div v-if="user" class="panel">
          <div class="page-head">
            <div>
              <h2>你已登录</h2>
              <p>如需继续使用，请直接进入用户后台；如需换号，请先退出当前账号。</p>
            </div>
          </div>
          <div class="login-dual-actions">
            <a class="btn primary" :href="routeUrl('/user')">进入用户后台</a>
            <button class="btn ghost" @click="logout">退出当前账号</button>
          </div>
        </div>
        <div v-else class="auth-box">
          <h3>注册账号</h3>
          <div class="form-grid">
            <div class="field">
              <label>用户名</label>
              <input v-model.trim="home.register.username" placeholder="4-32位英文数字">
            </div>
            <div class="field">
              <label>昵称</label>
              <input v-model.trim="home.register.nickname" placeholder="昵称">
            </div>
            <div class="field">
              <label>QQ号</label>
              <input v-model.trim="home.register.qq" placeholder="QQ号">
            </div>
            <div class="field">
              <label>密码</label>
              <input v-model="home.register.password" type="password" placeholder="至少8位更安全">
            </div>
            <div v-if="needRegisterEmail" class="field">
              <label>邮箱</label>
              <input v-model.trim="home.register.email" placeholder="邮箱地址">
            </div>
            <div v-if="needRegisterMobile" class="field">
              <label>手机号</label>
              <input v-model.trim="home.register.mobile" placeholder="手机号">
            </div>
            <div class="field full">
              <label>邀请码（可选）</label>
              <input v-model.trim="home.register.invite_code" placeholder="没有可留空">
            </div>
            <div v-if="registerNeedCaptcha" class="field">
              <label>图片验证码</label>
              <input v-model.trim="home.register.captcha" placeholder="请输入验证码">
            </div>
            <div v-if="registerNeedCaptcha" class="field">
              <label>验证码图片</label>
              <div class="inline-actions">
                <img :src="captchaUrl" alt="captcha" style="height:46px;border:1px solid var(--line);border-radius:12px;background:var(--input-bg)">
                <button class="btn sm" @click="refreshCaptcha">刷新</button>
              </div>
            </div>
          </div>
          <div class="login-dual-actions">
            <button class="btn primary" @click="submitRegister">注册并进入用户后台</button>
            <a class="btn ghost" :href="routeUrl('/login')">已有账号？去登录</a>
          </div>
        </div>
      </div>
    </section>

    <section v-else-if="routeMode === 'exchange'">
      <div class="home-hero-grid">
        <div class="panel">
          <div class="page-head">
            <div><h2>商品兑换码兑换</h2></div>
            <div class="inline-actions"><button class="btn ghost" @click="loadExchangeOrders(true)">刷新历史订单</button></div>
          </div>
          <div class="form-grid">
            <div class="field full"><label>兑换码</label><input v-model.trim="exchangePublic.code" placeholder="请输入 48 位以上商品兑换码"></div>
          </div>
          <div class="inline-actions"><button class="btn primary" @click="previewExchangeCode">查询兑换码</button></div>
          <div v-if="exchangePublic.preview" class="section-gap section-stack">
            <div class="order-summary-box">
              <h4>{{ exchangePublic.preview.product_name }}</h4>
              <div class="order-summary-grid">
                <div class="subtle"><span>兑换码</span><strong class="text-break">{{ exchangePublic.preview.display_code }}</strong></div>
                <div class="subtle"><span>数量</span><strong>{{ exchangePublic.preview.quantity }}</strong></div>
                <div class="subtle"><span>计价单位</span><strong>每 {{ exchangePublic.preview.step_num }} 数量</strong></div>
                <div class="subtle"><span>创建时价格快照</span><strong>{{ money(exchangePublic.preview.price_snapshot) }} {{ currency }}</strong></div>
              </div>
              <div v-if="exchangePublic.preview.product_desc && exchangePublic.preview.product_desc.length" class="desc-list section-gap"><div class="desc-item" v-for="(desc,idx) in exchangePublic.preview.product_desc" :key="idx">{{ desc }}</div></div>
            </div>
            <div class="panel">
              <h3>填写兑换信息</h3>
              <div class="form-grid">
                <div class="field full"><label>QQ号</label><input v-model.trim="exchangePublic.form.qq" placeholder="请输入下单 QQ"><div class="qq-preview" v-if="exchangePublic.form.qq"><img :src="qqAvatar(exchangePublic.form.qq)" alt="qq"><div class="tiny">头像由 QQ 提供，用于辅助核对。</div></div></div>
                <div v-for="field in exchangeInputFields" :key="field.key" class="field" :class="{full: field.key === 'feed_id'}"><label>{{ field.label }}</label><input v-model.trim="exchangePublic.form.extra[field.key]" :placeholder="field.placeholder || ('请输入' + field.label)"></div>
              </div>
              <div class="auth-footnote">若商品需要 QQ 号、说说 ID 等参数，请由兑换者自行填写；兑换成功后会自动生成本系统订单号。</div>
              <div class="inline-actions section-gap"><button class="btn success" @click="redeemExchangeCode">确认兑换并下单</button></div>
            </div>
          </div>
        </div>
        <div class="section-stack">
          <div class="panel">
            <h3>订单查单</h3>
            <div class="search-row exchange-order-search"><input v-model.trim="exchangePublic.orderSearch" placeholder="输入本浏览器已兑换的订单号"><button class="btn primary" @click="queryExchangeOrder(exchangePublic.orderSearch)">查询进度</button></div>
            <div v-if="exchangePublic.orderDetail" class="order-summary-box section-gap"><div class="action-row"><strong class="mono text-break">{{ exchangePublic.orderDetail.display_order_no || exchangePublic.orderDetail.order_no }}</strong><span class="badge" :class="badgeTone(exchangePublic.orderDetail.state)">{{ exchangePublic.orderDetail.state || '-' }}</span></div><div class="tiny">{{ exchangePublic.orderDetail.product_name || '-' }} · {{ formatDate(exchangePublic.orderDetail.created_at) }}</div><div class="pre-wrap section-gap">{{ exchangePublic.orderDetail.latest_message || exchangePublic.orderDetail.message || '无' }}</div></div>
          </div>
          <div class="panel">
            <h3>历史兑换订单</h3>
            <div v-if="!exchangePublic.orders.length" class="placeholder-card">当前浏览器还没有兑换记录，兑换成功后会自动出现在这里。</div>
            <div v-else class="code-list"><div v-for="order in exchangePublic.orders" :key="order.order_no" class="code-item"><div style="min-width:0"><div class="mono text-break">{{ order.display_order_no || order.order_no }}</div><div class="tiny">{{ order.product_name || '-' }} · {{ formatDate(order.created_at) }}</div></div><div class="inline-actions"><span class="badge" :class="badgeTone(order.state)">{{ order.state || '-' }}</span><button class="btn sm ghost" @click="queryExchangeOrder(order.order_no)">查看进度</button></div></div></div>
          </div>
          <div class="panel"><h3>说明</h3><div class="desc-list"><div class="desc-item">在兑换前，请确定已经开启了相应权限</div><div class="desc-item">若商品需要额外参数，请按页面提示填写；系统会按上游商品输入项进行校验。</div></div></div>
        </div>
      </div>
    </section>

    <section v-else-if="routeMode === 'user'">
      <div v-if="!user" class="auth-box" style="max-width:560px;margin:0 auto;">
        <h3>请先登录</h3>
        <p class="panel-sub">用户后台已与首页分离，请先前往统一登录页完成登录。</p>
        <div class="inline-actions">
          <a class="btn primary" :href="routeUrl('/login')">前往登录</a>
          <a class="btn ghost" :href="routeUrl('/register')">没有账号？去注册</a>
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
                  <div class="tiny">UID：{{ user.uid || '-' }}</div>
                </div>
              </div>
            </div>
            <div class="side-profile-card">
              <small class="muted">当前余额</small>
              <strong>{{ money(profile.user ? profile.user.balance : 0) }} {{ currency }}</strong>
            </div>
            <div class="side-profile-card">
              <small class="muted">用户组</small>
              <strong>{{ profile.group ? profile.group.name : '未加载' }}</strong>
            </div>
          </div>
          <div class="side-title">用户后台</div>
          <div class="nav-list">
            <button v-for="item in userNav" :key="item.key" class="nav-item" :class="{active:userTab===item.key}" @click="switchUserTab(item.key)">{{ item.label }}</button>
          </div>
        </aside>

        <div class="content-area">
          <div v-if="userTab === 'dashboard'">
            <div class="page-head">
              <div>
                <h2>用户首页</h2>
                <p>查看余额、用户组、累计消费与代理接口状态。</p>
              </div>
            </div>
            <div class="stats-grid">
              <div class="stat"><small>用户组</small><strong>{{ profile.group ? profile.group.name : '-' }}</strong></div>
              <div class="stat"><small>当前余额</small><strong>{{ money(profile.user ? profile.user.balance : 0) }}</strong></div>
              <div class="stat"><small>累计消费</small><strong>{{ money(profile.user ? profile.user.total_consume : 0) }}</strong></div>
              <div class="stat"><small>累计充值</small><strong>{{ money(profile.user ? profile.user.total_recharge : 0) }}</strong></div>
            </div>
            <div class="grid-2 section-gap">
              <div class="panel">
                <h3>对接状态</h3>
                <div class="kv">
                  <div>是否允许对接</div><div><span class="badge" :class="profile.api_access && profile.api_access.allow ? 'success' : 'warning'">{{ profile.api_access && profile.api_access.allow ? '允许对接' : '暂不允许对接' }}</span></div>
                  <div>系统 UID</div><div class="code-inline">{{ profile.user ? profile.user.uid : '-' }}</div>
                  <div>API Key</div><div><template v-if="profile.user && profile.user.api_key"><span class="code-inline">{{ profile.user.api_key }}</span></template><template v-else>当前账号暂无 API Key</template></div>
                  <template v-if="!(profile.api_access && profile.api_access.allow)">
                    <div>当前提示</div><div>{{ apiAccessHint(profile.api_access) }}</div>
                  </template>
                </div>
                <div class="inline-actions section-gap">
                  <button class="btn primary" @click="resetOwnApiKey" :disabled="!(profile.api_access && profile.api_access.can_generate_key)">{{ profile.user && profile.user.api_key ? '重置 API Key' : '生成 API Key' }}</button>
                </div>
              </div>
              <div class="panel">
                <h3>快捷入口</h3>
                <div class="quick-grid">
                  <button class="quick-card" @click="switchUserTab('order')"><h3>在线下单</h3></button>
                  <button class="quick-card" @click="switchUserTab('orders')"><h3>查单系统</h3></button>
                  <button class="quick-card" @click="switchUserTab('recharge')"><h3>额度充值</h3></button>
                  <button class="quick-card" @click="switchUserTab('invites')"><h3>邀请管理</h3></button>
                  <button class="quick-card" @click="switchUserTab('groups')"><h3>代理等级</h3></button>
                  <button class="quick-card" @click="switchUserTab('profile')"><h3>个人资料</h3></button>
                </div>
              </div>
            </div>
            <div v-if="canShowSupportGroup" class="panel section-gap">
              <div class="card-title">
                <div>
                  <h3>售后 / 支持群</h3>
                  <div class="landing-group-code">{{ settings.support_group_qq }}</div>
                </div>
                <button class="btn primary" @click="openGroup('support')">加入售后群</button>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'order'">
            <div class="page-head">
              <div>
                <h2>在线下单</h2>
              </div>
            </div>
            <div class="section-stack">
              <div class="panel">
                <h3>下单信息</h3>
                <div class="form-grid">
                  <div class="field">
                    <label>筛选商品</label>
                    <input v-model.trim="userState.productKeyword" placeholder="输入商品名筛选">
                  </div>
                  <div class="field">
                    <label>选择商品</label>
                    <select v-model="orderForm.sign">
                      <option value="">请选择商品</option>
                      <option v-for="product in filteredProducts" :key="product.id" :value="product.upstream_sign">
                        {{ product.name }}
                      </option>
                    </select>
                  </div>
                  <div class="field">
                    <label>QQ号</label>
                    <div class="order-qq-row">
                      <input v-model.trim="orderForm.qq" placeholder="请输入下单 QQ" @input="clearFeedSelection">
                      <div v-if="orderForm.qq" class="order-qq-avatar">
                        <img :src="qqAvatar(orderForm.qq)" alt="QQ 头像">
                        <div class="tiny">用于辅助核对 QQ。</div>
                      </div>
                    </div>
                  </div>
                  <div class="field">
                    <label>数量</label>
                    <input v-model.number="orderForm.num" type="number" :min="selectedProduct ? selectedProduct.min_num : 1" :max="selectedProduct ? selectedProduct.max_num : 999999999" :step="selectedProduct ? selectedProduct.step_num : 1" @change="scheduleQuote">
                  </div>
                  <div v-if="selectedProduct" class="field full">
                    <label>价格计算</label>
                    <div v-if="quote" class="order-summary-box">
                      <h4>{{ selectedProduct.name }} · 本次下单预估</h4>
                      <div class="order-summary-grid">
                        <div class="subtle"><span>下单数量</span><strong>{{ quote.quantity }}</strong></div>
                        <div class="subtle"><span>最终价格</span><strong>{{ money(quote.price) }} {{ currency }}</strong></div>
                        <div class="subtle"><span>折扣倍率</span><strong>{{ Number(quote.discount_rate || 1).toFixed(2) }}</strong></div>
                        <div class="subtle"><span>计价单位</span><strong>每 {{ selectedProduct.step_num }} 数量</strong></div>
                        <div class="subtle"><span>当前单价</span><strong>{{ money(selectedProduct.preview_price) }} {{ currency }}</strong></div>
                        <div class="subtle"><span>数量范围</span><strong>{{ selectedProduct.min_num }} - {{ selectedProduct.max_num }}</strong></div>
                      </div>
                    </div>
                    <div v-else class="placeholder-card">填写下单数量后，将在这里显示最终价格计算结果。</div>
                  </div>
                  <div v-if="selectedProduct && selectedProduct.desc && selectedProduct.desc.length" class="field full">
                    <label>商品描述</label>
                    <div class="desc-list">
                      <div v-for="(desc,idx) in selectedProduct.desc" :key="idx" class="desc-item">{{ desc }}</div>
                    </div>
                  </div>
                  <div v-if="showDelayedOption" class="field full">
                    <label>慢刷模式</label>
                    <div class="switch-inline">
                      <label><input type="checkbox" v-model="orderForm.is_delayed" @change="scheduleQuote"> 启用慢刷</label>
                    </div>
                    <div class="auth-footnote">慢刷虽然速度变慢，但是价格更加实惠。且部分慢刷有最低下单要求，详情请看上游文档。</div>
                  </div>
                  <div v-for="field in dynamicInputFields" :key="field.key" class="field" :class="{full:field.key==='feed_id'}">
                    <label>{{ field.label }}</label>
                    <template v-if="field.key === 'feed_id'">
                      <input v-model.trim="orderForm.feed_id" placeholder="请选择或手动填写说说 ID">
                      <div class="inline-actions">
                        <button class="btn sm" @click="loadFeedList">获取说说列表</button>
                        <span class="tiny">若图片和内容都为空，可能是转发内容，并非空说说。</span>
                      </div>
                    </template>
                    <template v-else>
                      <input v-model.trim="orderForm.extra[field.key]" :placeholder="field.placeholder || ('请输入' + field.label)">
                    </template>
                  </div>
                </div>
                <div class="inline-actions section-gap">
                  <button class="btn primary" @click="createOrder">提交订单</button>
                  <button class="btn ghost" @click="scheduleQuote">重新计算价格</button>
                </div>
                <div v-if="!filteredProducts.length" class="empty section-gap">当前没有符合筛选条件的商品。</div>
              </div>
            </div>

            <div v-if="userState.feedModalVisible" class="modal-mask" @click.self="closeFeedModal">
              <div class="modal">
                <div class="modal-head">
                  <div>
                    <h3>说说列表</h3>
                    <div class="tiny">若商品需要 feed_id，请在弹窗中选择对应说说；若图片和内容都为空，可能是转发内容，并非空说说。</div>
                  </div>
                  <button class="btn ghost" @click="closeFeedModal">关闭</button>
                </div>
                <div class="feed-modal-grid">
                  <div v-for="item in userState.feedItems" :key="item.id || item.feed_id || item.fid" class="feed-card" :class="{active: selectedFeedId === resolveFeedId(item)}" @click="selectFeed(item)">
                    <div class="feed-head">
                      <div>
                        <strong>说说 ID：{{ resolveFeedId(item) || '-' }}</strong>
                        <div class="tiny">发布时间：{{ formatFeedTime(item) }}</div>
                      </div>
                      <span class="badge info">点选即填入</span>
                    </div>
                    <div class="feed-content" style="margin-top:10px">{{ item.content || '（无正文）' }}</div>
                    <div class="feed-images" v-if="feedImageList(item).length">
                      <img v-for="(image,idx) in feedImageList(item)" :key="idx" :src="image.display || image.proxy || image.original || image.url" alt="feed">
                    </div>
                    <div v-if="item.is_possible_repost" class="feed-note">若图片和内容都为空，此说说可能是转发内容，并非空说说。</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'orders'">
            <div class="page-head">
              <div>
                <h2>查单系统</h2>
                <p>非终态订单在查看详情时会实时同步上游并展示最新执行信息，不显示原始请求与响应体。</p>
              </div>
            </div>
            <div class="panel">
              <div class="search-row">
                <div class="field" style="min-width:260px;flex:1 1 260px">
                  <label>系统订单号</label>
                  <input v-model.trim="userState.orderSearch" placeholder="输入系统订单号后查询详情">
                </div>
                <div class="inline-actions" style="padding-top:26px">
                  <button class="btn primary" @click="searchOrderDetail">查询订单</button>
                  <button class="btn ghost" @click="loadUserOrders(true)">刷新列表</button>
                </div>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>最近订单</h3>
                <div class="table-wrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>系统订单号</th>
                        <th>商品</th>
                        <th>状态</th>
                        <th>数量</th>
                        <th>金额</th>
                        <th>操作</th>
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
                          <button class="btn sm ghost" @click="showOrderDetail(row.display_order_no || row.order_no)">查看</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="panel">
                <h3>订单详情</h3>
                <div v-if="!userState.orderDetail" class="empty">点击左侧订单或输入系统订单号后，即可查看详情。</div>
                <template v-else>
                  <div class="order-metrics">
                    <div class="order-metric"><small>系统订单号</small><strong class="mono mono-wrap">{{ userState.orderDetail.display_order_no || userState.orderDetail.order_no }}</strong></div>
                    <div class="order-metric"><small>订单状态</small><strong>{{ userState.orderDetail.state }}</strong></div>
                    <div class="order-metric"><small>最新备注</small><strong>{{ userState.orderDetail.latest_message || '无' }}</strong></div>
                  </div>
                  <div class="kv">
                    <div>商品</div><div>{{ userState.orderDetail.product_name }}</div>
                    <div>QQ号</div><div>{{ userState.orderDetail.target_qq }}</div>
                    <div>数量</div><div>{{ userState.orderDetail.quantity }}</div>
                    <div>说说ID</div><div>{{ userState.orderDetail.feed_id || '-' }}</div>
                    <div>开始数量</div><div>{{ userState.orderDetail.start_num ?? '-' }}</div>
                    <div>当前数量</div><div>{{ userState.orderDetail.current_num ?? '-' }}</div>
                    <div>结束数量</div><div>{{ userState.orderDetail.finish_num ?? '-' }}</div>
                    <div>开始时间</div><div>{{ formatDate(userState.orderDetail.started_at) }}</div>
                    <div>最后更新时间</div><div>{{ formatDate(userState.orderDetail.last_sync_at || userState.orderDetail.updated_at) }}</div>
                    <div>结束时间</div><div>{{ formatDate(userState.orderDetail.finished_at) }}</div>
                    <div>下单时间</div><div>{{ formatDate(userState.orderDetail.created_at) }}</div>
                  </div>
                  <div class="tip-box section-gap" v-if="userState.orderDetail.can_retry || userState.orderDetail.state === '失败'">
                    <div class="note-strong">因忘记开权限或者其他原因导致失败的，可申请补单一次，补单后还失败的将不再支持再次补单。</div>
                  </div>
                  <div class="inline-actions">
                    <button class="btn warning" :disabled="!userState.orderDetail.can_retry" @click="userRetryOrder(userState.orderDetail)">申请补单</button>
                    <button class="btn danger" :disabled="!userState.orderDetail.can_refund" @click="userRefundOrder(userState.orderDetail)">申请退款</button>
                    <button class="btn ghost" @click="showOrderDetail(userState.orderDetail.display_order_no || userState.orderDetail.order_no)">重新同步</button>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'exchange_codes'">
            <div class="page-head">
              <div>
                <h2>商品兑换码</h2>
                <p>生成可分享的商品兑换码。兑换者无需登录即可填写 QQ / 说说 ID 并直接下单。</p>
              </div>
              <div class="inline-actions">
                <button class="btn ghost" @click="loadUserExchangeCodes(true)">刷新兑换码</button>
              </div>
            </div>
            <div v-if="userState.exchangeSettings && !userState.exchangeSettings.enabled" class="tip-box">商品兑换码功能当前已关闭，请联系管理员。</div>
            <div class="grid-2">
              <div class="panel">
                <h3>批量生成兑换码</h3>
                <div class="form-grid">
                  <div class="field full"><label>选择商品</label><select v-model="exchangeCodeForm.sign" @change="exchangeCodeForm.quantity = selectedExchangeProduct ? Number(selectedExchangeProduct.min_num || selectedExchangeProduct.step_num || 1) : 0"><option value="">请选择商品</option><option v-for="product in userState.products" :key="product.upstream_sign" :value="product.upstream_sign">{{ product.name }}</option></select></div>
                  <div class="field"><label>每个兑换码的下单数量</label><input v-model.number="exchangeCodeForm.quantity" type="number" min="1" :max="selectedExchangeProduct ? selectedExchangeProduct.max_num : null" :step="selectedExchangeProduct ? (selectedExchangeProduct.step_num || 1) : 1"></div>
                  <div class="field"><label>生成数量</label><input v-model.number="exchangeCodeForm.count" type="number" min="1" max="1000"></div>
                  <div class="field"><label>生成手续费（每张）</label><input :value="money(userState.exchangeSettings ? userState.exchangeSettings.generation_fee : 0) + ' ' + currency" readonly></div>
                </div>
                <div v-if="selectedExchangeProduct" class="order-summary-box section-gap"><div class="tiny">当前用户价格</div><strong>{{ money(selectedExchangeProduct.sell_price || selectedExchangeProduct.price || 0) }} {{ currency }} / {{ selectedExchangeProduct.step_num || 1 }} 个</strong><div v-if="selectedExchangeProduct.desc && selectedExchangeProduct.desc.length" class="desc-list section-gap"><div v-for="(desc,idx) in selectedExchangeProduct.desc" :key="idx" class="desc-item">{{ desc }}</div></div></div>
                <div class="admin-note section-gap" v-if="userState.exchangeSettings"><div>兑换码格式：<span class="code-inline">{{ userState.exchangeSettings.format }}</span></div><div class="tiny">{{ userState.exchangeSettings.format_help }}</div></div>
                <div class="inline-actions section-gap"><button class="btn primary" @click="createExchangeCode" :disabled="!selectedExchangeProduct || !exchangeCodeForm.quantity || !exchangeCodeForm.count">批量生成兑换码</button></div>
                <div v-if="exchangeCodeForm.generatedCodes.length" class="section-gap"><div class="action-row"><strong>本次生成的兑换码</strong><button class="btn sm ghost" @click="copyGeneratedExchangeCodes">一键复制全部</button></div><textarea class="code-output" readonly :value="exchangeCodeForm.generatedCodes.join('\n')"></textarea></div>
              </div>
              <div class="panel"><h3>使用说明</h3><div class="desc-list"><div class="desc-item">兑换者打开 <a :href="exchangePageUrl" target="_blank" rel="noopener">{{ exchangePageUrl }}</a> 即可兑换</div><div class="desc-item">生成兑换码只收取后台设置的生成手续费；兑换后订单费用按兑换码创建时的商品价格快照从生成者账户扣除。</div><div class="desc-item">兑换码长度至少 48 位，支持系统前缀、随机字符串和用户 UID 组合。</div></div></div>
            </div>
            <div class="panel section-gap"><div class="action-row"><h3>我的兑换码</h3><span class="tiny">展示完整兑换码；已使用或已销毁的兑换码不可再次编辑。</span></div><div v-if="!userState.exchangeCodes.length" class="empty">暂无兑换码。</div><div v-else class="code-list"><div v-for="row in userState.exchangeCodes" :key="row.id" class="code-item"><div style="min-width:0"><strong class="mono text-break">{{ row.code || row.display_code }}</strong><div class="tiny">{{ row.product_name_snapshot }} · {{ row.quantity }} 个 · {{ formatDate(row.created_at) }}<span v-if="row.redeemer_qq"> · 兑换者QQ {{ row.redeemer_qq }}</span></div></div><div class="inline-actions"><span class="badge" :class="row.status === 'used' ? 'success' : (row.status === 'destroyed' ? 'danger' : 'info')">{{ row.status === 'used' ? '已兑换' : (row.status === 'destroyed' ? '已销毁' : '未使用') }}</span><button v-if="row.status === 'unused'" class="btn sm ghost" @click="editExchangeCode(row)">编辑</button><button v-if="row.status === 'unused'" class="btn sm danger" @click="destroyExchangeCode(row)">销毁</button></div></div></div></div>
          </div>

          <div v-else-if="userTab === 'recharge'">
            <div class="page-head">
              <div>
                <h2>额度充值</h2>
                <p>在线支付填写人民币金额，系统展示预计到账额度；同时支持卡密充值。</p>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>在线充值</h3>
                <div class="form-grid">
                  <div class="field full">
                    <label>支付通道</label>
                    <select v-model.number="rechargeForm.channel_id">
                      <option value="0">请选择支付通道</option>
                      <option v-for="channel in userState.payments.channels" :key="channel.id" :value="Number(channel.id)">{{ channel.name }}（{{ channel.pay_type }}）</option>
                    </select>
                  </div>
                  <div class="field full">
                    <label>充值金额（人民币）</label>
                    <input v-model.trim="rechargeForm.money" type="number" min="0" step="0.01" placeholder="例如 10.00">
                    <div class="tiny">预计到账：{{ money(rechargePreview.credit_amount) }} {{ currency }} + 赠送 {{ money(rechargePreview.bonus_amount) }} {{ currency }} = {{ money(rechargePreview.expected_amount) }} {{ currency }}</div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="createRecharge">创建充值订单</button>
                  <button class="btn ghost" @click="loadUserPayments(true)">刷新支付通道</button>
                </div>
              </div>
              <div class="panel">
                <h3>支付信息</h3>
                <div v-if="!userState.paymentResult" class="placeholder-card">创建充值订单后，这里会展示支付链接、二维码与预计到账额度。</div>
                <template v-else>
                  <div class="order-summary-box">
                    <div class="kv">
                      <div>充值订单号</div><div class="mono">{{ userState.paymentResult.order_no }}</div>
                      <div>支付金额</div><div>{{ userState.paymentResult.money_yuan }} 元</div>
                      <div>到账额度</div><div>{{ money(userState.paymentResult.credit_amount) }} {{ currency }}</div>
                      <div>赠送额度</div><div>{{ money(userState.paymentResult.bonus_amount) }} {{ currency }}</div>
                      <div>预计总到账</div><div><strong>{{ money(userState.paymentResult.expected_amount) }} {{ currency }}</strong></div>
                    </div>
                  </div>
                  <div class="qr-box section-gap">
                    <canvas ref="payCanvas"></canvas>
                  </div>
                  <div class="desktop-only-hint">qrcode 字段返回的是解码文本链接，电脑端已转为二维码显示；无论设备类型，下面都提供直接打开按钮。</div>
                  <div class="pay-actions">
                    <a class="btn primary" :href="paymentJumpLink" target="_blank" rel="noopener">打开支付链接</a>
                    <button class="btn ghost" @click="copyPaymentLink">复制支付链接</button>
                  </div>
                </template>
              </div>
            </div>

            <div class="grid-2 section-gap">
              <div class="panel">
                <h3>卡密充值</h3>
                <div class="form-grid">
                  <div class="field full">
                    <label>卡密</label>
                    <input v-model.trim="cardRedeemCode" placeholder="请输入卡密">
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn success" @click="redeemCard">立即兑换</button>
                </div>
              </div>
              <div class="panel">
                <h3>最近充值订单</h3>
                <div class="table-wrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>订单号</th>
                        <th>通道</th>
                        <th>金额</th>
                        <th>预计到账</th>
                        <th>状态</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="row in userState.payments.orders" :key="row.id">
                        <td class="mono">{{ row.order_no }}</td>
                        <td>{{ row.channel_name || '-' }}</td>
                        <td>{{ row.money_yuan }} 元</td>
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
                <h2>邀请管理</h2>
                <p>一个用户可拥有多个邀请码，默认只生成一个 20 位邀请码。自定义邀请码会按长度规则扣除额度。</p>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>邀请码列表</h3>
                <div v-if="!userState.invites.codes.length" class="empty">暂无邀请码。</div>
                <div v-else class="code-list">
                  <div class="code-item" v-for="code in userState.invites.codes" :key="code.id">
                    <div>
                      <div class="mono">{{ code.code }}</div>
                      <div class="tiny">长度 {{ code.length }} · 已使用 {{ code.used_count }} 次</div>
                    </div>
                    <div class="inline-actions">
                      <span class="badge" :class="code.is_default ? 'success' : 'info'">{{ code.is_default ? '默认邀请码' : '自定义邀请码' }}</span>
                      <span class="badge">支付 {{ money(code.price_paid || 0) }} {{ currency }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel">
                <h3>创建邀请码</h3>
                <div class="form-grid">
                  <div class="field">
                    <label>随机邀请码的长度</label>
                    <input v-model.number="inviteForm.length" type="number" min="6" max="48">
                  </div>
                  <div class="field">
                    <label>预计价格</label>
                    <input :value="money(invitePricePreview) + ' ' + currency" readonly>
                  </div>
                  <div class="field full">
                    <label>自定义邀请码（可选）</label>
                    <input v-model.trim="inviteForm.code" placeholder="6-48位英文或数字，区分大小写；留空则随机生成">
                  </div>
                </div>
                <div class="auth-footnote">默认邀请码不扣费；自定义邀请码价格遵循后台配置的固定价或按长度计价规则。</div>
                <div class="inline-actions">
                  <button class="btn primary" @click="createInviteCode">创建邀请码</button>
                </div>
              </div>
            </div>
            <div class="panel section-gap">
              <h3>邀请记录</h3>
              <div class="table-wrap">
                <table class="table">
                  <thead>
                    <tr>
                      <th>被邀请用户</th>
                      <th>是否有效邀请</th>
                      <th>有效时间</th>
                      <th>记录时间</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="row in userState.invites.records" :key="row.id">
                      <td>{{ row.invitee_nickname || row.invitee_username || ('#' + row.invitee_id) }}</td>
                      <td><span class="badge" :class="row.became_valid ? 'success' : 'warning'">{{ row.became_valid ? '有效邀请' : '待达成' }}</span></td>
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
                <h2>代理等级</h2>
                <p>列出全部用户组、升级条件和介绍；满足条件后可手动触发升级检查。</p>
              </div>
              <div class="inline-actions">
                <button class="btn primary" @click="claimUserGroup">检测并尝试升级</button>
              </div>
            </div>
            <div class="grid-2">
              <div v-for="group in userState.groups" :key="group.id" class="level-card" :class="{active: profile.group && profile.group.id===group.id}">
                <div class="card-title">
                  <div>
                    <h4>{{ group.name }}</h4>
                    <div class="tiny mono">组ID：{{ group.group_code }}</div>
                  </div>
                  <span class="badge" :class="profile.group && profile.group.id===group.id ? 'success' : 'info'">{{ profile.group && profile.group.id===group.id ? '当前等级' : '可升级等级' }}</span>
                </div>
                  <div class="desc-list" style="margin-top:12px">
                    <div class="desc-item">门槛模式：{{ thresholdModeLabel(group.threshold_mode) }}</div>
                    <div class="desc-item">门槛数值：{{ money(group.threshold_value) }}</div>
                    <div class="desc-item">充值赠送：{{ Number(group.recharge_bonus_rate || 1).toFixed(2) }} 倍</div>
                    <div class="desc-item">对接默认：{{ group.allow_api_default ? '允许' : '不允许' }}</div>
                  </div>
                <div v-if="group.description" class="feed-note" style="margin-top:12px">{{ group.description }}</div>
              </div>
            </div>
          </div>

          <div v-else-if="userTab === 'profile'">
            <div class="page-head">
              <div>
                <h2>个人资料与密码</h2>
                <p>你可以随时修改 QQ、昵称、邮箱、手机号等资料，并在此重置密码。头像固定使用 QQ 头像。</p>
              </div>
            </div>
            <div class="grid-2">
              <div class="panel">
                <h3>编辑个人资料</h3>
                <div class="form-grid">
                  <div class="field">
                    <label>用户名</label>
                    <input :value="profile.user ? profile.user.username : ''" readonly>
                  </div>
                  <div class="field">
                    <label>昵称</label>
                    <input v-model.trim="profileForm.nickname" placeholder="昵称">
                  </div>
                  <div class="field">
                    <label>QQ号</label>
                    <input v-model.trim="profileForm.qq" placeholder="QQ号">
                    <div class="qq-preview" v-if="profileForm.qq"><img :src="qqAvatar(profileForm.qq)" alt="qq"><div class="tiny">QQ 头像预览</div></div>
                  </div>
                  <div class="field">
                    <label>邮箱</label>
                    <input v-model.trim="profileForm.email" placeholder="邮箱">
                  </div>
                  <div class="field">
                    <label>手机号</label>
                    <input v-model.trim="profileForm.mobile" placeholder="手机号">
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveProfile">保存资料</button>
                </div>
              </div>
              <div class="panel">
                <h3>修改密码</h3>
                <div class="form-grid">
                  <div class="field full">
                    <label>旧密码</label>
                    <input v-model="passwordForm.old_password" type="password" placeholder="请输入旧密码">
                  </div>
                  <div class="field full">
                    <label>新密码</label>
                    <input v-model="passwordForm.new_password" type="password" placeholder="请输入新密码">
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn warning" @click="changePassword">更新密码</button>
                </div>
                <div class="auth-footnote">修改密码成功后，下次登录请使用新密码。</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section v-else>
      <div v-if="!canAccessAdmin" class="auth-box" style="max-width:620px;margin:0 auto;">
        <h3>无后台访问权限</h3>
        <p class="panel-sub">管理后台与用户后台完全分离。请先前往统一登录页，使用管理员或站长账号登录后再进入后台。</p>
        <div class="inline-actions">
          <a class="btn primary" :href="routeUrl('/login')">前往统一登录</a>
          <a class="btn ghost" :href="routeUrl('/')">返回首页</a>
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
          <div class="side-title" v-if="!adminSidebarCollapsed">管理后台</div>
          <div class="admin-menu">
            <template v-for="item in adminNav" :key="item.key">
              <button v-if="!item.children || !item.children.length" class="nav-item admin-menu-toggle" :class="{active:adminTab===item.key}" @click="switchAdminTab(item.key)">
                <span class="nav-item-label">{{ item.label }}</span>
              </button>
              <div v-else class="admin-menu-group">
                <button class="nav-item admin-menu-toggle" :class="{active:adminCurrentMeta.parent===item.key}" @click="toggleAdminMenu(item.key)">
                  <span class="nav-item-label">{{ item.label }}</span>
                  <span v-if="!adminSidebarCollapsed" class="tiny">{{ adminMenuOpenKeys[item.key] ? '−' : '+' }}</span>
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
              <button class="btn ghost admin-sidebar-toggle" @click="setAdminSidebarCollapsed()">{{ adminSidebarCollapsed ? '展开菜单' : '收起菜单' }}</button>
            </div>
          </div>
          <div v-if="adminTab === 'dashboard'">
            <div class="page-head">
              <div>
                <h2>管理首页</h2>
                <p>概览今日订单、总用户、利润排行、余额排行与上游余额。</p>
              </div>
              <div class="inline-actions">
                <button class="btn ghost" @click="loadAdminDashboard(true)">刷新数据</button>
              </div>
            </div>
            <div class="stats-grid" v-if="adminState.dashboard">
              <div class="stat"><small>今日订单数</small><strong>{{ adminState.dashboard.orders_today }}</strong></div>
              <div class="stat"><small>总用户数</small><strong>{{ adminState.dashboard.users_total }}</strong></div>
              <div class="stat"><small>今日利润</small><strong>{{ money(adminState.dashboard.profit_today) }}</strong><span class="amount-yuan">{{ yuanApprox(adminState.dashboard.profit_today) }}</span></div>
              <div class="stat"><small>用户总余额</small><strong>{{ money(adminState.dashboard.balance_total) }}</strong><span class="amount-yuan">{{ yuanApprox(adminState.dashboard.balance_total) }}</span></div>
            </div>
            <div class="grid-2 section-gap" v-if="adminState.dashboard">
              <div class="panel">
                <h3>排行榜</h3>
                <div class="pill-nav">
                  <span class="badge info">上游余额：{{ adminState.dashboard.upstream_balance === null ? '获取失败' : money(adminState.dashboard.upstream_balance) }}<small v-if="adminState.dashboard.upstream_balance !== null" class="amount-yuan inline">{{ yuanApprox(adminState.dashboard.upstream_balance) }}</small></span>
                </div>
                <div class="table-wrap section-gap">
                  <table class="table">
                    <thead><tr><th>今日消费排行</th><th>总消费排行</th><th>余额排行</th><th>今日充值排行</th></tr></thead>
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
                <h3>快捷入口</h3>
                <div class="quick-grid">
                  <button class="quick-card" @click="switchAdminTab('products-list')"><h3>商品管理</h3></button>
                  <button class="quick-card" @click="switchAdminTab('users-list')"><h3>用户管理</h3></button>
                  <button class="quick-card" @click="switchAdminTab('orders-list')"><h3>订单管理</h3></button>
                  <button class="quick-card" @click="switchAdminTab('api-conditions')"><h3>对接设置</h3></button>
                  <button class="quick-card" @click="switchAdminTab('cards-generate')"><h3>充值设置</h3></button>
                  <button class="quick-card" @click="switchAdminTab('settings-basic')"><h3>系统设置</h3></button>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['products-sync','products-list'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'products-sync' ? '更新商品数据' : '商品管理' }}</h2>
                <p>{{ adminTab === 'products-sync' ? '从上游重新拉取商品，并更新本地商品基础信息。' : '控制商品是否允许前台下单、接口对接及数量折扣。' }}</p>
              </div>
              <div class="inline-actions">
                <button v-if="adminTab === 'products-sync'" class="btn primary" @click="syncProducts">立即同步商品</button>
                <button v-if="adminTab === 'products-list'" class="btn ghost" @click="loadAdminProducts(true)">刷新列表</button>
              </div>
            </div>
            <div v-if="adminTab === 'products-sync'" class="panel">
              <h3>同步上游商品</h3>
              <p class="panel-sub">同步会更新商品名称、价格、数量范围和输入字段；本地设置的前台开关、对接开关与折扣规则仍可在“管理商品”中维护。</p>
              <div class="inline-actions section-gap"><button class="btn primary" @click="syncProducts">更新商品数据</button></div>
            </div>
            <div v-if="adminTab === 'products-list'" class="product-grid">
              <div class="product-card" v-for="product in adminState.products" :key="product.id">
                <div class="card-title">
                  <div>
                    <h3>{{ product.name }}</h3>
                    <div class="tiny mono">{{ product.upstream_sign }}</div>
                  </div>
                  <span class="badge" :class="product.enabled_bool ? 'success' : 'danger'">{{ product.enabled_bool ? '已启用' : '已停用' }}</span>
                </div>
                <div class="product-meta">
                  <div class="subtle"><span>前台下单</span><label><input type="checkbox" v-model="product.allow_frontend_bool"> 允许</label></div>
                  <div class="subtle"><span>允许对接</span><label><input type="checkbox" v-model="product.allow_api_bool"> 允许</label></div>
                  <div class="subtle"><span>商品状态</span><label><input type="checkbox" v-model="product.enabled_bool"> 启用</label></div>
                  <div class="subtle product-sort-field"><span>排序优先级</span><div class="sort-input-wrap"><input v-model.number="product.sort_order" class="sort-priority-input" type="number" step="1" inputmode="numeric" aria-label="商品排序优先级"><span class="sort-hint">数字越小越靠前</span></div></div>
                  <div class="subtle"><span>范围</span><strong>{{ product.min_num }} - {{ product.max_num }} / 步长 {{ product.step_num }}</strong></div>
                </div>
                <div class="section-gap">
                  <div class="card-title"><h4>数量折扣</h4><button class="btn sm ghost" @click="addProductDiscount(product)">新增折扣</button></div>
                  <div v-if="!product.discounts.length" class="placeholder-card" style="padding:14px">暂无折扣规则。</div>
                  <div v-else class="editor-list">
                    <div class="editor-row" v-for="(discount,index) in product.discounts" :key="index">
                      <div class="field"><label>达到数量</label><input v-model.number="discount.min_quantity" type="number" min="1"></div>
                      <div class="field"><label>折扣倍率</label><input v-model.number="discount.discount_rate" type="number" min="0.01" max="1" step="0.01"></div>
                      <button class="btn sm danger" @click="removeProductDiscount(product, index)">删除</button>
                    </div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveProduct(product)">保存商品设置</button>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['groups-list','groups-default'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'groups-default' ? '默认用户组' : '用户组管理' }}</h2>
                <p>{{ adminTab === 'groups-default' ? '选择新用户注册后默认加入的用户组。' : '新增或编辑用户组，配置门槛、加价、充值赠送与对接默认值。' }}</p>
              </div>
            </div>
            <div v-if="adminTab === 'groups-list'" class="grid-2">
              <div class="panel">
                <h3>{{ groupForm.id ? '编辑用户组' : '新增用户组' }}</h3>
                <div class="form-grid">
                  <div class="field"><label>用户组ID</label><input v-model.trim="groupForm.group_code" placeholder="例如 VIP"></div>
                  <div class="field"><label>用户组名称</label><input v-model.trim="groupForm.name" placeholder="名称"></div>
                  <div class="field full"><label>介绍说明</label><textarea v-model.trim="groupForm.description" placeholder="用户前台可见的介绍"></textarea></div>
                  <div class="field"><label>门槛模式</label><select v-model="groupForm.threshold_mode"><option value="none">无门槛</option><option value="total_recharge">累计充值</option><option value="total_consume">累计消费</option><option value="invite_count">邀请用户数</option><option value="balance">余额大于等于</option></select></div>
                  <div class="field"><label>门槛数额</label><input v-model.number="groupForm.threshold_value" type="number" min="0"><div v-if="['total_recharge','total_consume','balance'].includes(groupForm.threshold_mode)" class="amount-yuan">{{ yuanApprox(groupForm.threshold_value) }}</div></div>
                  <div class="field"><label>加价模式</label><select v-model="groupForm.markup_mode"><option value="fixed">固定加价</option><option value="percent">百分比加价</option></select></div>
                  <div class="field"><label>加价数额</label><input v-model.number="groupForm.markup_value" type="number" step="0.01"><div class="tiny">固定加价：上游价 1000、填 200，用户价为 1200；百分比加价：上游价 1000、填 20，用户价为 1200。请勿把 20% 写成 0.2。</div></div>
                  <div class="field"><label>充值赠送倍率</label><input v-model.number="groupForm.recharge_bonus_rate" type="number" min="0.01" step="0.01"><div class="tiny">倍率 1 表示不赠送；1.1 表示充值到账额度额外增加 10%。倍率越高，平台实际承担的赠送成本越高。</div></div>
                  <div class="field"><label>排序权重</label><input v-model.number="groupForm.sort_order" type="number"></div>
                  <div class="field"><label>余额不足时可降级</label><select v-model.number="groupForm.downgrade_on_balance"><option :value="0">否</option><option :value="1">是</option></select></div>
                  <div class="field"><label>默认允许对接</label><select v-model.number="groupForm.allow_api_default"><option :value="0">否</option><option :value="1">是</option></select></div>
                </div>
                <div class="section-gap">
                  <h4>商品固定价格</h4>
                  <p class="panel-sub">固定价格按商品计价单位填写，将替代本用户组的加价规则；未填写的商品继续按上方加价规则计算，商品数量折扣仍会生效。</p>
                  <div v-if="!adminState.products.length" class="placeholder-card" style="padding:14px">暂无商品，请先同步商品数据。</div>
                  <div v-else class="editor-list">
                    <div class="code-item" v-for="product in adminState.products" :key="product.id">
                      <div>
                        <strong>{{ product.name }}</strong>
                        <div class="tiny">每 {{ product.step_num }} 数量为一个计价单位 · 上游成本 {{ money(product.price_cost) }} {{ currency }}</div>
                      </div>
                      <div class="field">
                        <label>固定价格（留空则跟随加价）</label>
                        <input v-model="groupForm.product_prices[String(product.id)]" type="number" min="0" step="1" placeholder="留空">
                        <div v-if="groupForm.product_prices[String(product.id)] !== '' && groupForm.product_prices[String(product.id)] != null" class="amount-yuan">{{ yuanApprox(groupForm.product_prices[String(product.id)]) }}</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveGroup">保存用户组</button>
                  <button class="btn ghost" @click="resetGroupForm">清空表单</button>
                </div>
              </div>
              <div class="panel">
                <h3>用户组列表</h3>
                <div class="code-list">
                  <div class="code-item" v-for="group in adminState.groups" :key="group.id">
                    <div>
                      <strong>{{ group.name }}</strong>
                      <div class="tiny mono">{{ group.group_code }} · {{ thresholdModeLabel(group.threshold_mode) }} / {{ money(group.threshold_value) }} <span v-if="['total_recharge','total_consume','balance'].includes(group.threshold_mode)" class="amount-yuan inline">{{ yuanApprox(group.threshold_value) }}</span></div>
                    </div>
                    <div class="inline-actions">
                      <span class="badge" :class="group.is_default_register ? 'success' : 'info'">{{ group.is_default_register ? '默认注册组' : '普通组' }}</span>
                      <button class="btn sm ghost" @click="editGroup(group)">编辑</button>
                      <button class="btn sm primary" @click="setDefaultGroup(group)">设为默认</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="adminTab === 'groups-default'" class="panel">
              <h3>默认注册用户组</h3>
              <p class="panel-sub">新注册用户会自动加入所选用户组。修改后只影响后续注册用户，不会批量变更已有用户。</p>
              <div class="code-list section-gap">
                <div class="code-item" v-for="group in adminState.groups" :key="group.id">
                  <div><strong>{{ group.name }}</strong><div class="tiny mono">{{ group.group_code }}</div></div>
                  <div class="inline-actions"><span v-if="group.is_default_register" class="badge success">当前默认</span><button v-else class="btn sm primary" @click="setDefaultGroup(group)">设为默认</button></div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['users-list','users-create','api-keys'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ adminTab === 'users-create' ? (userForm.id ? '编辑用户' : '新增用户') : (adminTab === 'api-keys' ? 'API Key 管理' : '用户列表') }}</h2>
                <p>{{ adminTab === 'users-create' ? '创建或修改用户资料、余额、用户组和账号权限。' : (adminTab === 'api-keys' ? '查看所有已生成 API Key 的用户并为其重置密钥。' : '搜索、查看、封禁或删除系统用户。') }}</p>
              </div>
              <div v-if="adminTab === 'users-list'" class="search-row" style="max-width:320px;width:100%">
                <div class="field full" style="margin:0"><label>搜索用户</label><input v-model.trim="adminState.userKeyword" placeholder="用户名 / QQ / UID"></div>
              </div>
            </div>
            <div class="section-stack">
              <div v-if="adminTab === 'users-create'" class="panel">
                <h3>{{ userForm.id ? '编辑用户' : '新增用户' }}</h3>
                <div class="form-grid">
                  <div class="field"><label>用户名</label><input v-model.trim="userForm.username" placeholder="英文数字"></div>
                  <div class="field"><label>昵称</label><input v-model.trim="userForm.nickname" placeholder="昵称"></div>
                  <div class="field"><label>QQ号</label><input v-model.trim="userForm.qq" placeholder="QQ号"></div>
                  <div class="field"><label>邮箱</label><input v-model.trim="userForm.email" placeholder="邮箱"></div>
                  <div class="field"><label>手机号</label><input v-model.trim="userForm.mobile" placeholder="手机号"></div>
                  <div class="field"><label>密码</label><input v-model="userForm.password" type="password" :placeholder="userForm.id ? '留空则不修改密码' : '新增用户必须填写' "></div>
                  <div class="field"><label>余额</label><input v-model.number="userForm.balance" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(userForm.balance) }}</div></div>
                  <div class="field"><label>所属用户组</label><select v-model.number="userForm.user_group_id"><option v-for="group in adminState.groups" :key="group.id" :value="Number(group.id)">{{ group.name }}</option></select></div>
                  <div class="field"><label>账号状态</label><select v-model="userForm.status"><option value="active">正常</option><option value="banned">封禁</option></select></div>
                  <div class="field"><label>角色</label><select v-model="userForm.account_role" :disabled="userForm.account_role === 'owner'"><option v-if="userForm.account_role === 'owner'" value="owner">Owner（锁定）</option><option value="member">User</option><option value="agent">Agent</option><option v-if="isOwner" value="admin">Admin</option></select><div v-if="userForm.account_role === 'owner'" class="tiny">站长身份已锁定，前台与后台都不能在这里改成其他身份，也不能把其他用户改成站长。</div></div>
                  <div class="field"><label>对接策略</label><select v-model="userForm.connect_policy"><option value="default">跟随用户组</option><option value="agent">允许对接</option><option value="user">禁止对接</option></select><div class="tiny">选择“跟随用户组”时，是否允许对接由所属用户组的设置决定。</div></div>
                  <div class="field full" v-if="userForm.id">
                    <label>只读信息</label>
                    <div class="kv-box">
                      <div class="tiny">注册时间：{{ formatDate(userForm.created_at) }} ｜ 上次登录：{{ formatDate(userForm.last_login_at) }} ｜ 上次登录IP：{{ userForm.last_login_ip || '-' }} ｜ 邀请用户数：{{ userForm.invite_count || 0 }}</div>
                    </div>
                  </div>
                </div>
                <div class="inline-actions">
                  <button class="btn primary" @click="saveAdminUser">保存用户</button>
                  <button class="btn ghost" @click="resetUserForm">清空表单</button>
                </div>
              </div>
              <div v-if="adminTab === 'users-list'" class="panel">
                <div class="action-row">
                  <div><h3>用户列表</h3><p class="panel-sub">此列表包含普通用户、代理、管理员和站长。站长身份固定不可修改，任何人都不能把用户改为站长。</p></div>
                  <div class="inline-actions">
                    <button class="btn ghost" @click="loadAdminUsers(true)">刷新列表</button>
                  </div>
                </div>
                <div class="table-wrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>UID</th><th>用户</th><th>角色</th><th>余额</th><th>用户组</th><th>状态</th><th>操作</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="row in filteredAdminUsers" :key="row.id">
                        <td class="mono">{{ row.uid }}</td>
                        <td>
                          <div>{{ row.nickname || row.username }}</div>
                          <div class="compact">{{ row.username }} · QQ {{ row.qq || '-' }}</div>
                        </td>
                        <td>{{ row.role_label }}</td>
                        <td>{{ money(row.balance) }}<div class="amount-yuan">{{ yuanApprox(row.balance) }}</div></td>
                        <td>{{ row.group_name || '-' }}</td>
                        <td><span class="badge" :class="row.status==='active' ? 'success' : 'danger'">{{ row.status }}</span></td>
                        <td class="actions-cell">
                          <button class="btn sm ghost" @click="editUser(row)" :disabled="!isOwner && ['owner','admin'].includes(String(row.account_role || ''))">编辑</button>
                          <button class="btn sm warning" @click="resetUserApiKey(row)" :disabled="connectPolicyOf(row) !== 'agent'">重置Key</button>
                          <button class="btn sm danger" @click="softDeleteUser(row)" :disabled="String(row.account_role || '') === 'owner' || (!isOwner && String(row.account_role || '') === 'admin')">删除</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-if="adminTab === 'api-keys'" class="panel">
                <div class="action-row"><div><h3>已生成 API Key 的用户</h3><p class="panel-sub">这里显示所有拥有 API Key 的用户，包括普通用户、代理、管理员和站长，不受当前对接策略影响。</p></div><button class="btn ghost" @click="loadAdminUsers(true, true)">刷新列表</button></div>
                <div class="table-wrap section-gap"><table class="table"><thead><tr><th>用户</th><th>身份</th><th>策略</th><th>UID</th><th>API Key</th><th>操作</th></tr></thead><tbody><tr v-for="row in apiKeyUsers" :key="row.id"><td>{{ row.nickname || row.username }}</td><td>{{ row.role_label || row.account_role }}</td><td>{{ connectPolicyLabel(connectPolicyOf(row)) }}</td><td class="mono">{{ row.uid }}</td><td class="wrap mono">{{ row.api_key }}</td><td><button class="btn sm warning" @click="resetUserApiKey(row)">重置 Key</button></td></tr><tr v-if="!apiKeyUsers.length"><td colspan="6" class="muted">暂无已生成 API Key 的用户</td></tr></tbody></table></div>
              </div>
            </div>
          </div>

          <div v-else-if="adminTab === 'orders-list'">
            <div class="page-head">
              <div>
                <h2>订单管理</h2>
                <p>可同步所有未完成订单状态，并执行向上游补单、退单或给用户仅退款。</p>
              </div>
              <div class="inline-actions">
                <button class="btn primary" @click="syncAdminOrders">更新速刷订单</button>
                <button class="btn ghost" @click="loadAdminOrders(true)">刷新列表</button>
              </div>
            </div>
            <div class="panel">
              <div class="action-row">
                <div><h3>订单查单</h3><p class="panel-sub">输入系统订单号或上游订单号，系统会查询订单并同步可更新的上游状态。</p></div>
              </div>
              <div class="search-row section-gap">
                <div class="field" style="margin:0"><label>订单号</label><input v-model.trim="adminState.orderSearch" placeholder="系统订单号 / 上游订单号" @keyup.enter="searchAdminOrder()"></div>
                <button class="btn primary" @click="searchAdminOrder()">查单</button>
                <button v-if="adminState.orderDetail" class="btn ghost" @click="clearAdminOrderDetail">清除结果</button>
              </div>
              <div v-if="adminState.orderDetail" class="order-summary-box section-gap">
                <div class="order-summary-grid">
                  <div class="subtle"><span>系统订单号</span><strong class="mono wrap">{{ adminState.orderDetail.display_order_no || adminState.orderDetail.order_no }}</strong></div>
                  <div class="subtle"><span>上游订单号</span><strong class="mono wrap">{{ adminState.orderDetail.upstream_order_no || '-' }}</strong></div>
                  <div class="subtle"><span>用户</span><strong>{{ adminState.orderDetail.nickname || adminState.orderDetail.username || ('#' + adminState.orderDetail.user_id) }}</strong></div>
                  <div class="subtle"><span>状态</span><strong><span class="badge" :class="badgeTone(adminState.orderDetail.state)">{{ adminState.orderDetail.state }}</span></strong></div>
                  <div class="subtle"><span>商品</span><strong>{{ adminState.orderDetail.product_name || '-' }}</strong></div>
                  <div class="subtle"><span>下单 QQ</span><strong>{{ adminState.orderDetail.target_qq || '-' }}</strong></div>
                  <div class="subtle"><span>数量</span><strong>{{ adminState.orderDetail.quantity }}</strong></div>
                  <div class="subtle"><span>进度（开始 / 当前 / 结束）</span><strong>{{ adminState.orderDetail.start_num ?? '-' }} / {{ adminState.orderDetail.current_num ?? '-' }} / {{ adminState.orderDetail.finish_num ?? '-' }}</strong></div>
                  <div class="subtle"><span>用户花费</span><strong>{{ money(adminState.orderDetail.user_price) }} <small class="amount-yuan">{{ yuanApprox(adminState.orderDetail.user_price) }}</small></strong></div>
                  <div class="subtle"><span>成本 / 利润</span><strong>{{ money(adminState.orderDetail.cost_price) }} / {{ money(adminState.orderDetail.profit) }}</strong></div>
                  <div class="subtle"><span>创建时间</span><strong>{{ formatDate(adminState.orderDetail.created_at) }}</strong></div>
                  <div class="subtle"><span>最后同步</span><strong>{{ formatDate(adminState.orderDetail.last_sync_at || adminState.orderDetail.updated_at) }}</strong></div>
                </div>
                <div class="tip-box section-gap"><div class="note-strong">备注：{{ adminState.orderDetail.latest_message || '无' }}</div></div>
              </div>
            </div>
            <div class="panel">
              <div class="table-wrap">
                <table class="table">
                  <thead>
                    <tr>
                      <th>系统订单号</th>
                      <th>上游订单号</th>
                      <th>用户</th>
                      <th>商品</th>
                      <th>状态</th>
                      <th>用户花费</th>
                      <th>成本</th>
                      <th>利润</th>
                      <th>备注</th>
                      <th>操作</th>
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
                        <button class="btn sm primary" @click="showAdminOrderDetail(row)">查单</button>
                        <button class="btn sm warning" :disabled="!row.can_retry" @click="adminRetryOrder(row)">补单</button>
                        <button class="btn sm danger" :disabled="!row.can_refund" @click="adminRefundOrder(row,false)">退单</button>
                        <button class="btn sm ghost" @click="adminRefundOrder(row,true)">仅退款</button>
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
                <h2>{{ adminTab === 'api-conditions' ? 'API Key 生成条件' : '上游管理' }}</h2>
                <p>{{ adminTab === 'api-conditions' ? '设置用户自行生成 API Key 前必须满足的条件；是否允许对接仍由用户组和后台策略综合决定。' : '配置上游账号、查看当前上游余额并检查上游 allow 状态。' }}</p>
              </div>
            </div>
            <div v-if="adminTab === 'api-conditions'" class="panel">
              <h3>API Key 生成条件</h3>
              <div class="form-grid section-gap">
                <div class="field"><label>判定字段</label><select v-model="apiSettings.api_condition_mode"><option value="total_consume">累计消费</option><option value="total_recharge">累计充值</option><option value="balance">余额</option><option value="invite_count">邀请用户数</option></select></div>
                <div class="field"><label>运算符</label><select v-model="apiSettings.api_condition_operator"><option value=">=">大于等于</option><option value=">">大于</option><option value="<=">小于等于</option><option value="<">小于</option><option value="=">等于</option></select></div>
                <div class="field full"><label>阈值</label><input v-model.trim="apiSettings.api_condition_value" type="number" min="0"><div v-if="['total_recharge','total_consume','balance'].includes(apiSettings.api_condition_mode)" class="amount-yuan">{{ yuanApprox(apiSettings.api_condition_value) }}</div></div>
              </div>
              <div class="auth-footnote">默认条件为累计充值大于等于 0 额度。满足这里只代表可以生成 API Key，不代表一定允许接口下单。</div>
              <div class="inline-actions"><button class="btn primary" @click="saveApiCondition">保存条件设置</button></div>
            </div>
            <div v-if="adminTab === 'upstream-manage'" class="panel">
              <div class="action-row">
                <div><h3>上游状态与配置</h3></div>
                <button class="btn ghost" @click="refreshUpstreamBalance(false)">刷新上游余额</button>
              </div>
              <div class="stats-grid compact-stats section-gap">
                <div class="stat"><small>当前上游余额</small><strong>{{ adminState.upstreamBalance === null ? '获取失败' : money(adminState.upstreamBalance) }}</strong><span v-if="adminState.upstreamBalance !== null" class="amount-yuan">{{ yuanApprox(adminState.upstreamBalance) }}</span></div>
              </div>
              <div v-if="adminState.upstreamBalanceError" class="auth-footnote danger-note section-gap">{{ adminState.upstreamBalanceError }}</div>
              <div class="divider"></div>
              <h4>{{ upstreamForm.id ? '编辑上游' : '新增上游' }}</h4>
              <div class="form-grid section-gap">
                <div class="field"><label>名称</label><input v-model.trim="upstreamForm.name" placeholder="默认上游"></div>
                <div class="field"><label>基础地址</label><input v-model.trim="upstreamForm.base_url" placeholder="https://example.com"></div>
                <div class="field"><label>上游 UID</label><input v-model.number="upstreamForm.upstream_uid" type="number" min="1"></div>
                <div class="field"><label>上游 API Key</label><input v-model.trim="upstreamForm.upstream_api_key" placeholder="编辑已有上游时留空则不修改"></div>
                <div class="field"><label>启用</label><select v-model.number="upstreamForm.enabled"><option :value="1">是</option><option :value="0">否</option></select></div>
                <div class="field"><label>设为默认</label><select v-model.number="upstreamForm.is_default"><option :value="1">是</option><option :value="0">否</option></select></div>
              </div>
              <div class="inline-actions"><button class="btn primary" @click="saveUpstream">保存上游配置</button><button class="btn ghost" @click="resetUpstreamForm">清空表单</button></div>
              <div class="divider"></div>
              <div class="action-row"><h4>上游列表</h4><button class="btn ghost" @click="loadAdminUpstream(true)">刷新列表</button></div>
              <div class="code-list section-gap">
                <div class="code-item" v-for="row in adminState.upstream" :key="row.id">
                  <div><strong>{{ row.name }}</strong><div class="tiny mono">{{ row.base_url }} · UID {{ row.upstream_uid }}</div></div>
                  <div class="inline-actions"><span class="badge" :class="row.enabled ? 'success' : 'danger'">{{ row.enabled ? '启用' : '停用' }}</span><span class="badge" :class="row.is_default ? 'info' : 'warning'">{{ row.is_default ? '默认上游' : '普通上游' }}</span><button class="btn sm ghost" @click="editUpstream(row)">编辑</button></div>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="['cards-generate','cards-list','payments-merchants','payments-channels','recharge-orders'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ {'cards-generate':'卡密生成','cards-list':'卡密列表','payments-merchants':'易支付商户','payments-channels':'支付通道','recharge-orders':'充值订单'}[adminTab] }}</h2>
              </div>
            </div>
            <div v-if="adminTab === 'cards-generate'" class="panel">
              <h3>生成充值卡密</h3>
              <div class="form-grid section-gap">
                <div class="field"><label>生成数量</label><input v-model.number="cardGenForm.count" type="number" min="1" :disabled="!!cardGenForm.custom_code"></div>
                <div class="field"><label>充值额度</label><input v-model.number="cardGenForm.amount" type="number" min="1"><div class="amount-yuan">{{ yuanApprox(cardGenForm.amount) }}</div></div>
                <div class="field"><label>可用次数</label><input v-model.number="cardGenForm.uses" type="number" min="-1"></div>
                <div class="field"><label>随机前缀</label><input v-model.trim="cardGenForm.prefix" placeholder="可选" :disabled="!!cardGenForm.custom_code"></div>
                <div class="field full"><label>自定义卡密内容（可选）</label><input v-model.trim="cardGenForm.custom_code" placeholder="填写后将按该内容生成单张卡密"></div>
                <div class="field full"><label>备注</label><input v-model.trim="cardGenForm.note" placeholder="备注"></div>
              </div>
              <div class="auth-footnote">一次卡可设置为 1 次，多次通兑卡可设置固定次数，-1 表示无限制，0 表示已不可兑换。自定义卡密内容保持大小写敏感。</div>
              <div class="inline-actions"><button class="btn primary" @click="generateCards">批量生成卡密</button></div>
            </div>
            <div v-if="adminTab === 'cards-list'" class="panel">
              <div class="action-row"><h3>卡密列表</h3><button class="btn ghost" @click="loadAdminCards(true)">刷新列表</button></div>
              <div class="table-wrap section-gap"><table class="table"><thead><tr><th>卡密</th><th>额度</th><th>总次数</th><th>剩余次数</th><th>启用</th><th>操作</th></tr></thead><tbody><tr v-for="row in adminState.cards" :key="row.id"><td class="mono wrap">{{ row.code }}</td><td>{{ money(row.amount) }}<div class="amount-yuan">{{ yuanApprox(row.amount) }}</div></td><td>{{ row.total_uses }}</td><td>{{ row.remaining_uses }}</td><td>{{ row.enabled ? '是' : '否' }}</td><td class="actions-cell"><button class="btn sm ghost" @click="editCardInline(row)">编辑</button><button class="btn sm danger" @click="destroyCard(row)">销毁</button></td></tr></tbody></table></div>
              <div v-if="cardEditForm.id" class="section-gap"><div class="divider"></div><h4>编辑卡密</h4><div class="form-grid"><div class="field full"><label>卡密内容</label><input v-model.trim="cardEditForm.code"></div><div class="field"><label>额度</label><input v-model.number="cardEditForm.amount" type="number"><div class="amount-yuan">{{ yuanApprox(cardEditForm.amount) }}</div></div><div class="field"><label>总次数</label><input v-model.number="cardEditForm.total_uses" type="number"></div><div class="field"><label>剩余次数</label><input v-model.number="cardEditForm.remaining_uses" type="number"></div><div class="field"><label>启用</label><select v-model.number="cardEditForm.enabled"><option :value="1">是</option><option :value="0">否</option></select></div><div class="field full"><label>备注</label><input v-model.trim="cardEditForm.note"></div></div><div class="inline-actions"><button class="btn primary" @click="saveCard">保存卡密</button><button class="btn ghost" @click="resetCardEditForm">取消编辑</button></div></div>
            </div>
            <div v-if="adminTab === 'payments-merchants'" class="panel">
              <h3>{{ merchantForm.id ? '编辑易支付商户' : '新增易支付商户' }}</h3>
              <div class="form-grid section-gap"><div class="field"><label>名称</label><input v-model.trim="merchantForm.name"></div><div class="field"><label>易支付地址</label><input v-model.trim="merchantForm.endpoint" placeholder="https://pay.example.com"></div><div class="field"><label>商户ID</label><input v-model.trim="merchantForm.pid"></div><div class="field"><label>商户密钥</label><input v-model.trim="merchantForm.merchant_key" placeholder="编辑已有商户时可留空不改"></div><div class="field"><label>启用</label><select v-model.number="merchantForm.enabled"><option :value="1">是</option><option :value="0">否</option></select></div></div>
              <div class="inline-actions"><button class="btn primary" @click="saveMerchant">保存商户</button><button class="btn ghost" @click="resetMerchantForm">清空表单</button></div><div class="divider"></div>
              <h4>商户列表</h4><div class="code-list section-gap"><div class="code-item" v-for="row in adminState.payments.merchants" :key="row.id"><div><strong>{{ row.name }}</strong><div class="tiny mono">{{ row.endpoint }} · PID {{ row.pid }}</div></div><div class="inline-actions"><span class="badge" :class="row.enabled ? 'success' : 'danger'">{{ row.enabled ? '启用' : '停用' }}</span><button class="btn sm ghost" @click="editMerchant(row)">编辑</button></div></div></div>
            </div>
            <div v-if="adminTab === 'payments-channels'" class="panel">
              <h3>{{ channelForm.id ? '编辑支付通道' : '新增支付通道' }}</h3>
              <div class="form-grid section-gap"><div class="field"><label>通道编码</label><input v-model.trim="channelForm.code" placeholder="wechat"></div><div class="field"><label>通道名称</label><input v-model.trim="channelForm.name" placeholder="微信支付"></div><div class="field"><label>pay_type</label><input v-model.trim="channelForm.pay_type" placeholder="wxpay / alipay"></div><div class="field"><label>易支付商户</label><select v-model.number="channelForm.merchant_id"><option v-for="m in adminState.payments.merchants" :key="m.id" :value="Number(m.id)">{{ m.name }}</option></select></div><div class="field"><label>排序</label><input v-model.number="channelForm.sort_order" type="number"></div><div class="field"><label>启用</label><select v-model.number="channelForm.enabled"><option :value="1">是</option><option :value="0">否</option></select></div></div>
              <div class="inline-actions"><button class="btn primary" @click="saveChannel">保存通道</button><button class="btn ghost" @click="resetChannelForm">清空表单</button></div><div class="divider"></div>
              <div class="table-wrap"><table class="table"><thead><tr><th>编码</th><th>名称</th><th>pay_type</th><th>商户ID</th><th>状态</th><th>操作</th></tr></thead><tbody><tr v-for="row in adminState.payments.channels" :key="row.id"><td class="mono">{{ row.code }}</td><td>{{ row.name }}</td><td>{{ row.pay_type }}</td><td>{{ row.merchant_id }}</td><td>{{ row.enabled ? '启用' : '停用' }}</td><td><button class="btn sm ghost" @click="editChannel(row)">编辑</button></td></tr></tbody></table></div>
            </div>
            <div v-if="adminTab === 'recharge-orders'" class="panel">
              <div class="action-row"><h3>充值订单列表</h3><button class="btn ghost" @click="loadAdminRecharge(true)">刷新列表</button></div>
              <div class="table-wrap section-gap"><table class="table"><thead><tr><th>订单号</th><th>用户ID</th><th>通道</th><th>支付金额</th><th>到账额度</th><th>赠送</th><th>状态</th><th>时间</th></tr></thead><tbody><tr v-for="row in adminState.payments.recharge_orders" :key="row.id"><td class="mono">{{ row.order_no }}</td><td>{{ row.user_id }}</td><td>{{ row.channel_id }}</td><td>{{ row.money_yuan }} 元</td><td>{{ money(row.credit_amount) }}<div class="amount-yuan">{{ yuanApprox(row.credit_amount) }}</div></td><td>{{ money(row.bonus_amount) }}<div class="amount-yuan">{{ yuanApprox(row.bonus_amount) }}</div></td><td><span class="badge" :class="badgeTone(row.status)">{{ row.status }}</span></td><td>{{ formatDate(row.created_at) }}</td></tr></tbody></table></div>
            </div>
          </div>

          <div v-else-if="['settings-basic','settings-theme','settings-sms','settings-security','settings-custom','settings-version','scheduled-tasks','exchange-rules'].includes(adminTab)">
            <div class="page-head">
              <div>
                <h2>{{ {'settings-basic':'基础设置','settings-theme':'界面主题','settings-sms':'短信 / 邮件 / 验证','settings-security':'登录 / 注册 / 邀请','settings-custom':'自定义 CSS / JS','settings-version':'版本与更新','scheduled-tasks':'定时任务 API','exchange-rules':'商品兑换码规则'}[adminTab] }}</h2>
                <p>当前页面仅显示所选设置分类，保存时会保留其他分类的现有配置。</p>
              </div>
              <div class="inline-actions">
                <button v-if="!['scheduled-tasks','settings-version'].includes(adminTab)" class="btn primary" @click="saveSettings">保存系统设置</button>
                <button class="btn ghost" @click="reloadCurrentSettingsPage">{{ adminTab === 'settings-version' ? '重新检测' : '重新加载' }}</button>
              </div>
            </div>
            <div class="section-stack">
              <div v-if="adminTab === 'settings-basic'" class="panel">
                <h3>站点与 SEO</h3>
                <div class="form-grid">
                  <div class="field"><label>站点名称</label><input v-model.trim="settingsForm.site_name"></div>
                  <div class="field"><label>后台路径</label><input v-model.trim="settingsForm.admin_path" placeholder="/admin"></div>
                  <div class="field"><label>网站关键字</label><input v-model.trim="settingsForm.site_keywords"></div>
                  <div class="field"><label>站点描述</label><input v-model.trim="settingsForm.site_description"></div>
                  <div class="field"><label>网站图标</label><input v-model.trim="settingsForm.site_favicon" placeholder="favicon URL"></div>
                  <div class="field"><label>站点 Logo</label><input v-model.trim="settingsForm.site_logo" placeholder="logo URL"></div>
                  <div class="field"><label>用户交流群 QQ</label><input v-model.trim="settingsForm.community_group_qq" placeholder="例如 1081888821"></div>
                  <div class="field"><label>售后群 QQ</label><input v-model.trim="settingsForm.support_group_qq" placeholder="例如 1081888821"></div>
                  <div class="field full">
                    <label>系统站长交流群</label>
                    <div class="inline-actions">
                      <span class="code-inline">{{ ownerFeedbackGroupQq }}</span>
                      <button type="button" class="btn ghost" @click="openGroup('owner_feedback')">加入站长交流群</button>
                    </div>
                  </div>
                  <div class="field"><label>ICP备案号</label><input v-model.trim="settingsForm.icp_beian_no" placeholder="例如 粤ICP备12345678号"></div>
                  <div class="field"><label>网安备案号</label><input v-model.trim="settingsForm.public_security_beian_no" placeholder="例如 粤公网安备 44000000000000号"></div>
                  <div class="field full"><label>SEO 页脚</label><textarea v-model.trim="settingsForm.seo_footer"></textarea></div>
                  <div class="field"><label>额度名称</label><input v-model.trim="settingsForm.currency_name" placeholder="如 速刷币"></div>
<div class="field"><label>首页模板</label><select v-model="settingsForm.home_template"><option value="default">默认模板</option><option value="modern">现代风格</option><option value="minimal">极简风格</option><option value="business">商务风格</option></select><div class="tiny">切换后刷新首页查看效果。</div></div>
                  <div class="field"><label>说说图片来源</label><select v-model="settingsForm.feed_image_mode"><option value="self_proxy">自己服务器代理</option><option value="upstream_proxy">上游代理链接</option></select></div>
                </div>
                <div class="auth-footnote section-gap">备案号会显示在系统首页底部中央。ICP备案号会链接到 <span class="code-inline">https://beian.miit.gov.cn</span>，请务必填写工信部实际核发编号；未悬挂、填写错误或链接不正确可能面临责令整改、罚款或备案注销风险。网安备案号请填写实际核发内容。</div>
              </div>

              <div v-if="adminTab === 'settings-sms'" class="panel">
                <h3>邮箱设置（SMTP）</h3>
                <div class="form-grid">
                  <div class="field"><label>SMTP Host</label><input v-model.trim="settingsForm.smtp_config.host"></div>
                  <div class="field"><label>端口</label><input v-model.number="settingsForm.smtp_config.port" type="number"></div>
                  <div class="field"><label>用户名</label><input v-model.trim="settingsForm.smtp_config.username"></div>
                  <div class="field"><label>密码</label><input v-model.trim="settingsForm.smtp_config.password"></div>
                  <div class="field"><label>加密方式</label><select v-model="settingsForm.smtp_config.encryption"><option value="ssl">SSL</option><option value="tls">TLS</option><option value="">无</option></select></div>
                  <div class="field"><label>发件邮箱</label><input v-model.trim="settingsForm.smtp_config.from"></div>
                  <div class="field full"><label>发件人名称</label><input v-model.trim="settingsForm.smtp_config.from_name"></div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-sms'" class="panel">
                <h3>短信设置</h3>
                <div class="form-grid">
                  <div class="field full"><label>短信服务商</label><select v-model="settingsForm.sms_provider"><option value="tencent">腾讯云</option><option value="aliyun">阿里云</option><option value="custom_http">自定义 HTTP</option></select></div>
                </div>
                <div v-if="settingsForm.sms_provider === 'tencent'" class="form-grid section-gap">
                  <div class="field"><label>SecretId</label><input v-model.trim="settingsForm.sms_config.secret_id"></div>
                  <div class="field"><label>SecretKey</label><input v-model.trim="settingsForm.sms_config.secret_key"></div>
                  <div class="field"><label>SDK App ID</label><input v-model.trim="settingsForm.sms_config.sdk_app_id"></div>
                  <div class="field"><label>模板 ID</label><input v-model.trim="settingsForm.sms_config.template_id"></div>
                  <div class="field"><label>地域</label><input v-model.trim="settingsForm.sms_config.region"></div>
                  <div class="field full"><label>说明</label><div class="tiny">腾讯云短信签名由程序按接入方式自动处理，这里只需要填写密钥、模板和地域即可。</div></div>
                </div>
                <div v-else-if="settingsForm.sms_provider === 'aliyun'" class="form-grid section-gap">
                  <div class="field"><label>AccessKeyId</label><input v-model.trim="settingsForm.sms_config.access_key_id"></div>
                  <div class="field"><label>AccessKeySecret</label><input v-model.trim="settingsForm.sms_config.access_key_secret"></div>
                  <div class="field"><label>模板 Code</label><input v-model.trim="settingsForm.sms_config.template_code"></div>
                  <div class="field"><label>地域</label><input v-model.trim="settingsForm.sms_config.region"></div>
                  <div class="field"><label>Endpoint</label><input v-model.trim="settingsForm.sms_config.endpoint"></div>
                  <div class="field full"><label>说明</label><div class="tiny">阿里云短信签名由程序按接入方式自动处理，这里只需要填写密钥、模板与地域配置即可。</div></div>
                </div>
                <div v-else class="section-gap section-stack">
                  <div class="form-grid">
                    <div class="field"><label>请求地址</label><input v-model.trim="settingsForm.sms_config.url"></div>
                    <div class="field"><label>请求方法</label><select v-model="settingsForm.sms_config.method"><option value="POST">POST</option><option value="GET">GET</option></select></div>
                    <div class="field"><label>成功字段</label><input v-model.trim="settingsForm.sms_config.success_field"></div>
                    <div class="field"><label>成功值</label><input v-model.trim="settingsForm.sms_config.success_value"></div>
                  </div>
                  <div class="grid-3">
                    <div class="panel" style="margin:0;padding:18px">
                      <div class="card-title"><h4>Headers</h4><button class="btn sm ghost" @click="addPair(settingsForm.sms_headers_rows)">新增</button></div>
                      <div class="editor-list" v-if="settingsForm.sms_headers_rows.length">
                        <div class="editor-row" v-for="(row,index) in settingsForm.sms_headers_rows" :key="index"><div class="field"><label>Key</label><input v-model.trim="row.key"></div><div class="field"><label>Value</label><input v-model.trim="row.value"></div><button class="btn sm danger" @click="removePair(settingsForm.sms_headers_rows,index)">删</button></div>
                      </div>
                      <div v-else class="placeholder-card" style="padding:12px">暂无 Header。</div>
                    </div>
                    <div class="panel" style="margin:0;padding:18px">
                      <div class="card-title"><h4>Query</h4><button class="btn sm ghost" @click="addPair(settingsForm.sms_query_rows)">新增</button></div>
                      <div class="editor-list" v-if="settingsForm.sms_query_rows.length">
                        <div class="editor-row" v-for="(row,index) in settingsForm.sms_query_rows" :key="index"><div class="field"><label>Key</label><input v-model.trim="row.key"></div><div class="field"><label>Value</label><input v-model.trim="row.value"></div><button class="btn sm danger" @click="removePair(settingsForm.sms_query_rows,index)">删</button></div>
                      </div>
                      <div v-else class="placeholder-card" style="padding:12px">暂无 Query 参数。</div>
                    </div>
                    <div class="panel" style="margin:0;padding:18px">
                      <div class="card-title"><h4>Body</h4><button class="btn sm ghost" @click="addPair(settingsForm.sms_body_rows)">新增</button></div>
                      <div class="editor-list" v-if="settingsForm.sms_body_rows.length">
                        <div class="editor-row" v-for="(row,index) in settingsForm.sms_body_rows" :key="index"><div class="field"><label>Key</label><input v-model.trim="row.key"></div><div class="field"><label>Value</label><input v-model.trim="row.value"></div><button class="btn sm danger" @click="removePair(settingsForm.sms_body_rows,index)">删</button></div>
                      </div>
                      <div v-else class="placeholder-card" style="padding:12px">暂无 Body 参数。</div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-sms'" class="panel">
                <h3>极验设置</h3>
                <div class="form-grid">
                  <div class="field"><label>Captcha ID</label><input v-model.trim="settingsForm.geetest_config.captcha_id"></div>
                  <div class="field"><label>Captcha Key</label><input v-model.trim="settingsForm.geetest_config.captcha_key"></div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-security'" class="panel">
                <h3>登录 / 注册设置</h3>
                <div class="form-grid">
                  <div class="field"><label>前台下单</label><select v-model.number="settingsForm.frontend_order_enabled"><option :value="1">开启</option><option :value="0">关闭</option></select></div>
                  <div class="field"><label>接口下单</label><select v-model.number="settingsForm.api_order_enabled"><option :value="1">开启</option><option :value="0">关闭</option></select></div>
                  <div class="field"><label>注册需邮箱</label><select v-model.number="settingsForm.register_need_email"><option :value="0">否</option><option :value="1">是</option></select></div>
                  <div class="field"><label>注册需手机号</label><select v-model.number="settingsForm.register_need_mobile"><option :value="0">否</option><option :value="1">是</option></select></div>
                  <div class="field"><label>注册图片验证码</label><select v-model.number="settingsForm.register_need_image_captcha"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>注册极验</label><select v-model.number="settingsForm.register_need_geetest"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>注册短信验证</label><select v-model.number="settingsForm.register_need_sms_code"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>注册邮件验证</label><select v-model.number="settingsForm.register_need_email_code"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>登录短信验证</label><select v-model.number="settingsForm.login_need_sms"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>登录邮件验证</label><select v-model.number="settingsForm.login_need_email"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>登录极验</label><select v-model.number="settingsForm.login_need_geetest"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>登录图片验证码</label><input value="统一登录强制开启图片验证码" readonly></div>
                  <div class="field"><label>默认注册策略：User</label><select v-model.number="settingsForm.default_register_strategy_user"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                  <div class="field"><label>默认注册策略：Agent</label><select v-model.number="settingsForm.default_register_strategy_agent"><option :value="0">关闭</option><option :value="1">开启</option></select></div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-security'" class="panel">
                <h3>邀请设置与邀请码价格</h3>
                <div class="form-grid">
                  <div class="field"><label>有效邀请条件</label><select v-model="settingsForm.invite_valid_mode"><option value="total_consume">被邀请用户累计消费</option><option value="total_recharge">被邀请用户累计充值</option><option value="invite_count">邀请用户数</option><option value="balance">余额大于等于</option></select></div>
                  <div class="field"><label>条件数值</label><input v-model.trim="settingsForm.invite_valid_value" type="number" min="0"></div>
                  <div class="field"><label>余额达标不足时可降级</label><select v-model.number="settingsForm.balance_downgrade_enabled"><option :value="0">否</option><option :value="1">是</option></select></div>
                </div>
                <div class="section-gap">
                  <div class="switch-inline"><label><input type="radio" value="fixed" v-model="settingsForm.invite_code_price_rules.mode"> 固定价格</label><label><input type="radio" value="length" v-model="settingsForm.invite_code_price_rules.mode"> 按长度定价</label></div>
                </div>
                <div v-if="settingsForm.invite_code_price_rules.mode === 'fixed'" class="form-grid section-gap">
                  <div class="field full"><label>固定价格</label><input v-model.number="settingsForm.invite_code_price_rules.fixed" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(settingsForm.invite_code_price_rules.fixed) }}</div></div>
                </div>
                <div v-else class="section-gap section-stack">
                  <div class="inline-actions"><button class="btn sm ghost" @click="addInviteRule">新增长度规则</button></div>
                  <div class="editor-list" v-if="settingsForm.invite_code_price_rules.length_rules.length">
                    <div class="editor-row" v-for="(rule,index) in settingsForm.invite_code_price_rules.length_rules" :key="index"><div class="field"><label>长度 / 区间</label><input v-model.trim="rule.length" placeholder="例如 6、6-12、6~12"></div><div class="field"><label>价格</label><input v-model.number="rule.price" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(rule.price) }}</div></div><button class="btn sm danger" @click="removeInviteRule(index)">删除</button></div>
                  </div>
                  <div v-else class="placeholder-card">暂无长度价格规则。</div>
                  <div class="tiny">支持输入 6、6-12 或 6~12，默认为闭区间；未单独配置的长度将回退到固定价格字段。</div>
                </div>
              </div>

              <div v-if="adminTab === 'exchange-rules'" class="panel">
                <h3>商品兑换码规则</h3>
                <div class="form-grid section-gap">
                  <div class="field"><label>启用商品兑换码</label><select v-model.number="settingsForm.exchange_code_enabled"><option :value="1">启用</option><option :value="0">停用</option></select></div>
                  <div class="field"><label>每张生成服务费</label><input v-model.number="settingsForm.exchange_code_generation_fee" type="number" min="0"><div class="amount-yuan">{{ yuanApprox(settingsForm.exchange_code_generation_fee) }}</div></div>
                  <div class="field"><label>系统自定义前缀</label><input v-model.trim="settingsForm.exchange_code_prefix" maxlength="64" placeholder="例如 XM"></div>
                  <div class="field"><label>随机字符串长度</label><input v-model.number="settingsForm.exchange_code_random_length" type="number" min="8" max="256"><div class="tiny">允许 8～256 位；最终兑换码不足 48 位时系统会自动补齐。</div></div>
                  <div class="field full"><label>兑换码格式</label><input v-model.trim="settingsForm.exchange_code_format" placeholder="{prefix}{random}{uid}"><div class="tiny">支持组件：<span class="code-inline">{prefix}</span> 系统前缀、<span class="code-inline">{random}</span> 随机字符串、<span class="code-inline">{uid}</span> 用户 UID。可自由调整顺序和组合。</div></div>
                  <div class="field"><label>兑换订单 Cookie 有效期（天）</label><input v-model.number="settingsForm.exchange_code_cookie_days" type="number" min="7" max="3650"><div class="tiny">允许 7～3650 天，默认 60 天。</div></div>
                </div>
                <div class="auth-footnote">生成兑换码时按“每张生成服务费 × 数量”收取服务费，默认 0 额度。兑换成功后，商品费用从兑换码创建者账户扣除，公式为：数量 ÷ 计价单位（最低步长） × 用户价格。系统内部按数据库唯一用户 ID 记账，不依赖可能重复的公开 UID。</div>
              </div>

              <div v-if="adminTab === 'settings-custom'" class="panel">
                <h3>自定义页面样式与脚本</h3>
                <div class="placeholder-card section-gap"><strong>安全提示</strong><div class="tiny">自定义 JavaScript 和第三方资源可以访问当前页面数据。请仅使用自己编写或确认可信的代码与资源链接；错误代码可能导致页面无法正常使用。</div></div>
                <div class="form-grid section-gap">
                  <div class="field full"><label>自定义 CSS</label><textarea v-model="settingsForm.custom_css" class="custom-code-editor" spellcheck="false" placeholder=".panel { border-radius: 20px; }"></textarea><div class="tiny">保存后应用到系统页面。请优先使用现有主题变量，避免硬编码颜色。</div></div>
                  <div class="field full"><label>自定义 JavaScript</label><textarea v-model="settingsForm.custom_js" class="custom-code-editor" spellcheck="false" placeholder="document.documentElement.classList.add('my-effect');"></textarea><div class="tiny">脚本会在页面主体加载完成后执行，最大 200000 字节。</div></div>
                </div>
                <div class="section-gap"><div class="card-title"><div><h3>第三方资源链接</h3><div class="tiny">仅允许 HTTP/HTTPS 链接，最多 20 条；按列表顺序加载。</div></div><button type="button" class="btn sm ghost" @click="addCustomResource">新增资源</button></div>
                  <div v-if="settingsForm.custom_resource_urls.length" class="editor-list section-gap"><div class="editor-row custom-resource-row" v-for="(resource,index) in settingsForm.custom_resource_urls" :key="index"><div class="field"><label>资源类型</label><select v-model="resource.type"><option value="css">CSS</option><option value="js">JavaScript</option></select></div><div class="field custom-resource-url"><label>资源链接</label><input v-model.trim="resource.url" type="url" placeholder="https://cdn.example.com/library.min.css"></div><button type="button" class="btn sm danger" @click="removeCustomResource(index)">删除</button></div></div>
                  <div v-else class="placeholder-card section-gap">尚未添加第三方资源。</div>
                </div>
              </div>

              <div v-if="adminTab === 'settings-version'" class="panel">
                <div class="card-title"><div><h3>版本与在线更新</h3><div class="tiny">管理员每次进入后台时自动检测一次远程版本。</div></div><span class="badge" :class="adminState.version.has_update ? 'warning' : 'success'">{{ adminState.version.has_update ? '发现新版本' : '当前版本' }}</span></div>
                <div class="info-grid section-gap"><div class="kv-box"><span class="tiny">当前版本</span><strong>{{ (adminState.version.current && adminState.version.current.version) || currentVersion.version || 'v1.0.0' }}</strong></div><div class="kv-box"><span class="tiny">远程版本</span><strong>{{ (adminState.version.remote && adminState.version.remote.version) || '未获取' }}</strong></div><div class="kv-box"><span class="tiny">Git 安装状态</span><strong>{{ adminState.version.git_available ? '可用' : '不可用' }}</strong></div><div class="kv-box"><span class="tiny">最近检测</span><strong>{{ formatDate(adminState.version.checked_at) }}</strong></div></div>
                <div class="placeholder-card section-gap">{{ adminState.version.message || '尚未执行版本检测。' }}</div>
                <div v-if="adminState.version.has_update && adminState.version.can_update" class="section-gap">
                  <button class="btn primary" @click="updateVersion" :disabled="adminState.version.updating">{{ adminState.version.updating ? '正在更新...' : '一键更新' }}</button>
                </div>
                <div class="section-gap"><h3>{{ adminState.version.has_update ? '新版本特性' : '当前版本特性' }}</h3><ul class="feature-list"><li v-for="(feature,index) in versionFeatures" :key="index">{{ feature }}</li></ul></div>
                <div class="auth-footnote section-gap">在线版本更新仅在项目根目录存在 <span class="code-inline">.git</span> 时可用。更新前请先备份数据库与配置。系统将自动执行 <span class="code-inline">git pull origin main</span> 拉取最新代码。</div>
              </div>

              <div v-if="adminTab === 'scheduled-tasks'" class="panel">
                <div class="action-row">
                  <div>
                    <h3>定时任务 HTTP API</h3>
                    <p class="panel-sub">可由宝塔计划任务、监控平台或其他定时服务调用。</p>
                  </div>
                  <button class="btn danger" @click="resetScheduledTaskKey">重置系统密钥</button>
                </div>
                <div class="auth-footnote danger-note section-gap">重置后旧密钥立即失效，所有已配置的定时任务都必须同步更新。</div>
                <div class="form-grid section-gap">
                  <div class="field full">
                    <label>系统密钥（仅管理员可查看和重置）</label>
                    <div class="search-row">
                      <input :value="adminState.scheduledTasks.system_key" readonly autocomplete="off" spellcheck="false" class="mono">
                      <button class="btn ghost" @click="copyScheduledTaskValue(adminState.scheduledTasks.system_key, '系统密钥')">复制</button>
                    </div>
                  </div>
                  <div class="field full">
                    <label>更新商品数据 API（GET / POST）</label>
                    <div class="search-row">
                      <input :value="scheduledTaskUrl(adminState.scheduledTasks.products_endpoint)" readonly class="mono">
                      <button class="btn ghost" @click="copyScheduledTaskValue(scheduledTaskUrl(adminState.scheduledTasks.products_endpoint), '商品更新 API')">复制</button>
                    </div>
                  </div>
                  <div class="field full">
                    <label>更新订单 API（GET / POST）</label>
                    <div class="search-row">
                      <input :value="scheduledTaskUrl(adminState.scheduledTasks.orders_endpoint)" readonly class="mono">
                      <button class="btn ghost" @click="copyScheduledTaskValue(scheduledTaskUrl(adminState.scheduledTasks.orders_endpoint), '订单更新 API')">复制</button>
                    </div>
                  </div>
                </div>
                <div class="auth-footnote section-gap">页面中的一键复制地址使用 system_key 查询参数，适合只支持 URL 的定时平台。更推荐使用 Authorization: Bearer 或 X-System-Key 请求头，避免密钥出现在 URL 访问日志中。</div>
              </div>

              <div v-if="adminTab === 'settings-theme'" class="panel">
                <div class="card-title">
                  <div>
                    <h3>界面主题</h3>
                    <p class="panel-sub">支持在线调整并导入 / 导出 JSON 主题文件。</p>
                  </div>
                  <div class="theme-actions">
                    <button class="btn ghost" @click="exportTheme">导出主题</button>
                    <button class="btn primary" @click="triggerThemeImport">导入主题</button><button class="btn ghost" @click="restoreDefaultTheme">恢复默认</button>
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
                          <input class="theme-value-input mono" v-model.trim="settingsForm.theme_config[item.key]" @input="applyTheme(settingsForm.theme_config)" :placeholder="item.type === 'color' ? '#rrggbb' : '支持 rgba(...) 等 CSS 颜色值'">
                        </div>
                      </label>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>

          <div v-else-if="adminTab === 'exchange-list'">
            <div class="page-head"><div><h2>商品兑换码列表</h2><p>支持按商品、状态、兑换者 QQ 和时间排序，兑换码不脱敏。</p></div><div class="inline-actions"><button class="btn ghost" @click="loadAdminExchangeCodes(true)">刷新列表</button></div></div>
            <div class="filter-grid exchange-filters"><select v-model="adminState.exchange.filters.product_id"><option value="">全部商品</option><option v-for="product in adminState.products" :key="product.id" :value="product.id">{{ product.name }}</option></select><select v-model="adminState.exchange.filters.status"><option value="">全部状态</option><option value="unused">未使用</option><option value="used">已兑换</option><option value="destroyed">已销毁</option></select><input v-model.trim="adminState.exchange.filters.redeemer_qq" placeholder="兑换者QQ"><select v-model="adminState.exchange.filters.sort"><option value="created_desc">生成时间倒序</option><option value="created_asc">生成时间正序</option><option value="used_desc">使用时间倒序</option><option value="used_asc">使用时间正序</option></select><button class="btn ghost" @click="loadAdminExchangeCodes(true)">筛选</button></div>
            <div class="table-wrap" v-if="adminState.exchange.codes.length"><table class="table"><thead><tr><th>兑换码</th><th>创建用户</th><th>商品 / 数量</th><th>价格 / 服务费</th><th>状态</th><th>兑换者QQ / 订单</th><th>生成/使用时间</th><th>操作</th></tr></thead><tbody><tr v-for="row in adminState.exchange.codes" :key="row.id"><td><div class="mono text-break exchange-code-cell">{{ row.code || row.display_code }}</div><div class="tiny">内部ID #{{ row.id }}</div></td><td>{{ row.creator_nickname || row.creator_username || row.creator_name_snapshot || '-' }}<div class="tiny">用户ID {{ row.creator_user_id }} · UID {{ row.creator_uid_snapshot }}</div></td><td>{{ row.product_name_snapshot }}<div class="tiny">{{ row.quantity }} 个 / 每 {{ row.step_num_snapshot }} 个计价</div></td><td>{{ money(row.price_snapshot) }}<div class="amount-yuan">{{ yuanApprox(row.price_snapshot) }}</div><div class="tiny">生成费 {{ money(row.generation_fee) }} · {{ yuanApprox(row.generation_fee) }}</div></td><td><span class="badge" :class="row.status === 'used' ? 'success' : (row.status === 'destroyed' ? 'danger' : 'info')">{{ row.status === 'used' ? '已兑换' : (row.status === 'destroyed' ? '已销毁' : '未使用') }}</span></td><td>{{ row.redeemer_qq || '-' }}<div class="tiny mono">{{ row.redeemer_order_no || '-' }}</div></td><td>生成：{{ formatDate(row.created_at) }}<div class="tiny" v-if="row.used_at">使用：{{ formatDate(row.used_at) }}</div></td><td><div class="inline-actions"><button v-if="row.status === 'unused'" class="btn sm ghost" @click="editExchangeCode(row, true)">编辑</button><button v-if="row.status === 'unused'" class="btn sm danger" @click="destroyExchangeCode(row, true)">销毁</button></div></td></tr></tbody></table></div><div v-else class="placeholder-card">暂无商品兑换码。</div>
          </div>
          <div v-else-if="adminTab === 'exchange-logs'">
            <div class="page-head">
              <div><h2>商品兑换码日志</h2><p>用于定位生成、兑换和异常操作，反馈问题时可提供日志编号。</p></div>
              <div class="inline-actions"><button class="btn ghost" @click="loadAdminExchangeLogs(true)">刷新日志</button></div>
            </div>
            <div class="log-list" v-if="adminState.exchange.logs.length">
              <div class="log-item" v-for="row in adminState.exchange.logs" :key="row.id">
                <div class="card-title">
                  <div><h3 style="margin:0">{{ exchangeActionText(row.action) }} · {{ row.product_name_snapshot || '未知商品' }}</h3><div class="tiny">日志 #{{ row.id }} · 兑换码 #{{ row.exchange_code_id }} · {{ formatDate(row.created_at) }} · IP {{ row.ip || '-' }}</div></div>
                  <span class="badge info">操作用户 {{ row.operator_user_id || '-' }}</span>
                </div>
                <div class="mono text-break">{{ maskExchangeCode(row.code) }}</div>
                <pre>{{ prettyJson(row.context_json ? parseJson(row.context_json, {}) : {}) }}</pre>
              </div>
            </div>
            <div v-else class="placeholder-card">暂无兑换码日志。</div>
          </div>

          <div v-else-if="adminTab === 'logs-list'">
            <div class="page-head">
              <div>
                <h2>系统日志</h2>
                <p>可按等级和频道筛选；反馈问题时请同时提供时间、频道和日志内容。</p>
              </div>
              <div class="inline-actions"><button class="btn ghost" @click="loadAdminLogs(true)">刷新日志</button></div>
            </div>
            <div class="form-grid section-gap">
              <div class="field"><label>日志等级</label><select v-model="adminState.logLevel" @change="loadAdminLogs(true)"><option value="">全部</option><option value="debug">debug</option><option value="info">info</option><option value="warning">warning</option><option value="error">error</option><option value="critical">critical</option></select></div>
              <div class="field"><label>日志频道</label><input v-model.trim="adminState.logChannel" placeholder="如 admin / payment / order" @change="loadAdminLogs(true)"></div>
            </div>
            <div class="log-list" v-if="adminState.logs.length">
              <div class="log-item" v-for="row in adminState.logs" :key="row.id">
                <div class="card-title">
                  <div>
                    <h3 style="margin:0">{{ row.message }}</h3>
                    <div class="tiny">{{ formatDate(row.created_at) }} · {{ row.channel }} · {{ row.level }} · 用户 {{ row.user_id || '-' }}</div>
                  </div>
                  <span class="badge" :class="badgeTone(row.level)">{{ row.level }}</span>
                </div>
                <pre>{{ prettyJson(row.context_json ? parseJson(row.context_json, {}) : {}) }}</pre>
              </div>
            </div>
            <div v-else class="placeholder-card">当前筛选条件下暂无日志。</div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer-block">
    <div v-if="routeMode === 'home' && (settings.icp_beian_no || settings.public_security_beian_no)" class="record-links" style="margin-bottom:12px">
      <a v-if="settings.icp_beian_no" href="https://beian.miit.gov.cn" target="_blank" rel="noopener">{{ settings.icp_beian_no }}</a>
      <div v-if="settings.public_security_beian_no" class="muted">网安备案：{{ settings.public_security_beian_no }}</div>
    </div>
    __SEO_FOOTER_BLOCK__
  </footer>

  <div v-if="exchangeEditState.visible" class="modal-mask" @click.self="closeExchangeEdit">
    <div class="modal" style="max-width:680px"><div class="modal-head"><div><h3>编辑商品兑换码</h3><div class="tiny">仅未使用兑换码可编辑。</div></div><button class="btn ghost" @click="closeExchangeEdit">关闭</button></div><div class="modal-body-scroll"><div class="form-grid"><div class="field full"><label>兑换码（至少48位）</label><input v-model.trim="exchangeEditState.form.code"></div><div class="field full"><label>商品</label><select v-model="exchangeEditState.form.sign"><option v-for="product in (exchangeEditState.admin ? adminState.products : userState.products)" :key="product.id || product.upstream_sign" :value="product.upstream_sign">{{ product.name }}</option></select></div><div class="field"><label>下单数量</label><input v-model.number="exchangeEditState.form.quantity" type="number" min="1"></div></div></div><div class="inline-actions section-gap" style="justify-content:flex-end"><button class="btn ghost" @click="closeExchangeEdit">取消</button><button class="btn primary" @click="saveExchangeCode">保存修改</button></div></div>
  </div>

  <div v-if="confirmState.visible" class="modal-mask" @click.self="cancelConfirm">
    <div class="modal" style="max-width:560px">
      <div class="modal-head">
        <div>
          <h3>{{ confirmState.title || '操作确认' }}</h3>
          <div class="tiny">请确认后继续执行。</div>
        </div>
        <button class="btn ghost" @click="cancelConfirm">关闭</button>
      </div>
      <div class="modal-body-scroll">
        <p class="pre-wrap" style="margin:0">{{ confirmState.message }}</p>
      </div>
      <div class="inline-actions section-gap" style="justify-content:flex-end">
        <button class="btn ghost" @click="cancelConfirm">取消</button>
        <button class="btn danger" @click="resolveConfirm(true)">{{ confirmState.confirmText || '确认' }}</button>
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
    site_name: '', site_keywords: '', site_description: '', site_favicon: '', site_logo: '', seo_footer: '', custom_css: '', custom_js: '', custom_resource_urls: [], currency_name: '额度', admin_path: '/admin', community_group_qq: '', support_group_qq: '', icp_beian_no: '', public_security_beian_no: '',
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
      currency: BOOT.currency || '额度',
      adminUrl: BOOT.adminUrl || BOOT.adminPath || '/admin',
      baseUrl: BOOT.baseUrl || '',
      ownerFeedbackGroupQq: '143805881',
      currentPath: BOOT.currentPath || '/',
      user: BOOT.user || null,
      loading: false,
      loadingText: '处理中...',
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
      confirmState: { visible: false, title: '', message: '', confirmText: '确认', resolver: null }
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
      return Array.isArray(source.features) && source.features.length ? source.features : ['暂无版本特性说明'];
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
        { key: 'dashboard', label: '首页' },
        { key: 'order', label: '在线下单' },
        { key: 'orders', label: '查单系统' },
        { key: 'exchange_codes', label: '商品兑换码' },
        { key: 'recharge', label: '额度充值' },
        { key: 'invites', label: '邀请管理' },
        { key: 'groups', label: '代理等级' },
        { key: 'profile', label: '个人资料' }
      ];
    },
    adminNav: function () {
      return [
        { key: 'dashboard', label: '管理首页' },
        { key: 'products', label: '商品管理', children: [
          { key: 'products-sync', label: '更新商品数据', description: '手动同步上游商品到本地。' },
          { key: 'products-list', label: '管理商品', description: '控制前台上架、对接开关与折扣。' }
        ] },
        { key: 'groups', label: '用户组管理', children: [
          { key: 'groups-list', label: '新增 / 管理用户组', description: '编辑用户组门槛、加价与介绍。' },
          { key: 'groups-default', label: '注册默认用户组', description: '设置新用户注册默认所属用户组。' }
        ] },
        { key: 'users', label: '用户管理', children: [
          { key: 'users-list', label: '用户列表', description: '查看用户、搜索、封禁、删除、改余额。' },
          { key: 'users-create', label: '新增 / 编辑用户', description: '管理员注册用户与修改基础资料。' }
        ] },
        { key: 'orders', label: '订单管理', children: [
          { key: 'orders-list', label: '速刷订单列表', description: '处理补单、退单与仅退款。' },
          { key: 'recharge-orders', label: '充值订单列表', description: '查看用户充值订单与支付结果。' }
        ] },
        { key: 'api', label: '对接设置', children: [
          { key: 'api-conditions', label: '条件设置', description: '配置 API Key 生成条件。' },
          { key: 'upstream-manage', label: '上游管理', description: '配置上游账号并检查 allow 状态。' },
          { key: 'api-keys', label: '密钥管理', description: '查看并重置用户 API Key。' }
        ] },
        { key: 'recharge', label: '充值设置', children: [
          { key: 'cards-generate', label: '卡密生成', description: '批量生成随机或自定义卡密。' },
          { key: 'cards-list', label: '卡密列表', description: '编辑、销毁和检查卡密使用情况。' },
          { key: 'payments-merchants', label: '易支付配置', description: '配置多个易支付商户。' },
          { key: 'payments-channels', label: '支付通道配置', description: '将支付方式绑定到具体商户。' }
        ] },
        { key: 'exchange', label: '商品兑换码', children: [
          { key: 'exchange-rules', label: '兑换码规则', description: '配置兑换码格式、前缀和 Cookie 时长。' },
          { key: 'exchange-list', label: '兑换码列表', description: '查看生成与兑换情况。' },
          { key: 'exchange-logs', label: '兑换码日志', description: '查看兑换码操作日志。' }
        ] },
        { key: 'settings', label: '系统设置', children: [
          { key: 'settings-basic', label: 'SEO / 基础设置', description: '站点名称、备案、群号与后台路径。' },
          { key: 'settings-theme', label: '界面主题', description: '后台化所有颜色并支持导入导出。' },
          { key: 'settings-sms', label: '短信 / 邮件 / 极验', description: '配置腾讯云、阿里云、自定义 HTTP 与 SMTP。' },
          { key: 'settings-security', label: '登录 / 邀请 / 其他', description: '登录注册策略、邀请码规则和首页开关。' },
          { key: 'settings-custom', label: '自定义 CSS / JS', description: '配置自定义样式、脚本和第三方资源。' },
          { key: 'settings-version', label: '版本与更新', description: '检测新版本并查看版本特性。' },
          { key: 'scheduled-tasks', label: '定时任务 API', description: '管理外部定时调用密钥与接口。' }
        ] },
        { key: 'logs', label: '系统日志', children: [
          { key: 'logs-list', label: '日志列表', description: '按等级与频道筛选系统日志。' }
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
      let found = { label: '管理首页', description: '概览系统关键数据。', parent: 'dashboard' };
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
        return { key: 'field_' + index, label: '参数 ' + (index + 1), placeholder: '' };
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
        return { key: 'field_' + index, label: '参数 ' + (index + 1), placeholder: '' };
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
        { title: '基础色', items: [
          { key: 'bg', label: '页面背景', type: 'color' }, { key: 'surface', label: '卡片背景', type: 'color' }, { key: 'surface_soft', label: '柔和背景', type: 'color' },
          { key: 'text', label: '正文文字', type: 'color' }, { key: 'muted', label: '辅助文字', type: 'color' }, { key: 'line', label: '边框颜色', type: 'color' },
          { key: 'primary', label: '主题主色', type: 'color' }, { key: 'success', label: '成功色', type: 'color' }, { key: 'warning', label: '警告色', type: 'color' }, { key: 'danger', label: '危险色', type: 'color' }
        ] },
        { title: '页头 / 导航', items: [
          { key: 'header_bg', label: '页头背景', type: 'text' }, { key: 'header_border', label: '页头边框', type: 'color' }, { key: 'logo_text', label: 'Logo文字', type: 'color' }, { key: 'avatar_bg', label: '头像底色', type: 'color' },
          { key: 'sidebar_bg', label: '侧栏背景', type: 'color' }, { key: 'sidebar_border', label: '侧栏边框', type: 'color' }, { key: 'sidebar_title_text', label: '侧栏标题', type: 'color' },
          { key: 'nav_text', label: '导航文字', type: 'color' }, { key: 'nav_active_bg', label: '导航激活背景', type: 'color' }, { key: 'nav_active_text', label: '导航激活文字', type: 'color' }, { key: 'nav_hover_bg', label: '导航悬停背景', type: 'color' }
        ] },
        { title: '按钮 / 表单', items: [
          { key: 'button_default_bg', label: '默认按钮背景', type: 'color' }, { key: 'button_default_text', label: '默认按钮文字', type: 'color' }, { key: 'button_primary_text', label: '主按钮文字', type: 'color' },
          { key: 'button_success_bg', label: '成功按钮背景', type: 'color' }, { key: 'button_success_text', label: '成功按钮文字', type: 'color' }, { key: 'button_warning_bg', label: '警告按钮背景', type: 'color' },
          { key: 'button_warning_text', label: '警告按钮文字', type: 'color' }, { key: 'button_danger_bg', label: '危险按钮背景', type: 'color' }, { key: 'button_danger_text', label: '危险按钮文字', type: 'color' },
          { key: 'input_bg', label: '输入框背景', type: 'color' }, { key: 'input_border', label: '输入框边框', type: 'color' }, { key: 'input_focus_ring', label: '输入框聚焦环', type: 'text' }
        ] },
        { title: '信息块 / 徽章 / 表格', items: [
          { key: 'badge_info_bg', label: '信息徽章背景', type: 'color' }, { key: 'badge_info_text', label: '信息徽章文字', type: 'color' }, { key: 'badge_success_bg', label: '成功徽章背景', type: 'color' }, { key: 'badge_success_text', label: '成功徽章文字', type: 'color' },
          { key: 'badge_warning_bg', label: '警告徽章背景', type: 'color' }, { key: 'badge_warning_text', label: '警告徽章文字', type: 'color' }, { key: 'badge_danger_bg', label: '危险徽章背景', type: 'color' }, { key: 'badge_danger_text', label: '危险徽章文字', type: 'color' },
          { key: 'table_head_bg', label: '表头背景', type: 'color' }, { key: 'table_head_text', label: '表头文字', type: 'color' }, { key: 'table_bg', label: '表格背景', type: 'color' },
          { key: 'desc_bg', label: '描述块背景', type: 'color' }, { key: 'tip_bg', label: '提示背景', type: 'color' }, { key: 'tip_border', label: '提示边框', type: 'color' }, { key: 'tip_text', label: '提示文字', type: 'color' },
          { key: 'code_item_bg', label: '兑换码卡片背景', type: 'color' }, { key: 'subtle_bg', label: '浅色信息块背景', type: 'color' }, { key: 'editor_bg', label: '编辑器底色', type: 'color' },
          { key: 'admin_note_bg', label: '后台说明背景', type: 'color' }, { key: 'admin_note_border', label: '后台说明边框', type: 'color' }, { key: 'admin_note_text', label: '后台说明文字', type: 'color' }
        ] },
        { title: '弹层 / 二维码 / 代码 / 提示', items: [
          { key: 'qr_bg', label: '二维码背景', type: 'color' }, { key: 'qr_border', label: '二维码边框', type: 'color' },
          { key: 'captcha_bg', label: '验证码背景', type: 'color' }, { key: 'captcha_line', label: '验证码干扰线', type: 'color' }, { key: 'captcha_text', label: '验证码文字', type: 'color' },
          { key: 'modal_bg', label: '弹窗背景', type: 'color' }, { key: 'overlay_bg', label: '遮罩背景', type: 'text' }, { key: 'loading_bg', label: '加载遮罩', type: 'text' }, { key: 'loading_card_bg', label: '加载卡片背景', type: 'color' },
          { key: 'spinner_track', label: '加载环底色', type: 'color' }, { key: 'toast_info', label: '普通提示', type: 'color' }, { key: 'toast_success', label: '成功提示', type: 'color' }, { key: 'toast_warning', label: '警告提示', type: 'color' }, { key: 'toast_danger', label: '危险提示', type: 'color' },
          { key: 'mono_bg', label: '代码块背景', type: 'color' }, { key: 'mono_text', label: '代码块文字', type: 'color' }, { key: 'shadow_color', label: '阴影颜色', type: 'text' }
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
    displayName: function (user) { return (user && (user.nickname || user.username)) ? (user.nickname || user.username) : '未登录'; },
    roleLabel: function (user) {
      const map = { owner: '站长 Owner', admin: '管理员 Admin', agent: '代理 Agent', member: '普通用户 User' };
      return map[String(user && user.account_role ? user.account_role : 'member')] || '用户';
    },
    money: function (value) {
      return Number(value || 0).toLocaleString('zh-CN');
    },
    yuanApprox: function (value) {
      const amount = Number(value || 0);
      return '≈' + (amount / 10000).toFixed(2) + '元';
    },
    formatDate: function (value) {
      return value ? String(value) : '-';
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
      return ({ create: '生成兑换码', redeem: '兑换并下单', update: '编辑兑换码', destroy: '销毁兑换码' })[String(action || '')] || String(action || '未知操作');
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
      const source = String(expr || '').trim().replace(/～/g, '~');
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
      if (!apiAccess) return '可联系网站管理员更改对接权限。';
      if (!apiAccess.can_generate_key) return '当前尚未满足 API Key 生成条件：' + this.apiConditionText(apiAccess);
      return '可联系网站管理员更改，或等待所属用户组 / 单独对接权限开放。';
    },
    openGroup: function (kind) {
      const groupMap = {
        support: this.settings.support_group_qq || '',
        owner_feedback: this.ownerFeedbackGroupQq,
        community: this.settings.community_group_qq || ''
      };
      const groupCode = String(groupMap[kind] || groupMap.community || '').trim();
      if (!groupCode) {
        this.notify('当前暂未配置群号', 'warning');
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
      this.notify('已恢复默认主题配色，请记得保存系统设置。', 'success');
    },
    importThemeFile: async function (event) {
      const file = event && event.target && event.target.files ? event.target.files[0] : null;
      if (!file) return;
      try {
        const raw = await file.text();
        const parsed = JSON.parse(raw);
        if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) throw new Error('主题文件格式不正确');
        this.settingsForm.theme_config = Object.assign({}, this.settingsForm.theme_config || {}, parsed);
        this.applyTheme(this.settingsForm.theme_config);
        this.notify('主题配置已导入并预览', 'success');
      } catch (error) {
        this.notify(error.message || '主题文件导入失败', 'danger');
      } finally {
        if (event && event.target) event.target.value = '';
      }
    },
    qqAvatar: function (qq) {
      return 'https://q1.qlogo.cn/g?b=qq&nk=' + encodeURIComponent(String(qq || '0')) + '&s=100';
    },
    boolText: function (value) { return boolish(value) ? '已开启' : '已关闭'; },
    badgeTone: function (value) {
      const text = String(value || '');
      if (['已完成', '完成', 'paid', 'active', 'success', 'done', 'info'].includes(text)) return 'success';
      if (['失败', '已退款', 'error', 'danger', 'banned', 'deleted'].includes(text)) return 'danger';
      if (['pending', 'processing', '补单中', '未开始', '处理中', 'warning'].includes(text)) return 'warning';
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
          title: opts.title || '操作确认',
          message: message,
          confirmText: opts.confirmText || '确认',
          resolver: resolve
        };
      });
    },
    resolveConfirm: function (flag) {
      const resolver = this.confirmState && this.confirmState.resolver ? this.confirmState.resolver : null;
      this.confirmState = { visible: false, title: '', message: '', confirmText: '确认', resolver: null };
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
      this.loadingText = text || '处理中...';
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
      if (!silent) this.setBusy(true, opts.loadingText || '处理中...');
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
          throw new Error(rawText || '接口返回了无法解析的响应内容');
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
              : ('请求失败（HTTP ' + response.status + '）')
          );
        }
        return payload && Object.prototype.hasOwnProperty.call(payload, 'data') ? payload.data : payload;
      } catch (error) {
        if (!silent) this.notify(error.message || '请求失败', 'danger');
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
      await this.fetchJson('/auth/login', { method: 'POST', body: body, loadingText: '正在登录...' });
      this.notify('登录成功', 'success');
      window.location.href = this.routeUrl(admin ? this.adminUrl : '/user');
    },
    async submitRegister() {
      await this.fetchJson('/auth/register', { method: 'POST', body: this.home.register, loadingText: '正在注册...' });
      this.notify('注册成功', 'success');
      window.location.href = this.routeUrl('/user');
    },
    async logout() {
      await this.fetchJson('/auth/logout', { method: 'GET', loadingText: '正在退出...' });
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
      const data = await this.fetchJson('/user/api/profile', { method: 'GET', loadingText: '正在加载资料...', silent: !force });
      this.profile = data;
      this.user = data.user;
      this.profileForm = { nickname: data.user.nickname || '', qq: data.user.qq || '', email: data.user.email || '', mobile: data.user.mobile || '' };
      return data;
    },
    async loadUserProducts(force) {
      const rows = await this.fetchJson('/user/api/products', { method: 'GET', loadingText: '正在加载商品...', silent: !force });
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
        this.notify('请先输入 QQ 号', 'warning');
        return;
      }
      const rows = await this.fetchJson(this.withQuery('/user/api/feed', { qq: this.orderForm.qq }), { method: 'GET', loadingText: '正在获取说说列表...' });
      this.userState.feedItems = Array.isArray(rows) ? rows : [];
      this.userState.feedModalVisible = this.userState.feedItems.length > 0;
      if (!this.userState.feedItems.length) this.notify('未获取到可选说说列表', 'warning');
    },
    resolveFeedId: function (item) {
      return String(item.feed_id || item.id || item.fid || item.tid || '');
    },
    selectFeed: function (item) {
      this.orderForm.feed_id = this.resolveFeedId(item);
      this.userState.feedModalVisible = false;
      this.notify('已选择说说 ID：' + (this.orderForm.feed_id || '-'), 'success');
    },
    async createOrder() {
      if (!this.selectedProduct) {
        this.notify('请先选择商品', 'warning');
        return;
      }
      const body = { sign: this.orderForm.sign, qq: this.orderForm.qq, num: this.orderForm.num, feed_id: this.orderForm.feed_id, is_delayed: this.orderForm.is_delayed ? 1 : 0 };
      this.dynamicInputFields.forEach((field) => {
        if (field.key === 'feed_id') return;
        body[field.key] = this.orderForm.extra[field.key] || '';
      });
      const data = await this.fetchJson('/user/api/order/create', { method: 'POST', body: body, loadingText: '正在提交订单...' });
      this.notify('下单成功，系统订单号：' + (data.display_order_no || data.order_no || '-'), 'success');
      await this.loadUserProfile(true);
      await this.loadUserOrders(true);
      this.userTab = 'orders';
      this.userState.orderSearch = data.display_order_no || data.order_no || '';
      await this.showOrderDetail(this.userState.orderSearch);
      this.setTabPath('orders', false);
    },
    async loadUserOrders(force) {
      const rows = await this.fetchJson('/user/api/orders', { method: 'GET', loadingText: '正在加载订单...', silent: !force });
      this.userState.orders = rows || [];
      return rows;
    },
    async searchOrderDetail() {
      if (!this.userState.orderSearch) {
        this.notify('请输入系统订单号', 'warning');
        return;
      }
      await this.showOrderDetail(this.userState.orderSearch);
    },
    async showOrderDetail(orderNo) {
      const detail = await this.fetchJson(this.withQuery('/user/api/order/detail', { bid: orderNo }), { method: 'GET', loadingText: '正在同步订单状态...' });
      this.userState.orderDetail = detail;
      this.userState.orderSearch = detail.display_order_no || detail.order_no || orderNo;
      await this.loadUserOrders(true);
    },
    async userRetryOrder(order) {
      if (!await this.confirmAction('确认发起补单申请吗？', { title: '补单确认', confirmText: '确认补单' })) return;
      const data = await this.fetchJson('/user/api/order/retry', { method: 'POST', body: { bid: order.display_order_no || order.order_no }, loadingText: '正在提交补单申请...' });
      this.userState.orderDetail = data;
      this.notify('补单申请已提交', 'success');
      await this.loadUserOrders(true);
    },
    async userRefundOrder(order) {
      if (!await this.confirmAction('确认发起退款申请吗？', { title: '退款确认', confirmText: '确认退款' })) return;
      const data = await this.fetchJson('/user/api/order/refund', { method: 'POST', body: { bid: order.display_order_no || order.order_no }, loadingText: '正在提交退款申请...' });
      this.userState.orderDetail = data;
      this.notify('退款申请已提交', 'success');
      await this.loadUserOrders(true);
      await this.loadUserProfile(true);
    },
    async loadUserPayments(force) {
      const data = await this.fetchJson('/user/api/payments', { method: 'GET', loadingText: '正在加载支付配置...', silent: !force });
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
      const result = await this.fetchJson('/user/api/recharge/create', { method: 'POST', body: { channel_id: this.rechargeForm.channel_id, money: this.rechargeForm.money }, loadingText: '正在创建充值订单...' });
      this.userState.paymentResult = result;
      this.notify('充值订单创建成功', 'success');
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
        wrap.setAttribute('title', '二维码脚本未加载，请使用下方按钮直接打开支付链接');
      }
    },
    async copyPaymentLink() {
      const text = this.paymentJumpLink;
      if (!text || text === '#') return;
      await navigator.clipboard.writeText(text);
      this.notify('支付链接已复制', 'success');
    },
    async redeemCard() {
      const data = await this.fetchJson('/card/redeem', { method: 'POST', body: { code: this.cardRedeemCode }, loadingText: '正在兑换卡密...' });
      this.notify('兑换成功，到账 ' + this.money(data.amount || 0) + ' ' + this.currency, 'success');
      this.cardRedeemCode = '';
      await this.loadUserProfile(true);
      await this.loadUserPayments(true);
    },
    async loadUserExchangeSettings(force) {
      const data = await this.fetchJson('/user/api/exchange-code/settings', { method: 'GET', loadingText: '正在加载兑换码规则...', silent: !force });
      this.userState.exchangeSettings = data || null;
      return data;
    },
    async loadUserExchangeCodes(force) {
      const rows = await this.fetchJson('/user/api/exchange-codes', { method: 'GET', loadingText: '正在加载商品兑换码...', silent: !force });
      this.userState.exchangeCodes = rows || [];
      return rows;
    },
    async createExchangeCode() {
      if (!this.exchangeCodeForm.sign) { this.notify('请先选择商品', 'warning'); return; }
      const count = Math.min(1000, Math.max(1, Number(this.exchangeCodeForm.count || 1)));
      const payload = { sign: this.exchangeCodeForm.sign, quantity: Number(this.exchangeCodeForm.quantity || 0), count: count };
      const data = await this.fetchJson('/user/api/exchange-code/create', { method: 'POST', body: payload, loadingText: '正在批量生成商品兑换码...' });
      const rows = Array.isArray(data.codes) ? data.codes : [];
      this.exchangeCodeForm.generatedCodes = rows.map(function (row) { return row.code || row.display_code || ''; }).filter(Boolean);
      this.notify('已生成 ' + this.exchangeCodeForm.generatedCodes.length + ' 个兑换码', 'success');
      await this.loadUserExchangeCodes(true); await this.loadUserProfile(true);
    },
    async copyGeneratedExchangeCodes() {
      const text = this.exchangeCodeForm.generatedCodes.join('\n');
      if (!text) return;
      try { await navigator.clipboard.writeText(text); this.notify('已复制全部兑换码', 'success'); } catch (e) { this.notify('复制失败，请手动复制文本框内容', 'warning'); }
    },
    editExchangeCode: function (row, admin) {
      this.exchangeEditState = { visible: true, admin: !!admin, form: { id: Number(row.id), code: row.code || row.display_code || '', sign: row.product_sign_snapshot || '', quantity: Number(row.quantity || 0) } };
    },
    closeExchangeEdit: function () { this.exchangeEditState.visible = false; },
    async saveExchangeCode() {
      const state = this.exchangeEditState; if (!state.form.code || state.form.code.length < 48) { this.notify('兑换码长度不能少于48位', 'warning'); return; }
      const url = state.admin ? this.adminUrl + '/api/exchange-code/save' : '/user/api/exchange-code/save';
      await this.fetchJson(url, { method: 'POST', body: state.form, loadingText: '正在保存兑换码...' });
      this.notify('兑换码已保存', 'success'); this.closeExchangeEdit();
      if (state.admin) await this.loadAdminExchangeCodes(true); else await this.loadUserExchangeCodes(true);
    },
    async destroyExchangeCode(row, admin) {
      if (!await this.confirmAction('销毁后该兑换码将不可兑换，且已保留审计记录，确认继续吗？', { title: '销毁兑换码', confirmText: '确认销毁' })) return;
      const url = admin ? this.adminUrl + '/api/exchange-code/destroy' : '/user/api/exchange-code/destroy';
      await this.fetchJson(url, { method: 'POST', body: { id: Number(row.id) }, loadingText: '正在销毁兑换码...' });
      this.notify('兑换码已销毁', 'success'); if (admin) await this.loadAdminExchangeCodes(true); else await this.loadUserExchangeCodes(true);
    },
    async previewExchangeCode() {
      if (!this.exchangePublic.code) {
        this.notify('请输入兑换码', 'warning');
        return;
      }
      const data = await this.fetchJson('/exchange/api/preview', { method: 'POST', body: { code: this.exchangePublic.code }, loadingText: '正在查询兑换码...' });
      this.exchangePublic.preview = data;
      const extra = {};
      this.exchangeInputFields.forEach(function (field) { extra[field.key] = ''; });
      this.exchangePublic.form = { qq: '', extra: extra };
      this.notify('兑换码校验通过，请继续填写兑换信息', 'success');
      return data;
    },
    async redeemExchangeCode() {
      if (!this.exchangePublic.preview) {
        this.notify('请先查询兑换码', 'warning');
        return;
      }
      const body = { code: this.exchangePublic.code, qq: this.exchangePublic.form.qq };
      this.exchangeInputFields.forEach((field) => {
        body[field.key] = (this.exchangePublic.form.extra || {})[field.key] || '';
      });
      const order = await this.fetchJson('/exchange/api/redeem', { method: 'POST', body: body, loadingText: '正在兑换并创建订单...' });
      this.notify('兑换成功，系统订单号：' + (order.display_order_no || order.order_no || '-'), 'success');
      this.exchangePublic.preview = null;
      this.exchangePublic.form = { qq: '', extra: {} };
      await this.loadExchangeOrders(true);
      return order;
    },
    async loadExchangeOrders(force) {
      const rows = await this.fetchJson('/exchange/api/orders', { method: 'GET', loadingText: '正在加载历史兑换订单...', silent: !force });
      this.exchangePublic.orders = rows || [];
      return rows;
    },
    async queryExchangeOrder(orderNo) {
      const value = String(orderNo || '').trim(); if (!value) { this.notify('请输入订单号', 'warning'); return; }
      const row = await this.fetchJson(this.withQuery('/exchange/api/order', { order_no: value }), { method: 'GET', loadingText: '正在查询订单进度...' });
      this.exchangePublic.orderSearch = value; this.exchangePublic.orderDetail = row;
      const index = this.exchangePublic.orders.findIndex(function (item) { return String(item.order_no) === value; });
      if (index >= 0) this.exchangePublic.orders.splice(index, 1, row);
      return row;
    },
    async loadUserInvites(force) {
      const data = await this.fetchJson('/user/api/invites', { method: 'GET', loadingText: '正在加载邀请码...', silent: !force });
      this.userState.invites = data || { codes: [], records: [] };
      return data;
    },
    async createInviteCode() {
      const payload = { length: this.inviteForm.length, code: this.inviteForm.code || '' };
      const row = await this.fetchJson('/user/api/invite/create', { method: 'POST', body: payload, loadingText: '正在创建邀请码...' });
      this.notify('邀请码创建成功：' + row.code, 'success');
      this.inviteForm = emptyInviteForm();
      await this.loadUserInvites(true);
      await this.loadUserProfile(true);
    },
    async loadUserGroups(force) {
      const rows = await this.fetchJson('/user/api/groups', { method: 'GET', loadingText: '正在加载用户组...', silent: !force });
      this.userState.groups = rows || [];
      return rows;
    },
    async claimUserGroup() {
      const data = await this.fetchJson('/user/api/group/claim', { method: 'POST', body: {}, loadingText: '正在检测升级资格...' });
      this.profile = data;
      this.user = data.user;
      this.notify('已刷新用户组与代理状态', 'success');
      await this.loadUserGroups(true);
    },
    async saveProfile() {
      const data = await this.fetchJson('/user/api/profile/save', { method: 'POST', body: this.profileForm, loadingText: '正在保存资料...' });
      this.profile = data;
      this.user = data.user;
      this.notify('资料已保存', 'success');
    },
    async resetOwnApiKey() {
      if (!(this.profile.api_access && this.profile.api_access.can_generate_key)) {
        this.notify(this.apiAccessHint(this.profile.api_access), 'warning');
        return;
      }
      const ok = await this.confirmAction(this.profile.user && this.profile.user.api_key ? '确认重置当前 API Key 吗？重置后旧密钥将立即失效。' : '确认生成 API Key 吗？', { title: 'API Key 操作确认', confirmText: this.profile.user && this.profile.user.api_key ? '确认重置' : '确认生成' });
      if (!ok) return;
      const data = await this.fetchJson('/user/api/api-key/reset', { method: 'POST', body: {}, loadingText: '正在处理 API Key...' });
      if (!this.profile.user) this.profile.user = {};
      this.profile.user.api_key = data.api_key || '';
      if (this.user) this.user.api_key = data.api_key || '';
      this.notify((this.profile.user.api_key ? '最新 API Key：' + this.profile.user.api_key : 'API Key 已更新'), 'success');
      await this.loadUserProfile(true);
    },
    async changePassword() {
      await this.fetchJson('/user/api/profile/password', { method: 'POST', body: this.passwordForm, loadingText: '正在修改密码...' });
      this.passwordForm = emptyPasswordForm();
      this.notify('密码修改成功', 'success');
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
      const data = await this.fetchJson(this.adminUrl + '/api/dashboard', { method: 'GET', loadingText: '正在加载统计...', silent: !force });
      this.adminState.dashboard = data;
      return data;
    },
    rankText: function (list, index, field) {
      const row = (list || [])[index];
      if (!row) return '-';
      return (row.nickname || row.username || '-') + ' / ' + this.money(row[field] || 0) + '（' + this.yuanApprox(row[field] || 0) + '）';
    },
    async loadAdminProducts(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/products', { method: 'GET', loadingText: '正在加载商品...', silent: !force });
      this.adminState.products = (rows || []).map(normalizeAdminProduct);
      return rows;
    },
    async syncProducts() {
      const data = await this.fetchJson(this.adminUrl + '/api/products/sync', { method: 'POST', body: {}, loadingText: '正在同步商品...' });
      this.notify('已同步 ' + (data.count || 0) + ' 个商品', 'success');
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
      await this.fetchJson(this.adminUrl + '/api/products/save', { method: 'POST', body: payload, loadingText: '正在保存商品...' });
      this.notify('商品设置已保存', 'success');
      await this.loadAdminProducts(true);
    },
    async loadAdminGroups(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/groups', { method: 'GET', loadingText: '正在加载用户组...', silent: !force });
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
      await this.fetchJson(this.adminUrl + '/api/groups/save', { method: 'POST', body: this.groupForm, loadingText: '正在保存用户组...' });
      this.notify('用户组已保存', 'success');
      this.resetGroupForm();
      await this.loadAdminGroups(true);
    },
    async setDefaultGroup(group) {
      await this.fetchJson(this.adminUrl + '/api/groups/default', { method: 'POST', body: { id: group.id }, loadingText: '正在设置默认用户组...' });
      this.notify('默认注册用户组已更新', 'success');
      await this.loadAdminGroups(true);
    },
    async loadAdminUsers(force, apiKeyOnly) {
      const keyOnly = apiKeyOnly === true || this.adminTab === 'api-keys';
      const rows = await this.fetchJson(this.adminUrl + '/api/users', { method: 'POST', body: { keyword: keyOnly ? '' : (this.adminState.userKeyword || ''), api_key_only: keyOnly ? 1 : 0 }, loadingText: '正在加载用户...', silent: !force });
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
      return ({ default: '跟随用户组', user: '禁止对接', agent: '允许对接' })[String(value || 'default')] || '跟随用户组';
    },
    apiConditionText: function (apiAccess) {
      if (!apiAccess) return '-';
      return this.thresholdModeLabel(apiAccess.condition_mode) + ' ' + apiAccess.condition_operator + ' ' + this.money(apiAccess.condition_value) + '（当前 ' + this.money(apiAccess.condition_current) + '）';
    },
    thresholdModeLabel: function (mode) {
      return ({ none: '无门槛', total_recharge: '累计充值', total_consume: '累计消费', invite_count: '邀请用户数', balance: '余额' })[String(mode || 'none')] || mode;
    },
    markupLabel: function (mode, value) {
      return mode === 'percent' ? ('百分比加价 ' + value + '%') : ('固定加价 ' + value + ' ' + this.currency);
    },
    async saveAdminUser() {
      const payload = clone(this.userForm);
      await this.fetchJson(this.adminUrl + '/api/users/save', { method: 'POST', body: payload, loadingText: '正在保存用户...' });
      this.notify('用户信息已保存', 'success');
      this.resetUserForm();
      await this.loadAdminUsers(true);
      await this.loadAdminGroups(true);
    },
    async resetUserApiKey(row) {
      const data = await this.fetchJson(this.adminUrl + '/api/users/reset-key', { method: 'POST', body: { id: row.id }, loadingText: '正在重置 API Key...' });
      this.notify('新的 API Key：' + (data.api_key || ''), 'success');
      await this.loadAdminUsers(true);
    },
    async softDeleteUser(row) {
      if (!await this.confirmAction('确认删除该用户吗？该操作会模拟真实删除，但数据仍保留。', { title: '删除用户确认', confirmText: '确认删除' })) return;
      await this.fetchJson(this.adminUrl + '/api/users/delete', { method: 'POST', body: { id: row.id }, loadingText: '正在删除用户...' });
      this.notify('用户已删除（软删除）', 'success');
      await this.loadAdminUsers(true);
    },
    async loadAdminOrders(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/orders', { method: 'GET', loadingText: '正在加载订单...', silent: !force });
      this.adminState.orders = rows || [];
      return rows;
    },
    async searchAdminOrder(row) {
      const rowOrderNo = row ? (row.display_order_no || row.order_no || row.upstream_order_no || '') : '';
      const bid = String(rowOrderNo || this.adminState.orderSearch || '').trim();
      if (!bid) {
        this.notify('请输入系统订单号或上游订单号', 'warning');
        return;
      }
      const detail = await this.fetchJson(this.withQuery(this.adminUrl + '/api/orders/detail', { bid: bid }), { method: 'GET', loadingText: '正在查询并同步订单...' });
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
      const data = await this.fetchJson(this.adminUrl + '/api/orders/sync', { method: 'POST', body: {}, loadingText: '正在同步订单状态...' });
      this.notify('已同步 ' + (data.count || 0) + ' 个进行中订单', 'success');
      await this.loadAdminOrders(true);
    },
    async adminRetryOrder(row) {
      if (!await this.confirmAction('确认向上游发起补单吗？', { title: '后台补单确认', confirmText: '确认补单' })) return;
      await this.fetchJson(this.adminUrl + '/api/orders/retry', { method: 'POST', body: { id: row.id }, loadingText: '正在向上游提交补单...' });
      this.notify('补单申请已提交', 'success');
      await this.loadAdminOrders(true);
    },
    async adminRefundOrder(row, manualOnly) {
      const text = manualOnly ? '确认给用户执行仅退款吗？此操作只会给用户退款，上方货源不会给你退款。' : '确认向上游申请退单吗？';
      if (!await this.confirmAction(text, { title: manualOnly ? '仅退款确认' : '退单确认', confirmText: manualOnly ? '确认仅退款' : '确认退单' })) return;
      await this.fetchJson(this.adminUrl + (manualOnly ? '/api/orders/manual-refund' : '/api/orders/refund'), { method: 'POST', body: { id: row.id }, loadingText: '正在处理退款...' });
      this.notify(manualOnly ? '仅退款已完成' : '退单申请已提交', 'success');
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
        const result = await this.fetchJson(this.adminUrl + '/api/upstream/balance', { method: 'GET', loadingText: '正在获取上游余额...', silent: !!silent });
        const balance = this.parseUpstreamBalance(result);
        if (balance === null) throw new Error('上游返回成功，但响应中没有可识别的余额字段');
        this.adminState.upstreamBalance = balance;
        this.adminState.upstreamBalanceError = '';
        if (!silent) this.notify('上游余额已刷新', 'success');
        return balance;
      } catch (error) {
        this.adminState.upstreamBalance = null;
        this.adminState.upstreamBalanceError = '无法获取上游余额：' + (error && error.message ? error.message : '未知错误');
        return null;
      }
    },
    async loadAdminUpstream(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/upstream', { method: 'GET', loadingText: '正在加载上游配置...', silent: !force });
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
      await this.fetchJson(this.adminUrl + '/api/upstream/save', { method: 'POST', body: this.upstreamForm, loadingText: '正在校验并保存上游...' });
      this.notify('上游配置保存成功', 'success');
      this.resetUpstreamForm();
      await this.loadAdminUpstream(true);
    },
    async loadAdminRecharge(force) {
      const data = await this.fetchJson(this.adminUrl + '/api/payments', { method: 'GET', loadingText: '正在加载充值配置...', silent: !force });
      this.adminState.payments = data || { merchants: [], channels: [], recharge_orders: [] };
      if (!this.channelForm.merchant_id && this.adminState.payments.merchants.length) this.channelForm.merchant_id = Number(this.adminState.payments.merchants[0].id);
      return data;
    },
    async generateCards() {
      await this.fetchJson(this.adminUrl + '/api/cards/generate', { method: 'POST', body: this.cardGenForm, loadingText: '正在生成卡密...' });
      this.notify('卡密已生成', 'success');
      await this.loadAdminCards(true);
    },
    async loadAdminCards(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/cards', { method: 'GET', loadingText: '正在加载卡密...', silent: !force });
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
      await this.fetchJson(this.adminUrl + '/api/cards/save', { method: 'POST', body: this.cardEditForm, loadingText: '正在保存卡密...' });
      this.notify('卡密已保存', 'success');
      this.resetCardEditForm();
      await this.loadAdminCards(true);
    },
    async destroyCard(row) {
      if (!await this.confirmAction('确认销毁该卡密吗？', { title: '销毁卡密确认', confirmText: '确认销毁' })) return;
      await this.fetchJson(this.adminUrl + '/api/cards/destroy', { method: 'POST', body: { id: row.id }, loadingText: '正在销毁卡密...' });
      this.notify('卡密已销毁', 'success');
      await this.loadAdminCards(true);
    },
    editMerchant: function (row) {
      this.merchantForm = Object.assign(emptyMerchantForm(), clone(row), { merchant_key: '' });
    },
    resetMerchantForm: function () { this.merchantForm = emptyMerchantForm(); },
    async saveMerchant() {
      await this.fetchJson(this.adminUrl + '/api/payments/merchant', { method: 'POST', body: this.merchantForm, loadingText: '正在保存易支付商户...' });
      this.notify('易支付商户已保存', 'success');
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
      await this.fetchJson(this.adminUrl + '/api/payments/channel', { method: 'POST', body: this.channelForm, loadingText: '正在保存支付通道...' });
      this.notify('支付通道已保存', 'success');
      this.resetChannelForm();
      await this.loadAdminRecharge(true);
    },
    async loadScheduledTaskConfig(force) {
      const data = await this.fetchJson(this.adminUrl + '/api/scheduled-tasks/key', { method: 'GET', loadingText: '正在加载定时任务配置...', silent: !force });
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
        this.notify('暂无可复制内容', 'warning');
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
      this.notify((label || '内容') + '已复制', 'success');
    },
    async resetScheduledTaskKey() {
      if (!await this.confirmAction('重置后旧系统密钥会立即失效，已配置的商品和订单定时任务将无法继续调用，确认重置吗？', { title: '重置系统密钥', confirmText: '确认重置' })) return;
      const data = await this.fetchJson(this.adminUrl + '/api/scheduled-tasks/key/reset', { method: 'POST', body: {}, loadingText: '正在重置系统密钥...' });
      this.adminState.scheduledTasks = Object.assign({ system_key: '', products_endpoint: '', orders_endpoint: '' }, data || {});
      this.notify('系统密钥已重置，请立即更新所有定时任务', 'success');
    },
    async checkVersion(force) {
      if (!force && this.adminState.version.checked_at) return this.adminState.version;
      try {
        const data = await this.fetchJson(this.adminUrl + '/api/version/check', { method: 'GET', loadingText: '正在检测新版本...', silent: !force });
        this.adminState.version = Object.assign({ current: this.currentVersion, remote: null, has_update: false, git_available: false, can_update: false, checked_at: '', message: '', updating: false }, data || {});
        return data;
      } catch (error) {
        this.adminState.version = Object.assign({}, this.adminState.version, { current: this.currentVersion, checked_at: new Date().toISOString(), message: error.message || '版本检测失败' });
        return null;
      }
    },
    async updateVersion() {
      if (!confirm('确定要更新到最新版本吗？建议先备份数据库和配置。')) return;
      this.adminState.version.updating = true;
      try {
        const data = await this.fetchJson(this.adminUrl + '/api/version/update', { method: 'POST', loadingText: '正在执行更新...' });
        if (data && data.updated) {
          this.notify('更新成功！页面将刷新以加载新版本。', 'success');
          setTimeout(() => location.reload(), 2000);
        } else {
          this.notify(data && data.message || '当前已是最新版本。', 'info');
          this.adminState.version.updating = false;
        }
      } catch (error) {
        this.notify(error.message || '更新失败，请查看系统日志。', 'error');
        this.adminState.version.updating = false;
      }
    },
    async reloadCurrentSettingsPage() {
      if (this.adminTab === 'scheduled-tasks') return this.loadScheduledTaskConfig(true);
      if (this.adminTab === 'settings-version') return this.checkVersion(true);
      return this.loadAdminSettings(true);
    },
    addCustomResource() {
      if (this.settingsForm.custom_resource_urls.length >= 20) return this.notify('外部资源链接最多允许 20 条', 'warning');
      this.settingsForm.custom_resource_urls.push({ type: 'css', url: '' });
    },
    removeCustomResource(index) { this.settingsForm.custom_resource_urls.splice(index, 1); },
    async loadAdminSettings(force) {
      const raw = await this.fetchJson(this.adminUrl + '/api/settings', { method: 'GET', loadingText: '正在加载系统设置...', silent: !force });
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
      await this.fetchJson(this.adminUrl + '/api/settings/save', { method: 'POST', body: this.apiSettings, loadingText: '正在保存对接条件...' });
      this.notify('对接条件已保存', 'success');
      await this.loadAdminSettings(true);
    },
    addPair: function (rows) { rows.push({ key: '', value: '' }); },
    removePair: function (rows, index) { rows.splice(index, 1); },
    addInviteRule: function () { this.settingsForm.invite_code_price_rules.length_rules.push({ length: '6', price: 0 }); },
    removeInviteRule: function (index) { this.settingsForm.invite_code_price_rules.length_rules.splice(index, 1); },
    async saveSettings() {
      const payload = formToSettingsPayload(this.settingsForm);
      await this.fetchJson(this.adminUrl + '/api/settings/save', { method: 'POST', body: payload, loadingText: '正在保存系统设置...' });
      this.notify('系统设置已保存', 'success');
      await this.loadAdminSettings(true);
    },
    async loadAdminExchangeCodes(force) {
      const rows = await this.fetchJson(this.withQuery(this.adminUrl + '/api/exchange-codes', this.adminState.exchange.filters), { method: 'GET', loadingText: '正在加载兑换码列表...', silent: !force });
      this.adminState.exchange.codes = rows || [];
      return rows;
    },
    async loadAdminExchangeLogs(force) {
      const rows = await this.fetchJson(this.adminUrl + '/api/exchange-codes/logs', { method: 'GET', loadingText: '正在加载兑换码日志...', silent: !force });
      this.adminState.exchange.logs = rows || [];
      return rows;
    },
    async loadAdminLogs(force) {
      const rows = await this.fetchJson(this.withQuery(this.adminUrl + '/api/logs', { level: this.adminState.logLevel, channel: this.adminState.logChannel }), { method: 'GET', loadingText: '正在加载系统日志...', silent: !force });
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
