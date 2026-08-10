<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaFileResource\Pages;
use Illuminate\Support\Facades\DB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MediaFileResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'คลังสื่อประชาสัมพันธ์';
    protected static ?string $modelLabel = 'ไฟล์สื่อ';
    protected static ?string $pluralModelLabel = 'คลังสื่อประชาสัมพันธ์';

    // เนื่องจากใช้ตาราง DB::table('media_files') แบบไม่มี Model ตรง หรือสร้าง Model ควบคุมไว้แล้ว สามารถผูกตารางได้ดังนี้
    public static function getModel(): string
    {
        return \Illuminate\Database\Eloquent\Model::class; // หรือใช้ Model ที่เตรียมไว้
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(DB::table('media_files')->orderBy('uploaded_at', 'desc'))
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('ตัวอย่างภาพ')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('ชื่อไฟล์')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_type')
                    ->label('ประเภท')
                    ->badge(),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('ขนาดไฟล์ (Bytes)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('uploaded_at')
                    ->label('วันที่อัปโหลด')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('ดาวน์โหลด')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaFiles::route('/'),
        ];
    }
}