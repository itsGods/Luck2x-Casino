<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // Update users table with all fields
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'unique_id')) $table->string('unique_id')->nullable();
            if (!Schema::hasColumn('users', 'username')) $table->string('username')->nullable();
            if (!Schema::hasColumn('users', 'avatar')) $table->string('avatar')->default('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/b5/b5bd56c1aa4644a474a2e4972be27ef9e82e517e_full.jpg');
            if (!Schema::hasColumn('users', 'sessionId')) $table->string('sessionId')->nullable();
            if (!Schema::hasColumn('users', 'user_id')) $table->string('user_id')->nullable();
            if (!Schema::hasColumn('users', 'balance')) $table->float('balance')->default(0);
            if (!Schema::hasColumn('users', 'bonus')) $table->float('bonus')->default(0);
            if (!Schema::hasColumn('users', 'requery')) $table->integer('requery')->default(0);
            if (!Schema::hasColumn('users', 'ip')) $table->string('ip')->nullable();
            if (!Schema::hasColumn('users', 'is_admin')) $table->integer('is_admin')->default(0);
            if (!Schema::hasColumn('users', 'superadmin')) $table->integer('superadmin')->default(0);
            if (!Schema::hasColumn('users', 'is_lowadmin')) $table->integer('is_lowadmin')->default(0);
            if (!Schema::hasColumn('users', 'is_moder')) $table->integer('is_moder')->default(0);
            if (!Schema::hasColumn('users', 'is_youtuber')) $table->integer('is_youtuber')->default(0);
            if (!Schema::hasColumn('users', 'fake')) $table->integer('fake')->default(0);
            if (!Schema::hasColumn('users', 'time')) $table->integer('time')->default(0);
            if (!Schema::hasColumn('users', 'banchat')) $table->integer('banchat')->default(0);
            if (!Schema::hasColumn('users', 'banchat_reason')) $table->string('banchat_reason')->nullable();
            if (!Schema::hasColumn('users', 'ban')) $table->integer('ban')->default(0);
            if (!Schema::hasColumn('users', 'ban_reason')) $table->string('ban_reason')->nullable();
            if (!Schema::hasColumn('users', 'link_trans')) $table->string('link_trans')->nullable();
            if (!Schema::hasColumn('users', 'link_reg')) $table->string('link_reg')->nullable();
            if (!Schema::hasColumn('users', 'ref_id')) $table->integer('ref_id')->default(0);
            if (!Schema::hasColumn('users', 'ref_money')) $table->float('ref_money')->default(0);
            if (!Schema::hasColumn('users', 'ref_money_all')) $table->float('ref_money_all')->default(0);
            if (!Schema::hasColumn('users', 'style')) $table->integer('style')->default(0);
            if (!Schema::hasColumn('users', 'rank')) $table->integer('rank')->default(0);
            if (!Schema::hasColumn('users', 'bsum')) $table->float('bsum')->default(0);
            if (!Schema::hasColumn('users', 'tg_id')) $table->string('tg_id')->nullable();
            if (!Schema::hasColumn('users', 'tg_bonus')) $table->integer('tg_bonus')->default(0);
            if (!Schema::hasColumn('users', 'clan_id')) $table->integer('clan_id')->default(0);
            if (!Schema::hasColumn('users', 'auth_token')) $table->string('auth_token')->nullable();
            if (!Schema::hasColumn('users', 'current_id')) $table->integer('current_id')->default(0);
        });

        // Settings table
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('domain')->nullable();
                $table->string('sitename')->default('Luck2x');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->text('keywords')->nullable();
                $table->integer('site_disable')->default(0);
                $table->string('vk_url')->nullable();
                $table->string('vk_support_link')->nullable();
                $table->string('vk_service_key')->nullable();
                $table->string('censore_replace')->default('***');
                $table->integer('chat_dep')->default(0);
                $table->integer('fakebets')->default(0);
                $table->float('fake_min_bet')->default(1);
                $table->float('fake_max_bet')->default(100);
                $table->string('fk_mrh_ID')->nullable();
                $table->string('fk_secret1')->nullable();
                $table->string('fk_secret2')->nullable();
                $table->string('fk_api')->nullable();
                $table->string('fk_wallet')->nullable();
                $table->string('payeer_mrh_ID')->nullable();
                $table->string('payeer_secret1')->nullable();
                $table->string('payeer_secret2')->nullable();
                $table->string('payeer_account_ID')->nullable();
                $table->string('payeer_api_ID')->nullable();
                $table->string('payeer_api_pass')->nullable();
                $table->float('profit_koef')->default(0.05);
                $table->float('profit_money')->default(0);
                $table->float('jackpot_commission')->default(5);
                $table->integer('wheel_timer')->default(30);
                $table->float('wheel_min_bet')->default(1);
                $table->float('wheel_max_bet')->default(10000);
                $table->string('wheel_rotate')->nullable();
                $table->string('wheel_rotate2')->nullable();
                $table->string('wheel_rotate_start')->nullable();
                $table->float('crash_min_bet')->default(1);
                $table->float('crash_max_bet')->default(10000);
                $table->integer('crash_timer')->default(10);
                $table->integer('battle_timer')->default(30);
                $table->float('battle_min_bet')->default(1);
                $table->float('battle_max_bet')->default(10000);
                $table->float('battle_commission')->default(5);
                $table->float('dice_min_bet')->default(1);
                $table->float('dice_max_bet')->default(10000);
                $table->float('flip_commission')->default(5);
                $table->float('flip_min_bet')->default(1);
                $table->float('flip_max_bet')->default(10000);
                $table->integer('hilo_timer')->default(30);
                $table->float('hilo_min_bet')->default(1);
                $table->float('hilo_max_bet')->default(10000);
                $table->integer('hilo_bets')->default(5);
                $table->float('exchange_min')->default(10);
                $table->float('exchange_curs')->default(1);
                $table->float('ref_perc')->default(5);
                $table->float('ref_sum')->default(50);
                $table->float('min_ref_withdraw')->default(10);
                $table->float('min_dep')->default(50);
                $table->float('min_dep_withdraw')->default(10);
                $table->integer('requery_perc')->default(10);
                $table->integer('requery_bet_perc')->default(3);
                $table->float('dep_bonus_min')->default(100);
                $table->integer('dep_bonus_perc')->default(100);
                $table->integer('bonus_group_time')->default(24);
                $table->integer('max_active_ref')->default(10);
                $table->float('payeer_com_percent')->default(0);
                $table->float('payeer_com_rub')->default(0);
                $table->float('payeer_min')->default(50);
                $table->float('qiwi_com_percent')->default(0);
                $table->float('qiwi_com_rub')->default(0);
                $table->float('qiwi_min')->default(50);
                $table->float('yandex_com_percent')->default(0);
                $table->float('yandex_com_rub')->default(0);
                $table->float('yandex_min')->default(50);
                $table->float('webmoney_com_percent')->default(0);
                $table->float('webmoney_com_rub')->default(0);
                $table->float('webmoney_min')->default(50);
                $table->float('visa_com_percent')->default(0);
                $table->float('visa_com_rub')->default(0);
                $table->float('visa_min')->default(50);
            });
        }

        // Crash table
        if (!Schema::hasTable('crash')) {
            Schema::create('crash', function (Blueprint $table) {
                $table->increments('id');
                $table->float('multiplier')->default(0);
                $table->float('profit')->default(0);
                $table->string('hash')->nullable();
                $table->string('status')->default('wait');
                $table->timestamps();
            });
        }

        // Crash bets table
        if (!Schema::hasTable('crash_bets')) {
            Schema::create('crash_bets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->integer('round_id')->default(0);
                $table->float('price')->default(0);
                $table->float('withdraw')->default(0);
                $table->float('won')->default(0);
                $table->string('status')->default('wait');
                $table->integer('fake')->default(0);
                $table->string('balType')->default('balance');
                $table->timestamps();
            });
        }

        // Wheel/roulette table
        if (!Schema::hasTable('wheel')) {
            Schema::create('wheel', function (Blueprint $table) {
                $table->increments('id');
                $table->string('winnder_color')->nullable();
                $table->float('price')->default(0);
                $table->string('status')->default('wait');
                $table->string('hash')->nullable();
                $table->float('profit')->default(0);
                $table->integer('ranked')->default(0);
                $table->timestamps();
            });
        }

        // Wheel bets table
        if (!Schema::hasTable('wheel_bets')) {
            Schema::create('wheel_bets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->integer('game_id')->default(0);
                $table->float('price')->default(0);
                $table->string('color')->nullable();
                $table->integer('win')->default(0);
                $table->float('balance')->default(0);
                $table->float('win_sum')->default(0);
                $table->integer('fake')->default(0);
                $table->timestamps();
            });
        }

        // Jackpot table
        if (!Schema::hasTable('jackpot')) {
            Schema::create('jackpot', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('room')->default(1);
                $table->integer('game_id')->default(0);
                $table->integer('winner_id')->default(0);
                $table->string('winner_ticket')->nullable();
                $table->float('winner_balance')->default(0);
                $table->float('winner_bonus')->default(0);
                $table->string('hash')->nullable();
                $table->string('status')->default('wait');
                $table->timestamps();
            });
        }

        // Jackpot bets table
        if (!Schema::hasTable('jackpot_bets')) {
            Schema::create('jackpot_bets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('room')->default(1);
                $table->integer('game_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->float('sum')->default(0);
                $table->string('color')->nullable();
                $table->float('balance')->default(0);
                $table->integer('win')->default(0);
                $table->integer('fake')->default(0);
                $table->timestamps();
            });
        }

        // Battle table
        if (!Schema::hasTable('battle')) {
            Schema::create('battle', function (Blueprint $table) {
                $table->increments('id');
                $table->float('price')->default(0);
                $table->float('commission')->default(0);
                $table->string('winner_team')->nullable();
                $table->float('winner_factor')->default(0);
                $table->string('winner_ticket')->nullable();
                $table->string('status')->default('wait');
                $table->string('hash')->nullable();
                $table->timestamps();
            });
        }

        // Battle bets table
        if (!Schema::hasTable('battle_bets')) {
            Schema::create('battle_bets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->integer('battle_id')->default(0);
                $table->float('sum')->default(0);
                $table->string('team')->nullable();
                $table->float('ticket_from')->default(0);
                $table->float('ticket_to')->default(0);
                $table->integer('win')->default(0);
                $table->float('win_sum')->default(0);
                $table->integer('fake')->default(0);
                $table->timestamps();
            });
        }

        // Dice table
        if (!Schema::hasTable('dice')) {
            Schema::create('dice', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->float('sum')->default(0);
                $table->float('perc')->default(0);
                $table->integer('vip')->default(0);
                $table->float('num')->default(0);
                $table->string('range')->nullable();
                $table->integer('win')->default(0);
                $table->float('win_sum')->default(0);
                $table->string('balType')->default('balance');
                $table->integer('fake')->default(0);
                $table->string('hash')->nullable();
                $table->timestamps();
            });
        }

        // CoinFlip table
        if (!Schema::hasTable('flip')) {
            Schema::create('flip', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('heads')->default(0);
                $table->float('heads_from')->default(0);
                $table->float('heads_to')->default(0);
                $table->integer('tails')->default(0);
                $table->float('tails_from')->default(0);
                $table->float('tails_to')->default(0);
                $table->float('bank')->default(0);
                $table->integer('winner_id')->default(0);
                $table->string('winner_ticket')->nullable();
                $table->float('winner_sum')->default(0);
                $table->string('balType')->default('balance');
                $table->string('hash')->nullable();
                $table->string('status')->default('wait');
                $table->timestamps();
            });
        }

        // Hilo table
        if (!Schema::hasTable('hilo')) {
            Schema::create('hilo', function (Blueprint $table) {
                $table->increments('id');
                $table->string('card_name')->nullable();
                $table->integer('card_amount')->default(0);
                $table->string('card_section')->nullable();
                $table->string('card_sign')->nullable();
                $table->string('status')->default('wait');
                $table->float('profit')->default(0);
                $table->string('hash')->nullable();
                $table->timestamps();
            });
        }

        // Hilo bets table
        if (!Schema::hasTable('hilo_bets')) {
            Schema::create('hilo_bets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('game_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->string('bet_type')->nullable();
                $table->float('bet_x')->default(0);
                $table->float('sum')->default(0);
                $table->float('balance')->default(0);
                $table->integer('win')->default(0);
                $table->float('win_sum')->default(0);
                $table->integer('win_bonus')->default(0);
                $table->timestamps();
            });
        }

        // Roulette table
        if (!Schema::hasTable('roulette')) {
            Schema::create('roulette', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('winner_num')->default(0);
                $table->string('winner_color')->nullable();
                $table->float('price')->default(0);
                $table->string('status')->default('wait');
                $table->integer('ranked')->default(0);
                $table->string('hash')->nullable();
                $table->timestamps();
            });
        }

        // Roulette bets table
        if (!Schema::hasTable('roulettebets')) {
            Schema::create('roulettebets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->integer('round_id')->default(0);
                $table->float('price')->default(0);
                $table->string('type')->nullable();
                $table->integer('win')->default(0);
                $table->float('win_sum')->default(0);
                $table->integer('is_fake')->default(0);
                $table->timestamps();
            });
        }

        // King table
        if (!Schema::hasTable('king')) {
            Schema::create('king', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('winner_id')->default(0);
                $table->float('bank')->default(0);
                $table->string('status')->default('wait');
                $table->timestamps();
            });
        }

        // King bets table
        if (!Schema::hasTable('king_bets')) {
            Schema::create('king_bets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->integer('game_id')->default(0);
                $table->float('bet')->default(0);
                $table->timestamps();
            });
        }

        // Mines table
        if (!Schema::hasTable('mines')) {
            Schema::create('mines', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_users')->default(0);
                $table->string('login')->nullable();
                $table->integer('num_mines')->default(1);
                $table->float('bet')->default(0);
                $table->text('mines')->nullable();
                $table->text('click')->nullable();
                $table->integer('onOff')->default(0);
                $table->integer('result')->default(0);
                $table->integer('step')->default(0);
                $table->integer('win')->default(0);
                $table->float('can_open')->default(0);
                $table->float('multiplayer')->default(0);
                $table->float('total')->default(0);
                $table->timestamps();
            });
        }

        // Tower table
        if (!Schema::hasTable('towers')) {
            Schema::create('towers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->float('bet')->default(0);
                $table->integer('bombs')->default(1);
                $table->string('currency')->default('balance');
                $table->text('field')->nullable();
                $table->text('revealed')->nullable();
                $table->float('coeff')->default(1);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // Payments table
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->string('secret')->nullable();
                $table->string('order_id')->nullable();
                $table->float('sum')->default(0);
                $table->string('status')->default('pending');
                $table->string('system')->nullable();
                $table->timestamps();
            });
        }

        // Withdraw table
        if (!Schema::hasTable('withdraw')) {
            Schema::create('withdraw', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->float('value')->default(0);
                $table->float('valueWithCom')->default(0);
                $table->string('wallet')->nullable();
                $table->string('system')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        // Promocode table
        if (!Schema::hasTable('promocode')) {
            Schema::create('promocode', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->string('code')->unique();
                $table->integer('limit')->default(0);
                $table->float('amount')->default(0);
                $table->integer('count_use')->default(0);
                $table->string('type')->default('balance');
                $table->timestamps();
            });
        }

        // Promo log table
        if (!Schema::hasTable('promo_log')) {
            Schema::create('promo_log', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->float('sum')->default(0);
                $table->string('code')->nullable();
                $table->string('type')->default('balance');
                $table->timestamps();
            });
        }

        // Ranks table
        if (!Schema::hasTable('ranks')) {
            Schema::create('ranks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->string('style')->nullable();
                $table->integer('points')->default(0);
                $table->float('bonus')->default(0);
                $table->string('icon')->nullable();
                $table->text('ids')->nullable();
                $table->timestamps();
            });
        }

        // Styles table
        if (!Schema::hasTable('styles')) {
            Schema::create('styles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('css')->nullable();
                $table->timestamps();
            });
        }

        // Bonus table
        if (!Schema::hasTable('bonus')) {
            Schema::create('bonus', function (Blueprint $table) {
                $table->increments('id');
                $table->float('sum')->default(0);
                $table->string('bg')->nullable();
                $table->string('color')->nullable();
                $table->integer('status')->default(1);
                $table->string('type')->default('balance');
                $table->timestamps();
            });
        }

        // Bonus log table
        if (!Schema::hasTable('bonus_log')) {
            Schema::create('bonus_log', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->float('sum')->default(0);
                $table->float('remaining')->default(0);
                $table->integer('status')->default(0);
                $table->string('type')->default('balance');
                $table->timestamps();
            });
        }

        // Giveaway table
        if (!Schema::hasTable('giveaway')) {
            Schema::create('giveaway', function (Blueprint $table) {
                $table->increments('id');
                $table->float('sum')->default(0);
                $table->string('type')->default('balance');
                $table->integer('time_to')->default(0);
                $table->string('group_sub')->nullable();
                $table->float('min_dep')->default(0);
                $table->integer('winner_id')->default(0);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // Giveaway users table
        if (!Schema::hasTable('giveaway_users')) {
            Schema::create('giveaway_users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('giveaway_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->timestamps();
            });
        }

        // Clans table
        if (!Schema::hasTable('clans')) {
            Schema::create('clans', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('members')->default(0);
                $table->string('name')->nullable();
                $table->string('avatar')->nullable();
                $table->string('background')->nullable();
                $table->integer('admin_id')->default(0);
                $table->integer('need_points')->default(0);
                $table->integer('points')->default(0);
                $table->timestamps();
            });
        }

        // Filter table
        if (!Schema::hasTable('filter')) {
            Schema::create('filter', function (Blueprint $table) {
                $table->increments('id');
                $table->string('word')->nullable();
                $table->timestamps();
            });
        }

        // Rooms table
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('title')->nullable();
                $table->float('min')->default(0);
                $table->float('max')->default(0);
                $table->integer('bets')->default(0);
                $table->integer('time')->default(0);
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        // Tournament table
        if (!Schema::hasTable('tournament')) {
            Schema::create('tournament', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->float('reward')->default(0);
                $table->integer('members')->default(0);
                $table->string('status')->default('active');
                $table->integer('start')->default(0);
                $table->integer('end')->default(0);
                $table->timestamps();
            });
        }

        // Tournament players table
        if (!Schema::hasTable('tournamentPlayers')) {
            Schema::create('tournamentPlayers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('tour_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->integer('bets')->default(0);
                $table->timestamps();
            });
        }

        // Profit table
        if (!Schema::hasTable('profit')) {
            Schema::create('profit', function (Blueprint $table) {
                $table->increments('id');
                $table->string('game')->nullable();
                $table->float('sum')->default(0);
                $table->timestamps();
            });
        }

        // Sends table
        if (!Schema::hasTable('sends')) {
            Schema::create('sends', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('sender')->default(0);
                $table->integer('receiver')->default(0);
                $table->float('sum')->default(0);
                $table->timestamps();
            });
        }

        // Exchanges table
        if (!Schema::hasTable('exchanges')) {
            Schema::create('exchanges', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->float('sum')->default(0);
                $table->timestamps();
            });
        }

        // Slots table
        if (!Schema::hasTable('slots')) {
            Schema::create('slots', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('title')->nullable();
                $table->string('image')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        // Slots data table
        if (!Schema::hasTable('slots_data')) {
            Schema::create('slots_data', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->default(0);
                $table->string('trx_id')->nullable();
                $table->string('game_id')->nullable();
                $table->float('amount')->default(0);
                $table->string('type')->nullable();
                $table->float('balanceBefore')->default(0);
                $table->float('balanceAfter')->default(0);
                $table->timestamps();
            });
        }

        // Success pay table
        if (!Schema::hasTable('success_pay')) {
            Schema::create('success_pay', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user')->default(0);
                $table->float('price')->default(0);
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        $tables = ['settings', 'crash', 'crash_bets', 'wheel', 'wheel_bets', 
                   'jackpot', 'jackpot_bets', 'battle', 'battle_bets', 'dice',
                   'flip', 'hilo', 'hilo_bets', 'roulette', 'roulettebets',
                   'king', 'king_bets', 'mines', 'towers', 'payments', 'withdraw',
                   'promocode', 'promo_log', 'ranks', 'styles', 'bonus', 'bonus_log',
                   'giveaway', 'giveaway_users', 'clans', 'filter', 'rooms',
                   'tournament', 'tournamentPlayers', 'profit', 'sends', 'exchanges',
                   'slots', 'slots_data', 'success_pay'];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
