<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsItem->title }} - องค์การบริหารส่วนจังหวัดกำแพงเพชร</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col justify-between">

    <!-- Top Header -->
    <header class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🏛️</span>
                <div>
                    <h1 class="text-xl font-bold tracking-wide">องค์การบริหารส่วนจังหวัดกำแพงเพชร</h1>
                    <p class="text-xs text-blue-200">Kamphaeng Phet Provincial Administrative Organization</p>
                </div>
            </div>
            <div>
                <a href="{{ url('/') }}" class="bg-white text-blue-900 hover:bg-blue-50 px-4 py-2 rounded-lg font-semibold transition text-sm shadow-sm flex items-center gap-2">
                    <span>&larr;</span> กลับหน้าแรก
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-10 max-w-4xl flex-grow">
        <article class="bg-white rounded-2xl shadow-md p-8 md:p-12 border border-gray-100">
            
            <!-- Metadata: Category & Date -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">
                    {{ optional($newsItem->category)->category_name ?? 'ประชาสัมพันธ์ทั่วไป' }}
                </span>
                <span class="text-gray-400 text-xs flex items-center gap-1">
                    📅 เผยแพร่เมื่อ: {{ optional($newsItem->created_at)->format('d/m/Y H:i') ?? date('d/m/Y H:i') }}
                </span>
                @if(isset($newsItem->department))
                    <span class="text-gray-500 text-xs bg-gray-100 px-3 py-1 rounded-full">
                        🏢 หน่วยงาน: {{ $newsItem->department->department_name }}
                    </span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 leading-tight">
                {{ $newsItem->title }}
            </h1>

            <!-- Content Body (แปลง Path รูปภาพในเนื้อหาให้ชี้ไปที่ asset ทันที) -->
            <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4 text-base md:text-lg mb-8">
                {!! str_replace(
                    ['src="news-media/', 'src="/news-media/', 'src="storage/'], 
                    ['src="' . asset('storage/news-media') . '/', 'src="' . asset('storage/news-media') . '/', 'src="' . asset('storage') . '/'], 
                    $newsItem->content
                ) !!}
            </div>

            <!-- Image Gallery from media_files -->
            @php
                $mediaFiles = DB::table('media_files')
                    ->where('news_id', $newsItem->id ?? $newsItem->news_id)
                    ->get();
            @endphp

            @if($mediaFiles->count() > 0)
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span>🖼️</span> ภาพถ่ายกิจกรรมประกอบข่าว
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($mediaFiles as $media)
                            <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm bg-gray-50 group">
                                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->file_name }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Back Button Footer -->
            <div class="mt-12 pt-6 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ url('/') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-semibold transition text-sm flex items-center gap-2">
                    &larr; ย้อนกลับไปหน้าหลัก
                </a>
                <span class="text-xs text-gray-400">ระบบประชาสัมพันธ์ อบจ.กำแพงเพชร</span>
            </div>
        </article>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6 mt-16 border-t border-gray-800 text-xs text-gray-400">
        <p>&copy; 2026 องค์การบริหารส่วนจังหวัดกำแพงเพชร All rights reserved.</p>
    </footer>

</body>
</html>