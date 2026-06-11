@extends('layouts.app')

@section('title', 'Dashboard - DSB Documentation System')

@section('content')
<style>
.dash { width: 100%; padding: 0 4px; box-sizing: border-box; display: flex; flex-direction: column; }

/* ── Greeting ── */
.dash-greeting { margin-bottom: 24px; }
.dash-greeting h1 {
    font-size: 27px; font-weight: 800; color: #e8f0fc;
    letter-spacing: -0.5px; line-height: 1.2; margin-bottom: 6px;
}
.dash-greeting p { font-size: 14px; color: #45617e; }

/* ── Stats ── */
.stats-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 14px; margin-bottom: 14px;
}
.stat-card {
    background: #0c1626; border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px; padding: 20px 22px 18px;
    transition: border-color 0.2s;
}
.stat-card:hover { border-color: rgba(255,255,255,0.12); }
.stat-top { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 14px; }
.stat-icon {
    width: 48px; height: 48px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
}
.stat-icon.blue   { background: rgba(59,130,246,0.14); color: #5ca3e8; }
.stat-icon.green  { background: rgba(52,211,153,0.13); color: #34d399; }
.stat-icon.amber  { background: rgba(251,146,60,0.12); color: #fb923c; }
.stat-label { font-size: 12.5px; color: #3f5a78; font-weight: 500; margin-bottom: 4px; }
.stat-value { font-size: 34px; font-weight: 800; color: #e4edf8; line-height: 1; }
.stat-divider { border: none; border-top: 1px solid rgba(255,255,255,0.055); margin: 0 0 12px; }
.stat-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; color: #3f5a78; text-decoration: none; transition: color 0.15s;
}
.stat-link:hover { color: #7ab3e8; }
.stat-link i { font-size: 10px; }

/* ── Action Cards ── */
.actions-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 14px; margin-bottom: 14px;
}
.action-card {
    background: #0c1626; border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px; padding: 22px 22px 0;
    text-decoration: none; display: flex; flex-direction: column;
    gap: 9px; position: relative; overflow: hidden;
    min-height: 170px;
    transition: transform 0.18s, border-color 0.18s;
    cursor: pointer;
}
.action-card:hover { transform: translateY(-3px); border-color: rgba(255,255,255,0.13); }
.action-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
    position: relative; z-index: 2; transition: transform 0.18s;
}
.action-card:hover .action-icon { transform: scale(1.08); }
.action-icon.blue   { background: rgba(59,130,246,0.15); color: #5ca3e8; }
.action-icon.green  { background: rgba(52,211,153,0.13); color: #34d399; }
.action-icon.purple { background: rgba(167,139,250,0.14); color: #c084fc; }
.action-title { font-size: 15px; font-weight: 700; color: #dce8f8; margin-bottom: 4px; position: relative; z-index: 2; }
.action-desc { font-size: 12px; color: #4e6e90; line-height: 1.6; position: relative; z-index: 2; }
.action-cta {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700;
    position: relative; z-index: 2; padding-bottom: 18px;
}
.action-cta.blue   { color: #5ca3e8; }
.action-cta.green  { color: #34d399; }
.action-cta.purple { color: #c084fc; }
.action-cta i { font-size: 10px; }
.wave-deco { position: absolute; bottom: 0; left: 0; right: 0; height: 100%; z-index: 1; pointer-events: none; }

/* ── Bottom Row — FIXED HEIGHT, never grows ── */
.bottom-row {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 14px;
    height: 310px;      /* hard cap */
    min-height: 0;
    overflow: hidden;
}

/* Overview panel */
.overview-panel {
    background: #0c1626; border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px; padding: 20px 22px 16px;
    display: flex; flex-direction: column;
    min-height: 0; overflow: hidden;
}
.panel-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 10px; flex-shrink: 0;
}
.panel-title { font-size: 14.5px; font-weight: 700; color: #d4e3f5; }
.week-pill {
    display: flex; align-items: center; gap: 6px;
    font-size: 11.5px; color: #3f5a78;
    background: rgba(255,255,255,0.042);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 8px; padding: 5px 10px; cursor: pointer;
    transition: background 0.15s; position: relative;
}
.week-pill:hover { background: rgba(255,255,255,0.06); }

/* Period dropdown */
.period-dropdown {
    position: absolute; top: calc(100% + 6px); right: 0;
    background: #0d1b30; border: 1px solid rgba(255,255,255,0.09);
    border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    min-width: 130px; z-index: 50; display: none; overflow: hidden;
}
.period-dropdown.open { display: block; }
.period-option {
    padding: 9px 14px; font-size: 12.5px; color: #6d8aaa;
    cursor: pointer; transition: background 0.12s;
}
.period-option:hover, .period-option.active { background: rgba(59,130,246,0.1); color: #7ab3e8; }

/* Chart legend */
.chart-legend {
    display: flex; gap: 16px; margin-bottom: 8px; flex-shrink: 0;
}
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #3f5a78; }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; }

/* Chart fills remaining space */
.chart-container { position: relative; flex: 1; min-height: 0; }

/* Activity panel */
.activity-panel {
    background: #0c1626; border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px; padding: 16px 18px 14px;
    display: flex; flex-direction: column;
    min-height: 0; overflow: hidden;
}
.view-all-link { font-size: 12px; font-weight: 700; color: #4080c8; text-decoration: none; transition: color 0.15s; }
.view-all-link:hover { color: #7ab3e8; }

.activity-list { flex: 1; overflow-y: auto; min-height: 0; }
.activity-item {
    display: flex; align-items: center; gap: 11px;
    padding: 9px 0;
    border-bottom: 1px solid rgba(255,255,255,0.044);
}
.activity-item:first-child { padding-top: 0; }
.activity-item:last-child  { border-bottom: none; padding-bottom: 0; }
.activity-icon {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; flex-shrink: 0;
}
.activity-icon.blue   { background: rgba(59,130,246,0.13); color: #5ca3e8; }
.activity-icon.green  { background: rgba(52,211,153,0.11); color: #34d399; }
.activity-icon.amber  { background: rgba(251,146,60,0.10); color: #fb923c; }
.activity-icon.purple { background: rgba(167,139,250,0.12); color: #c084fc; }
.activity-body { flex: 1; min-width: 0; }
.activity-title { font-size: 12px; font-weight: 600; color: #c8daee; line-height: 1.3; margin-bottom: 1px; }
.activity-sub   { font-size: 10.5px; color: #4e6e90; line-height: 1.3; }
.activity-meta  { display: flex; align-items: center; gap: 5px; flex-shrink: 0; }
.activity-time  { font-size: 10.5px; color: #3f5a78; white-space: nowrap; }
.dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.dot.blue  { background: #3b82f6; }
.dot.green { background: #10b981; }
.dot.amber { background: #f59e0b; }
.dot.purple{ background: #a78bfa; }

.activity-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 20px 0; text-align: center;
    color: #4e6e90; font-size: 12px;
}
.activity-empty i { font-size: 20px; margin-bottom: 8px; display: block; }

/* ── Add Client Modal ── */
@keyframes acm-in {
    from { opacity:0; transform:scale(0.96) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
</style>

@php
    use App\Models\Client;
    use App\Models\ClientDocument;
    use App\Models\Service;

    $totalClients    = Client::count();
    $completedCount  = Client::where('status','Completed')->count();
    $pendingCount    = Client::where('status','Pending')->count();

    $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i));
    $chartLabels     = $days->map(fn($d) => $d->format('D'))->toArray();
    $clientsPerDay   = $days->map(fn($d) => Client::whereDate('created_at', $d->toDateString())->count())->toArray();
    $docsPerDay      = $days->map(fn($d) => ClientDocument::whereDate('created_at', $d->toDateString())->count())->toArray();

    $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));
    $monthLabels     = $months->map(fn($m) => $m->format('M'))->toArray();
    $clientsPerMonth = $months->map(fn($m) => Client::whereYear('created_at',$m->year)->whereMonth('created_at',$m->month)->count())->toArray();
    $docsPerMonth    = $months->map(fn($m) => ClientDocument::whereYear('created_at',$m->year)->whereMonth('created_at',$m->month)->count())->toArray();

    $weeks = collect(range(3, 0))->map(fn($i) => now()->startOfWeek()->subWeeks($i));
    $weekLabels      = $weeks->map(fn($w) => 'Wk '.$w->format('M j'))->toArray();
    $clientsPerWeek  = $weeks->map(fn($w) => Client::whereBetween('created_at', [$w->copy()->startOfWeek(), $w->copy()->endOfWeek()])->count())->toArray();
    $docsPerWeek     = $weeks->map(fn($w) => ClientDocument::whereBetween('created_at', [$w->copy()->startOfWeek(), $w->copy()->endOfWeek()])->count())->toArray();

    $recentActivity = collect();

    Client::latest()->take(5)->get()->each(fn($c) => $recentActivity->push([
        'icon'=>'blue','fa'=>'fa-user-check',
        'title'=>'Client registered','sub'=>$c->client_name,'time'=>$c->created_at
    ]));

    ClientDocument::latest()->take(5)->get()->each(fn($d) => $recentActivity->push([
        'icon'=>'green','fa'=>'fa-file-arrow-up',
        'title'=>'Document added','sub'=>$d->title,'time'=>$d->created_at
    ]));

    Service::latest()->take(3)->get()->each(fn($s) => $recentActivity->push([
        'icon'=>'amber','fa'=>'fa-briefcase',
        'title'=>'Service updated','sub'=>$s->name,'time'=>$s->created_at
    ]));

    $recentActivity = $recentActivity->sortByDesc('time')->take(8)->values();
@endphp

<div class="dash">

    {{-- Greeting --}}
    <div class="dash-greeting">
        <h1>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()?->name ?? 'Admin' }}! </h1>
        <p>Here's a quick overview of your system — {{ now()->format('l, F j, Y') }}.</p>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
                <div>
                    <div class="stat-label">Total Clients</div>
                    <div class="stat-value" id="totalClientsVal">{{ $totalClients }}</div>
                </div>
            </div>
            <hr class="stat-divider">
            <a href="{{ route('clients.index') }}" class="stat-link">
                View all clients <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <div class="stat-label">Completed</div>
                    <div class="stat-value">{{ $completedCount }}</div>
                </div>
            </div>
            <hr class="stat-divider">
            <a href="{{ route('clients.index') }}?status=Completed" class="stat-link">
                View completed <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon amber"><i class="fa-regular fa-clock"></i></div>
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $pendingCount }}</div>
                </div>
            </div>
            <hr class="stat-divider">
            <a href="{{ route('clients.index') }}?status=Pending" class="stat-link">
                View pending <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    {{-- ── ACTION CARDS ── --}}
    <div class="actions-grid">
        <a href="{{ route('services.index') }}" class="action-card">
            <div class="action-icon blue"><i class="fa-solid fa-briefcase"></i></div>
            <div>
                <div class="action-title">Services</div>
                <div class="action-desc">Browse, add, and manage all professional services available.</div>
            </div>
            <div class="action-cta blue">View Services <i class="fa-solid fa-arrow-right"></i></div>
            <svg class="wave-deco" viewBox="0 0 400 100" preserveAspectRatio="none">
                <path d="M0,60 C60,35 120,75 180,55 C240,35 300,65 360,50 L400,50 L400,100 L0,100Z" fill="none" stroke="rgba(92,163,232,0.18)" stroke-width="1.5"/>
                <path d="M0,72 C50,55 110,82 175,65 C240,48 310,75 380,62 L400,60 L400,100 L0,100Z" fill="none" stroke="rgba(92,163,232,0.10)" stroke-width="1.2"/>
            </svg>
        </a>
        <a href="{{ route('documents.index') }}" class="action-card">
            <div class="action-icon green"><i class="fa-regular fa-folder-open"></i></div>
            <div>
                <div class="action-title">Documentation</div>
                <div class="action-desc">View, upload, and manage client documents and files.</div>
            </div>
            <div class="action-cta green">View Library <i class="fa-solid fa-arrow-right"></i></div>
            <svg class="wave-deco" viewBox="0 0 400 100" preserveAspectRatio="none">
                <path d="M0,60 C60,35 120,75 180,55 C240,35 300,65 360,50 L400,50 L400,100 L0,100Z" fill="none" stroke="rgba(52,211,153,0.18)" stroke-width="1.5"/>
                <path d="M0,72 C50,55 110,82 175,65 C240,48 310,75 380,62 L400,60 L400,100 L0,100Z" fill="none" stroke="rgba(52,211,153,0.10)" stroke-width="1.2"/>
            </svg>
        </a>
        {{-- New Client — opens modal --}}
        <div class="action-card" onclick="openAddClientModal()">
            <div class="action-icon purple"><i class="fa-solid fa-user-plus"></i></div>
            <div>
                <div class="action-title">New Client</div>
                <div class="action-desc">Register a new client and assign a service or upload their documents.</div>
            </div>
            <div class="action-cta purple">Add New Client <i class="fa-solid fa-arrow-right"></i></div>
            <svg class="wave-deco" viewBox="0 0 400 100" preserveAspectRatio="none">
                <path d="M0,60 C60,35 120,75 180,55 C240,35 300,65 360,50 L400,50 L400,100 L0,100Z" fill="none" stroke="rgba(192,132,252,0.18)" stroke-width="1.5"/>
                <path d="M0,72 C50,55 110,82 175,65 C240,48 310,75 380,62 L400,60 L400,100 L0,100Z" fill="none" stroke="rgba(192,132,252,0.10)" stroke-width="1.2"/>
            </svg>
        </div>
    </div>

    {{-- ── BOTTOM ROW ── --}}
    <div class="bottom-row">

        {{-- Overview Chart --}}
        <div class="overview-panel">
            <div class="panel-header">
                <div class="panel-title">Overview</div>
                <div style="position:relative;">
                    <div class="week-pill" id="periodPill" onclick="togglePeriodDropdown()">
                        <span id="periodLabel">This Week</span>
                        <i class="fa-solid fa-chevron-down" style="font-size:9px;"></i>
                        <div class="period-dropdown" id="periodDropdown">
                            <div class="period-option active" onclick="setPeriod('week', event)">This Week</div>
                            <div class="period-option" onclick="setPeriod('month', event)">This Month</div>
                            <div class="period-option" onclick="setPeriod('6months', event)">6 Months</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-legend">
                <div class="legend-item"><div class="legend-dot" style="background:#3b82f6;"></div> Clients</div>
                <div class="legend-item"><div class="legend-dot" style="background:#10b981;"></div> Documents</div>
            </div>

            <div class="chart-container">
                <canvas id="overviewChart"></canvas>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="activity-panel">
            <div class="panel-header" style="flex-shrink:0; margin-bottom:10px;">
                <div class="panel-title">Daily Activity</div>
                <a href="{{ route('clients.index') }}" class="view-all-link">View All</a>
            </div>

            <div class="activity-list">
                @forelse($recentActivity as $act)
                <div class="activity-item">
                    <div class="activity-icon {{ $act['icon'] }}">
                        <i class="fa-solid {{ $act['fa'] }}"></i>
                    </div>
                    <div class="activity-body">
                        <div class="activity-title">{{ $act['title'] }}</div>
                        <div class="activity-sub">{{ Str::limit($act['sub'], 28) }}</div>
                    </div>
                    <div class="activity-meta">
                        <span class="activity-time">{{ $act['time']->diffForHumans(null, true) }}</span>
                        <span class="dot {{ $act['icon'] }}"></span>
                    </div>
                </div>
                @empty
                <div class="activity-empty">
                    <i class="fa-regular fa-bell-slash"></i>
                    No recent activity found.<br>
                    <span style="font-size:11px;margin-top:4px;display:block;color:#3f5a78;">Try adding a client or uploading a document.</span>
                </div>
                @endforelse
            </div>
        </div>

    </div>{{-- /bottom-row --}}

</div>{{-- /dash --}}


{{-- ══════════════════════════════════════
     ADD NEW CLIENT MODAL
══════════════════════════════════════ --}}
<div id="addClientModal"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.65);
            backdrop-filter:blur(4px);align-items:center;justify-content:center;"
     onclick="if(event.target===this)closeAddClientModal()">

    <div style="background:#0d1b30;border:1px solid rgba(255,255,255,0.09);border-radius:18px;
                width:100%;max-width:480px;margin:0 16px;overflow:hidden;
                box-shadow:0 24px 80px rgba(0,0,0,0.6);animation:acm-in 0.2s ease;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:18px 24px 14px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <div>
                <div id="acmTitle" style="font-size:14px;font-weight:700;color:#e4edf8;">Add New Client</div>
                <div style="display:flex;align-items:center;gap:5px;margin-top:6px;">
                    <div class="acm-dot" data-s="1" style="width:6px;height:6px;border-radius:50%;background:#3b82f6;transition:background 0.2s;"></div>
                    <div class="acm-dot" data-s="2" style="width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,0.15);transition:background 0.2s;"></div>
                    <div class="acm-dot" data-s="3" style="width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,0.15);transition:background 0.2s;"></div>
                    <span id="acmStepLabel" style="font-size:10px;color:#4e6e90;margin-left:4px;">Step 1 of 3</span>
                </div>
            </div>
            <button onclick="closeAddClientModal()"
                    style="width:28px;height:28px;border-radius:8px;border:none;background:rgba(255,255,255,0.05);
                           color:#4e6180;font-size:14px;font-weight:700;cursor:pointer;">✕</button>
        </div>

        {{-- Step 1: Choose type --}}
        <div id="acmStep1" style="padding:22px 24px;">
            <p style="font-size:12px;color:#7a95b0;margin-bottom:18px;">Choose what you'd like to add this client to:</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

                <button type="button" onclick="acmSelectType('services')" id="acmCardServices"
                        style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:20px 12px;
                               border-radius:14px;border:2px solid rgba(255,255,255,0.07);background:rgba(255,255,255,0.02);
                               cursor:pointer;transition:all 0.18s;text-align:center;font-family:'Inter',sans-serif;">
                    <div style="width:44px;height:44px;border-radius:12px;background:rgba(59,130,246,0.12);
                                color:#5ca3e8;display:flex;align-items:center;justify-content:center;font-size:20px;">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#dce8f8;">Services</div>
                        <div style="font-size:11px;color:#4e6e90;margin-top:2px;">Assign a service type</div>
                    </div>
                </button>

                <button type="button" onclick="acmSelectType('documents')" id="acmCardDocuments"
                        style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:20px 12px;
                               border-radius:14px;border:2px solid rgba(255,255,255,0.07);background:rgba(255,255,255,0.02);
                               cursor:pointer;transition:all 0.18s;text-align:center;font-family:'Inter',sans-serif;">
                    <div style="width:44px;height:44px;border-radius:12px;background:rgba(52,211,153,0.11);
                                color:#34d399;display:flex;align-items:center;justify-content:center;font-size:20px;">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#dce8f8;">Documents</div>
                        <div style="font-size:11px;color:#4e6e90;margin-top:2px;">Upload files only</div>
                    </div>
                </button>

            </div>
        </div>

        {{-- Step 2: Client details --}}
        <div id="acmStep2" style="display:none;padding:20px 24px;">
            <div id="acmBadge" style="display:inline-flex;align-items:center;gap:7px;padding:5px 12px;
                                      border-radius:20px;font-size:11px;font-weight:700;margin-bottom:16px;"></div>
            <div style="display:flex;flex-direction:column;gap:13px;">
                <div>
                    <label style="display:block;font-size:11.5px;font-weight:600;color:#3f5a78;margin-bottom:5px;">
                        Client Name <span style="color:#f87171;">*</span>
                    </label>
                    <input type="text" id="acmName" placeholder="e.g. Juan dela Cruz"
                           style="width:100%;border:1px solid rgba(255,255,255,0.08);border-radius:10px;
                                  padding:9px 13px;background:rgba(255,255,255,0.04);color:#dce8f8;
                                  font-size:13px;outline:none;font-family:'Inter',sans-serif;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:11.5px;font-weight:600;color:#3f5a78;margin-bottom:5px;">
                        Date Received <span style="color:#f87171;">*</span>
                    </label>
                    <input type="date" id="acmDate"
                           style="width:100%;border:1px solid rgba(255,255,255,0.08);border-radius:10px;
                                  padding:9px 13px;background:rgba(255,255,255,0.04);color:#dce8f8;
                                  font-size:13px;outline:none;font-family:'Inter',sans-serif;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;font-size:11.5px;font-weight:600;color:#3f5a78;margin-bottom:5px;">Status</label>
                    <select id="acmStatus"
                            style="width:100%;border:1px solid rgba(255,255,255,0.08);border-radius:10px;
                                   padding:9px 13px;background:#0c1626;color:#dce8f8;
                                   font-size:13px;outline:none;font-family:'Inter',sans-serif;box-sizing:border-box;">
                        <option value="Pending">Pending</option>
                        <option value="On going">On going</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div id="acmServiceWrap" style="display:none;">
                    <label style="display:block;font-size:11.5px;font-weight:600;color:#3f5a78;margin-bottom:5px;">Select Service</label>
                    <select id="acmService"
                            style="width:100%;border:1px solid rgba(255,255,255,0.08);border-radius:10px;
                                   padding:9px 13px;background:#0c1626;color:#dce8f8;
                                   font-size:13px;outline:none;font-family:'Inter',sans-serif;box-sizing:border-box;">
                        <option value="">— Choose a service —</option>
                        @foreach(\App\Models\Service::orderBy('name')->get() as $svc)
                        <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Step 3: Review & confirm --}}
        <div id="acmStep3" style="display:none;padding:20px 24px;">
            <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);
                        border-radius:12px;padding:14px 16px;margin-bottom:14px;">
                <div style="display:flex;flex-direction:column;gap:9px;font-size:12px;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#3f5a78;">Client</span>
                        <span style="color:#dce8f8;font-weight:600;" id="acmSumName">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#3f5a78;">Adding to</span>
                        <span style="font-weight:700;" id="acmSumType">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#3f5a78;">Status</span>
                        <span style="color:#dce8f8;font-weight:600;" id="acmSumStatus">—</span>
                    </div>
                    <div id="acmSumSvcRow" style="display:flex;justify-content:space-between;">
                        <span style="color:#3f5a78;">Service</span>
                        <span style="color:#dce8f8;font-weight:600;" id="acmSumSvc">—</span>
                    </div>
                </div>
            </div>
            <div id="acmDocUpload" style="display:none;">
                <label style="display:block;font-size:11.5px;font-weight:600;color:#3f5a78;margin-bottom:5px;">
                    Attach Files <span style="color:#4e6e90;font-weight:400;">(optional)</span>
                </label>
                <input type="file" id="acmFiles" multiple style="display:none;"
                       onchange="document.getElementById('acmFileCount').textContent=this.files.length?this.files.length+' file(s) selected':'No files selected'">
                <div onclick="document.getElementById('acmFiles').click()"
                     style="border:2px dashed rgba(255,255,255,0.07);border-radius:12px;padding:18px;
                            display:flex;flex-direction:column;align-items:center;gap:6px;cursor:pointer;">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:22px;color:#4e6e90;"></i>
                    <span style="font-size:12px;font-weight:600;color:#7a95b0;">Click to browse</span>
                    <span id="acmFileCount" style="font-size:11px;color:#4e6e90;">No files selected</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:14px 24px;border-top:1px solid rgba(255,255,255,0.07);background:rgba(255,255,255,0.01);">
            <button id="acmBtnBack" onclick="acmBack()"
                    style="display:none;background:none;border:none;color:#3f5a78;font-size:12px;font-weight:600;
                           cursor:pointer;font-family:'Inter',sans-serif;align-items:center;gap:5px;">
                <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i> Back
            </button>
            <div style="margin-left:auto;display:flex;align-items:center;gap:8px;">
                <button onclick="closeAddClientModal()"
                        style="padding:8px 16px;border-radius:9px;border:none;background:rgba(255,255,255,0.04);
                               color:#7a95b0;font-size:12px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
                    Cancel
                </button>
                <button id="acmBtnNext" onclick="acmNext()" disabled
                        style="padding:8px 20px;border-radius:9px;border:none;background:#2563eb;color:#fff;
                               font-size:12px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;
                               display:flex;align-items:center;gap:6px;opacity:0.4;">
                    Next <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                </button>
                <button id="acmBtnSubmit" onclick="acmSubmit()"
                        style="display:none;padding:8px 20px;border-radius:9px;border:none;background:#059669;color:#fff;
                               font-size:12px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;
                               align-items:center;gap:6px;">
                    <i class="fa-solid fa-check"></i> Create Client
                </button>
            </div>
        </div>

    </div>
</div>


{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Chart ──
const chartData = {
    week:     { labels: @json($chartLabels),  clients: @json($clientsPerDay),   docs: @json($docsPerDay) },
    '6months':{ labels: @json($monthLabels),  clients: @json($clientsPerMonth), docs: @json($docsPerMonth) },
    month:    { labels: @json($weekLabels),   clients: @json($clientsPerWeek),  docs: @json($docsPerWeek) }
};

let currentPeriod = 'week';
let overviewChart;

function buildChart(period) {
    const d   = chartData[period] || chartData.week;
    const ctx = document.getElementById('overviewChart').getContext('2d');
    const g1  = ctx.createLinearGradient(0,0,0,180);
    g1.addColorStop(0,'rgba(59,130,246,0.22)'); g1.addColorStop(1,'rgba(59,130,246,0)');
    const g2  = ctx.createLinearGradient(0,0,0,180);
    g2.addColorStop(0,'rgba(16,185,129,0.18)'); g2.addColorStop(1,'rgba(16,185,129,0)');
    if (overviewChart) overviewChart.destroy();
    overviewChart = new Chart(ctx, {
        type: 'line',
        data: { labels: d.labels, datasets: [
            { label:'Clients',   data:d.clients, borderColor:'#3b82f6', backgroundColor:g1, borderWidth:2.5, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#3b82f6', pointBorderColor:'#07111f', pointBorderWidth:2, tension:0.42, fill:true },
            { label:'Documents', data:d.docs,    borderColor:'#10b981', backgroundColor:g2, borderWidth:2.5, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#10b981', pointBorderColor:'#07111f', pointBorderWidth:2, tension:0.42, fill:true }
        ]},
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction:{ mode:'index', intersect:false },
            plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#0d1b30', borderColor:'rgba(255,255,255,0.09)', borderWidth:1, titleColor:'#d4e3f5', bodyColor:'#7ab3e8', padding:10, cornerRadius:10, callbacks:{ title:i=>i[0].label, label:i=>` ${i.dataset.label}: ${i.parsed.y}` } } },
            scales:{
                x:{ grid:{color:'rgba(255,255,255,0.04)',drawBorder:false}, ticks:{color:'#2b3d54',font:{family:'Inter',size:11}}, border:{display:false} },
                y:{ beginAtZero:true, grid:{color:'rgba(255,255,255,0.04)',drawBorder:false}, ticks:{color:'#2b3d54',font:{family:'Inter',size:11},stepSize:1,precision:0}, border:{display:false} }
            }
        }
    });
}

function togglePeriodDropdown() { document.getElementById('periodDropdown').classList.toggle('open'); }
function setPeriod(period, e) {
    e.stopPropagation();
    currentPeriod = period;
    const labels = { week:'This Week', month:'This Month', '6months':'6 Months' };
    document.getElementById('periodLabel').textContent = labels[period];
    document.querySelectorAll('.period-option').forEach(el => el.classList.remove('active'));
    e.target.classList.add('active');
    document.getElementById('periodDropdown').classList.remove('open');
    buildChart(period);
}
document.addEventListener('click', e => {
    if (!document.getElementById('periodPill').contains(e.target))
        document.getElementById('periodDropdown').classList.remove('open');
});
document.addEventListener('DOMContentLoaded', () => buildChart('week'));

setInterval(() => {
    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text()).then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const nl  = doc.querySelector('.activity-list');
            if (nl) document.querySelector('.activity-list').innerHTML = nl.innerHTML;
            const el = doc.getElementById('totalClientsVal');
            if (el) document.getElementById('totalClientsVal').textContent = el.textContent;
        }).catch(()=>{});
}, 60000);

// ── Add New Client Modal ──
let _acmType = null, _acmStep = 1;

function openAddClientModal() {
    _acmType = null; _acmStep = 1;
    document.getElementById('acmName').value    = '';
    document.getElementById('acmDate').value    = new Date().toISOString().slice(0,10);
    document.getElementById('acmStatus').value  = 'Pending';
    document.getElementById('acmService').value = '';
    document.getElementById('acmFiles').value   = '';
    document.getElementById('acmFileCount').textContent = 'No files selected';
    ['acmCardServices','acmCardDocuments'].forEach(id => {
        const el = document.getElementById(id);
        el.style.borderColor = 'rgba(255,255,255,0.07)';
        el.style.background  = 'rgba(255,255,255,0.02)';
    });
    const nb = document.getElementById('acmBtnNext');
    nb.disabled = true; nb.style.opacity = '0.4';
    acmRender();
    document.getElementById('addClientModal').style.display = 'flex';
}

function closeAddClientModal() {
    document.getElementById('addClientModal').style.display = 'none';
}

function acmSelectType(type) {
    _acmType = type;
    ['acmCardServices','acmCardDocuments'].forEach(id => {
        document.getElementById(id).style.borderColor = 'rgba(255,255,255,0.07)';
        document.getElementById(id).style.background  = 'rgba(255,255,255,0.02)';
    });
    if (type === 'services') {
        document.getElementById('acmCardServices').style.borderColor = 'rgba(59,130,246,0.55)';
        document.getElementById('acmCardServices').style.background  = 'rgba(59,130,246,0.06)';
    } else {
        document.getElementById('acmCardDocuments').style.borderColor = 'rgba(52,211,153,0.55)';
        document.getElementById('acmCardDocuments').style.background  = 'rgba(52,211,153,0.06)';
    }
    const nb = document.getElementById('acmBtnNext');
    nb.disabled = false; nb.style.opacity = '1';
}

function acmRender() {
    ['acmStep1','acmStep2','acmStep3'].forEach((id,i) => {
        document.getElementById(id).style.display = (i+1 === _acmStep) ? 'block' : 'none';
    });
    ['Add New Client','Client Details','Review & Confirm'].forEach((t,i) => {
        if (i+1 === _acmStep) document.getElementById('acmTitle').textContent = t;
    });
    document.getElementById('acmStepLabel').textContent = `Step ${_acmStep} of 3`;
    document.querySelectorAll('.acm-dot').forEach(d => {
        d.style.background = parseInt(d.dataset.s) <= _acmStep ? '#3b82f6' : 'rgba(255,255,255,0.15)';
    });
    const back = document.getElementById('acmBtnBack');
    back.style.display = _acmStep > 1 ? 'flex' : 'none';
    const isLast = _acmStep === 3;
    document.getElementById('acmBtnNext').style.display   = isLast ? 'none' : 'flex';
    document.getElementById('acmBtnSubmit').style.display = isLast ? 'flex' : 'none';

    if (_acmStep === 2) {
        const isSvc = _acmType === 'services';
        const badge = document.getElementById('acmBadge');
        badge.innerHTML = isSvc
            ? '<i class="fa-solid fa-briefcase"></i> Services'
            : '<i class="fa-regular fa-folder-open"></i> Documents';
        badge.style.background = isSvc ? 'rgba(59,130,246,0.12)' : 'rgba(52,211,153,0.11)';
        badge.style.color      = isSvc ? '#5ca3e8' : '#34d399';
        document.getElementById('acmServiceWrap').style.display = isSvc ? 'block' : 'none';
        const nb = document.getElementById('acmBtnNext');
        nb.disabled = false; nb.style.opacity = '1';
    }

    if (_acmStep === 3) {
        const isSvc = _acmType === 'services';
        document.getElementById('acmSumName').textContent   = document.getElementById('acmName').value || '—';
        document.getElementById('acmSumStatus').textContent = document.getElementById('acmStatus').value;
        const st = document.getElementById('acmSumType');
        st.textContent = isSvc ? 'Services' : 'Documents';
        st.style.color = isSvc ? '#5ca3e8' : '#34d399';
        const svcRow = document.getElementById('acmSumSvcRow');
        if (isSvc) {
            svcRow.style.display = 'flex';
            const sel = document.getElementById('acmService');
            document.getElementById('acmSumSvc').textContent = sel.options[sel.selectedIndex]?.text || '—';
        } else { svcRow.style.display = 'none'; }
        document.getElementById('acmDocUpload').style.display = isSvc ? 'none' : 'block';
    }
}

function acmShowError(msg) {
    let el = document.getElementById('acmErrorMsg');
    if (!el) {
        el = document.createElement('div');
        el.id = 'acmErrorMsg';
        el.style.cssText = 'margin:0 24px 12px;padding:9px 13px;border-radius:9px;background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.25);color:#f87171;font-size:12px;font-weight:500;display:flex;align-items:center;gap:7px;';
        el.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i><span></span>';
        document.getElementById('acmStep1').parentNode.insertBefore(el, document.getElementById('acmStep1'));
    }
    el.querySelector('span').textContent = msg;
    el.style.display = 'flex';
    setTimeout(() => { if (el) el.style.display = 'none'; }, 3000);
}

function acmNext() {
    if (_acmStep === 1 && !_acmType) { acmShowError('Please choose Services or Documents first.'); return; }
    if (_acmStep === 2) {
        const nameEl = document.getElementById('acmName');
        const dateEl = document.getElementById('acmDate');
        if (!nameEl.value.trim()) {
            nameEl.style.borderColor = 'rgba(248,113,113,0.5)';
            nameEl.focus();
            acmShowError('Client name is required.');
            return;
        }
        nameEl.style.borderColor = 'rgba(255,255,255,0.08)';
        if (!dateEl.value) {
            dateEl.style.borderColor = 'rgba(248,113,113,0.5)';
            dateEl.focus();
            acmShowError('Date received is required.');
            return;
        }
        dateEl.style.borderColor = 'rgba(255,255,255,0.08)';
    }
    const errEl = document.getElementById('acmErrorMsg');
    if (errEl) errEl.style.display = 'none';
    _acmStep++; acmRender();
}

function acmBack() { if (_acmStep > 1) { _acmStep--; acmRender(); } }

async function acmSubmit() {
    const btn  = document.getElementById('acmBtnSubmit');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating...';
    try {
        const res  = await fetch('/clients', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body: JSON.stringify({
                client_name:   document.getElementById('acmName').value.trim(),
                date_received: document.getElementById('acmDate').value,
                status:        document.getElementById('acmStatus').value,
                service_id:    _acmType === 'services' ? (document.getElementById('acmService').value || null) : null,
            })
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            acmShowError(data.message ?? 'Failed to create client. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Create Client';
            return;
        }
        const cid = data.client.id;
        if (_acmType === 'services') {
            const svcId = document.getElementById('acmService').value;
            if (svcId) await fetch(`/clients/${cid}/services`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({service_id:svcId})}).catch(()=>{});
        }
        if (_acmType === 'documents') {
            const files = document.getElementById('acmFiles').files;
            if (files.length) {
                const fd = new FormData();
                Array.from(files).forEach(f => fd.append('files[]',f));
                await fetch(`/clients/${cid}/documents`,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:fd}).catch(()=>{});
            }
        }
        closeAddClientModal();
        if (typeof showToast === 'function') showToast('Client created successfully!','success');
        setTimeout(() => location.reload(), 900);
    } catch(e) {
        acmShowError('Network error. Please check your connection and try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Create Client';
    }
}
</script>

@endsection