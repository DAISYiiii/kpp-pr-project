<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ส่งข่าวประชาสัมพันธ์ - องค์การบริหารส่วนจังหวัดกำแพงเพชร</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-blue-900 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🏛️</span>
                <div>
                    <h1 class="text-xl font-bold">ระบบกลางแจ้งงานประชาสัมพันธ์</h1>
                    <p class="text-xs text-blue-200">องค์การบริหารส่วนจังหวัดกำแพงเพชร</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="bg-white text-blue-900 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition text-sm">
                &larr; กลับหน้าแรก
            </a>
        </div>
    </header>

    <!-- Main Form -->
    <main class="container mx-auto px-6 py-10 max-w-3xl flex-grow">
        <div class="bg-white rounded-2xl shadow-md p-8 md:p-10 border border-gray-100">
            
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-900">📝 แบบฟอร์มส่งข่าว / กิจกรรมของหน่วยงาน</h2>
                <p class="text-gray-500 text-sm mt-1">กรอกข้อมูลรายละเอียดและแนบภาพถ่ายกิจกรรมให้ครบถ้วน เพื่อให้ฝ่ายประชาสัมพันธ์นำไปตรวจสอบและเผยแพร่ต่อไป</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                    <p class="font-semibold mb-1">⚠️ กรุณาตรวจสอบความถูกต้องของข้อมูล:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- เพิ่ม enctype="multipart/form-data" เพื่อให้รองรับการอัปโหลดไฟล์ -->
            <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- หน่วยงานต้นสังกัด -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">หน่วยงานต้นสังกัด *</label>
                        <select name="department_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">-- เลือกหน่วยงานในสังกัด --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- หมวดหมู่ข่าว -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">หมวดหมู่ข่าว *</label>
                        <select name="category_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">-- เลือกหมวดหมู่ข่าว --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- รหัสอำเภอ -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสอำเภอ / พื้นที่ *</label>
                        <input type="number" name="district_id" value="1" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <!-- วันที่จัดกิจกรรม -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">วันที่จัดกิจกรรม *</label>
                        <input type="date" name="activity_date" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <!-- สถานที่จัดงาน -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">สถานที่จัดงาน *</label>
                        <input type="text" name="location" placeholder="เช่น อบจ.กำแพงเพชร" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ชื่อผู้ติดต่อ -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ชื่อผู้ประสานงาน *</label>
                        <input type="text" name="contact_name" placeholder="ระบุชื่อ-นามสกุล" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <!-- เบอร์โทรศัพท์ -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">เบอร์โทรศัพท์ผู้ประสานงาน *</label>
                        <input type="text" name="contact_phone" placeholder="เช่น 0812345678" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <!-- หัวข้อข่าว -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">หัวข้อข่าว / ชื่อกิจกรรม *</label>
                    <input type="text" name="title" placeholder="ระบุหัวข้อข่าวให้ชัดเจน" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <!-- รายละเอียดเนื้อหา -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">รายละเอียดเนื้อหาข่าว *</label>
                    <textarea name="detail" rows="6" placeholder="กรอกรายละเอียดความเป็นมา กิจกรรม หรือผลงานที่ต้องการประชาสัมพันธ์..." required class="w-full border border-gray-300 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                </div>

                <!-- ฟิลด์อัปโหลดรูปภาพกิจกรรม -->
                <div class="p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <label class="block text-sm font-bold text-blue-900 mb-2">📷 แนบรูปภาพประกอบกิจกรรม (เลือกได้หลายรูปพร้อมกัน)</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    <p class="text-xs text-gray-500 mt-2">รองรับไฟล์ภาพนามสกุล: jpg, jpeg, png (ขนาดไม่เกิน 2MB ต่อภาพ)</p>
                </div>

                <!-- ปุ่มส่งข้อมูล -->
                <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                    <a href="{{ url('/') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition text-sm flex items-center">ยกเลิก</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-xl font-semibold transition text-sm shadow-md">
                        📤 ส่งข้อมูลและอัปโหลดไฟล์
                    </button>
                </div>

            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6 mt-12 text-xs text-gray-400 border-t border-gray-800">
        <p>&copy; 2026 องค์การบริหารส่วนจังหวัดกำแพงเพชร All rights reserved.</p>
    </footer>

</body>
</html>