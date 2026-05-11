package com.calisma.e_ticaretsitesison.activities

import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.calisma.e_ticaretsitesison.*
import com.calisma.e_ticaretsitesison.models.Urun
import com.calisma.e_ticaretsitesison.ui.network.ApiConstants
import com.calisma.e_ticaretsitesison.ui.network.SessionManager
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import okhttp3.*
import java.io.IOException
import com.calisma.e_ticaretsitesison.models.Kategori
import com.calisma.e_ticaretsitesison.ProductAdapter

class MainActivity : AppCompatActivity() {

    private lateinit var etSearch: EditText
    private lateinit var rvPopularProducts: RecyclerView
    private lateinit var rvFlashProducts: RecyclerView
    private lateinit var tvPopularTitle: TextView
    private lateinit var tvFlashTitle: TextView
    private lateinit var bottomNav: BottomNavigationView

    private lateinit var productAdapterPopular: ProductAdapter
    private lateinit var productAdapterFlash: ProductAdapter

    private var allProducts = listOf<Urun>()
    private val client = OkHttpClient()
    private val gson = Gson()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        etSearch = findViewById(R.id.etSearch)
        rvPopularProducts = findViewById(R.id.rvPopularProducts)
        rvFlashProducts = findViewById(R.id.rvFlashProducts)
        tvPopularTitle = findViewById(R.id.tvPopularTitle)
        tvFlashTitle = findViewById(R.id.tvFlashTitle)
        bottomNav = findViewById(R.id.bottomNav)

        // Dikey ürün listesi ayarı
        rvPopularProducts.layoutManager = LinearLayoutManager(this)
        rvFlashProducts.layoutManager = LinearLayoutManager(this)

        productAdapterPopular = ProductAdapter(emptyList(), {}, {})
        productAdapterFlash = ProductAdapter(emptyList(), {}, {})

        rvPopularProducts.adapter = productAdapterPopular
        rvFlashProducts.adapter = productAdapterFlash

        etSearch.addTextChangedListener(object : TextWatcher {
            override fun afterTextChanged(s: Editable?) {
                val query = s.toString().lowercase()
                val filtered = allProducts.filter { it.ad?.lowercase()?.contains(query) == true }
                productAdapterPopular.updateList(filtered)
                productAdapterFlash.updateList(filtered)
            }

            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {}
        })

        bottomNav.selectedItemId = R.id.nav_home
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_home -> true
                R.id.nav_favorites -> {
                    startActivity(Intent(this, FavoritesActivity::class.java)); true
                }
                R.id.nav_cart -> {
                    startActivity(Intent(this, CartActivity::class.java)); true
                }
                R.id.nav_account -> {
                    val email = SessionManager.loginEmail
                    val target = if (email.isNullOrEmpty()) LoginActivity::class.java else AccountActivity::class.java
                    startActivity(Intent(this, target)); true
                }
                else -> false
            }
        }

        loadAllProducts()
    }

    private fun loadAllProducts() {
        val url = ApiConstants.BASE_URL + "Urun"
        val request = Request.Builder().url(url).build()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    Toast.makeText(this@MainActivity, "Ürünler yüklenemedi: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val body = response.body?.string()
                if (response.isSuccessful && body != null) {
                    try {
                        val type = object : TypeToken<List<Urun>>() {}.type
                        val urunList: List<Urun> = gson.fromJson(body, type)
                        allProducts = urunList

                        runOnUiThread {
                            productAdapterPopular.updateList(urunList)
                            productAdapterFlash.updateList(urunList)
                        }

                    } catch (e: Exception) {
                        runOnUiThread {
                            Toast.makeText(this@MainActivity, "JSON ayrıştırma hatası: ${e.message}", Toast.LENGTH_SHORT).show()
                        }
                    }
                } else {
                    runOnUiThread {
                        Toast.makeText(this@MainActivity, "Sunucu yanıtı başarısız", Toast.LENGTH_SHORT).show()
                    }
                }
            }
        })
    }
}

