<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('user_counts')) {
	function user_counts() {
		return DB::table('users')->count();
	}
}