<x-filament-widgets::widget>
    <style>
        .dms-hero {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            padding: 24px;
            color: #ffffff;
            background: linear-gradient(120deg, #0f6ab4 0%, #1d4ed8 55%, #0ea5a4 100%);
            box-shadow: 0 10px 24px rgba(2, 6, 23, 0.15);
        }
        .dms-hero::before,
        .dms-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }
        .dms-hero::before {
            width: 180px;
            height: 180px;
            right: -40px;
            top: -40px;
            background: rgba(255, 255, 255, 0.18);
        }
        .dms-hero::after {
            width: 140px;
            height: 140px;
            left: 80px;
            bottom: -55px;
            background: rgba(103, 232, 249, 0.2);
        }
        .dms-hero-head {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }
        .dms-hero-kicker {
            margin: 0;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            opacity: 0.9;
        }
        .dms-hero-title {
            margin: 6px 0 0;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.1;
        }
        .dms-hero-date {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            white-space: nowrap;
        }
        .dms-hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
        }
        .dms-hero-stat {
            border-radius: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(2px);
        }
        .dms-hero-label {
            margin: 0;
            font-size: 12px;
            opacity: 0.85;
        }
        .dms-hero-value {
            margin: 6px 0 0;
            font-size: 22px;
            font-weight: 700;
        }
        @media (max-width: 1200px) {
            .dms-hero-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (max-width: 820px) {
            .dms-hero-title { font-size: 24px; }
            .dms-hero-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 560px) {
            .dms-hero-head { flex-direction: column; }
            .dms-hero-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="dms-hero">
        <div class="dms-hero-head">
            <div>
                <p class="dms-hero-kicker">Document Control Center</p>
                <h2 class="dms-hero-title">Approval Operations Dashboard</h2>
            </div>
            <span class="dms-hero-date">{{ $today }}</span>
        </div>

        <div class="dms-hero-grid">
            <div class="dms-hero-stat">
                <p class="dms-hero-label">Total Documents</p>
                <p class="dms-hero-value">{{ number_format($totals['total']) }}</p>
            </div>
            <div class="dms-hero-stat">
                <p class="dms-hero-label">Approved</p>
                <p class="dms-hero-value">{{ number_format($totals['approved']) }}</p>
            </div>
            <div class="dms-hero-stat">
                <p class="dms-hero-label">Pending</p>
                <p class="dms-hero-value">{{ number_format($totals['pending']) }}</p>
            </div>
            <div class="dms-hero-stat">
                <p class="dms-hero-label">Rejected</p>
                <p class="dms-hero-value">{{ number_format($totals['rejected']) }}</p>
            </div>
            <div class="dms-hero-stat">
                <p class="dms-hero-label">Approval Rate</p>
                <p class="dms-hero-value">{{ $totals['approval_rate'] }}%</p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
