<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Perhitungan WASPAS - K3LT - Arsip {{ $history->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 40px;
            font-size: 13px;
            line-height: 1.5;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #281C59;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-box {
            width: 50px;
            height: 50px;
            background: linear-gradient(to bottom right, #281C59, #4E8D9C);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            line-height: 50px;
        }

        .header-title {
            color: #281C59;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .header-subtitle {
            color: #4E8D9C;
            font-size: 12px;
            font-weight: 600;
            margin: 3px 0 0 0;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .info-grid td {
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
        }

        .label {
            font-weight: 700;
            color: #475569;
            width: 25%;
        }

        .value {
            color: #1e293b;
        }

        .section-title {
            color: #281C59;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            border-left: 4px solid #4E8D9C;
            padding-left: 10px;
            margin: 25px 0 15px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table.data-table th {
            background-color: #281C59;
            color: white;
            font-weight: 700;
            text-align: left;
            padding: 8px 12px;
            font-size: 11px;
            text-transform: uppercase;
        }

        table.data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .winner-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 16px;
            padding: 20px;
            margin-top: 30px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .winner-crown {
            font-size: 40px;
        }

        .winner-title {
            color: #b45309;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .winner-name {
            color: #1e293b;
            font-size: 20px;
            font-weight: 800;
            margin: 5px 0;
        }

        .winner-desc {
            color: #475569;
            font-size: 12px;
            margin: 0;
        }

        .signature-container {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .signature-box {
            width: 50%;
            text-align: center;
        }

        .sig-line {
            margin-top: 80px;
            font-weight: 700;
            color: #1e293b;
            text-decoration: underline;
        }

        .sig-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-benefit {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-cost {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Header Table -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <h1 class="header-title" style="font-family: 'Inter', sans-serif; font-weight: 800; font-size: 24px; margin: 0; color: #1e293b;">WASPAS - <span style="color:#059669;">K3LT</span></h1>
                <p class="header-subtitle" style="margin: 5px 0 0 0; color: #64748b;">Arsip Laporan Hasil Perhitungan Kepala Divisi K3LT - Terarsip Aman di Database</p>
            </td>
        </tr>
    </table>

    <!-- Info Detail -->
    <table class="info-grid">
        <tr>
            <td class="label">Judul Riwayat</td>
            <td class="value"><strong>{{ $history->title }}</strong></td>
            <td class="label">Tanggal Perhitungan</td>
            <td class="value">{{ $history->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">Parameter Sensitivitas (&lambda;)</td>
            <td class="value" colspan="3">{{ number_format($history->lambda, 2) }}</td>
        </tr>
    </table>

    <!-- 1. Kriteria & Bobot -->
    <h2 class="section-title">1. Daftar Kriteria & Bobot Kepentingan</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Kode</th>
                <th style="width: 45%;">Nama Kriteria</th>
                <th style="width: 25%;">Tipe Atribut</th>
                <th style="width: 20%;">Bobot Kepentingan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result['criteria'] as $c)
                <tr>
                    <td><strong>C{{ $c['id'] }}</strong></td>
                    <td>{{ $c['name'] }}</td>
                    <td>
                        <span class="badge {{ $c['type'] === 'Benefit' ? 'badge-benefit' : 'badge-cost' }}">
                            {{ $c['type'] }}
                        </span>
                    </td>
                    <td>{{ number_format($c['weight'], 4) }} ({{ number_format($c['weight'] * 100, 2) }}%)</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 2. Matriks Ternormalisasi -->
    <h2 class="section-title">2. Hasil Normalisasi Matriks Keputusan (R)</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Alternatif / Kandidat</th>
                @foreach($result['criteria'] as $c)
                    <th>C{{ $c['id'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($result['alternatives'] as $alt)
                <tr>
                    <td><strong>{{ $alt['name'] }}</strong></td>
                    @foreach($result['criteria'] as $c)
                        <td>{{ number_format($result['normalizedMatrix'][$alt['id']][$c['id']] ?? 0, 4) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 3. Hasil Perankingan -->
    <h2 class="section-title">3. Tabel Peringkat Final WASPAS (Qi)</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%; text-align: center;">Peringkat</th>
                <th style="width: 40%;">Nama Alternatif / Kandidat</th>
                <th style="width: 15%;">Q1 (SAW)</th>
                <th style="width: 15%;">Q2 (WP)</th>
                <th style="width: 15%;">Qi (Skor Akhir)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result['rankings'] as $rank)
                <tr style="{{ $loop->first ? 'background-color: #fefcbf; font-weight: bold;' : '' }}">
                    <td style="text-align: center;">
                        @if($rank['rank'] === 1) <span class="font-bold">1</span> @elseif($rank['rank'] === 2) <span class="font-bold">2</span> @elseif($rank['rank'] === 3) <span class="font-bold">3</span> @else {{ $rank['rank'] }} @endif
                    </td>
                    <td>{{ $rank['alternative_name'] }}</td>
                    <td>{{ number_format($rank['q1'], 4) }}</td>
                    <td>{{ number_format($rank['q2'], 4) }}</td>
                    <td style="color: #281C59;">{{ number_format($rank['qi'], 4) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Winner Announcement -->
    @if(isset($result['rankings'][0]))
        @php $winner = $result['rankings'][0]; @endphp
        <div class="winner-box">
            <div class="winner-crown"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg></div>
            <div>
                <h4 class="winner-title">Rekomendasi Kandidat Terbaik</h4>
                <h2 class="winner-name">{{ $winner['alternative_name'] }}</h2>
                <p class="winner-desc">
                    Berdasarkan perhitungan terintegrasi WASPAS (&lambda; = {{ number_format($history->lambda, 2) }}), kandidat di atas dinyatakan memiliki kecocokan tertinggi untuk menjabat sebagai **Kepala Divisi K3LT** dengan nilai Qi akhir sebesar **{{ number_format($winner['qi'], 4) }}**.
                </p>
            </div>
        </div>
    @endif

</body>
</html>
