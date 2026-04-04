<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        User::updateOrCreate(
            ['email' => 'admin@SettleANZ.com'],
            [
                'name' => 'SettleANZ Admin',
                'password' => Hash::make('admin@1234'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );
    }

    public function down(): void
    {
        User::where('email', 'admin@SettleANZ.com')->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
