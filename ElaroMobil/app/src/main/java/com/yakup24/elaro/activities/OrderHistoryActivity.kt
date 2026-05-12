package com.yakup24.elaro.activities

import android.os.Bundle
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.yakup24.elaro.R

class OrderHistoryActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_order_history)

        val tvOrders = findViewById<TextView>(R.id.tvOrderHistory)
        tvOrders.text = "Henüz bir sipariş geçmişiniz yok."
    }
}
