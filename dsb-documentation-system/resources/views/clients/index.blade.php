@extends('layouts.app')

@section('content')

<div x-data="{
         panels: {},
         getPanel(id) {
             if (!this.panels[id]) this.panels[id] = { open: false, reqs: false };
             return this.panels[id];
         }
     }">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-bold text-slate-100 tracking-wide">Client Management</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage client records, files, and requirements</p>
        </div>
        <form method="GET" class="flex items-center">
            @if(request('service_id'))
                <input type="hidden" name="service_id" value="{{ request('service_id') }}">
            @endif
            <div class="flex items-center bg-[#0c1626] border border-white/[0.08] rounded-lg overflow-hidden">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search client..."
                       class="bg-transparent text-slate-300 placeholder-slate-600 text-sm px-3 py-2 w-52 focus:outline-none">
                <button type="submit"
                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white transition flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Toast --}}
    <div id="toast" class="hidden fixed top-4 right-4 z-50 px-5 py-3 rounded-xl shadow-2xl text-white text-sm font-semibold transition-all duration-300"></div>

    {{-- Table --}}
    <div class="rounded-xl border border-white/[0.07] overflow-hidden">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-white/[0.03] text-slate-500 font-semibold text-xs uppercase tracking-wider border-b border-white/[0.07]">
                    <th class="px-4 py-3 text-left w-1/4">Name</th>
                    <th class="px-4 py-3 text-left w-1/4">Client</th>
                    <th class="px-4 py-3 text-left w-1/4">Date Received</th>
                    <th class="px-4 py-3 text-left w-1/6">Status</th>
                    <th class="px-4 py-3 text-right w-12"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)

                {{-- MAIN ROW --}}
                <tr class="border-b border-white/[0.05] hover:bg-white/[0.02] transition-colors" id="client-row-{{ $client->id }}">
                    <td class="px-4 py-3 font-semibold text-slate-200">{{ $client->client_name }}</td>
                    <td class="px-4 py-3 text-slate-400 text-xs">{{ $client->client_name }}</td>
                    <td class="px-4 py-3 text-slate-400 text-xs">
                        {{ \Carbon\Carbon::parse($client->date_received)->format('M j, Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $sc = match($client->status) {
                                'Completed' => 'bg-emerald-500/10 text-emerald-400',
                                'On going'  => 'bg-blue-500/10 text-blue-400',
                                default     => 'bg-orange-500/10 text-orange-400',
                            };
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $sc }}">{{ $client->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button"
                                    @click="getPanel({{ $client->id }}).open = true"
                                    class="w-7 h-7 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 flex items-center justify-center transition"
                                    title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                            <button type="button"
                                    onclick="deleteClient({{ $client->id }}, '{{ addslashes($client->client_name) }}')"
                                    class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition"
                                    title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            <button type="button"
                                    @click="getPanel({{ $client->id }}).open = !getPanel({{ $client->id }}).open"
                                    class="w-7 h-7 rounded-lg bg-white/[0.04] hover:bg-white/[0.08] text-slate-500 flex items-center justify-center transition">
                                <svg class="w-3.5 h-3.5 transform transition-transform duration-200"
                                     :class="getPanel({{ $client->id }}).open ? 'rotate-0' : 'rotate-180'"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- DETAIL PANEL --}}
                <tr x-show="getPanel({{ $client->id }}).open" x-transition>
                    <td colspan="5" class="p-0">
                        <div class="bg-[#0a111d] border-t border-white/[0.05] px-4 py-5">

                            {{-- Panel header --}}
                            <div class="flex justify-between items-center mb-4 pb-3 border-b border-white/[0.06]">
                                <div>
                                    <div class="text-sm font-bold text-slate-200">{{ $client->client_name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        Received {{ \Carbon\Carbon::parse($client->date_received)->format('M j, Y') }}
                                    </div>
                                </div>
                                <button @click="getPanel({{ $client->id }}).open = false"
                                        class="w-7 h-7 rounded-lg bg-white/[0.05] hover:bg-white/[0.09] text-slate-500 hover:text-slate-300 flex items-center justify-center transition text-sm font-bold">
                                    ✕
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">

                                {{-- ===== LEFT COLUMN ===== --}}
                                <div class="space-y-3">

                                    <div>
                                        <label class="block font-semibold text-slate-500 mb-1.5">Client Name</label>
                                        <input type="text"
                                               id="client-name-{{ $client->id }}"
                                               value="{{ $client->client_name }}"
                                               class="w-full border border-white/[0.08] rounded-lg px-3 py-2 bg-white/[0.04] text-slate-200 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                                    </div>

                                    <div>
                                        <label class="block font-semibold text-slate-500 mb-1.5">Upload Files</label>
                                        <input type="file" id="file-upload-{{ $client->id }}" class="hidden" multiple
                                               onchange="handleFileUpload({{ $client->id }}, this)">
                                        <div onclick="document.getElementById('file-upload-{{ $client->id }}').click()"
                                             id="upload-zone-{{ $client->id }}"
                                             class="border-2 border-dashed border-white/[0.07] rounded-lg p-5 flex flex-col items-center justify-center text-slate-600 cursor-pointer hover:border-blue-500/40 hover:bg-blue-500/5 transition">
                                            <svg class="w-7 h-7 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            <span class="font-semibold text-slate-500">Upload Files</span>
                                            <span class="text-slate-600 text-xs mt-0.5">Click to browse or drag &amp; drop</span>
                                        </div>
                                        <div id="upload-progress-{{ $client->id }}" class="hidden mt-2">
                                            <div class="flex items-center gap-2 text-blue-400 text-xs font-medium">
                                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                                </svg>
                                                <span>Uploading...</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-semibold text-slate-500 mb-1.5">Status</label>
                                        <select id="status-{{ $client->id }}"
                                                class="w-full border border-white/[0.08] rounded-lg px-3 py-2 bg-white/[0.04] text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="Pending"   {{ $client->status=='Pending'  ?'selected':'' }} class="bg-[#0c1626]">Pending</option>
                                            <option value="On going"  {{ $client->status=='On going' ?'selected':'' }} class="bg-[#0c1626]">On going</option>
                                            <option value="Completed" {{ $client->status=='Completed'?'selected':'' }} class="bg-[#0c1626]">Completed</option>
                                        </select>
                                    </div>

                                    {{-- Save Changes button (left column, after status) --}}
                                    <button type="button"
                                            onclick="saveClient({{ $client->id }})"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition text-xs flex items-center justify-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Save Changes
                                    </button>
                                </div>

                                {{-- ===== RIGHT COLUMN ===== --}}
                                <div class="flex flex-col gap-3">

                                    <div>
                                        <label class="block font-semibold text-slate-500 mb-1.5">Date Received</label>
                                        <input type="date"
                                               id="date-received-{{ $client->id }}"
                                               value="{{ \Carbon\Carbon::parse($client->date_received)->format('Y-m-d') }}"
                                               class="w-full border border-white/[0.08] rounded-lg px-3 py-2 bg-white/[0.04] text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label class="block font-semibold text-slate-500 mb-1.5">Attachments</label>
                                        <div id="attachments-{{ $client->id }}"
                                             class="border border-white/[0.07] rounded-lg divide-y divide-white/[0.05] min-h-[40px] bg-white/[0.02]">
                                            @forelse($client->documents as $doc)
                                            <div class="flex justify-between items-center px-3 py-2 text-slate-400"
                                                 id="doc-{{ $doc->id }}">
                                                <div class="flex items-center gap-2 overflow-hidden">
                                                    <i class="fa-solid {{ $doc->icon_class }} text-sm flex-shrink-0 text-blue-400"></i>
                                                    <span class="font-medium truncate text-slate-300">{{ $doc->original_name }}</span>
                                                    <span class="text-slate-600 flex-shrink-0 text-xs">{{ $doc->human_size }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                                    <a href="{{ route('clients.documents.download', [$client, $doc]) }}"
                                                       class="w-6 h-6 rounded bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 flex items-center justify-center transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                    </a>
                                                    <button type="button"
                                                            onclick="deleteDocument({{ $client->id }}, {{ $doc->id }}, '{{ addslashes($doc->original_name) }}')"
                                                            class="w-6 h-6 rounded bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            @empty
                                            <div id="no-docs-{{ $client->id }}" class="px-3 py-4 text-slate-600 text-xs text-center">
                                                No files uploaded yet.
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- ===== REQUIREMENTS — INLINE (no absolute positioning) ===== --}}
                                    <div>
                                        <label class="block font-semibold text-slate-500 mb-1.5">Requirements</label>

                                        {{-- Toggle header --}}
                                        <div @click="getPanel({{ $client->id }}).reqs = !getPanel({{ $client->id }}).reqs"
                                             class="w-full border border-white/[0.08] rounded-t-lg px-3 py-2 bg-blue-500/10 text-blue-300 flex justify-between items-center cursor-pointer select-none">
                                            <span class="font-semibold text-xs">List of Requirements ({{ $client->client_name }})</span>
                                            <svg class="w-3 h-3 transform transition-transform duration-200"
                                                 :class="getPanel({{ $client->id }}).reqs ? 'rotate-0' : 'rotate-180'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </div>

                                        {{-- Inline checklist — no absolute, flows naturally in the column --}}
                                        <div x-show="getPanel({{ $client->id }}).reqs"
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="border border-t-0 border-white/[0.08] rounded-b-lg bg-[#0c1626] px-3 pt-3 pb-3 space-y-2">

                                            @foreach($requirements as $req)
                                            <label class="flex items-center gap-2.5 cursor-pointer py-0.5">
                                                <input type="checkbox"
                                                       name="requirements[{{ $client->id }}][]"
                                                       value="{{ $req }}"
                                                       {{ is_array($client->requirements_checklist) && in_array($req, $client->requirements_checklist) ? 'checked' : '' }}
                                                       class="req-checkbox-{{ $client->id }} w-3.5 h-3.5 rounded accent-blue-500">
                                                <span class="text-slate-300 font-medium">{{ $req }}</span>
                                            </label>
                                            @endforeach

                                            <div class="pt-2 border-t border-white/[0.06]">
                                                <button type="button"
                                                        onclick="saveRequirements({{ $client->id }})"
                                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded-lg text-xs transition flex items-center justify-center gap-2">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Save Requirements
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- end right column --}}
                            </div>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-slate-600">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm font-medium">No clients found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `fixed top-4 right-4 z-50 px-5 py-3 rounded-xl shadow-2xl text-white text-sm font-semibold transition-all duration-300 ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'}`;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    async function saveClient(clientId) {
        const name   = document.getElementById(`client-name-${clientId}`).value.trim();
        const date   = document.getElementById(`date-received-${clientId}`).value;
        const status = document.getElementById(`status-${clientId}`).value;
        if (!name) { showToast('Client name is required.', 'error'); return; }
        if (!date) { showToast('Date received is required.', 'error'); return; }
        try {
            const res  = await fetch(`/clients/${clientId}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ client_name: name, date_received: date, status }),
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('Client saved successfully!');
                const row = document.getElementById(`client-row-${clientId}`);
                if (row) {
                    row.cells[0].querySelector('.font-semibold').textContent = name;
                    row.cells[1].textContent = name;
                    const badge = row.cells[3].querySelector('span');
                    badge.textContent = status;
                    const sc = {'Completed':'bg-emerald-500/10 text-emerald-400','On going':'bg-blue-500/10 text-blue-400','Pending':'bg-orange-500/10 text-orange-400'};
                    badge.className = `text-xs font-bold px-2.5 py-1 rounded-full ${sc[status] || sc['Pending']}`;
                }
            } else { showToast(data.message ?? 'Failed to save.', 'error'); }
        } catch(e) { showToast('Network error.', 'error'); }
    }

    async function saveRequirements(clientId) {
        const checkboxes = document.querySelectorAll(`.req-checkbox-${clientId}:checked`);
        const requirements = Array.from(checkboxes).map(cb => cb.value);
        try {
            const res  = await fetch(`/clients/${clientId}/requirements`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ requirements_checklist: requirements }),
            });
            const data = await res.json();
            showToast(data.success ? 'Requirements saved!' : (data.message ?? 'Failed.'), data.success ? 'success' : 'error');
        } catch(e) { showToast('Network error.', 'error'); }
    }

    async function handleFileUpload(clientId, input) {
        if (!input.files.length) return;
        const formData = new FormData();
        Array.from(input.files).forEach(f => formData.append('files[]', f));
        const progress = document.getElementById(`upload-progress-${clientId}`);
        progress.classList.remove('hidden');
        try {
            const res  = await fetch(`/clients/${clientId}/documents`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: formData,
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast(`${data.documents.length} file(s) uploaded!`);
                const container = document.getElementById(`attachments-${clientId}`);
                document.getElementById(`no-docs-${clientId}`)?.remove();
                data.documents.forEach(doc => {
                    const div = document.createElement('div');
                    div.id = `doc-${doc.id}`;
                    div.className = 'flex justify-between items-center px-3 py-2 text-slate-400';
                    div.innerHTML = `
                        <div class="flex items-center gap-2 overflow-hidden">
                            <i class="fa-solid ${doc.icon_class} text-sm flex-shrink-0 text-blue-400"></i>
                            <span class="font-medium truncate text-slate-300">${escapeHtml(doc.original_name)}</span>
                            <span class="text-slate-600 flex-shrink-0 text-xs">${doc.human_size}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                            <a href="${doc.download_url}" class="w-6 h-6 rounded bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 flex items-center justify-center transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            <button type="button" onclick="deleteDocument(${clientId},${doc.id},'${escapeHtml(doc.original_name)}')"
                                    class="w-6 h-6 rounded bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>`;
                    container.appendChild(div);
                });
            } else {
                const msg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message ?? 'Upload failed.');
                showToast(msg, 'error');
            }
        } catch(e) { showToast('Network error during upload.', 'error'); }
        finally { progress.classList.add('hidden'); input.value = ''; }
    }

    async function deleteDocument(clientId, docId, filename) {
        if (!confirm(`Delete "${filename}"?`)) return;
        try {
            const res  = await fetch(`/clients/${clientId}/documents/${docId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast('File deleted.');
                document.getElementById(`doc-${docId}`)?.remove();
                const container = document.getElementById(`attachments-${clientId}`);
                if (container && !container.children.length) {
                    container.innerHTML = `<div id="no-docs-${clientId}" class="px-3 py-4 text-slate-600 text-xs text-center">No files uploaded yet.</div>`;
                }
            } else { showToast(data.message ?? 'Failed to delete.', 'error'); }
        } catch(e) { showToast('Network error.', 'error'); }
    }

    async function deleteClient(clientId, clientName) {
        if (!confirm(`Delete client "${clientName}" and all their files?`)) return;
        try {
            const res  = await fetch(`/clients/${clientId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (res.ok && data.success) {
                showToast(`"${clientName}" deleted.`);
                const row = document.getElementById(`client-row-${clientId}`);
                if (row) { if (row.nextElementSibling) row.nextElementSibling.remove(); row.remove(); }
            } else { showToast(data.message ?? 'Failed to delete.', 'error'); }
        } catch(e) { showToast('Network error.', 'error'); }
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }
</script>

@endsection