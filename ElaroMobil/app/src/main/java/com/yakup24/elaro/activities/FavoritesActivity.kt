package com.yakup24.elaro.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.yakup24.elaro.*
import com.yakup24.elaro.models.Urun
import com.yakup24.elaro.ui.network.SessionManager
import com.google.android.material.bottomnavigation.BottomNavigationView

class FavoritesActivity : AppCompatActivity() {

    private lateinit var rvFavorites: RecyclerView
    private lateinit var productAdapter: ProductAdapter
    private var favoriteList = mutableListOf<Urun>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_favorites)

        // RecyclerView ayarı
        rvFavorites = findViewById(R.id.rvFavorites)
        rvFavorites.layoutManager = LinearLayoutManager(this)

        // Favori listesini
        favoriteList = FavoritesManager.getFavorites().toMutableList()

        // Adapter
        productAdapter = ProductAdapter(
            favoriteList,
            onItemClick = { product ->
                val intent = Intent(this, ProductDetailActivity::class.java)
                intent.putExtra("selectedProduct", product)
                startActivity(intent)
            },
            onRemoveClick = { product ->
                FavoritesManager.removeFromFavorites(product.urunID)
                favoriteList.remove(product)
                productAdapter.updateList(favoriteList)
                Toast.makeText(this, "${product.ad} favorilerden çıkarıldı", Toast.LENGTH_SHORT).show()
            }
        )

        rvFavorites.adapter = productAdapter

        // Alt Navigasyon Menüsü
        val bottomNav = findViewById<BottomNavigationView>(R.id.bottomNav)
        bottomNav.selectedItemId = R.id.nav_favorites

        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_home -> {
                    startActivity(Intent(this, MainActivity::class.java))
                    true
                }
                R.id.nav_favorites -> true
                R.id.nav_cart -> {
                    startActivity(Intent(this, CartActivity::class.java))
                    true
                }
                R.id.nav_account -> {
                    val target = if (SessionManager.loginEmail.isEmpty())
                        LoginActivity::class.java else AccountActivity::class.java
                    startActivity(Intent(this, target))
                    true
                }
                else -> false
            }
        }
    }
}
