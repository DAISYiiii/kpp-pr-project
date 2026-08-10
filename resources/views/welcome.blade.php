<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>องค์การบริหารส่วนจังหวัดกำแพงเพชร - ระบบประชาสัมพันธ์</title>
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
            <div class="flex items-center gap-3">
                <a href="{{ route('news.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold transition text-sm flex items-center gap-2 shadow-sm">
                    <span>📝</span> ส่งข่าวประชาสัมพันธ์
                </a>
                <a href="/admin" class="bg-white text-blue-900 hover:bg-blue-50 px-4 py-2 rounded-lg font-semibold transition text-sm shadow-sm flex items-center gap-2">
                    <span>⚙️</span> ระบบหลังบ้าน
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Banner & Search Section -->
    <section class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white py-12 px-6 shadow-inner">
        <div class="container mx-auto text-center max-w-4xl">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">ศูนย์กลางข่าวสารและประชาสัมพันธ์</h2>
            <p class="text-blue-100 text-base md:text-lg mb-8">ติดตามความเคลื่อนไหว กิจกรรม โครงการ และประกาศสำคัญจาก อบจ.กำแพงเพชร และหน่วยงานภายใน</p>
            
            <!-- Search & Filter Bar -->
            <form action="{{ url('/') }}" method="GET" class="bg-white p-2 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-2">
                <div class="flex-grow flex items-center px-4 py-2">
                    <span class="text-gray-400 mr-2">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาหัวข้อข่าว, กิจกรรม, หรือประกาศ..." class="w-full focus:outline-none text-gray-800 text-sm">
                </div>
                <div class="w-full md:w-auto flex gap-2">
                    <select name="category_id" class="border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-700 focus:outline-none bg-gray-50">
                        <option value="">ทุกหมวดหมู่</option>
                        @if(isset($categories))
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-semibold transition text-sm">ค้นหา</button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ url('/') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-xl font-semibold transition text-sm flex items-center justify-center">รีเซ็ต</a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-10 flex-grow">
        
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span>📰</span> ข่าวสารและกิจกรรมล่าสุด
            </h3>
            <span class="text-sm text-gray-500">ประกาศอย่างเป็นทางการ</span>
        </div>

        @if(isset($news) && $news->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($news as $item)
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition flex flex-col justify-between border border-gray-100 group">
                        <div>
                            <!-- รูปภาพหน้าปกข่าว (ดึงรูปแรกจากตาราง media_files มาแสดงผล) -->
                            @php
                                $coverImage = DB::table('media_files')
                                    ->where('news_id', $item->news_id ?? $item->id)
                                    ->first();
                            @endphp

                            <div class="h-48 overflow-hidden bg-gray-100 relative">
                                @if($coverImage)
                                    <img src="{{ asset('storage/' . $coverImage->file_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-blue-50 text-blue-400">
                                        <span class="text-4xl">🏛️</span>
                                    </div>
                                @endif
                                <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-3 py-1 rounded-full font-semibold shadow">
                                    {{ optional($item->category)->category_name ?? 'ประชาสัมพันธ์' }}
                                </span>
                            </div>

                            <div class="p-6">
                                <h4 class="text-lg font-bold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition">{{ $item->title }}</h4>
                                <div class="text-gray-600 mt-2 text-sm line-clamp-3">
                                    {!! strip_tags($item->content) !!}
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 pb-6 pt-2 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                            <span>📅 {{ optional($item->created_at)->format('d/m/Y H:i') }}</span>
                            <a href="{{ route('news.show', $item->news_id ?? $item->id) }}" class="text-blue-600 font-semibold hover:underline flex items-center gap-1 text-sm">
                                อ่านเพิ่มเติม &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $news->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                <span class="text-5xl block mb-3">📭</span>
                <p class="text-gray-500 text-lg">ไม่พบข้อมูลข่าวสารในขณะนี้</p>
                <a href="{{ url('/') }}" class="inline-block mt-4 text-blue-600 font-semibold hover:underline">ดูข่าวทั้งหมด</a>
            </div>
        @endif
    </main>

    <!-- Footer / Internal Links -->
    <footer class="bg-gray-900 text-white pt-12 pb-8 mt-16 border-t border-gray-800">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <h5 class="text-lg font-bold mb-4 text-blue-400">องค์การบริหารส่วนจังหวัดกำแพงเพชร</h5>
                <p class="text-gray-400 text-sm leading-relaxed">
                    มุ่งมั่นพัฒนาท้องถิ่น ส่งเสริมคุณภาพชีวิตประชาชน บริหารจัดการด้วยหลักธรรมาภิบาล เพื่อความผาสุกของชาวจังหวัดกำแพงเพชร
                </p>
            </div>
            <div>
                <h5 class="text-lg font-bold mb-4 text-blue-400">สำนักและกองภายใน</h5>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li>• สำนักปลัด อบจ. / สำนักงานเลขานุการ</li>
                    <li>• สำนักช่าง / กองคลัง</li>
                    <li>• กองยุทธศาสตร์และงบประมาณ</li>
                    <li>• กองสาธารณสุข / กองการศึกษาฯ</li>
                </ul>
            </div>
            <div>
                <h5 class="text-lg font-bold mb-4 text-blue-400">ติดต่อเรา</h5>
                <p class="text-gray-400 text-sm leading-relaxed mb-2">
                    📍 ศาลากลางจังหวัดกำแพงเพชร หรือที่ทำการ อบจ.กำแพงเพชร
                </p>
                <p class="text-gray-400 text-sm">
                    📞 โทรศัพท์ / โทรสาร: ประชาสัมพันธ์ อบจ.กำแพงเพชร
                </p>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-6 text-center text-gray-500 text-xs">
            <p>&copy; 2026 องค์การบริหารส่วนจังหวัดกำแพงเพชร All rights reserved.</p>
        </div>
    </footer>

</body>
</html>