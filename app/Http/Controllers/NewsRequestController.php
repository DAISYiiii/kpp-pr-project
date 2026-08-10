<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsRequestResource\Pages;
use App\Models\NewsRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class NewsRequestResource extends Resource
{
    protected static ?string $model = NewsRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'จัดการคำขอข่าวจากหน่วยงาน';
    
    protected static ?string $pluralModelLabel = 'รายการคำขอข่าว';
    
    protected static ?string $modelLabel = 'คำขอข่าว';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ข้อมูลหน่วยงานและผู้ส่งเรื่อง')
                    ->schema([
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'department_name')
                            ->label('หน่วยงานต้นสังกัด')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'category_name')
                            ->label('หมวดหมู่ข่าว')
                            ->required(),
                        Forms\Components\TextInput::make('contact_name')
                            ->label('ชื่อผู้ประสานงาน')
                            ->required()
                            ->maxLength(150),
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('เบอร์โทรศัพท์ผู้ประสานงาน')
                            ->required()
                            ->maxLength(30),
                    ])->columns(2),

                Forms\Components\Section::make('รายละเอียดข่าว / กิจกรรม')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('หัวข้อข่าว / ชื่อกิจกรรม')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('activity_date')
                            ->label('วันที่จัดกิจกรรม')
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->label('สถานที่จัดกิจกรรม')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('detail')
                            ->label('รายละเอียดเนื้อหาข่าว')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('การตรวจสอบและสถานะการดำเนินงาน')
                    ->schema([
                        Forms\Components\Select::make('current_status')
                            ->label('สถานะปัจจุบันของข่าว')
                            ->options([
                                'ส่งข้อมูลแล้ว' => 'ส่งข้อมูลแล้ว[cite: 1]',
                                'อยู่ระหว่างตรวจสอบ' => 'อยู่ระหว่างตรวจสอบ[cite: 1]',
                                'ขอข้อมูลเพิ่มเติม' => 'ขอข้อมูลเพิ่มเติม[cite: 1]',
                                'พร้อมเผยแพร่' => 'พร้อมเผยแพร่[cite: 1]',
                                'เผยแพร่แล้ว' => 'เผยแพร่แล้ว[cite: 1]',
                                'ยกเลิก/ไม่เผยแพร่' => 'ยกเลิก/ไม่เผยแพร่[cite: 1]',
                            ])
                            ->required()
                            ->default('ส่งข้อมูลแล้ว'),
                        Forms\Components\Textarea::make('remark')
                            ->label('หมายเหตุ / ข้อความแจ้งกลับหน่วยงาน')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.department_name')
                    ->label('หน่วยงานต้นสังกัด')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('หัวข้อข่าว')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category.category_name')
                    ->label('หมวดหมู่')
                    ->badge(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('ผู้ประสานงาน')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('current_status')
                    ->label('สถานะ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ส่งข้อมูลแล้ว' => 'warning',
                        'อยู่ระหว่างตรวจสอบ' => 'info',
                        'ขอข้อมูลเพิ่มเติม' => 'danger',
                        'พร้อมเผยแพร่', 'เผยแพร่แล้ว' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('วันที่ส่งเรื่อง')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('current_status')
                    ->label('กรองตามสถานะ')
                    ->options([
                        'ส่งข้อมูลแล้ว' => 'ส่งข้อมูลแล้ว',
                        'อยู่ระหว่างตรวจสอบ' => 'อยู่ระหว่างตรวจสอบ',
                        'ขอข้อมูลเพิ่มเติม' => 'ขอข้อมูลเพิ่มเติม',
                        'พร้อมเผยแพร่' => 'พร้อมเผยแพร่',
                        'เผยแพร่แล้ว' => 'เผยแพร่แล้ว',
                    ]),
                SelectFilter::make('department_id')
                    ->relationship('department', 'department_name')
                    ->label('กรองตามหน่วยงาน')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('ตรวจสอบ/จัดการ'),
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
            'edit' => Pages\EditNewsRequest::route('/edit/{record}') ,
        ];
    }
}