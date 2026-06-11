@extends('layouts.app')

@section('title', 'Settings – Profile')
@section('page_title', 'Settings')

@section('content')

{{-- Banners --}}
@if (session('status'))
<div class="mb-6 flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    {{ session('status') }}
</div>
@endif

@if ($errors->any())
<div class="mb-6 bg-red-500/10 border-l-4 border-red-500/50 px-4 py-3 rounded-r-xl">
    <div class="flex items-center gap-2 mb-1">
        <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
        <span class="text-sm font-semibold text-red-400">Please fix the following errors:</span>
    </div>
    <ul class="text-xs text-red-400/80 list-disc list-inside space-y-0.5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="max-w-5xl mx-auto">
<form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

    {{-- LEFT COLUMN --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Avatar card --}}
        <div class="bg-[#0c1626] border border-white/[0.07] rounded-2xl p-6 text-center">
            @php
                $avatarUrl = $profile->avatar
                    ? asset('storage/' . $profile->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1e3a5f&color=5ca3e8&size=200';
            @endphp
            <div class="relative inline-block mb-4">
                <img id="avatar-preview"
                     src="{{ $avatarUrl }}"
                     alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-500/20 shadow-xl">
                <label for="avatar"
                       class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition"
                       title="Change photo">
                    <i class="fa-solid fa-camera text-xs"></i>
                </label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
            </div>
            <p class="font-bold text-slate-200 text-sm">{{ $user->name }}</p>
            <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $user->email }}</p>
            @if ($profile->position)
            <span class="inline-block mt-2 px-2.5 py-0.5 bg-blue-500/10 text-blue-400 rounded-full text-xs font-semibold">
                {{ $profile->position }}
            </span>
            @endif
        </div>

        {{-- Quick info --}}
        <div class="bg-[#0c1626] border border-white/[0.07] rounded-2xl p-4 space-y-2.5 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-envelope w-4 text-blue-400"></i>
                <span class="truncate text-slate-400">{{ $user->email }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-phone w-4 text-blue-400"></i>
                <span class="text-slate-400">{{ $profile->contact_number ?: '—' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-location-dot w-4 text-blue-400"></i>
                <span class="truncate text-slate-400">{{ $profile->address ?: '—' }}</span>
            </div>
            @if ($profile->department)
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-building w-4 text-blue-400"></i>
                <span class="text-slate-400">{{ $profile->department }}</span>
            </div>
            @endif
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-calendar w-4 text-blue-400"></i>
                <span class="text-slate-400">Member since {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="lg:col-span-3 space-y-5">

        {{-- Section 1: Account Information --}}
        <div class="bg-[#0c1626] border border-white/[0.07] rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06] bg-white/[0.02]">
                <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-user-circle text-blue-400"></i> Account Information
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Your core login credentials.</p>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Full Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           placeholder="Your full name"
                           class="w-full px-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600
                                  focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition
                                  @error('name') border-red-500/50 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Email Address <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           placeholder="your@email.com"
                           class="w-full px-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600
                                  focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition
                                  @error('email') border-red-500/50 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- Section 2: Personal Details --}}
        <div class="bg-[#0c1626] border border-white/[0.07] rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06] bg-white/[0.02]">
                <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-blue-400"></i> Personal Details
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Additional information visible on your profile.</p>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Contact Number --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Contact Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600 text-sm">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <input type="text" name="contact_number" value="{{ old('contact_number', $profile->contact_number) }}"
                               placeholder="+63 900 000 0000"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

                {{-- Birthdate --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Birthdate</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600 text-sm">
                            <i class="fa-solid fa-cake-candles"></i>
                        </div>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $profile->birthdate?->format('Y-m-d')) }}"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Gender</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600 text-sm">
                            <i class="fa-solid fa-venus-mars"></i>
                        </div>
                        <select name="gender"
                                class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition appearance-none">
                            <option value="" class="bg-[#0c1626]">— Select gender —</option>
                            @foreach (['male' => 'Male', 'female' => 'Female', 'non-binary' => 'Non-binary', 'prefer_not_to_say' => 'Prefer not to say'] as $val => $label)
                            <option value="{{ $val }}" class="bg-[#0c1626]" {{ old('gender', $profile->gender) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-600 text-xs">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Address</label>
                    <div class="relative">
                        <div class="absolute top-3 left-0 pl-3.5 flex items-start pointer-events-none text-slate-600 text-sm">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <textarea name="address" rows="1" placeholder="Street, City, Province"
                                  class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition resize-none">{{ old('address', $profile->address) }}</textarea>
                    </div>
                </div>

                {{-- Position --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Position / Job Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600 text-sm">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <input type="text" name="position" value="{{ old('position', $profile->position) }}"
                               placeholder="e.g. Documentation Officer"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

                {{-- Department --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Department</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-600 text-sm">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <input type="text" name="department" value="{{ old('department', $profile->department) }}"
                               placeholder="e.g. Records Management"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

            </div>

            {{-- Bio --}}
            <div class="px-6 pb-6">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bio / About Me</label>
                <textarea name="bio" rows="4" placeholder="Write a short bio about yourself…"
                          class="w-full px-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600
                                 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition resize-none">{{ old('bio', $profile->bio) }}</textarea>
                <p class="text-xs text-slate-600 mt-1">Max 1,000 characters.</p>
            </div>
        </div>

        {{-- Section 3: Social Links --}}
        <div class="bg-[#0c1626] border border-white/[0.07] rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06] bg-white/[0.02]">
                <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes text-blue-400"></i> Social Links
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Optional external profile links.</p>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Facebook</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1877f2] text-sm">
                            <i class="fa-brands fa-facebook"></i>
                        </div>
                        <input type="url" name="facebook" value="{{ old('facebook', $profile->facebook) }}"
                               placeholder="https://facebook.com/yourprofile"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">LinkedIn</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#0077b5] text-sm">
                            <i class="fa-brands fa-linkedin"></i>
                        </div>
                        <input type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin) }}"
                               placeholder="https://linkedin.com/in/yourprofile"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 4: Change Password --}}
        <div class="bg-[#0c1626] border border-white/[0.07] rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/[0.06] bg-white/[0.02]">
                <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-blue-400"></i> Change Password
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Leave blank to keep your current password.</p>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Current Password</label>
                    <input type="password" name="current_password" placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600
                                  focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition
                                  @error('current_password') border-red-500/50 @enderror">
                    @error('current_password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
                    <input type="password" name="password" placeholder="Min. 8 characters"
                           class="w-full px-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600
                                  focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition
                                  @error('password') border-red-500/50 @enderror">
                    @error('password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Re-enter new password"
                           class="w-full px-3.5 py-2.5 bg-white/[0.04] border border-white/[0.08] rounded-xl text-sm text-slate-200 placeholder-slate-600
                                  focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                </div>

            </div>
        </div>

        {{-- Save / Cancel --}}
        <div class="flex items-center justify-end gap-3 pb-2">
            <a href="{{ route('dashboard') }}"
               class="px-5 py-2.5 text-sm font-semibold text-slate-400 bg-white/[0.05] border border-white/[0.08] rounded-xl hover:bg-white/[0.08] transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                Save Changes
            </button>
        </div>

    </div>{{-- end right column --}}
</div>{{-- end grid --}}
</form>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
    reader.readAsDataURL(file);
}
</script>

@endsection