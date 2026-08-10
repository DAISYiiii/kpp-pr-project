<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('department_name')
                    ->label('ชื่อหน่วยงาน')
                    ->required()
                    ->maxLength(200),
                Forms\Components\Select::make('department_type')
                    ->label('ประเภทหน่วยงาน')
                    ->options([
                        'ส่วนราชการ' => 'ส่วนราชการ',
                        'โรงเรียน' => 'โรงเรียน',
                        'รพ.สต.' => 'รพ.สต.',
                        'สถานีอนามัย' => 'สถานีอนามัย',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('เบอร์โทรศัพท์')
                    ->tel()
                    ->maxLength(30),
                Forms\Components\TextInput::make('contact_person')
                    ->label('ชื่อผู้ประสานงาน')
                    ->maxLength(150),
                Forms\Components\Textarea::make('address')
                    ->label('ที่อยู่')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('สถานะ')
                    ->options([
                        'active' => 'ใช้งานอยู่',
                        'inactive' => 'ปิดใช้งาน',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department_name')
                    ->label('ชื่อหน่วยงาน')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department_type')
                    ->label('ประเภทหน่วยงาน')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('เบอร์โทรศัพท์'),
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('ผู้ประสานงาน'),
                Tables\Columns\TextColumn::make('status')
                    ->label('สถานะ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}