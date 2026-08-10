<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\NewsRequestController;

// 1. หน้าแรกแสดงรายการข่าวสารและระบบค้นหา
Route::get('/', [FrontController::class, 'index'])->name('home');

// 2. ฟอร์มส่งข่าวประชาสัมพันธ์สำหรับหน่วยงาน (จัดลำดับให้อยู่ก่อน /news/{id} ป้องกันการชน Route Parameter)
Route::get('/news/create', [FrontController::class, 'create'])->name('news.create');
Route::post('/news/store', [FrontController::class, 'store'])->name('news.store');

// 3. หน้าอ่านรายละเอียดข่าวแบบเต็มรูปแบบ (กำหนดชื่อรองรับทั้ง news.show และ news.detail)
Route::get('/news/{id}', [FrontController::class, 'show'])->name('news.show');

// 4. (เพิ่มเติมตามโครงงาน) เส้นทางสำหรับหน้าติดตามสถานะข่าวของหน่วยงานในสังกัด
// Route::get('/my-news-requests', [NewsRequestController::class, 'index'])->name('news.requests.index');