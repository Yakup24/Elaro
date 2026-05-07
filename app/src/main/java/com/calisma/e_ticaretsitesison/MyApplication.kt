package com.calisma.e_ticaretsitesison

import android.app.Application
import com.calisma.e_ticaretsitesison.ui.network.SessionManager

class MyApplication : Application() {
    override fun onCreate() {
        super.onCreate()
        SessionManager.init(this)
    }
}

