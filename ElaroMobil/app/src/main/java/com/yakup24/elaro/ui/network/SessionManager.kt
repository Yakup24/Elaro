package com.yakup24.elaro.ui.network

import android.annotation.SuppressLint
import android.content.Context
import android.content.SharedPreferences

object SessionManager {
    private var prefs: SharedPreferences? = null

    fun init(context: Context) {
        if (prefs == null) {
            prefs = context.getSharedPreferences("user_session", Context.MODE_PRIVATE)
        }
    }

    var loginEmail: String
        get() = prefs?.getString("login_email", "") ?: ""
        @SuppressLint("ApplySharedPref", "UseKtx")
        set(value) {
            prefs?.edit()?.putString("login_email", value)?.apply()
        }

    var userId: Int
        get() = prefs?.getInt("user_id", 0) ?: 0
        @SuppressLint("ApplySharedPref", "UseKtx")
        set(value) {
            prefs?.edit()?.putInt("user_id", value)?.apply()
        }

    var firstName: String
        get() = prefs?.getString("first_name", "") ?: ""
        @SuppressLint("ApplySharedPref", "UseKtx")
        set(value) {
            prefs?.edit()?.putString("first_name", value)?.apply()
        }

    var lastName: String
        get() = prefs?.getString("last_name", "") ?: ""
        @SuppressLint("ApplySharedPref", "UseKtx")
        set(value) {
            prefs?.edit()?.putString("last_name", value)?.apply()
        }

    var accessToken: String
        get() = prefs?.getString("access_token", "") ?: ""
        @SuppressLint("ApplySharedPref", "UseKtx")
        set(value) {
            prefs?.edit()?.putString("access_token", value)?.apply()
        }

    fun logout() {
        prefs?.edit()?.clear()?.apply()
    }
}
