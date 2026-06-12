@extends('layouts.app')

@section('title', 'Documentation & Files - DSB Documentation System')
@section('page_title', 'Documentation & Files')

@section('content')
<style>
* { box-sizing: border-box; }
.docs-wrap {
    width: 100%;
    padding: 1.5rem 2rem;
}

/* Header */
.docs-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
.docs-header h1 { font-size: 1.3rem; font-weight: 800; color: #e2e8f0; margin: 0; letter-spacing: -0.3px; }
.docs-header p  { font-size: 0.78rem; color: #475569; margin: 0.2rem 0 0; }
.header-right   { display: flex; align-items: center; gap: 0.75rem; }

.search-bar { display: flex; align-items: center; background: #0f1e30; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.5rem 0.85rem; gap: 0.5rem; width: 220px; }
.search-bar input { border: none; outline: none; font-size: 0.82rem; color: #cbd5e1; width: 100%; background: transparent; }
.search-bar input::placeholder { color: #475569; }

/* Client card */
.client-card {
    background: #0c1626;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    margin-bottom: 2.5rem;
}
.client-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; cursor: pointer; user-select: none; transition: background 0.15s; border-radius: 14px; }
.client-header.is-open { border-radius: 14px 14px 0 0; border-bottom: 1px solid rgba(255,255,255,0.07); background: rgba(255,255,255,0.02); }
.client-header:hover { background: rgba(255,255,255,0.03); }
.client-info  { display: flex; align-items: center; gap: 0.85rem; }
.client-avatar{ width: 40px; height: 40px; border-radius: 10px; background: rgba(59,130,246,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: #5ca3e8; flex-shrink: 0; }
.client-name  { font-weight: 700; font-size: 0.92rem; color: #dce8f8; }
.client-meta  { font-size: 0.73rem; color: #3f5a78; margin-top: 2px; }
.hdr-right    { display: flex; align-items: center; gap: 0.6rem; }


.badge { font-size: 0.68rem; font-weight: 700; padding: 3px 10px; border-radius: 99px; }
.badge-pending   { background: rgba(251,146,60,0.12); color: #fb923c; }
.badge-ongoing   { background: rgba(59,130,246,0.14); color: #5ca3e8; }
.badge-completed { background: rgba(52,211,153,0.13); color: #34d399; }
.badge-gray      { background: rgba(255,255,255,0.06); color: #64748b; }

.chevron { color: #3f5a78; font-size: 0.75rem; transition: transform 0.22s; }
.chevron.open { transform: rotate(180deg); }

/* Body */
.client-body { display: none; padding: 1.25rem 1.25rem 1.5rem; }
.client-body.open { display: block; }

/* Tabs */
.tab-bar { display: flex; gap: 0.25rem; margin-bottom: 1.1rem; border-bottom: 1px solid rgba(255,255,255,0.07); padding-bottom: 0; }
.tab-btn { font-size: 0.78rem; font-weight: 700; padding: 0.4rem 1rem 0.55rem; border: none; background: none; cursor: pointer; color: #3f5a78; border-bottom: 2.5px solid transparent; margin-bottom: -1px; font-family: inherit; transition: color 0.15s; }
.tab-btn.active { color: #5ca3e8; border-bottom-color: #3b82f6; }
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* Tracker tab */
.tracker-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media(max-width:700px){ .tracker-grid { grid-template-columns: 1fr; } }

.sec-title { font-size: 0.68rem; font-weight: 700; color: #3f5a78; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.65rem; display: flex; align-items: center; gap: 0.4rem; }

.agency-list { display: flex; flex-direction: column; gap: 0.4rem; }
.agency-row  { display: flex; align-items: center; gap: 0.6rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 0.55rem 0.8rem; }
.agency-row:hover { border-color: rgba(59,130,246,0.3); }
.agency-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-gray    { background: #334155; }
.dot-amber   { background: #f59e0b; }
.dot-blue    { background: #3b82f6; }
.dot-teal    { background: #10b981; }
.dot-green   { background: #16a34a; }
.agency-name { font-size: 0.78rem; font-weight: 600; color: #94a3b8; flex: 1; }
.agency-sel  { font-size: 0.72rem; font-weight: 600; border-radius: 7px; border: 1px solid rgba(255,255,255,0.1); padding: 0.22rem 0.45rem; background: #0f1e30; color: #94a3b8; cursor: pointer; outline: none; }
.agency-sel:focus { border-color: #3b82f6; }

.doc-list    { display: flex; flex-direction: column; gap: 0.38rem; }
.doc-row     { display: flex; align-items: center; gap: 0.6rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 0.5rem 0.8rem; }
.doc-row:hover { border-color: rgba(59,130,246,0.3); }
.doc-row.on  { border-color: rgba(59,130,246,0.3); background: rgba(59,130,246,0.06); }
.doc-row.mis { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.05); }
.doc-chk     { width: 15px; height: 15px; border-radius: 4px; cursor: pointer; accent-color: #3b82f6; flex-shrink: 0; }
.doc-label   { font-size: 0.78rem; font-weight: 500; color: #94a3b8; flex: 1; }
.doc-loc-sel { font-size: 0.7rem; font-weight: 600; border-radius: 7px; border: 1px solid rgba(255,255,255,0.1); padding: 0.2rem 0.4rem; background: #0f1e30; color: #64748b; cursor: pointer; outline: none; max-width: 110px; }
.doc-loc-sel:focus { border-color: #3b82f6; }

.notes-area  { width: 100%; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.6rem 0.8rem; font-size: 0.78rem; font-family: inherit; color: #94a3b8; resize: vertical; min-height: 68px; outline: none; background: rgba(255,255,255,0.03); margin-top: 0.5rem; }
.notes-area:focus { border-color: #3b82f6; background: rgba(59,130,246,0.04); }
.notes-area::placeholder { color: #2e4560; }

.action-row  { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem; }
.btn-save    { background: rgba(59,130,246,0.15); color: #5ca3e8; border: 1px solid rgba(59,130,246,0.3); border-radius: 9px; padding: 0.45rem 1.1rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: all 0.15s; }
.btn-save:hover { background: rgba(59,130,246,0.25); }
.btn-save:disabled { opacity: 0.4; cursor: not-allowed; }
.btn-print   { background: rgba(255,255,255,0.05); color: #64748b; border: 1px solid rgba(255,255,255,0.08); border-radius: 9px; padding: 0.45rem 1.1rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: all 0.15s; display: flex; align-items: center; gap: 0.4rem; }
.btn-print:hover { background: rgba(255,255,255,0.08); color: #94a3b8; }

/* Files tab */
.files-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(190px,1fr)); gap: 0.85rem; }
.file-card  { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 1rem; display: flex; flex-direction: column; gap: 0.55rem; transition: all 0.2s; }
.file-card:hover { border-color: rgba(59,130,246,0.3); background: rgba(59,130,246,0.05); }
.file-icon  { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; }
.icon-pdf   { background: rgba(220,38,38,0.12); color: #f87171; }
.icon-img   { background: rgba(52,211,153,0.12); color: #34d399; }
.icon-word  { background: rgba(59,130,246,0.14); color: #5ca3e8; }
.icon-xls   { background: rgba(5,150,105,0.12); color: #34d399; }
.icon-other { background: rgba(255,255,255,0.06); color: #64748b; }
.file-name  { font-size: 0.78rem; font-weight: 600; color: #dce8f8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.file-size  { font-size: 0.7rem; color: #3f5a78; }
.file-acts  { display: flex; gap: 0.45rem; margin-top: auto; }
.f-btn      { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.3rem; padding: 0.32rem 0; border-radius: 7px; font-size: 0.7rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; transition: background 0.15s; }
.f-dl  { background: rgba(59,130,246,0.14); color: #5ca3e8; }
.f-dl:hover  { background: rgba(59,130,246,0.25); }
.f-del { background: rgba(220,38,38,0.12); color: #f87171; }
.f-del:hover { background: rgba(220,38,38,0.22); }
.no-files   { padding: 2.5rem; text-align: center; color: #3f5a78; font-size: 0.82rem; }
.upload-zone{ border: 2px dashed rgba(255,255,255,0.08); border-radius: 12px; padding: 1.5rem; text-align: center; color: #3f5a78; cursor: pointer; transition: all 0.15s; margin-bottom: 1rem; }
.upload-zone:hover { border-color: rgba(59,130,246,0.4); background: rgba(59,130,246,0.04); color: #5ca3e8; }
.upload-zone i { font-size: 1.5rem; display: block; margin-bottom: 0.4rem; }
.upload-zone span { font-size: 0.78rem; font-weight: 600; }

/* Toast */
#toast { display:none; position:fixed; top:1rem; right:1rem; z-index:999; padding:0.65rem 1.2rem; border-radius:10px; font-size:0.82rem; font-weight:700; color:#fff; box-shadow:0 4px 24px rgba(0,0,0,0.4); }
#toast.ok  { display:block; background:#10b981; }
#toast.err { display:block; background:#ef4444; }

/* Empty */
.empty-state { text-align:center; padding:4rem 2rem; color:#3f5a78; }
.empty-state i { font-size:3rem; display:block; margin-bottom:1rem; opacity:0.3; }

/* Print */
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: fixed; top: 0; left: 0; width: 100%; padding: 2rem; background: #fff; color: #1e293b; }
    .no-print { display: none !important; }
    .print-header { margin-bottom: 1.5rem; border-bottom: 2px solid #1e293b; padding-bottom: 1rem; }
    .print-header h2 { font-size: 1.3rem; font-weight: 800; margin: 0 0 0.2rem; }
    .print-header p  { font-size: 0.8rem; color: #64748b; margin: 0; }
    .print-section { margin-bottom: 1.2rem; }
    .print-section h3 { font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #475569; margin: 0 0 0.5rem; }
    .print-row { display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.82rem; }
    .print-notes { font-size: 0.82rem; color: #475569; padding: 0.5rem; background: #f8fafc; border-radius: 6px; white-space: pre-wrap; }
    .print-footer { margin-top: 2rem; font-size: 0.75rem; color: #94a3b8; text-align: center; }
}
</style>

<div class="print-area" id="print-area" style="display:none;"></div>

<div class="docs-wrap">

    <div class="docs-header">
        <div>
            <h1>Documentation & Files</h1>
            <p>Track documents per agency · manage uploaded files per client</p>
        </div>
        <div class="header-right">
            <form method="GET" class="search-bar">
                <i class="fa-solid fa-magnifying-glass" style="color:#3f5a78;font-size:0.8rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client..." onchange="this.form.submit()">
            </form>
        </div>
    </div>

    @forelse($clients as $client)
    @php
        $statusClass = match($client->status) {
            'Completed' => 'badge-completed',
            'On going'  => 'badge-ongoing',
            default     => 'badge-pending',
        };
        $initial   = strtoupper(substr($client->client_name, 0, 1));
        // Load tracker data
        $tracker   = is_array($client->tracker_data) ? $client->tracker_data : (json_decode($client->tracker_data ?? '{}', true) ?? []);
        $tAgencies = $tracker['agencies'] ?? [];
        $tDocs     = $tracker['docs']     ?? [];
        $tNotes    = $tracker['notes']    ?? '';
        $docCount  = $client->documents->count();

        $agencyList = [
            'ROD'       => 'Register of Deeds (ROD)',
            'LRA'       => 'Land Registration Authority (LRA)',
            'DAR'       => 'Dept. of Agrarian Reform (DAR)',
            'DENR'      => 'Dept. of Env. & Natural Resources (DENR)',
            'ASSESSORS' => "Assessor's Office",
            'TREASURY'  => 'Treasury Office',
        ];
        $docList = [
            'tax_dec'        => 'Tax Declaration',
            'birth_cert'     => 'Birth Certificate',
            'survey_plan'    => 'Survey Plan',
            'id_copy'        => 'ID Copy',
            'land_title'     => 'Land Title Copy',
            'brgy_clearance' => 'Barangay Clearance',
            'spa'            => 'SPA',
            'deed_of_sale'   => 'Deed of Sale',
            'property_title' => 'Property Title',
        ];
        $agencyStatuses = ['' => '— Status —', 'waiting' => 'Waiting', 'processing' => 'Processing', 'submitted' => 'Submitted', 'released' => 'Released', 'done' => 'Done'];
        $dotMap = ['waiting'=>'dot-gray','processing'=>'dot-amber','submitted'=>'dot-blue','released'=>'dot-teal','done'=>'dot-green'];
    @endphp

    <div class="client-card" id="card-{{ $client->id }}">

        <div class="client-header is-open" id="hdr-{{ $client->id }}" onclick="toggleCard({{ $client->id }})">
            <div class="client-info">
                <div class="client-avatar">{{ $initial }}</div>
                <div>
                    <div class="client-name">{{ $client->client_name }}</div>
                    <div class="client-meta">
                        {{ $client->service->name ?? '—' }} &nbsp;·&nbsp;
                        Received {{ \Carbon\Carbon::parse($client->date_received)->format('M j, Y') }}
                    </div>
                </div>
            </div>
            <div class="hdr-right">
                <span class="badge badge-gray">{{ $docCount }} {{ Str::plural('file',$docCount) }}</span>
                <span class="badge {{ $statusClass }}">{{ $client->status }}</span>
                <i class="fa-solid fa-chevron-down chevron open" id="chev-{{ $client->id }}"></i>
            </div>
        </div>

        <div class="client-body open" id="body-{{ $client->id }}">

            <div class="tab-bar no-print">
                <button class="tab-btn active" onclick="switchTab({{ $client->id }},'tracker',this)">
                    <i class="fa-solid fa-map-location-dot"></i> Document Tracker
                </button>
                <button class="tab-btn" onclick="switchTab({{ $client->id }},'files',this)">
                    <i class="fa-regular fa-folder-open"></i> Uploaded Files
                    @if($docCount > 0)<span style="margin-left:4px;background:rgba(59,130,246,0.14);color:#5ca3e8;border-radius:99px;padding:1px 7px;font-size:0.65rem;">{{ $docCount }}</span>@endif
                </button>
            </div>

            {{-- TAB 1: DOCUMENT TRACKER --}}
            <div class="tab-panel active" id="tab-tracker-{{ $client->id }}">
                <div class="tracker-grid">

                    <div>
                        <div class="sec-title"><i class="fa-solid fa-building-columns"></i> Agency Tracker — kung saan na ang papers</div>
                        <div class="agency-list">
                            @foreach($agencyList as $akey => $alabel)
                            @php $aStat = $tAgencies[$akey]['status'] ?? ''; $dotCls = $dotMap[$aStat] ?? 'dot-gray'; @endphp
                            <div class="agency-row">
                                <div class="agency-dot {{ $dotCls }}" id="dot-{{ $client->id }}-{{ $akey }}"></div>
                                <div class="agency-name">{{ $alabel }}</div>
                                <select class="agency-sel" data-client="{{ $client->id }}" data-agency="{{ $akey }}"
                                        onchange="updateDot(this);markDirty({{ $client->id }})">
                                    @foreach($agencyStatuses as $val => $lbl)
                                    <option value="{{ $val }}" {{ $aStat===$val?'selected':'' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="sec-title"><i class="fa-solid fa-list-check"></i> Documents — status at location ng bawat papel</div>
                        <div class="doc-list">
                            @foreach($docList as $dkey => $dlabel)
                            @php $dChecked = $tDocs[$dkey]['submitted'] ?? false; $dLocation = $tDocs[$dkey]['location'] ?? ''; $rowCls = $dChecked ? 'on' : ($dLocation==='missing'?'mis':''); @endphp
                            <div class="doc-row {{ $rowCls }}" id="drow-{{ $client->id }}-{{ $dkey }}">
                                <input type="checkbox" class="doc-chk" data-client="{{ $client->id }}" data-doc="{{ $dkey }}"
                                       {{ $dChecked?'checked':'' }} onchange="updateDocRow(this);markDirty({{ $client->id }})">
                                <span class="doc-label">{{ $dlabel }}</span>
                                <select class="doc-loc-sel" data-client="{{ $client->id }}" data-doc="{{ $dkey }}" onchange="markDirty({{ $client->id }})">
                                    <option value="">— Loc —</option>
                                    @foreach(array_keys($agencyList) as $ak)
                                    <option value="{{ $ak }}" {{ $dLocation===$ak?'selected':'' }}>{{ $ak }}</option>
                                    @endforeach
                                    <option value="office"  {{ $dLocation==='office' ?'selected':'' }}>Office</option>
                                    <option value="client"  {{ $dLocation==='client' ?'selected':'' }}>Client</option>
                                    <option value="missing" {{ $dLocation==='missing'?'selected':'' }}>Missing</option>
                                </select>
                            </div>
                            @endforeach
                        </div>
                        <div class="sec-title" style="margin-top:1rem;"><i class="fa-solid fa-note-sticky"></i> Notes / Updates</div>
                        <textarea class="notes-area" data-client="{{ $client->id }}"
                                  placeholder="Mga updates, remarks, o instructions..."
                                  oninput="markDirty({{ $client->id }})">{{ $tNotes }}</textarea>
                    </div>

                </div>

                <div class="action-row no-print">
                    <button class="btn-print" onclick="printClient({{ $client->id }},'{{ addslashes($client->client_name) }}','{{ $client->service->name ?? '' }}','{{ \Carbon\Carbon::parse($client->date_received)->format('M j, Y') }}','{{ $client->status }}')">
                        <i class="fa-solid fa-print"></i> Print Report
                    </button>
                    <button class="btn-save" id="savebtn-{{ $client->id }}" onclick="saveTracker({{ $client->id }})" disabled>
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                </div>
            </div>

            {{-- TAB 2: VIEW FILES --}}
<div class="tab-panel" id="tab-files-{{ $client->id }}">

    @if($client->documents->isEmpty())
        <div class="no-files">
            <i class="fa-regular fa-folder-open"
               style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
            No files uploaded yet.
        </div>
    @else
        <div class="files-grid" id="files-grid-{{ $client->id }}">

            @foreach($client->documents as $doc)

            @php
                $mime = $doc->mime_type ?? '';

                $iconCls = str_contains($mime,'pdf')
                    ? 'icon-pdf'
                    : (str_contains($mime,'image')
                        ? 'icon-img'
                        : (str_contains($mime,'word') || str_contains($mime,'document')
                            ? 'icon-word'
                            : (str_contains($mime,'sheet') || str_contains($mime,'excel')
                                ? 'icon-xls'
                                : 'icon-other')));

                $faIcon = str_contains($mime,'pdf')
                    ? 'fa-file-pdf'
                    : (str_contains($mime,'image')
                        ? 'fa-file-image'
                        : (str_contains($mime,'word') || str_contains($mime,'document')
                            ? 'fa-file-word'
                            : (str_contains($mime,'sheet') || str_contains($mime,'excel')
                                ? 'fa-file-excel'
                                : 'fa-file')));
            @endphp

            <div class="file-card" id="fcard-{{ $doc->id }}">

                <div class="file-icon {{ $iconCls }}">
                    <i class="fa-solid {{ $faIcon }}"></i>
                </div>

                <div>
                    <div class="file-name" title="{{ $doc->original_name }}">
                        {{ $doc->original_name }}
                    </div>

                    <div class="file-size">
                        {{ $doc->human_size }}
                    </div>
                </div>

                <div class="file-acts">

                    {{-- VIEW --}}
                    <a href="{{ asset('storage/' . $doc->file_path) }}"
                       target="_blank"
                       class="f-btn">
                        <i class="fa-solid fa-eye"></i>
                        View
                    </a>

                    {{-- DOWNLOAD --}}
                    <a href="{{ route('clients.documents.download', [$client, $doc]) }}"
                       class="f-btn f-dl">
                        <i class="fa-solid fa-download"></i>
                        Download
                    </a>

                    {{-- DELETE --}}
                    <button
                        type="button"
                        class="f-btn f-del"
                        onclick="deleteDoc({{ $client->id }},{{ $doc->id }},'{{ addslashes($doc->original_name) }}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>

                </div>

            </div>

            @endforeach

        </div>
    @endif

</div>{{-- end tab-files --}}

        </div>{{-- end client-body --}}

    </div>{{-- end client-card --}}

    @empty
    <div class="empty-state">
        <i class="fa-regular fa-folder-open"></i>
        <p>No clients found{{ request('search') ? ' matching "'.request('search').'"' : '' }}.</p>
    </div>
    @endforelse

</div>{{-- end docs-wrap --}}

<div id="toast"></div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function toggleCard(id) {
    const body = document.getElementById('body-'+id), hdr = document.getElementById('hdr-'+id), chev = document.getElementById('chev-'+id);
    const open = body.classList.contains('open');
    body.classList.toggle('open',!open); hdr.classList.toggle('is-open',!open); chev.classList.toggle('open',!open);
}

function switchTab(clientId, tab, btn) {
    ['tracker','files'].forEach(t => document.getElementById('tab-'+t+'-'+clientId)?.classList.remove('active'));
    document.getElementById('tab-'+tab+'-'+clientId)?.classList.add('active');
    btn.closest('.tab-bar').querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function updateDot(sel) {
    const map = {'':'dot-gray',waiting:'dot-gray',processing:'dot-amber',submitted:'dot-blue',released:'dot-teal',done:'dot-green'};
    const dot = document.getElementById('dot-'+sel.dataset.client+'-'+sel.dataset.agency);
    if (dot) dot.className = 'agency-dot '+(map[sel.value]||'dot-gray');
}

function updateDocRow(chk) {
    const row = document.getElementById('drow-'+chk.dataset.client+'-'+chk.dataset.doc);
    if (!row) return;
    const loc = row.querySelector('.doc-loc-sel')?.value;
    row.className = 'doc-row '+(chk.checked?'on':(loc==='missing'?'mis':''));
}

function markDirty(id) {
    const btn = document.getElementById('savebtn-'+id);
    if (btn) { btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-floppy-disk"></i> Save Changes'; }
}

async function saveTracker(clientId) {
    const btn = document.getElementById('savebtn-'+clientId);
    btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    const agencies={};
    document.querySelectorAll(`.agency-sel[data-client="${clientId}"]`).forEach(s=>{ agencies[s.dataset.agency]={status:s.value}; });
    const docs={};
    document.querySelectorAll(`.doc-chk[data-client="${clientId}"]`).forEach(c=>{
        const loc=document.querySelector(`.doc-loc-sel[data-client="${clientId}"][data-doc="${c.dataset.doc}"]`);
        docs[c.dataset.doc]={submitted:c.checked,location:loc?loc.value:''};
    });
    const notes=document.querySelector(`.notes-area[data-client="${clientId}"]`)?.value??'';
    try {
        const res=await fetch(`/clients/${clientId}/tracker`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({agencies,docs,notes})});
        const data=await res.json();
        if(res.ok&&data.success){showToast('Saved!','ok');btn.innerHTML='<i class="fa-solid fa-check"></i> Saved';}
        else{showToast(data.message??'Error saving.','err');btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-floppy-disk"></i> Save Changes';}
    }catch(e){showToast('Network error.','err');btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-floppy-disk"></i> Save Changes';}
}

async function uploadFiles(clientId,input) {
    if(!input.files.length) return;
    const fd=new FormData();
    Array.from(input.files).forEach(f=>fd.append('files[]',f));
    fd.append('_token',CSRF);
    showToast('Uploading...','ok');
    try {
        const res=await fetch(`/clients/${clientId}/documents`,{method:'POST',body:fd});
        const data=await res.json();
        if(res.ok&&data.success){
            showToast('Uploaded!','ok');
            let grid=document.getElementById('files-grid-'+clientId);
            if(!grid){
                const panel=document.getElementById('tab-files-'+clientId);
                panel.querySelector('.no-files')?.remove();
                grid=document.createElement('div'); grid.className='files-grid'; grid.id='files-grid-'+clientId;
                panel.querySelector('.upload-zone').after(grid);
            }
            data.documents.forEach(doc=>{
                const isPdf=doc.mime_type?.includes('pdf'),isImg=doc.mime_type?.includes('image'),isWord=doc.mime_type?.includes('word')||doc.mime_type?.includes('document'),isExcel=doc.mime_type?.includes('sheet')||doc.mime_type?.includes('excel');
                const ic=isPdf?'icon-pdf':isImg?'icon-img':isWord?'icon-word':isExcel?'icon-xls':'icon-other';
                const fi=isPdf?'fa-file-pdf':isImg?'fa-file-image':isWord?'fa-file-word':isExcel?'fa-file-excel':'fa-file';
                grid.insertAdjacentHTML('beforeend',`<div class="file-card" id="fcard-${doc.id}"><div class="file-icon ${ic}"><i class="fa-solid ${fi}"></i></div><div><div class="file-name" title="${doc.original_name}">${doc.original_name}</div><div class="file-size">${doc.human_size}</div></div><div class="file-acts"><a href="${doc.download_url}" class="f-btn f-dl"><i class="fa-solid fa-download"></i> Download</a><button type="button" class="f-btn f-del" onclick="deleteDoc(${clientId},${doc.id},'${doc.original_name.replace(/'/g,"\\'")}')"><i class="fa-solid fa-trash"></i></button></div></div>`);
            });
        }else{showToast('Upload failed.','err');}
    }catch(e){showToast('Network error.','err');}
    input.value='';
}

async function deleteDoc(clientId,docId,filename) {
    if(!confirm(`Delete "${filename}"?`)) return;
    try{
        const res=await fetch(`/clients/${clientId}/documents/${docId}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}});
        const data=await res.json();
        if(res.ok&&data.success){showToast('File deleted.','ok');document.getElementById('fcard-'+docId)?.remove();}
        else{showToast('Failed to delete.','err');}
    }catch(e){showToast('Network error.','err');}
}

function printClient(clientId,name,service,received,status) {
    const agRows=[];
    document.querySelectorAll(`.agency-sel[data-client="${clientId}"]`).forEach(sel=>{
        agRows.push(`<div class="print-row"><span>${sel.closest('.agency-row').querySelector('.agency-name').textContent}</span><strong>${sel.options[sel.selectedIndex].text}</strong></div>`);
    });
    const docRows=[];
    document.querySelectorAll(`.doc-chk[data-client="${clientId}"]`).forEach(chk=>{
        const label=chk.closest('.doc-row').querySelector('.doc-label').textContent;
        const loc=chk.closest('.doc-row').querySelector('.doc-loc-sel');
        const locTxt=loc&&loc.value?loc.options[loc.selectedIndex].text:'—';
        docRows.push(`<div class="print-row"><span>${label}</span><span style="display:flex;gap:1rem;"><span>${chk.checked?'✅ Submitted':'⬜ Pending'}</span><span style="color:#64748b;">${locTxt}</span></span></div>`);
    });
    const notes=document.querySelector(`.notes-area[data-client="${clientId}"]`)?.value??'';
    const now=new Date().toLocaleDateString('en-PH',{year:'numeric',month:'long',day:'numeric'});
    document.getElementById('print-area').style.display='block';
    document.getElementById('print-area').innerHTML=`<div class="print-header" style="display:flex;align-items:center;gap:1.25rem;margin-bottom:1.5rem;border-bottom:2px solid #1e293b;padding-bottom:1rem;"><img src="/images/dsb-logo.png" style="width:72px;height:72px;object-fit:contain;flex-shrink:0;" alt="DSB Logo"><div><h2 style="font-size:1.25rem;font-weight:800;margin:0 0 0.25rem;color:#0f172a;">DSB Documentation Services — Status Report</h2><p style="font-size:0.8rem;color:#475569;margin:0 0 0.15rem;">Client: <strong>${name}</strong> &nbsp;|&nbsp; Service: ${service} &nbsp;|&nbsp; Received: ${received} &nbsp;|&nbsp; Status: ${status}</p><p style="font-size:0.72rem;color:#94a3b8;margin:0;">Printed: ${now}</p></div></div><div class="print-section"><h3>Agency Tracker</h3>${agRows.join('')}</div><div class="print-section"><h3>Document Status</h3>${docRows.join('')}</div>${notes?`<div class="print-section"><h3>Notes / Updates</h3><div class="print-notes">${notes}</div></div>`:''}<div class="print-footer">DSB Documentation Services · 2nd/F Plaza Medica Annex Building Setton St. Triangulo, Naga City</div>`;
    window.print();
    document.getElementById('print-area').style.display='none';
}

function showToast(msg,type){const t=document.getElementById('toast');t.textContent=msg;t.className=type;clearTimeout(t._t);t._t=setTimeout(()=>t.className='',3000);}
</script>
@endsection