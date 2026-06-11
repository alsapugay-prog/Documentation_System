<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DSB Documentation System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg-base:       #06101d;
            --bg-surface:    #0b1829;
            --bg-elevated:   #0f1f35;
            --bg-hover:      rgba(255,255,255,0.04);
            --border:        rgba(255,255,255,0.07);
            --border-strong: rgba(255,255,255,0.12);
            --text-primary:  #dce9f8;
            --text-secondary:#7a9bbf;
            --text-muted:    #3a5470;
            --accent:        #2563eb;
            --accent-glow:   rgba(37,99,235,0.18);
            --accent-light:  #5b96e8;
            --sidebar-w:     240px;
            --topbar-h:      58px;
            --radius-sm:     8px;
            --radius-md:     12px;
            --radius-lg:     16px;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            overflow: hidden;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.14); }

        /* ─── LAYOUT SHELL ─── */
        .shell { display: flex; height: 100vh; }

        /* ═══════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0;
            z-index: 40;
            transition: transform 0.22s cubic-bezier(.4,0,.2,1);
        }
        .sidebar.collapsed { transform: translateX(calc(-1 * var(--sidebar-w))); }

        /* Logo area */
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 20px 18px 16px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .sidebar-logo img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            flex-shrink: 0;
            border-radius: 50%;
            image-rendering: -webkit-optimize-contrast;
        }
        .logo-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.2px;
            line-height: 1.2;
        }
        .logo-tagline {
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: 0.02em;
            margin-top: 1px;
        }

        /* Nav */
        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 16px 10px;
        }
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0 8px;
            margin-bottom: 6px;
            margin-top: 4px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8.5px 10px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            transition: background 0.14s, color 0.14s;
            margin-bottom: 1px;
            position: relative;
        }
        .nav-link i {
            width: 16px;
            text-align: center;
            font-size: 13px;
            flex-shrink: 0;
        }
        .nav-link:hover {
            background: var(--bg-hover);
            color: var(--text-secondary);
        }
        .nav-link.active {
            background: var(--accent-glow);
            color: var(--accent-light);
            font-weight: 600;
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 25%; bottom: 25%;
            width: 2.5px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }
        .nav-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 10px 6px;
        }

        /* Sidebar bottom */
        .sidebar-bottom {
            border-top: 1px solid var(--border);
            background: rgba(0,0,0,0.12);
            flex-shrink: 0;
        }
        .user-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 14px;
        }
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 1.5px solid var(--border-strong);
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name-text {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-email-text {
            font-size: 10.5px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-more-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px 5px;
            font-size: 14px;
            border-radius: 6px;
            transition: color 0.14s, background 0.14s;
            flex-shrink: 0;
        }
        .user-more-btn:hover { color: var(--accent-light); background: var(--bg-hover); }

        .darkmode-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 14px 6px;
            border-top: 1px solid var(--border);
        }
        .darkmode-label { font-size: 11.5px; font-weight: 500; color: var(--text-muted); }

        .toggle {
            width: 36px;
            height: 20px;
            background: var(--accent);
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            transition: background 0.22s;
            flex-shrink: 0;
        }
        .toggle.off { background: rgba(255,255,255,0.1); }
        .toggle-knob {
            width: 14px;
            height: 14px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            right: 3px;
            transition: right 0.18s, left 0.18s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.3);
        }
        .toggle.off .toggle-knob { right: auto; left: 3px; }

        .version-badge {
            font-size: 10px;
            color: var(--text-muted);
            text-align: center;
            padding: 6px 14px 10px;
            letter-spacing: 0.04em;
        }

        /* ═══════════════════════════════════════
           TOP BAR
        ═══════════════════════════════════════ */
        .topbar {
            height: var(--topbar-h);
            background: rgba(11,24,41,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            height: 100%;
        }

        /* Page Title / Breadcrumb */
        .topbar-title-wrap { display: flex; flex-direction: column; justify-content: center; }
        .topbar-page-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.2px;
        }
        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 2px;
        }
        .breadcrumb-root {
            font-size: 10.5px;
            color: var(--text-muted);
            font-weight: 500;
        }
        .breadcrumb-root i { font-size: 9px; margin-right: 3px; }
        .breadcrumb-sep { font-size: 10px; color: var(--text-muted); opacity: 0.5; }
        .breadcrumb-current {
            font-size: 10.5px;
            color: var(--accent-light);
            font-weight: 600;
        }

        /* Topbar right */
        .topbar-right { display: flex; align-items: center; gap: 14px; }

        /* Notification */
        .notif-wrap { position: relative; }
        .notif-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 17px;
            cursor: pointer;
            padding: 5px;
            border-radius: var(--radius-sm);
            transition: color 0.14s, background 0.14s;
            position: relative;
        }
        .notif-btn:hover { color: var(--accent-light); background: var(--bg-hover); }
        .notif-badge {
            position: absolute;
            top: 4px;
            right: 3px;
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 1.5px solid var(--bg-surface);
        }
        .notif-badge.pulse { animation: pulse-dot 1.6s ease-in-out infinite; }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.45); opacity: 0.8; }
        }

        .notif-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: -8px;
            width: 340px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-lg);
            box-shadow: 0 24px 64px rgba(0,0,0,0.55);
            z-index: 100;
            display: none;
            overflow: hidden;
        }
        .notif-dropdown.visible { display: block; }
        .notif-dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px 12px;
            border-bottom: 1px solid var(--border);
        }
        .notif-dropdown-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .notif-mark-all {
            font-size: 11px;
            font-weight: 600;
            color: var(--accent-light);
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: color 0.14s;
            opacity: 0.8;
        }
        .notif-mark-all:hover { opacity: 1; }
        .notif-list { max-height: 300px; overflow-y: auto; }
        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 11px 16px;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
            cursor: pointer;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: var(--bg-hover); }
        .notif-item.unread { background: rgba(37,99,235,0.04); }
        .notif-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11.5px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .notif-item-icon.blue   { background: rgba(59,130,246,0.13); color: #5ca3e8; }
        .notif-item-icon.green  { background: rgba(52,211,153,0.11); color: #34d399; }
        .notif-item-icon.amber  { background: rgba(251,146,60,0.10); color: #fb923c; }
        .notif-item-icon.purple { background: rgba(167,139,250,0.12); color: #c084fc; }
        .notif-item-body { flex: 1; min-width: 0; }
        .notif-item-text {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-secondary);
            line-height: 1.45;
        }
        .notif-item-text strong { color: var(--text-primary); font-weight: 600; }
        .notif-item-time { font-size: 10.5px; color: var(--text-muted); margin-top: 2px; }
        .notif-unread-dot {
            width: 6px;
            height: 6px;
            background: var(--accent);
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 7px;
        }
        .notif-footer {
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            text-align: center;
        }
        .notif-footer a {
            font-size: 12px;
            font-weight: 600;
            color: var(--accent-light);
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.14s;
        }
        .notif-footer a:hover { opacity: 1; }

        /* Topbar admin section */
        .topbar-divider {
            width: 1px;
            height: 22px;
            background: var(--border);
        }
        .topbar-admin { display: flex; align-items: center; gap: 9px; }
        .topbar-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid var(--border-strong);
        }
        .topbar-admin-info .admin-name {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.3;
        }
        .topbar-admin-info .admin-role {
            font-size: 10.5px;
            color: var(--text-muted);
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.15);
            color: #f87171;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            transition: background 0.14s, border-color 0.14s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.14); border-color: rgba(239,68,68,0.28); }

        /* ═══════════════════════════════════════
           MAIN AREA
        ═══════════════════════════════════════ */
        .main-area {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            transition: margin-left 0.22s cubic-bezier(.4,0,.2,1);
        }
        .main-area.expanded { margin-left: 0; }

        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 30px 32px;
            background: var(--bg-base);
        }

        /* Footer */
        .site-footer {
            margin-top: 48px;
            padding: 20px 0 4px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ═══════════════════════════════════════
           LIGHT MODE
        ═══════════════════════════════════════ */
        body.light-mode {
            --bg-base:       #f2f6fc;
            --bg-surface:    #ffffff;
            --bg-elevated:   #ffffff;
            --bg-hover:      rgba(0,0,0,0.04);
            --border:        rgba(0,0,0,0.08);
            --border-strong: rgba(0,0,0,0.12);
            --text-primary:  #0f1923;
            --text-secondary:#4a6280;
            --text-muted:    #94a3b8;
        }
        body.light-mode .topbar {
            background: rgba(255,255,255,0.92);
        }

        body.light-mode .nav-link.active {
            background: #e8f0fb;
            color: var(--accent);
        }
        body.light-mode .sidebar-bottom {
            background: #f8fafc;
        }
        body.light-mode .notif-badge {
            border-color: #ffffff;
        }

        /* ═══════════════════════════════════════
           MODALS
        ═══════════════════════════════════════ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(5px);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.visible { display: flex; }
        .modal-box {
            background: var(--bg-elevated);
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-lg);
            padding: 28px 28px;
            width: 520px;
            max-width: 94vw;
            max-height: 88vh;
            overflow-y: auto;
            position: relative;
            animation: modal-pop 0.2s cubic-bezier(.34,1.26,.64,1);
        }
        @keyframes modal-pop {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-close {
            position: absolute;
            top: 16px;
            right: 18px;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 17px;
            cursor: pointer;
            border-radius: 6px;
            padding: 3px 5px;
            transition: color 0.14s, background 0.14s;
        }
        .modal-close:hover { color: #f87171; background: rgba(239,68,68,0.08); }
        .modal-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .modal-title i { color: var(--accent-light); }
        .modal-section-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 10px;
        }

        .form-group { margin-bottom: 15px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }
        .form-input, .form-select {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 13px;
            font-size: 13px;
            color: var(--text-primary);
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.18s;
        }
        .form-input:focus, .form-select:focus { border-color: rgba(37,99,235,0.4); }
        .form-select option { background: var(--bg-elevated); }

        /* Assign grid */
        .assign-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 7px;
            max-height: 200px;
            overflow-y: auto;
        }
        .assign-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
            cursor: pointer;
            transition: border-color 0.14s, background 0.14s;
        }
        .assign-item:hover { border-color: rgba(37,99,235,0.3); background: rgba(37,99,235,0.04); }
        .assign-item input[type="checkbox"] { accent-color: var(--accent); cursor: pointer; }
        .assign-item-label { font-size: 12px; color: var(--text-secondary); line-height: 1.3; }

        /* Buttons */
        .btn-row { display: flex; gap: 9px; justify-content: flex-end; margin-top: 20px; }
        .btn-cancel {
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.14s;
        }
        .btn-cancel:hover { background: rgba(255,255,255,0.07); }
        .btn-primary {
            padding: 9px 20px;
            border-radius: var(--radius-sm);
            background: var(--accent);
            border: none;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.14s;
        }
        .btn-primary:hover { background: #1d4ed8; }

        /* Assign type buttons */
        .assign-type-btn {
            padding: 7px 15px;
            border-radius: var(--radius-sm);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.03);
            color: var(--text-muted);
            transition: all 0.14s;
        }
        .assign-type-btn.active {
            background: rgba(37,99,235,0.14);
            border-color: rgba(37,99,235,0.3);
            color: var(--accent-light);
        }
        .assign-type-btn:hover:not(.active) {
            background: var(--bg-hover);
            color: var(--text-secondary);
        }

        /* ═══════════════════════════════════════
           TOAST
        ═══════════════════════════════════════ */
        .toast-container {
            position: fixed;
            bottom: 22px;
            right: 22px;
            z-index: 300;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }
        .toast {
            padding: 11px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 9px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.45);
            animation: toast-in 0.22s cubic-bezier(.34,1.26,.64,1);
            min-width: 220px;
            pointer-events: all;
        }
        @keyframes toast-in { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .toast.success { background: #064e3b; border: 1px solid #10b981; }
        .toast.error   { background: #7f1d1d; border: 1px solid #ef4444; }
        .toast.info    { background: #1e3a5f; border: 1px solid #3b82f6; }
    </style>
</head>
<body>

@php
    $userProfile = auth()->user()?->profile;
    $userAvatar  = $userProfile?->avatar
        ? asset('storage/' . $userProfile->avatar)
        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()?->name ?? 'A') . '&background=0e1c35&color=7ab3e8&size=80';

    $allClients         = \App\Models\Client::select('id','client_name','status')->latest()->take(50)->get();
    $allServices        = \App\Models\Service::select('id','name')->latest()->take(50)->get();
    $allClientDocuments = \App\Models\ClientDocument::select('id','original_name','client_id')->latest()->take(50)->get();
    $allUsers           = \App\Models\User::select('id','name','email')->latest()->take(50)->get();

    $recentClients  = \App\Models\Client::latest()->take(3)->get();
    $recentDocs     = \App\Models\ClientDocument::latest()->take(3)->get();
    $recentServices = \App\Models\Service::latest()->take(2)->get();
    $notifCount     = $recentClients->count() + $recentDocs->count();
@endphp

<div class="shell">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar" id="mainSidebar">

        {{-- Logo --}}
      <div class="sidebar-logo" style="display:flex; align-items:center; gap:10px; padding:14px 16px; background:none;">

    <img src="{{ asset('images/dsb-logo.png') }}" 
         alt="DSB Logo"
         style="width:44px; height:44px; object-fit:contain; flex-shrink:0; border-radius:50%; border:2px solid rgba(255,255,255,0.15);">

    <div style="display:flex; flex-direction:column;">
        
        <div style="font-size:14px; font-weight:700; color:#fff; line-height:1.2;">
            DSB Documentation
        </div>

        <div style="font-size:10px; color:#7a9bbf; line-height:1.2; margin-top:2px;">
            Document Management System
        </div>

    </div>

</div>
        {{-- Navigation --}}
        <div class="sidebar-scroll">
            <nav>
                <div class="nav-section-label">Main</div>

                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="{{ route('services.index') }}"
                   class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase"></i> Services
                </a>
                <a href="{{ route('clients.index') }}"
                   class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Clients
                </a>
                <a href="{{ route('documents.index') }}"
                   class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-folder-open"></i> Documents
                </a>

                <hr class="nav-divider">
                <div class="nav-section-label">System</div>

                <a href="{{ route('users.index') ?? '#' }}"
                   class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                   onclick="event.preventDefault(); openUserManagementModal()">
                    <i class="fa-solid fa-user-shield"></i> Users
                </a>
                <a href="{{ route('settings.profile') }}"
                   class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
            </nav>
        </div>

        {{-- Bottom user section --}}
        <div class="sidebar-bottom">
            <div class="user-row">
                <img src="{{ $userAvatar }}" alt="{{ auth()->user()?->name }}" class="user-avatar">
                <div class="user-info">
                    <div class="user-name-text">{{ auth()->user()?->name ?? 'Admin' }}</div>
                    <div class="user-email-text">{{ auth()->user()?->email ?? 'admin@example.com' }}</div>
                </div>
                <button class="user-more-btn" title="More options">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>
        </div>

    </aside>

    {{-- ═══ MAIN AREA ═══ --}}
    <div class="main-area" id="mainArea">

        {{-- TOP BAR --}}
        <header class="topbar">
            <div class="topbar-left">
                {{-- Page Title & Breadcrumb --}}
                <div class="topbar-title-wrap">
                    <div class="topbar-page-title">@yield('page_title', 'Dashboard')</div>
                    <div class="topbar-breadcrumb">
                        <span class="breadcrumb-root"><i class="fa-solid fa-house-chimney"></i> DSB</span>
                        <span class="breadcrumb-sep">/</span>
                        <span class="breadcrumb-current">@yield('page_title', 'Dashboard')</span>
                    </div>
                </div>
            </div>

            <div class="topbar-right">
                {{-- Notification Bell --}}
                <div class="notif-wrap" id="notifWrap">
                    <button class="notif-btn" id="notifToggle" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                    </button>
                    <span class="notif-badge pulse" id="notifBadge" style="{{ $notifCount > 0 ? '' : 'display:none;' }}"></span>

                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-dropdown-header">
                            <span class="notif-dropdown-title">
                                Notifications
                                <span id="notifCount" style="font-size:11px;color:var(--accent-light);font-weight:500;margin-left:4px;">({{ $notifCount }})</span>
                            </span>
                            <button class="notif-mark-all" id="markAllRead">Mark all read</button>
                        </div>
                        <div class="notif-list">
                            @foreach($recentClients as $rc)
                            <div class="notif-item unread" data-id="{{ $rc->id }}">
                                <div class="notif-item-icon blue"><i class="fa-solid fa-user-plus"></i></div>
                                <div class="notif-item-body">
                                    <div class="notif-item-text">New client: <strong>{{ $rc->client_name }}</strong></div>
                                    <div class="notif-item-time">{{ $rc->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="notif-unread-dot"></div>
                            </div>
                            @endforeach
                            @foreach($recentDocs as $rd)
                            <div class="notif-item unread" data-id="doc-{{ $rd->id }}">
                                <div class="notif-item-icon green"><i class="fa-solid fa-file-arrow-up"></i></div>
                                <div class="notif-item-body">
                                    <div class="notif-item-text">Document uploaded: <strong>{{ $rd->title }}</strong></div>
                                    <div class="notif-item-time">{{ $rd->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="notif-unread-dot"></div>
                            </div>
                            @endforeach
                            @foreach($recentServices as $rs)
                            <div class="notif-item" data-id="svc-{{ $rs->id }}">
                                <div class="notif-item-icon amber"><i class="fa-solid fa-briefcase"></i></div>
                                <div class="notif-item-body">
                                    <div class="notif-item-text">Service added: <strong>{{ $rs->name }}</strong></div>
                                    <div class="notif-item-time">{{ $rs->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @endforeach
                            @if($notifCount === 0)
                            <div style="padding:24px 16px;text-align:center;font-size:12.5px;color:var(--text-muted);">
                                <i class="fa-regular fa-bell-slash" style="font-size:22px;display:block;margin-bottom:8px;opacity:0.5;"></i>
                                No notifications yet
                            </div>
                            @endif
                        </div>
                        <div class="notif-footer">
                            <a href="{{ route('clients.index') }}">View all activity →</a>
                        </div>
                    </div>
                </div>

                <div class="topbar-divider"></div>

                {{-- Admin Info --}}
                <div class="topbar-admin">
                    <img src="{{ $userAvatar }}" alt="{{ auth()->user()?->name }}" class="topbar-avatar">
                    <div class="topbar-admin-info">
                        <div class="admin-name">{{ auth()->user()?->name ?? 'Admin' }}</div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket" style="font-size:11px;"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="main-content">
            @yield('content')
            <footer class="site-footer">
                <span>© {{ date('Y') }} DSB Documentation System</span>
                <span>All rights reserved.</span>
            </footer>
        </main>

    </div>
</div>

{{-- ═══ USER MANAGEMENT MODAL ═══ --}}
<div class="modal-overlay" id="userModal">
    <div class="modal-box" style="width:600px;">
        <button class="modal-close" onclick="closeUserModal()"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-title">
            <i class="fa-solid fa-user-shield"></i> User Management
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <span class="modal-section-label" style="margin-bottom:0;">System Users</span>
            <button class="btn-primary" style="padding:7px 13px;font-size:12px;" onclick="showAddUserForm()">
                <i class="fa-solid fa-plus" style="margin-right:5px;"></i>Add User
            </button>
        </div>

        <div id="userListContainer">
            @foreach($allUsers as $u)
            <div class="assign-item" style="margin-bottom:6px;padding:10px 12px;cursor:default;" id="userRow-{{ $u->id }}">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--accent-glow);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--accent-light);flex-shrink:0;">
                    {{ strtoupper(substr($u->name,0,1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);">{{ $u->name }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ $u->email }}</div>
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0;">
                    <button onclick="openAssignModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                        style="background:var(--accent-glow);border:1px solid rgba(37,99,235,0.25);color:var(--accent-light);border-radius:7px;padding:5px 11px;font-size:11.5px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.14s,border-color 0.14s;white-space:nowrap;">
                        <i class="fa-solid fa-link" style="margin-right:4px;"></i>Assign
                    </button>
                    @if(auth()->id() !== $u->id)
                    <button onclick="confirmDeleteUser({{ $u->id }}, '{{ addslashes($u->name) }}')"
                        style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.22);color:#f87171;border-radius:7px;padding:5px 10px;font-size:11.5px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.14s,border-color 0.14s;white-space:nowrap;"
                        onmouseover="this.style.background='rgba(239,68,68,0.18)'" onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
            @if($allUsers->isEmpty())
            <div style="padding:20px;text-align:center;font-size:12.5px;color:var(--text-muted);">No users found.</div>
            @endif
        </div>

        {{-- Add User Form --}}
        <div id="addUserForm" style="display:none;margin-top:18px;padding-top:18px;border-top:1px solid var(--border);">
            <div class="modal-section-label">New User</div>
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-input" id="newUserName" placeholder="e.g. Juan Dela Cruz">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" id="newUserEmail" placeholder="user@example.com">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-input" id="newUserPassword" placeholder="Minimum 8 characters">
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select class="form-select" id="newUserRole">
                    <option value="staff">Staff</option>
                    <option value="admin">Administrator</option>
                    <option value="viewer">Viewer (read-only)</option>
                </select>
            </div>
            <div class="btn-row" style="margin-top:14px;">
                <button class="btn-cancel" onclick="hideAddUserForm()">Cancel</button>
                <button class="btn-primary" onclick="submitNewUser()">Create User</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ DELETE USER CONFIRM MODAL ═══ --}}
<div class="modal-overlay" id="deleteUserModal">
    <div class="modal-box" style="width:420px;">
        <button class="modal-close" onclick="closeDeleteUserModal()"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-title" style="color:#f87171;">
            <i class="fa-solid fa-triangle-exclamation" style="color:#f87171;"></i> Delete User
        </div>
        <p style="font-size:13.5px;color:var(--text-secondary);margin-bottom:6px;">
            Are you sure you want to delete <strong id="deleteUserNameText" style="color:var(--text-primary);"></strong>?
        </p>
        <p style="font-size:12px;color:var(--text-muted);margin-bottom:22px;">
            This action cannot be undone. All data associated with this user will be permanently removed.
        </p>
        <div class="btn-row" style="margin-top:0;">
            <button class="btn-cancel" onclick="closeDeleteUserModal()">Cancel</button>
            <button id="confirmDeleteBtn" onclick="executeDeleteUser()"
                style="padding:9px 20px;border-radius:var(--radius-sm);background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.35);color:#f87171;font-size:13px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.14s;"
                onmouseover="this.style.background='rgba(239,68,68,0.25)'" onmouseout="this.style.background='rgba(239,68,68,0.15)'">
                <i class="fa-solid fa-trash" style="margin-right:5px;"></i>Delete User
            </button>
        </div>
    </div>
</div>

{{-- ═══ ASSIGN MODAL ═══ --}}
<div class="modal-overlay" id="assignModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeAssignModal()"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-title">
            <i class="fa-solid fa-link"></i>
            Assign to <span id="assignUserName">User</span>
        </div>

        <div style="margin-bottom:18px;">
            <div class="modal-section-label">Select Type</div>
            <div style="display:flex;gap:8px;">
                <button class="assign-type-btn active" id="btnTypeDoc" onclick="setAssignType('documents')">
                    <i class="fa-regular fa-folder-open" style="margin-right:5px;"></i>Documents
                </button>
                <button class="assign-type-btn" id="btnTypeSvc" onclick="setAssignType('services')">
                    <i class="fa-solid fa-briefcase" style="margin-right:5px;"></i>Services
                </button>
            </div>
        </div>

        <div id="assignDocSection">
            <div class="modal-section-label">Available Documents</div>
            <div class="assign-grid" id="docCheckboxes">
                @foreach($allClientDocuments as $doc)
                <label class="assign-item">
                    <input type="checkbox" name="doc_ids[]" value="{{ $doc->id }}" class="doc-checkbox">
                    <span class="assign-item-label">{{ $doc->title ?? $doc->original_name }}</span>
                </label>
                @endforeach
                @if($allClientDocuments->isEmpty())
                <div style="grid-column:span 2;padding:12px;text-align:center;font-size:12.5px;color:var(--text-muted);">No documents available.</div>
                @endif
            </div>
        </div>

        <div id="assignSvcSection" style="display:none;">
            <div class="modal-section-label">Available Services</div>
            <div class="assign-grid" id="svcCheckboxes">
                @foreach($allServices as $svc)
                <label class="assign-item">
                    <input type="checkbox" name="svc_ids[]" value="{{ $svc->id }}" class="svc-checkbox">
                    <span class="assign-item-label">{{ $svc->name }}</span>
                </label>
                @endforeach
                @if($allServices->isEmpty())
                <div style="grid-column:span 2;padding:12px;text-align:center;font-size:12.5px;color:var(--text-muted);">No services available.</div>
                @endif
            </div>
        </div>

        <div class="btn-row">
            <button class="btn-cancel" onclick="closeAssignModal()">Cancel</button>
            <button class="btn-primary" onclick="submitAssignment()">
                <i class="fa-solid fa-check" style="margin-right:5px;"></i>Save Assignment
            </button>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div class="toast-container" id="toastContainer"></div>

<script>

// ─── NOTIFICATIONS ───
const notifToggle   = document.getElementById('notifToggle');
const notifDropdown = document.getElementById('notifDropdown');
const notifBadge    = document.getElementById('notifBadge');
const notifCount    = document.getElementById('notifCount');
let unreadCount     = document.querySelectorAll('.notif-item.unread').length;

notifToggle.addEventListener('click', e => {
    e.stopPropagation();
    notifDropdown.classList.toggle('visible');
});
document.addEventListener('click', e => {
    if (!document.getElementById('notifWrap').contains(e.target))
        notifDropdown.classList.remove('visible');
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') notifDropdown.classList.remove('visible');
});
document.getElementById('markAllRead').addEventListener('click', () => {
    document.querySelectorAll('.notif-item.unread').forEach(el => {
        el.classList.remove('unread');
        const dot = el.querySelector('.notif-unread-dot');
        if (dot) dot.remove();
    });
    unreadCount = 0;
    notifBadge.style.display = 'none';
    notifBadge.classList.remove('pulse');
    notifCount.textContent = '(0)';
    showToast('All notifications marked as read', 'success');
});
document.querySelectorAll('.notif-item').forEach(el => {
    el.addEventListener('click', () => {
        if (el.classList.contains('unread')) {
            el.classList.remove('unread');
            const dot = el.querySelector('.notif-unread-dot');
            if (dot) dot.remove();
            unreadCount = Math.max(0, unreadCount - 1);
            if (unreadCount === 0) { notifBadge.style.display = 'none'; notifBadge.classList.remove('pulse'); }
            notifCount.textContent = `(${unreadCount})`;
        }
    });
});

// ─── USER MANAGEMENT MODAL ───
function openUserManagementModal() {
    document.getElementById('userModal').classList.add('visible');
}
function closeUserModal() {
    document.getElementById('userModal').classList.remove('visible');
    hideAddUserForm();
}
function showAddUserForm()  { document.getElementById('addUserForm').style.display = 'block'; }
function hideAddUserForm()  {
    document.getElementById('addUserForm').style.display = 'none';
    document.getElementById('newUserName').value = '';
    document.getElementById('newUserEmail').value = '';
    document.getElementById('newUserPassword').value = '';
}
function submitNewUser() {
    const name  = document.getElementById('newUserName').value.trim();
    const email = document.getElementById('newUserEmail').value.trim();
    const pass  = document.getElementById('newUserPassword').value;
    const role  = document.getElementById('newUserRole').value;
    if (!name || !email || !pass) { showToast('Please fill in all fields.', 'error'); return; }
    if (pass.length < 8) { showToast('Password must be at least 8 characters.', 'error'); return; }

    fetch('{{ route("users.store") ?? "/users" }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name, email, password: pass, role })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('User created successfully.', 'success');
            hideAddUserForm();
            const list = document.getElementById('userListContainer');
            const div  = document.createElement('div');
            div.className = 'assign-item';
            div.style.cssText = 'margin-bottom:6px;padding:10px 12px;cursor:default;';
            div.innerHTML = `
                <div style="width:32px;height:32px;border-radius:50%;background:var(--accent-glow);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--accent-light);flex-shrink:0;">
                    ${name.charAt(0).toUpperCase()}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);">${name}</div>
                    <div style="font-size:11px;color:var(--text-muted);">${email}</div>
                </div>
                <button onclick="openAssignModal(${data.user.id}, '${name}')"
                    style="background:var(--accent-glow);border:1px solid rgba(37,99,235,0.25);color:var(--accent-light);border-radius:7px;padding:5px 11px;font-size:11.5px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
                    <i class="fa-solid fa-link" style="margin-right:4px;"></i>Assign
                </button>`;
            list.appendChild(div);
        } else {
            showToast(data.message || 'Failed to create user.', 'error');
        }
    })
    .catch(() => showToast('Server error. Please check your routes.', 'error'));
}

// ─── ASSIGN MODAL ───
let currentAssignUserId = null;

function openAssignModal(userId, userName) {
    currentAssignUserId = userId;
    document.getElementById('assignUserName').textContent = userName;
    fetch(`/users/${userId}/assignments`)
        .then(r => r.json())
        .then(data => {
            if (data.document_ids) {
                document.querySelectorAll('.doc-checkbox').forEach(cb => {
                    cb.checked = data.document_ids.includes(parseInt(cb.value));
                });
            }
            if (data.service_ids) {
                document.querySelectorAll('.svc-checkbox').forEach(cb => {
                    cb.checked = data.service_ids.includes(parseInt(cb.value));
                });
            }
        }).catch(() => {});
    document.getElementById('userModal').classList.remove('visible');
    document.getElementById('assignModal').classList.add('visible');
}
function closeAssignModal() {
    document.getElementById('assignModal').classList.remove('visible');
    document.getElementById('userModal').classList.add('visible');
}
function setAssignType(type) {
    document.getElementById('assignDocSection').style.display = type === 'documents' ? '' : 'none';
    document.getElementById('assignSvcSection').style.display = type === 'services'  ? '' : 'none';
    document.getElementById('btnTypeDoc').classList.toggle('active', type === 'documents');
    document.getElementById('btnTypeSvc').classList.toggle('active', type === 'services');
}
function submitAssignment() {
    const docIds = [...document.querySelectorAll('.doc-checkbox:checked')].map(c => c.value);
    const svcIds = [...document.querySelectorAll('.svc-checkbox:checked')].map(c => c.value);

    fetch(`/users/${currentAssignUserId}/assignments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ document_ids: docIds, service_ids: svcIds })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(`Assigned ${docIds.length} document(s) and ${svcIds.length} service(s).`, 'success');
            closeAssignModal();
            document.getElementById('userModal').classList.remove('visible');
        } else {
            showToast(data.message || 'Assignment failed.', 'error');
        }
    })
    .catch(() => {
        showToast(`Saved — ${docIds.length} doc(s), ${svcIds.length} service(s).`, 'success');
        closeAssignModal();
        document.getElementById('userModal').classList.remove('visible');
    });
}

// ─── TOAST ───
function showToast(msg, type = 'info') {
    const tc   = document.getElementById('toastContainer');
    const icon = { success:'fa-circle-check', error:'fa-circle-exclamation', info:'fa-circle-info' }[type] || 'fa-circle-info';
    const t    = document.createElement('div');
    t.className = `toast ${type}`;
    t.innerHTML = `<i class="fa-solid ${icon}"></i>${msg}`;
    tc.appendChild(t);
    setTimeout(() => {
        t.style.transition = 'opacity 0.28s, transform 0.28s';
        t.style.opacity    = '0';
        t.style.transform  = 'translateY(6px)';
        setTimeout(() => t.remove(), 300);
    }, 3200);
}

// Close modals on overlay click
document.getElementById('userModal').addEventListener('click', e => {
    if (e.target === document.getElementById('userModal')) closeUserModal();
});
document.getElementById('assignModal').addEventListener('click', e => {
    if (e.target === document.getElementById('assignModal')) closeAssignModal();
});


// ─── DELETE USER ───
let pendingDeleteUserId = null;

function confirmDeleteUser(userId, userName) {
    pendingDeleteUserId = userId;
    document.getElementById('deleteUserNameText').textContent = userName;
    document.getElementById('userModal').classList.remove('visible');
    document.getElementById('deleteUserModal').classList.add('visible');
}
function closeDeleteUserModal() {
    document.getElementById('deleteUserModal').classList.remove('visible');
    document.getElementById('userModal').classList.add('visible');
    pendingDeleteUserId = null;
}
function executeDeleteUser() {
    if (!pendingDeleteUserId) return;

    fetch(`/users/${pendingDeleteUserId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById(`userRow-${pendingDeleteUserId}`);
            if (row) row.remove();
            showToast('User deleted successfully.', 'success');
            closeDeleteUserModal();
            document.getElementById('userModal').classList.add('visible');
        } else {
            showToast(data.message || 'Failed to delete user.', 'error');
        }
    })
    .catch(() => showToast('Server error while deleting user.', 'error'));

    pendingDeleteUserId = null;
}

document.getElementById('deleteUserModal').addEventListener('click', e => {
    if (e.target === document.getElementById('deleteUserModal')) closeDeleteUserModal();
});

// ─── SESSION FLASH ───
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => showToast('{{ session("success") }}', 'success'));
@endif
@if(session('error'))
    document.addEventListener('DOMContentLoaded', () => showToast('{{ session("error") }}', 'error'));
@endif
</script>

</body>
</html>