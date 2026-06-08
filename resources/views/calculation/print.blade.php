<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keputusan Hasil Evaluasi WASPAS - K3LT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        * {
            font-family: 'Helvetica', Arial, sans-serif;
        }
        body {
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
            border-bottom: 2px solid #334155;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-box {
            width: 50px;
            height: 50px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            line-height: 50px;
        }

        .header-title {
            color: #0f172a;
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .header-subtitle {
            color: #475569;
            font-size: 12px;
            font-weight: 500;
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
            color: #1e293b;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            border-left: 4px solid #3b82f6;
            padding-left: 10px;
            margin: 25px 0 15px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table.data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-align: left;
            padding: 8px 12px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e1;
            border-top: 1px solid #cbd5e1;
        }

        table.data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .winner-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #3b82f6;
            border-radius: 8px;
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
            color: #3b82f6;
            font-size: 11px;
            font-weight: 700;
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
    <table class="header-table" style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #281C59; padding-bottom: 12px; margin-bottom: 20px;">
        <tr>
            <td style="vertical-align: top;">
                <h1 class="header-title" style="font-weight: 800; font-size: 22px; margin: 0; color: #1e293b; letter-spacing: 0.5px;">WASPAS - <span style="color:#059669;">K3LT</span></h1>
                <p class="header-subtitle" style="margin: 4px 0 0 0; color: #64748b; font-size: 11px;">Laporan Keputusan Hasil Evaluasi Kepala Divisi K3LT Secara Objektif & Presisi</p>
            </td>
        </tr>
    </table>

    <!-- Info Detail -->
    <table class="info-grid">
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td class="value">{{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
            <td class="label">Metode Evaluasi</td>
            <td class="value">WASPAS (Weighted Aggregated Sum Product Assessment)</td>
        </tr>
        <tr>
            <td class="label">Parameter Lambda (Lambda)</td>
            <td class="value" colspan="3">{{ number_format($result['lambda'], 2) }}</td>
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
            @foreach($result['criteria'] as $index => $c)
                <tr>
                    <td><strong>C{{ $index + 1 }}</strong></td>
                    <td>{{ $c->name }}</td>
                    <td>
                        <span class="badge {{ $c->type === 'Benefit' ? 'badge-benefit' : 'badge-cost' }}">
                            {{ $c->type }}
                        </span>
                    </td>
                    <td>{{ number_format($c->weight, 4) }} ({{ number_format($c->weight * 100, 2) }}%)</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 2. Matriks Ternormalisasi -->
    <h2 class="section-title">2. Hasil Normalisasi Matriks Keputusan (R)</h2>
    @php
        $criteriaChunks = $result['criteria']->chunk(3);
    @endphp

    @foreach($criteriaChunks as $chunkIndex => $chunk)
        @if($criteriaChunks->count() > 1)
            <div style="font-size: 11px; font-weight: bold; color: #281C59; margin-top: 10px; margin-bottom: 5px;">
                Bagian {{ $chunkIndex + 1 }} dari {{ $criteriaChunks->count() }}
            </div>
        @endif
        <table class="data-table" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 25%;">Nama Alternatif / Kandidat</th>
                    @foreach($chunk as $c)
                        @php
                            $index = $result['criteria']->search(fn($item) => $item->id === $c->id);
                        @endphp
                        <th style="font-size: 10px;">
                            C{{ $index + 1 }}: {{ $c->name }} <br>
                            <span style="font-size: 8px; font-weight: normal; text-transform: lowercase;">({{ $c->type }})</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($result['alternatives'] as $alt)
                    <tr>
                        <td><strong>{{ $alt->name }}</strong></td>
                        @foreach($chunk as $c)
                            <td>{{ number_format($result['normalizedMatrix'][$alt->id][$c->id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

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
                        {{ $rank['rank'] }}
                    </td>
                    <td>{{ $rank['alternative_name'] }}</td>
                    <td>{{ number_format($rank['q1'], 4) }}</td>
                    <td>{{ number_format($rank['q2'], 4) }}</td>
                    <td style="font-weight: bold; color: #0f172a;">{{ number_format($rank['qi'], 4) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Winner Announcement -->
    @if(isset($result['rankings'][0]))
        @php $winner = $result['rankings'][0]; @endphp
        <div class="winner-box">
            <!-- No icon for formal doc -->
            <div>
                <h4 class="winner-title">Rekomendasi Kandidat Terbaik</h4>
                <h2 class="winner-name">{{ $winner['alternative_name'] }}</h2>
                <p class="winner-desc">
                    Berdasarkan perhitungan terintegrasi WASPAS (Lambda = {{ number_format($result['lambda'], 2) }}), kandidat di atas dinyatakan memiliki kecocokan tertinggi untuk menjabat sebagai **Kepala Divisi K3LT** dengan nilai Qi akhir sebesar **{{ number_format($winner['qi'], 4) }}**.
                </p>
            </div>
        </div>
    @endif

</body>
</html>
