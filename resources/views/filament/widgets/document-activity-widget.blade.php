<x-filament-widgets::widget>
    <style>
        .dms-activity-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .dms-panel {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 14px;
            padding: 14px;
        }
        .dms-panel-danger {
            border-color: #fecaca;
        }
        .dms-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }
        .dms-panel-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }
        .dms-panel-meta {
            font-size: 12px;
            color: #6b7280;
        }
        .dms-item {
            display: block;
            text-decoration: none;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 8px;
            color: inherit;
            transition: border-color .15s ease, background-color .15s ease;
        }
        .dms-item:hover {
            border-color: #93c5fd;
            background: #f8fbff;
        }
        .dms-item-danger {
            border-color: #fee2e2;
        }
        .dms-item-danger:hover {
            border-color: #fca5a5;
            background: #fff7f7;
        }
        .dms-item-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 4px;
        }
        .dms-item-title {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            line-height: 1.35;
        }
        .dms-item-sub {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }
        .dms-badge {
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }
        .dms-badge-gray { background: #f3f4f6; color: #374151; }
        .dms-badge-amber { background: #fef3c7; color: #92400e; }
        .dms-badge-green { background: #dcfce7; color: #166534; }
        .dms-badge-red { background: #fee2e2; color: #991b1b; }
        .dms-empty {
            border: 1px dashed #d1d5db;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }
        @media (max-width: 980px) {
            .dms-activity-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="dms-activity-grid">
        <div class="dms-panel">
            <div class="dms-panel-head">
                <h3 class="dms-panel-title">Recent Documents</h3>
                <span class="dms-panel-meta">{{ count($recentDocuments) }} items</span>
            </div>

            @forelse ($recentDocuments as $item)
                @php
                    $badgeClass = match ($item['status']) {
                        'approved' => 'dms-badge-green',
                        'submitted' => 'dms-badge-amber',
                        'rejected' => 'dms-badge-red',
                        default => 'dms-badge-gray',
                    };
                @endphp
                <a href="{{ $item['url'] }}" class="dms-item">
                    <div class="dms-item-row">
                        <p class="dms-item-title">{{ $item['title'] }}</p>
                        <span class="dms-badge {{ $badgeClass }}">
                            {{ $statusLabels[$item['status']] ?? ucfirst($item['status']) }}
                        </span>
                    </div>
                    <p class="dms-item-sub">
                        {{ $item['category'] }} • {{ $item['uploader'] }} • {{ optional($item['created_at'])->diffForHumans() }}
                    </p>
                </a>
            @empty
                <div class="dms-empty">No recent document activity.</div>
            @endforelse
        </div>

        <div class="dms-panel dms-panel-danger">
            <div class="dms-panel-head">
                <h3 class="dms-panel-title">Over SLA Queue</h3>
                <span class="dms-panel-meta">{{ count($overdueDocuments) }} critical</span>
            </div>

            @forelse ($overdueDocuments as $item)
                <a href="{{ $item['url'] }}" class="dms-item dms-item-danger">
                    <div class="dms-item-row">
                        <p class="dms-item-title">{{ $item['title'] }}</p>
                        <span class="dms-badge dms-badge-red">+{{ $item['hours_over_sla'] }}h</span>
                    </div>
                    <p class="dms-item-sub">
                        {{ $item['category'] }} • {{ optional($item['created_at'])->diffForHumans() }}
                    </p>
                </a>
            @empty
                <div class="dms-empty">No over-SLA document right now.</div>
            @endforelse
        </div>
    </div>
</x-filament-widgets::widget>
