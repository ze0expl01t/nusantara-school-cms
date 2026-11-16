<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==============================================================================
        // 1. TABEL INDUK / CORE (Dibuat pertama karena tidak memiliki foreign key)
        // ==============================================================================

        Schema::create('twill_users', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->string('role', 100);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('language')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->boolean('require_new_password')->default(0);
        });

        Schema::create('twill_files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->text('uuid');
            $table->text('filename')->nullable();
            $table->unsignedInteger('size');
        });

        Schema::create('twill_medias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->text('uuid');
            $table->text('alt_text')->nullable();
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->text('caption')->nullable();
            $table->text('filename')->nullable();
        });

        Schema::create('twill_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('key')->index()->nullable();
            $table->string('section')->index()->nullable();
        });

        Schema::create('twill_tags', function (Blueprint $table) {
            $table->id();
            $table->string('namespace');
            $table->string('slug');
            $table->string('name');
            $table->unsignedInteger('count')->default(0);
        });

        // ==============================================================================
        // 2. TABEL MODUL UTAMA
        // ==============================================================================

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->string('level', 30)->nullable();
            $table->string('rank', 30)->nullable();
            $table->date('achievement_date')->nullable();
        });

        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->dateTime('agenda_date')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->string('audience', 20)->default('all');
            $table->string('priority', 20)->default('normal');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_pinned')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->string('name')->nullable();
        });

        Schema::create('brosurs', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->timestamp('brosur_date')->nullable();
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });

        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->string('category')->nullable();
            $table->string('schedule')->nullable();
            $table->string('location')->nullable();
            $table->string('advisor_name')->nullable();
            $table->string('advisor_role')->nullable();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->text('icon')->nullable();
            $table->string('stat_value')->nullable();
            $table->string('capacity_value')->nullable();
        });

        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->string('company_name', 200)->nullable();
            $table->string('location', 200)->nullable();
            $table->string('job_type', 30)->nullable();
            $table->string('salary_range', 100)->nullable();
            $table->date('deadline')->nullable();
            $table->string('apply_url', 500)->nullable();
            $table->string('apply_email', 150)->nullable();
            $table->string('contact_person', 150)->nullable();
            $table->string('contact_phone', 50)->nullable();
        });

        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->string('code', 20)->nullable();
            $table->string('color', 20)->nullable();
            $table->string('head_of_department')->nullable();
            $table->string('accreditation', 10)->nullable();
        });

        Schema::create('menu_links', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('published')->default(0);
            $table->unsignedInteger('position')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->boolean('is_leadership')->default(0);
        });

        // ==============================================================================
        // 3. TABEL UTILITAS & LOG
        // ==============================================================================

        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->index()->nullable();
            $table->text('description');
            $table->bigInteger('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->string('event')->nullable();
            $table->bigInteger('causer_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->text('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->bigInteger('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->bigInteger('expiration');
        });

        Schema::create('checkpoint_scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('twill_users')->nullOnDelete();
            $table->string('triggered_by')->default('manual')->index();
            $table->integer('total_checks')->default(0);
            $table->integer('pass_count')->default(0);
            $table->integer('warn_count')->default(0);
            $table->integer('fail_count')->default(0);
            $table->decimal('duration_seconds', 8, 2)->default(0.00);
            $table->integer('exit_code')->default(0);
            $table->longText('raw_output')->nullable();
            $table->json('parsed_output')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->enum('status', ['pending', 'active', 'unsubscribed'])->default('pending')->index();
            $table->string('token', 64)->unique()->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ==============================================================================
        // 4. TABEL ANAK (REVISIONS, SLUGS, TRANSLATIONS)
        // ==============================================================================
        
        $modules = [
            'achievement' => 'achievements',
            'agenda' => 'agendas',
            'app_setting' => 'app_settings',
            'brosur' => 'brosurs',
            'extracurricular' => 'extracurriculars',
            'facility' => 'facilities',
            'job_vacancy' => 'job_vacancies',
            'jurusan' => 'jurusans',
            'page' => 'pages',
            'post' => 'posts',
            'project' => 'projects',
            'team_member' => 'team_members',
        ];

        foreach ($modules as $singular => $plural) {
            // Revisions
            Schema::create("{$singular}_revisions", function (Blueprint $table) use ($singular, $plural) {
                $table->id();
                $table->foreignId("{$singular}_id")->constrained($plural)->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('twill_users')->nullOnDelete();
                $table->timestamps();
                $table->json('payload');
            });

            // Slugs
            Schema::create("{$singular}_slugs", function (Blueprint $table) use ($singular, $plural) {
                $table->id();
                $table->foreignId("{$singular}_id")->constrained($plural)->cascadeOnDelete();
                $table->softDeletes();
                $table->timestamps();
                $table->string('slug');
                $table->string('locale', 7)->index();
                $table->boolean('active')->default(1);
            });
        }

        // Announcement (Hanya memiliki Revisions & Translations)
        Schema::create('announcement_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->nullable()->constrained('announcements')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('twill_users')->nullOnDelete();
            $table->json('payload');
            $table->timestamps();
        });

        Schema::create('announcement_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements')->cascadeOnDelete();
            $table->string('locale', 7)->index();
            $table->boolean('active')->default(0);
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('cta_label', 100)->nullable();
            $table->string('cta_url', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Kustomisasi Kolom Translations per Modul
        $translationSchemas = [
            'achievement' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
                $table->string('student_name', 200)->nullable();
                $table->string('organizer', 200)->nullable();
                $table->text('excerpt')->nullable();
            },
            'agenda' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
            'app_setting' => function (Blueprint $table) {
                $table->string('site_title')->nullable();
                $table->string('site_tagline')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('cta_text')->nullable();
                $table->string('cta_url')->nullable();
                $table->string('admin_login_text')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('contact_phone')->nullable();
                $table->text('contact_address')->nullable();
                $table->string('google_maps_url')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('youtube_url')->nullable();
                $table->string('twitter_url')->nullable();
                $table->string('tiktok_url')->nullable();
                $table->string('whatsapp_url')->nullable();
                $table->text('footer_intro')->nullable();
                $table->text('footer_words')->nullable();
                $table->string('quick_links_label')->nullable();
                $table->string('contact_us_label')->nullable();
                $table->string('stay_updated_label')->nullable();
                $table->text('newsletter_text')->nullable();
                $table->string('newsletter_placeholder')->nullable();
                $table->string('newsletter_button')->nullable();
                $table->string('footer_copyright')->nullable();
                $table->string('vendor_label')->nullable();
                $table->string('vendor_name')->nullable();
                $table->string('vendor_url')->nullable();
            },
            'brosur' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
            'extracurricular' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
            'facility' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
                $table->string('badge', 50)->nullable();
                $table->string('stat_label', 100)->nullable();
                $table->string('capacity_label', 100)->nullable();
            },
            'job_vacancy' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
                $table->text('excerpt')->nullable();
                $table->text('requirements')->nullable();
            },
            'jurusan' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
                $table->string('short_name', 20)->nullable();
                $table->text('excerpt')->nullable();
                $table->text('vision')->nullable();
                $table->text('mission')->nullable();
            },
            'page' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
            'post' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
            'project' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
            'team_member' => function (Blueprint $table) {
                $table->string('title', 200)->nullable();
            },
        ];

        foreach ($modules as $singular => $plural) {
            Schema::create("{$singular}_translations", function (Blueprint $table) use ($singular, $plural, $translationSchemas) {
                $table->id();
                $table->foreignId("{$singular}_id")->constrained($plural)->cascadeOnDelete();
                $table->softDeletes();
                $table->timestamps();
                $table->string('locale', 7)->index();
                $table->boolean('active')->default(0);
                
                // Tambahkan field custom per modul
                $translationSchemas[$singular]($table);

                // Default Meta Fields (app_setting tidak ada `description` & `meta_title`)
                if ($singular !== 'app_setting') {
                    $table->text('description')->nullable();$table->string('meta_title', 70)->nullable();
                }
                $table->string('meta_description', 160)->nullable();

                $table->unique(["{$singular}_id", 'locale']);
            });
        }

        // ==============================================================================
        // 5. TABEL TWILL PENDUKUNG LAINNYA
        // ==============================================================================

        Schema::create('twill_blocks', function (Blueprint $table) {$table->id();
            $table->unsignedInteger('blockable_id')->nullable();$table->string('blockable_type')->nullable();
            $table->unsignedInteger('position');$table->json('content');
            $table->string('type');$table->string('child_key')->nullable();
            $table->unsignedInteger('parent_id')->nullable();$table->string('editor_name', 60)->nullable();

            $table->index(['blockable_type', 'blockable_id']);
        });

        Schema::create('twill_features', function (Blueprint $table) {$table->id();
            $table->string('featured_id', 36);$table->string('featured_type');
            $table->string('bucket_key')->index();$table->unsignedInteger('position');
            $table->boolean('starred')->default(0);$table->timestamps();

            $table->unique(['featured_id', 'featured_type', 'bucket_key']);
        });

        Schema::create('twill_fileables', function (Blueprint $table) {$table->id();
            $table->timestamps();$table->softDeletes();
            $table->foreignId('file_id')->constrained('twill_files')->cascadeOnUpdate()->cascadeOnDelete();$table->unsignedBigInteger('fileable_id')->nullable();
            $table->string('fileable_type')->nullable();$table->string('role')->nullable();
            $table->string('locale', 7)->index();$table->integer('position')->default(1);

            $table->index(['fileable_type', 'fileable_id']);
        });

        Schema::create('twill_mediables', function (Blueprint $table) {$table->id();
            $table->timestamps();$table->softDeletes();
            $table->unsignedBigInteger('mediable_id')->nullable();$table->string('mediable_type')->nullable();
            $table->foreignId('media_id')->constrained('twill_medias')->cascadeOnUpdate()->cascadeOnDelete();$table->integer('crop_x')->nullable();
            $table->integer('crop_y')->nullable();$table->integer('crop_w')->nullable();
            $table->integer('crop_h')->nullable();$table->string('role')->nullable();
            $table->string('crop')->nullable();$table->text('lqip_data')->nullable();
            $table->string('ratio')->nullable();$table->json('metadatas');
            $table->string('locale', 7)->default('en')->index();$table->integer('position')->default(1);

            $table->index(['mediable_type', 'mediable_id']);
        });

        Schema::create('twill_password_resets', function (Blueprint $table) {$table->string('email')->index();
            $table->string('token')->index();$table->timestamp('created_at')->nullable();

            $table->foreign('email')->references('email')->on('twill_users')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('twill_related', function (Blueprint $table) {$table->increments('id');
            $table->unsignedInteger('subject_id')->nullable();$table->string('subject_type');
            $table->unsignedInteger('related_id')->nullable();$table->string('related_type');
            $table->string('browser_name')->index();$table->unsignedInteger('position');

            $table->unique(['subject_id', 'subject_type', 'related_id', 'related_type', 'browser_name'], 'related_unique');
        });

        Schema::create('twill_setting_translations', function (Blueprint $table) {$table->id();
            $table->foreignId('twill_setting_id')->constrained('twill_settings')->cascadeOnDelete();$table->softDeletes();
            $table->timestamps();$table->string('locale', 7)->index();
            $table->boolean('active')->default(0);$table->text('value')->nullable();

            $table->unique(['twill_setting_id', 'locale']);
        });

        Schema::create('twill_tagged', function (Blueprint $table) {
            $table->id();$table->string('taggable_type');
            $table->unsignedInteger('taggable_id');$table->unsignedInteger('tag_id');

            $table->index(['taggable_type', 'taggable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Matikan pemeriksaan foreign key agar drop tabel tidak error karena saling berelasi
        Schema::disableForeignKeyConstraints();

        $tables = [
            'achievement_revisions', 'achievement_slugs', 'achievement_translations', 'achievements',
            'agenda_revisions', 'agenda_slugs', 'agenda_translations', 'agendas',
            'announcement_revisions', 'announcement_translations', 'announcements',
            'app_setting_revisions', 'app_setting_slugs', 'app_setting_translations', 'app_settings',
            'brosur_revisions', 'brosur_slugs', 'brosur_translations', 'brosurs',
            'extracurricular_revisions', 'extracurricular_slugs', 'extracurricular_translations', 'extracurriculars',
            'facility_revisions', 'facility_slugs', 'facility_translations', 'facilities',
            'job_vacancy_revisions', 'job_vacancy_slugs', 'job_vacancy_translations', 'job_vacancies',
            'jurusan_revisions', 'jurusan_slugs', 'jurusan_translations', 'jurusans',
            'menu_links',
            'page_revisions', 'page_slugs', 'page_translations', 'pages',
            'post_revisions', 'post_slugs', 'post_translations', 'posts',
            'project_revisions', 'project_slugs', 'project_translations', 'projects',
            'team_member_revisions', 'team_member_slugs', 'team_member_translations', 'team_members',
            'activity_log', 'cache', 'cache_locks', 'checkpoint_scan_results', 'newsletter_subscribers',
            'twill_blocks', 'twill_features', 'twill_fileables', 'twill_files', 'twill_mediables', 'twill_medias',
            'twill_password_resets', 'twill_related', 'twill_setting_translations', 'twill_settings',
            'twill_tagged', 'twill_tags', 'twill_users'
        ];

        foreach ($tables as$table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }
};
