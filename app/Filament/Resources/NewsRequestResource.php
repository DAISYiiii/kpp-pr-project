<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsRequestResource\Pages;
use App\Models\NewsRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class NewsRequestResource extends Resource
{
    protected static ?string $model = NewsRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'คำขออนุมัติข่าว';
    protected static ?string $modelLabel = 'คำขออนุมัติข่าว';

    // 🚀 ระบบจำกัดสิทธิ์การมองเห็นข้อมูลตามหน่วยงาน (Data Scoping)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // ถ้าผู้ใช้งานไม่ใช่แอดมินส่วนกลาง ให้แสดงเฉพาะข้อมูลของหน่วยงานตนเองเท่านั้น
        if ($user && $user->department_id && $user->role_id != 1) {
            $query->where('department_id', $user->department_id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('department_id')
                    ->label('หน่วยงานที่ส่งเรื่อง')
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('category_id')
                    ->label('หมวดหมู่ข่าว')
                    ->relationship('category', 'category_name')
                    ->required(),

                Forms\Components\Hidden::make('district_id')
                    ->default(1),

                Forms\Components\DatePicker::make('activity_date')
                    ->label('วันที่จัดกิจกรรม')
                    ->default(now())
                    ->required(),

                Forms\Components\TextInput::make('location')
                    ->label('สถานที่จัดงาน')
                    ->default('จังหวัดกำแพงเพชร')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('contact_name')
                    ->label('ชื่อผู้ติดต่อ / ผู้ส่งเรื่อง')
                    ->default(fn () => auth()->user()->name ?? 'Administrator')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('contact_phone')
                    ->label('เบอร์โทรศัพท์ผู้ติดต่อ')
                    ->default('0800000000')
                    ->required()
                    ->maxLength(50),

                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => auth()->id() ?? 1),

                Forms\Components\TextInput::make('title')
                    ->label('หัวข้อข่าว')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('detail')
                    ->label('เนื้อหาข่าว')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.department_name')
                    ->label('หน่วยงาน')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('หัวข้อข่าว')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('current_status')
                    ->label('สถานะ')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('วันที่ส่งคำขอ')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('approveAndPublish')
                    ->label('อนุมัติ/เผยแพร่')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('ยืนยันการอนุมัติและเผยแพร่ข่าว')
                    ->modalDescription('เมื่อกดอนุมัติ ข้อมูลนี้จะถูกส่งไปแสดงผลพร้อมรูปภาพในหน้าเว็บไซต์ทันที')
                    ->action(function ($record) {
                        // 1. สร้างข้อมูลข่าวจริง
                        $news = \App\Models\News::create([
                            'title' => $record->title,
                            'content' => $record->detail ?? 'ไม่มีเนื้อหาข่าว',
                            'category_id' => $record->category_id ?? 2,
                            'department_id' => $record->department_id,
                            'status' => 'published',
                            'user_id' => $record->created_by ?? auth()->id() ?? 1,
                        ]);

                        // 2. ดึง ID ของข่าวใหม่ที่เพิ่งบันทึก (แก้ปัญหาเรื่อง news_id)
                        $newNewsId = DB::getPdo()->lastInsertId();
                        if (!$newNewsId) {
                            $newNewsId = $news->news_id ?? $news->id ?? DB::table('news')->max('news_id');
                        }

                        $oldRecordId = $record->news_id ?? $record->id;

                        // 3. โยกย้ายรูปภาพใน media_files ให้วิ่งมาหาข่าวจริงตัวใหม่
                        DB::table('media_files')
                            ->where('news_id', $oldRecordId)
                            ->update(['news_id' => $newNewsId]);

                        // 4. ลบประวัติสถานะและลบรายการคำขอออก
                        DB::table('news_status_logs')->where('news_id', $oldRecordId)->delete();
                        $record->delete();

                        Notification::make()
                            ->title('อนุมัติและเผยแพร่ข่าวพร้อมรูปภาพเรียบร้อยแล้ว')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()->label('ตรวจสอบ'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsRequests::route('/'),
            'create' => Pages\CreateNewsRequest::route('/create'),
            'edit' => Pages\EditNewsRequest::route('/{record}/edit'),
        ];
    }
}