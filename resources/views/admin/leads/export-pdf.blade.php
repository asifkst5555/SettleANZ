<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; line-height: 1.4; }
        h1 { font-size: 18px; color: #0f172a; margin-bottom: 4px; }
        .meta { color: #64748b; font-size: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f5f9; padding: 6px 8px; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #475569; text-align: left; border-bottom: 2px solid #e2e8f0; }
        td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; font-size: 9px; }
        .status { display: inline-block; padding: 1px 6px; border-radius: 10px; font-size: 8px; font-weight: 600; background: #e2e8f0; }
        .footer { margin-top: 16px; font-size: 8px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="meta">Generated {{ $date }} · {{ count($leads) }} leads</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Source</th>
                <th>Page</th>
                <th>Visa/Service</th>
                <th>Status</th>
                <th>Staff</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leads as $lead)
            <tr>
                <td>{{ $lead->id }}</td>
                <td>{{ $lead->full_name ?: $lead->first_name ?: 'Unknown' }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ $lead->lead_source_label }}</td>
                <td>{{ $lead->website_page_label }}</td>
                <td>{{ $lead->visa_type ? (\App\Models\Lead::visaTypes()[$lead->visa_type] ?? $lead->visa_type) : ($lead->interested_service ?: '-') }}</td>
                <td><span class="status">{{ ucfirst($lead->status) }}</span></td>
                <td>{{ $lead->assignedStaff?->name ?? '-' }}</td>
                <td>{{ $lead->created_at?->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;padding:20px;color:#94a3b8;">No leads to export.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">SettleANZ CRM · Lead Export · {{ $date }}</div>
</body>
</html>
