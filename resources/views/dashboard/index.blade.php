@extends('layouts.app')

@section('title', 'Admin Dashboard')

@php $currentPage = 'dashboard'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <button class="menu-toggle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    </div>

    <div class="topbar-actions">
        <div class="action-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="action-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="profile-circle">
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="dashboard-grid">
        <!-- Left Column: Area Chart & Bar Chart -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Area Chart -->
            <div class="dash-card">
                <h3 class="dash-card-title">Area Chart</h3>
                <div style="height: 150px; position: relative; overflow: hidden;">
                    <svg preserveAspectRatio="none" viewBox="0 0 100 50" style="width: 100%; height: 100%;">
                        <path d="M0,50 L0,30 C10,10 20,40 30,20 C40,0 50,40 60,10 C70,-10 80,30 90,10 C95,5 100,20 100,50 Z" fill="url(#grad1)" />
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#38bdf8;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#ffffff;stop-opacity:0.2" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="dash-card">
                <h3 class="dash-card-title">Bar Chart</h3>
                <div style="display:flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; height: 12px; border-radius: 6px; overflow: hidden;">
                        <div style="width: 30%; background: #94a3b8;"></div>
                        <div style="width: 20%; background: #64748b;"></div>
                        <div style="width: 25%; background: #334155;"></div>
                    </div>
                    <div style="display: flex; height: 12px; border-radius: 6px; overflow: hidden;">
                        <div style="width: 40%; background: #94a3b8;"></div>
                        <div style="width: 15%; background: #64748b;"></div>
                        <div style="width: 10%; background: #334155;"></div>
                    </div>
                    <div style="display: flex; height: 12px; border-radius: 6px; overflow: hidden;">
                        <div style="width: 25%; background: #94a3b8;"></div>
                        <div style="width: 35%; background: #64748b;"></div>
                        <div style="width: 20%; background: #334155;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Pie Chart -->
        <div class="dash-card" style="display: flex; flex-direction: column; align-items: center; justify-content: flex-start;">
            <h3 class="dash-card-title" style="align-self: flex-start; margin-bottom: 8px;">Pie Chart</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 32px; text-align: left; width: 100%;">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.
            </p>
            <div style="position: relative; width: 200px; height: 200px;">
                <svg viewBox="0 0 36 36" style="width: 100%; height: 100%;">
                    <path stroke="#38bdf8" stroke-width="8" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="60, 100" />
                    <path stroke="#94a3b8" stroke-width="8" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="40, 100" stroke-dashoffset="-60" />
                </svg>
                <div style="position: absolute; top:50%; left:50%; transform: translate(-50%, -50%); text-align:center;">
                    <div style="font-weight: 700; font-size:1.5rem; color: var(--text-main);">60%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="dash-card" style="margin-bottom: 40px; overflow-x: auto;">
        <h3 class="dash-card-title">TABLE</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Progres</th>
                    <th>Sales</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#124</td>
                    <td><span class="status-badge status-pending">PENDING</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:32px; height:32px; background:var(--input-bg); border-radius:50%;"></div>
                            <div>
                                <div style="font-weight:600; font-size:0.9rem;">NUR DAPON</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">AI Prompter</div>
                            </div>
                        </div>
                    </td>
                    <td><div style="width:60px; height:6px; background:var(--input-bg); border-radius:3px;"><div style="width:40%; height:100%; background:#facc15; border-radius:3px;"></div></div></td>
                    <td><svg width="40" height="15" viewBox="0 0 40 15"><path d="M0,10 L10,5 L20,12 L30,2 L40,8" fill="none" stroke="#38bdf8" stroke-width="2"/></svg></td>
                    <td><button style="background:none; color:var(--text-muted);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button></td>
                </tr>
                <tr>
                    <td>#342</td>
                    <td><span class="status-badge status-completed">COMPLETED</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:32px; height:32px; background:var(--input-bg); border-radius:50%;"></div>
                            <div>
                                <div style="font-weight:600; font-size:0.9rem;">NUR DAPON</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">AI Prompter</div>
                            </div>
                        </div>
                    </td>
                    <td><div style="width:60px; height:6px; background:var(--input-bg); border-radius:3px;"><div style="width:100%; height:100%; background:#4ade80; border-radius:3px;"></div></div></td>
                    <td><svg width="40" height="15" viewBox="0 0 40 15"><path d="M0,8 L10,12 L20,2 L30,10 L40,5" fill="none" stroke="#4ade80" stroke-width="2"/></svg></td>
                    <td><button style="background:none; color:var(--text-muted);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button></td>
                </tr>
                <tr>
                    <td>#124</td>
                    <td><span class="status-badge status-in-progress">IN PROGRES</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:32px; height:32px; background:var(--input-bg); border-radius:50%;"></div>
                            <div>
                                <div style="font-weight:600; font-size:0.9rem;">NUR DAPON</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">AI Prompter</div>
                            </div>
                        </div>
                    </td>
                    <td><div style="width:60px; height:6px; background:var(--input-bg); border-radius:3px;"><div style="width:60%; height:100%; background:#fb7185; border-radius:3px;"></div></div></td>
                    <td><svg width="40" height="15" viewBox="0 0 40 15"><path d="M0,5 L10,12 L20,5 L30,10 L40,2" fill="none" stroke="#fb7185" stroke-width="2"/></svg></td>
                    <td><button style="background:none; color:var(--text-muted);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
