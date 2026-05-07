package com.calisma.e_ticaretsitesison.activities

import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.bumptech.glide.Glide
import com.calisma.e_ticaretsitesison.CartManager
import com.calisma.e_ticaretsitesison.FavoritesManager
import com.calisma.e_ticaretsitesison.R
import com.calisma.e_ticaretsitesison.models.Urun

class ProductDetailActivity : AppCompatActivity() {

    private lateinit var imgProductLarge: ImageView
    private lateinit var tvName: TextView
    private lateinit var tvPrice: TextView
    private lateinit var tvDesc: TextView
    private lateinit var tvDetails: TextView
    private lateinit var btnAddToCart: Button
    private lateinit var btnAddToFavorites: Button

    private var urun: Urun? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_product_detail)

        imgProductLarge = findViewById(R.id.imgProductLarge)
        tvName = findViewById(R.id.tvProductNameDetail)
        tvPrice = findViewById(R.id.tvProductPriceDetail)
        tvDesc = findViewById(R.id.tvProductDesc)
        tvDetails = findViewById(R.id.tvProductDetails)
        btnAddToCart = findViewById(R.id.btnAddToCart)
        btnAddToFavorites = findViewById(R.id.btnAddToFavorites)

        // Ürün verisini intent ile al
        urun = intent.getParcelableExtra("selectedProduct")

        if (urun != null) {
            displayProductInfo(urun!!)
        } else {
            Toast.makeText(this, "Ürün bilgisi alınamadı", Toast.LENGTH_SHORT).show()
            finish()
        }

        btnAddToCart.setOnClickListener {
            urun?.let {
                CartManager.addToCart(it)
                Toast.makeText(this, "${it.ad} sepete eklendi", Toast.LENGTH_SHORT).show()
            }
        }

        btnAddToFavorites.setOnClickListener {
            urun?.let {
                FavoritesManager.addToFavorites(it)
                Toast.makeText(this, "${it.ad} favorilere eklendi", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun displayProductInfo(urun: Urun) {
        tvName.text = urun.ad
        tvPrice.text = "₺ %.2f".format(urun.fiyat)
        tvDesc.text = urun.aciklama
        tvDetails.text = "Marka: ${urun.marka} | Renk: ${urun.renk} | Stok: ${urun.stokAdedi}"

        Glide.with(this)
            .load(urun.gorselURL)
            .placeholder(R.drawable.placeholder_resim_background)
            .error(R.drawable.error_resim)
            .into(imgProductLarge)
    }
}
