<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil məlumatları')
                    ->description('İstifadəçinin əsas hesab məlumatları')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('E-poçt ünvanı')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('locale')
                            ->label('Dil')
                            ->options([
                                'az' => 'Azərbaycanca',
                                'en' => 'İngiliscə',
                                'tr' => 'Türkcə',
                                'ru' => 'Rusca',
                            ])
                            ->default('az')
                            ->native(false),

                        DateTimePicker::make('email_verified_at')
                            ->label('E-poçt təsdiqləndi')
                            ->seconds(false),
                    ]),

                Section::make('Şifrə')
                    ->description('Yeni istifadəçi yaradanda şifrə təyin edin. Mövcud istifadəçi üçün boş saxlasanız, şifrə dəyişməyəcək.')
                    ->columns(2)
                    ->components([
                        TextInput::make('password')
                            ->label('Şifrə')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(4)
                            ->maxLength(255),

                        TextInput::make('password_confirmation')
                            ->label('Şifrəni təsdiqlə')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->same('password')
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ]),

                Section::make('Avatar')
                    ->components([
                        FileUpload::make('avatar_path')
                            ->label('Profil şəkli')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->directory('avatars')
                            ->visibility('public'),
                    ]),
            ]);
    }
}
