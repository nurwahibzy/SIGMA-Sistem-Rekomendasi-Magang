<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan Magang</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .certificate {
            background: white;
            position: relative;
            width: 100%;
            height: 100%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .border-outer {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 10px solid #1a472a;
            background: linear-gradient(45deg, #2d5a3d, #1a472a);
        }

        .border-inner {
            position: absolute;
            top: 20mm;
            left: 20mm;
            right: 20mm;
            bottom: 20mm;
            border: 4px solid #d4af37;
            background: white;
        }

        .decorative-corner {
            position: absolute;
            width: 50px;
            height: 50px;
            border: 4px solid #d4af37;
        }

        .decorative-corner.top-left {
            top: 25mm;
            left: 25mm;
            border-right: none;
            border-bottom: none;
        }

        .decorative-corner.top-right {
            top: 25mm;
            right: 25mm;
            border-left: none;
            border-bottom: none;
        }

        .decorative-corner.bottom-left {
            bottom: 25mm;
            left: 25mm;
            border-right: none;
            border-top: none;
        }

        .decorative-corner.bottom-right {
            bottom: 25mm;
            right: 25mm;
            border-left: none;
            border-top: none;
        }

        .logo {
            width: 80px;
            height: 80px;
            border: 4px solid #d4af37;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #d4af37, #b8941f);
            color: white;
            font-size: 32px;
            font-weight: bold;
        }

        .content {
            position: absolute;
            top: 40mm;
            left: 30mm;
            right: 30mm;
            bottom: 35mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header {
            margin-top: -30px;
            margin-bottom: 50px;
        }

        .title {
            font-size: 62px;
            font-weight: bold;
            color: #1a472a;
            margin-bottom: 12px;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 30px;
            color: #d4af37;
            font-style: italic;
            margin-bottom: 100px;
        }

        .body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .awarded-to {
            font-size: 23px;
            color: #666;
            margin-bottom: 25px;
            font-style: italic;
        }

        .recipient-name {
            font-size: 50px;
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 50px;
            text-decoration: underline;
            text-decoration-color: #d4af37;
            text-underline-offset: 10px;
        }

        .description {
            font-size: 25px;
            line-height: 2;
            color: #333;
            max-width: 1200px;
            margin: 0 auto 30px;
            text-align: justify;
            text-justify: inter-word;
        }

        .company-name {
            color: #1a472a;
            font-weight: bold;
        }

        .period {
            color: #d4af37;
            font-weight: bold;
        }

        .footer-date {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-right: 20px;
        }

        .footer-signature {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .date-section {
            text-align: right;
            flex: 1;
        }

        .signature-section {
            text-align: center;
            flex: 1;

        }

        .signature-label {
            font-size: 23px;
            color: #666;
            margin-bottom: 90px;
        }

        .date-label {
            font-size: 23px;
            color: #666;
            margin-bottom: 20px;
        }

        .date-value {
            font-size: 25px;
            color: #333;
            font-weight: bold;
        }

        .signature-line {
            width: 250px;
            height: 2px;
            background: #333;
            margin: 0 auto 15px;
        }

        .signature-name {
            font-size: 25px;
            color: #333;
            font-weight: bold;
        }

        .signature-title {
            font-size: 23px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        
        <!-- Decorative corners -->
        <div class="decorative-corner top-left"></div>
        <div class="decorative-corner top-right"></div>
        <div class="decorative-corner bottom-left"></div>
        <div class="decorative-corner bottom-right"></div>

        <!-- Logo/Institution -->
        {{-- <div class="logo-container">
            <div class="logo">
                <img src="{{ asset('template/assets/compiled//polinema.png') }}">
            </div>
        </div> --}}

        <div class="content">
            <div class="header">
                <div class="title">Sertifikat</div>
                <div class="subtitle">Kelulusan Program Magang</div>
            </div>

            <div class="body">
                <div class="awarded-to">Dengan bangga diberikan kepada:</div>
                <div class="recipient-name">{{ $nama }}</div>
                
                <div class="description">
                    Telah berhasil menyelesaikan program magang di 
                    <span class="company-name">{{ $perusahaan }}</span> 
                    selama periode 
                    <span class="period">{{ $tanggal_mulai }} sampai {{ $tanggal_selesai }}</span> 
                    dengan menunjukkan dedikasi, profesionalisme, dan kinerja yang memuaskan. 
                    Sertifikat ini diberikan sebagai pengakuan atas pencapaian dan kontribusi 
                    yang telah diberikan selama masa magang.
                </div>
            </div>

            <div class="footer-date">
                <div class="date-section">
                    <div class="date-label">Diterbitkan pada:</div>
                    <div class="date-value">{{ $tanggal_terbit ?? date('d F Y') }}</div>
                </div>
            </div>
            <div class="footer-signature">
                <div class="signature-section">
                    <div class="signature-label">Ditandatangani oleh:</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">Manajer Program Magang</div>
                    {{-- <div class="signature-title">Direktur Akademik</div> --}}
                </div>
            </div>
        </div>
    </div>
</body>
</html>