<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ส่งข่าวประชาสัมพันธ์ - กำแพงเพชร</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Header -->
    <header class="bg-blue-700 text-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">📢 ระบบส่งข่าวประชาสัมพันธ์ (สำหรับหน่วยงาน)</h1>
            <a href="/" class="bg-white text-blue-700 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition text-sm">กลับหน้าแรก</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-10 max-w-3xl">
        <div class="bg-white rounded-2xl shadow-md p-8 md:p-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">ฟอร์มส่งเรื่องเพื่อขออนุมัติเผยแพร่ข่าว</h2>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">หน่วยงานที่ส่งเรื่อง</label>
                    <select name="department_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- เลือกหน่วยงาน --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">หมวดหมู่ข่าว</label>
                    <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- เลือกหมวดหมู่ --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">หัวข้อข่าว</label>
                    <input type="text" name="title" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ระบุหัวข้อข่าว...">
                </div>

                <!-- ช่องอัปโหลดรูปภาพ -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">รูปภาพหน้าปกข่าว</label>
                    <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">รองรับไฟล์ภาพ: JPG, PNG, JPEG (ขนาดไม่เกิน 2MB)</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">เนื้อหาข่าว / รายละเอียด</label>
                    <textarea name="content" rows="6" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="พิมพ์เนื้อหาข่าวประชาสัมพันธ์ที่นี่..."></textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 transition shadow-md">ส่งเรื่องขออนุมัติ</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-6 mt-12">
        <p>&copy; 2026 ระบบประชาสัมพันธ์จังหวัดกำแพงเพชร All rights reserved.</p>
    </footer>

</body>
</html>