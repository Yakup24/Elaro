package com.calisma.e_ticaretsitesison.activities

import android.os.Bundle
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.calisma.e_ticaretsitesison.R

class OrderHistoryActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_order_history)

        val tvOrders = findViewById<TextView>(R.id.tvOrderHistory)
        tvOrders.text = "Henüz bir sipariş geçmişiniz yok."
    }
}
