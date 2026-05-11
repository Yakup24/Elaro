package com.calisma.e_ticaretsitesison.activities

import android.content.Intent
import android.os.Bundle
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.calisma.e_ticaretsitesison.*
import com.calisma.e_ticaretsitesison.models.CartItem
import com.calisma.e_ticaretsitesison.ui.network.ApiConstants
import com.calisma.e_ticaretsitesison.ui.network.SessionManager
import com.google.android.material.bottomnavigation.BottomNavigationView
import okhttp3.*
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.RequestBody.Companion.toRequestBody
import org.json.JSONArray
import org.json.JSONObject
import java.io.IOException

class CartActivity : AppCompatActivity() {

    private lateinit var rvCartItems: RecyclerView
    private lateinit var tvTotalPrice: TextView
    private lateinit var btnCheckout: Button
    private lateinit var cartAdapter: CartAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_cart)

        // XML bağlantıları
        rvCartItems = findViewById(R.id.rvCartItems)
        tvTotalPrice = findViewById(R.id.tvTotalPrice)
        btnCheckout = findViewById(R.id.btnCheckout)

        // RecyclerView ve adapter
        rvCartItems.layoutManager = LinearLayoutManager(this)
        cartAdapter = CartAdapter(CartManager.items.toMutableList()) { itemToRemove ->
            CartManager.removeFromCart(itemToRemove.urun.urunID)
            cartAdapter.removeItem(itemToRemove)
            updateTotalPrice()
            Toast.makeText(this, "${itemToRemove.urun.ad} sepetten çıkarıldı", Toast.LENGTH_SHORT).show()
        }
        rvCartItems.adapter = cartAdapter

        updateTotalPrice()

        // Sipariş tamamla
        btnCheckout.setOnClickListener {
            if (CartManager.items.isEmpty()) {
                Toast.makeText(this, "Sepetiniz boş", Toast.LENGTH_SHORT).show()
            } else {
                performCheckout()
            }
        }

        // Alt menü navigasyonu
        val bottomNav = findViewById<BottomNavigationView>(R.id.bottomNav)
        bottomNav.selectedItemId = R.id.nav_cart
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_home -> {
                    startActivity(Intent(this, MainActivity::class.java)); true
                }
                R.id.nav_favorites -> {
                    startActivity(Intent(this, FavoritesActivity::class.java)); true
                }
                R.id.nav_cart -> true
                R.id.nav_account -> {
                    val target = if (SessionManager.loginEmail.isEmpty())
                        LoginActivity::class.java else AccountActivity::class.java
                    startActivity(Intent(this, target)); true
                }
                else -> false
            }
        }
    }

    private fun updateTotalPrice() {
        val total = CartManager.getTotalPrice()
        tvTotalPrice.text = "Toplam: ₺%.2f".format(total)
    }

    private fun performCheckout() {
        val customerEmail = SessionManager.loginEmail
        if (customerEmail.isEmpty()) {
            Toast.makeText(this, "Lütfen giriş yapınız", Toast.LENGTH_LONG).show()
            return
        }

        val orderJson = JSONObject().apply {
            put("musteriEmail", customerEmail)
            put("adresID", 1) // gerektiğinde gerçek adresID alınmalı
            put("odemeBilgisiID", 1) // aynı şekilde dinamik olmalı

            val itemsArray = JSONArray()
            for (item in CartManager.items) {
                val itemObj = JSONObject().apply {
                    put("urunID", item.urun.urunID)
                    put("adet", item.quantity)
                }
                itemsArray.put(itemObj)
            }
            put("urunler", itemsArray)
        }

        val url = ApiConstants.BASE_URL + "Siparis"
        val mediaType = "application/json; charset=utf-8".toMediaType()
        val body = orderJson.toString().toRequestBody(mediaType)
        val request = Request.Builder().url(url).post(body).build()

        OkHttpClient().newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    Toast.makeText(this@CartActivity, "Sipariş başarısız: ${e.message}", Toast.LENGTH_LONG).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val resp = response.body?.string()
                runOnUiThread {
                    if (response.isSuccessful) {
                        Toast.makeText(this@CartActivity, "Siparişiniz başarıyla oluşturuldu", Toast.LENGTH_LONG).show()
                        CartManager.clearCart()
                        cartAdapter.notifyDataSetChanged()
                        updateTotalPrice()
                    } else {
                        Toast.makeText(this@CartActivity, "Sipariş başarısız: $resp", Toast.LENGTH_LONG).show()
                    }
                }
            }
        })
    }
}
