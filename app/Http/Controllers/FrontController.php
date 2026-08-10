<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsRequest;
use App\Models\Department;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    // แสดงรายการข่าวทั้งหมดหน้าแรก (รองรับการค้นหาและกรองหมวดหมู่)
    public function index(Request $request)
    {
        $query = News::where('status', 'published');

        // ค้นหาตามหัวข้อข่าว
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // กรองตามหมวดหมู่
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('welcome', compact('news', 'categories'));
    }

    // แสดงรายละเอียดข่าวแบบเจาะลึก พร้อมดึงรูปภาพจากคลังสื่อมาแสดงผล
    public function show($id)
    {
        $newsItem = News::findOrFail($id);

        // ดึงไฟล์รูปภาพที่ผูกกับข่าวนี้จากตาราง media_files
        $mediaFiles = DB::table('media_files')
            ->where('news_id', $newsItem->id ?? $newsItem->news_id)
            ->get();

        return view('news-detail', compact('newsItem', 'mediaFiles'));
    }

    // แสดงหน้าฟอร์มส่งข่าวสำหรับหน่วยงาน
    public function create()
    {
        $departments = Department::all();
        $categories = Category::all();

        return view('news.create', compact('departments', 'categories'));
    }

    // บันทึกข้อมูลคำขอส่งข่าวพร้อมอัปโหลดรูปภาพและบันทึกลงตาราง media_files
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'category_id' => 'required|exists:categories,category_id',
            'district_id' => 'required|integer',
            'activity_date' => 'required|date',
            'location' => 'required|string|max:255',
            'contact_name' => 'required|string|max:150',
            'contact_phone' => 'required|string|max:30',
            'title' => 'required|string|max:255',
            'detail' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // ตรวจสอบไฟล์รูปภาพหลายรูป
        ]);

        // กำหนดค่าเริ่มต้นเพิ่มเติม
        $validated['created_by'] = auth()->id() ?? 1; 
        $validated['current_status'] = 'ส่งข้อมูลแล้ว';

        // 1. บันทึกข้อมูลคำขอลงตาราง news_requests
        $newsRequest = NewsRequest::create($validated);

        // 2. จัดการอัปโหลดไฟล์รูปภาพและบันทึกลงตาราง media_files
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // จัดเก็บไฟล์ไว้ที่ storage/app/public/news-media
                $path = $file->store('news-media', 'public');

                // บันทึกประวัติลงตาราง media_files ตาม ER Diagram
                DB::table('media_files')->insert([
                    'news_id' => $newsRequest->news_id ?? $newsRequest->id,
                    'uploaded_by' => $validated['created_by'],
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => 'image',
                    'file_extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'uploaded_at' => now(),
                ]);
            }
        }

        return redirect()->route('news.create')->with('success', 'ส่งข้อมูลข่าวประชาสัมพันธ์และอัปโหลดรูปภาพเข้าสู่ระบบกลางเรียบร้อยแล้วครับ เจ้าหน้าที่กำลังตรวจสอบข้อมูล');
    }
}