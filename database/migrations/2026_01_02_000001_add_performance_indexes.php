<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // subscriptions: activeSubscriptionFor() filters user_id + creator_id + status
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['user_id', 'creator_id', 'status'], 'subscriptions_user_creator_status_index');
            $table->index(['user_id', 'status'], 'subscriptions_user_status_index');
            $table->index(['ends_at'], 'subscriptions_ends_at_index');
        });

        // posts: frequent visibility + status + user_id filtering
        Schema::table('posts', function (Blueprint $table) {
            $table->index(['user_id', 'visibility'], 'posts_user_visibility_index');
            $table->index(['user_id', 'status'], 'posts_user_status_index');
            $table->index(['status', 'published_at'], 'posts_status_published_at_index');
            $table->index(['created_at'], 'posts_created_at_index');
        });

        // creator_profiles: explore/home filtering and sorting
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->index(['verification_status', 'is_featured'], 'creator_profiles_verification_featured_index');
            $table->index(['verification_status', 'subscriber_count'], 'creator_profiles_verification_subscriber_index');
            $table->index(['verification_status', 'created_at'], 'creator_profiles_verification_created_index');
        });

        // blocks: both directions are queried (blocker_id OR blocked_id)
        Schema::table('blocks', function (Blueprint $table) {
            $table->index(['blocked_id'], 'blocks_blocked_id_index');
            $table->index(['blocker_id', 'blocked_id'], 'blocks_blocker_blocked_index');
        });

        // payment_transactions: dashboard sums filtered by creator_id + status
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->index(['user_id'], 'payment_transactions_user_id_index');
            $table->index(['subscription_id'], 'payment_transactions_subscription_id_index');
            $table->index(['status', 'created_at'], 'payment_transactions_status_created_index');
        });

        // notifications: morph + read_at queries
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['read_at'], 'notifications_read_at_index');
            $table->index(['created_at'], 'notifications_created_at_index');
        });

        // audit_logs: frequently filtered by user + action + entity
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['user_id'], 'audit_logs_user_id_index');
            $table->index(['action'], 'audit_logs_action_index');
        });

        // follows: follower feed plucks
        // follows already has unique (follower_id,following_id) and index(following_id)
        // add index for follower_id lookups
        Schema::table('follows', function (Blueprint $table) {
            $table->index(['follower_id'], 'follows_follower_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_user_creator_status_index');
            $table->dropIndex('subscriptions_user_status_index');
            $table->dropIndex('subscriptions_ends_at_index');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_user_visibility_index');
            $table->dropIndex('posts_user_status_index');
            $table->dropIndex('posts_status_published_at_index');
            $table->dropIndex('posts_created_at_index');
        });

        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropIndex('creator_profiles_verification_featured_index');
            $table->dropIndex('creator_profiles_verification_subscriber_index');
            $table->dropIndex('creator_profiles_verification_created_index');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->dropIndex('blocks_blocked_id_index');
            $table->dropIndex('blocks_blocker_blocked_index');
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropIndex('payment_transactions_user_id_index');
            $table->dropIndex('payment_transactions_subscription_id_index');
            $table->dropIndex('payment_transactions_status_created_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_read_at_index');
            $table->dropIndex('notifications_created_at_index');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_user_id_index');
            $table->dropIndex('audit_logs_action_index');
        });

        Schema::table('follows', function (Blueprint $table) {
            $table->dropIndex('follows_follower_id_index');
        });
    }
};
