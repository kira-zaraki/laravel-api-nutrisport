<h2>Daily Sales Report</h2>

<p><strong>Most sold product:</strong>
{{ $report['mostSold']->name }} ({{ $report['mostSold']->total_qty }})
</p>

<p><strong>Least sold product:</strong>
{{ $report['leastSold']->name }} ({{ $report['leastSold']->total_qty }})
</p>

<p><strong>Max revenue product:</strong>
{{ $report['maxRevenue']->name }} ({{ $report['maxRevenue']->revenue }})
</p>

<p><strong>Min revenue product:</strong>
{{ $report['minRevenue']->name }} ({{ $report['minRevenue']->revenue }})
</p>

<h3>Revenue by site</h3>

<ul>
@foreach($report['revenueBySite'] as $site)
<li>{{ $site->site }} : {{ $site->revenue }}</li>
@endforeach
</ul>