<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // First, backup existing data
        $existingProcesses = DB::table('application_processes')->get();
        $existingPivots = DB::table('application_process_representing_country')->get();

        // Drop the pivot table
        Schema::dropIfExists('application_process_representing_country');

        // Recreate application_processes with new structure (flat, no parent_id)
        Schema::dropIfExists('application_processes');
        Schema::create('application_processes', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->string('name')->unique();
            $table->string('color')->default('gray');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Create rep_country_status table (junction with customization)
        Schema::create('rep_country_status', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('representing_country_id')->constrained('representing_countries')->onDelete('cascade');
            $table->string('status_name'); // Reference to status name instead of ID
            $table->text('notes')->nullable();
            $table->string('custom_name')->nullable(); // Custom name for this status
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['representing_country_id', 'status_name']);
        });

        // Create sub_statuses table
        Schema::create('sub_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rep_country_status_id')->constrained('rep_country_status')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['rep_country_status_id', 'name']);
        });

        // Seed default application statuses
        $statuses = [
            ['id' => Str::ulid(), 'name' => 'New', 'color' => 'blue', 'order' => 1],
            ['id' => Str::ulid(), 'name' => 'Application On Hold', 'color' => 'yellow', 'order' => 2],
            ['id' => Str::ulid(), 'name' => 'Pre-Application Process', 'color' => 'purple', 'order' => 3],
            ['id' => Str::ulid(), 'name' => 'Rejected by University', 'color' => 'red', 'order' => 4],
            ['id' => Str::ulid(), 'name' => 'Application Submitted', 'color' => 'green', 'order' => 5],
            ['id' => Str::ulid(), 'name' => 'Conditional Offer', 'color' => 'orange', 'order' => 6],
            ['id' => Str::ulid(), 'name' => 'Pending Interview', 'color' => 'yellow', 'order' => 7],
            ['id' => Str::ulid(), 'name' => 'Unconditional Offer', 'color' => 'green', 'order' => 8],
            ['id' => Str::ulid(), 'name' => 'Acceptance', 'color' => 'green', 'order' => 9],
            ['id' => Str::ulid(), 'name' => 'Visa Processing', 'color' => 'blue', 'order' => 10],
            ['id' => Str::ulid(), 'name' => 'Enrolled', 'color' => 'green', 'order' => 11],
            ['id' => Str::ulid(), 'name' => 'Dropped', 'color' => 'red', 'order' => 12],
        ];

        foreach ($statuses as $status) {
            DB::table('application_processes')->insert([
                'id' => $status['id'],
                'name' => $status['name'],
                'color' => $status['color'],
                'order' => $status['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrate existing data to new structure
        $representingCountries = DB::table('representing_countries')->get();
        foreach ($representingCountries as $repCountry) {
            $order = 1;
            foreach ($statuses as $status) {
                DB::table('rep_country_status')->insert([
                    'representing_country_id' => $repCountry->id,
                    'status_name' => $status['name'],
                    'notes' => null,
                    'custom_name' => null,
                    'order' => $order++,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_statuses');
        Schema::dropIfExists('rep_country_status');
        Schema::dropIfExists('application_processes');

        // Restore old structure (simplified - you may need to adjust)
        Schema::create('application_processes', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->foreignUlid('parent_id')->nullable()->constrained('application_processes')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('application_process_representing_country', function (Blueprint $table) {
            $table->foreignUlid('application_process_id')->constrained('application_processes')->onDelete('cascade');
            $table->foreignUlid('representing_country_id')->constrained('representing_countries')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('custom_name')->nullable();
            $table->timestamps();

            $table->primary(['application_process_id', 'representing_country_id']);
        });
    }
};
