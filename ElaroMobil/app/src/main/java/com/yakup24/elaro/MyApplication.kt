package com.yakup24.elaro

import android.app.Application
import com.yakup24.elaro.ui.network.SessionManager

class MyApplication : Application() {
    override fun onCreate() {
        super.onCreate()
        SessionManager.init(this)
    }
}

