package com.calisma.e_ticaretsitesison.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.calisma.e_ticaretsitesison.R
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.calisma.e_ticaretsitesison.ui.network.SessionManager

class AccountActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_account)

        val tvUserInfo = findViewById<TextView>(R.id.tvUserInfo)
        val btnOrders = findViewById<Button>(R.id.btnOrders)
        val btnAddresses = findViewById<Button>(R.id.btnAddresses)
        val btnLogout = findViewById<Button>(R.id.btnLogout)
        val bottomNav = findViewById<BottomNavigationView>(R.id.bottomNav)

        // Kullanıcı bilgilerini SessionManager'dan çek
        val ad = SessionManager.firstName
        val soyad = SessionManager.lastName

        tvUserInfo.text = "Hoş geldin, $ad $soyad"

        btnOrders.setOnClickListener {
            startActivity(Intent(this, OrderHistoryActivity::class.java))
        }

        btnAddresses.setOnClickListener {
            startActivity(Intent(this, AddressActivity::class.java))
        }

        btnLogout.setOnClickListener {
            // Tüm oturum bilgilerini temizle
            SessionManager.logout()
            // Login ekranına yönlendir
            val intent = Intent(this, LoginActivity::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            startActivity(intent)
            finish()
        }

        bottomNav.selectedItemId = R.id.nav_account
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_home -> {
                    startActivity(Intent(this, MainActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_favorites -> {
                    startActivity(Intent(this, FavoritesActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_cart -> {
                    startActivity(Intent(this, CartActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_account -> true
                else -> false
            }
        }
    }
}
