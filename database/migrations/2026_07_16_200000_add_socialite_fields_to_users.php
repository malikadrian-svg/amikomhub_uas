<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan field social_id & social_provider untuk SSO Google (Socialite).
     * Menjadikan password nullable agar user yang login via Google tidak wajib punya password.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite tidak mendukung ALTER COLUMN, perlu recreate
            $oldUsers = DB::table('users')->get();
            Schema::dropIfExists('users');

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->enum('role', ['superadmin', 'organizer', 'user'])->default('user');
                $table->string('social_id')->nullable();
                $table->string('social_provider')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });

            foreach ($oldUsers as $user) {
                DB::table('users')->insert([
                    'id'                => $user->id,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password'          => $user->password,
                    'role'              => $user->role,
                    'social_id'         => null,
                    'social_provider'   => null,
                    'remember_token'    => $user->remember_token,
                    'created_at'        => $user->created_at,
                    'updated_at'        => $user->updated_at,
                ]);
            }
            return;
        }

        // MySQL / Production
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('social_id')->nullable()->after('email');
            $table->string('social_provider')->nullable()->after('social_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $oldUsers = DB::table('users')->get();
            Schema::dropIfExists('users');

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['superadmin', 'organizer', 'user'])->default('user');
                $table->rememberToken();
                $table->timestamps();
            });

            foreach ($oldUsers as $user) {
                DB::table('users')->insert([
                    'id'                => $user->id,
                    'name'              => $user->name,
                    'email'             => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password'          => $user->password ?? '',
                    'role'              => $user->role,
                    'remember_token'    => $user->remember_token,
                    'created_at'        => $user->created_at,
                    'updated_at'        => $user->updated_at,
                ]);
            }
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['social_id', 'social_provider']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
