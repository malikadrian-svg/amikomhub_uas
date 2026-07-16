<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah enum role users: admin→superadmin, menambah organizer.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Load old users data into memory
            $oldUsers = DB::table('users')->get();
            
            // Drop old table to clear indexes
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
                $newRole = $user->role === 'admin' ? 'superadmin' : $user->role;
                DB::table('users')->insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password' => $user->password,
                    'role' => $newRole,
                    'remember_token' => $user->remember_token,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
            return;
        }

        // MySQL / Production
        DB::statement("UPDATE users SET role = 'superadmin' WHERE role = 'admin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'organizer', 'user') NOT NULL DEFAULT 'user'");
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
                $table->enum('role', ['admin', 'user'])->default('user');
                $table->rememberToken();
                $table->timestamps();
            });

            foreach ($oldUsers as $user) {
                $oldRole = $user->role === 'superadmin' ? 'admin' : ($user->role === 'organizer' ? 'user' : $user->role);
                DB::table('users')->insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password' => $user->password,
                    'role' => $oldRole,
                    'remember_token' => $user->remember_token,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
            return;
        }

        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'superadmin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
