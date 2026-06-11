@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Binago ang container para maging dark theme --}}
<div class="bg-slate-800 rounded shadow-sm p-6 text-gray-200" x-data="serviceManager()">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-wide uppercase">
            DSB Services List
        </h2>
        <button @click="showAddModal = true" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded transition shadow-sm">
            + Add Service
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                {{-- Binago ang header background at text color --}}
                <tr class="border-b border-slate-700 bg-slate-900 text-gray-400 font-semibold text-xs uppercase">
                    <th class="p-3 text-left w-2/5">Name</th>
                    <th class="p-3 text-left w-1/5">Service Type ID</th>
                    <th class="p-3 text-left w-1/5">Primary Contact</th>
                    <th class="p-3 text-center w-1/5">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($services) && $services->count() > 0)
                    @foreach($services as $service)
                        <tr class="border-b border-slate-700 hover:bg-slate-750 transition-colors">
                            <td class="p-3 font-medium">{{ $service->name }}</td>
                            <td class="p-3 text-gray-400">Service Type ID {{ $service->service_type_id }}</td>
                            <td class="p-3 text-gray-400">{{ $service->primary_contact }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('clients.index', ['service_id' => $service->id]) }}" 
                                   class="text-blue-400 hover:text-blue-300 font-semibold text-xs hover:underline">
                                    View Clients
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif

                <template x-for="row in newRows" :key="row.id">
                    <tr class="border-b border-slate-700 hover:bg-slate-750 transition-colors">
                        <td class="p-3 font-medium" x-text="row.name"></td>
                        <td class="p-3 text-gray-400" x-text="'Service Type ID ' + row.service_type_id"></td>
                        <td class="p-3 text-gray-400" x-text="row.primary_contact"></td>
                        <td class="p-3 text-center">
                            <a :href="'/clients?service_id=' + row.id" 
                               class="text-blue-400 hover:text-blue-300 font-semibold text-xs hover:underline">
                                View Clients
                            </a>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL - Binago rin ang kulay para sumakto sa dark mode --}}
    <div x-show="showAddModal" 
         class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" 
         style="display: none;" 
         x-transition>
        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-xl w-full max-w-md overflow-hidden" 
              @click.away="closeModal()">
            
            <div class="bg-blue-700 text-white px-4 py-3 flex justify-between items-center font-bold text-sm uppercase">
                <span>Add New Service</span>
                <button @click="closeModal()" class="text-white font-bold text-lg">&times;</button>
            </div>
            
            <div class="p-4 space-y-4 text-xs text-gray-200">
                <div>
                    <label class="block font-bold mb-1">Select Service</label>
                    <select x-model="form.selectedChoice" class="w-full border border-slate-600 rounded p-2 bg-slate-900 text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Choose a Service --</option>
                        <option value="Land & Property Titling">Land & Property Titling</option>
                        <option value="Land Conversion">Land Conversion</option>
                        <option value="Legal & Notarial">Legal & Notarial</option>
                        <option value="Land Surveyor">Land Surveyor</option>
                        <option value="BIR Taxation Services">BIR Taxation Services</option>
                        <option value="Others">Others (Type Custom Name)</option>
                    </select>
                </div>

                <div x-show="form.selectedChoice === 'Others'" x-transition>
                    <label class="block font-bold mb-1">Service Name (Editable)</label>
                    <input type="text" x-model="form.customName" placeholder="Type or edit service name here..." 
                           class="w-full border border-slate-600 rounded p-2 bg-slate-900 text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-bold mb-1">Service Type ID Number</label>
                    <input type="number" x-model="form.addId" placeholder="e.g., 1" 
                           class="w-full border border-slate-600 rounded p-2 bg-slate-900 text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block font-bold mb-1">Primary Contact</label>
                    <input type="text" x-model="form.addContact" placeholder="Type contact name..." 
                           class="w-full border border-slate-600 rounded p-2 bg-slate-900 text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="bg-slate-900 px-4 py-3 flex justify-end space-x-2 border-t border-slate-700">
                <button @click="closeModal()" class="bg-slate-600 text-white px-4 py-1.5 rounded text-xs font-semibold hover:bg-slate-500">
                    Cancel
                </button>
                <button @click="saveService()" 
                        :disabled="saving"
                        class="bg-blue-600 text-white px-4 py-1.5 rounded text-xs font-semibold hover:bg-blue-700 disabled:opacity-60">
                    <span x-text="saving ? 'Saving...' : 'Save'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Ang logic mo ay tama na, hindi na kailangang baguhin.
    function serviceManager() {
        return {
            showAddModal: false,
            saving: false,
            newRows: [], 
            form: { selectedChoice: '', customName: '', addId: '', addContact: 'Jofin Solivan' },
            get finalName() { return this.form.selectedChoice === 'Others' ? this.form.customName : this.form.selectedChoice; },
            closeModal() { this.showAddModal = false; this.form = { selectedChoice: '', customName: '', addId: '', addContact: 'Jofin Solivan' }; },
            async saveService() {
                if (!this.finalName || !this.form.addId || !this.form.addContact) { alert('Paki-sagutan ang lahat ng fields!'); return; }
                this.saving = true;
                try {
                    const response = await fetch('{{ route('services.store') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ name: this.finalName, service_type_id: this.form.addId, primary_contact: this.form.addContact })
                    });
                    const data = await response.json();
                    if (data.success) { this.newRows.push(data.service); this.closeModal(); } 
                    else { alert('May error: ' + (data.message ?? '')); }
                } catch (error) { alert('Hindi mai-save ang data.'); } finally { this.saving = false; }
            }
        }
    }
</script>
@endsection